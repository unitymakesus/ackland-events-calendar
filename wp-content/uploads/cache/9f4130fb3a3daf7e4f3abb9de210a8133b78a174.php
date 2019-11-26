<?php
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
?>

<header class="page-header">
  <div class="container-wide">
    <h1 class="page-header__title"><?php echo e(__('Calendar', 'sage')); ?></h1>
    <?php echo e(__('Upcoming Events', 'sage')); ?>

    <form id="js-event-search">
      <span class="screen-reader-text">
        <?php echo e(__('Note: Typing a search keyword or using the filters below will auto-reload the search results.', 'sage')); ?>

      </span>
      <div class="input-search">
        <label for="js-filter-search" class="screen-reader-text"><?php echo e(__('Search the calendar', 'sage')); ?></label>
        <input
          id="js-filter-search"
          class="browser-default"
          type="text"
          placeholder="<?php echo e(__('Search the calendar', 'sage')); ?>"
        />
      </div>
    </form>
  </div>
</header>

<section>
  <div class="container-wide">
    <div class="row">
      <div class="col s12 l4">
        <div class="screen-reader-text"><?php echo e(__('Filter Results', 'sage')); ?></div>

        

        <fieldset form="js-event-search" class="fieldset" name="tribe_events_date">
          <input id="datepicker" type="date" />
          <label class="screen-reader-text" for="datepicker"><?php echo e(__('Date')); ?></label>
        </fieldset>

        <?php if($categories): ?>
        <fieldset form="js-event-search" class="fieldset" name="tribe_events_audience" data-fieldset="tribe_events_audience">
          <legend><?php echo e(__('Audience', 'sage')); ?></legend>
          <?php $__currentLoopData = $audiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input
              type="radio"
              name="tribe_events_audience"
              id="<?php echo e($audience->slug); ?>"
              value="<?php echo e($audience->slug); ?>"
              data-filter
            />
            <label class="checklabel" for="<?php echo e($audience->slug); ?>"><?php echo e($audience->name); ?></label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </fieldset>
        <?php endif; ?>

        <?php if($categories): ?>
        <fieldset form="js-event-search" class="fieldset" name="tribe_events_cat" data-fieldset="tribe_events_cat">
          <legend><?php echo e(__('Category', 'sage')); ?></legend>
          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input
              type="checkbox"
              name="tribe_events_cat"
              id="<?php echo e($category->slug); ?>"
              value="<?php echo e($category->slug); ?>"
              data-filter
            />
            <label class="checklabel" for="<?php echo e($category->slug); ?>"><?php echo e($category->name); ?></label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </fieldset>
        <?php endif; ?>

        <div>
          <a role="button" href="#" id="js-reset-filters" class="text-uppercase"><?php echo e(__('Reset Filters', 'sage')); ?></a>
        </div>
      </div>
      <div class="col s12 l8">
        <div id="ajaxLoading" class="is-loading">
          <small><?php echo e(__('Loading', 'sage')); ?></small>
        </div>
        <div
          id="js-posts-wrapper"
          aria-live="polite"
          data-paged="0"
          data-posts_per_page="<?php echo e(get_option('posts_per_page')); ?>"
          data-context="events"
        >
          <div id="js-posts-row">
            <?php
              // posts get loaded in here via WP REST API & ajax
            ?>
          </div>
          <div class="text-center">
            <a role="button" id="js-posts-load-more" class="text-uppercase" href="#" data-finished="true" data-loading-text="<?php echo e(__('Loading...', 'sage')); ?>"><?php echo e(__('More Events', 'sage')); ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
  $featured_events = tribe_get_events([
    'eventDisplay'   => 'custom',
    'start_date'     => 'now',
    'posts_per_page' => 4,
    'featured'       => true,
  ]);
?>

<?php if($featured_events): ?>
<section class="main__featured">
  <div class="container-wide">
    <h2><?php echo e(__('Featured', 'sage')); ?></h2>
    <div class="cards-featured">
      <?php $__currentLoopData = $featured_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('partials.event-card--featured', [
          'event' => $event
        ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>
<?php endif; ?>
