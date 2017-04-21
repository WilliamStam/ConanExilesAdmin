<?php
namespace parser;
use \timer as timer;
use \models as models;
class player_logins extends _run {
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
			"group"=>"Exploits",
			"description"=>"Checks if player is using the login exploit",
			"order"=>3
		);

		return $return;
	}
	

	
}
