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
                    <form action="{{ route('document.uploadFile') }}" enctype="multipart/form-data" method="POST" class="p-3">
                        @csrf
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label required">@lang('form.select_file')</label>
                            <div class="col-md-10">
                                <input name="file" class="form-control" type="file" id="formFile"
                                       accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                       required>
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
