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
		//test_array($scans);
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



					$def['against']['db'] = method_exists($class, "against_db");
					$def['against']['log'] = false;
					if (method_exists($class, "against_log")){
						$def['against']['log'] = true;
						$def['log_last_timestamp'] = null;

					}

					if (isset($scans[$def['id']])){
						$scan = $scans[$def['id']];
						unset($scan['parserID']);

						if (isset($scan['result']['last_timestamp'])){
							//test_array($scan);
							$def['log_last_timestamp'] = $scan['result']['last_timestamp'];
						}


						if (method_exists($class, "display")){
							$scan['result'] =  $class::getInstance()->display($scan['result']);
						}


						$def['last_scan'] = $scan;


					}


					$return[] = $def;
				}
			}
		}

		usort($return, function($a, $b) {
			return $a['order'] <=> $b['order'];
		});

		//test_array(array($return,$scans));
	//	test_array($return);
		return $return;
	}
	function getLastResults(){

		$return= array();
		$results = $this->f3->get("DB")->exec("
SELECT t1.* FROM scans t1 JOIN (SELECT scans.parserID, MAX(datein) datein FROM scans GROUP BY parserID) t2 ON t1.parserID = t2.parserID AND t1.datein = t2.datein; ");
		foreach ($results as $item){

			$item['result'] = json_decode($item['result'],true);
			$item['time'] = $item['result']['time'];
			$return[$item['parserID']] = $item;
		}

		//test_array($return);
		return $return;
	}

	private function _timestampToNumber($timestamp){
		return str_replace(array("."," ","-",":"), "", $timestamp);
	}
	function scan(){
		$timer = new timer();
		$DB = $this->f3->get("GAMEDB");


		$results = array();
		$result_scans = array();
		$earliestTimeStampFromLogScanners = null;

		$scanners = array();

		foreach ($this->f3->get("parsers") as $item){
			$scanners[$item['id']] = $item['id']::getInstance();


			if ($item['against']['db']){
				if (!isset($result_scans[$item['id']])){
					$result_scans[$item['id']] = array(
						"parser"=>$item['id'],
						"result"=>array(),
						"time"=>new timer()
					);
				}
				$result =  $scanners[$item['id']]->against_db($DB);

				if ($result){
					$result_scans[$item['id']]['result'] = $result;
				}



			}

			if ($item['against']['log']){
				//test_array(array("item"=>$item,"cl"=>$this->_timestampToNumber($item['log_last_timestamp'])));

				if ($earliestTimeStampFromLogScanners==null) {
					$earliestTimeStampFromLogScanners = $item['log_last_timestamp'];
				} else {
					if ($this->_timestampToNumber($item['log_last_timestamp']) < $this->_timestampToNumber($earliestTimeStampFromLogScanners)){
						$earliestTimeStampFromLogScanners = $item['log_last_timestamp'];
					}
				}

			}

		}

		//test_array($earliestTimeStampFromLogScanners);
		ini_set("auto_detect_line_endings", true);




		$LOG = fopen($this->cfg['gamelog'], "rb");
		if ($LOG) {

			$logFirstTimeStamp = null;
			while (!feof($LOG)) {
				$line = fgets($LOG, 4096);  // use a buffer of 4KB




				//test_array($buffer);
				$timestamp = $this->getLogTimeStamp($line);
				if ($timestamp)	{

					if ($logFirstTimeStamp==null){
						$logFirstTimeStamp = $timestamp;

						// TODO: if the $earliestTimeStampFromLogScanners is earlier than this open a previous log file

					}
					//test_array($timestamp);
				}





				foreach ($this->f3->get("parsers") as $item){



					if ($item['against']['log']){
						// only running the scanners if the log timestamp is greater than the stored last timestamp
						if ($this->_timestampToNumber($item['log_last_timestamp']) < $this->_timestampToNumber($timestamp)){

							if (!isset($result_scans[$item['id']])){
								$result_scans[$item['id']] = array(
									"parser"=>$item['id'],
									"result"=>array(),
									"time"=>new timer(),

									"status"=>0,
									"last_timestamp"=>null
								);
							}


							$result =   $scanners[$item['id']]->against_log($line,$timestamp);
							if ($result){
								$result_scans[$item['id']]['result'][] = $result;
							}

							if ($timestamp){
								$result_scans[$item['id']]['last_timestamp'] = $timestamp;
							}
						}



					}
				}
				///
			}


			//test_array($result_scans);
			foreach ($result_scans as $key=>$item){
				$time = $item['time']->stop();
				$status = 0;

				$results[$item['parser']]['result'] =  $item['result'];
				$results[$item['parser']]['time'] =  $time;

				$result = $item['result'];
				$result['time'] = $time;
				$result['last_timestamp'] = $item['last_timestamp'];
			//	test_array($item);

				if (method_exists($item['parser'], "action")){

					$results[$item['parser']]['status'] =  $status = $scanners[$item['parser']]->action( $item['result']);;
				}


				$this->updateScanTable($item['parser'], $result, $status);



			}


			//$this->updateScanTable($item['id'], $result, $status);

			fclose($LOG);
		} else {
			// error opening the file.
		}


		$timeTaken = $timer->stop();

		test_array(array("time"=>$timeTaken,"results"=>$results));

	}
	function getLogTimeStamp($line){

		$timestamp = false;
		if (substr($line,0,1)=="["){
			// [2017.04.20-03.48.44:766]

			// TODO: turn this into a regex. but my phone a friend is in hospital

			$timestamp	= substr($line, 1, 23);
			$datetimestamp = substr($timestamp, 0, 4)."-".substr($timestamp, 5, 2)."-".substr($timestamp, 8, 2)." ".substr($timestamp, 11, 2).":".substr($timestamp, 14, 2).":".substr($timestamp, 17, 2);


			if (date("Y-m-d H:i:s",strtotime($datetimestamp))!=$datetimestamp){
				$timestamp = false;
			}

		}
		//test_string($line);



		return $timestamp;
	}


	function updateScanTable($parser,$result,$status){

		$this->scansORM->parserID = $parser;
		$this->scansORM->result = json_encode($result);
		$this->scansORM->execute_time = $result['time'];
		$this->scansORM->save();
		$this->scansORM->reset();





	}


	
}
