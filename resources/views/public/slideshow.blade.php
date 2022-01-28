@extends('public.base')

@section('title')
    {{ $event->name }}
@endsection

@section('style')
    <style>
        #app > div {
            position: absolute;
        }
    </style>
@endsection

@section('content')
    @foreach($files as $index => $file)
        <div>
            <img src="{{ Storage::disk('s3')->url($file) }}">
        </div>
    @endforeach
@endsection

@section('script')
    <script>
        $("#app > div:gt(0)").hide();

        setInterval(function () {
            $('#app > div:first')
                .fadeOut(1000)
                .next()
                .fadeIn(1000)
                .end()
                .appendTo('#app');
        }, 3000);
    </script>
@endsection
