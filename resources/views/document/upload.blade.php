<?php
/**
 * @var \Illuminate\Pagination\LengthAwarePaginator|\App\ViewModels\DocumentViewModel[] $pagination
 */
?>
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
                    <form action="{{ route('document.uploadFile') }}" enctype="multipart/form-data" method="POST" class="p-3">
                        @csrf
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label required">@lang('form.select_file')</label>
                            <div class="col-md-10">
                                <input name="file" class="form-control" type="file" id="formFile" required
                                       accept="application/msword,
                                           application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                                           application/vnd.ms-excel,
                                           application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
                                           application/vnd.ms-powerpoint,
                                           application/vnd.openxmlformats-officedocument.presentationml.presentation,
                                           application/pdf"/>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            @lang('form.upload')
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- /.col-->
    <!-- /.row-->
@endsection
