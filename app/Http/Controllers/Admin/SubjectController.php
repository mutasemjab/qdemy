<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\SubjectCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;

class SubjectController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $subjects = Subject::with(['program', 'grade', 'semester', 'fieldType'])
              ->orderBy('id','desc')
            ->paginate(15);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $fieldTypes = $this->categoryRepository->getFieldTypes();
        $programs   = $this->categoryRepository->getMajors();
        return view('admin.subjects.create', compact('fieldTypes', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSubject($request);

        DB::beginTransaction();

        try {
            // Generate sort_order if not provided
            if (empty($validated['sort_order'])) {
                $validated['sort_order'] = $this->generateSortOrder(
                    $validated['programm_id'],
                    $validated['grade_id'] ?? null,
                    $validated['semester_id'] ?? null
                );
            }

            $subject = Subject::create($validated);

            // Handle category relationships based on program type
            $this->handleCategoryRelationships($subject, $request);

            DB::commit();

            return redirect()->route('subjects.index')
                ->with('success', __('messages.subject_created_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->withErrors(['error' => $e->getMessage() . __('messages.something_went_wrong')]);
        }
    }

    public function edit(Subject $subject)
    {
        $fieldTypes = $this->categoryRepository->getFieldTypes();
        $programs   = $this->categoryRepository->getMajors();
        $semesters  = [];
        $fields     = [];

        if ($subject->grade_id) {
            $grade = Category::find($subject->grade_id);
            if ($grade && $grade->level == 'elementray_grade') {
                $semesters = $this->categoryRepository->getGradeSemesters($subject->grade_id);
            }
            if ($grade && $grade->ctg_key == 'final_year') {
                $fields = $this->categoryRepository->getTawjihiLastGradeFieldes();
            }
        }

        if($subject->program?->ctg_key == 'elementary-grades-program'){
            $grades = $this->categoryRepository->getElementryProgramGrades();
        }elseif($subject->program?->ctg_key == 'tawjihi-and-secondary-program'){
            $grades = $this->categoryRepository->getTawjihiProgrammGrades();
        }

        // Get existing field relationships for Tawjihi last year
        $existingFields = $subject->categories()
            ->wherePivot('pivot_level', 'field')
            ->get()
            ->keyBy('id');

        return view('admin.subjects.edit', compact(
            'subject',
            'fieldTypes',
            'programs',
            'grades',
            'semesters',
            'fields',
            'existingFields'
        ));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $this->validateSubject($request, $subject->id);

        DB::beginTransaction();

        try {
            $filteredValidatedData = Arr::except($validated, ['category_id', 'is_optional', 'is_ministry']);
            $subject->update($filteredValidatedData);

            // Clear existing relationships and recreate
            $subject->categories()->detach();

            // Handle category relationships based on program type
            $this->handleCategoryRelationships($subject, $request);

            DB::commit();

            return redirect()->route('subjects.index')
                ->with('success', __('messages.subject_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->withErrors(['error' => $e->getMessage() . __('messages.something_went_wrong')]);
        }
    }

    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
            return redirect()->route('subjects.index')
                ->with('success', __('messages.subject_deleted_successfully'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('messages.cannot_delete_subject')]);
        }
    }

    private function validateSubject(Request $request, $subjectId = null)
    {
        $rules = [
            'name_ar' => [
                'required',
                'max:100',
                function ($attribute, $value, $fail) use ($request, $subjectId) {
                    $query = Subject::where('name_ar', $value)
                        ->where('programm_id', $request->programm_id);

                    if ($request->grade_id) {
                        $query->where('grade_id', $request->grade_id);
                    }

                    if ($request->semester_id) {
                        $query->where('semester_id', $request->semester_id);
                    }

                    if ($subjectId) {
                        $query->where('id', '!=', $subjectId);
                    }

                    if ($query->exists()) {
                        $fail(__('messages.name_ar_already_exists'));
                    }
                }
            ],
            'name_en' => [
                'required',
                'max:100',
                function ($attribute, $value, $fail) use ($request, $subjectId) {
                    $query = Subject::where('name_en', $value)
                        ->where('programm_id', $request->programm_id);

                    if ($request->grade_id) {
                        $query->where('grade_id', $request->grade_id);
                    }

                    if ($request->semester_id) {
                        $query->where('semester_id', $request->semester_id);
                    }

                    if ($subjectId) {
                        $query->where('id', '!=', $subjectId);
                    }

                    if ($query->exists()) {
                        $fail(__('messages.name_en_already_exists'));
                    }
                }
            ],
            'description_ar' => 'nullable|max:500',
            'description_en' => 'nullable|max:500',
            'icon' => 'nullable|max:100',
            'color' => 'nullable|max:100',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer',
            'field_type_id' => 'required|exists:categories,id',
            'programm_id' => 'required|exists:categories,id',
        ];

        // Conditional validation for grade_id
        $program = Category::find($request->programm_id);
        if ($program && in_array($program->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
            $rules['grade_id'] = 'required|exists:categories,id';
        } else {
            $rules['grade_id'] = 'nullable|exists:categories,id';
        }

        // Conditional validation for semester_id
        if ($request->grade_id) {
            $grade = Category::find($request->grade_id);
            if ($grade && $grade->level == 'elementray_grade') {
                $rules['semester_id'] = 'required|exists:categories,id';
            }
        }

        // Validation for Tawjihi last year fields
        if ($request->grade_id) {
            $grade = Category::find($request->grade_id);
            if ($grade && $grade->ctg_key == 'final_year') {
                $rules['category_id'] = 'required|array';
                $rules['category_id.*'] = 'exists:categories,id';
                $rules['is_optional'] = 'required|array';
                $rules['is_optional.*'] = 'boolean';
                $rules['is_ministry'] = 'required|array';
                $rules['is_ministry.*'] = 'boolean';
            }
        }

        return $request->validate($rules);
    }

    private function generateSortOrder($programId, $gradeId = null, $semesterId = null)
    {
        $query = Subject::where('programm_id', $programId);

        if ($gradeId) {
            $query->where('grade_id', $gradeId);
        }

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        return $query->max('sort_order') + 1 ?? 1;
    }

    private function handleCategoryRelationships(Subject $subject, Request $request)
    {
        $program = Category::find($subject->programm_id);

        // Case 1: Programs without grades (tawjihi-and-secondary-program or elementary-grades-program)
        if (!in_array($program->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
            $subject->categories()->attach($subject->programm_id, [
                'pivot_level' => 'programm',
            ]);
            return;
        }

        // Case 2: Elementary grade with semester
        if ($subject->grade_id) {
            $grade = Category::find($subject->grade_id);

            if ($grade->level == 'elementray_grade' && $subject->semester_id) {
                // Attach to the selected semester only
                $subject->categories()->attach($subject->semester_id, [
                    'pivot_level' => 'semester',
                ]);
            }

            // Case 3: Tawjihi first year
            elseif ($program->ctg_key == 'tawjihi-and-secondary-program' && $grade->ctg_key == 'first_year') {
                $subject->categories()->attach($subject->grade_id, [
                    'pivot_level' => 'grade',
                    'is_optional' => false,
                    'is_ministry' => true
                ]);
            }
            // Case 4: Tawjihi last year with fields
            elseif ($program->ctg_key == 'tawjihi-and-secondary-program' && $grade->ctg_key == 'final_year') {
                if ($request->has('category_id')) {
                    foreach ($request->category_id as $fieldId) {
                        // CategorySubject::Create([
                        //     'subject_id'  => $subject->id,
                        //     'category_id'  => $request->category_id[$fieldId],
                        //     'pivot_level' => 'field',
                        //     'is_optional' => $request->is_optional[$fieldId] ?? false,
                        //     'is_ministry' => $request->is_ministry[$fieldId] ?? true
                        // ]);
                        $subject->categories()->attach($fieldId, [
                            'pivot_level' => 'field',
                            'is_optional' => $request->is_optional[$fieldId] ?? false,
                            'is_ministry' => $request->is_ministry[$fieldId] ?? true
                        ]);
                    }
                }
            }
        }
    }

    // AJAX methods
    public function getGrades(Request $request)
    {
        $programId = $request->program_id;
        $program = Category::find($programId);

        if (!$program) {
            return response()->json([]);
        }

        // If program doesn't need grades
        if (!in_array($program->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
            return response()->json(['no_grades' => true]);
        }

        if($program->ctg_key == 'elementary-grades-program'){
            $grades = $this->categoryRepository->getElementryProgramGrades();
        }else{
            $grades = $this->categoryRepository->getTawjihiProgrammGrades();
        }

        return response()->json($grades);
    }

    public function getSemesters(Request $request)
    {
        $gradeId = $request->grade_id;
        $semesters = $this->categoryRepository->getGradeSemesters($gradeId);

        return response()->json($semesters);
    }

    public function getFields(Request $request)
    {
        $fields = $this->categoryRepository->getTawjihiLastGradeFieldes();

        return response()->json($fields);
    }
}
