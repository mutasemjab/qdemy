<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\CategorySubject;
use App\Models\SubjectCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;

class SubjectController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->middleware('permission:subject-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:subject-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:subject-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:subject-delete', ['only' => ['destroy']]);
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $query = Subject::with(['program', 'grade', 'semester', 'fieldType', 'courses']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        // Program filter
        if ($request->filled('program_id')) {
            $query->where('programm_id', $request->program_id);
        }

        // Grade filter
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Order by program, grade, semester, then sort_order
        $subjects = $query->orderBy('programm_id')
                        ->orderBy('grade_id')
                        ->orderBy('semester_id')
                        ->orderBy('sort_order')
                        ->orderBy('id', 'desc')
                        ->paginate(15)
                        ->withQueryString();

        // Get filter data
        $programs = $this->categoryRepository->getMajors();
        $grades = collect();
        if ($request->filled('program_id')) {
            $program = Category::find($request->program_id);
            if ($program && in_array($program->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
                if($program->ctg_key == 'elementary-grades-program'){
                    $grades = $this->categoryRepository->getElementryProgramGrades();
                } else {
                    $grades = $this->categoryRepository->getTawjihiProgrammGrades();
                }
            }
        }

        return view('admin.subjects.index', compact('subjects', 'programs', 'grades'));
    }

    public function show(Subject $subject)
    {
        // Load all relationships
        $subject->load([
            'program',
            'grade',
            'semester',
            'fieldType',
            'categories' => function($query) {
                $query->withPivot(['pivot_level', 'is_optional', 'is_ministry']);
            },
            'courses' => function($query) {
                $query->withCount('students');
            }
        ]);

        // Get related fields if Tawjihi last year
        $relatedFields = collect();
        if ($subject->grade && $subject->grade->ctg_key == 'final_year') {
            $relatedFields = $subject->categories()
                ->wherePivot('pivot_level', 'field')
                ->withPivot(['is_optional', 'is_ministry'])
                ->get();
        }

        return view('admin.subjects.show', compact('subject', 'relatedFields'));
    }

    public function create()
    {
        $fieldTypes = $this->categoryRepository->getFieldTypes();
        $programs = $this->categoryRepository->getMajors();

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
        $programs = $this->categoryRepository->getMajors();
        $semesters = [];
        $fields = [];
        $grades = [];
        $internationalTypes = [];

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
        }elseif ($subject->program?->ctg_key == 'international-program') {
            $grades = $this->categoryRepository->getInternationalPrograms();
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
            'existingFields',
            // 'internationalTypes',
        ));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $this->validateSubject($request, $subject->id);

        DB::beginTransaction();
        try {

            $subject->update($validated);

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


    // أضف هذه الدوال الجديدة:
    public function toggleStatus(Subject $subject)
    {
        $subject->is_active = !$subject->is_active;
        $subject->save();

        return redirect()->back()->with('success', __('messages.status_updated'));
    }

    public function moveUp(Subject $subject)
    {
        $previousSubject = Subject::where('programm_id', $subject->programm_id)
            ->where(function($q) use ($subject) {
                if ($subject->grade_id) {
                    $q->where('grade_id', $subject->grade_id);
                } else {
                    $q->whereNull('grade_id');
                }
            })
            ->where(function($q) use ($subject) {
                if ($subject->semester_id) {
                    $q->where('semester_id', $subject->semester_id);
                } else {
                    $q->whereNull('semester_id');
                }
            })
            ->where('sort_order', '<', $subject->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previousSubject) {
            $tempOrder = $subject->sort_order;
            $subject->sort_order = $previousSubject->sort_order;
            $previousSubject->sort_order = $tempOrder;

            $subject->save();
            $previousSubject->save();
        }

        return redirect()->back()->with('success', __('messages.order_updated'));
    }

    public function moveDown(Subject $subject)
    {
        $nextSubject = Subject::where('programm_id', $subject->programm_id)
            ->where(function($q) use ($subject) {
                if ($subject->grade_id) {
                    $q->where('grade_id', $subject->grade_id);
                } else {
                    $q->whereNull('grade_id');
                }
            })
            ->where(function($q) use ($subject) {
                if ($subject->semester_id) {
                    $q->where('semester_id', $subject->semester_id);
                } else {
                    $q->whereNull('semester_id');
                }
            })
            ->where('sort_order', '>', $subject->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($nextSubject) {
            $tempOrder = $subject->sort_order;
            $subject->sort_order = $nextSubject->sort_order;
            $nextSubject->sort_order = $tempOrder;

            $subject->save();
            $nextSubject->save();
        }

        return redirect()->back()->with('success', __('messages.order_updated'));
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
            'programm_id' => 'required|exists:categories,id',
        ];

        // Conditional validation for grade_id
        $program = Category::find($request->programm_id);
        $grade   = null;
        if ($program && $program->ctg_key != 'universities-and-colleges-program') {
            $rules['grade_id'] = 'required|exists:categories,id';
        } else {
            $rules['grade_id'] = 'nullable|exists:categories,id';
        }

        if ($request->grade_id) {

            $grade = Category::find($request->grade_id);
            if ($grade && $grade->level == 'elementray_grade') {  // Conditional validation for semester_id

                $rules['semester_id'] = 'required|exists:categories,id';

            }elseif ($grade && $grade->ctg_key == 'final_year') { // Validation for Tawjihi last year fields

                $rules['field_type_id'] = 'required|exists:categories,id';
                $rules['category_id']   = 'required|array';
                $rules['category_id.*.*'] = 'exists:categories,id';
                $rules['is_optional']   = 'required|array';
                $rules['is_optional.*.*'] = 'boolean';
                $rules['is_ministry']   = 'required|array';
                $rules['is_ministry.*.*'] = 'boolean';

            }elseif ($grade && $grade->ctg_key == 'first_year') { // Validation for Tawjihi first year

                $rules['is_optional_single'] = 'boolean';
                $rules['is_ministry_single'] = 'boolean';

            }
        }

        $validated = $request->validate($rules);

        $filteredValidatedData = Arr::except($validated, ['category_id', 'is_optional_single', 'is_ministry_single','is_optional','is_ministry']);
        if (!$grade || $grade->ctg_key != 'final_year') {
            $filteredValidatedData['field_type_id'] = null;
        }
        return $filteredValidatedData;
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

        // Case 0: International Program
        if ($program->ctg_key == 'international-program') {
            // Attach to the international program type (stored in grade_id)
            $subject->categories()->attach($subject->grade_id, [
                'pivot_level' => 'national_programm',
                'is_optional' => false,
                'is_ministry' => true,
            ]);
            return;
        }

        // Case 1: Programs without grades (tawjihi-and-secondary-program or elementary-grades-program)
        if (!in_array($program->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
            $subject->categories()->attach($subject->programm_id, [
                'pivot_level' => 'programm',
                'is_optional' => false,
                'is_ministry' => true,
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
                    'is_optional' => false,
                    'is_ministry' => true,
                ]);
            }
            // Case 3: Tawjihi first year
            elseif ($program->ctg_key == 'tawjihi-and-secondary-program' && $grade->ctg_key == 'first_year') {
                $subject->categories()->attach($subject->grade_id, [
                    'pivot_level' => 'grade',
                    'is_optional' => $request->is_optional_single ?? false,
                    'is_ministry' => $request->is_ministry_single ?? true
                ]);
            }
            // Case 4: Tawjihi last year with fields
            elseif ($program->ctg_key == 'tawjihi-and-secondary-program' && $grade->ctg_key == 'final_year') {
                if ($request->has('category_id')) {
                    foreach ($request->category_id as $key => $value) {
                        foreach ($value as $fieldId) {
                            $subject->categories()->attach($fieldId, [
                                'pivot_level' => 'field',
                                'is_optional' => $request->is_optional[$key][$fieldId] ?? false,
                                'is_ministry' => $request->is_ministry[$key][$fieldId] ?? true
                            ]);
                        }
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

        // Handle international program - return program types
        if ($program->ctg_key == 'international-program') {
            $internationalTypes = $this->categoryRepository->getInternationalPrograms();
            return response()->json($internationalTypes);
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

    public function getFields(Request $request,$subject = null)
    {
        $fields = $this->categoryRepository->getTawjihiLastGradeFieldes();

        // Check if we're in edit mode
        if ($request->has('subject_id') && $request->subject_id || $subject) {
            $subject = Subject::find($request->subject_id ?? $subject);

            if ($subject) {

                // Get existing category relationships for fields
                $existingRelations = CategorySubject::where('subject_id',$subject->id)
                    ->where('pivot_level', 'field')
                    ->get()
                    ->keyBy('id');

                // get array for current category_id relations
                $existingRelationsFields = $existingRelations->pluck('category_id')?->toArray() ?? [];

                // Add relationship data to each field
                $fields = $fields->map(function($field) use ($existingRelations,$existingRelationsFields) {
                    $field->key         = $field->id;
                    $field->checked     = false;
                    $field->is_optional = false;
                    $field->is_ministry = true;
                    if(!in_array($field->id,$existingRelationsFields)){
                       return $field;
                    }
                });

                if($existingRelationsFields){
                    foreach ($existingRelations as $key => $existingRelation) {
                        $ctgField = new Category;
                        $ctgField->key         = $existingRelation->category_id .'_'.$existingRelation->subject_id;
                        $ctgField->id          = $existingRelation->category_id;
                        $ctgField->checked     = true;
                        $ctgField->name_ar     = $existingRelation->category->name_ar;
                        $ctgField->name_en     = $existingRelation->category->name_en;
                        $ctgField->is_optional = $existingRelation->is_optional;
                        $ctgField->is_ministry = $existingRelation->is_ministry;
                        $fields = $fields->push($ctgField);
                    }
                }

            }
        }

        return response()->json($fields);
    }

}
