<?php
namespace FLCacheClear;
class Cacheenabler {

	static function run() {
		if ( class_exists( '\Cache_Enabler' ) ) {
			\Cache_Enabler::clear_total_cache();
		}
	}
}
