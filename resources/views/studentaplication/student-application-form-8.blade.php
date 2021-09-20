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
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="p-1 bg-gray-50"></div>
                <div class="col-span-5 sm:col-span-6 text-3xl text-center pt-10 bg-white">
                    Employment History
                </div>
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="emptyentre grid grid-cols-7 gap-2 xl:gap-1 {{count($studentApplicationsEmploymentCollection)>0?'':'hidden'}}">
                        <div class="hidden lg:block lg:col-span-1"><label for="employer" class="block text-sm font-medium text-gray-700">Employer * </label></div>
                        <div class="hidden lg:block lg:col-span-1"><label for="jobTitle" class="block text-sm font-medium text-gray-700">Job Title * </label></div>
                        <div class="hidden lg:block lg:col-span-1"><label for="fullOrPartTim" class="block text-sm font-medium text-gray-700">Full or part time * </label></div>
                        <div class="hidden lg:block lg:col-span-1"><label for="roleDescription" class="block text-sm font-medium text-gray-700">Role description * </label></div>
                        <div class="hidden lg:block lg:col-span-1"><label for="employmentStartDate" class="block text-sm font-medium text-gray-700">Employment Start Date * </label></div>
                        <div class="hidden lg:block lg:col-span-1"><label for="employmentEndDate" class="block text-sm font-medium text-gray-700">Employment End Date * </label></div>

                    </div>

                    @foreach($studentApplicationsEmploymentCollection as $saq)
                    <div id="form_{{$saq->id}}" class="mt-10 lg:mt-0 form_ grid grid-cols-7 gap-2 xl:gap-1">
                        <div class="col-span-7 md:col-span-3 lg:col-span-1"><label for="employer" class="lg:hidden block text-sm font-medium text-gray-700">Employer * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="employer_{{$saq->id}}" value="{{$saq->employer}}" id="employer_{{$saq->employer}}" required></div>
                        <div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="jobTitle" class="lg:hidden block text-sm font-medium text-gray-700">Job Title * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="jobTitle_{{$saq->id}}" value="{{$saq->job_title}}" id="jobTitle_{{$saq->id}}" required></div>
                        <div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="fullOrPartTim" class="lg:hidden block text-sm font-medium text-gray-700">Full or part time * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="fullOrPartTime_{{$saq->id}}" value="{{$saq->full_or_part_time}}" id="fullOrPartTime_{{$saq->id}}" required></div>
                        <div class="col-span-7 md:col-span-3 lg:col-span-1"><label for="roleDescription" class="lg:hidden block text-sm font-medium text-gray-700">Role description * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="roleDescription_{{$saq->id}}" value="{{$saq->role_description}}" id="roleDescription_{{$saq->id}}" required></div>
                        <div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="employmentStartDate" class="lg:hidden block text-sm font-medium text-gray-700">Employment Start Date * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="date" name="employmentStartDate_{{$saq->id}}" value="{{$saq->employment_start_date}}" id="employmentStartDate_{{$saq->id}}" required></div>
                        <div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="employmentEndDate" class="lg:hidden block text-sm font-medium text-gray-700">Employment End Date * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="date" name="employmentEndDate_{{$saq->id}}" value="{{$saq->employment_end_date}}" id="employmentEndDate_{{$saq->id}}" required></div>
                        <div class="col-span-7 lg:col-span-1 remove" id="{{$saq->id}}"><button class="inline-flex justify-center mt-2 py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">Delete</button></div>
                    </div>
                    @endforeach

                    {{ csrf_field() }}
                    <div id="formAddMore"></div>
                    <div>
                        <br>
                        <span class="cursor-pointer inline-flex justify-center mt-2 py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-800" id="addMore">Add Employer ++</span>
                        <br>
                        <br>
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

        $("form").submit(function (event) {

            event.preventDefault();

            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('student_applications_employment.add_data') }}",
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
        $(document).on('click', '#addMore', function () {

            $('.emptyentre').removeClass('hidden');

            $('#formAddMore').append('<div class="mt-10 lg:mt-0 form_ grid grid-cols-7 gap-2" id="form_' + x + '">\n\
<div class="col-span-7 md:col-span-3 lg:col-span-1"><label for="employer" class="lg:hidden block text-sm font-medium text-gray-700">Employer * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="employer_' + x + '" value="" id="employer_' + x + '" required></div>\n\
<div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="jobTitle" class="lg:hidden block text-sm font-medium text-gray-700">Job Title * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="jobTitle_' + x + '" value="" id="jobTitle_' + x + '" required></div>\n\
<div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="fullOrPartTim" class="lg:hidden block text-sm font-medium text-gray-700">Full or part time * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="fullOrPartTime_' + x + '" value="" id="fullOrPartTime_' + x + '" required></div>\n\
<div class="col-span-7 md:col-span-3 lg:col-span-1"><label for="roleDescription" class="lg:hidden block text-sm font-medium text-gray-700">Role description * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="roleDescription_' + x + '" value="" id="roleDescription_' + x + '" required></div>\n\
<div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="employmentStartDate" class="lg:hidden block text-sm font-medium text-gray-700">Employment Start Date * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="date" name="employmentStartDate_' + x + '" value="" id="employmentStartDate_' + x + '" required></div>\n\
<div class="col-span-7 md:col-span-2 lg:col-span-1"><label for="employmentEndDate" class="lg:hidden block text-sm font-medium text-gray-700">Employment End Date * </label><input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="date" name="employmentEndDate_' + x + '" value="" id="employmentEndDate_' + x + '" required></div>\n\
<div class="col-span-7 lg:col-span-1 remove" id="' + x + '"><button class="inline-flex justify-center mt-2 py-1 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">Delete</button></div></div>');
            x++;

        });

        $(document).on('click', '.remove', function () {
            var formId = $(this).attr("id");
            selector = '#form_' + formId;
            $(selector).remove();

            if (!$('div').hasClass('form_')) {
                $('.emptyentre').addClass('hidden');
            }
            ;
        });

        $(document).on('click', '.removeDB', function () {
            var formId = $(this).attr("id");
            selector = '#form_' + formId;
            $(selector).remove();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('student_applications_employment.delete_data') }}",
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
    }
    );
</script>