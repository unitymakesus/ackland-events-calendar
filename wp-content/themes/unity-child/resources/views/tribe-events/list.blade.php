@php
  // ¯\_(ツ)_/¯
  $__env = App\sage('blade');

  $audiences = get_terms([
    'taxonomy'   => 'tribe_events_audience',
    'hide_empty' => false,
    'meta_key'   => 'tax_position',
    'orderby'    => 'tax_position',
  ]);

  $categories = get_terms([
    'taxonomy'   => 'tribe_events_cat',
    'hide_empty' => false,
    'order'      => 'ASC',
    'orderby'    => 'name',
  ]);
@endphp

<header class="page-header">
  <div class="container-wide">
    <h1 class="page-header__title">{{ __('Calendar', 'sage') }}</h1>
    {{ __('Upcoming Events', 'sage') }}
    <form id="js-event-search">
      <span class="screen-reader-text">
        {{ __('Note: Typing a search keyword or using the filters below will auto-reload the search results.', 'sage') }}
      </span>
      <div class="input-search">
        <label for="js-filter-search" class="screen-reader-text">{{ __('Search the calendar', 'sage') }}</label>
        <input
          id="js-filter-search"
          class="browser-default"
          type="text"
          placeholder="{{ __('Search the calendar', 'sage') }}"
        />
      </div>
    </form>
  </div>
</header>

<section>
  <div class="container-wide">
    <div class="row">
      <div class="col s12 l4">
        <fieldset form="js-event-search" class="fieldset" name="tribe_events_date">
          <input id="datepicker" type="date" />
          <label class="screen-reader-text" for="datepicker">{{ __('Date') }}</label>
        </fieldset>

        @if ($categories)
        <fieldset form="js-event-search" class="fieldset" name="tribe_events_audience" data-fieldset="tribe_events_audience">
          <legend>{{ __('Audience', 'sage') }}</legend>
          @foreach ($audiences as $audience)
            <input
              type="checkbox"
              name="tribe_events_audience"
              id="{{ $audience->slug }}"
              value="{{ $audience->slug }}"
              data-filter
            />
            <label class="checklabel" for="{{ $audience->slug }}">{{ $audience->name }}</label>
          @endforeach
        </fieldset>
        @endif

        @if ($categories)
        <fieldset form="js-event-search" class="fieldset" name="tribe_events_cat" data-fieldset="tribe_events_cat">
          <legend>{{ __('Category', 'sage') }}</legend>
          @foreach ($categories as $category)
            <input
              type="checkbox"
              name="tribe_events_cat"
              id="{{ $category->slug }}"
              value="{{ $category->slug }}"
              data-filter
            />
            <label class="checklabel" for="{{ $category->slug }}">{{ $category->name }}</label>
          @endforeach
        </fieldset>
        @endif

        <div class="mb3">
          <a role="button" href="#" id="js-reset-filters" class="text-uppercase">{{ __('Reset Filters', 'sage') }}</a>
        </div>
      </div>
      <div class="col s12 l8">
        <div id="ajaxLoading" class="is-loading">
          <small>{{ __('Loading', 'sage') }}</small>
        </div>
        <div
          id="js-posts-wrapper"
          aria-live="polite"
          data-paged="0"
          data-posts_per_page="{{ get_option('posts_per_page') }}"
          data-context="events"
        >
          <div id="js-posts-row">
            @php
              // posts get loaded in here via WP REST API & ajax
            @endphp
          </div>
          <div class="text-center">
            <a role="button" id="js-posts-load-more" class="text-uppercase" href="#" data-finished="true" data-loading-text="{{ __('Loading...', 'sage') }}">{{ __('More Events', 'sage') }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@php
  $featured_events = tribe_get_events([
    'eventDisplay'   => 'custom',
    'start_date'     => 'now',
    'posts_per_page' => 4,
    'featured'       => true,
  ]);
@endphp

@if ($featured_events)
<section class="main__featured">
  <div class="container-wide">
    <h2>{{ __('Featured', 'sage') }}</h2>
    <div class="cards-featured">
      @foreach ($featured_events as $event)
        @include('partials.event-card--featured', [
          'event' => $event
        ])
      @endforeach
    </div>
  </div>
</section>
@endif
