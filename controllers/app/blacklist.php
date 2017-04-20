<?php
namespace controllers\app;
use \timer as timer;
use \models as models;
class blacklist extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){



		
		$tmpl = new \template("template.twig","ui/app");
		$tmpl->page = array(
			"section"    => "blacklist",
			"sub_section"=> "list",
			"template"   => "blacklist",
			"meta"       => array(
				"title"=> "Blacklist",
			),
		);
		$tmpl->output();
	}
	
	
	
}
