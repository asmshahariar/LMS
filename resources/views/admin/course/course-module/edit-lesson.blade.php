@extends('admin.layouts.master')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Lesson</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.courses.edit', $courseId) }}?step=3" class="btn btn-primary">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.course-content.update-lesson', $lesson->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $courseId }}">
                    <input type="hidden" name="chapter_id" value="{{ $chapterId }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3 add_course_basic_info_imput">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ @$lesson?->title }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="">Source</label>
                                <select name="source" class="add_course_basic_info_imput storage" required>
                                    <option value="">Select</option>
                                    @foreach(config('course.video_sources') as $source => $name)
                                    <option @selected(@$lesson?->storage == $source) value="{{ $source }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="add_course_basic_info_imput upload_source {{ @$lesson->storage == 'upload' ? '' : 'd-none' }}">
                                <label for="#">Path</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                      <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                        <i class="fa fa-picture-o"></i> Choose
                                      </a>
                                    </span>
                                    <input id="thumbnail" class="form-control source_input" type="text" name="file" value="{{ @$lesson?->file_path }}" >
                                  </div>
                            </div>
                            <div class="add_course_basic_info_imput external_source {{ @$lesson->storage != 'upload' ? '' : 'd-none' }}">
                                <label for="#">Path</label>
                                <input type="text" name="url" class="source_input" value="{{ @$lesson?->file_path }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="">File Type</label>
                                <select name="file_type" id="" class="add_course_basic_info_imput" required>
                                    <option value="">Select</option>
                                    @foreach(config('course.file_types') as $source => $name)
                                    <option @selected(@$lesson?->file_type == $source) value="{{ $source }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3 add_course_basic_info_imput">
                                <label for="">Duration</label>
                                <input type="text" class="" name="duration" value="{{ @$lesson?->duration }}" required>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="add_course_more_info_checkbox">
                                <div class="form-check" style="width: 200px">
                                    <input @checked(@$lesson?->is_preview === 1) class="form-check-input" type="checkbox" name="is_preview" value="1" id="preview">
                                    <label class="form-check-label" for="preview">Is Preview</label>
                                </div>
                                <div class="form-check" style="width: 200px">
                                    <input @checked(@$lesson?->downloadable === 1) class="form-check-input" type="checkbox" name="downloadable" value="1" id="downloadable">
                                    <label class="form-check-label" for="downloadable">Downloadable</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="">Description</label>
                                <textarea name="description" class="add_course_basic_info_imput" id="" cols="30" rows="10" required>{!! @$lesson?->description !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
     $('#lfm').filemanager('file', {prefix: '/admin/laravel-filemanager'});
</script>
@endpush

@push('header_scripts')
    @vite(['resources/js/admin/course.js'])
@endpush
