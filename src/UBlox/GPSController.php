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

		$factory->get(
			'/',
			'UBlox\GPSController::getEntry'
		);



		return $factory;
	}

	public function postEntry(Application $app, Request $request) {
		$gps_entry = array(
			'coord'	=>array(
				$request->get('la'),
				$request->get('lo'),
			),
			'alt'	=> $request->get('alt'),
			'speed'	=> $request->get('speed'),
			'time'	=> $request->get('time'),
		);

		$gps_entry_db = add_entry($gps_entry);

		return new Response(json_encode($gps_entry_db), HTTP_CREATED);
	}

	public function getEntry(Application $app, $id) {
		$gps = $app['pdo']->fetchAssoc('SELECT * FROM gps WHERE id = ?', $id);

		if (0) {
			$app->abort(HTTP_NOT_FOUND, "Parameter {$param} does not exist.");
		}
		return json_encode($gps);
	}

	private function add_entry(Application $app, $gps) {
		$app['pdo']->insert('gps', $gps);

		return 0;
	}
}

?>
