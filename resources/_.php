<?php
namespace resources;
use \timer as timer;
use \models as models;
class _ {

	function __construct() {
		$this->f3 = \Base::instance();
		$this->user = $this->f3->get("user");
		$this->cfg = $this->f3->get("cfg");
		
		$this->f3->set("NOTIMERS",true);
		
	}

	

	
}
