@extends('backend.layouts.app')

@section('title','Create Event')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/backend/css/ckeditor/ckeditor.css') }}">
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Create Event</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Event</li>
              </ol>
            </div>
          </div>
        </div>
      </section>
    <section class="content p-10">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card card-primary">
                <form  action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                <div class="card-body">
                    <div class="form-group">
                      <label for="exampleInputFullName">Title</label>
                      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" id="exampleInputFullName" placeholder="Enter event title">
                        @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                      <label for="exampleInputMobile">Short Description</label>
                      <textarea type="text" name="short_description" class="form-control @error('short_description') is-invalid @enderror" id="exampleInputMobile" placeholder="Enter short description">{{ old('short_description') }}</textarea>
                        @error('short_description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                      <label for="exampleInputMobile">Details</label>
                      <textarea class="@error('details') is-invalid @enderror" name="details">{!! old('details') !!}</textarea>
                      @error('details')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="status">Status : </label>
                      <input name="status" type="radio" checked  id="radio_1" value="Draft" class="@error('status') is-invalid @enderror">
                      <span for="radio">Draft</span>
                      <input name="status" type="radio" id="radio_2" value="Published" class="@error('status') is-invalid @enderror">
                      <span for="radio_2">Published</span>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                      <label>Published Date</label><br>
                      <input type="date" name="published_date" class="form-control @error('published_date') is-invalid @enderror">
                      @error('published_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label>Featured Photo</label><br>
                      <input type="file" name="photo" class="@error('photo') is-invalid @enderror">
                      @error('photo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
@endsection

@push('script')
<script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
{{-- <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script> --}}
<script>
  CKEDITOR.replace('details', {
    filebrowserUploadUrl: "{{route('admin.event.file.Upload', ['_token' => csrf_token() ])}}",
    filebrowserUploadMethod: 'form'
  });
</script>
@endpush