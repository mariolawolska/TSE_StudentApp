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
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-3">
                        <div class="col-span-6 text-3xl text-center p-3">
                            Further Information
                        </div>
                        {{-- Have Completed First Degree In English --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4">
                            <label for="have_completed_first_degree_in_english" class="block text-sm font-medium text-gray-700">Have you completed a first (undergraduate) degree that was taught in English? * </label>
                            <select name="have_completed_first_degree_in_english" class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $value)
                                <option value="{{$value}}" {{old('have_completed_first_degree_in_english', $studentApplicationsObject->have_completed_first_degree_in_english) == $value ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Have Completed First Degree In English END --}}

                        {{-- Sat_English_Language_Test --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4 {{empty($studentApplicationsObject->sat_english_language_test) ? 'hidden' : ''}}" id="sat_english_language_test_div">
                            <label for="sat_english_language_test" class="block text-sm font-medium text-gray-700">Have you sat, or are you intending to sit an English language test?</label>
                            <select id="sat_english_language_test" name="sat_english_language_test" class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="0" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $value)
                                <option value="{{$value}}" {{old('sat_english_language_test', $studentApplicationsObject->sat_english_language_test) == $value ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Sat_English_Language_Test END --}}


                        {{-- Sat English Language Test Details --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4 {{empty($studentApplicationsObject->sat_english_language_test_details) ? 'hidden' : ''}}" id="sat_english_language_test_details_div">
                            <label for="sat_english_language_test_details" class="block text-sm font-medium text-gray-700">Which test is this? (IELTS, TOEFL, CAE, EF SET etc.)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="sat_english_language_test_details" 
                                   value="{{empty(old('sat_english_language_test_details',$studentApplicationsObject->sat_english_language_test_details)) ?  '' : old('sat_english_language_test_details', $studentApplicationsObject->sat_english_language_test_details) }}">
                        </div>   
                        {{-- Sat English Language Test Details END --}}

                        {{-- Date First Entry To UK --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-3 {{empty($studentApplicationsObject->sat_english_language_test_date) ? 'hidden' : ''}}" id="sat_english_language_test_date_div">
                            <label for="sat_english_language_test_date" class="block text-sm font-medium text-gray-700">Date of first entry to the UK (other than for education) - (those born in the UK, leave blank)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" id="sat_english_language_test_date" type="date" class="form-control" name="sat_english_language_test_date" 
                                   value="{{ empty(old('sat_english_language_test_date', $studentApplicationsObject->sat_english_language_test_date) )?  '' : old('sat_english_language_test_date', $studentApplicationsObject->sat_english_language_test_date) }}">
                        </div>
                        {{-- Date First Entry To UK END --}}

                        {{-- Sat English Language Test Result --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4 {{empty($studentApplicationsObject->sat_english_language_test_result) ? 'hidden' : ''}}" id="sat_english_language_test_result_div">
                            <label for="sat_english_language_test_result" class="block text-sm font-medium text-gray-700">What result did you achieve?</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="sat_english_language_test_result" 
                                   value="{{empty(old('sat_english_language_test_result', $studentApplicationsObject->sat_english_language_test_result)) ?  '' : old('sat_english_language_test_result', $studentApplicationsObject->sat_english_language_test_result) }}">
                        </div>   
                        {{-- Sat English Language Test Result END --}}

                        {{-- Sat English Language Test More Info --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4">
                            <label for="sat_english_language_test_more_info" class="block text-sm font-medium text-gray-700">If you have any further information regarding an English language test, please give details.</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="500"  name="sat_english_language_test_more_info" 
                                   value="{{empty(old('sat_english_language_test_more_info', $studentApplicationsObject->sat_english_language_test_more_info)) ?  '' : old('sat_english_language_test_more_info',$studentApplicationsObject->sat_english_language_test_more_info) }}">
                        </div>   
                        {{-- Sat English Language Test Details END --}}
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
    /**
     * have_completed_first_degree_in_english
     */
    $('select[name="have_completed_first_degree_in_english"]').change(function (e) {
        var value = $(this).val();

        if (value == 'Yes') {
            $('#sat_english_language_test_div').show();
            $('select[name="sat_english_language_test"]').val(0);
        } else {
            $('#sat_english_language_test_div').hide();
            $('#sat_english_language_test_details_div').hide();
            $('#sat_english_language_test_date_div').hide();
            $('#sat_english_language_test_result_div').hide();
        }
    });
    /**
     * sat_english_language_test
     */
    $('select[name="sat_english_language_test"]').change(function (e) {
        var value = $(this).val();

        if (value == 'Yes') {
            $('#sat_english_language_test_details_div').show();
            $('#sat_english_language_test_date_div').show();
            $('#sat_english_language_test_result_div').show();
        } else {
            $('#sat_english_language_test_details_div').hide();
            $('#sat_english_language_test_date_div').hide();
            $('#sat_english_language_test_result_div').hide();
        }
    });

</script>


