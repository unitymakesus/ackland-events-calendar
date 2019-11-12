<?php
namespace FLCacheClear;
class W3cache {

	static function run() {
		if ( function_exists( '\w3tc_pgcache_flush' ) ) {
			\w3tc_pgcache_flush();
		}
	}
}
