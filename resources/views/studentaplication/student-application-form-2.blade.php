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
                            Personal Details
                        </div>
                        {{-- Surname --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="surname" >Surname *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="120" id="surname"  name="surname"  placeholder="Surname" 
                                   value="{{ empty(old('surname', $studentApplicationsObject->surname) )?  '' : old('surname', $studentApplicationsObject->surname)}}" required>
                        </div>   
                        {{-- Surname END --}}

                        {{-- First_names --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="first_names" >First Names *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="120" name="first_names" placeholder="First Names" value="{{empty(old('first_names', $studentApplicationsObject->first_names)) ? '' : old('first_names', $studentApplicationsObject->first_names) }}" required>
                        </div>   
                        {{-- first_names END --}}

                        {{-- Preferred Name --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="preferred_name" >Preferred Name</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="120" name="preferred_name" placeholder="Preferred Name" value="{{empty(old('preferred_name',$studentApplicationsObject->preferred_name)) ? '' : old('preferred_name', $studentApplicationsObject->preferred_name) }}">
                        </div>   
                        {{-- Preferred Name END --}}

                        {{-- Previous_name --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="previous_name" >Previous Name</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="120"  name="previous_name" placeholder="Previous Name" value="{{empty(old('previous_name', $studentApplicationsObject->previous_name)) ? '' : old('previous_name', $studentApplicationsObject->previous_name) }}">
                        </div>   
                        {{-- previous_name END --}}

                        {{-- Title --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="title">Title *</label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="title" required>
                                <option disabled selected> -- Select -- </option>
                                @foreach($titles as $title)
                                <option value="{{$title}}" {{old('title', $titleArray['title']) == $title? 'selected' : ''}}>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Title END --}}

                        {{-- Title Other--}}
                        <div class="col-span-6 sm:col-span-4" id="title_other_div" style="{{empty($titleArray['other']) ? 'display: none' : ''}}">
                            <label class="block text-sm font-medium text-gray-700" for="title_other"></label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="120"  name="title_other" placeholder="Title" 
                                   value="{{old('title_other', empty($titleArray['other'])) ? '' : old('title_other', $titleArray['other']) }}">
                        </div>
                        {{-- Title Other END --}}

                        {{-- DOB --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="dob" >Date of Birth *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" id="dob" type="date" name="dob" value="{{ empty(old('dob', $studentApplicationsObject->dob) )?  '' : old('dob', $studentApplicationsObject->dob)}}" required>
                        </div>
                        {{-- DOB END --}}

                        {{-- First (Native) Language --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="native_language" >First (Native) Language *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="120"  name="native_language" placeholder="Native Language"  
                                   value="{{empty(old('native_language', $studentApplicationsObject->native_language)) ? '' : old('native_language',$studentApplicationsObject->native_language) }}" required>
                        </div>   
                        {{-- First (Native) Language END --}}



                        {{-- Home Address --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="home_address" >Home Address *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="500"  name="home_address" placeholder="Home Address" 
                                   value="{{empty(old('home_address', $studentApplicationsObject->home_address)) ? '' : old('home_address', $studentApplicationsObject->home_address) }}" required>
                        </div>   
                        {{-- Home Address END --}}

                        {{-- Home Postcode / ZIP Code --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="home_postcode" >Home Postcode / ZIP Code *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="home_postcode" placeholder="Home Postcode / ZIP Code" 
                                   value="{{empty(old('home_postcode', $studentApplicationsObject->home_postcode)) ? '' : old('home_postcode', $studentApplicationsObject->home_postcode) }}" required>
                        </div>   
                        {{-- Home Address END --}}

                        {{-- Current Countries --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="current_country">Current Country of Residence *</label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="current_country" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($studentDomicile as $domicile)
                                <option value="{{$domicile->hesa_code}}" 
                                        {{old('current_country', $studentApplicationsObject->current_country) == $domicile->hesa_code ? 'selected' : ''}}>{{ $domicile->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Countries END --}}

                        {{-- Previous Country --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="previous_country">Country of Residence Previous to Study *</label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="previous_country" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($studentDomicile as $domicile)
                                <option value="{{$domicile->hesa_code}}" 
                                        {{old('previous_country', $studentApplicationsObject->previous_country) == $domicile->hesa_code ? 'selected' : ''}}>{{ $domicile->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Country of Residence Previous to Study END --}}

                        {{-- Contact Number --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="contact_number" >Contact Number *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="contact_number" placeholder="Contact Number"
                                   value="{{empty(old('contact_number', $studentApplicationsObject->contact_number)) ? '' : old('contact_number', $studentApplicationsObject->contact_number) }}" required>
                        </div>   
                        {{-- Number END --}}

                        {{-- Email Address --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="email" >Email Address *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="email" placeholder="example@domain.com"
                                   value="{{empty(old('email', $studentApplicationsObject->email)) ? '' : old('email', $studentApplicationsObject->email) }}"  pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required>
                        </div>   
                        {{-- Email Address END --}}

                        {{-- Correspondence Address --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="correspondence_address" >Correspondence Address (if different to home address) </label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="correspondence_address" placeholder="Correspondence Address" 
                                   value="{{empty(old('correspondence_address', $studentApplicationsObject->correspondence_address)) ? '' : old('correspondence_address', $studentApplicationsObject->correspondence_address) }}">
                        </div>   
                        {{-- Correspondence Address END --}}

                        {{-- Correspondence Postcode --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700" for="correspondence_postcode" >Correspondence Postcode (if different to home address)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="correspondence_postcode" placeholder="Correspondence Postcode" 
                                   value="{{empty(old('correspondence_postcode', $studentApplicationsObject->correspondence_postcode)) ? '' : old('correspondence_postcode', $studentApplicationsObject->correspondence_postcode) }}">
                        </div>   
                        {{-- Correspondence Postcode END --}}

                        {{-- Emergency Contact Name --}}
                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700" for="emergency_contact" >Emergency Contact Name *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="emergency_contact" placeholder="Emergency Contact Name"
                                   value="{{empty(old('emergency_contact',$studentApplicationsObject->emergency_contact)) ? '' : old('emergency_contact', $studentApplicationsObject->emergency_contact) }}">
                        </div>   
                        {{-- Emergency Contact Name END --}}

                        {{-- Emergency Contact Phone --}}
                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700" for="emergency_phone" >Emergency Contact Phone <span></span></label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" id="emergency_phone" type="text" maxlength="100"  name="emergency_phone"  placeholder="Emergency Phone"
                                   value="{{empty(old('emergency_phone', $studentApplicationsObject->emergency_phone)) ? '' : old('emergency_phone', $studentApplicationsObject->emergency_phone) }}" >
                        </div>   
                        {{-- Emergency Contact Phone END --}}

                        {{-- Emergency Contact Email --}}
                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700" for="emergency_email" >Emergency Contact Email *</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="emergency_email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" placeholder="example@domain.com" 
                                   value="{{empty(old('emergency_email', $studentApplicationsObject->emergency_email)) ? '' : old('emergency_email',$studentApplicationsObject->emergency_email) }}" required>
                        </div>   
                        {{-- Emergency Contact Email END --}}

                        {{-- Hesa_Student_Ethnic --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="ethnicity">Ethnicity <span></span></label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="ethnicity" id="ethnicity">
                                <option value="" selected>-- Select --</option>
                                @foreach($studentEthnic as $ethnic)
                                <option value="{{$ethnic->hesa_code}}" 
                                        {{old('ethnicity', $studentApplicationsObject->ethnicity) == $ethnic->hesa_code ? 'selected' : ''}}>{{ $ethnic->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Hesa_Student_Ethnic END --}}

                        {{-- Hesa_Student_Genderid --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Gender identity <span></span></label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="gender" id="gender">
                                <option value="" selected>-- Select --</option>
                                @foreach($studentGenderid as $genderid)
                                <option value="{{ $genderid->hesa_code}}"
                                        {{old('gender', $studentApplicationsObject->gender) == $genderid->hesa_code ? 'selected' : ''}}> {{ $genderid->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Hesa_Student_Genderid END --}}

                        {{-- Hesa_Student_Nationality --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="nationality">Nationality <span></span></label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="nationality" id="nationality">
                                <option value="" selected>-- Select --</option>
                                @foreach($studentNation as $nation)
                                <option value="{{ $nation->hesa_code}}"
                                        {{old('nationality', $studentApplicationsObject->nationality) == $nation->hesa_code ? 'selected' : ''}}>{{ $nation->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Hesa_Student_Nationality END --}}

                        {{-- Religion or belief --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="religion">Religion or belief <span></span></label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="religion" id="religion"> 
                                <option value="" selected>-- Select --</option>
                                @foreach($studentReligion as $religion)
                                <option value="{{$religion->hesa_code}}" 
                                        {{old('religion', $studentApplicationsObject->religion) == $religion->hesa_code ? 'selected' : ''}}>{{ $religion->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Religion or belief END --}}

                        {{-- Sex Identifier --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="sex_identifier">Sex identifier <span></span></label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="sex_identifier" id="sex_identifier">
                                <option value="" selected>-- Select --</option>
                                @foreach($studentSexid as $sexid)
                                <option value="{{ $sexid->hesa_code}}" 
                                        {{old('sex_identifier', $studentApplicationsObject->sex_identifier) == $sexid->hesa_code ? 'selected' : ''}}>{{ $sexid->hesa_label}}

                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Sex Identifier END --}}

                        {{-- Sexual Orientation --}}
                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700" for="sexual_orientation">Sexual orientation <span></span></label>
                            <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="sexual_orientation" id="sexual_orientation">
                                <option value="" selected>-- Select --</option>
                                @foreach($studentSexort as $sexort)
                                <option value="{{$sexort->hesa_code}}"
                                        {{old('sexual_orientation', $studentApplicationsObject->sexual_orientation) == $sexort->hesa_code ? 'selected' : ''}}>{{ $sexort->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Sexual Orientation END --}}
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

        /**
         * Title
         */
        $('select[name="title"]').change(function (e) {

            var title = $(this).val();
            if (title == 'Other') {
                $('#title_other_div').show();
            } else {
                $('#title_other_div').hide();
            }
        });

        var current_country = $('select[name="current_country"]').val();
        checkIfFieldIsRequired(current_country);

        /**
         * Set requred field if current_cuntry is UK
         */
        $('select[name="current_country"]').change(function (e) {

            var current_country = $(this).val();

            checkIfFieldIsRequired(current_country);


        });
        function checkIfFieldIsRequired(current_country) {
            if (['XF', 'XI', 'XK', 'XH', 'XG', 'XL'].includes(current_country)) {

                $('label span').addClass('required_field');
                setRequiredAttribute();
            } else {
                $('label span').removeClass('required_field');
                removeRequiredAttribute();
            }

            function setRequiredAttribute() {
                document.getElementById("emergency_phone").setAttribute("required", "");
                document.getElementById("ethnicity").setAttribute("required", "");
                document.getElementById("gender").setAttribute("required", "");
                document.getElementById("nationality").setAttribute("required", "");
                document.getElementById("religion").setAttribute("required", "");
                document.getElementById("sex_identifier").setAttribute("required", "");
                document.getElementById("sexual_orientation").setAttribute("required", "");
            }
            function removeRequiredAttribute() {

                document.getElementById("emergency_phone").removeAttribute('required');
                document.getElementById("ethnicity").removeAttribute('required');
                document.getElementById("gender").removeAttribute('required');
                document.getElementById("nationality").removeAttribute('required');
                document.getElementById("religion").removeAttribute('required');
                document.getElementById("sex_identifier").removeAttribute('required');
                document.getElementById("sexual_orientation").removeAttribute('required');
            }
        }
    });

</script>
