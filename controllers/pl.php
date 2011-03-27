<?php

Class Pl {

    function __construct(&$registry){
		header('Content-Type: text/html; charset=utf-8');
		$this->registry =& $registry;
		$this->registry->_lang = 'pl';
		echo 'polski';
	}

}

?>