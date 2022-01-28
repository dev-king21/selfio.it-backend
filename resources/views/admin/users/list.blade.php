@extends('admin.layouts.main')

@section('title')
    {{ __('Users') }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <table class="table table-striped table-bordered datatable">
            <thead>
            <tr>
                <th>{{ __('Id') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Max devices') }}</th>
                <th>{{ __('Max events') }}</th>
                <th>{{ __('End date') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td><a href="{{route('admin.user.help')}}?id={{$user->id}}">{{$user->name}}</a></td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->type}}</td>
                    <td>{{$user->max_devices}}</td>
                    <td>{{$user->max_events}}</td>
                    <td>{{$user->end_date}}</td>
                    <td>
                        <a class="btn btn-info"
                           href="{{route('admin.user.edit')}}?id={{$user->id}}">{{ __('Update') }}</a>
                        <button type="button" class="btn btn-danger delete-user-btn"
                                data-id="{{ $user->id }}"
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
                <th>{{ __('Email') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Max devices') }}</th>
                <th>{{ __('Max events') }}</th>
                <th>{{ __('End date') }}</th>
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
                        Delete user</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Do you want to delete this user?
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
                "processing": true,
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        });

        $('.delete-user-btn').on('click', function () {
            let id = $(this).attr('data-id');
            $('.delete-form').attr('action', '{{ route('admin.user.delete') }}?id=' + id);
        });
    </script>
@endsection
