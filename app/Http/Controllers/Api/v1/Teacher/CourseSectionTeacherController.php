<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseContent;
use App\Traits\CourseManagementTrait;
use App\Traits\Responses;
use Illuminate\Http\Request;

class CourseSectionTeacherController extends Controller
{
    use CourseManagementTrait, Responses;

    /**
     * Get course sections with contents
     */
    public function index(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            // Load course with nested relationships
            $course->load([
                'sections' => function($query) {
                    $query->whereNull('parent_id')->orderBy('created_at');
                },
                'sections.contents' => function($query) {
                    $query->orderBy('order');
                },
                'sections.children.contents' => function($query) {
                    $query->orderBy('order');
                }
            ]);

            // Get direct contents (not in any section)
            $directContents = $course->contents()
                ->whereNull('section_id')
                ->orderBy('order')
                ->get();

            $courseData = [
                'id' => $course->id,
                'title_en' => $course->title_en,
                'title_ar' => $course->title_ar,
                'sections' => $course->sections->map(function ($section) {
                    return [
                        'id' => $section->id,
                        'title_en' => $section->title_en,
                        'title_ar' => $section->title_ar,
                        'parent_id' => $section->parent_id,
                        'contents' => $section->contents->map(function ($content) {
                            return [
                                'id' => $content->id,
                                'title_en' => $content->title_en,
                                'title_ar' => $content->title_ar,
                                'content_type' => $content->content_type,
                                'is_free' => $content->is_free,
                                'order' => $content->order,
                                'video_duration' => $content->video_duration,
                                'created_at' => $content->created_at
                            ];
                        }),
                        'children' => $section->children->map(function ($child) {
                            return [
                                'id' => $child->id,
                                'title_en' => $child->title_en,
                                'title_ar' => $child->title_ar,
                                'parent_id' => $child->parent_id,
                                'contents' => $child->contents->map(function ($content) {
                                    return [
                                        'id' => $content->id,
                                        'title_en' => $content->title_en,
                                        'title_ar' => $content->title_ar,
                                        'content_type' => $content->content_type,
                                        'is_free' => $content->is_free,
                                        'order' => $content->order,
                                        'video_duration' => $content->video_duration,
                                        'created_at' => $content->created_at
                                    ];
                                })
                            ];
                        })
                    ];
                }),
                'direct_contents' => $directContents->map(function ($content) {
                    return [
                        'id' => $content->id,
                        'title_en' => $content->title_en,
                        'title_ar' => $content->title_ar,
                        'content_type' => $content->content_type,
                        'is_free' => $content->is_free,
                        'order' => $content->order,
                        'video_duration' => $content->video_duration,
                        'created_at' => $content->created_at
                    ];
                })
            ];

            return $this->success_response('Course sections retrieved successfully', $courseData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve course sections: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get specific section details
     */
    public function show(Request $request, $courseId, $sectionId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);
            $section = $course->sections()->with([
                'contents' => function($query) {
                    $query->orderBy('order');
                },
                'children.contents' => function($query) {
                    $query->orderBy('order');
                }
            ])->findOrFail($sectionId);

            $sectionData = [
                'id' => $section->id,
                'title_en' => $section->title_en,
                'title_ar' => $section->title_ar,
                'parent_id' => $section->parent_id,
                'course' => [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                ],
                'contents' => $section->contents->map(function ($content) {
                    return [
                        'id' => $content->id,
                        'title_en' => $content->title_en,
                        'title_ar' => $content->title_ar,
                        'content_type' => $content->content_type,
                        'is_free' => $content->is_free,
                        'order' => $content->order,
                        'video_type' => $content->video_type,
                        'video_url' => $content->video_url,
                        'video_duration' => $content->video_duration,
                        'file_path' => $content->file_path,
                        'pdf_type' => $content->pdf_type,
                        'created_at' => $content->created_at
                    ];
                }),
                'children' => $section->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'title_en' => $child->title_en,
                        'title_ar' => $child->title_ar,
                        'parent_id' => $child->parent_id,
                        'contents_count' => $child->contents->count(),
                        'contents' => $child->contents->map(function ($content) {
                            return [
                                'id' => $content->id,
                                'title_en' => $content->title_en,
                                'title_ar' => $content->title_ar,
                                'content_type' => $content->content_type,
                                'is_free' => $content->is_free,
                                'order' => $content->order,
                                'video_duration' => $content->video_duration,
                                'created_at' => $content->created_at
                            ];
                        })
                    ];
                }),
                'created_at' => $section->created_at,
                'updated_at' => $section->updated_at
            ];

            return $this->success_response('Section retrieved successfully', $sectionData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve section: ' . $e->getMessage(), null);
        }
    }

    /**
     * Create a new section
     */
    public function store(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            // Use the trait method
            return $this->storeCourseSection($request, $course);

        } catch (\Exception $e) {
            return $this->error_response('Failed to create section: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update a section
     */
    public function update(Request $request, $courseId, $sectionId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);
            $section = $course->sections()->findOrFail($sectionId);

            // Use the trait method
            return $this->updateCourseSection($request, $course, $section);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update section: ' . $e->getMessage(), null);
        }
    }

    /**
     * Delete a section
     */
    public function destroy(Request $request, $courseId, $sectionId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);
            $section = $course->sections()->findOrFail($sectionId);

            // Use the trait method
            return $this->deleteCourseSection($course, $section);

        } catch (\Exception $e) {
            return $this->error_response('Failed to delete section: ' . $e->getMessage(), null);
        }
    }

    // === CONTENT MANAGEMENT ===

    /**
     * Create content for a course (can be in a section or direct)
     */
    public function storeContent(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            // Use the trait method
            return $this->storeCourseContent($request, $course);

        } catch (\Exception $e) {
            return $this->error_response('Failed to create content: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update course content
     */
    public function updateContent(Request $request, $courseId, $contentId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);
            $content = $course->contents()->findOrFail($contentId);

            // Use the trait method
            return $this->updateCourseContent($request, $content);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update content: ' . $e->getMessage(), null);
        }
    }

    /**
     * Delete course content
     */
    public function destroyContent(Request $request, $courseId, $contentId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);
            $content = $course->contents()->findOrFail($contentId);

            // Use the trait method
            return $this->deleteCourseContent($content);

        } catch (\Exception $e) {
            return $this->error_response('Failed to delete content: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get all contents for a course (regardless of section)
     */
    public function getContents(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            $contents = $course->contents()
                ->with('section:id,title_en,title_ar')
                ->orderBy('section_id')
                ->orderBy('order')
                ->get();

            $contentsData = $contents->map(function ($content) {
                return [
                    'id' => $content->id,
                    'title_en' => $content->title_en,
                    'title_ar' => $content->title_ar,
                    'content_type' => $content->content_type,
                    'is_free' => $content->is_free,
                    'order' => $content->order,
                    'video_type' => $content->video_type,
                    'video_url' => $content->video_url,
                    'video_duration' => $content->video_duration,
                    'file_path' => $content->file_path,
                    'pdf_type' => $content->pdf_type,
                    'section' => $content->section ? [
                        'id' => $content->section->id,
                        'title_en' => $content->section->title_en,
                        'title_ar' => $content->section->title_ar,
                    ] : null,
                    'created_at' => $content->created_at,
                    'updated_at' => $content->updated_at
                ];
            });

            return $this->success_response('Course contents retrieved successfully', [
                'course' => [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                ],
                'contents' => $contentsData,
                'total_contents' => $contentsData->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve contents: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get specific content details
     */
    public function showContent(Request $request, $courseId, $contentId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);
            $content = $course->contents()->with('section:id,title_en,title_ar')->findOrFail($contentId);

            $contentData = [
                'id' => $content->id,
                'title_en' => $content->title_en,
                'title_ar' => $content->title_ar,
                'content_type' => $content->content_type,
                'is_free' => $content->is_free,
                'order' => $content->order,
                'video_type' => $content->video_type,
                'video_url' => $content->video_url,
                'video_duration' => $content->video_duration,
                'file_path' => $content->file_path,
                'pdf_type' => $content->pdf_type,
                'course' => [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                ],
                'section' => $content->section ? [
                    'id' => $content->section->id,
                    'title_en' => $content->section->title_en,
                    'title_ar' => $content->section->title_ar,
                ] : null,
                'created_at' => $content->created_at,
                'updated_at' => $content->updated_at
            ];

            return $this->success_response('Content retrieved successfully', $contentData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve content: ' . $e->getMessage(), null);
        }
    }

    /**
     * Reorder sections
     */
    public function reorderSections(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $validator = \Validator::make($request->all(), [
                'sections' => 'required|array',
                'sections.*.id' => 'required|exists:course_sections,id',
                'sections.*.order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            \DB::beginTransaction();
            try {
                foreach ($request->sections as $sectionData) {
                    $section = $course->sections()->findOrFail($sectionData['id']);
                    $section->update(['order' => $sectionData['order']]);
                }

                \DB::commit();

                return $this->success_response('Sections reordered successfully',[]);

            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return $this->error_response('Failed to reorder sections: ' . $e->getMessage(), null);
        }
    }

    /**
     * Reorder contents within a section or course
     */
    public function reorderContents(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $validator = \Validator::make($request->all(), [
                'contents' => 'required|array',
                'contents.*.id' => 'required|exists:course_contents,id',
                'contents.*.order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            \DB::beginTransaction();
            try {
                foreach ($request->contents as $contentData) {
                    $content = $course->contents()->findOrFail($contentData['id']);
                    $content->update(['order' => $contentData['order']]);
                }

                \DB::commit();

                return $this->success_response('Contents reordered successfully');

            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return $this->error_response('Failed to reorder contents: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get course statistics for teacher
     */
    public function getCourseStatistics(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)
                ->with(['sections', 'contents', 'enrollments'])
                ->findOrFail($courseId);

            $statistics = [
                'course_info' => [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                    'selling_price' => $course->selling_price,
                    'created_at' => $course->created_at
                ],
                'content_statistics' => [
                    'total_sections' => $course->sections->count(),
                    'total_contents' => $course->contents->count(),
                    'video_contents' => $course->contents->where('content_type', 'video')->count(),
                    'pdf_contents' => $course->contents->where('content_type', 'pdf')->count(),
                    'free_contents' => $course->contents->where('is_free', 1)->count(),
                    'paid_contents' => $course->contents->where('is_free', 2)->count(),
                ],
                'enrollment_statistics' => [
                    'total_enrollments' => $course->enrollments->count(),
                    'active_students' => $course->enrollments->where('status', 'active')->count(),
                    'completion_rate' => $course->enrollments->avg('progress') ?? 0,
                ],
                'duration_statistics' => [
                    'total_video_duration' => $course->contents->where('content_type', 'video')->sum('video_duration'),
                    'average_video_duration' => $course->contents->where('content_type', 'video')->avg('video_duration') ?? 0,
                ]
            ];

            return $this->success_response('Course statistics retrieved successfully', $statistics);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve course statistics: ' . $e->getMessage(), null);
        }
    }
}