<?php
require_once '../vendor/autoload.php';

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
	return false;
}

$app = new Silex\Application();
$app['debug'] = true;


// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile'	=> 'php://stderr',
));

// Register the Twig templating engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path'	=> __DIR__.'/../views',
));

// Register the Postgres database add-on
$dbopts = parse_url(getenv('DATABASE_URL'));
$app->register(new Herrera\Pdo\PdoServiceProvider(),
	array(
		'pdo.dsn'		=> 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"],
		'pdo.port'		=> $dbopts["port"],
		'pdo.username'	=> $dbopts["user"],
		'pdo.password'	=> $dbopts["pass"]
	)
);

$app->mount('/ublox/settings', new UBlox\SettingsController());
// $app->mount('/ublox/gps', new UBlox\GPSController());
// $app->mount('/ublox/sensors', new UBlox\SensorsController());

$app->run();

?>
