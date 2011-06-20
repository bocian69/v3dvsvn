<?php

Class Postgres extends Db {

	function __construct($registry) {
		parent::__construct($registry);
		$this->setDbType('postgres');
		//$this->registry['db']->debug=1;
	}
	
	function showTables() {
		$res = $this->db->Execute('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', array('public'));
		$tables = array();
		while ($ret = $res->FetchRow()) {
			$tables[] = array_pop($ret);
		}
		return $tables;
	}
	
	function showDatabases() {
		$res = $this->db->Execute($this->db->metaDatabasesSQL);
		$databases = array();
		while ($ret = $res->FetchRow()) {
			$databases[] = array_pop($ret);
		}
		return $databases;
	}
	
	function showColumns($table) {
        $sql = sprintf($this->db->metaColumnsSQL, $table, $table);//"SHOW COLUMNS FROM " . addslashes($table);
		$res = $this->db->Execute($sql);
        foreach ($res as $r) 
        {
        	$typ = $r['typname'];
        	if ($r['typname'] == 'varchar') {
        		$typ = $r['typname'].' ('.($r['atttypmod']-4).')';
        	}
			$tables[] = array(
                'field' => $r['attname'],
                'type' => $typ
            );
		}
        return $tables;
	}
	
	function showRelations() {
		$sql = 'SELECT tc.constraint_name,
				tc.constraint_type,
				tc.table_name,
				kcu.column_name,
				tc.is_deferrable,
				tc.initially_deferred,
				rc.match_option AS match_type,
				
				rc.update_rule AS on_update,
				rc.delete_rule AS on_delete,
				ccu.table_name AS references_table,
				ccu.column_name AS references_field
				FROM information_schema.table_constraints tc
				
				LEFT JOIN information_schema.key_column_usage kcu
				ON tc.constraint_catalog = kcu.constraint_catalog
				AND tc.constraint_schema = kcu.constraint_schema
				AND tc.constraint_name = kcu.constraint_name
				
				LEFT JOIN information_schema.referential_constraints rc
				ON tc.constraint_catalog = rc.constraint_catalog
				AND tc.constraint_schema = rc.constraint_schema
				AND tc.constraint_name = rc.constraint_name
				
				LEFT JOIN information_schema.constraint_column_usage ccu
				ON rc.unique_constraint_catalog = ccu.constraint_catalog
				AND rc.unique_constraint_schema = ccu.constraint_schema
				AND rc.unique_constraint_name = ccu.constraint_name
				
				WHERE lower(tc.constraint_type) in (?, ?)';
		$res = $this->db->Execute($sql, array('foreign key', 'primary key'));
		$tables = array();
		while ($ret = $res->FetchRow()) {
			if (!empty($ret['table_name']))
				$tables[] = array('table' => $ret['table_name'], 'column' => $ret['column_name']);
			if (!empty($ret['references_name']))
				$tables[] = array('table' => $ret['references_name'], 'column' => $ret['references_field']);
			
		}
		return $tables;
	}
	
	function tableInfo($table) {
		//"SHOW TABLE STATUS LIKE ?", array($table)
		$res = $this->db->Execute($this->db->metaColumnsSQL);
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
	
}

?>