@extends('layouts.app')

@section('content')
    <div class="container">
            <ul id="orders">
                @if (count($orders) > 0)
                    @foreach ($orders as $order)
                        @include('order', $order)
                    @endforeach
                @endif
            </ul>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.1/socket.io.js"></script>
    <script>
        var socket = io('{{ env('APP_URL') }}:3000');
        socket.on("restaurant-channel:App\\Events\\NewOrder", function(message){
            var order = message.order;
            var user = message.user;

            var template = '<li data-order-id="' + order.id + '" class="order">' +
                                '<p class="order-status order-status-' + order.status + '">' + order.status + '</p>' +
                                '<p class="order-name">Dish name: <span>' + order.name + '</span></p>' +
                                '<p class="table-number">Table number: <span>' + order.table_number + '</span></p>' +
                                '<p class="waiter-name">Waiter Name: <span>' + user.name + '</span></p>' +
                                '<p class="cook_time">' + order.cook_time + '</p>' +
                                '<span class="cooking_timer"></span>' +
                                '<button class="btn btn-success btn-order-done">Done</button>' +
                            '</li>';
            $('#orders').append(template);
        });
    </script>
    <script>
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(".btn-create-order").on("click", function() {
                $("#create-order-modal").modal('show');
            });

            $("#orders").on("click", ".btn-order-done", function() {
                var order_id = $(this.closest('li')).attr('data-order-id');
                changeStatus(order_id, 'done');
            });

            setInterval(updateCookingTime, 1000);

            function updateCookingTime() {
                $(".cook_time").each( function() {
                    var $current_time = Date.now();
                    var $cooking_time = Date.parse($(this).text());
                    var $time_left = $cooking_time - $current_time;
                    if ($time_left < 0) {
                        var $order_status = $(this).siblings('.order-status');

                        if ($order_status.text() != 'cooking' ) {
                            return true;
                        }

                        var order_id = $(this.closest('li')).attr('data-order-id');
                        changeStatus(order_id, 'delayed');

                        return true;
                    }

                    $(this).next().html(msToTime($time_left));
                });
            }

            function changeStatus(order_id, order_status) {
                $.post('{{ url('/change_status') }}', {
                            _token: CSRF_TOKEN,
                            order_id: order_id,
                            order_status: order_status
                        })
                        .done(function(order) {
                            if (order.status == 'done') {
                                $('[data-order-id="' + order_id + '"]').remove();
                            }

                            if (order.status == 'delayed') {
                                var order = $('[data-order-id="' + order_id + '"]');
                                order.children('.cooking_timer').remove();
                                order.children(".order-status")
                                        .removeClass()
                                        .addClass("order-status order-status-" + order_status)
                                        .text(order_status);
                            }
                        });
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