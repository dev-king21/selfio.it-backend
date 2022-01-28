@extends('admin.layouts.main')

@section('title')
    {{ __('Plans') }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <h5 class="mb-3 text-right">
            <a href="{{ route('admin.plan.add') }}"><span class="fa fa-plus pr-1"></span>{{ __('Add') }}</a>
        </h5>
        <table class="table table-striped table-bordered datatable">
            <thead>
            <tr>
                <th>{{ __('Id') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Devices') }}</th>
                <th>{{ __('Events') }}</th>
                <th>{{ __('Months') }}</th>
                <th>{{ __('Cost') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($plans as $index => $plan)
                <tr>
                    <td>{{$plan->id}}</td>
                    <td>{{$plan->type}}</td>
                    <td>{{$plan->devices}}</td>
                    <td>{{$plan->events}}</td>
                    <td>{{$plan->months}}</td>
                    <td>{{$plan->cost}}</td>
                    <td>
                        <a class="btn btn-info"
                           href="{{route('admin.plan.edit')}}?id={{$plan->id}}">{{ __('Update') }}</a>
                        <button type="button" class="btn btn-danger delete-plan-btn"
                                data-id="{{ $plan->id }}"
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
                <th>{{ __('Type') }}</th>
                <th>{{ __('Devices') }}</th>
                <th>{{ __('Events') }}</th>
                <th>{{ __('Months') }}</th>
                <th>{{ __('Cost') }}</th>
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
                        Delete plan</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Do you want to delete this plan?
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

        $('.delete-plan-btn').on('click', function () {
            let id = $(this).attr('data-id');
            $('.delete-form').attr('action', '{{ route('admin.plan.delete') }}?id=' + id);
        });
    </script>
@endsection
