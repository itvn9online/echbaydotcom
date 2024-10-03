<?php

/**
 * 
 */
// define('REDIS_MY_HOST', '%redis_host%');
// define('REDIS_MY_PORT', '%redis_port%');
defined('EB_REDIS_CACHE') || define('EB_REDIS_CACHE', enable_redis);
defined('WGR_CACHE_PREFIX') || define('WGR_CACHE_PREFIX', 'str_cache_prefix');
