<?php
namespace UBlox;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use Silex\ControllerProviderInterface;

class SettingsController implements ControllerProviderInterface {

	private $settings = array(
		'log_interval_s'	=> '10',
		'sim_pin'			=> '1234',
		'sim_apn'			=> 'something.dk',
		'sim_username'		=> 'ciao',
		'sim_passwd'		=> '********',		
	);


	public function connect(Application $app) {
		$factory = $app['controllers_factory'];

		$factory->get(
			'/',
			'UBlox\SettingsController::getAll'
		);

		$factory->get(
			'/{param}',
			'UBlox\SettingsController::getParameter'
		);

		return $factory;
	}

	public function getAll(Application $app) {
		return json_encode($this->settings);
	}

	public function getParameter(Application $app, $param) {
		if (!isset($this->settings[$param])) {
			$app->abort(HTTP_NOT_FOUND, "Parameter {$param} does not exist.");
		}
		return json_encode($this->settings[$param]);
	}
}

?>
