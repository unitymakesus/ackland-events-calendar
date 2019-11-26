<div class="card card--featured">
    <?php if(has_post_thumbnail($event)): ?>
      <?php
        $thumbnail_id = get_post_thumbnail_id($event);
        $src = wp_get_attachment_image_src($thumbnail_id, 'medium')[0];
        $src_2x = wp_get_attachment_image_src($thumbnail_id, 'medium_large')[0];
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      ?>
      <div class="card__image">
        <img
          class="lazyload"
          data-sizes="auto"
          data-src="<?php echo e($src); ?>"
          data-srcset="<?php echo e($src); ?> 1x, <?php echo e($src_2x); ?> 2x"
          data-expand="-10"
          alt="<?php echo e($alt); ?>"
        />
      </div>
    <?php endif; ?>
    <div class="card__text">
      <h2 class="h5 heading-reset">
        <a href="<?php echo get_the_permalink($event); ?>">
          <?php echo e($event->post_title); ?>

        </a>
      </h2>
      <div class="tribe-event-schedule"><?php echo tribe_events_event_schedule_details($event); ?></div>
    </div>
  </div>
