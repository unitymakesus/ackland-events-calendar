<?php

namespace App;

/**
 * Staff list shortcode
 */
add_shortcode('team', function($atts) {
	$people = new \WP_Query([
		'post_type' => 'simple-team',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	]);

	ob_start();

	if ($people->have_posts()) : while ($people->have_posts()) : $people->the_post(); ?>

    <div class="row person">
      <div class="col m4 s6 xs12">
        <?php echo get_the_post_thumbnail($post_id, 'medium-square-thumbnail'); ?>

      </div>
      <div class="col m8 s6 xs12">
        <h2 itemprop="name"><?php the_title(); ?></h2>

        <?php if (!empty($title = get_field('title'))) { ?>
          <div class="title" itemprop="jobTitle"><?php echo $title; ?></div>
        <?php } ?>

				<?php if (!empty($email = get_field('email'))) { ?>
					<div><a itemprop="email" target="_blank" rel="noopener" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
				<?php } ?>

				<?php
					$linkedin = get_field('linkedin');
					$twitter = get_field('twitter_name');
				?>

				<?php if (!empty($linkedin) || !empty($twitter)) { ?>
					<ul class="social-icons">
						<?php if (!empty($linkedin)) { ?><li class="icon-linkedin-team"><a href="<?php echo $linkedin; ?>">LinkedIn</a></li><?php } ?>
						<?php if (!empty($twitter)) { ?><li class="icon-twitter-team"><a href="https://www.twitter.com/<?php echo $twitter; ?>">Twitter</a></li><?php } ?>
					</ul>
				<?php } ?>

        <?php
          if (!empty($short_bio = get_field('short_bio'))) {
            echo $short_bio;
					}
					if (!empty(get_field('longer_bio'))) {
            echo '<p><a href="' . get_permalink() . '">Read more about ' . get_the_title() . '</a></p>';
          }
        ?>
      </div>
    </div>

		<?php
	endwhile; endif; wp_reset_postdata();

	return ob_get_clean();
});
