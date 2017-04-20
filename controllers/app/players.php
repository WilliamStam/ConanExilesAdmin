<?php
namespace controllers\app;
use \timer as timer;
use \models as models;
class players extends _ {
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
			"section"    => "players",
			"sub_section"=> "list",
			"template"   => "players",
			"meta"       => array(
				"title"=> "Players",
			),
		);
		$tmpl->output();
	}
	
	
	
}
