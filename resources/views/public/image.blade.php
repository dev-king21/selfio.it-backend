@extends('public.base')

@section('title')
    {{ $event->name }}
@endsection

@section('content')
    <div>
        <img src="{{ Storage::disk('s3')->url($file) }}">
        <button class="download btn btn-primary btn-lg">
            <span class="glyphicon glyphicon-download-alt"></span> Download
        </button>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
        });

        $('.download').on('click', function () {
            let url = '{{ Storage::disk('s3')->url($file)}}';
            let res = url.split("/");
            let x = new XMLHttpRequest();
            x.open("GET", url, true);
            x.responseType = 'blob';
            x.onload = function (e) {
                download(x.response, res[res.length - 1], "image/jpg");
            };
            x.send();
        });
    </script>
@endsection
