<?php

namespace controllers\app\save;

use \models as models;

class blacklist extends _ {
	function __construct() {
		parent::__construct();

	}




	function ip_ban() {
		$result = array();
		$ID = isset($_REQUEST['ID'])?$_REQUEST['ID']:"";
		$values = array(
			"ip" => $this->post("ip",true),
			"uID"=>$this->user['ID'],
			"datein"=>date("Y-m-d H:i:s")


		);


		if ($values['ip']==""){
			$this->errors['ip'] = "IP is required";
		}




		if (count($this->errors)==0){

			$ID = models\blacklist::_save($ID,$values);
		}
		$return = array(
			"ID" => $ID,
			"errors" => $this->errors
		);

		return $GLOBALS["output"]['data'] = $return;
	}



}
