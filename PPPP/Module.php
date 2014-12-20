<?php

Abstract Class PPPP_Module {

	public function __construct() {
		add_action("PPPP_init", array( $this, "add_hook" ));
	}

	abstract function add_hook();

}
