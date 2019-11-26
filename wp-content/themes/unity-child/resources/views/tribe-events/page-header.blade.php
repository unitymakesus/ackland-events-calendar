<header class="page-header">
  <div class="container-wide">
    @php tribe_the_notices() @endphp
    <h1 class="page-header__title">{!! App::title() !!}</h1>
    @php
      // Output event schedule details but without recurring info.
      Tribe__Events__Pro__Main::instance()->disable_recurring_info_tooltip();
      echo tribe_events_event_schedule_details(get_the_ID(), '<div class="page-header__schedule">', '</div>');
      do_action('tribe_events_single_event_after_the_content');
    @endphp
    <div class="page-header__venue">{{ tribe_get_venue() }}</div>
  </div>
</header>
