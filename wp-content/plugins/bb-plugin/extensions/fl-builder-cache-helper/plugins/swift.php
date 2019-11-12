<?php
namespace FLCacheClear;
class Swift {

	static function run() {
		if ( class_exists( '\Swift_Performance_Cache' ) ) {
			\Swift_Performance_Cache::clear_all_cache();
		}
	}
}
