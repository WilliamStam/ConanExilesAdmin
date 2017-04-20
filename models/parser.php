<?php
namespace models;

use \timer as timer;

class parser extends _ {
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

	function login() {
		$timer = new timer();
		$return = array();




		//test_array($return);
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());

		return $return;
	}


	function updatePlayerTable(){
		$timer = new timer();
		$return = array(
			"same"=>0,
			"updated"=>0,
			"added"=>0
		);

		$results_ = $this->f3->get("GAMEDB")->query('SELECT playerId, id, char_name, level, rank,guild, isAlive, killerName, lastTimeOnline FROM characters');
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

		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}










}
