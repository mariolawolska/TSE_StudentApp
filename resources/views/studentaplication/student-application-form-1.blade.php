@include('studentaplication.header.head')

<div class="xl:hidden w-full bg-white-50 bg-gray-100">
    @include('studentaplication.progress-bar.horizontal')
</div>

<div class="md:grid md:grid-cols-4 md:gap-6 bg-gray-100 pb-20 min-h-screen">
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
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 text-3xl text-center p-3">
                            Course Details
                        </div>
                        <div class="col-span-6 sm:col-span-5 md:col-span-4">
                            <label for="course_group" class="block text-sm font-medium text-gray-700">Course Group * </label>
                            <select name="course_group" class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                <option value="" is_ma="" is_mfa="" disabled selected>-- Select --</option>
                                @foreach($nameOfCourseCollect as $courseGroupObj)
                                <option value="{{$courseGroupObj->course_group_id}}" is_ma="{{ $courseGroupObj->is_ma }}" is_mfa="{{ $courseGroupObj->is_mfa }}"
                                        {{old('course_group', $studentApplicationsObject->course_group_id) == $courseGroupObj->course_group_id ? 'selected' : ''}}>
                                    {{ $courseGroupObj->course_title}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Course Group END --}}

                        {{-- Qualification Type --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4" id="qualification_type_div" style="{{empty($studentApplicationsObject->qualification_type) ? 'display: none' : ''}}">
                            <label for="qualification_type" class="block text-sm font-medium text-gray-700">Qualification Type *</label>
                            <select name="qualification_type" class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="qualification_type">
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($qualificationType as $type)
                                <option value="{{ $type}}" 
                                        {{old('qualification_type', $studentApplicationsObject->qualification_type) == $type ? 'selected' : ''}}>{{$type}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Qualification Type END --}}

                        {{-- Academic Year Of Entry --}}
                        <div class="col-span-5 sm:col-span-3">
                            <legend  for="academic_year_of_entry" class="text-base font-medium text-gray-900">Academic Year Of Entry *</legend>
                            <div class="mt-4 space-y-4">
                                @foreach($academicYearOfEntry as $yearOfEntry)
                                <div class="flex items-center">
                                    <input required type="radio" name="academic_year_of_entry" value="{{$yearOfEntry}}" class="focus:ring-indigo-900 h-4 w-4 text-indigo-900 border-gray-300" 
                                           {{old('academic_year_of_entry', $studentApplicationsObject->academic_year_of_entry) == $yearOfEntry ? 'checked' : ''}}>
                                    <label for="academic_year_of_entry" class="ml-1 mr-3 block text-sm font-medium text-gray-700">{{$yearOfEntry}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- Academic Year Of Entry END --}}

                        {{-- Academic Month Of Entry --}}
                        <div class="col-span-5 sm:col-span-3">
                            <?php
                            ?>
                            <legend for="month_of_entry" class="text-base font-medium text-gray-900">Academic Month Of Entry *</legend>
                            <div class="mt-4 space-y-4">
                                @foreach($monthOfEntry as $monthNumber => $monthOfEntry)
                                <div class="flex items-center">
                                    <input required type="radio" name="month_of_entry" value="{{$monthOfEntry}}" class="focus:ring-indigo-900 h-4 w-4 text-indigo-900 border-gray-300" 
                                           {{old('month_of_entry', $studentApplicationsObject->month_of_entry) == $monthOfEntry ? 'checked' : ''}}>
                                    <label for="month_of_entry" class="ml-1 mr-3 block text-sm font-medium text-gray-700" >{{$monthOfEntry}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- Academic Month Of Entry END --}}

                        {{-- Mode Of Study MA --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4" id="mode_of_study_ma" style="{{(array_key_exists($studentApplicationsObject->course_group_id, $modeOfStudyMAIdArray)) ? '' : 'display: none'}}">
                            <label for="mode_of_study" class="block text-sm font-medium text-gray-700">Mode Of Study *</label>
                            <select name="mode_of_study" class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="MA_mode_of_study">
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($modeOfStudyMA_Array as $modeOfStudyMA)
                                <option value="{{$modeOfStudyMA}}" {{($studentApplicationsObject->mode_of_study == $modeOfStudyMA) ? 'selected' : ''}}>
                                    {{ $modeOfStudyMA }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Mode Of Study MA END --}}

                        {{-- Mode Of Study MFA --}}
                        <div class="col-span-6 sm:col-span-5 md:col-span-4" id="mode_of_study_mfa" style="{{(array_key_exists($studentApplicationsObject->course_group_id, $modeOfStudyMFAIdArray)) ? '' : 'display: none'}}">
                            <label for="mode_of_study" class="block text-sm font-medium text-gray-700">Mode Of Study *</label>
                            <select name="mode_of_study" class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="MFA_mode_of_study">
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($modeOfStudyMFA_Array as $modeOfStudyMFA)
                                <option value="{{$modeOfStudyMFA}}">
                                    {{ $modeOfStudyMFA }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Mode Of Study MFA END --}}
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
    $(function () {

        $('select[name="course_group"]').change(function (e) {
            var is_ma = $('select[name="course_group"] :selected').attr('is_ma');
            var is_mfa = $('select[name="course_group"] :selected').attr('is_mfa');

            if (is_ma == 1) {
                $('#mode_of_study_ma').show();
                $('#qualification_type_div').show();
                setRequiredAttributeQualificationType();
                setRequiredAttributModeOfStudy(is_ma, is_mfa);
            } else {
                $('#mode_of_study_ma').hide();
                $('#qualification_type_div').hide();
                removeRequiredAttributeQualificationTyp();
                removeRequiredAttributModeOfStudy(is_ma, is_mfa);
            }

            if (is_mfa == 1) {
                $('#mode_of_study_mfa').show();
                setRequiredAttributModeOfStudy(is_ma, is_mfa);
            } else {
                $('#mode_of_study_mfa').hide();
                removeRequiredAttributModeOfStudy(is_ma, is_mfa);
            }
        });

        function setRequiredAttributeQualificationType() {
            document.getElementById("qualification_type").setAttribute("required", "");
        }
        function removeRequiredAttributeQualificationTyp() {
            document.getElementById("qualification_type").removeAttribute('required');
        }
        function setRequiredAttributModeOfStudy(is_ma, is_mfa) {
            if (is_ma == 1) {
                document.getElementById("MA_mode_of_study").setAttribute("required", "");
            }
            if (is_mfa == 1) {
                document.getElementById("MFA_mode_of_study").setAttribute("required", "");
            }
        }
        function removeRequiredAttributModeOfStudy(is_ma, is_mfa) {
            if (is_ma == 1) {
                document.getElementById("MFA_mode_of_study").removeAttribute("required");
            }
            if (is_mfa == 1) {
                document.getElementById("MA_mode_of_study").removeAttribute("required");
            }
        }
    });
</script>
