<?php

namespace controllers\app\data;

use \models as models;

class scan extends _ {
	function __construct() {
		parent::__construct();

	}


	function data() {
		$return = array();

		$return['options'] = array(
			"filter"=>array(
				"search"=>""
			)
		);
		$return['list'] = models\players::getInstance()->getAll();


		return $GLOBALS["output"]['data'] = $return;
	}



}
