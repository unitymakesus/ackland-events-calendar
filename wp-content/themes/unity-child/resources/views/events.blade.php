{{--
  Template Name: Events
--}}

@extends('layouts.app')

@section('content')
  <div id="tribe-events-pg-template">
    @php
      tribe_events_before_html();
      tribe_get_view();
      tribe_events_after_html();
    @endphp
  </div>
@endsection
