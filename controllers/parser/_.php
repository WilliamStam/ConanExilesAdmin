<?php
namespace controllers\parser;
use \timer as timer;
use \models as models;
class _ extends \controllers\_ {

	function __construct() {
		$this->f3 = \Base::instance();
		parent::__construct();
		$this->cfg = $this->f3->get("cfg");

		
		
	}

	function templatefile($class){
		$class = str_replace("controllers\\parser\\","",$class);
		return $class;
		
	}

	
}
