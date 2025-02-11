@extends('layouts.app')
@section('content')
    <!-- /.row-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('form.documents')</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('document.store') }}" enctype="multipart/form-data" method="POST" class="p-3">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="document_type">
                                @lang('form.document_type')
                            </label>
                            <div class="col-sm-10">
                                <select id="document_type" class="form-select" name="document_type">
                                    <option value="word" selected="">Word</option>
                                    <option value="exel">Exel</option>
                                    <option value="ppt">Presentation</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="title">
                                @lang('form.title')
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" placeholder="@lang('form.title_placeholder')"
                                       name="title" value="{{ old('title') }}" required autofocus>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">
                                @lang('form.description')
                            </label>
                            <div class="col-sm-10">
                                    <textarea id="description" class="form-control"
                                              placeholder="@lang('form.description_placeholder')"
                                              name="description" required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            @lang('form.create')
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- /.col-->
    <!-- /.row-->
@endsection
