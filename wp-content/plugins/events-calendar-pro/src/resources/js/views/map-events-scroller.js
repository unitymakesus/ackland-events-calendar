/**
 * Makes sure we have all the required levels on the Tribe Object
 *
 * @since 4.7.7
 *
 * @type   {PlainObject}
 */
tribe.events = tribe.events || {};
tribe.events.views = tribe.events.views || {};

/**
 * Configures Map Events Scroller Object in the Global Tribe variable
 *
 * @since 4.7.7
 *
 * @type  {PlainObject}
 */
tribe.events.views.mapEventsScroller = {};

/**
 * Initializes in a Strict env the code that manages the Event Views
 *
 * @since 4.7.7
 *
 * @param  {PlainObject} $   jQuery
 * @param  {PlainObject} obj tribe.events.views.manager
 *
 * @return {void}
 */
( function( $, obj ) {
	'use strict';
	var $document = $( document );

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since 4.7.7
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		mapEventCardsOuterWrapper: '[data-js="tribe-events-pro-map-event-cards-wrapper"]',
		mapEventCardsWrapper: '[data-js="tribe-events-pro-map-event-cards"]',
		mapEventCardsWrapperClass: '.tribe-events-pro-map__event-cards',
		mapEventCardsWrapperActiveClass: '.tribe-events-pro-map__event-cards--active',
		mapEventCardsPaneClass: '.tribe-events-pro-map__event-cards-scroll-pane',
		mapEventCardsSliderClass: '.tribe-events-pro-map__event-cards-scroll-slider',
	};

	/**
	 * Deinitialize scroller
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 *
	 * @return {void}
	 */
	obj.deinitScroller = function( $container ) {
		$container
			.find( obj.selectors.mapEventCardsOuterWrapper )
			.nanoScroller( { destroy: true } );
	};

	/**
	 * Initialize scroller
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 *
	 * @return {void}
	 */
	obj.initScroller = function( $container ) {
		$container
			.find( obj.selectors.mapEventCardsOuterWrapper )
			.nanoScroller( {
				paneClass: obj.selectors.mapEventCardsPaneClass.className(),
				sliderClass: obj.selectors.mapEventCardsSliderClass.className(),
				contentClass: obj.selectors.mapEventCardsWrapperClass.className(),
				iOSNativeScrolling: true,
				alwaysVisible: false,
				/**
				 * @todo: implement scrollTo when events are available.
				 */
				// scrollTo: $first_event
			} )
			.find( obj.selectors.mapEventCardsWrapper )
			.addClass( obj.selectors.mapEventCardsWrapperActiveClass.className() );
	};

	/**
	 * Deinitialize map events scroller.
	 *
	 * @since 4.7.7
	 *
	 * @param  {Event}       event    event object for 'afterSetup.tribeEvents' event
	 * @param  {jqXHR}       jqXHR    Request object
	 * @param  {PlainObject} settings Settings that this request was made with
	 *
	 * @return {void}
	 */
	obj.deinit = function( event, jqXHR, settings ) {
		var $container = event.data.container;
		obj.deinitScroller( $container );
	};

	/**
	 * Initialize map events scroller.
	 *
	 * @since 4.7.7
	 *
	 * @param {Event}   event      JS event triggered.
	 * @param {integer} index      jQuery.each index param from 'afterSetup.tribeEvents' event.
	 * @param {jQuery}  $container jQuery object of view container.
	 * @param {object}  data       data object passed from 'afterSetup.tribeEvents' event.
	 *
	 * @return {void}
	 */
	obj.init = function( event, index, $container, data ) {
		if ( 'map' !== data.slug ) return;

		obj.initScroller( $container );
		$container.on( 'beforeAjaxSuccess.tribeEvents', { container: $container }, obj.deinit );
	};

	/**
	 * Handles the initialization of the map events scroller when Document is ready
	 *
	 * @since 4.7.7
	 *
	 * @return {void}
	 */
	obj.ready = function() {
		$document.on( 'afterSetup.tribeEvents', tribe.events.views.manager.selectors.container, obj.init );
	};

	// Configure on document ready
	$document.ready( obj.ready );
} )( jQuery, tribe.events.views.mapEventsScroller );
