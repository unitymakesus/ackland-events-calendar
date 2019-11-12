<?php
namespace FLCacheClear;
class Supercache {

	static function run() {
		if ( function_exists( '\wp_cache_clear_cache' ) ) {
			\wp_cache_clear_cache();
		}
	}
}
