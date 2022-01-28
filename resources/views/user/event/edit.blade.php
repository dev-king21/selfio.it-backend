@extends('user.layouts.app')

@section('title')
    {{ __('Edit Event') }}
@endsection

@section('style')
    <style>
        section {
            padding: 20px 0;
        }

        #tabs {
            background: #007b5e;
            color: #eee;
        }

        #tabs .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            font-size: 20px;
        }

        #tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            color: #f3f3f3;
            background-color: transparent;
            border-color: transparent transparent #f3f3f3;
            border-bottom: 4px solid !important;
            font-weight: bold;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 80%;
                margin: 1.75rem auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <form action="{{ route('user.event.edit') }}" method="POST">
            @csrf
            <input type="text" style="display: none" name="id" value="{{ $event->id }}">
            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 mb-2">
                    <label for="event_name">Event name</label>
                    <i class="fa fa-info-circle _tooltip">
                        <span class="tooltiptext">
                            The event name must be a single word containing English letters and numbers starting with a capital letter. Once created, it cannot be modified.
                        </span>
                    </i>
                    <input name="event_name" type="text" class="form-control" placeholder="Event name" maxlength="12"
                           pattern="[A-Z]{1}[a-zA-Z0-9]+" value="{{ $event->name }}" required readonly>

                    @if (\Illuminate\Support\Facades\Session::has('error'))
                        <div class="col-md-12 alert alert-danger text-center">
                            {{ \Illuminate\Support\Facades\Session::get('error') }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"
                               style="position: absolute;right: 10px;">×</a>
                        </div>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 mb-2">
                    <label for="orientation">Orientation</label>
                    <i class="fa fa-info-circle _tooltip">
                        <span class="tooltiptext">
                            The orientation to be used in the photo booth.
                        </span>
                    </i>
                    <select name="orientation" class="form-control orientation" required>
                        <option value="">Select the orientation</option>
                        @if ($event->orientation == "Landscape")
                            <option value="Landscape" selected>Landscape</option>
                            <option value="Portrait">Portrait</option>
                        @else
                            <option value="Landscape">Landscape</option>
                            <option value="Portrait" selected>Portrait</option>
                        @endif
                    </select>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 mb-2">
                    <label for="start_time">Start</label>
                    <i class="fa fa-info-circle _tooltip">
                        <span class="tooltiptext">
                            The start time of the event.
                        </span>
                    </i>
                    <input name="start_time" id="start_time" type="datetime-local" class="form-control"
                           value="{{ $event->start_time }}" required readonly>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 mb-2">
                    <label for="end_time">End</label>
                    <i class="fa fa-info-circle _tooltip">
                        <span class="tooltiptext">
                            The end time of the event.
                        </span>
                    </i>
                    <input name="end_time" id="end_time" type="datetime-local" class="form-control"
                           value="{{ $event->end_time }}" required readonly>
                </div>

                <section id="tabs" class="col-md-12">
                    <nav>
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-style-tab" data-toggle="tab" href="#nav-style"
                               role="tab" aria-controls="nav-style" aria-selected="true">Style</a>
                            <a class="nav-item nav-link" id="nav-overlay-tab" data-toggle="tab" href="#nav-overlay"
                               role="tab" aria-controls="nav-overlay" aria-selected="false">Overlay</a>
                            <a class="nav-item nav-link" id="nav-green-screen-tab" data-toggle="tab"
                               href="#nav-green-screen"
                               role="tab" aria-controls="nav-green-screen" aria-selected="false">Green screen</a>
                            <a class="nav-item nav-link hide" id="nav-sharing-tab" data-toggle="tab" href="#nav-sharing"
                               role="tab" aria-controls="nav-sharing" aria-selected="false">Sharing</a>
                            <a class="nav-item nav-link hide" id="nav-extras-tab" data-toggle="tab" href="#nav-extras"
                               role="tab" aria-controls="nav-extras" aria-selected="false">Extra features</a>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-style" role="tabpanel"
                             aria-labelledby="nav-style-tab">
                            <!------------------------------Countdown------------------------------------------>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                                    @if ($event->countdown)
                                        <input type="checkbox" id="countdown" name="countdown" checked>
                                    @else
                                        <input type="checkbox" id="countdown" name="countdown">
                                    @endif
                                    <label for="countdown">Countdown (seconds)</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate the countdown.
                                        </span>
                                    </i>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2 countdown_time hide">
                                    <label for="countdown_time">Seconds</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Select the number of seconds.
                                        </span>
                                    </i>
                                    <select name="countdown_time" class="form-control" required>
                                        @for ($i=1;$i<10;$i++)
                                            @if ($event->countdown_time != 0 && $event->countdown_time == $i)
                                                <option value="{{ $i }}" selected>{{ $i }}</option>
                                            @elseif ($event->countdown_time == 0 && $i == 5)
                                                <option value="{{ $i }}" selected>{{ $i }}</option>
                                            @else
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <input id="style" type="hidden" name="style" value="{{ $event->style }}"/>
                                <!------------------------------Style 1------------------------------------------>
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mb-2">
                                    <img src="{{ asset('images/user/landscape_1.png') }}" style="width:100%"
                                         class="col-12 style_1" alt="">
                                    <button type="button" class="col-12 btn btn-success btn-block style"
                                            data-button="1">
                                        1 photo 4 x 6"
                                    </button>
                                </div>

                                <!------------------------------Style 2------------------------------------------>
                            {{--                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mb-2">--}}
                            {{--                                    <img src="{{ asset('images/user/landscape_4_gif.png') }}"--}}
                            {{--                                         class="col-12 style_2" style="width:100%" alt="">--}}
                            {{--                                    <button type="button" class="col-12 btn btn-primary btn-block style"--}}
                            {{--                                            data-button="2">--}}
                            {{--                                        4 photos + GIF--}}
                            {{--                                    </button>--}}
                            {{--                                </div>--}}

                            <!------------------------------Style 3------------------------------------------>
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mb-2">
                                    <img src="{{ asset('images/user/landscape_4_strip_gif.png') }}"
                                         class="col-12 style_3" style="width:100%" alt="">
                                    <button type="button" class="col-12 btn btn-primary btn-block style"
                                            data-button="3">
                                        4 photos strip + GIF
                                    </button>
                                </div>

                                <!------------------------------Style VIDEO------------------------------------------>
                                {{--                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">--}}
                                {{--                                    <img src="{{ asset('images/user/landscape_video.png') }}"--}}
                                {{--                                         class="col-12 style_video" style="width:100%" alt="">--}}
                                {{--                                    <button type="button" class="col-12 btn btn-primary btn-block style"--}}
                                {{--                                            data-button="4">--}}
                                {{--                                        Video booth--}}
                                {{--                                    </button>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-overlay" role="tabpanel"
                             aria-labelledby="nav-overlay-tab">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                                    <img class="overlay_image" src="{{ asset('images/user/landscape_overlay.png') }}"
                                         width="60px" alt="">
                                    <label>Overlay/Template</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Guests can see the company logo and screen wallpaper.
                                        </span>
                                    </i>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 mt-auto mb-auto">
                                    @if ($event->preview)
                                        <input type="checkbox" id="preview" name="preview" checked>
                                    @else
                                        <input type="checkbox" id="preview" name="preview">
                                    @endif
                                    <label for="preview">Preview</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate to preview overlay.
                                        </span>
                                    </i>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mb-2 four_six hide">
                                    <img class="four_six_image" src="{{ asset('images/user/strip_4_6.png') }}"
                                         height="60px" alt="">
                                    @if ($event->four_six)
                                        <input type="checkbox" id="four_six" name="four_six" checked>
                                    @else
                                        <input type="checkbox" id="four_six" name="four_six">
                                    @endif
                                    <label for="four_six">4*6</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate to use 4*6 print format.
                                        </span>
                                    </i>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mb-2 gif">
                                    <img class="gif_image" src="{{ asset('images/user/templates/landscape/gif.gif') }}"
                                         height="60px" alt="">
                                    @if ($event->gif)
                                        <input type="checkbox" id="gif" name="gif" checked>
                                    @else
                                        <input type="checkbox" id="gif" name="gif">
                                    @endif
                                    <label for="gif">Gif</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate to use gif.
                                        </span>
                                    </i>
                                </div>

                                <div class="col-12 print-template">
                                    <!------------------------------Print Overlay------------------------------------------>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <input id="overlay0" type="hidden" name="overlay0"
                                               value="{{ $event->print_overlay }}"/>
                                        <fieldset
                                            style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">
                                            <div class="col-12">
                                                Print Overlay / Print Template
                                                <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.
                                                    </span>
                                                </i>
                                            </div>

                                            @if (strpos($event->print_overlay, 'http') === 0)
                                                <img class="col-12 overlay-image-0" src="{{ $event->print_overlay }}"
                                                     alt="">
                                            @elseif (strpos($event->print_overlay, 'overlay_images/') === 0)
                                                <img class="col-12 overlay-image-0"
                                                     src="{{ url('/storage/'.$event->print_overlay) }}" alt="">
                                            @else
                                                <img class="col-12 overlay-image-0" src="" alt="">
                                            @endif
                                            <input type="file" style="display: none" data-id="0"
                                                   id="select-overlay-0" accept="image/x-png"/>
                                            <button type="button" class="col-12 btn btn-success image-select-button"
                                                    data-id="0">
                                                <i class="fa fa-camera" aria-hidden="true"></i>
                                                Upload my own design
                                            </button>

                                            <button type="button" class="col-12 btn btn-primary print-overlay"
                                                    data-id="0"
                                                    data-toggle="modal" data-target="#OverlayModal">
                                                <i class="fa fa-camera" aria-hidden="true"></i>
                                                Use a template
                                            </button>
                                        </fieldset>
                                    </div>

                                    <div class="col-12 templates">
                                        <div class="row">
                                            <!------------------------------GIF animation------------------------------------------>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                @if ($event->gif_animate)
                                                    <input type="checkbox" id="gif_animate" name="gif_animate" checked>
                                                @else
                                                    <input type="checkbox" id="gif_animate" name="gif_animate">
                                                @endif
                                                <label for="gif_animate">GIF animation (different overlay for each
                                                    photo)</label>
                                                <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                       Different overlay for each photo. You must upload four PNG files with the same orientation as your photo.
                                                    </span>
                                                </i>
                                            </div>

                                            <!------------------------------Boomrang effect------------------------------------------>
                                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mb-2">
                                                @if ($event->boomerang)
                                                    <input type="checkbox" id="boomerang" name="boomerang" checked>
                                                @else
                                                    <input type="checkbox" id="boomerang" name="boomerang">
                                                @endif
                                                <label for="boomerang">Boomerang effect</label>
                                                <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                       Activate boomerang effect.
                                                    </span>
                                                </i>
                                            </div>

                                            <!------------------------------With frame------------------------------------------>
                                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 mb-2 use_overlay">
                                                @if ($event->boomerang)
                                                    <input type="checkbox" id="use_overlay" name="use_overlay" checked>
                                                @else
                                                    <input type="checkbox" id="use_overlay" name="use_overlay">
                                                @endif
                                                <label for="use_overlay">Use overlay</label>
                                                <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                       Activate to use overlay on boomerang.
                                                    </span>
                                                </i>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!------------------------------Overlay 1------------------------------------------>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 overlay1">
                                                <input id="overlay1" type="hidden" name="overlay1"
                                                       value="{{ $event->overlay1 }}"/>
                                                <fieldset
                                                    style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">
                                                    <div class="col-12">
                                                        Overlay 1 / Template 1
                                                        <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.
                                                    </span>
                                                        </i>
                                                    </div>

                                                    @if (strpos($event->overlay1, 'http') === 0)
                                                        <img class="col-12 overlay-image-1" src="{{ $event->overlay1 }}"
                                                             alt="">
                                                    @elseif (strpos($event->overlay1, 'overlay_images/') === 0)
                                                        <img class="col-12 overlay-image-1"
                                                             src="{{ url('/storage/'.$event->overlay1) }}" alt="">
                                                    @else
                                                        <img class="col-12 overlay-image-1" alt="">
                                                    @endif
                                                    <input type="file" style="display: none" data-id="1"
                                                           id="select-overlay-1" accept="image/x-png"/>
                                                    @if ($event->overlay1 == '')
                                                        <button type="button" data-id="1"
                                                                class="col-12 btn btn-danger delete-overlay hide">
                                                            Delete
                                                        </button>
                                                    @else
                                                        <button type="button" data-id="1"
                                                                class="col-12 btn btn-danger delete-overlay">
                                                            Delete
                                                        </button>
                                                    @endif
                                                    <button type="button" data-id="1"
                                                            class="col-12 btn btn-success image-select-button">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Upload my own design
                                                    </button>

                                                    <button type="button" class="col-12 btn btn-primary" data-id="1"
                                                            data-toggle="modal" data-target="#OverlayModal">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Use a template
                                                    </button>
                                                </fieldset>
                                            </div>

                                            <!------------------------------Overlay 2------------------------------------------>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 overlay2 hide">
                                                <input id="overlay2" type="hidden" name="overlay2"
                                                       value="{{ $event->overlay2 }}"/>
                                                <fieldset
                                                    style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">
                                                    <div class="col-12">
                                                        Overlay 2 / Template 2
                                                        <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.
                                                    </span>
                                                        </i>
                                                    </div>

                                                    @if (strpos($event->overlay2, 'http') === 0)
                                                        <img class="col-12 overlay-image-2" src="{{ $event->overlay2 }}"
                                                             alt="">
                                                    @elseif (strpos($event->overlay2, 'overlay_images/') === 0)
                                                        <img class="col-12 overlay-image-2"
                                                             src="{{ url('/storage/'.$event->overlay2) }}" alt="">
                                                    @else
                                                        <img class="col-12 overlay-image-2" alt="">
                                                    @endif
                                                    <input type="file" style="display: none" data-id="2"
                                                           id="select-overlay-2" accept="image/x-png"/>
                                                    @if ($event->overlay2 == '')
                                                        <button type="button" data-id="2"
                                                                class="col-12 btn btn-danger delete-overlay hide">
                                                            Delete
                                                        </button>
                                                    @else
                                                        <button type="button" data-id="2"
                                                                class="col-12 btn btn-danger delete-overlay">
                                                            Delete
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                            class="col-12 btn btn-success image-select-button"
                                                            data-id="2">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Upload my own design
                                                    </button>

                                                    <button type="button" class="col-12 btn btn-primary" data-id="2"
                                                            data-toggle="modal" data-target="#OverlayModal">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Use a template
                                                    </button>
                                                </fieldset>
                                            </div>

                                            <!------------------------------Overlay 3------------------------------------------>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 overlay3 hide">
                                                <input id="overlay3" type="hidden" name="overlay3"
                                                       value="{{ $event->overlay3 }}"/>
                                                <fieldset
                                                    style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">
                                                    <div class="col-12">
                                                        Overlay 3 / Template 3
                                                        <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.
                                                    </span>
                                                        </i>
                                                    </div>

                                                    @if (strpos($event->overlay3, 'http') === 0)
                                                        <img class="col-12 overlay-image-3" src="{{ $event->overlay3 }}"
                                                             alt="">
                                                    @elseif (strpos($event->overlay3, 'overlay_images/') === 0)
                                                        <img class="col-12 overlay-image-3"
                                                             src="{{ url('/storage/'.$event->overlay3) }}" alt="">
                                                    @else
                                                        <img class="col-12 overlay-image-3" alt="">
                                                    @endif
                                                    <input type="file" style="display: none" data-id="3"
                                                           id="select-overlay-3" accept="image/x-png"/>
                                                    @if ($event->overlay3 == '')
                                                        <button type="button" data-id="3"
                                                                class="col-12 btn btn-danger delete-overlay hide">
                                                            Delete
                                                        </button>
                                                    @else
                                                        <button type="button" data-id="3"
                                                                class="col-12 btn btn-danger delete-overlay">
                                                            Delete
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                            class="col-12 btn btn-success image-select-button"
                                                            data-id="3">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Upload my own design
                                                    </button>

                                                    <button type="button" class="col-12 btn btn-primary" data-id="3"
                                                            data-toggle="modal" data-target="#OverlayModal">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Use a template
                                                    </button>
                                                </fieldset>
                                            </div>

                                            <!------------------------------Overlay 4------------------------------------------>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 overlay4 hide">
                                                <input id="overlay4" type="hidden" name="overlay4"
                                                       value="{{ $event->overlay4 }}"/>
                                                <fieldset
                                                    style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">
                                                    <div class="col-12">
                                                        Overlay 4 / Template 4
                                                        <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 MB.
                                                    </span>
                                                        </i>
                                                    </div>

                                                    @if (strpos($event->overlay4, 'http') === 0)
                                                        <img class="col-12 overlay-image-4" src="{{ $event->overlay4 }}"
                                                             alt="">
                                                    @elseif (strpos($event->overlay4, 'overlay_images/') === 0)
                                                        <img class="col-12 overlay-image-4"
                                                             src="{{ url('/storage/'.$event->overlay4) }}" alt="">
                                                    @else
                                                        <img class="col-12 overlay-image-4" alt="">
                                                    @endif
                                                    <input type="file" style="display: none" data-id="4"
                                                           id="select-overlay-4" accept="image/x-png"/>
                                                    @if ($event->overlay4 == '')
                                                        <button type="button" data-id="4"
                                                                class="col-12 btn btn-danger delete-overlay hide">
                                                            Delete
                                                        </button>
                                                    @else
                                                        <button type="button" data-id="4"
                                                                class="col-12 btn btn-danger delete-overlay">
                                                            Delete
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                            class="col-12 btn btn-success image-select-button"
                                                            data-id="4">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Upload my own design
                                                    </button>

                                                    <button type="button" class="col-12 btn btn-primary" data-id="4"
                                                            data-toggle="modal" data-target="#OverlayModal">
                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                        Use a template
                                                    </button>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-green-screen" role="tabpanel"
                             aria-labelledby="nav-green-screen-tab">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <img src="{{ asset('images/user/green_screen.png') }}" width="60px" alt="">
                                    @if ($event->green_screen)
                                        <input type="checkbox" id="green_screen" name="green_screen" checked>
                                    @else
                                        <input type="checkbox" id="green_screen" name="green_screen">
                                    @endif
                                    <label for="green_screen">Green screen</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate this feature to use the green screen background.
                                        </span>
                                    </i>
                                </div>

                                <div class="col-12 green_screen-template hide">
                                    <div class="row">
                                        <!------------------------------H value------------------------------------------>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <label for="h_value">H value</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Set this value for green screen background.
                                                </span>
                                            </i>
                                            <input type="range" id="h_value" name="h_value" min="0" max="360"
                                                   value="{{ $event->h_value }}">
                                            <label class="h_value">{{ $event->h_value }}</label>
                                        </div>
                                        <!------------------------------S value------------------------------------------>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <label for="s_value">S value</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Set this value for green screen background.
                                                </span>
                                            </i>
                                            <input type="range" id="s_value" name="s_value" min="0" max="100"
                                                   value="{{ $event->s_value }}">
                                            <label class="s_value">{{ $event->s_value }}</label>
                                        </div>
                                        <!------------------------------B value------------------------------------------>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <label for="b_value">B value</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Set this value for green screen background.
                                                </span>
                                            </i>
                                            <input type="range" id="b_value" name="b_value" min="0" max="100"
                                                   value="{{ $event->b_value }}">
                                            <label class="b_value">{{ $event->b_value }}</label>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <input id="overlay5" type="hidden" name="overlay5"
                                                   value="{{ $event->green_background }}"/>
                                            <fieldset
                                                style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">
                                                <div class="col-12">
                                                    Background
                                                    <i class="fa fa-info-circle _tooltip">
                                                    <span class="tooltiptext">
                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.
                                                    </span>
                                                    </i>
                                                </div>

                                                @if (strpos($event->green_background, 'http') === 0)
                                                    <img class="col-12 overlay-image-5" src="{{ $event->green_background }}"
                                                         alt="">
                                                @elseif (strpos($event->green_background, 'overlay_images/') === 0)
                                                    <img class="col-12 overlay-image-5"
                                                         src="{{ url('/storage/'.$event->green_background) }}" alt="">
                                                @else
                                                    <img class="col-12 overlay-image-5" alt="">
                                                @endif
                                                <input type="file" style="display: none" data-id="5"
                                                       id="select-overlay-5" accept="image/x-png"/>
                                                @if ($event->green_background == '')
                                                    <button type="button" data-id="5"
                                                            class="col-12 btn btn-danger delete-overlay hide">
                                                        Delete
                                                    </button>
                                                @else
                                                    <button type="button" data-id="5"
                                                            class="col-12 btn btn-danger delete-overlay">
                                                        Delete
                                                    </button>
                                                @endif
                                                <button type="button" data-id="5"
                                                        class="col-12 btn btn-success image-select-button">
                                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                                    Upload my own design
                                                </button>

{{--                                                <button type="button" class="col-12 btn btn-primary" data-id="5"--}}
{{--                                                        data-toggle="modal" data-target="#OverlayModal">--}}
{{--                                                    <i class="fa fa-camera" aria-hidden="true"></i>--}}
{{--                                                    Use a template--}}
{{--                                                </button>--}}
                                            </fieldset>
                                        </div>
                                        {{--                                        <!------------------------------Green screen background 1------------------------------------------>--}}
                                        {{--                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 overlay1">--}}
                                        {{--                                            <input id="green_screen1" type="hidden" name="green_screen1"/>--}}
                                        {{--                                            <fieldset--}}
                                        {{--                                                style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">--}}
                                        {{--                                                <div class="col-12">--}}
                                        {{--                                                    Green screen background 1--}}
                                        {{--                                                    <i class="fa fa-info-circle _tooltip">--}}
                                        {{--                                                    <span class="tooltiptext">--}}
                                        {{--                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.--}}
                                        {{--                                                    </span>--}}
                                        {{--                                                    </i>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <img class="col-12 green_screen-image-1" src="" alt="">--}}
                                        {{--                                                <input type="file" style="display: none" data-id="1"--}}
                                        {{--                                                       id="select-green_screen-1" accept="image/x-png"/>--}}
                                        {{--                                                <button type="button"--}}
                                        {{--                                                        class="col-12 btn btn-success image-select-button"--}}
                                        {{--                                                        data-id="1">--}}
                                        {{--                                                    <i class="fa fa-camera" aria-hidden="true"></i>--}}
                                        {{--                                                    Upload my own design--}}
                                        {{--                                                </button>--}}
                                        {{--                                            </fieldset>--}}
                                        {{--                                        </div>--}}

                                        {{--                                        <!------------------------------Green screen background 2------------------------------------------>--}}
                                        {{--                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 green_screen2 hide">--}}
                                        {{--                                            <input id="green_screen2" type="hidden" name="green_screen2"/>--}}
                                        {{--                                            <fieldset--}}
                                        {{--                                                style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">--}}
                                        {{--                                                <div class="col-12">--}}
                                        {{--                                                    Green screen background 2--}}
                                        {{--                                                    <i class="fa fa-info-circle _tooltip">--}}
                                        {{--                                                    <span class="tooltiptext">--}}
                                        {{--                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.--}}
                                        {{--                                                    </span>--}}
                                        {{--                                                    </i>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <img class="col-12 green_screen-image-2" src="" alt="">--}}
                                        {{--                                                <input type="file" style="display: none" data-id="2"--}}
                                        {{--                                                       id="select-green_screen-2" accept="image/x-png"/>--}}
                                        {{--                                                <button type="button"--}}
                                        {{--                                                        class="col-12 btn btn-success image-select-button"--}}
                                        {{--                                                        data-id="2">--}}
                                        {{--                                                    <i class="fa fa-camera" aria-hidden="true"></i>--}}
                                        {{--                                                    Upload my own design--}}
                                        {{--                                                </button>--}}
                                        {{--                                            </fieldset>--}}
                                        {{--                                        </div>--}}

                                        {{--                                        <!------------------------------Green screen background 3------------------------------------------>--}}
                                        {{--                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 green_screen3 hide">--}}
                                        {{--                                            <input id="green_screen3" type="hidden" name="green_screen3"/>--}}
                                        {{--                                            <fieldset--}}
                                        {{--                                                style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">--}}
                                        {{--                                                <div class="col-12">--}}
                                        {{--                                                    Green screen background 3--}}
                                        {{--                                                    <i class="fa fa-info-circle _tooltip">--}}
                                        {{--                                                    <span class="tooltiptext">--}}
                                        {{--                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 Mb.--}}
                                        {{--                                                    </span>--}}
                                        {{--                                                    </i>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <img class="col-12 green_screen-image-3" src="" alt="">--}}
                                        {{--                                                <input type="file" style="display: none" data-id="3"--}}
                                        {{--                                                       id="select-green_screen-3" accept="image/x-png"/>--}}
                                        {{--                                                <button type="button"--}}
                                        {{--                                                        class="col-12 btn btn-success image-select-button"--}}
                                        {{--                                                        data-id="3">--}}
                                        {{--                                                    <i class="fa fa-camera" aria-hidden="true"></i>--}}
                                        {{--                                                    Upload my own design--}}
                                        {{--                                                </button>--}}
                                        {{--                                            </fieldset>--}}
                                        {{--                                        </div>--}}

                                        {{--                                        <!------------------------------Green screen background 4------------------------------------------>--}}
                                        {{--                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 green_screen4 hide">--}}
                                        {{--                                            <input id="green_screen4" type="hidden" name="green_screen4"/>--}}
                                        {{--                                            <fieldset--}}
                                        {{--                                                style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">--}}
                                        {{--                                                <div class="col-12">--}}
                                        {{--                                                    Green screen background 4--}}
                                        {{--                                                    <i class="fa fa-info-circle _tooltip">--}}
                                        {{--                                                    <span class="tooltiptext">--}}
                                        {{--                                                        You can upload PNG file in the same orientation as the photo. Max size: 2 MB.--}}
                                        {{--                                                    </span>--}}
                                        {{--                                                    </i>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <img class="col-12 green_screen-image-4" src="" alt="">--}}
                                        {{--                                                <input type="file" style="display: none" data-id="4"--}}
                                        {{--                                                       id="select-green_screen-4" accept="image/x-png"/>--}}
                                        {{--                                                <button type="button"--}}
                                        {{--                                                        class="col-12 btn btn-success image-select-button"--}}
                                        {{--                                                        data-id="4">--}}
                                        {{--                                                    <i class="fa fa-camera" aria-hidden="true"></i>--}}
                                        {{--                                                    Upload my own design--}}
                                        {{--                                                </button>--}}
                                        {{--                                            </fieldset>--}}
                                        {{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-sharing" role="tabpanel"
                             aria-labelledby="nav-sharing-tab">
                            <div class="row">
                                <!------------------------------Share------------------------------------------>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                    <img src="{{ asset('images/user/share.png') }}" width="60px" alt="">
                                    @if ($event->share)
                                        <input type="checkbox" id="share" name="share" checked>
                                    @else
                                        <input type="checkbox" id="share" name="share">
                                    @endif
                                    <label for="share">Share</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                           Activate to share by native.
                                        </span>
                                    </i>
                                </div>

                                <!------------------------------Whatsapp------------------------------------------>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                    <img src="{{ asset('images/user/whatsapp.png') }}" width="60px" alt="">
                                    @if ($event->whatsapp)
                                        <input type="checkbox" id="whatsapp" name="whatsapp" checked>
                                    @else
                                        <input type="checkbox" id="whatsapp" name="whatsapp">
                                    @endif
                                    <label for="whatsapp">Whatsapp</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                           Activate to share by Whatsapp.
                                        </span>
                                    </i>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 whatsapp_msg hide">
                                        <label>Message (max. 255 characters)</label>
                                        <input type="text" name="whatsapp_msg" class="form-control"
                                               value="{{ $event->whatsapp_msg }}" maxlength="255">
                                    </div>
                                </div>

                                <!------------------------------SMS------------------------------------------>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                    <img src="{{ asset('images/user/sms.png') }}" width="60px" alt="">
                                    @if ($event->sms)
                                        <input type="checkbox" id="sms" name="sms" checked>
                                    @else
                                        <input type="checkbox" id="sms" name="sms">
                                    @endif
                                    <label for="sms">SMS</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate to share by SMS.
                                        </span>
                                    </i>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 sms_msg hide">
                                        <label>Message (max. 255 characters)</label>
                                        <input type="text" name="sms_msg" class="form-control"
                                               value="{{ $event->sms_msg }}" maxlength="255">
                                    </div>
                                </div>

                                <!------------------------------Email------------------------------------------>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                    <img src="{{ asset('images/user/email.png') }}" width="60px" alt="">
                                    @if ($event->email)
                                        <input type="checkbox" id="email" name="email" checked>
                                    @else
                                        <input type="checkbox" id="email" name="email">
                                    @endif
                                    <label for="email">Email</label>
                                    <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate to share by Email.
                                        </span>
                                    </i>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 email_msg hide">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                                <label>Subject (max. 80 characters)</label>
                                                <input type="text" name="email_subject" class="form-control"
                                                       value="{{ $event->email_subject }}" maxlength="80">
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                                <label for="email_msg">Message (max. 255 characters)</label>
                                                <input type="text" name="email_msg" class="form-control"
                                                       value="{{ $event->email_msg }}" maxlength="255">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-extras" role="tabpanel"
                             aria-labelledby="nav-extras-tab">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <img src="{{ asset('images/user/extras.png') }}" width="60px" alt="">
                                    <label>Extra features</label>
                                </div>

                                <!------------------------------Block menu------------------------------------------>
                                <div class="col-12 mb-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                                            @if ($event->block_menu)
                                                <input type="checkbox" id="block_menu" name="block_menu" checked>
                                            @else
                                                <input type="checkbox" id="block_menu" name="block_menu">
                                            @endif
                                            <label for="block_menu">Block menu</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    You can block the App menu to prevent guests from closing the App. To unlock the App menu you must enter the event code.
                                                </span>
                                            </i>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2 password hide">
                                            <label for="password">Password</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Input password to unblock screen.
                                                </span>
                                            </i>
                                            <input name="password" id="password" class="form-control" type="text"
                                                   maxlength="20" value="{{ $event->password }}">
                                        </div>
                                    </div>
                                </div>

                                <!------------------------------Screen saver------------------------------------------>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                                            @if ($event->screen_saver)
                                                <input type="checkbox" id="screen_saver" name="screen_saver" checked>
                                            @else
                                                <input type="checkbox" id="screen_saver" name="screen_saver">
                                            @endif
                                            <label for="screen_saver">Screen saver</label>
                                            <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate the screen saver.
                                        </span>
                                            </i>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2 screen_saver_time hide">
                                            <label for="screen_saver_time">Seconds</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Select the number of seconds.
                                                </span>
                                            </i>
                                            <input name="screen_saver_time" class="form-control" type="number" min="0"
                                                   value="{{ $event->screen_saver_time }}" step="1" required>
                                        </div>
                                    </div>
                                </div>

                                <!------------------------------Print------------------------------------------>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 mb-2">
                                            <img src="{{ asset('images/user/printer.png') }}" width="60px" alt="">
                                            @if ($event->printer)
                                                <input type="checkbox" id="printer" name="printer" checked>
                                            @else
                                                <input type="checkbox" id="printer" name="printer">
                                            @endif
                                            <label for="printer">Printer</label>
                                            <i class="fa fa-info-circle _tooltip">
                                        <span class="tooltiptext">
                                            Activate to print.
                                        </span>
                                            </i>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 mb-2 printer_ip hide">
                                            <label for="printer_ip">IP address</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Input IP address.
                                                </span>
                                            </i>
                                            <input id="printer_ip" name="printer_ip" class="form-control" type="text"
                                                   minlength="7" maxlength="15" required
                                                   placeholder="xxx.xxx.xxx.xxx" value="{{ $event->printer_ip }}"
                                                   pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$">
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 mb-2 copy_limit hide">
                                            <label for="copy_limit">Copy limit</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Input the limit of copies to print.
                                                </span>
                                            </i>
                                            <input id="copy_limit" name="copy_limit" class="form-control" type="number"
                                                   min="1" max="99" value="{{ $event->copy_limit }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!------------------------------Background------------------------------------------>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                                            @if ($event->background)
                                                <input type="checkbox" id="background" name="background" checked>
                                            @else
                                                <input type="checkbox" id="background" name="background">
                                            @endif
                                            <label for="background">Background</label>
                                            <i class="fa fa-info-circle _tooltip">
                                            <span class="tooltiptext">
                                                Activate the background. You can upload image or video file. Max size: 2 Mb.
                                            </span>
                                            </i>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2 background hide">
                                            <label for="background_type">Type</label>
                                            <i class="fa fa-info-circle _tooltip">
                                                <span class="tooltiptext">
                                                    Select the type of background.
                                                </span>
                                            </i>
                                            <select id="background_type" name="background_type" class="form-control">
                                                @if ($event->background_type == 'Video')
                                                    <option value="Image">Image</option>
                                                    <option value="Video" selected>Video</option>
                                                @else
                                                    <option value="Image" selected>Image</option>
                                                    <option value="Video">Video</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 background hide">
                                            <input id="back_content" type="hidden" name="back_content"
                                                   value="{{ $event->back_content }}"/>
                                            <fieldset
                                                style="border: 2px solid #eeeeee; border-radius: 10px; margin-bottom: 20px; padding:10px;">

                                                <div class="back_content">
                                                    @if ($event->background)
                                                        @if ($event->background_type == 'Image' && $event->back_content !== '')
                                                            <img src="{{ asset('storage/'.$event->back_content) }}"
                                                                 style="width:100%" alt=""/>
                                                        @else
                                                            <video controls style="width: 100%">
                                                                <source
                                                                    src="{{ asset('storage/'.$event->back_content) }}"
                                                                    type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @endif
                                                    @endif
                                                </div>

                                                <input type="file" style="display: none" id="select-back-image"
                                                       accept=".png, .jpg, .jpeg, .gif"/>

                                                <input type="file" style="display: none" id="select-back-video"
                                                       accept=".webm, .mp4, .ogg"/>

                                                <button type="button" id="back-select-button"
                                                        class="col-12 btn btn-success">
                                                    Upload background file
                                                </button>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <button type="submit" onclick="return isValidImageCount()" class="btn btn-success">Save & exit
                </button>
            </div>
        </form>
    </div>


    <!-- Select template modal -->
    <div class="modal fade" id="OverlayModal" tabindex="-1" role="dialog"
         aria-labelledby="overlayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="overlayModalLabel">
                        Templates</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_1.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_1.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_2.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_2.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_3.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_3.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_4.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_4.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_5.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_5.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_6.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_6.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_7.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_7.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_8.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_8.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_9.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_9.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_10.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_10.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/temp_11.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id="" data-url="{{ asset('images/user/templates/landscape/temp_11.png') }}">
                                Use this template
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select print template modal -->
    <div class="modal fade" id="PrintOverlayModal" tabindex="-1" role="dialog"
         aria-labelledby="overlayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="overlayModalLabel">
                        Templates</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_1.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_1.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_2.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_2.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_3.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_3.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_4.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_4.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_5.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_5.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_6.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_6.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_7.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_7.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_8.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_8.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_9.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_9.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_10.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_10.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/print_temp_11.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/print_temp_11.png') }}">
                                Use this template
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select strip template modal -->
    <div class="modal fade" id="StripOverlayModal" tabindex="-1" role="dialog"
         aria-labelledby="overlayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="overlayModalLabel">
                        Templates</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_1.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_1.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_2.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_2.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_3.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_3.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_4.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_4.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_5.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_5.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_6.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_6.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_7.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_7.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_8.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_8.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_9.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_9.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_10.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_10.png') }}">
                                Use this template
                            </button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                            <img style="width:100%; border:1px solid gray;" class="templateImg"
                                 src="{{ asset('images/user/templates/landscape/strip_temp_11.png') }}" alt="">
                            <button type="button" class="col-12 btn btn-success templateBtn" data-dismiss="modal"
                                    data-id=""
                                    data-url="{{ asset('images/user/templates/landscape/strip_temp_11.png') }}">
                                Use this template
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let overlay_count = 1;
        $(function () {
            $('#h_value').on('input', function () {
                $('.h_value').text($(this).val());
            });

            $('#s_value').on('input', function () {
                $('.s_value').text($(this).val());
            });

            $('#b_value').on('input', function () {
                $('.b_value').text($(this).val());
            });

            $('.style.btn-success').addClass('btn-primary');
            $('.style.btn-success').removeClass('btn-success');
            $('.style[data-button={{ $event->style }}]').removeClass('btn-primary');
            $('.style[data-button={{ $event->style }}]').addClass('btn-success');
            $('.four_six').removeClass('hide');
            $('.gif').removeClass('hide');

            switch (parseInt('{{ $event->style }}')) {
                case 1:
                    overlay_count = 1;
                    $('.four_six').addClass('hide');
                    $('.gif').addClass('hide');
                    $('.templates').addClass('hide');
                    $('.print-overlay').attr('data-target', '#OverlayModal');
                    break;
                case 2:
                    overlay_count = 4;
                    $('.print-overlay').attr('data-target', '#PrintOverlayModal');
                    break;
                case 3:
                    if ('{{ $event->orientation }}' === 'Portrait')
                        overlay_count = 3;
                    else
                        overlay_count = 4;
                    $('.print-overlay').attr('data-target', '#StripOverlayModal');
                    break;
            }

            if ('{{ $event->orientation }}' === 'Portrait') {
                $("button[data-button=3]").text("3 photos strip + GIF");
                $(".style_1").attr('src', "{{ asset('images/user/portrait_1.png') }}");
                $(".style_2").attr('src', "{{ asset('images/user/portrait_4_gif.png') }}");
                $(".style_3").attr('src', "{{ asset('images/user/portrait_3_strip_gif.png') }}");
                $(".style_video").attr('src', "{{ asset('images/user/portrait_video.png') }}");
                $(".overlay_image").attr('src', "{{ asset('images/user/portrait_overlay.png') }}");
                $(".gif_image").attr('src', "{{ asset('images/user/templates/portrait/gif.gif') }}");

                let imgs = $('.templateImg');
                for (let img of imgs) {
                    let src = $(img).attr('src');
                    $(img).attr('src', src.replace('landscape', 'portrait'));
                }

                let btns = $('.templateBtn');
                for (let btn of btns) {
                    let url = $(btn).attr('data-url');
                    $(btn).attr('data-url', url.replace('landscape', 'portrait'));
                }
            } else {
                $("button[data-button=3]").text("4 photos strip + GIF");
                $(".style_1").attr('src', "{{ asset('images/user/landscape_1.png') }}");
                $(".style_2").attr('src', "{{ asset('images/user/landscape_4_gif.png') }}");
                $(".style_3").attr('src', "{{ asset('images/user/landscape_4_strip_gif.png') }}");
                $(".style_video").attr('src', "{{ asset('images/user/landscape_video.png') }}");
                $(".overlay_image").attr('src', "{{ asset('images/user/landscape_overlay.png') }}");
                $(".gif_image").attr('src', "{{ asset('images/user/templates/landscape/gif.gif') }}");

                let imgs = $('.templateImg');
                for (let img of imgs) {
                    let src = $(img).attr('src');
                    $(img).attr('src', src.replace('portrait', 'landscape'));
                }

                let btns = $('.templateBtn');
                for (let btn of btns) {
                    let url = $(btn).attr('data-url');
                    $(btn).attr('data-url', url.replace('portrait', 'landscape'));
                }
            }

            if ('{{ $event->countdown }}' === '1') {
                $('.countdown_time').removeClass('hide');
            }

            if ('{{ $event->overlay }}' === '1') {
                $('.print-template').removeClass('hide');
            }

            if ('{{ $event->gif }}' === '0')
                $('.templates').addClass('hide');

            if ('{{ $event->gif_animate }}' === '1') {
                $('.overlay2').removeClass('hide');
                $('.overlay3').removeClass('hide');
                if (parseInt('{{ $event->style }}') !== 3 || '{{ $event->orientation }}' === 'Landscape') {
                    $('.overlay4').removeClass('hide');
                }
            }

            if ('{{ $event->boomerang }}' === '0')
                $('.use_overlay').addClass('hide');

            if ('{{ $event->green_screen }}' === '1') {
                $('.green_screen-template').removeClass('hide');
            }

            if ('{{ $event->whatsapp }}' === '1') {
                $('.whatsapp_msg').removeClass('hide');
            }

            if ('{{ $event->sms }}' === '1') {
                $('.sms_msg').removeClass('hide');
            }

            if ('{{ $event->email }}' === '1') {
                $('.email_msg').removeClass('hide');
            }

            if ('{{ $event->block_menu }}' === '1') {
                $('.password').removeClass('hide');
            }

            if ('{{ $event->screen_saver }}' === '1') {
                $('.screen_saver_time').removeClass('hide');
            }

            if ('{{ $event->printer }}' === '1') {
                $('.printer_ip').removeClass('hide');
                $('.copy_limit').removeClass('hide');
            }

            if ('{{ $event->background }}' === '1') {
                $('.background').removeClass('hide');
            }

            if ('{{ \Illuminate\Support\Facades\Auth::guard('user')->user()->type }}' === 'Business') {
                $('#nav-sharing-tab').removeClass('hide');
                $('#nav-extras-tab').removeClass('hide');
            }
        });

        $('.style').on('click', function () {
            if (!$(this).hasClass('btn-success')) {
                $('.style.btn-success').addClass('btn-primary');
                $('.style.btn-success').removeClass('btn-success');
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-success');
                $('#style').val($(this).attr('data-button'));
                $('.gif').removeClass('hide');

                for (let i = 0; i <= 4; i++) {
                    if (i > 0)
                        $(".delete-overlay[data-id=" + i + "]").addClass('hide');
                    $('.overlay-image-' + i).attr('src', '');
                    $('#overlay' + i).val('');
                    $('input[type=file]').val('');
                }

                switch (parseInt($(this).attr('data-button'))) {
                    case 1:
                        overlay_count = 1;
                        $('.gif').addClass('hide');
                        $('.templates').addClass('hide');
                        $('.print-overlay').attr('data-target', '#OverlayModal');
                        break;
                    case 2:
                        overlay_count = 4;
                        $('.print-overlay').attr('data-target', '#PrintOverlayModal');
                        break;
                    case 3:
                        if ($('.orientation').val() === 'Portrait')
                            overlay_count = 3;
                        else
                            overlay_count = 4;
                        $('.print-overlay').attr('data-target', '#StripOverlayModal');
                        break;
                }
                if ($('#gif_animate').is(':checked')) {
                    for (let i = overlay_count + 1; i <= 4; i++)
                        $('.overlay' + i).addClass('hide');
                    for (let i = 1; i <= overlay_count; i++)
                        $('.overlay' + i).removeClass('hide');
                } else {
                    for (let i = 2; i <= 4; i++)
                        $('.overlay' + i).addClass('hide');
                    $('.overlay1').removeClass('hide');
                }
            }
        });

        $('.orientation').on('change', function () {
            for (let i = 0; i <= 4; i++) {
                if (i > 0)
                    $(".delete-overlay[data-id=" + i + "]").addClass('hide');
                $('.overlay-image-' + i).attr('src', '');
                $('#overlay' + i).val('');
                $('input[type=file]').val('');
            }

            if ($(this).val() === 'Portrait') {
                $("button[data-button=3]").text("3 photos strip + GIF");
                $(".style_1").attr('src', "{{ asset('images/user/portrait_1.png') }}");
                $(".style_2").attr('src', "{{ asset('images/user/portrait_4_gif.png') }}");
                $(".style_3").attr('src', "{{ asset('images/user/portrait_3_strip_gif.png') }}");
                $(".style_video").attr('src', "{{ asset('images/user/portrait_video.png') }}");
                $(".overlay_image").attr('src', "{{ asset('images/user/portrait_overlay.png') }}");
                $(".gif_image").attr('src', "{{ asset('images/user/templates/portrait/gif.gif') }}");

                let imgs = $('.templateImg');
                for (let img of imgs) {
                    let src = $(img).attr('src');
                    $(img).attr('src', src.replace('landscape', 'portrait'));
                }

                let btns = $('.templateBtn');
                for (let btn of btns) {
                    let url = $(btn).attr('data-url');
                    $(btn).attr('data-url', url.replace('landscape', 'portrait'));
                }

                if (parseInt($('.style.btn-success').attr('data-button')) === 3) {
                    overlay_count = 3;
                    $('.overlay4').addClass('hide');
                }
            } else {
                $("button[data-button=3]").text("4 photos strip + GIF");
                $(".style_1").attr('src', "{{ asset('images/user/landscape_1.png') }}");
                $(".style_2").attr('src', "{{ asset('images/user/landscape_4_gif.png') }}");
                $(".style_3").attr('src', "{{ asset('images/user/landscape_4_strip_gif.png') }}");
                $(".style_video").attr('src', "{{ asset('images/user/landscape_video.png') }}");
                $(".overlay_image").attr('src', "{{ asset('images/user/landscape_overlay.png') }}");
                $(".gif_image").attr('src', "{{ asset('images/user/templates/landscape/gif.gif') }}");

                let imgs = $('.templateImg');
                for (let img of imgs) {
                    let src = $(img).attr('src');
                    $(img).attr('src', src.replace('portrait', 'landscape'));
                }

                let btns = $('.templateBtn');
                for (let btn of btns) {
                    let url = $(btn).attr('data-url');
                    $(btn).attr('data-url', url.replace('portrait', 'landscape'));
                }

                if (parseInt($('.style.btn-success').attr('data-button')) === 3) {
                    overlay_count = 4;
                    if ($('#gif_animate').is(':checked'))
                        $('.overlay4').removeClass('hide');
                }
            }
        });

        $('#countdown').on('change', function () {
            if ($(this).is(':checked'))
                $('.countdown_time').removeClass('hide');
            else
                $('.countdown_time').addClass('hide');
        });

        $('#gif').on('change', function () {
            if ($(this).is(':checked'))
                $('.templates').removeClass('hide');
            else
                $('.templates').addClass('hide');
        });

        $('#boomerang').on('change', function () {
            if ($(this).is(':checked'))
                $('.use_overlay').removeClass('hide');
            else
                $('.use_overlay').addClass('hide');
        });

        $('#gif_animate').on('change', function () {
            if ($(this).is(':checked')) {
                $('.overlay4').addClass('hide');
                for (let i = 1; i <= overlay_count; i++)
                    $('.overlay' + i).removeClass('hide');
            } else {
                for (let i = 2; i <= 4; i++)
                    $('.overlay' + i).addClass('hide');
                $('.overlay1').removeClass('hide');
            }
        });

        $('#green_screen').on('change', function () {
            if ($(this).is(':checked'))
                $('.green_screen-template').removeClass('hide');
            else
                $('.green_screen-template').addClass('hide');
        });

        $('#whatsapp').on('change', function () {
            if ($(this).is(':checked'))
                $('.whatsapp_msg').removeClass('hide');
            else
                $('.whatsapp_msg').addClass('hide');
        });

        $('#sms').on('change', function () {
            if ($(this).is(':checked'))
                $('.sms_msg').removeClass('hide');
            else
                $('.sms_msg').addClass('hide');
        });

        $('#email').on('change', function () {
            if ($(this).is(':checked'))
                $('.email_msg').removeClass('hide');
            else
                $('.email_msg').addClass('hide');
        });

        $('#block_menu').on('change', function () {
            if ($(this).is(':checked'))
                $('.password').removeClass('hide');
            else
                $('.password').addClass('hide');
        });

        $('#screen_saver').on('change', function () {
            if ($(this).is(':checked'))
                $('.screen_saver_time').removeClass('hide');
            else
                $('.screen_saver_time').addClass('hide');
        });

        $('#printer').on('change', function () {
            if ($(this).is(':checked')) {
                $('.printer_ip').removeClass('hide');
                $('.copy_limit').removeClass('hide');
            } else {
                $('.printer_ip').addClass('hide');
                $('.copy_limit').addClass('hide');
            }
            $('#printer_ip').val('0.0.0.0');
            $('#copy_limit').val('1');
        });

        $('#background').on('change', function () {
            if ($(this).is(':checked'))
                $('.background').removeClass('hide');
            else
                $('.background').addClass('hide');
            $('.back_content').html('');
            $('#back_content').val('');
        });

        $('#background_type').on('change', function () {
            $('#back_content').val('');
            $('.back_content').html('');
        });

        // Preview the selected background
        $("#back-select-button").on('click', function () {
            if ($('#background_type').val() === "Image")
                $('#select-back-image').trigger('click');
            else
                $('#select-back-video').trigger('click');
        });
        $("#select-back-video").on('change', function (evt) {
            let selectedFile = evt.target.files[0];
            if (!selectedFile) {
                $('.back_content').html('');
                $('#back_content').val('');
            } else {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#back_content').val(e.target.result);
                    let video = `<video style='width: 100%' controls><source src="${e.target.result}" type="video/mp4">Your browser does not support the video tag.</video>`;
                    $('.back_content').html(video);
                };
                reader.readAsDataURL(selectedFile);
            }
        });
        $("#select-back-image").on('change', function (evt) {
            let selectedFile = evt.target.files[0];
            if (!selectedFile) {
                $('.back_content').html('');
                $('#back_content').val('');
            } else {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#back_content').val(e.target.result);
                    let img = `<img style='width: 100%' src="${e.target.result}" alt=""/>`;
                    $('.back_content').html(img);
                };
                reader.readAsDataURL(selectedFile);
            }
        });

        // Preview the selected images
        $('.image-select-button').on('click', function () {
            let id = $(this).attr('data-id');
            $('#select-overlay-' + id).trigger('click');
        });
        $('input[type=file]').on("change", function (evt) {
            let id = $(this).attr('data-id');
            let selectedFile = evt.target.files[0];
            if (selectedFile) {
                if (selectedFile.size > 2 * 1024 * 1024)
                    alert("The image size should not exceed 2MB.");
                else if (id != null) {
                    let img = new Image();
                    img.onload = function () {
                        if (this.width < 400 || this.height < 400) {
                            alert("Too low dimension.");
                            return;
                        }
                        console.log(id);
                        if ($('#style').val() === '3' && id === 0) {
                            if (this.width * 2 >= this.height) {
                                alert("Image orientation should be for strip.");
                                return;
                            }
                        }
                        if ($('.orientation').val() === 'Portrait') {
                            if (this.width >= this.height) {
                                alert("Image orientation should be portrait.");
                                return;
                            }
                        } else {
                            if (this.width <= this.height) {
                                alert("Image orientation should be landscape.");
                                return;
                            }
                        }
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            $('.overlay-image-' + id).attr('src', e.target.result);
                            $('#overlay' + id).val(e.target.result);
                            if (id > 0)
                                $(".delete-overlay[data-id=" + id + "]").removeClass('hide');
                        };
                        reader.readAsDataURL(selectedFile);
                    };
                    img.src = window.URL.createObjectURL(selectedFile);
                }
            }
        });

        // Use a template
        $("button[data-target='#OverlayModal']").on('click', function () {
            let id = $(this).attr('data-id');
            $('.templateBtn').attr('data-id', id);
        });
        $('.templateBtn').on('click', function () {
            let id = $(this).attr('data-id');
            let url = $(this).attr('data-url');
            $('.overlay-image-' + id).attr('src', url);
            $('#overlay' + id).val(url);
            if (id > 0)
                $(".delete-overlay[data-id=" + id + "]").removeClass('hide');
        });

        // Delete overlay
        $('.delete-overlay').on('click', function () {
            let id = $(this).attr('data-id');
            $('.overlay-image-' + id).attr('src', '');
            $('#overlay' + id).val('');
            $(this).addClass('hide');
        });

        function isValidImageCount() {
            if ($('#background').is(':checked') && $('#back_content').val() === '') {
                alert('Background should be set up.');
                return false;
            }

            if ($('#overlay0').val() === '') {
                alert('Print Overlay should be set up.');
                return false;
            }

            if (!$('.templates').hasClass('hide')) {
                if ($('#gif_animate').is(':checked')) {
                    for (let i = 0; i <= overlay_count; i++)
                        if ($('#overlay' + i).val() === '') {
                            alert('All overlays should be set up.');
                            return false;
                        }
                }
            }

            if ($('#start_time').val() >= $('#end_time').val()) {
                alert('End time must be later than start time.');
                return false;
            }

            if ($('#block_menu').is(':checked') && $('#password').val() === '') {
                alert('Password should be set up to unblock app.');
                return false;
            }

            return true;
        }
    </script>
@endsection
