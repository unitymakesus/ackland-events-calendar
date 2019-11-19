<div class="card card--featured">
    @if (has_post_thumbnail($event))
      @php
        $thumbnail_id = get_post_thumbnail_id($event);
        $src = wp_get_attachment_image_src($thumbnail_id, 'medium')[0];
        $src_2x = wp_get_attachment_image_src($thumbnail_id, 'medium_large')[0];
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      @endphp
      <div class="card__image">
        <img
          class="lazyload"
          data-sizes="auto"
          data-src="{{ $src }}"
          data-srcset="{{ $src }} 1x, {{ $src_2x }} 2x"
          data-expand="-10"
          alt="{{ $alt }}"
        />
      </div>
    @endif
    <div class="card__text">
      <h2 class="h5 heading-reset">
        <a href="{!! get_the_permalink($event) !!}">
          {{ $event->post_title }}
        </a>
      </h2>
      <div class="tribe-event-schedule">{!! tribe_events_event_schedule_details($event) !!}</div>
    </div>
  </div>
