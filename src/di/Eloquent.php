<?php

use App\AppSettings;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Container;

/** @var Container $container */
$container['Eloquent'] = function(Container $container) {

	$appSettings = $container->get(AppSettings::class);

	$capsule = new Capsule();
	$capsule->addConnection(
		array(
			'driver' => 'mysql',
			'host' => $appSettings->db['host'],
			'database' => $appSettings->db['dbname'],
			'username' => $appSettings->db['user'],
			'password' => $appSettings->db['pass'],
			'charset' => 'utf8mb4',
		)
	);
	$capsule->setAsGlobal();
	$capsule->bootEloquent();

	return $capsule;

};



