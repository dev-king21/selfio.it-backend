@extends('admin.layouts.main')

@section('title')
    {{ __('Add plan') }}
@endsection

@section('content')
    <div class="container-fluid pr-2 pl-2" style="padding: 0 20px!important">
        <div class="card card-default">
            <div class="card-body row">
                <form action="{{ route('admin.plan.add') }}" class="form-inline" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-6 input-group mb-2">
                        <label for="type"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Type') }}</label>
                        <select name="type" class="form-control orientation" required>
                            <option value="Private">Private</option>
                            <option value="Business">Business</option>
                        </select>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="devices"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Devices') }}</label>
                        <input type="number" class="form-control" name="devices"
                               min="1" max="999" placeholder="{{ __('Devices') }}" required autofocus>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="events"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Events') }}</label>
                        <input type="number" class="form-control" name="events"
                               min="1" max="999" placeholder="{{ __('Events') }}" required autofocus>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="months"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Months') }}</label>
                        <input type="number" class="form-control" name="months"
                               min="1" max="99" placeholder="{{ __('Months') }}" required autofocus>
                    </div>
                    <div class="col-md-6 input-group mb-2">
                        <label for="cost"
                               class="input-group-prepend pr-3 w-30 text-right">{{ __('Cost') }}</label>
                        <input type="number" class="form-control" name="cost"
                               min="0.01" step="0.01" placeholder="{{ __('Cost') }}" required autofocus>
                    </div>
                    <div class="col-md-12 input-group justify-content-end">
                        <button type="submit" class="btn btn-primary mb-2">{{ __('Add') }}</button>
                    </div>
                    <div class="loader" style="display: none"></div>
                </form>
            </div>
        </div>
    </div>
@endsection
