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
                            Further Information
                        </div>
                        {{-- Resident_Abroad_In_Last_10_Years --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 {{ ($currentCountry == 'UK'|| $currentCountry =='EU') ? '' : 'hidden'}}">
                            <label for="resident_abroad_in_last_10_years" class="block text-sm font-medium text-gray-700">Have you been a resident for more than 3 weeks outside of the EU in the last 10 years? * </label>
                            <select name="resident_abroad_in_last_10_years" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" id="resident_abroad_in_last_10_years" {{($currentCountry == 'UK'|| $currentCountry == 'EU') ? 'required' : ''}}>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $value)
                                <option value="{{$value}}" 
                                        {{old('resident_abroad_in_last_10_years', $studentApplicationsObject->resident_abroad_in_last_10_years) == $value ? 'selected' : ''}}>{{ $value }} 
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Resident_Abroad_In_Last_10_Years END --}}

                        {{-- Resident_Abroad_Details --}}
                        <div id="resident_abroad_details_div" class="col-span-6 sm:col-span-6 md:col-span-4 {{!empty($studentApplicationsObject->resident_abroad_details) ? '' : 'hidden'}}">
                            <label for="resident_abroad_details" class="block text-sm font-medium text-gray-700">If 'Yes', please provide details: *</label>
                            <input id="resident_abroad_details" type="text" maxlength="700"  name="resident_abroad_details" class="focus:ring-indigo-900 focus:border-indigo-900 block shadow-sm  border-gray-300 rounded-md" value="{{ empty(old('resident_abroad_details', $studentApplicationsObject->resident_abroad_details)) ?  '' : old('resident_abroad_details', $studentApplicationsObject->resident_abroad_details) }}">
                        </div>  

                        {{-- Resident Abroad Details END --}}

                        {{-- Date First Entry To UK --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 {{ ($currentCountry == 'UK'|| $currentCountry == 'EU') ? '' : 'hidden'}}">
                            <label for="date_first_entry_to_uk" class="block text-sm font-medium text-gray-700">Date of first entry to the UK (other than for education) - (those born in the UK, leave blank) 
                                {{ (($currentCountry == 'UK'|| $currentCountry == 'EU') && $studentApplicationsObject->nationality!='E' ) ? '*' : ''}} </label>
                            <input id="date_first_entry_to_uk" class="focus:ring-indigo-900 focus:border-indigo-900 block shadow-sm  border-gray-300 rounded-md" type="date" class="form-control" name="date_first_entry_to_uk" value="{{ empty(old('date_first_entry_to_uk', $studentApplicationsObject->date_first_entry_to_uk) )?  '' : old('date_first_entry_to_uk', $studentApplicationsObject->date_first_entry_to_uk)}}"
                                   {{ (($currentCountry == 'UK'|| $currentCountry == 'EU') && $studentApplicationsObject->nationality!='E' ) ? 'required' : ''}}>
                        </div>
                        {{-- Date First Entry To UK END --}}

                        {{-- Who Will Pay Fees --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-5">
                            <label for="who_will_pay_fees" class="block text-sm font-medium text-gray-700">Who will be paying your fees? * </label>
                            <select name="who_will_pay_fees" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($studentMstufee as $mstufee)
                                <option value="{{$mstufee->hesa_code}}" 
                                        {{old('who_will_pay_fees', $studentApplicationsObject->who_will_pay_fees) == $mstufee->hesa_code ? 'selected' : ''}}>{{ $mstufee->hesa_label}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Who Will Pay Fees END --}}

                        {{-- Work In Industry --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4">
                            <label for="work_in_industry" class="block text-sm font-medium text-gray-700">Do you work (or are you actively seeking work) in the film, music or games industries? *</label>
                            <select name="work_in_industry" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $value)
                                <option value="{{$value}}" {{old('work_in_industry', $studentApplicationsObject->work_in_industry) == $value ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Work In Industry END --}}

                        {{-- Type Work In Industry --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 {{empty($studentApplicationsObject->work_in_industry_type) ? 'hidden' : ''}}" id="work_in_industry_type_div">
                            <label for="work_in_industry_type" class="block text-sm font-medium text-gray-700">If you answered “Yes” to the previous question, is this work freelance or self-employed?</label>
                            <select id="work_in_industry_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" name="work_in_industry_type">
                                <option value="0" disabled selected>-- Select --</option>
                                @foreach($typeWorkInIndustryArray as $value)
                                <option value="{{$value}}" {{old('work_in_industry_type', $studentApplicationsObject->work_in_industry_type) == $value ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Type Work In Industry END --}}

                        {{-- Tax Vat Number --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 {{empty($studentApplicationsObject->tax_vat_number) ? 'hidden' : ''}}" id="tax_vat_number_div">
                            <label for="tax_vat_number" class="block text-sm font-medium text-gray-700">If you answered “Self-employed”, what is your TAX / VAT Number?</label>
                            <input class="tax_vat_number focus:ring-indigo-900 focus:border-indigo-900 block shadow-sm  border-gray-300 rounded-md" type="text" maxlength="100"  name="tax_vat_number" 
                                   value="{{empty(old('tax_vat_number', $studentApplicationsObject->tax_vat_number)) ?  '' : old('tax_vat_number', $studentApplicationsObject->tax_vat_number) }}">
                        </div>   
                        {{-- Tax Vat Number END --}}

                        {{-- Need Payment Plan --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4">
                            <label for="need_payment_plan" class="block text-sm font-medium text-gray-700">Will you need a payment plan? *</label>
                            <select name="need_payment_plan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" required>
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $value)
                                <option value="{{$value}}" {{old('need_payment_plan', $studentApplicationsObject->need_payment_plan) == $value ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Need Payment Plan END --}}

                        {{-- Need Student Loan --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 {{($currentCountry == 'UK') ? '' : 'hidden'}}">
                            <label for="need_student_loan" class="block text-sm font-medium text-gray-700">Will you be applying for a Postgraduate Student Loan from Student Finance England? *</label>
                            <select name="need_student_loan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" id="need_student_loan">
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $value)
                                <option value="{{$value}}" {{old('need_student_loan', $studentApplicationsObject->need_student_loan) == $value ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Need Student Loan END --}}

                        {{-- Student Loan Critical --}}
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 {{($currentCountry == 'UK') ? '' : 'hidden'}}">
                            <label for="student_loan_critical" class="block text-sm font-medium text-gray-700">Would failure to receive a loan prevent you from accepting a place on your chosen course? *</label>
                            <select name="student_loan_critical" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-900 focus:border-indigo-900 sm:text-sm" id="student_loan_critical">
                                <option value="" disabled selected>-- Select --</option>
                                @foreach($requireAdjustments as $value)
                                <option value="{{$value}}" {{old('student_loan_critical', $studentApplicationsObject->student_loan_critical) == $value? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Student Loan Critical END --}}
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

<script type="text/javascript">
    $(document).ready(function () {

        /**
         * resident_abroad_in_last_10_years
         */
        $('select[name="resident_abroad_in_last_10_years"]').change(function (e) {
            var value = $(this).val();
            if (value == 'Yes') {
                $('#resident_abroad_details_div').show();
                setRequiredAttributeResident();
            } else {
                $('#resident_abroad_details_div').hide();
                $('input[name=resident_abroad_details]').attr('value', '');
                removeRequiredAttributeResident();
            }
        });
        /**
         * work_in_industry
         */
        $('select[name="work_in_industry"]').change(function (e) {
            // this line gives me the value after the change event.
            var value = $(this).val();
            if (value == 'Yes') {
                $('#work_in_industry_type_div').show();
                $('select[name="work_in_industry_type"]').val(0);
            } else {
                $('#work_in_industry_type_div').hide();
                $('#tax_vat_number_div').hide();
            }
        });
        /**
         * work_in_industry_type
         */
        $('select[name="work_in_industry_type"]').change(function (e) {
            // this line gives me the value after the change event.
            var value = $(this).val();
            if (value == 'Self-Employed') {
                $('#tax_vat_number_div').show();
            } else {
                $('#tax_vat_number_div').hide();
                $('input[name=tax_vat_number]').attr('value', '');
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
                        setRequiredAttributeUK();
                    } else {
                        removeRequiredAttributeUK();
                    }
                }
            });
        }
        checkCurrentCountryIsUK();

        function setRequiredAttributeUK() {
            document.getElementById("need_student_loan").setAttribute("required", "");
            document.getElementById("student_loan_critical").setAttribute("required", "");
        }
        function removeRequiredAttributeUK() {

            document.getElementById("need_student_loan").removeAttribute('required');
            document.getElementById("student_loan_critical").removeAttribute('required');
        }
        function setRequiredAttributeResident() {
            document.getElementById("resident_abroad_details").setAttribute("required", "");
        }
        function removeRequiredAttributeResident() {
            document.getElementById("resident_abroad_details").removeAttribute('required');
        }
    }
    );
</script>
