@extends('admin.layouts.main')

@section('title')
    {{ __('Event detail') }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <div class="card card-default">
            <div class="card-body row">
                <form action="{{ route('admin.event.delete') }}" class="form-inline" method="POST">
                    {{ csrf_field() }}
                    <input type="text" style="display: none" name="id" value="{{$event->id}}">
                    <div class="col-md-6 input-group mb-2">
                        <label for="name"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{$event->name}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="code"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Code') }}</label>
                        <input type="text" class="form-control" name="code" value="{{$event->code}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="owner"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Owner') }}</label>
                        <input type="text" class="form-control" name="owner" value="{{\App\Models\User::firstWhere('id', $event->owner_id)->name}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="email"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Email') }}</label>
                        <input type="text" class="form-control" name="email" value="{{\App\Models\User::firstWhere('id', $event->owner_id)->email}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="start_time"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Start time') }}</label>
                        <input type="datetime-local" class="form-control" name="start_time" value="{{$event->start_time}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="end_time"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('End time') }}</label>
                        <input type="datetime-local" class="form-control" name="end_time" value="{{$event->end_time}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="orientation"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Orientation') }}</label>
                        <input type="text" class="form-control" name="orientation" value="{{$event->orientation}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="style"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Style') }}</label>
                        <input type="text" class="form-control" name="style" value="{{\App\Helper\Helper::getStyle($event->orientation, $event->style)}}" readonly>
                    </div>
                    <div class="col-md-12 input-group justify-content-end">
                        <button type="submit" class="btn btn-danger mb-2">{{ __('Delete') }}</button>
                    </div>
                    <div class="loader" style="display: none"></div>
                </form>
            </div>
        </div>
    </div>
@endsection
