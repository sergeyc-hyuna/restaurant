@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Users list:</h2>
        <table id="employees" class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr data-user-id="{{ $user->id }}">
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="td-position">{{ $user->role }}</td>
                    <td class="td-action">
                        @if($user->role == 'trainee')
                        <select class="choose-position" id="sel1">
                            <option disabled selected value> -- select position -- </option>
                            <option value="cook">cook</option>
                            <option value="waiter">waiter</option>
                        </select>
                        @else
                            <button data-user-id="{{ $user->id }}" class="btn btn-xs btn-danger btn-fire-employee">Fire</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(".btn-fire-employee").on("click", function() {
                var data = {
                    _token: CSRF_TOKEN,
                    user_id: $(this).attr('data-user-id')
                };
                $.post('{{ url('/fire_employee') }}', data)
                        .done(function(user) {
                            $('tr[data-user-id="' + user.id + '"]').remove();
                        });
            });

            $(".choose-position").on("change", function() {
                var that = $(this);
                var data = {
                    _token: CSRF_TOKEN,
                    user_id: that.closest('tr').attr('data-user-id'),
                    position: that.val()
                };
                $.post('{{ url('/change_employee_position') }}', data)
                        .done(function(user) {
                            var button = '<button data-user-id="' + user.id + '" class="btn btn-xs btn-danger btn-fire-employee">Fire</button>';
                            that.parent().prev().text(user.role);
                            that.parents('.td-action').html(button);
                        });
            });
        });
    </script>
@endsection