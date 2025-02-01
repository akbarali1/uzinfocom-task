<?php
/**
 * @var string $code
 */
?>
@extends('layouts.exception')
@section('content')
    @php
        $descriptionKey     = "exceptions.{$code}.description";
		$contentKey         = "exceptions.{$code}.content";
        $transDesc          = trans($descriptionKey);
        $transContent       = trans($contentKey);
		if ($transContent === $contentKey) {
			$content = ($descriptionKey === $transDesc)? "No additional description and content provided" : $transDesc;
		}else{
			$content = $transContent;
		}
    @endphp
    <div class="mt-3">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button text-white" type="button" style="background-color: #161818!important;">
                        @lang('exceptions.'.$code.'.message')
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body text-white" style="background-color: #1e1e1e;">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
