<li data-order-id="{{ $order->id }}" class="order">
    <p class="order-status order-status-{{ $order->status }}">{{ $order->status }}</p>
    <p class="order-name">Dish name:<span>{{ $order->name }}</span></p>
    <p class="table-number">Table number: <span>{{ $order->table_number }}</span></p>
    <p class="waiter-name">Waiter Name: <span>{{ $order->user->name }}</span></p>
    <p class="cook_time">{{ $order->cook_time }}</p>
    <span class="cooking_timer"></span>
    <button class="btn btn-success btn-order-done">Done</button>
</li>