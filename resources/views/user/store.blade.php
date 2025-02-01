<?php
/**
 * @var \App\ViewModels\UserViewModel $item
 */
?>
@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">
                {{ trans(isset($item->id) ?  "form.editing_item" : "form.creating_item") }}
            </h5>
            <a class="btn btn-sm btn-outline-primary" href="{{ route("user.index") }}">
                <i class="c-icon c-icon-sm cil-arrow-left"></i> @lang('form.back')
            </a>
        </div>
        <form class="form-horizontal" action="{{ isset($item->id) ? route('user.update', $item->id) : route('user.store') }}" method="post">
            @csrf
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12 mb-3">
                                <label class="col-form-label" for="name">@lang('form.name')</label>
                                <input class="form-control" id="name" type="text" name="name" value="{{ old('name', $item->name ?? "") }}"
                                       placeholder="@lang('form.name')" autocomplete="off" required autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12 mb-3">
                                <label class="col-form-label" for="name">@lang('form.email')</label>
                                <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $item->email ?? "") }}"
                                       placeholder="@lang('form.email')" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12 mb-3">
                                <label class="col-form-label" for="name">@lang('form.password')</label>
                                <input class="form-control" id="password" type="password" name="password" value="{{ old('password') }}"
                                       placeholder="@lang('form.password')" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12 mb-3">
                                <label class="col-form-label" for="name">@lang('form.password_confirmation')</label>
                                <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                                       placeholder="@lang('form.password_confirmation')" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label" for="defaultSelect" for="role">@lang('form.role')</label>
                                <select id="defaultSelect" class="form-select" required name="role_id">
                                    <option value="">@lang('form.select_role')</option>
                                    @foreach($item->listOfRoles as $role)
                                        <option value="{{ $role->id }}" @selected(in_array($role->id, $item->roleIds ?? [], true))>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-success" type="submit">
                    <i class="c-icon c-icon-sm cil-save"></i>
                    @lang('form.save')
                </button>
            </div>
        </form>
    </div>
@endsection
