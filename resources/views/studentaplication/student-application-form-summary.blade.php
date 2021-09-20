@include('studentaplication.header.head')

<div class="xl:hidden w-full bg-white-50 bg-gray-100">
    @include('studentaplication.progress-bar.horizontal')
</div>
{{ csrf_field()  }}
<div class="md:grid md:grid-cols-4 md:gap-6 bg-gray-100 pb-20 min-h-screen">
    <div class="sm:col-span-1 hidden xl:block mt-20 xl:ml-14 2xl:ml-24">
        <div class="mt-10 md:mt-0 "> 
            @include('studentaplication.progress-bar.vertical')
        </div>
    </div>
    <div class="sm:col-span-4 xl:col-span-3 mb-10 xl:mr-20 mr-5 ml-5 sm:mr-10 sm:ml-10 bg-white-50"> 
        <div class="mb-5 md:mt-0 md:col-span-2">
            @include('studentaplication.buttons-form')
        </div> 

        <div class="pt-10 text-xl px-4 py-3 text-left sm:px-6">
            Thanks for filling out our form!<br>
        </div>
        <div class="text-sm px-4 py-3 text-left sm:px-6">
            Press CONFIRM button to get email with confirmation. 
        </div>
        {{-- Submit --}}
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="button" onclick="window.location ='{{ route('confirmSaveForm', ['flow' => $studentApplicationsObject->id]) }}'" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-900 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">Confirm</button>
        </div>
        {{-- Submit END --}}
    </div>
</div>
