<?php
/**
 * View: Top Bar
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/top-bar.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */
?>
<div class="tribe-events-c-top-bar tribe-events-header__top-bar">

	<?php $this->template( 'components/top-bar/nav' ); ?>

	<?php $this->template( 'week/top-bar/today' ); ?>

	<?php $this->template( 'week/top-bar/datepicker' ); ?>

	<?php $this->template( 'components/top-bar/actions' ); ?>

</div>
