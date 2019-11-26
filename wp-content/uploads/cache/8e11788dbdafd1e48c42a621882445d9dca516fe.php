<!doctype html>
<?php
  $text_size = $_COOKIE['data_text_size'] ?? '';
  $contrast = $_COOKIE['data_contrast'] ?? '';
  $simple_fonts = get_theme_mod('theme_fonts');
  $simple_color = get_theme_mod('theme_color');
  $button_font = get_theme_mod('button_font');
?>
<html <?php echo language_attributes(); ?> data-text-size="<?php echo e($text_size); ?>" data-contrast="<?php echo e($contrast); ?>">
  <?php echo $__env->make('partials.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <body <?php echo body_class(); ?> data-font="<?php echo e($simple_fonts); ?>" data-color="<?php echo e($simple_color); ?>" data-buttons="<?php echo e($button_font); ?>">
    <a href="#content" class="screen-reader-text">Skip to content</a>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php echo __('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>

      </div>
    <![endif]-->
    <?php do_action('get_header') ?>
    <?php $logo_align = get_theme_mod( 'header_logo_align' ) ?>
    <?php if($logo_align == 'inline-left'): ?>
      <?php echo $__env->make('partials.header-inline', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php else: ?>
      <?php echo $__env->make('partials.header-float', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <div id="content" class="content" role="document">
      <div class="wrap">
        <?php if(App\display_sidebar()): ?>
          <aside id="aside" class="sidebar" role="complementary">
            <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          </aside>
        <?php endif; ?>
        <main role="main" class="main">
          <?php echo $__env->yieldContent('content'); ?>
        </main>
      </div>
    </div>
    <?php do_action('get_footer') ?>
    <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php wp_footer() ?>
  </body>
</html>
