<?php

namespace controllers\app\data;

use \models as models;

class players extends _ {
	function __construct() {
		parent::__construct();

	}


	function data() {
		$return = array();
		$activity = isset($_GET['activity'])?$_GET['activity']:"1";
		$search = isset($_GET['search'])?$_GET['search']:"";

		$return['options'] = array(
			"activity"=>$activity,
			"filter"=>array(
				"search"=>$search
			)
		);


		$where = "1";
		$timestamp = strtotime("-".$this->cfg['active']);
		if ($activity=='1'){

			$where = $where." AND lastTimeOnline >= ". $timestamp;

		} else {
			$where = $where." AND lastTimeOnline < ". $timestamp;
		}

		if ($search){
			$where = $where . " AND (playerId LIKE '%$search%' OR char_name LIKE '%$search%')";
		}


		$return['list'] = models\players::getInstance()->getAll($where,"lastTimeOnline DESC","",array("IP"=>true));


		return $GLOBALS["output"]['data'] = $return;
	}
	function details() {
		$return = array();
		$ID = isset($_GET['ID'])?$_GET['ID']:"";

		$return= models\players::getInstance()->get($ID,array("IP"=>true));
		$logins_ = models\players_logins::getInstance()->getAll("playerID='{$return['ID']}'","login_timestamp DESC","0,10");

		$logins = array();

		foreach ($logins_ as $item){
			$key = date("Ymd",strtotime($item['login_time']));
			if (!isset($logins[$key])){
				$logins[$key] = array(
					"label"=>date("D, d M Y",strtotime($item['login_time'])),
					"records"=>array()
				);
			}

			$logins[$key]['records'][] = $item;

		}



		$return['logins'] = $logins;
		$return['ips'] = models\players_ips::getInstance()->getAll("playerID='{$return['ID']}'","timestamp DESC","0,10");


		return $GLOBALS["output"]['data'] = $return;
	}



}
