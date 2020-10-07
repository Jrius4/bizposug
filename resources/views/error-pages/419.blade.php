@extends('error-pages.error')
@section('content')
    <div class="title">
        <h4>session is expired</h4>
    </div>
    <p>{{$error}}</p>
@endsection
