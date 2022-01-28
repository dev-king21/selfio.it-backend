@extends('admin.layouts.main')

@section('title')
    {{ __('Events') }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <table class="table table-striped table-bordered datatable">
            <thead>
            <tr>
                <th>{{ __('Id') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Code') }}</th>
                <th>{{ __('Owner') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Orientation') }}</th>
                <th>{{ __('Start time') }}</th>
                <th>{{ __('End time') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($events as $index => $event)
                <tr>
                    <td>{{$event->id}}</td>
                    <td>{{$event->name}}</td>
                    <td>{{$event->code}}</td>
                    <td>{{\App\Models\User::firstWhere('id', $event->owner_id)->name}}</td>
                    <td>{{\App\Models\User::firstWhere('id', $event->owner_id)->email}}</td>
                    <td>{{$event->orientation}}</td>
                    <td>{{str_replace('T', ' ', $event->start_time)}}</td>
                    <td>{{str_replace('T', ' ', $event->end_time)}}</td>
                    <td>
{{--                        <a class="btn btn-info"--}}
{{--                           href="{{route('admin.event.detail')}}?id={{$event->id}}">{{ __('Detail') }}</a>--}}
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
                <th>{{ __('Id') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Code') }}</th>
                <th>{{ __('Owner') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Orientation') }}</th>
                <th>{{ __('Start time') }}</th>
                <th>{{ __('End time') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </tfoot>
        </table>
        <div class="loader" style="display: none"></div>
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
@endsection

@section('script')
    <script>
        $(function () {
            $('.datatable').DataTable({
                "paging": true,
                "processing" : true,
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
            });
        });

        $('.delete-event-btn').on('click', function () {
            let id = $(this).attr('data-id');
            $('.delete-form').attr('action', '{{ route('admin.event.delete') }}?id=' + id);
        });
    </script>
@endsection
