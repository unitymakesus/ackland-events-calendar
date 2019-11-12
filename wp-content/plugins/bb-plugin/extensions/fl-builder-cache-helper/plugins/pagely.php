<?php
namespace FLCacheClear;
class Pagely {

	static function run() {
		if ( class_exists( '\PagelyCachePurge' ) ) {
			$purger = new \PagelyCachePurge();
			$purger->purgeAll();
		}
	}
}
