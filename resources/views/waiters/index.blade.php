@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Orders list:  <button class="btn btn-primary btn-create-order">Create new</button></h2>
        <ul id="orders">
        @if (count($orders) > 0)
            @foreach ($orders as $order)
                <li data-order-id="{{ $order->id }}" class="order">
                    <p class="order-status order-status-{{ $order->status }}">{{ $order->status }}</p>
                    <p class="order-name">Dish name:<span>{{ $order->name }}</span></p>
                    <p class="cook_time">{{ $order->cook_time }}</p>
                    <p class="cooking_timer"></p>
                    @if ($order->status == 'done')
                    <button class="btn btn-danger btn-delete-order">Delete</button>
                    @endif
                </li>
            @endforeach
        @endif
        </ul>
    </div>

    <!-- Create Order Modal -->
    <div class="modal fade" id="create-order-modal" tabindex="-1" role="dialog" aria-labelledby="create-order-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="send-email-label">Create new order</h4>
                </div>
                <div class="modal-body">
                    <form id="create-order-form" class="form-horizontal" role="form" method="POST" action="{{ url('/order') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('table_number') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Table number</label>

                            <div class="col-md-6">
                                <input id="table_number" type="text" class="form-control" name="table_number" value="{{ old('table_number') }}" required>

                                @if ($errors->has('table_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('table_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cook_time') ? ' has-error' : '' }}">
                            <label for="cook_time" class="col-md-4 control-label">Cook time (min)</label>

                            <div class="col-md-6">
                                <input id="cook_time" type="text" class="form-control" name="cook_time" value="{{ old('cook_time') }}" required>

                                @if ($errors->has('cook-time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cook_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-primary" id="create-order-confirm">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.1/socket.io.js"></script>
    <script>
        var socket = io('{{ env('APP_URL') }}:3000');
        socket.on("restaurant-channel:App\\Events\\OrderStatusChange", function(message){
            var order = $('[data-order-id="' + message.order.id + '"]');
            order.children('.cooking_timer').remove();
            order.children(".order-status")
                    .removeClass()
                    .addClass("order-status order-status-" + message.order.status)
                    .text(message.order.status);
            if (message.order.status == 'done') {
                order.append('<button class="btn btn-danger btn-delete-order">Delete</button>');
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(".btn-create-order").on("click", function() {
                $("#create-order-modal").modal('show');
            });

            $("#orders").on("click", ".btn-delete-order", function() {
                var order_id = $(this.closest('li')).attr('data-order-id');
                deleteOrder(order_id);
            });

            $("#create-order-form").on("submit", function (e) {
                var formData = {
                    _token: CSRF_TOKEN,
                    'name': $('input[name=name]').val(),
                    'table_number': $('input[name=table_number]').val(),
                    'cook_time': $('input[name=cook_time]').val()
                };

                $.post('{{ url('/order') }}', formData)
                        .done(function(order) {
                            var template = '<li data-order-id="' + order.id + '" class="order">' +
                                    '<p class="order-status order-status-' + order.status + '">' + order.status + '</p>' +
                                    '<p class="order-name">Dish name: <span>' + order.name + '</span></p>' +
                                    '<p class="table-number">Table number: <span>' + order.table_number + '</span></p>' +
                                    '<p class="cook_time">' + order.cook_time + '</p>' +
                                    '<span class="cooking_timer"></span>' +
                                    '</li>';
                            $('#orders').append(template);
                            $("#create-order-modal").modal('hide');
                        });

                e.preventDefault();
            });

            setInterval(updateCookingTime, 1000);

            function deleteOrder(order_id) {
                $.post('{{ url('/delete_order') }}', {
                            _token: CSRF_TOKEN,
                            order_id: order_id
                        })
                        .done(function(data) {
                            console.log(data);
                            $('[data-order-id="' + order_id + '"]').remove();
                        });
            }

            function updateCookingTime() {

                var cook_timers = $(".cook_time");

                if (!cook_timers.length) {
                    return true;
                }

                $(".cook_time").each( function() {
                    var $current_time = Date.now();
                    var $cooking_time = Date.parse($(this).text());
                    var $time_left = $cooking_time - $current_time;
                    var $order_status = $(this).siblings('.order-status');

                    if ($time_left < 0 ||
                        $order_status.text() != 'cooking') {
                        return true;
                    }

                    $(this).next().html(msToTime($time_left));
                });
            }

            function changeStatus() {

            }

            function msToTime(t) {
                var ms = parseInt((t%1000)/100)
                        , s = parseInt((t/1000)%60)
                        , m = parseInt((t/(1000*60))%60)
                        , h = parseInt((t/(1000*60*60))%24);
                return addZero(h) + ":" + addZero(m) + ":" + addZero(s);
            }

            function addZero(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }
        });
    </script>
@endsection