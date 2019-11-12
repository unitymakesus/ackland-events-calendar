<?php
namespace FLCacheClear;
class Litespeed {

	static function run() {
		if ( class_exists( '\LiteSpeed_Cache_API' ) ) {
			\LiteSpeed_Cache_API::purge_all();
		}
	}
}
