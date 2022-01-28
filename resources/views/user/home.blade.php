@extends('user.layouts.app')

@section('title')
    {{ config('app.name') }}
@endsection

@section('style')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e9322d;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: green;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px green;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Plan</div>
                    <div class="card-body row">

                        @if (\Illuminate\Support\Facades\Session::has('success'))
                            <div class="col-md-12 alert alert-success text-center">
                                {{ \Illuminate\Support\Facades\Session::get('success') }}
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"
                                   style="position: absolute;right: 10px;">×</a>
                            </div>
                        @endif

                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 input-group mb-2">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 input-group">
                                <label for="max_devices"
                                       class="input-group-prepend pr-3 w-30 text-right">Max devices</label>
                                <input type="text" class="form-control" name="max_devices"
                                       value="{{ \Illuminate\Support\Facades\Auth::guard('user')->user()->max_devices }}"
                                       disabled>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 input-group">
                                <label for="max_events"
                                       class="input-group-prepend pr-3 w-30 text-right">Max events</label>
                                <input type="text" class="form-control" name="max_events"
                                       value="{{ \Illuminate\Support\Facades\Auth::guard('user')->user()->max_events }}"
                                       disabled>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 input-group">
                                <label for="end_date"
                                       class="input-group-prepend pr-3 w-30 text-right">End date</label>
                                <input type="text" class="form-control" name="end_date"
                                       value="{{ \Illuminate\Support\Facades\Auth::guard('user')->user()->end_date }}"
                                       disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 input-group mb-auto justify-content-center">
                            <a class="btn btn-success" href="{{ route('user.plan') }}">Change Plan</a>
                        </div>
                    </div>
                </div>
                <div class="card card-default">
                    <div class="card-header">Events</div>
                    <div class="card-body">
                        @if (sizeof($events) < \Illuminate\Support\Facades\Auth::guard('user')->user()->max_events && \Illuminate\Support\Facades\Auth::guard('user')->user()->end_date >= date("Y-m-d"))
                            <h5 class="mb-3 text-right">
                                <a href="{{ route('user.event.add') }}"><span
                                        class="fa fa-plus pr-1"></span>Add</a>
                            </h5>
                        @endif
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Code</th>
                                <th>Created</th>
                                <th>Style</th>
                                <th>Enable</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($events as $index => $event)
                                <tr>
                                    <td>{{ $event->name }}<br>({{ $event->orientation }})</td>
                                    <td>{{ str_replace('T', ' ', $event->start_time) }}</td>
                                    <td>{{ str_replace('T', ' ', $event->end_time) }}</td>
                                    <td>{{ $event->code }}</td>
                                    <td>{{ \App\Helper\Helper::dateFromDatetime($event->created_at) }}</td>
                                    <td>
                                        @switch($event->style)
                                            @case(1)
                                            1 photo 4 x 6"
                                            @break
                                            @case(2)
                                            4 photos + GIF
                                            @break
                                            @case(3)
                                            @if ($event->orientation == 'Landscape')
                                                4 photos strip + GIF
                                            @else
                                                3 photos strip + GIF
                                            @endif
                                            @break
                                            @case(4)
                                            Video booth
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <label class="switch">
                                            @if ($event->enable == 0)
                                                <input type="checkbox" id="{{$event->id}}" class="enable">
                                            @else
                                                <input type="checkbox" id="{{$event->id}}" class="enable" checked>
                                            @endif
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success qr-code-btn"
                                                data-id="{{ $event->id }}"
                                                data-toggle="modal" data-target="#QrCodeModal">
                                            Qr code
                                        </button>
                                        <a class="btn btn-info" href="{{ route('user.event.edit') }}?id={{$event->id}}">Update</a>
                                        <button type="button" class="btn btn-danger delete-event-btn"
                                                data-id="{{ $event->id }}"
                                                data-toggle="modal" data-target="#DeleteModal">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Code</th>
                                <th>Created</th>
                                <th>Style</th>
                                <th>Enable</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR code modal -->
    <div class="modal fade" id="QrCodeModal" tabindex="-1" role="dialog"
         aria-labelledby="QrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="QrCodeModalLabel">
                        Qr code</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    Scan this qr code with your phone
                    @foreach($events as $index => $event)
                        <div class="col-12 mb-2 qr-code hide" data-id="{{ $event->id }}"
                             style="justify-content: center;display: flex">
                            {{ \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->margin(3)->generate($event->code) }}
                        </div>
                    @endforeach
                    <div class="col-12 mb-2" style="justify-content: end;display: inline-grid">
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal" aria-label="Close">
                            Close
                        </button>
                    </div>
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
                    <h5 class="modal-title" id="deleteModalLabel">
                        Delete event</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Do you want to delete this event?
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


    <form class="enable-form hide" action="{{ route('user.event.enable') }}" method="POST">
        @csrf
        <input type="text" id="id" name="id">
        <input type="checkbox" id="enable" name="enable">
    </form>
@endsection

@section('script')
    <script>
        $(function () {
            $('.datatable').DataTable({
                "paging": true,
                "processing": true,
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        });

        $('.enable').on('click', function () {
            $('#id').val($(this).attr('id'));
            $('#enable').prop('checked', $(this).prop('checked'));
            $('.enable-form').submit();
        });

        $('.delete-event-btn').on('click', function () {
            let id = $(this).attr('data-id');
            $('.delete-form').attr('action', '{{ route('user.event.delete') }}?id=' + id);
        });

        $('.qr-code-btn').on('click', function () {
            let id = $(this).attr('data-id');
            $('.qr-code').addClass('hide');
            $(".qr-code[data-id=" + id + "]").removeClass('hide');
        });
    </script>
@endsection
