@include('studentaplication.header.head')

<div class="xl:hidden w-full bg-white-50 bg-gray-100"> 
    @include('studentaplication.progress-bar.horizontal')
</div>

<div class="md:grid md:grid-cols-4 md:gap-6 bg-gray-100 pb-20">
    <div class="sm:col-span-1 hidden xl:block mt-20 xl:ml-14 2xl:ml-24">
        <div class="mt-10 md:mt-0 "> 
            @include('studentaplication.progress-bar.vertical')
        </div>
    </div>
    <div class="sm:col-span-4 xl:col-span-3 mb-10 xl:mr-20 mr-5 ml-5 sm:mr-10 sm:ml-10 bg-white-50"> 
         <form method="POST" action="{{ route('saveStudentApplication') }}">
            {{ csrf_field()  }}
            <div class="mb-5 md:mt-0 md:col-span-2">
                @include('studentaplication.buttons-form')
            </div> 
            <div class="shadow overflow-hidden sm:rounded-md bg-white">
                <div class="p-1 bg-gray-50"></div>
                <div class="col-span-6 text-3xl text-center p-10 bg-white">
                    Qualifications
                </div>

                {{-- Highest Qualification --}}
                @include('studentaplication.formBits.form-7-highest-qualification', ['settingArray' => $settingArray])
                {{-- Highest Qualification  End--}}


                <div class="more_gualification_wrapper">
                    <div class=" px-4 py-5 bg-white sm:p-6">

                        <div class="emptyentre grid grid-cols-9 gap-2 xl:gap-1 {{count($studentApplicationsQualificationsCollection)>0 ? 'hidden' : ''}}">
                            <div class="hidden lg:block lg:col-span-1"><label for="country" class="block text-sm font-medium text-gray-700">Country * </label></div>
                            <div class="hidden lg:block lg:col-span-1"><label for="institution" class="block text-sm font-medium text-gray-700">Institution * </label></div>
                            <div class="hidden lg:block lg:col-span-1"><label for="qualificationType" class="block text-sm font-medium text-gray-700">Qualification * </label></div>
                            <div class="hidden lg:block lg:col-span-1"><label for="qualificationSubject" class="block text-sm font-medium text-gray-700">Subject * </label></div>
                            <div class="hidden lg:block lg:col-span-1"><label for="qualificationGrade" class="block text-sm font-medium text-gray-700">Grade * </label></div>
                            <div class="hidden lg:block lg:col-span-1"><label for="examiningBody" class="block text-sm font-medium text-gray-700">Examining Body *</label></div>
                            <div class="hidden lg:block lg:col-span-1"><label for=setting" class="block text-sm font-medium text-gray-700">Setting * </label></div>
                            <div class="hidden lg:block lg:col-span-1"><label for="year" class="block text-sm font-medium text-gray-700">Year * </label></div>
                        </div>

                        @foreach($studentApplicationsQualificationsCollection as $saq)
                        <div id="form_{{$saq->id}}" class="mt-10 lg:mt-0 form_ grid grid-cols-9 gap-2 xl:gap-1">
                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="country" class="lg:hidden block text-sm font-medium text-gray-700">Country * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="country_{{$saq->id}}" value="{{$saq->country}}" id="country_{{$saq->country}}" required></div>
                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="institution" class="lg:hidden block text-sm font-medium text-gray-700">Institution * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="institution_{{$saq->id}}" value="{{$saq->institution}}" id="institution_{{$saq->id}}" required></div>

                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="qualificationType" class="lg:hidden block text-sm font-medium text-gray-700">Qualification * </label>
                                <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="qualificationType_{{$saq->id}}" id="qualificationType_{{$saq->id}}">
                                    <option value="" disabled selected>-- Select --</option>
                                    @foreach ($studenAlternativeQualtype as $qualtype)
                                    <option value="{{$qualtype->hesa_code}}" {{$qualtype->hesa_code == $saq->qualification ? 'selected' : ''}}>
                                        {{ $qualtype->hesa_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="qualificationSubject" class="lg:hidden block text-sm font-medium text-gray-700">Subject * </label>
                                <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="qualificationSubject_{{$saq->id}}" id="qualificationSubject_{{$saq->id}}">
                                     <option value="" disabled selected>-- Select --</option>
                                    @foreach ($studenAlternativeQualsbj  as $qualsbj)
                                    <option value="{{$qualsbj->hesa_code}}" {{$qualsbj->hesa_code == $saq->subject ? 'selected' : ''}}>
                                        {{ $qualsbj->hesa_label }}</option>
                                    @endforeach
                                </select></div>
                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="qualificationGrade" class="lg:hidden block text-sm font-medium text-gray-700">Grade * </label>
                                <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="qualificationGrade_{{$saq->id}}" id="qualificationGrade_{{$saq->id}}" required>
                                    <option value="" disabled selected>-- Select --</option>
                                    @foreach ($studenAlternativeQualgrade  as $qualgrade)
                                    <option value="{{$qualgrade->hesa_code}}" {{$qualgrade->hesa_code == $saq->grade ? 'selected' : ''}}>
                                        {{ $qualgrade->hesa_label }}</option>
                                    @endforeach
                                </select></div>
                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="examiningBody" class="lg:hidden block text-sm font-medium text-gray-700">Examining Body *</label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="examiningBody_{{$saq->id}}" value="{{$saq->examining_body}}" id="examiningBody_{{$saq->id}}" required></div>
                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="setting" class="lg:hidden block text-sm font-medium text-gray-700">Setting * </label>
                                <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="sitting_{{$saq->id}}" id="sitting_{{$saq->id}}" required>
                                    <option value="" disabled selected>-- Select --</option>
                                    @foreach($settingArray as $store => $display)
                                    <option value="{{$store}}" {{$saq->sitting == $store? 'selected' : ''}}>{{ $display }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="year" class="lg:hidden block text-sm font-medium text-gray-700">Year * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="year_{{$saq->id}}" value="{{$saq->year}}" id="year_{{$saq->id}}" required></div>
                            <div class="col-span-9 lg:col-span-1 removeDB" id="{{$saq->id}}"><button class="inline-flex justify-center mt-2 py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">Delete</button></div></div>
                        @endforeach

                        <div id="formAddMore"></div>
                        <div>
                            <br>
                            <span class="cursor-pointer inline-flex justify-center mt-2 py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-800" id="addMore">Add more qualifications ++</span>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
                {{-- Submit --}}
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-900 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        Save
                    </button>
                </div>
                {{-- Submit END --}}
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function () {

        var _token = $('input[name="_token"]').val();

        function checkStudentHasQualifications() {

            $.ajax({
                url: "{{ route('student_applications_qualifications.checkStudentHasQualifications') }}",
                method: "POST",
                data: {
                    _token: _token
                },
                success: function (data)
                {
                    if (data[0]) {
                        $('.more_gualification_wrapper').show();
                        if (data[1]) {
                            $('.emptyentre').removeClass('hidden');
                        } else {
                            $('.emptyentre').addClass('hidden');
                        }
                    } else {
                        $('.more_gualification_wrapper').hide();
                        $('.emptyentre').addClass('hidden');
                    }
                }
            });
        }

        checkStudentHasQualifications();

        /**
         * Save qualifications to DB
         */
        $("form").submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('student_applications_qualifications.saveQualifications') }}",
                method: "POST",
                data: {
                    serializeArray: $(this).serializeArray(),
                    _token: _token
                },
                success: function ()
                {
                    location.reload();
                }
            });
        });

        var x = 1;

        /**
         * Display additional qualification row  to enter
         */
        $(document).on('click', '#addMore', function () {

            $('.emptyentre').removeClass('hidden');

            $('#formAddMore').append('<div class="mt-10 lg:mt-0 form_ grid grid-cols-9 gap-2 xl:gap-1" id="form_' + x + '">\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="country" class="lg:hidden block text-sm font-medium text-gray-700">Country * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="country_' + x + '" value="" id="country_' + x + '" required></div>\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="institution" class="lg:hidden block text-sm font-medium text-gray-700">Institution * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="institution_' + x + '" value="" id="institution_' + x + '" required></div>\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="qualificationType" class="lg:hidden block text-sm font-medium text-gray-700">Qualification * </label><select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="qualificationType_' + x + '" id="qualificationType_' + x + '" required><option value="">-- Select --</option><?php echo $selectBoxQualtype ?></select></div>\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="qualificationSubject" class="lg:hidden block text-sm font-medium text-gray-700">Subject * </label><select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="qualificationSubject_' + x + '" id="qualificationSubject_' + x + '" required><option value="">-- Select --</option><?php echo $selectBoxQualsbj; ?></select></div>\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="qualificationGrade" class="lg:hidden block text-sm font-medium text-gray-700">Grade * </label><select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="qualificationGrade_' + x + '" id="qualificationGrade_' + x + '" required><option value="">-- Select --</option><?php echo $selectBoxQualgrade; ?></select></div>\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="examiningBody" class="lg:hidden block text-sm font-medium text-gray-700">Examining Body *</label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="examiningBody_' + x + '" value="" id="examiningBody_' + x + '" required></div>\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="setting" class="lg:hidden block text-sm font-medium text-gray-700">Setting * </label><select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="sitting_' + x + '" name="sitting_' + x + '" required ><option value="" >Select Category</option></option><option value="S">Summer</option><option value="W">Winter</option></select></div>\n\
<div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="year" class="lg:hidden block text-sm font-medium text-gray-700">Year * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="year_' + x + '" value="" id="year_' + x + '" required></div><div class="col-span-9 lg:col-span-1 remove" id="' + x + '"><button class="inline-flex justify-center mt-2 py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">Delete</button></div></div>\n\
    ');
            x++;
        });

        /**
         * Remove qualification from the list
         */
        $(document).on('click', '.remove', function () {
            var formId = $(this).attr("id");
            selector = '#form_' + formId;
            $(selector).remove();

            if (!$('div').hasClass('form_')) {
                $('.emptyentre').addClass('hidden');
            }
            ;

        });

        /**
         * Remove saved qualification from DB
         */
        $(document).on('click', '.removeDB', function () {
            var formId = $(this).attr("id");
            selector = '#form_' + formId;
            $(selector).remove();

            $.ajax({
                url: "{{ route('student_applications_qualifications.deleteQualification') }}",
                method: "POST",
                data: {
                    formId: formId,
                    _token: _token
                }
            });

            if (!$('div').hasClass('form_')) {
                $('.emptyentre').addClass('hidden');
            }
            ;

        });

        /**
         * Highest_qualification - validation
         */
        $('select[name="highestQualification_hq"]').change(function (e) {
            var qualification = $(this).val();

            $.ajax({
                url: "{{ route('student_applications_qualifications.showMoreQualification') }}",
                method: "POST",
                data: {
                    qualification: qualification,
                    _token: _token
                },
                success: function (data)
                {
                    if (data) {
                        $('.more_gualification_wrapper').show();
                        $('.emptyentre').addClass('hidden');
                    } else {
                        $('.more_gualification_wrapper').hide();
                        $('.emptyentre').removeClass('hidden');
                    }
                }
            });
        });

    });

</script>
