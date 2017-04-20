<?php
namespace models;

use \timer as timer;

class players extends _ {
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

	function get($ID, $options = array()) {
		$timer = new timer();
		$where = "players.ID = '$ID'";



		$result = $this->getData($where, "", "0,1", $options);


		if (count($result)) {
			$return = $result[0];

		}
		else {
			$return = parent::dbStructure("players");
		}

		if ($options['format']) {
			$return = $this->format($return, $options);
		}
		//test_array($return);
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());

		return $return;
	}

	public function getAll($where = "", $orderby = "", $limit = "", $options = array()) {
		$result = $this->getData($where, $orderby, $limit, $options);
		$result = $this->format($result, $options);

		return $result;

	}

	public function getData($where = "", $orderby = "", $limit = "", $options = array()) {
		$timer = new timer();
		$f3 = \Base::instance();

		if ($where) {
			$where = "WHERE " . $where . "";
		}
		else {
			$where = " ";
		}

		if ($orderby) {
			$orderby = " ORDER BY " . $orderby;
		}
		if ($limit) {
			$limit = " LIMIT " . $limit;
		}

		$args = "";
		if (isset($options['args'])) {
			$args = $options['args'];
		}

		$ttl = "";
		if (isset($options['ttl'])) {
			$ttl = $options['ttl'];
		}


		$ip_join = "";
		if (isset($options['IP'])&&$options['IP']) {
			$ip_join = ",(SELECT ip FROM players_ip WHERE playerID = players.ID) AS last_ip";
		}


		$result = $f3->get("DB")->exec("
			 SELECT DISTINCT players.*, system_users.fullname as banned_user_name $ip_join
			FROM players LEFT JOIN system_users ON system_users.ID = players.banned_uID
			$where
			$orderby
			$limit;
		", $args, $ttl);

		$return = $result;
		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());

		return $return;
	}



	public static function _save($ID, $values = array()) {
		$timer = new timer();
		$f3 = \Base::instance();



		//test_array($values);

		$a = new \DB\SQL\Mapper($f3->get("DB"), "players");
		$a->load("ID='$ID'");

		foreach ($values as $key => $value) {
			if (isset($a->$key)) {
				$a->$key = $value;
			}

		}

		$a->save();
		$ID = ($a->ID) ? $a->ID : $a->_id;









		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());

		return $ID;
	}


	public static function _delete($ID) {
		$timer = new timer();
		$f3 = \Base::instance();
		$user = $f3->get("user");


		$a = new \DB\SQL\Mapper($f3->get("DB"), "players");
		$a->load("ID='$ID'");

		$a->erase();

		$a->save();


		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());

		return "done";

	}


	static function format($data,$options=array()) {
		$timer = new timer();
		$single = FALSE;
		//	test_array($items);
		if (isset($data['ID'])) {
			$single = TRUE;
			$data = array($data);
		}



		//	test_array($data);
		//test_array($data);

		$i = 1;
		$n = array();
		foreach ($data as $item) {
			$item['timeago']['lastTimeOnline'] = timesince(date("Y-m-d H:i:s",$item['lastTimeOnline']));
			$item['timeago']['lastlogin'] = timesince($item['lastlogin']);
			$item['timeago']['lastActivity'] = timesince($item['lastActivity']);
			$item['raw_settings'] = (json_decode($item['settings'], TRUE));


			$n[] = $item;
		}

		if ($single) {
			$n = $n[0];
		}


		$records = $n;


		//test_array($n); 


		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());

		return $n;
	}











}
