@extends('layouts.layout')

@section('content')

<div class="contents">
  <liff-body :facilities="{{$facilities}}">
  </liff-body>
</div>

@stop
