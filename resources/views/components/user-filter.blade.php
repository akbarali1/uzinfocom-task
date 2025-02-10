<?php
/**
 * @var array{id: int, name: string} $users
 */
?>

<div class="form-group">
    <label for="user_id">@lang('form.filter.user')</label>
    <select name="user_id" id="user_id" class="form-select">
        <option value="">--------</option>
        @foreach($users as $user)
            <option value="{{ $user['id'] }}" @selected(!is_null(request()->get('user_id') ) && (int)$user['id'] === (int)request()->get('user_id'))>
                {{ $user['name'] }}
            </option>
        @endforeach
    </select>
</div>