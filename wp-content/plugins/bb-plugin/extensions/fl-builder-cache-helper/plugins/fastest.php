<?php
namespace FLCacheClear;
class Fastest {

	static function run() {
		if ( class_exists( '\WpFastestCache' ) ) {
			global $wp_fastest_cache;
			$wp_fastest_cache->deleteCache( true );
		}
	}
}
