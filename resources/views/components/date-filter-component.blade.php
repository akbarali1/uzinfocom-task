<?php
/**
 * @var string $minDate
 * @var string $maxDate
 */
?>

<div class="form-group">
    <div class="row">
        <div class="col-6">
            <label>@lang('all.date_from')</label>
            <input type="date" class="form-control" name="date_from" min="{{ $minDate }}" max="{{ $maxDate }}"
                   value="{{ request('date_from') }}">
        </div>
        <div class="col-6">
            <label>@lang('all.date_to')</label>
            <input type="date" class="form-control" name="date_to" min="{{ $minDate }}" max="{{ $maxDate }}" value="{{ request('date_to') }}">
        </div>
    </div>
</div>
