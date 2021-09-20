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
                        <div class="col-span-5 sm:col-span-6 text-3xl text-center p-3">
                            Hardware & Equipment
                        </div>
                        {{-- Primary Computer --}}
                        <div class="col-span-6 md:col-span-4 xl:col-span-3">
                            <label for="primary_computer" class="block text-sm font-medium text-gray-700">Make & Model of Primary Computer</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer" 
                                   value="{{empty($studentApplicationsObject->primary_computer) ?  '' : $studentApplicationsObject->primary_computer }}">
                        </div>   
                        {{-- Primary Computer END --}}

                        {{-- Slave Computer --}}
                        <div class="col-span-6 md:col-span-4 xl:col-span-3">
                            <label for="slave_computer" class="block text-sm font-medium text-gray-700">Do you have a slave computer? (If ‘Yes’, please give details)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="slave_computer" 
                                   value="{{empty($studentApplicationsObject->slave_computer) ?  '' : $studentApplicationsObject->slave_computer }}">
                        </div>   
                        {{-- Slave Computer END --}}
                        {{-- Primary Computer Processor --}}
                        <div class="col-span-6 md:col-span-3">
                            <label for="primary_computer_processor" class="block text-sm font-medium text-gray-700">Computer processor</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_processor" 
                                   value="{{empty($studentApplicationsObject->primary_computer_processor) ?  '' : $studentApplicationsObject->primary_computer_processor }}">
                        </div>   
                        {{-- Primary Computer Processor END --}}

                        {{-- Primary Computer Ram --}}
                        <div class="col-span-6 md:col-span-3 ">
                            <label for="primary_computer_ram" class="block text-sm font-medium text-gray-700">Computer Memory (RAM)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_ram" 
                                   value="{{empty($studentApplicationsObject->primary_computer_ram) ?  '' : $studentApplicationsObject->primary_computer_ram }}">
                        </div>   
                        {{-- Primary Computer Ram END --}}

                        {{-- Primary Computer Gpu --}}
                        <div class="col-span-6 md:col-span-4 xl:col-span-3">
                            <label for="primary_computer_gpu" class="block text-sm font-medium text-gray-700">GPU (only applicable to applicants for Game courses)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_gpu" 
                                   value="{{empty($studentApplicationsObject->primary_computer_gpu) ?  '' : $studentApplicationsObject->primary_computer_gpu }}">
                        </div>   
                        {{-- Primary Computer Gpu END --}}

                        {{-- Primary Computer Hard Disks --}}
                        <div class="col-span-6 md:col-span-4 xl:col-span-3">
                            <label for="primary_computer_hard_disks" class="block text-sm font-medium text-gray-700">Hard Disk Size(s)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_hard_disks" 
                                   value="{{empty($studentApplicationsObject->primary_computer_hard_disks) ?  '' : $studentApplicationsObject->primary_computer_hard_disks }}">
                        </div>   
                        {{-- Primary Computer Hard Disks END --}}

                        {{-- Primary Computer OS --}}
                        <div class="col-span-6 md:col-span-3">
                            <label for="primary_computer_os" class="block text-sm font-medium text-gray-700">Operating System</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_os" 
                                   value="{{empty($studentApplicationsObject->primary_computer_os) ?  '' : $studentApplicationsObject->primary_computer_os }}">
                        </div>   
                        {{-- Primary Computer OS END --}}

                        {{-- Primary Computer Internet --}}
                        <div class="col-span-6 md:col-span-3">
                            <label for="primary_computer_internet" class="block text-sm font-medium text-gray-700">Internet Speed</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_internet" 
                                   value="{{empty($studentApplicationsObject->primary_computer_internet) ?  '' : $studentApplicationsObject->primary_computer_internet }}">
                        </div>   
                        {{-- Primary Computer Internet END --}}

                        {{-- Primary Computer Daw --}}
                        <div class="col-span-6 md:col-span-4 xl:col-span-3">
                            <label for="primary_computer_daw" class="block text-sm font-medium text-gray-700">What is your main Digital Audio Workstation (DAW)?</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_daw" 
                                   value="{{empty($studentApplicationsObject->primary_computer_daw) ?  '' : $studentApplicationsObject->primary_computer_daw }}">
                        </div>   
                        {{-- Primary Computer Daw END --}}

                        {{-- Primary Computer Sample Libraries --}}
                        <div class="col-span-6 md:col-span-4 xl:col-span-3">
                            <label for="primary_computer_sample_libraries" class="block text-sm font-medium text-gray-700">What are your primary sample libraries / Sound Design Libraries?</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_sample_libraries" 
                                   value="{{empty($studentApplicationsObject->primary_computer_sample_libraries) ?  '' : $studentApplicationsObject->primary_computer_sample_libraries }}">
                        </div>   
                        {{-- Primary Computer Sample Libraries END --}}

                        {{-- Primary Computer Monitors --}}
                        <div class="col-span-6 md:col-span-3">
                            <label for="primary_computer_monitors" class="block text-sm font-medium text-gray-700">Monitors (speakers)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_monitors" 
                                   value="{{empty($studentApplicationsObject->primary_computer_monitors) ?  '' : $studentApplicationsObject->primary_computer_monitors }}">
                        </div>   
                        {{-- Primary Computer Monitors END --}}

                        {{-- Primary Computer Audio Interface --}}
                        <div class="col-span-6 md:col-span-3">
                            <label for="primary_computer_audio_interface" class="block text-sm font-medium text-gray-700">Audio Interface</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_audio_interface" 
                                   value="{{empty($studentApplicationsObject->primary_computer_audio_interface) ?  '' : $studentApplicationsObject->primary_computer_audio_interface }}">
                        </div>   
                        {{-- Primary Computer Audio Interface END --}}

                        {{-- Primary Computer Recording Equipment --}}
                        <div class="col-span-6 md:col-span-4 xl:col-span-3">
                            <label for="primary_computer_recording_equipment" class="block text-sm font-medium text-gray-700">Recording Equipment (handheld recorders / mics etc.)</label>
                            <input class="mt-1 focus:ring-indigo-900 focus:border-indigo-900 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-500 placeholder-opacity-50" type="text" maxlength="100"  name="primary_computer_recording_equipment" 
                                   value="{{empty($studentApplicationsObject->primary_computer_recording_equipment) ?  '' : $studentApplicationsObject->primary_computer_recording_equipment }}">
                        </div>   
                        {{-- Primary Computer Recording Equipment END --}}
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
