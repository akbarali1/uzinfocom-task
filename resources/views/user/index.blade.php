<?php
/**
 * @var \Illuminate\Pagination\LengthAwarePaginator|\App\ViewModels\UserViewModel[] $pagination
 */
?>
@extends('layouts.app')
@section('content')
    <!-- /.row-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('form.users')</h5>
                    <a class="btn btn-sm btn-success" href="{{ route('user.create') }}">
                        <i class="c-icon c-icon-sm cil-plus"></i> @lang('form.add')
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-responsive-sm table-striped table-sm">
                        <thead>
                        <tr>
                            <th class="b-t-0">ID</th>
                            <th class="b-t-0">@lang('form.name')</th>
                            <th class="b-t-0">@lang('form.email')</th>
                            <th class="b-t-0 text-center">@lang('form.roles')</th>
                            <th class="b-t-0 text-center">@lang('form.created_at')</th>
                            <th class="b-t-0" width="5px"></th>
                            <th class="b-t-0" width="5px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pagination as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td class="text-center">
                                    @foreach($item->roleNames as $roleName)
                                        <span class="badge text-bg-success">{{ $roleName }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">{{ $item->createdAt }}</td>
                                <td>
                                    <a href="{{ route('user.edit',$item->id) }}" class="btn btn-sm btn-info text-white">
                                        @lang('form.edit')
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('user.delete',$item->id) }}"
                                       class="btn btn-sm btn-danger text-white" data-action="delete">
                                        @lang('form.delete')
                                    </a>
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
