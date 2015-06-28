<?php
namespace UBlox;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use Silex\ControllerProviderInterface;

class GPSController implements ControllerProviderInterface {

	// db stuff maybe?


	public function connect(Application $app) {
		$factory = $app['controllers_factory'];

		$factory->post(
			'/',
			'UBlox\GPSController::postEntry'
		);

		return $factory;
	}

	public function postEntry(Application $app, Request $request) {
		return json_encode($this->settings);
	}
}

?>
