@if(session::has('form_stage'))
@php
$form_stage = session::get('form_stage');
@endphp
<br>
<div class="flex">
    <div class="w-1/2">
        <div class="md:mt-0 md:col-span-2 text-left ">
            @if($form_stage['show_back'])
            <a class="font-medium text-yellow-400 text-left hover:text-indigo-900 leading-5" href="{{ route('formPart', ['flow' => 'back']) }}" role="button"><span class="text-2xl">⇦</span> EDIT - {{ $form_stage['steps_name'][$form_stage['stage_no'] - 1]}}</a>
            @endif
        </div>
    </div>
    <div class="w-1/2"> 
        <div class="md:mt-0 md:col-span-2 text-right">
            @if($form_stage['show_next'])
            <a class="font-medium text-yellow-400 text-right hover:text-indigo-900 leading-5" href="{{ route('formPart', ['flow' => 'next']) }}" role="button"> NEXT -  {{ $form_stage['steps_name'][$form_stage['stage_no'] + 1]}}<span class="text-2xl"> ⇨</span></a>
            @endif
        </div>
    </div>
</div>
@endif