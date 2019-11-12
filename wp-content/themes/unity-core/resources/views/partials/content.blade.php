<article class="excerpt">
  @if (has_post_thumbnail())
      @php
        $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
          $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      @endphp
        {!! get_the_post_thumbnail( get_the_ID(), 'full', ['alt' => $alt] ) !!}

      @else
        <div class="overlay"></div>
  @endif

  <div class="entry-summary" itemprop="description">
    <h3 class="entry-title" itemprop="name"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h3>
    @include('partials/entry-meta')

    @php the_excerpt(140) @endphp
  </div>
</article>
