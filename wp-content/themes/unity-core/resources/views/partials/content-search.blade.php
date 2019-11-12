<article {!! post_class('excerpt') !!}>
  <header>
    <h3 class="excerpt-title"><a href="{{ get_permalink() }}">{{ get_the_title() }}</a></h3>
    @if (get_post_type() === 'post')
      @include('partials/entry-meta')
    @endif
  </header>
  <div class="entry-summary">
    @php the_excerpt() @endphp
  </div>
</article>
