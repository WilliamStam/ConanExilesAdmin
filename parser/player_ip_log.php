<?php
namespace parser;
use \timer as timer;
use \models as models;
class player_ip_log extends _run {
	private static $instance;
	function __construct() {
		parent::__construct();

		$players = array();
		$players_ = models\players::getInstance()->getAll("","ID ASC");
		foreach ($players_ as $item){
			$players[$item['char_name']] = array(
				"ID"=>$item['ID'],
				"playerId"=>$item['playerId'],
				"char_name"=>$item['char_name'],
				"ip"=>null,
				"port"=>null,
				"timestamp"=>null,
			);
		}

		$this->players = $players;

		
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
	function against_log($line,$timestamp){

		$return = false;


		// [2017.04.20-11.49.41:754][194]LogNet: Join succeeded: WtFnE$$
		// [2017.04.20-11.49.41:900][198]BattlEyeLogging: BattlEyeServer: Print Message: Player #2 WtFnE$$ (45.220.33.198:55078) connected
		// [2017.04.20-11.49.41:900][198]BattlEyeLogging: BattlEyeServer: Print Message: Player #2 WtFnE$$ - GUID: 5e105a859f4fddc44bf9615f75c1c37a


		// [2017.04.18-07.30.56:722][414]BattlEyeLogging: BattlEyeServer: Print Message: Player #1 percy1994 (41.113.222.239:3690) connected




		if (strpos($line,"BattlEyeLogging: BattlEyeServer: Print Message: Player #")&&strpos($line,"connected")){

			// TODO: use regex to get all the data


			$re = '^\[([^]]*)\]\[([^]]*)\]([^:]*):([^:]*): ([^:]*): Player ([^ ]*) ([^ (]*) (\([^)]*\))';

			$re = '/'.$re.'/';
			preg_match_all($re, $line, $matches, PREG_SET_ORDER, 0);
			$ip_ = $matches[0][8];
			$ip_ = str_replace(array("(",")"), "", $ip_);
			$ip_ = explode(":",$ip_);
			$ip = $ip_[0];
			$port = $ip_[1];


			if ($ip){
				if (isset($this->players[$matches[0][7]])){
					$this->players[$matches[0][7]]['ip']  = $ip;
					$this->players[$matches[0][7]]['port']  = $port;
					$this->players[$matches[0][7]]['timestamp']  = $timestamp;
					$return = $this->players[$matches[0][7]];
				}


			}




		}


		return $return;


	}

	function display($result){


		$result = $result['result'];

	//	$str = "S: ".$result['same']." | U: ".$result['updated'].' | A: '.$result['added'];

		//test_array($str);
		return json_encode($result);
	}

	function action($results){

		$players_ = $this->players;
		$players = array();
		foreach ($players_ as $item){
			if ($item['ip']){
				$players[$item['ID']] = $item;
			}
		}

		$table = new \DB\SQL\Mapper($this->f3->get("DB"),"players_ip");

		foreach ($players as $item){



			$table->ip = $item['ip'];
			$table->port = $item['port'];
			$table->playerID = $item['ID'];
			$table->timestamp = $item['timestamp'];
			$table->save();

		}




		return 1;
	}
	
}
