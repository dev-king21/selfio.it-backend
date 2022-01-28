@extends('public.base')

@section('title')
    {{ $event->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 style="color:white;">{{ $event->name }}</h1>
        </div>
        <div class="tz-gallery">
            <div class="custom-inline-flex-grid">
                @foreach($files as $index => $file)
                    <div style="width: 200px; padding: 20px">
                        <a class="lightbox" href="{{ Storage::disk('s3')->url($file) }}">
                            <img src="{{ Storage::disk('s3')->url($file) }}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let download_btn = $('<button class="download btn btn-primary btn-lg"><span class="glyphicon glyphicon-download-alt"></span> Download</button>');
        $(function () {
            baguetteBox.run('.tz-gallery');
            $('#baguetteBox-overlay').append(download_btn);
        });

        download_btn.on('click', function () {
            let p_val = $('#baguetteBox-slider').attr('style').replace('transform: translate3d(', '').split('%');
            let id = p_val[0] === '0' ? 0 : parseInt(p_val[0]) / -100;
            @foreach($files as $index => $file)
            if ('{{ $index }}' === '' + id) {
                let url = '{{ Storage::disk('s3')->url($file)}}';
                let res = url.split("/");
                let x = new XMLHttpRequest();
                x.open("GET", url, true);
                x.responseType = 'blob';
                x.onload = function (e) {
                    download(x.response, res[res.length - 1], "image/jpg");
                };
                x.send();
            }
            @endforeach
        });
    </script>
@endsection
