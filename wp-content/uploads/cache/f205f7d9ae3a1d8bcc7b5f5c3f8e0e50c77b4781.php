<?php $__env->startSection('content'); ?>
  <div id="tribe-events-pg-template">
    <?php
      tribe_events_before_html();
      tribe_get_view();
      tribe_events_after_html();
    ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>