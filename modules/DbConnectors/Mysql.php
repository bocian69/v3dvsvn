<?php

Class Mysql extends Db {

	function __construct() {
		parent::__construct();
		$this->setDbType('mysql');
		//$this->registry['db']->debug=1;
	}
	
	function showColumns($table) {
        $sql = sprintf($this->db->metaColumnsSQL, $table);//"SHOW COLUMNS FROM " . addslashes($table);
		$res = $this->db->Execute($sql);
        foreach ($res as $r) 
        {
			$tables[] = array(
                'field' => $r['Field'],
                'type' => $r['Type']
            );
		}
        return $tables;
	}
	
	function tableInfo($table) {
		$res = $this->db->Execute("SHOW TABLE STATUS LIKE ?", array($table));
		$info = $res->FetchRow();
		$newInfo = array('Typ' => $info['Engine'],
						 'Kodowanie' => $info['Collation'],
						 'Ilość rekordów' => $info['Rows'],
						 'Data utworzenia' => $info['Create_time'],
						 'Data ostatniej modyfikacji' => $info['Update_time'],
						 'Następny numer' => empty($info['Auto_increment']) ? 1 : $info['Auto_increment']
						);
		return $newInfo;
	}
	
	function showTables() {
		$res = $this->db->Execute($this->db->metaTablesSQL);
		$tables = array();
		while ($ret = $res->FetchRow()) {
			$tables[] = array_pop($ret);
		}
		return $tables;
	}
	
	function showDatabases() {
		$res = $this->db->Execute('SHOW DATABASES');
		$databases = array();
		while ($ret = $res->FetchRow()) {
			$databases[] = array_pop($ret);
		}
		return $databases;
	}
	
	function showRelations() {
		
	}
	
	
}

?>