<?php

namespace controllers\app\data;

use \models as models;

class blacklist extends _ {
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
		$return['manual'] = models\blacklist::getInstance()->getAll();
		$return['banned'] = models\players::getInstance()->getAll("banned='1'","banned_date DESC","",array("IP"=>true));


		return $GLOBALS["output"]['data'] = $return;
	}

	function ip_ban() {
		$ID=isset($_GET['ID'])?$_GET['ID']:"";
		$return = array();


		$return['details'] = models\blacklist::getInstance()->get($ID);


		return $GLOBALS["output"]['data'] = $return;
	}



}
