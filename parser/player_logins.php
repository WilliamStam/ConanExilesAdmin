<?php

namespace parser;

use \timer as timer;
use \models as models;

class player_logins extends _run {
	private static $instance;

	function __construct() {
		parent::__construct();
		$this->players = false;
		$this->result = array();

		$this->max_session_length_in_minutes = 1440;
	}

	public static function getInstance() {
		if ( is_null(self::$instance) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	static function def() {
		$return = array(
			"id" => __CLASS__,
			"group" => "Players",
			"description" => "Updates the players Logins / Logouts",
			"order" => 8,
		);

		return $return;
	}

	function against_log($line, $timestamp) {
		$return = FALSE;





		if ( strpos($line, "BattlEyeLogging: BattlEyeServer: Print Message: Player #") && strpos($line, "connected") ) {
			if ( strpos($line, "disconnected") ) {
				$re = '^\[([^]]*)\]\[([^]]*)\]([^:]*):([^:]*): ([^:]*): Player ([^ ]*) ([^ (]*)';

				$re = '/' . $re . '/';
				preg_match_all($re, $line, $matches, PREG_SET_ORDER, 0);
				$return = array(
					"timestamp" => $timestamp,
					"type" => "disconnect",
					"char_name" => $matches[0][7],

					"line" => $line,
					//	"matches"=>$matches
				);
				//test_array($return);

				$this->result[$return['char_name']][] = array(
					"login"=>$timestamp,
					"logout"=>"",
					"duration"=>null
				);

			} else {
				$re = '^\[([^]]*)\]\[([^]]*)\]([^:]*):([^:]*): ([^:]*): Player ([^ ]*) ([^ (]*) (\([^)]*\))';

				$re = '/' . $re . '/';
				preg_match_all($re, $line, $matches, PREG_SET_ORDER, 0);
				$ip_ = $matches[0][8];
				$ip_ = str_replace(array(
					"(",
					")",
				), "", $ip_);

				$ip_ = explode(":", $ip_);
				$ip = $ip_[0];
				$port = $ip_[1];


				//test_array($this->players);

				$return = array(
					"timestamp" => $timestamp,
					"type" => "connect",
					"char_name" => $matches[0][7],

					"ip" => $ip,
					"port" => $port,
					"line" => $line,
				);
				if ($this->result[$return['char_name']]){
					//test_array($this->result[$return['char_name']]);
					foreach ($this->result[$return['char_name']] as $k => $item){

						if ($item['logout']==""){
							$this->result[$return['char_name']][$k]['logout'] = $timestamp;

							$s = strtotime(timeStampToDate($this->result[$return['char_name']][$k]['login']));
							$e = strtotime(timeStampToDate($this->result[$return['char_name']][$k]['logout']));

							$duration = round(abs($e - $s) / 60,2);
							$this->result[$return['char_name']][$k]['duration'] = $duration;

						}
					}
				}


			//	$this->result[$return['char_name']][$this->result[$return['char_name']][0]] = $timestamp;

			}


		}


		return $return;


	}

	function display($result) {


		$result = $result['result'];

		//	$str = "S: ".$result['same']." | U: ".$result['updated'].' | A: '.$result['added'];

		//test_array($str);
		return json_encode($result);
	}

	function action($results) {
		$players = array();
		$players_ = models\players::getInstance()->getAll("","ID ASC","",array("IP"=>true));

		foreach ($players_ as $item){
			$players[$item['char_name']] = array(
				"ID"=>$item['ID'],
				"playerId"=>$item['playerId'],
				"char_name"=>$item['char_name'],
				"ip"=>null,
				"port"=>null,
				"timestamp"=>null,
				"last_ip"=>$item['last_ip'],
			);
		}
		$result_logins = array();
		$table = new \DB\SQL\Mapper($this->f3->get("DB"),"players_logins");

		$logouts = array();
		foreach ($results as $item){
			if (isset($players[$item['char_name']])){
				$playerID = $players[$item['char_name']]['ID'];

				if ($item['type']=='connect'){
					$table->load("playerID='{$playerID}' AND login_timestamp='{$item['timestamp']}'");
					$table->playerID = $playerID;
					$table->login_timestamp = $item['timestamp'];
					$table->save();
					$table->reset();

				}
				if ($item['type']=='disconnect'){
					$timekey = str_replace(array(".",":"," ","-"),"",$item['timestamp']);
					$item['logout_timestamp_time'] = timeStampToDate($item['timestamp']);
					$logouts[$timekey] = $item;
				}
			}
		}





		$records = $this->f3->get("DB")->exec("SELECT * FROM players_logins WHERE logout_timestamp is null ORDER BY login_timestamp ASC");



		$logins = array();
		foreach ($records as $item){
			$timekey = str_replace(array(".",":"," ","-"),"",$item['login_timestamp']);
			$item['login_timestamp_time'] = timeStampToDate($item['login_timestamp']);
			$logins[$timekey] = $item;
		}


		asort($logins);
		arsort($logouts);


		$result = array();

		foreach ($logins as $inK=>$inV){
			foreach ($logouts as $loK=>$loV){

				if ($inK<$loK){
					$inV['logout_timestamp'] = $loV['timestamp'];

					$to_time = strtotime($inV['login_timestamp_time']);
					$from_time = strtotime($loV['logout_timestamp_time']);
					$duration = round(abs($to_time - $from_time) / 60,2);


					$inV['duration'] = $duration;
				}

			}

			if ($inV['duration']<$this->max_session_length_in_minutes) $result[] = $inV;

		}


		foreach ($result as $item){

					$table->load("ID='{$item['ID']}'");
					$table->playerID = $item['playerID'];
					$table->logout_timestamp = $item['logout_timestamp'];
					$table->duration = $item['duration'];
					$table->save();
					$table->reset();



		}















		return 1;
	}


}
