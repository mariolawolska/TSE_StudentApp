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
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 text-3xl text-center p-3">
                            Disabilities & Accessibility
                        </div>

                        {{-- Disabilities --}}
                        @include('studentaplication.formBits.form-3-disabilities')
                        {{-- Disabilities END --}}

                        {{-- Learning Disabilities --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4">
                            <label for="learning_disabilities" class="block text-sm font-medium text-gray-700">Do you have any learning difficulties or disabilities which may require learning support? *</label>

                            <select name="learning_disabilities" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $key=>$value)
                                <option value="{{$key}}" {{old('learning_disabilities', $studentApplicationsObject->learning_disabilities) == $key ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Learning Disabilities END --}}

                        {{-- Require Adjustments --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4">
                            <label for="require_adjustments" class="block text-sm font-medium text-gray-700">Do you require any reasonable adjustments to be made for you? *</label>
                            <select name="require_adjustments" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $key => $value)
                                <option value="{{$key}}" {{old('require_adjustments', $studentApplicationsObject->require_adjustments) == $key ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Require Adjustments END --}}

                        {{-- Disability Allowance --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4">
                            <label for="disability_allowance" class="block text-sm font-medium text-gray-700">Are you currently or will you be receiving student disability allowance? *</label>
                            <select name="disability_allowance" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $key=>$value)
                                <option value="{{$key}}" {{old('disability_allowance', $studentApplicationsObject->disability_allowance) == $key ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Disability Allowance END --}}

                        {{-- Require Appointmnet --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4">
                            <label for="require_appointmnet" class="block text-sm font-medium text-gray-700">Would you like an appointment to discuss any additional requirements that may affect your learning? *</label>
                            <select name="require_appointmnet" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $key=>$value)
                                <option value="{{$key}}" {{old('require_appointmnet', $studentApplicationsObject->require_appointmnet) == $key ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Require Appointmnet END --}}

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
