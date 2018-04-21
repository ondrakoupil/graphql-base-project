<?php

use App\AppSettings;
use Slim\Container;

/** @var Container $container */
$container[AppSettings::class] = function(Container $container) {
	$s = $container['settings'];
	$as = AppSettings::load($s);
	return $as;
};
