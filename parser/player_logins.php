<?php
namespace parser;
use \timer as timer;
use \models as models;
class player_logins extends _run {
	private static $instance;
	function __construct() {
		parent::__construct();
		$players = array();
		$players_ = models\players::getInstance()->getAll("","ID ASC");
		foreach ($players_ as $item){
			$players[$item['char_name']] = $item['ID'];
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
			"description"=>"Updates the players Logins / Logouts",
			"order"=>3
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




			if (strpos($line,"disconnected")){
				$re = '^\[([^]]*)\]\[([^]]*)\]([^:]*):([^:]*): ([^:]*): Player ([^ ]*) ([^ (]*)';

				$re = '/'.$re.'/';
				preg_match_all($re, $line, $matches, PREG_SET_ORDER, 0);
				$return = array(
					"timestamp" => $timestamp,
					"type" => "disconnect",
					//"session" => $matches[0][2],
					"char_name" => $matches[0][7],
					"playerId" => $this->players[$matches[0][7]],
					"line"=>$line,
					//	"matches"=>$matches
				);
				//test_array($return);

			} else {
				$re = '^\[([^]]*)\]\[([^]]*)\]([^:]*):([^:]*): ([^:]*): Player ([^ ]*) ([^ (]*) (\([^)]*\))';

				$re = '/'.$re.'/';
				preg_match_all($re, $line, $matches, PREG_SET_ORDER, 0);
				$ip_ = $matches[0][8];
				$ip_ = str_replace(array("(",")"), "", $ip_);

				$ip_ = explode(":",$ip_);
				$ip = $ip_[0];
				$port = $ip_[1];


				//test_array($this->players);

				$return = array(
					"timestamp" => $timestamp,
					"type" => "connect",
					//"session" => $matches[0][2],
					"char_name" => $matches[0][7],
					"playerId" => $this->players[$matches[0][7]],
					"ip" => $ip,
					"port" => $port,
					"line"=>$line
				);

			}


		}

		if ($return){
			if ($return['char_name']=="Kwagga"){

			} else {
				$return = false;
			}
		}





		return $return;


	}

	
}
