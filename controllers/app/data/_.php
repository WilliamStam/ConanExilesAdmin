<?php
namespace controllers\app\data;

use models as models;

class _ extends \controllers\_ {
	private static $instance;

	function __construct() {
		$this->f3 = \Base::instance();
		parent::__construct();
		$this->user = $this->f3->get("user");

		$this->f3->set("__runJSON", TRUE);




	}
	function _save_settings($section,$settings){
		$s_write = $this->user['raw_settings'];
		$s_write[$section] = array_replace_recursive((array)$this->user['raw_settings'][$section],$settings);
		models\system_users::_save($this->user['ID'], array("settings"=>$s_write));

	}



}
