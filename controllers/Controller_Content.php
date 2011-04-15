<?php

Class Controller_Content Extends Controller_Base {
	
	/**
	 * 
	 * Zmienna przechowująca argumenty z GET
	 * @var array
	 */
	
	var $args = array();
	
	
	/**
	 * 
	 * Ustawia argumenty przekazane z GET sformatowane przez router w postaci klucz=>wartosc
	 * @param $args
	 */
	
	function setArgs($args) {
		$this->args = $args;
	}
	
	
	/**
	 * 
	 * Starter
	 */

	function index() {
		
		/*
		$res = $this->registry['db']->Execute('SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?', array($validate['return']['db']));
		*/
		$this->setToolbar();
		$this->setTableView();
//		$this->registry->pokapoka($this->registry['db']->tableInfo('klient'));
		
		$this->registry['template']->assign('template', 'Content');
	}
	
	function setToolbar() {
//		$this->registry->pokapoka($this->registry['db']);
		$databases = $this->registry['db']->showDatabases();
		$toolbar[] = array('class' => 'toolbar_name','text' => 'Baza: <strong>'.$this->registry['db']->db->database.'</strong>');
		$toolbar[] = array('class' => 'separator');
		$toolbar[] = array('class' => 'toolbar_name','text' => 'Połącz z inną bazą:');
		foreach ($databases as $database) {
			$toolbar[] = array('link'  => array('id' 	=> '',
												  'class' => '',
												  'href'	=> main_path .'/switchDatabase/dbname/'.$database),
								 'img'	=> array('src'	=> img_path . 'database_go.png',
								 				 'title' => '',
								 				 'alt'	=> ''),
								 'label'	=> $database);
		}
		
		$this->registry['template']->assign('toolbar', $toolbar);
	}
	
	function setTableView() {
		$tables = $this->registry['db']->showTables();
		$this->registry['template']->assign('tables', $tables);
	}

}
