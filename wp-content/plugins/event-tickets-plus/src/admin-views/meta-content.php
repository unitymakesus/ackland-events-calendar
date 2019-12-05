<?php $meta_object = tribe( 'tickets-plus.main' )->meta(); ?>
<h4 class="accordion-label"><?php esc_html_e( 'Attendee Information:', 'event-tickets-plus' ); ?></h4>
<p class="tribe-intro">
	<?php esc_html_e( 'Need to collect information from your ticket buyers? Configure your own registration form with the options below.', 'event-tickets-plus' ); ?>
	<a href="<?php echo esc_url( 'https://theeventscalendar.com/knowledgebase/collecting-attendee-information/?utm_source=tec&utm_medium=eventticketsplusapp&utm_term=adminnotice&utm_campaign=evergreen&cid=tec_eventticketsplusapp_adminnotice_evergreen' ) ?>">
		<?php esc_html_e( 'Learn more', 'event-tickets-plus' ) ?>
	</a>.
</p>
<?php include tribe( 'tickets-plus.main' )->plugin_path . 'src/admin-views/meta.php';
