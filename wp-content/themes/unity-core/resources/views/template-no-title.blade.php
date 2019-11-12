{{--
  Template Name: Page Without Title
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <article class="container" {!! post_class() !!}>
      @include('partials.content-page')
    </article>
  @endwhile
@endsection
