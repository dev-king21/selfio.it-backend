@extends('user.layouts.app')

@section('title')
    {{ config('app.name').' | '.__('Plan') }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Plans</div>

                    @if (Session::has('error'))
                        <div class="col-md-12 alert alert-danger text-center">
                            {{ Session::get('error') }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"
                               style="position: absolute;right: 10px;">×</a>
                        </div>
                    @endif

                    <div class="card-body row justify-content-center">
                        @foreach($plans as $index => $plan)
                            <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto pb-2">
                                <div class="card card-default" style="width: 200px">
                                    <div class="card-body" style="display: flex; flex-direction: column">
                                        <label><h5>{{$plan->devices}} devices</h5></label>
                                        <label><h5>{{$plan->events}} events</h5></label>
                                        <label><h5>{{$plan->months}} months</h5></label>
                                        <label><h5>€{{$plan->cost}}</h5></label>
                                        <button type="button" class="btn btn-primary btn-block choose-events-btn"
                                                data-id="{{ $plan->id }}" data-count="{{$plan->events}}"
                                                data-toggle="modal" data-target="#ChooseEventsModal">
                                            Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Choose events modal -->
    <div class="modal fade" id="ChooseEventsModal" tabindex="-1" role="dialog"
         aria-labelledby="eventsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventsModalLabel">
                        Choose events</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    You can select x original events at most.
                </div>
                <div class="modal-footer">
                    <form class="col-12" action="{{ route('user.plan_by_paypal') }}" method="POST">
                        @csrf
                        <input type="text" style="display: none" name="id" class="plan_id">
                        <input type="text" style="display: none" name="events" class="events">
                        <div class="row">
                            @foreach($events as $index => $event)
                                <div class="col-auto">
                                    <input type="checkbox" class="selected_event" value="{{$event->code}}"
                                           onclick="return ValidateSelection();">{{$event->name}}<br>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-success">
                            Confirm
                        </button>
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let count = 0;
        $('.choose-events-btn').on('click', function () {
            let id = $(this).attr('data-id');
            count = $(this).attr('data-count');
            $('.modal-body').html('You can select ' + count + ' original events at most.');
            $('.plan_id').val(id);
            $('.events').val('');
        });

        /**
         * @return {boolean}
         */
        function ValidateSelection() {
            let checkboxes = $(".selected_event");
            let numberOfCheckedItems = 0;
            let events = '';
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    numberOfCheckedItems++;
                    if (events === '')
                        events += checkboxes[i].getAttribute('value');
                    else
                        events += ',' + checkboxes[i].getAttribute('value');
                }
            }
            $('.events').val(events);
            if (numberOfCheckedItems > count) {
                alert("You can't select more than " + count + " events!");
                return false;
            }
            return true;
        }
    </script>
@endsection
