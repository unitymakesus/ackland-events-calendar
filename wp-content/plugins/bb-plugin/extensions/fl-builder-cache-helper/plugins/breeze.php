<?php
namespace FLCacheClear;
class Breeze {

	static function run() {
		if ( class_exists( '\Breeze_PurgeCache' ) ) {
			\Breeze_PurgeCache::breeze_cache_flush();
		}
	}
}
