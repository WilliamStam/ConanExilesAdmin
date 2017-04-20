<?php
namespace controllers\parser;
use \timer as timer;
use \models as models;
class parser extends _ {
	function __construct(){
		parent::__construct();
	}
	function players(){
		//if ($this->user['ID']=="")$this->f3->reroute("/login");
		$result = models\parser::getInstance()->updatePlayerTable();
		test_array($result);
	}

}
