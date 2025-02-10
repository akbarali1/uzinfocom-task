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
                    <table class="table table-responsive-sm table-striped table-sm">
                        <thead>
                        <tr>
                            <th class="b-t-0">ID</th>
                            <th class="b-t-0">@lang('form.fileName')</th>
                            <th class="b-t-0">@lang('form.author')</th>
                            <th class="b-t-0">@lang('form.fileSize')</th>
                            <th class="b-t-0 text-center">@lang('form.edited_at')</th>
                            <th class="b-t-0 text-center">@lang('form.created_at')</th>
                            <th class="b-t-0" width="5px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pagination as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->fileName }}</td>
                                <td class="text-center">
                                    <span class="badge text-bg-success">
                                        {{ $item->authorName }}
                                    </span>
                                </td>
                                <td>{{ $item->fileSize }}</td>
                                <td class="text-center">{{ $item->lastEditedAt }}</td>
                                <td class="text-center">{{ $item->createdAt }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ $item->viewUrl }}" type="button" class="btn btn-warning btn-sm">@lang('form.view')</a>
                                        <a href="{{ $item->editUrl }}" type="button" class="btn btn-success btn-sm">@lang('form.edit')</a>
                                        <a href="{{ $item->downloadUrl }}" type="button" class="btn btn-info btn-sm">@lang('form.download')</a>
                                        <a data-action="delete" href="{{ $item->deleteUrl }}" type="button" class="btn btn-danger btn-sm">@lang('form.delete')</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">@lang('form.no_data')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($pagination->hasPages())
                <div class="card card-body pb-2  d-flex align-items-center justify-content-center">
                    {{ $pagination->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- /.col-->
    <!-- /.row-->
@endsection
