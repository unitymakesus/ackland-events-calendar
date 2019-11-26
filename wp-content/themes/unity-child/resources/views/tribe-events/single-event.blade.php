@php
  // ¯\_(ツ)_/¯
  $__env = App\sage('blade');
@endphp

@while (have_posts()) @php the_post() @endphp
  @php
    $event_id = get_the_ID();
    $tags = get_the_tags();
    $sponsors = get_field('event_sponsors');
  @endphp

  <header class="page-header">
    <div class="container-wide">
      @php tribe_the_notices() @endphp
      <h1 class="page-header__title">{!! App::title() !!}</h1>
      @php
        // Output event schedule details but without recurring info.
        Tribe__Events__Pro__Main::instance()->disable_recurring_info_tooltip();
        echo tribe_events_event_schedule_details($event_id, '<div class="page-header__schedule">', '</div>');
        do_action('tribe_events_single_event_after_the_content');
      @endphp
      <div class="page-header__venue">{{ tribe_get_venue() }}</div>
    </div>
  </header>

<section class="main__content py0">
  <div class="grid-flex-test">
    <div class="grid-l">
      @php the_content() @endphp
    </div>
    @if (has_post_thumbnail())
      @php
        $thumbnail_id = get_post_thumbnail_id($event_id);
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      @endphp
      <figure class="grid-r">
        {!! get_the_post_thumbnail($event_id, 'large', ['alt' => $alt] ) !!}
      </figure>
    @endif
  </div>
</section>

<section>
  <div class="container-wide">
    <h2>{{ __('RSVP', 'sage') }}</h2>
    <p>This section is forthcoming.</p>
  </div>
</section>

<section class="main__event-details">
  <div class="container-wide">
    <h2 class="screen-reader-text">{{ __('Additional Event Details', 'sage') }}</h2>
    @if (have_rows('event_sponsors'))
    <h3 class="h2">{{ __('Sponsored By', 'sage') }}</h3>
      @while (have_rows('event_sponsors')) @php the_row() @endphp
        <img src="https://placehold.it/400x250" alt="{{ get_sub_field('name') }}" />
      @endwhile
    @endif

    @if ($tags)
    <h3 class="h2">{{ __('Tags') }}</h3>
    <ul class="list-tags">
      @foreach ($tags as $tag)
        <li class="list-tags__item">
          <span>{{ $tag->name }}</span>
        </li>
      @endforeach
    </ul>
    @endif

    <div>
      <a href="{{ tribe_get_single_ical_link() }}">{{ __('Save Event To Calendar', 'sage') }}</a>
    </div>
  </div>
</section>

  {{--
    Google Map
  --}}
  @if ($map = tribe_get_embedded_map())
  <div class="map">
    <div class="tribe-events-venue-map">
      @php
        do_action('tribe_events_single_meta_map_section_start');
        echo $map;
        do_action('tribe_events_single_meta_map_section_end');
      @endphp
    </div>
  </div>
  @endif
@endwhile

@php
  // Custom pagination because TEC only returns a formatted link by default.
  $adjacent_events = new Tribe__Events__Adjacent_Events;
  $adjacent_events->set_current_event_id($event_id);
  $prev = $adjacent_events->get_closest_event('previous');
  $next = $adjacent_events->get_closest_event('next');
@endphp

@if ($prev || $next)
<section>
  <div class="container-wide">
    <div class="cards-pagination">
      @if ($prev)
        <div class="cards-pagination__prev">
          <span class="h3 text-underline color-primary">{{ __('Previous', 'sage') }}</span>
        </div>
        @include('partials.event-card', [
          'event' => $prev
        ])
      @endif
      @if ($next)
        <div class="cards-pagination__next">
          <span class="h3 text-underline color-primary">{{ __('Next', 'sage') }}</span>
        </div>
        @include('partials.event-card', [
          'event' => $next
        ])
      @endif
    </div>
  </div>
</section>
@endif
