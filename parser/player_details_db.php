<?php
namespace parser;
use \timer as timer;
use \models as models;
class player_details_db extends _run {
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
			"description"=>"Pulls the player details from the game.db file",
			"order"=>1
		);

		return $return;
	}

	function against_db($db){

		$return = array(
			"same"=>0,
			"updated"=>0,
			"added"=>0
		);

		$results_ = $db->query('SELECT playerId, id, char_name, level, rank,guild, isAlive, killerName, lastTimeOnline FROM characters');
		$users = new \DB\SQL\Mapper($this->f3->get("DB"),"players");

		$results = array();

		$fields = array(
			"playerId"=>"playerId",
			"char_name"=>"char_name",
			"lastTimeOnline"=>"lastTimeOnline",
			"dbId"=>"id"
		);

		while ($row = $results_->fetchArray(SQLITE3_ASSOC)) {
			$changes = false;
			$users->load("playerId='{$row['playerId']}'");

			foreach($fields as $k=>$v){
				if ($users->$k!=$row[$v]) $changes = true;
				$users->$k = $row[$v];
			}



			if ($users->dry()){
				$return['added'] = $return['added'] + 1;
			} else {
				if ($changes){
					$return['updated'] = $return['updated'] + 1;
				} else {
					$return['same'] = $return['same'] + 1;
				}

			}
			$users->save();

		}


		return $return;


	}

	function display($result){


		$result = $result['result'];

		$str = "S: ".$result['same']." | U: ".$result['updated'].' | A: '.$result['added'];

		//test_array($str);
		return $str;
	}

	function action($results){

	}

	
}
