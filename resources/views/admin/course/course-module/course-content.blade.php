@extends('admin.course.course-module.course-app')

@section('tab_content')
    <div class="tab-pane fade show active" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h3 class="card-title m-0 text-dark fw-bold">Curriculum</h3>
                <div class="card-actions">
                    <span class="text-muted">Details {{ count($chapters) }} Sections</span>
                </div>
            </div>
            <div class="card-body p-4 bg-light">
                <div class="curriculum-builder" id="curriculum-builder">
                    @foreach ($chapters as $chapter)
                        <div class="card mb-4 section-card border-0 shadow-sm" data-id="{{ $chapter->id }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-grid-dots text-muted me-2 drag-handle cursor-move"></i>
                                    <h4 class="m-0 fw-semibold text-dark">{{ $chapter->title }}</h4>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-3">{{ count($chapter->lessons ?? []) }} Item(s)</span>
                                    <a href="#" class="text-muted me-2 edit_chapter" data-course-id="{{ $chapter->course_id }}" data-chapter-id="{{ $chapter->id }}"><i class="ti ti-edit"></i></a>
                                    <a href="{{ route('admin.course-content.destory-chapter', $chapter->id) }}" class="text-muted delete-item"><i class="ti ti-trash"></i></a>
                                    <a class="text-muted ms-2" data-bs-toggle="collapse" href="#collapse-{{ $chapter->id }}"><i class="ti ti-chevron-down"></i></a>
                                </div>
                            </div>
                            <div class="collapse show" id="collapse-{{ $chapter->id }}">
                                <div class="card-body bg-white p-3">
                                    <!-- Section Description Placeholder -->
                                    <div class="mb-3">
                                        <textarea class="form-control bg-light border-0" rows="2" placeholder="+ Add Description">{{ $chapter->description }}</textarea>
                                    </div>

                                    <!-- Lessons List -->
                                    <ul class="lessons-list sortable_list list-unstyled">
                                        @foreach($chapter->lessons ?? [] as $lesson)
                                            <li class="lesson-item d-flex justify-content-between align-items-center p-3 mb-2 border rounded bg-white shadow-sm" data-lesson-id="{{ $lesson->id }}" data-chapter-id="{{ $chapter->id }}">
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-grid-dots text-muted me-2 drag-handle cursor-move dragger"></i>
                                                    <i class="ti ti-file-text text-primary me-2"></i>
                                                    <span class="fw-medium text-dark">{{ $lesson->title }}</span>
                                                </div>
                                                <div class="lesson-actions" style="opacity: 0.5; transition: opacity 0.2s;">
                                                    <a href="#" class="text-muted me-2 toggle-lesson-status" data-lesson-id="{{ $lesson->id }}" title="Visibility"><i class="ti {{ $lesson->status == 1 ? 'ti-eye' : 'ti-eye-off' }}"></i></a>
                                                    <a href="{{ route('admin.course-content.edit-lesson', $lesson->id) }}" class="text-muted me-2" title="Edit Lesson"><i class="ti ti-edit"></i></a>
                                                    <a href="{{ route('admin.course-content.destroy-lesson', $lesson->id) }}" class="text-danger delete-item" title="Delete"><i class="ti ti-trash"></i></a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <!-- Add New Item Row -->
                                    <div class="add-item-row mt-3 border rounded p-3 bg-light d-flex align-items-center">
                                        <i class="ti ti-plus text-primary me-2"></i>
                                        <span class="text-muted">Create a new Lesson</span>
                                        <div class="ms-auto">
                                            <button class="btn btn-outline-primary btn-sm add_lesson" data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $chapter->course_id }}">Add Lesson</button>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-3 d-flex gap-2">
                                        <button class="btn btn-outline-secondary btn-sm bg-white add_lesson" data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $chapter->course_id }}"><i class="ti ti-file me-1"></i> New Lesson</button>
                                        <button class="btn btn-outline-secondary btn-sm bg-white"><i class="ti ti-puzzle me-1"></i> New Quiz</button>
                                        <button class="btn btn-outline-secondary btn-sm bg-white"><i class="ti ti-box me-1"></i> Content Bank</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Create New Section -->
                    <div class="card mt-4 border-0 shadow-sm">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center bg-white rounded">
                            <div class="d-flex align-items-center text-muted">
                                <i class="ti ti-plus me-2"></i>
                                <span>Create a new section</span>
                            </div>
                            <button class="btn btn-primary dynamic-modal-btn" data-id="{{ $courseId }}">Add Section</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .cursor-move { cursor: move; }
        .lesson-item:hover { border-color: #007bff !important; }
        .lesson-item:hover .lesson-actions { opacity: 1 !important; }
        .section-card { border-radius: 8px; overflow: hidden; }
    </style>
@endsection
