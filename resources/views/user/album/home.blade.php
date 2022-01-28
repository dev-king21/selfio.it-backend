@extends('user.layouts.app')

@section('title')
    {{ __('Album') }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="text-center"><h2>Events</h2></div>
                <div class="row justify-content-center">
                    @foreach($events as $index => $event)
                        @if (sizeof(Storage::disk('s3')->files('album/'.$event->code)) != 0)
                            <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto col-xl-auto pb-2">
                                <div class="card card-default img_holder">
                                    <div class="card-body p-0 card-img"
                                         style="display:flex; background-image: url('{{ Storage::disk('s3')->url(Storage::disk('s3')->files('album/'.$event->code)[0]) }}');">
                                        <a class="w-100 h-100" href="{{ route('user.album') }}?id={{ $event->id }}"></a>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a class="text-decoration-none"
                                           href="{{ route('user.album') }}?id={{ $event->id }}"
                                           style="font-size: medium">{{ $event->name }}</a>
                                        <div class="dropdown" style="float: right">
                                            <a id="dLabel" class="action text-decoration-none" href="#" type="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                •••
                                            </a>

                                            <ul class="dropdown-menu dropdown-menu-right event_menu"
                                                style="width: 120px" aria-labelledby="dLabel">
                                                <li>
                                                    <a href="{{ route('album', strtotime($event->created_at)) }}">
                                                        <i class="fa fa-th"></i>View</a>
                                                </li>
                                                <li>
                                                    <a class="share-album" href="#" data-toggle="modal"
                                                       data-target="#ShareModal"
                                                       data-url="{{ route('album', strtotime($event->created_at)) }}">
                                                        <i class="fa fa-share"></i>Share</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('slideshow', strtotime($event->created_at)) }}">
                                                        <i class="fa fa-play"></i>Slideshow</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div style="color: grey; font-size: smaller">
                                            {{ \App\Helper\Helper::dateFromDatetime($event->updated_at) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Share modal -->
    <div class="modal fade" id="ShareModal" tabindex="-1" role="dialog"
         aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="shareModalLabel">Share Album</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="w-100 share-url"
                           value="https://fotoshare.co/u/1835827985/Antonio%20%26%20Luana"
                           readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.share-album').on('click', function () {
            let url = $(this).attr('data-url');
            $('.share-url').val(url);
        });
    </script>
@endsection
