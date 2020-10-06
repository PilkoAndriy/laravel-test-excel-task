<div role="alert" class="p-10">
    <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
        {{__('Validation error')}}
    </div>
    <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
        @foreach($messages as $message)
            <p>{{$message}}</p>
        @endforeach
    </div>
</div>
