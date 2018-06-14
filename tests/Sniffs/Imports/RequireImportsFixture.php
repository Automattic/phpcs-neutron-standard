<?php
declare( strict_types=1 );

namespace CAR_APP\Vehicles;

use Physics\MovementType;
use VehicleWithEmissions as PollutionProducer;
use function Roads\getSpeedLimit;
use function monitor_begin;
use function Physics\{ // this line has an unused import
	setMoving,
	setMovable as makeItMove,
	setStopped
};
use CAR_APP_GLOBALS; // this line has an unused import
use WeatherStore;
use WeatherModels\Storm;
use const Weather\SNOW;
use const Weather\{RAIN, SLEET, CAR_IN_WEATHER}; // this line has an unused import
use Notifications\Alerts;

const TYPE = 'Car';

class Car {
	public function drive(string $whereTo, Car $previousCar = null): void {
		// next line has unimported function
		$currentWeather = getWeather();
		if ($currentWeather === SNOW) {
			// next line has unimported class
			return new DrivingProblem('weather is too harsh');
		}
		if ($currentWeather === RAIN) {
			// next line has unimported class
			return new DrivingProblem('weather is too harsh');
		}
		// next line has unimported const
		if ($currentWeather === SUN) {
			// next line has unimported class
			if (DataStore::readyToMark()) {
				WeatherStore::markGoodDay();
			}
		}
		if ($currentWeather instanceof Storm) {
			return new \Exception('stormy');
		}
		// next line has unimported class
		if ($currentWeather instanceof Drizzle) {
			throw new \Exception();
		}
		if (getSpeedLimit() < 1) {
			// next line has unimported class
			return new DrivingProblem('no speed limit');
		}
		setMoving(TYPE, true);
		\Physics\setMoving(TYPE, 'drive');
		// next line has an unimported function
		setMovable(CAR_IN_WEATHER);
		makeItMove(CAR_IN_WEATHER);
		startMonitor();
		$this->polluter = new PollutionProducer();
		// next line has an unimported class
		$data = new stdClass(PHP_VERSION);
		$store = new WeatherStore($data);
		try {
			$store->trackWeather($store->key);
		} catch (\MyException $err) {
			Alerts\makeAnAlert($err);
			// next line has an unimported function
			OldAlerts\makeAlert($err);
			// next line has an unimported class
			$oldAlert = new OldAlerts\OldAlert();
			// next line has an unimported class
			$oldAlert = OldAlerts\OldAlert::makeRed($oldAlert);
			$oldAlert->warn();
			$alert = new Alerts\MyAlert();
			$alert = Alerts\MyAlert::markAlertImportant($alert);
			$alert->notifyUser();
			// next line has an unimported class
			return new Exception('buggy');
		}
		$name = $store->current_name_as_string;
		$data = new \stdClass(PHP_VERSION);
		return new MovementType('driving in ' . $name);
	}

	// next line has an unimported class
	public function convertToRobot(Car $car): Robot {
		// next line has an unimported class
		return new Robot($car);
	}
}

function startMonitor() {
	// next line has an unimported function
	OldAlerts\makeAlert();
	new Car();
	echo str_replace('Foo', 'Bar', 'Foobar...');
	$rows = whitelisted_function();
	$data = allowed_funcs_function_one();
	array_map(function (array $row) use ($data) {
		$data && monitor_begin($row);
	}, $rows);
}

define(WHATEVER, 'some words');
