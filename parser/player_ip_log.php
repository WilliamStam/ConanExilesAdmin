<?php
namespace parser;
use \timer as timer;
use \models as models;
class player_ip_log extends _run {
	private static $instance;
	function __construct() {
		parent::__construct();
		
	}
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	static function def(){
		$return = array(
			"id"=>__CLASS__,
			"group"=>"Players",
			"description"=>"Finds the players IP addresses from the log file",
			"order"=>1
		);

		return $return;
	}
	function against_log($LOG){
		$timer = new timer();
		$return = array(
			"same"=>0,
			"updated"=>0,
			"added"=>0
		);








		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		$return = array(
			"result"=>$return,
			"time"=>$timer->stop()
		);
		return $return;


	}

	static function display_result($result){


		$result = $result['result'];

		$str = "S: ".$result['same']." | U: ".$result['updated'].' | A: '.$result['added'];

		//test_array($str);
		return $str;
	}

	
}
