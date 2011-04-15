<?php

Class Postgres extends Db {

	function __construct($registry) {
		parent::__construct($registry);
		$this->setDbType('postgres');
		//$this->registry['db']->debug=1;
	}
	
	function showTables() {
		/*$res = $this->db->Execute($this->db->metaTablesSQL);
		$tables = array();
		while ($ret = $res->FetchRow()) {
			$tables[] = array_pop($ret);
		}
		return $tables;*/
	}
	
	function showDatabases() {
		/*$res = $this->db->Execute($this->db->metaDatabasesSQL);
		$databases = array();
		while ($ret = $res->FetchRow()) {
			$databases[] = array_pop($ret);
		}
		return $databases;*/
	}
}

?>