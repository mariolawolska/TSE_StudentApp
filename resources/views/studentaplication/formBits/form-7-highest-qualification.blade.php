<div class="bg-gray-50 m-6 shadow overflow-hidden sm:rounded-md">
   
    <!--{{-- Highest Qualification --}}-->
    <div class="grid grid-cols-6">
        <div  class="px-4 py-5 sm:p-6 col-span-6 md:col-span-5">
            <label for="highest_qualification" class="block text-sm font-medium text-gray-700">Qualification: Type of Highest Qualification (e.g. MA, MSc, GCSE) *</label>
            <select name="highestQualification_hq" id="highest_qualification" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                <option value="" selected>-- Select --</option>
                @foreach($studenAlternativeQualification as $qualification)
                <option value="{{$qualification->hesa_code}}" {{$studentApplicationsObject->highest_qualification == $qualification->hesa_code ? 'selected' : ''}}>
                    {{ $qualification->hesa_label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--{{-- Highest Qualification  End--}}-->

    <div class=" px-4 py-5 sm:p-6">

        <div class="grid grid-cols-9 gap-2 xl:gap-1">
            <div class="hidden lg:block lg:col-span-1"><label for="country_hq" class="block text-sm font-medium text-gray-700">Country</label></div>
            <div class="hidden lg:block lg:col-span-1"><label for="institution_hq" class="block text-sm font-medium text-gray-700">Institution</label></div>
            <div class="hidden lg:block lg:col-span-1"><label for="subject_hq" class="block text-sm font-medium text-gray-700">Subject</label></div>
            <div class="hidden lg:block lg:col-span-1"><label for="grade_hq" class="block text-sm font-medium text-gray-700">Grade</label></div>
            <div class="hidden lg:block lg:col-span-1"><label for="examining_body_hq" class="block text-sm font-medium text-gray-700">Examining Body</label></div>
            <div class="hidden lg:block lg:col-span-1"><label for="setting_hq" class="block text-sm font-medium text-gray-700">Setting</label></div>
            <div class="hidden lg:block lg:col-span-1"><label for="year_hq" class="block text-sm font-medium text-gray-700">Year</label></div>
        </div>

        <div class="lg:mt-0 form_ grid grid-cols-9 gap-2 xl:gap-1">
            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="country_hq" class="lg:hidden block text-sm font-medium text-gray-700">Country</label>
                <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="country_hq" value="{{$studentApplicationsObject->country_hq}}">
            </div>
            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="institution_hq" class="lg:hidden block text-sm font-medium text-gray-700">Institution</label>
                <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="institution_hq" value="{{$studentApplicationsObject->institution_hq}}">
            </div>

            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="subject_hq" class="lg:hidden block text-sm font-medium text-gray-700">Subject</label>
                <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="subject_hq">
                   <option value="" selected>-- Select --</option>
                    @foreach ($studenAlternativeQualsbj  as $qualsbj)
                    <option value="{{$qualsbj->hesa_code}}" {{$qualsbj->hesa_code == $studentApplicationsObject->subject_hq ? 'selected' : ''}}>
                        {{ $qualsbj->hesa_label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="grade_hq" class="lg:hidden block text-sm font-medium text-gray-700">Grade</label>
                <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="grade_hq">
                   <option value="" selected>-- Select --</option>
                    @foreach ($studenAlternativeQualgrade  as $qualgrade)
                    <option value="{{$qualgrade->hesa_code}}" {{$qualgrade->hesa_code == $studentApplicationsObject->grade_hq ? 'selected' : ''}}>
                        {{ $qualgrade->hesa_label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="examining_body_hq" class="lg:hidden block text-sm font-medium text-gray-700">Examining Body</label>
                <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="examiningBody_hq" value="{{$studentApplicationsObject->examining_body_hq}}">
            </div>
            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="setting_hq" class="lg:hidden block text-sm font-medium text-gray-700">Setting</label>
                <select class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="setting_hq">
                   <option value="" selected>-- Select --</option>
                    @foreach($settingArray as $store => $display)
                    <option value="{{$store}}" {{$studentApplicationsObject->setting_hq == $store? 'selected' : ''}}>{{ $display }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-9 md:col-span-4 lg:col-span-1"><label for="year_hq" class="lg:hidden block text-sm font-medium text-gray-700">Year</label>
                <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" type="text" name="year_hq" value="{{$studentApplicationsObject->year_hq}}">
            </div>
        </div>
    </div>
</div>

