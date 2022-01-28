@extends('admin.layouts.main')

@section('title')
    {{ __('Edit user') }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <div class="card card-default">
            <div class="card-body row">
                <form action="{{ route('admin.user.edit') }}" class="form-inline" method="POST">
                    {{ csrf_field() }}
                    <input type="text" style="display: none" name="id" value="{{$user->id}}">
                    <div class="col-md-6 input-group mb-2">
                        <label for="name"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{$user->name}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="email"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Email') }}</label>
                        <input type="email" class="form-control" name="email" value="{{$user->email}}" readonly>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="type"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Type') }}</label>
                        <select name="type" class="form-control orientation" required>
                            @if ($user->type == "Private")
                                <option value="Private" selected>Private</option>
                                <option value="Business">Business</option>
                            @else
                                <option value="Private">Private</option>
                                <option value="Business" selected>Business</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="max_devices"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Max devices') }}</label>
                        <input type="number" class="form-control" name="max_devices" value="{{$user->max_devices}}"
                               min="1" max="999" placeholder="{{ __('Max devices') }}" required autofocus>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="max_events"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Max events') }}</label>
                        <input type="number" class="form-control" name="max_events" value="{{$user->max_events}}"
                               min="1" max="999" placeholder="{{ __('Max events') }}" required autofocus>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="end_date"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('End date') }}</label>
                        <input name="end_date" id="end_date" type="date" class="form-control"
                               value="{{$user->end_date}}" required>
                    </div>
                    <div class="col-md-12 input-group justify-content-end">
                        <button type="submit" class="btn btn-primary mb-2">{{ __('Update') }}</button>
                    </div>
                    <div class="loader" style="display: none"></div>
                </form>
            </div>
        </div>
    </div>
@endsection
