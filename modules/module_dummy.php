<?php
define('dummy_module_file_loaded','');

class Dummy_Module extends Module {

		
	public function __construct($app,$name='') {
		if($name=='') $name='dummy module';
		parent::__construct($app,$name);
	}

	# Place functions here.. 

		
} # end class



?>
