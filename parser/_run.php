<?php
namespace parser;
use \timer as timer;
use \models as models;
class _run {
	private static $instance;
	function __construct() {
		$this->f3 = \Base::instance();
		$this->user = $this->f3->get("user");
		$this->cfg = $this->f3->get("cfg");
		
		$this->f3->set("NOTIMERS",true);

		$this->scansORM = new \DB\SQL\Mapper($this->f3->get("DB"),"scans");
		
	}
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	static function getClass($input){
		return "\\parser\\{$input}";

	}
	function _list(){
		$return = array();
		$scans = $this->getLastResults();
		foreach (glob("./parser/*.php") as $input) {

			$class = self::getClass(str_replace(array("./parser/",".php"), "", $input));

			if (class_exists($class)) {
				if (method_exists($class, "def")){
					$defO = $class . "::def";
					$def = $defO();
					$def['last_scan'] = array();
					$def['against']=array(
						"db"=>false,
						"log"=>false
					);
					if (isset($scans[$def['id']])){
						$scan = $scans[$def['id']];
						unset($scan['parserID']);

						if (method_exists($class, "display_result")){
							$scan['result'] =  $class::display_result($scan['result']);
						}


						$def['last_scan'] = $scan;
					}

					$def['against']['db'] = method_exists($class, "against_db");
					$def['against']['log'] = method_exists($class, "against_log");


					$return[] = $def;
				}
			}
		}

		usort($return, function($a, $b) {
			return $a['order'] <=> $b['order'];
		});

		//test_array(array($return,$scans));
		//test_array($return);
		return $return;
	}
	function getLastResults(){

		$return= array();
		$results = $this->f3->get("DB")->exec("SELECT * FROM (SELECT * FROM scans ORDER BY datein DESC limit 5) as temp GROUP BY parserID");
		foreach ($results as $item){

			$item['result'] = json_decode($item['result'],true);
			$item['time'] = $item['result']['time'];
			$return[$item['parserID']] = $item;
		}


		return $return;
	}

	function scan(){
		$DB = $this->f3->get("GAMEDB");
		$LOG = fopen($this->cfg['gamelog'], "r");

		$results = array();
		foreach ($this->f3->get("parsers") as $item){
			if ($item['against']['db']){
				$result =  $item['id']::getInstance()->against_db($DB);
				$results[$item['id']] =$result;
				$status = ($result)?1:0;
				$this->updateScanTable($item['id'], $result, $status);
			}
			/*
			if ($item['against']['log']){
				$result =  $item['id']::getInstance()->against_log($LOG);
				$results[$item['id']] =$result;
				$status = ($result)?1:0;
				$this->updateScanTable($item['id'], $result, $status);
			}
			*/

		}



		if ($LOG) {
			//while (($line = fgets($handle)) !== false) {
			fclose($LOG);
		} else {
			// error opening the file.
		}




		test_array($results);

	}

	function updateScanTable($parser,$result,$status){

		$this->scansORM->parserID = $parser;
		$this->scansORM->result = json_encode($result);
		$this->scansORM->status = $status;
		$this->scansORM->save();





	}


	
}
