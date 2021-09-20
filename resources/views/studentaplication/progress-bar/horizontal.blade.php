<div class="stepper-horizontal" id="stepper1">
    <div onclick="{{array_key_exists(1,session('form_stage')['steps_available'])? 'takeStep(1)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==1?'current':''}} {{array_key_exists(1,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle"><span>1</span></div>
        <div class="step-title hidden md:block">Course Details</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
    <div onclick="{{array_key_exists(2,session('form_stage')['steps_available'])? 'takeStep(2)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==2?'current':''}} {{array_key_exists(2,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle">
            <span>2</span></div>
        <div class="step-title hidden md:block">Personal Details</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
    <div onclick="{{array_key_exists(3,session('form_stage')['steps_available'])? 'takeStep(3)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==3?'current':''}} {{array_key_exists(3,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle"><span>3</span></div>
        <div class="step-title hidden md:block">Disabilities & Accessibility</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
    <div onclick="{{array_key_exists(4,session('form_stage')['steps_available'])? 'takeStep(4)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==4?'current':''}} {{array_key_exists(4,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle"><span>4</span></div>
        <div class="step-title hidden md:block">Further Information</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
    <div onclick="{{array_key_exists(5,session('form_stage')['steps_available'])? 'takeStep(5)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==5?'current':''}} {{array_key_exists(5,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle"><span>5</span></div>
        <div class="step-title hidden md:block">Further Information</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
    <div onclick="{{array_key_exists(6,session('form_stage')['steps_available'])? 'takeStep(6)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==6?'current':''}} {{array_key_exists(6,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle"><span>6</span></div>
        <div class="step-title hidden md:block">Hardware & Equipment</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
    <div onclick="{{array_key_exists(7,session('form_stage')['steps_available'])? 'takeStep(7)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==7?'current':''}} {{array_key_exists(7,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle"><span>7</span></div>
        <div class="step-title hidden md:block">Qualifications</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
    <div onclick="{{array_key_exists(8,session('form_stage')['steps_available'])? 'takeStep(8)':''}}" class="step cursor-pointer {{session('form_stage')['stage_no']==8?'current':''}} {{array_key_exists(8,session('form_stage')['steps_available'])?'is-done':''}}">
        <div class="step-circle"><span>8</span></div>
        <div class="step-title hidden md:block">Employment History</div>
        <div class="step-bar-left"></div>
        <div class="step-bar-right"></div>
    </div>
</div>

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