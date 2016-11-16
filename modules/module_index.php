<?php

class Module {
	public $name,$version,$author;
	public $app;
	
	public function __construct($app,$name='') {
		$this->name=$name;
		$this->app=$app;
		$this->app->echoln2("Module named $this->name initialized");
	}		
}

include_once(dirname(__FILE__).'/module_vps.php');
include_once(dirname(__FILE__).'/module_vpn.php');
include_once(dirname(__FILE__).'/module_ssl.php');



?>
