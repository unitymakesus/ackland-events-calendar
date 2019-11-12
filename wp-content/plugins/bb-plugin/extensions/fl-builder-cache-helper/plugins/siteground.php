<?php
namespace FLCacheClear;
class Siteground {

	static function run() {
		if ( function_exists( '\sg_cachepress_purge_cache' ) ) {
			\sg_cachepress_purge_cache();
		}
	}
}
