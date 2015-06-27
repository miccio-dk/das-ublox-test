<?php

require('../vendor/autoload.php');

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

$settings = array(
	1 => array(
		'log_interval_s'	=> '10',
		'sim_pin'			=> '1234',
		'sim_apn'			=> 'something.dk',
		'sim_username'		=> 'ciao',
		'sim_passwd'		=> '********',		
	),
);

// Our web handlers
$app->get('/db/', function() use($app) {
	$st = $app['pdo']->prepare('SELECT name FROM test_table');
	$st->execute();

	$names = array();
	while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
		$app['monolog']->addDebug('Row ' . $row['name']);
		$names[] = $row;
	}

	return $app['twig']->render('database.twig', array(
		'names' => $names
	));
});

// Settings
$app->get('/ublox/settings', function() use ($settings) {
	return json_encode($settings);
});

$app->get('/ublox/settings/{param}', function (Silex\Application $app, $param) use ($settings) {
	if (!isset($settings[$param])) {
		$app->abort(404, "Setting parameter {$param} does not exist.");
	}
	return json_encode($settings[$param]);
});



$app->run();

?>