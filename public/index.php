<?php

use App\Middleware\LazyCorsMiddleware;
use Nette\Neon\Neon;
use OndraKoupil\Tools\Arrays;
use Slim\App;
use Slim\Container;

if (preg_match('~^/api/~', $_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 4);
}

include '../vendor/autoload.php';

define('PATH_SRC', __DIR__ . '/../src');

$settings = Neon::decode(file_get_contents(PATH_SRC . '/config.neon'));
$settingsEnv = Neon::decode(file_get_contents(PATH_SRC . '/environment.neon'));

$settings = Arrays::arrayize($settingsEnv) + Arrays::arrayize($settings);

if ($settings['displayErrorDetails']) {
	error_reporting(E_ALL);
} else {
	error_reporting(0);
}

$app = new App(
	[
		'settings' => $settings
	]
);

// Dependency injection

/** @var Container $container */
$container = $app->getContainer();
$deps = glob(PATH_SRC . '/di/*.php');
foreach ($deps as $dep) {
	include $dep;
}

// Middlewares

$app->add(
	new LazyCorsMiddleware()
);


// Routes
$routes = glob(PATH_SRC . '/routes/*.php');

foreach ($routes as $r) {
	include $r;
}


$app->run();

