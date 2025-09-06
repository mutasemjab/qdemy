<?php
namespace App\Traits;

use App\Models\Category;
use App\Models\CategorySubject;
use App\Models\Subject;

trait SubjectCategoryTrait
{
    /**
     * Get all subjects for a given category considering the hierarchy and pivot relationships
     * 
     * @param int $categoryId
     * @param bool $includeInactive
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategorySubjects($categoryId, $includeInactive = false)
    {
        if (!$categoryId) {
            return collect();
        }

        $category = Category::find($categoryId);
        if (!$category) {
            return collect();
        }

        $subjectIds = collect();

        // 1. Get subjects directly linked through pivot table (category_subjects)
        $pivotSubjects = CategorySubject::where('category_id', $categoryId)
            ->pluck('subject_id');
        $subjectIds = $subjectIds->merge($pivotSubjects);

        // 2. Get subjects linked through direct foreign keys based on category level/type
        $directSubjects = $this->getDirectSubjectsByCategoryLevel($category);
        $subjectIds = $subjectIds->merge($directSubjects);

        // 3. If this is a parent category, get subjects from children
        $childrenSubjects = $this->getSubjectsFromChildren($categoryId);
        $subjectIds = $subjectIds->merge($childrenSubjects);

        // 4. Remove duplicates and get actual subject records
        $subjectIds = $subjectIds->unique()->filter();

        $query = Subject::whereIn('id', $subjectIds);
        
        if (!$includeInactive) {
            $query->active();
        }

        return $query->ordered()->get();
    }

    /**
     * Get subjects based on category level and direct foreign key relationships
     */
    private function getDirectSubjectsByCategoryLevel($category)
    {
        $subjectIds = collect();

        // Based on the seeder structure:
        switch ($category->level) {
            case 'tawjihi_grade': // توجيهي 2009, توجيهي 2008
                $subjectIds = Subject::where('grade_id', $category->id)->pluck('id');
                break;

            case 'elementray_grade': // الصف الأول, الصف الثاني, etc.
                $subjectIds = Subject::where('grade_id', $category->id)->pluck('id');
                break;

            case 'semester': // الفصل الأول, الفصل الثاني
                $subjectIds = Subject::where('semester_id', $category->id)->pluck('id');
                break;

            case 'tawjihi_scientific_fields': // الحقل الطبي, الحقل الهندسي, etc.
            case 'tawjihi_literary_fields': // حقل الأعمال, حقل اللغات, etc.
                $subjectIds = Subject::where('field_type_id', $category->id)->pluck('id');
                break;

            default:
                // For main programs (البرنامج الدولي, برنامج الجامعات والكليات)
                if ($category->type === 'major' && !$category->parent_id) {
                    $subjectIds = Subject::where('programm_id', $category->id)->pluck('id');
                }
                break;
        }

        return $subjectIds;
    }

    /**
     * Get subjects from child categories recursively
     */
    private function getSubjectsFromChildren($categoryId)
    {
        $subjectIds = collect();
        
        $children = Category::where('parent_id', $categoryId)->get();
        
        foreach ($children as $child) {
            // Get subjects from this child
            $childSubjects = $this->getDirectSubjectsByCategoryLevel($child);
            $subjectIds = $subjectIds->merge($childSubjects);

            // Get subjects from pivot table for this child
            $pivotSubjects = CategorySubject::where('category_id', $child->id)
                ->pluck('subject_id');
            $subjectIds = $subjectIds->merge($pivotSubjects);

            // Recursively get from grandchildren
            $grandchildrenSubjects = $this->getSubjectsFromChildren($child->id);
            $subjectIds = $subjectIds->merge($grandchildrenSubjects);
        }

        return $subjectIds;
    }

    /**
     * Get subjects for API response format
     */
    public function getSubjectsByCategoryForApi($categoryId, $includeInactive = false)
    {
        $subjects = $this->getCategorySubjects($categoryId, $includeInactive);

        return $subjects->map(function($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->localized_name,
                'name_ar' => $subject->name_ar,
                'name_en' => $subject->name_en,
                'icon' => $subject->icon,
                'color' => $subject->color,
            ];
        });
    }

    /**
     * Get subjects with their category relationship details
     */
    public function getSubjectsWithCategoryDetails($categoryId)
    {
        $subjects = $this->getCategorySubjects($categoryId);

        return $subjects->map(function($subject) use ($categoryId) {
            $pivotData = CategorySubject::where('category_id', $categoryId)
                ->where('subject_id', $subject->id)
                ->first();

            return [
                'id' => $subject->id,
                'name' => $subject->localized_name,
                'name_ar' => $subject->name_ar,
                'name_en' => $subject->name_en,
                'icon' => $subject->icon,
                'color' => $subject->color,
                'is_optional' => $pivotData ? $pivotData->is_optional : false,
                'is_ministry' => $pivotData ? $pivotData->is_ministry : true,
                'pivot_level' => $pivotData ? $pivotData->pivot_level : null,
                'relationship_type' => $this->getSubjectRelationshipType($subject, $categoryId)
            ];
        });
    }

    /**
     * Determine how a subject is related to a category
     */
    private function getSubjectRelationshipType($subject, $categoryId)
    {
        // Check pivot table first
        if (CategorySubject::where('category_id', $categoryId)->where('subject_id', $subject->id)->exists()) {
            return 'pivot';
        }

        // Check direct foreign keys
        if ($subject->grade_id == $categoryId) return 'grade';
        if ($subject->semester_id == $categoryId) return 'semester';
        if ($subject->programm_id == $categoryId) return 'program';
        if ($subject->field_type_id == $categoryId) return 'field_type';

        return 'inherited';
    }

    /**
     * Check if a category has any subjects
     */
    public function categoryHasSubjects($categoryId)
    {
        return $this->getCategorySubjects($categoryId)->isNotEmpty();
    }

    /**
     * Get subject count for a category
     */
    public function getCategorySubjectCount($categoryId)
    {
        return $this->getCategorySubjects($categoryId)->count();
    }
}