<?php
namespace controllers\app;
use \timer as timer;
use \models as models;
class scan extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){



/*
		$results_ = $this->f3->get("GAMEDB")->query('SELECT class,id,x,y,z,sx,sy,sz,rx,ry,rz,rw FROM actor_position');

		$results = array();
		while ($row = $results_->fetchArray(SQLITE3_ASSOC)) {
			$results[] = $row;
		}
*/



		


		
		$tmpl = new \template("template.twig","ui/app");
		$tmpl->page = array(
			"section"    => "scan",
			"sub_section"=> "list",
			"template"   => "scan",
			"meta"       => array(
				"title"=> "Scan",
			),
		);
		$tmpl->output();
	}
	
	
	
}
