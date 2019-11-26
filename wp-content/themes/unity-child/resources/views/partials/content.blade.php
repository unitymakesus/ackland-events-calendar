<section class="main__content py0">
  <div class="grid-flex-test">
    <div class="grid-l">
      @php the_content() @endphp
    </div>
    @if (has_post_thumbnail())
      @php
        $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      @endphp
      <figure class="grid-r">
        {!! get_the_post_thumbnail( get_the_ID(), 'large', ['alt' => $alt] ) !!}
      </figure>
    @endif
  </div>
</section>
