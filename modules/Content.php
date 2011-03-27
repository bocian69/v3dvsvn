<?php

Class Content {

	function __construct(&$registry) {
		$this->registry =& $registry;
		//$this->registry['db']->debug=1;
	}

	/** FRONT **/

	function getMainContent() {
		try {
			$res = $this->registry['db']->Execute("SELECT c.*, m.type FROM tbl_content c LEFT JOIN tbl_menu_structure m ON (c.id_poz = m.id_poz) WHERE onmain = 1 AND lang = '".$this->registry->_lang."' LIMIT 1");
    	} catch (exception $e) {
			return NULL;
		}
		return $res->FetchRow();
	}

	function getOneContent($id) {
		try {
			$res = $this->registry['db']->Execute("SELECT c.* FROM tbl_content c LEFT JOIN tbl_menu_structure m ON (c.id_poz = m.id_poz) WHERE m.id_poz = '$id' AND lang = '".$this->registry->_lang."' LIMIT 1");
    	} catch (exception $e) {
			return NULL;
		}
		return $res->FetchRow();
	}

	function getContents($parent) {
		try {
			$type = $this->registry['db']->GetOne("SELECT type FROM tbl_menu_structure WHERE id_poz = ".$parent);
    	} catch (exception $e) {
			return NULL;
		}
		switch ($type) {
			case 'kategoria':
				try {
					$res = $this->registry['db']->Execute("SELECT c.* FROM tbl_menu_structure as m LEFT JOIN tbl_content as c ON (m.id_poz = c.id_poz) WHERE m.parent = '".$parent."' AND m.type = 'Content' AND c.lang = '".$this->registry->_lang."' AND c.publish = 1 ORDER BY m.ord ASC");
		    	} catch (exception $e) {
					return NULL;
				}
				return $res->GetRows();
			case 'Content':
				try {
					$res = $this->registry['db']->Execute("SELECT * FROM tbl_content WHERE lang = '".$this->registry->_lang."' AND id_poz = ".$parent);
		    	} catch (exception $e) {
					return NULL;
				}
				return $res->GetRows();
			default:
				return NULL;
		}
	}

}

?>