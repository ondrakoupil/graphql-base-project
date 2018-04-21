<?php


use Slim\Container;

/** @var Container $container */
$container['RunID'] = function(Container $container) {
	return substr(md5(time() . rand(1,999999)), 0, 6);
};
