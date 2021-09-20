
<?php
$studentApplicationsDisabilities = explode(',', $studentApplicationsObject->disabilities);
?>

<div class="multiselect col-span-6 md:col-span-5">
    <div class="selectBox" onclick="showCheckboxes()">
        <label  for="disabilities" class="block text-sm font-medium text-gray-700">Please select from the following list of disabilities which best applies to you <span  class="disabilities"></span></label>
        <select name="disabilities" class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            <option value="" disabled selected>-- Select --</option>
        </select>
        <div class="overSelect"></div>
    </div>
    <div id="checkboxes" class="mt-1 pl-1 pb-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        @foreach($studentDisable as $disable)
        <label  for="disabilities_{{$disable->hesa_code}}" class="block text-sm font-medium text-gray-700">
            <input name="disabilities_{{$disable->hesa_code}}" class="focus:ring-indigo-900 h-4 w-4 text-indigo-900 border-gray-300 {{ $disable->hesa_code == '00' ? 'no_disabilities_options' : 'disabilities_options' }}"
                   type="checkbox" id="disabilities_{{$disable->hesa_code}}" {{in_array($disable->hesa_code,$studentApplicationsDisabilities) ? 'checked' : ''}}/>
            {{ $disable->hesa_label}}</label>
        @endforeach
    </div>
    <!-- Error -->
    @if ($errors->has('disabilities'))
    <div class="error">
        {{ $errors->first('disabilities') }}
    </div>
    @endif
    <!-- Error END -->
</div>

<script>

    /**
     * Disabilities
     */
    var expanded = false;

    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }

    $('.no_disabilities_options').click(function () {

        if ($(this).is(':checked')) {
            $(".disabilities_options").attr("checked", false);
        }

    });

    $('.disabilities_options').click(function () {

        if ($(this).is(':checked')) {
            $(".no_disabilities_options").attr("checked", false);
        }

    });

    function checkCurrentCountryIsUK() {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('checkCurrentCountryIsUK')}}",
            method: "POST",
            data: {
                _token: _token
            },
            success: function (data)
            {
                if (data) {
                    $('.disabilities').addClass('required_field');
                } else {
                    $('.disabilities').removeClass('required_field');
                }
            }
        });
    }
    checkCurrentCountryIsUK();

    function checkStudentHasDisabilities() {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('checkStudentHasDisabilities')}}",
            method: "POST",
            data: {
                _token: _token
            },
            success: function (data)
            {
//                if (data) {
                    $("#checkboxes").css("display", "block");
//                } else {
//                    $("#checkboxes").css("display", "none");
//                }
            }
        });
    }
    checkStudentHasDisabilities();


</script>
<style>

    .selectBox {
        position: relative;
    }

    .selectBox select {
        width: 100%;
    }

    .overSelect {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }

    #checkboxes {
        display: none;
        border: 1px #dadada solid;
    }

    #checkboxes label {
        display: block;
    }

    #checkboxes label:hover {
        background-color: #1e90ff;
    }
    .required_field:after{
        content:'*';
    }
</style>