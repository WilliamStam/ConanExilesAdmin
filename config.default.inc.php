<?php
$cfg['DB']['host'] = "localhost";
$cfg['DB']['username'] = "";
$cfg['DB']['password'] = "";
$cfg['DB']['database'] = "conan_db";

$cfg['git'] = array(
	'username'=>"",
	"password"=>"",
	"path"=>"github.com/WilliamStam/ConanExilesAdmin",
	"branch"=>"master"
);

$cfg['media'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR. "media" . DIRECTORY_SEPARATOR;
$cfg['backup'] = $cfg['media'] . "backups" . DIRECTORY_SEPARATOR;


$cfg['ttl'] = 0;
$cfg['gamedb'] = "C:\\Program Files (x86)\\Steam\\steamapps\\common\\Conan Exiles\\ConanSandbox\\Saved\\game.db";
$cfg['gamelog'] = "C:\\Game_Servers\\conan_exiles_server\\ConanSandbox\\Saved\\Logs\\ConanSandbox.log";

//backups "ConanSandbox-backup-2017.04.20-05.48.44"

$cfg['ban_time'] = 60*3; // minutes




$cfg['active'] = "5 days";
