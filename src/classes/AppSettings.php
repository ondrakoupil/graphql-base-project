<?php

namespace App;

class AppSettings {

	public $displayErrorDetails;

	public $db;

	public $baseUrl;

	public $frontendUrl;


	/**
	 * @param array $conf
	 *
	 * @return AppSettings
	 * @throws Exception
	 */
	static function load($conf) {

		$s = new AppSettings();

		$fields = get_object_vars($s);

		foreach ($fields as $f => $val) {
			if (!isset($conf[$f])) {
				throw new Exception('Could not process settings. Missing: ' . $f);
			}
			$s->$f = $conf[$f];
		}

		return $s;

	}

}
