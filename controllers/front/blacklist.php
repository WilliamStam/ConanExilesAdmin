<?php
namespace controllers\front;
use \timer as timer;
use \models as models;
class blacklist extends _ {
	function __construct(){
		parent::__construct();
	}
	function all(){
		$banned_ips = array();
		foreach ($this->ip(true) as $item){
			$banned_ips[] = $item;
		}
		foreach ($this->banned(true) as $item){
			$banned_ips[] = $item;
		}
		header("Content-Type: text/plain");
		foreach ($banned_ips as $item){
			echo $item.PHP_EOL;
		}
		exit();

	}
	function ip($return=false){
		$banned_ips = array();
		$data = models\blacklist::getInstance()->getAll();
		foreach ($data as $item){
			$banned_ips[] = $item['ip'];
		}

		//test_array(return);
		if ($return===true){
			return $banned_ips;
		}

		//test_array($banned_ips);
		header("Content-Type: text/plain");
		foreach ($banned_ips as $item){
			echo $item.PHP_EOL;
		}

		exit();
	}

	function banned($return=false){
		$cfg = $this->f3->get("cfg");
		$banned_ = models\players::getInstance()->getAll("banned='1'","banned_date DESC","");
		$banned_ids = array();

		$banned_ips = array();


		foreach ($banned_ as $item){
			$banned_ids[] = $item['ID'];
		}

		if (count($banned_ids)){

			$banned_ids = implode(",",$banned_ids);
			$where = "playerID in ($banned_ids) AND STR_TO_DATE(timestamp, '%Y.%m.%d-%H.%i.%s:%f') > NOW() - INTERVAL {$cfg['ban_time']} MINUTE";

			$records = models\players_ips::getInstance()->getAll($where);
			//test_array($where);
			foreach ($records as $item){
				$banned_ips[] = $item['ip'];
			}

		}




		if ($return===true){
			return $banned_ips;
		}



		header("Content-Type: text/plain");
		foreach ($banned_ips as $item){
			echo $item.PHP_EOL;
		}

		exit();


	}


}
