<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Course;
use App\Models\Teacher;
use App\Traits\Responses;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use Responses;

    /**
     * Handle the incoming request and return home page data
     */
    public function __invoke(Request $request)
    {
        try {
            // Get all banners
            $banners = Banner::select('id','photo_for_mobile')
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($banner) {
                    return [
                        'id' => $banner->id,
                        'photo_for_mobile' => $banner->photo_for_mobile ? asset('assets/admin/uploads/' . $banner->photo_for_mobile) : null
                    ];
                });

            // Get main categories (parent_id is null)
            $categories = Category::where('parent_id', null)
                ->where('is_active', true)
                ->select([
                    'id',
                    'name_ar',
                    'name_en',
                    'ctg_key',
                    'level',
                    'description_ar',
                    'description_en',
                    'icon',
                    'color',
                    'sort_order',
                    'type',
                ])
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name_ar' => $category->name_ar,
                        'name_en' => $category->name_en,
                        'ctg_key' => $category->ctg_key,
                        'level' => $category->level,
                        'description_ar' => $category->description_ar,
                        'description_en' => $category->description_en,
                        'icon' => $category->icon,
                        'color' => $category->color,
                        'sort_order' => $category->sort_order,
                        'type' => $category->type,
                    ];
                });

            // Static services array in Arabic
            $services = [
                [
                    'id' => 1,
                    'name' => 'البكجات والعروض',
                   
                ],
                [
                    'id' => 2,
                    'name' => 'الإمتحانات الالكترونية',
                   
                ],
                [
                    'id' => 3,
                    'name' => 'مجتمع qdemy',
                  
                ],
                [
                    'id' => 4,
                    'name' => 'بنك الأسئلة',
                  
                ],
                [
                    'id' => 5,
                    'name' => 'أسئلة سنوات وزارية',
                  
                ],
                [
                    'id' => 6,
                    'name' => 'نقاط البيع',
                  
                ]
            ];

            // Get latest courses with teacher and category info
            $courses = Course::with(['teacher', 'subject'])
                ->select([
                    'id',
                    'title_en',
                    'title_ar',
                    'description_en',
                    'description_ar',
                    'selling_price',
                    'photo',
                    'teacher_id',
                    'subject_id',
                    'created_at'
                ])
                ->orderBy('created_at', 'desc')
                ->limit(10) // Limit to latest 10 courses for home page
                ->get()
                ->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title_en' => $course->title_en,
                        'title_ar' => $course->title_ar,
                        'description_en' => $course->description_en,
                        'description_ar' => $course->description_ar,
                        'selling_price' => $course->selling_price,
                        'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                        'created_at' => $course->created_at,
                        'teacher' => $course->teacher ? [
                            'id' => $course->teacher->id,
                            'name' => $course->teacher->name,
                            'name_of_lesson' => $course->teacher->name_of_lesson,
                            'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : null
                        ] : null,
                       
                    ];
                });

            // Get featured teachers
            $teachers = Teacher::with('user')
                ->select([
                    'id',
                    'name',
                    'name_of_lesson',
                    'description_ar',
                    'description_en',
                    'facebook',
                    'instagram',
                    'youtube',
                    'whataspp',
                    'photo',
                    'user_id',
                    'created_at'
                ])
                ->orderBy('created_at', 'desc')
                ->limit(8) // Limit to 8 teachers for home page
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'name_of_lesson' => $teacher->name_of_lesson,
                        'description_ar' => $teacher->description_ar,
                        'description_en' => $teacher->description_en,
                        'photo' => $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : null,
                        'social_media' => [
                            'facebook' => $teacher->facebook,
                            'instagram' => $teacher->instagram,
                            'youtube' => $teacher->youtube,
                            'whatsapp' => $teacher->whataspp
                        ],
                        'user_info' => $teacher->user ? [
                            'id' => $teacher->user->id,
                            'name' => $teacher->user->name,
                            'email' => $teacher->user->email
                        ] : null,
                        'created_at' => $teacher->created_at
                    ];
                });

            // Prepare response data
            $homeData = [
                'banners' => $banners,
                'categories' => $categories,
                'services' => $services,
                'courses' => $courses,
                'teachers' => $teachers,
                'stats' => [
                    'total_banners' => $banners->count(),
                    'total_categories' => $categories->count(),
                    'total_services' => count($services),
                    'total_courses' => Course::count(),
                    'total_teachers' => Teacher::count(),
                    'active_categories' => Category::where('is_active', true)->count()
                ]
            ];

            return $this->success_response('Home data retrieved successfully', $homeData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve home data: ' . $e->getMessage(), null);
        }
    }

   
}