<?php
namespace UBlox;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * The routes used for stockcode.
 *
 * @package UBlox
 */
class SettingsController implements ControllerProviderInterface {
	private $settings = array(
		'log_interval_s'	=> '10',
		'sim_pin'			=> '1234',
		'sim_apn'			=> 'something.dk',
		'sim_username'		=> 'ciao',
		'sim_passwd'		=> '********',		
	);
    
	/**
	* Connect function is used by Silex to mount the controller to the application.
	*
	* Please list all routes inside here.
	*
	* @param Application $app Silex Application Object.
	*
	* @return Response Silex Response Object.
	*/
	public function connect(Application $app) {
		/**
		* @var \Silex\ControllerCollection $factory
		*/
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

	/**
	* Get all the stockcodes.
	*
	* @param Application $app       The silex app.
	*
	* @return string
	*/
	public function getAll(Application $app) {
		return json_encode($this->settings);
	}

	/**
	* Get a setting parameter.
	*
	* @param Application $app 		The silex app.
	* @param string      $param 	The parameter.
	*
	* @return string
	*/
	public function getParameter(Application $app, $param)
	{
		if (!isset($this->settings[$param])) {
			$app->abort(HTTP_NOT_FOUND, "Parameter {$param} does not exist.");
		}
		return json_encode($this->settings[$param]);
	}
}