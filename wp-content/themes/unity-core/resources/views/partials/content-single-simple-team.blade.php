<article class="container" {!! post_class() !!}>
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
      echo get_field('longer_bio');
    ?>

  </div>
  </div>

  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>
