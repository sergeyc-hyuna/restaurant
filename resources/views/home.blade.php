@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    You are logged in!
                </div>

            </div>
        </div>
    </div>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="links">
                <a href="{{ env('APP_URL') }}/waiter">Waiter tool</a>
                <a href="{{ env('APP_URL') }}/kitchen">Kitchen tool</a>
                <a href="{{ env('APP_URL') }}/manager">Manager tool</a>
            </div>
        </div>
    </div>
</div>
@endsection
