<?php

namespace controllers\app\data;

use \models as models;

class home extends _ {
	function __construct() {
		parent::__construct();

	}


	function data() {
		$return = array();

		$parsers = $this->f3->get("parsers");
		$list = array();

		foreach($parsers as $item){
			if (!isset($list[$item['group']])){
				$list[$item['group']] = array(
					"label"=>$item['group'],
					"parsers"=>array()
				);
			}
			$list[$item['group']]['parsers'][] = $item;
		}
		$n = array();
		foreach($list as $item){
			$n[] = $item;
		}
		$list = $n;



		$return['list'] = $list;


		return $GLOBALS["output"]['data'] = $return;
	}



}
