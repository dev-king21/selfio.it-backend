@extends('user.layouts.app')

@section('title')
    {{ $event->name }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row justify-content-center">
                    @foreach(Storage::disk('s3')->files('album/'.$event->code) as $index => $file)
                        <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto col-xl-auto pb-2">
                            <div class="card card-default img_holder">
                                <div class="card-body p-0 card-img"
                                     style="display:flex; background-image: url('{{ Storage::disk('s3')->url($file) }}');">
                                </div>
                                <div class="dropdown" style="position: absolute;top: 10px;right: 10px;z-index: 10;">
                                    <a id="dLabel" class="action text-decoration-none" href="#" type="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        •••
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-right event_menu"
                                        style="width: 120px" aria-labelledby="dLabel">
                                        <li>
                                            <a href="{{ route('album', strtotime($event->created_at)).'?id='.$index }}">View</a>
                                        </li>
                                        <li>
                                            <a class="download" href=""
                                               data-url="{{ Storage::disk('s3')->url($file) }}">Download</a>
                                        </li>
                                        <li>
                                            <a class="delete-image-btn" href="#" data-toggle="modal"
                                               data-target="#DeleteModal" data-url="{{ $file }}">Delete</a>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog"
         aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteModalLabel">Delete image</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to permanently delete this item?
                </div>
                <div class="modal-footer">
                    <form class="delete-form col-12" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Yes
                        </button>
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal" aria-label="Close">
                            No
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.download').on('click', function () {
            let url = $(this).attr('data-url');
            let res = url.split("/");
            let x = new XMLHttpRequest();
            x.open("GET", url, true);
            x.responseType = 'blob';
            x.onload = function (e) {
                download(x.response, res[res.length - 1], "image/jpg");
            };
            x.send();
        });

        $('.delete-image-btn').on('click', function () {
            let url = $(this).attr('data-url');
            $('.delete-form').attr('action', '{{ route('user.album.event.remove') }}?url=' + url);
        });
    </script>
@endsection
