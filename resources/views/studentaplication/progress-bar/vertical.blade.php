<?php
//    dump(session()->all());
?>
<div class="wrapper text-base w-80">
    <ul class="StepProgress relative pl-11 mb-20">
        <li onclick="{{array_key_exists(1,session('form_stage')['steps_available'])? 'takeStep(1)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==1?'current':''}} {{array_key_exists(1,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 1: Course Details</div></li>
        <li onclick="{{array_key_exists(2,session('form_stage')['steps_available'])? 'takeStep(2)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==2?'current':''}} {{array_key_exists(2,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 2: Personal Details</div></li>
        <li onclick="{{array_key_exists(3,session('form_stage')['steps_available'])? 'takeStep(3)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==3?'current':''}} {{array_key_exists(3,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 3: Disabilities & Accessibility</div></li>
        <li onclick="{{array_key_exists(4,session('form_stage')['steps_available'])? 'takeStep(4)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==4?'current':''}} {{array_key_exists(4,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 4: Further Information</div></li>
        <li onclick="{{array_key_exists(5,session('form_stage')['steps_available'])? 'takeStep(5)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==5?'current':''}} {{array_key_exists(5,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 5: Further Information</div></li>
        <li onclick="{{array_key_exists(6,session('form_stage')['steps_available'])? 'takeStep(6)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==6?'current':''}} {{array_key_exists(6,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 6: Hardware & Equipment</div></li>
        <li onclick="{{array_key_exists(7,session('form_stage')['steps_available'])? 'takeStep(7)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==7?'current':''}} {{array_key_exists(7,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 7: Qualifications</div></li>
        <li onclick="{{array_key_exists(8,session('form_stage')['steps_available'])? 'takeStep(8)':''}}" class="StepProgress-item cursor-pointer {{session('form_stage')['stage_no']==8?'current':''}} {{array_key_exists(8,session('form_stage')['steps_available'])?'is-done':''}}"><div class="font-semibold">Part 8: Employment History</div></li>
    </ul>
</div>

<style>
   
   
</style>

<script>

    function takeStep(no) {

        var _token = $('input[name="_token"]').val();


        $.ajax({
            url: "{{ route('takeStep') }}",
            method: "POST",
            data: {
                no: no,
                _token: _token
            },
            success: function ()
            {
                location.reload();
            }
        });
    }

</script>