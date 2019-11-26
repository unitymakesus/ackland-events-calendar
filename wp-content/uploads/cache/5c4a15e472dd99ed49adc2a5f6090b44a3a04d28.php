<?php
  // ¯\_(ツ)_/¯
  $__env = App\sage('blade');
?>

<?php while(have_posts()): ?> <?php the_post() ?>
  <?php
    $event_id = get_the_ID();
    $tags = get_the_tags();
    $sponsors = get_field('event_sponsors');
  ?>

  <header class="page-header">
    <div class="container-wide">
      <?php tribe_the_notices() ?>
      <h1 class="page-header__title"><?php echo App::title(); ?></h1>
      <?php
        // Output event schedule details but without recurring info.
        Tribe__Events__Pro__Main::instance()->disable_recurring_info_tooltip();
        echo tribe_events_event_schedule_details($event_id, '<div class="page-header__schedule">', '</div>');
        do_action('tribe_events_single_event_after_the_content');
      ?>
      <div class="page-header__venue"><?php echo e(tribe_get_venue()); ?></div>
    </div>
  </header>

<section class="main__content py0">
  <div class="grid-flex-test">
    <div class="grid-l">
      <?php the_content() ?>
    </div>
    <?php if(has_post_thumbnail()): ?>
      <?php
        $thumbnail_id = get_post_thumbnail_id($event_id);
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      ?>
      <figure class="grid-r">
        <?php echo get_the_post_thumbnail($event_id, 'large', ['alt' => $alt] ); ?>

      </figure>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container-wide">
    <h2><?php echo e(__('RSVP', 'sage')); ?></h2>
    <p>This section is forthcoming.</p>
  </div>
</section>

<section class="main__event-details">
  <div class="container-wide">
    <h2 class="screen-reader-text"><?php echo e(__('Additional Event Details', 'sage')); ?></h2>
    <?php if(have_rows('event_sponsors')): ?>
    <h3 class="h2"><?php echo e(__('Sponsored By', 'sage')); ?></h3>
      <?php while(have_rows('event_sponsors')): ?> <?php the_row() ?>
        <img src="https://placehold.it/400x250" alt="<?php echo e(get_sub_field('name')); ?>" />
      <?php endwhile; ?>
    <?php endif; ?>

    <?php if($tags): ?>
    <h3 class="h2"><?php echo e(__('Tags')); ?></h3>
    <ul class="list-tags">
      <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="list-tags__item">
          <span><?php echo e($tag->name); ?></span>
        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <?php endif; ?>

    <div>
      <a href="<?php echo e(tribe_get_single_ical_link()); ?>"><?php echo e(__('Save Event To Calendar', 'sage')); ?></a>
    </div>
  </div>
</section>

  
  <?php if($map = tribe_get_embedded_map()): ?>
  <div class="map">
    <div class="tribe-events-venue-map">
      <?php
        do_action('tribe_events_single_meta_map_section_start');
        echo $map;
        do_action('tribe_events_single_meta_map_section_end');
      ?>
    </div>
  </div>
  <?php endif; ?>
<?php endwhile; ?>

<?php
  // Custom pagination because TEC only returns a formatted link by default.
  $adjacent_events = new Tribe__Events__Adjacent_Events;
  $adjacent_events->set_current_event_id($event_id);
  $prev = $adjacent_events->get_closest_event('previous');
  $next = $adjacent_events->get_closest_event('next');
?>

<?php if($prev || $next): ?>
<section>
  <div class="container-wide">
    <div class="cards-pagination">
      <?php if($prev): ?>
        <div class="cards-pagination__prev">
          <span class="h3 text-underline color-primary"><?php echo e(__('Previous', 'sage')); ?></span>
        </div>
        <?php echo $__env->make('partials.event-card', [
          'event' => $prev
        ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <?php endif; ?>
      <?php if($next): ?>
        <div class="cards-pagination__next">
          <span class="h3 text-underline color-primary"><?php echo e(__('Next', 'sage')); ?></span>
        </div>
        <?php echo $__env->make('partials.event-card', [
          'event' => $next
        ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
