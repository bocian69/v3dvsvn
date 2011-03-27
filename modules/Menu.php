<?php

Class Menu {

	function __construct(&$registry) {
		$this->registry =& $registry;
	}

	function setMenu($menu){
		if ($menu) {
			$this->menu = $menu;
		}
		else die('Nie podano menu');
	}

	function getMainContent() {
		try {
			$res = $this->registry['db']->Execute("SELECT * FROM tbl_content WHERE onmain = 1 AND lang = '".$this->registry->_lang."' LIMIT 1");
    	} catch (exception $e) {
			return NULL;
		}
		return $res->FetchRow();
	}

	/** FRONT **/

	/*
	 * Zwraca 1 poziom menu
	 */

	function getOneLevel($parent) {
		try {
			$res = $this->registry['db']->Execute("SELECT ms.id_poz, ms.type, c.name FROM tbl_menu_structure as ms LEFT JOIN tbl_content as c ON (ms.id_poz = c.id_poz) LEFT JOIN tbl_menu as m ON (m.id = ms.id_menu) WHERE ms.parent = '".$parent."' AND m.id = '".$this->menu."' AND c.publish = 1 AND c.lang = '".$this->registry->_lang."' ORDER BY ms.ord ASC");
    	} catch (exception $e) {
			return NULL;
		}
		return $res->GetRows();
	}

	function getOneLevelSection($parent, $section) {
		try {
			$res = $this->registry['db']->Execute("SELECT ms.id_poz, ms.type, c.name FROM tbl_menu_structure as ms LEFT JOIN tbl_content as c ON (ms.id_poz = c.id_poz) LEFT JOIN tbl_menu as m ON (m.id = ms.id_menu) WHERE ms.parent = '".$parent."' AND m.id = '".$section."' AND c.publish = 1 AND c.lang = '".$this->registry->_lang."' ORDER BY ms.ord ASC");
    	} catch (exception $e) {
			return NULL;
		}
		return $res->GetRows();
	}




	function setMainMenu(){
		$menu  = $this->registry['db']->GetOne("SELECT id FROM tbl_menu WHERE main_menu = 1");
		$this->menu = $menu;
	}


	/** Panel Admina  **/

	function getMenus($selectedMenu){
		$rs = $this->registry['db']->Execute('SELECT `name`, `id` FROM `tbl_menu` ORDER BY name ASC');
		return $rs->GetMenu2('record_structure[id_menu]',$selectedMenu,true,false,0,'id="sel_menu"');
	}


	function menuJSON() {
		$menutbl = $this->getMenuArray();
		$totalCount = count($menutbl,COUNT_RECURSIVE);
		$menutbl_nagl = array('requestFirstIndex' => 0,
							  'firstIndex' => 0,
							  'count' => $totalCount,
							  'totalCount' => $totalCount,
							  'columns' => array('0' => 'Menu',
												 '1' => 'Akcje',
												),
							  'items' => $menutbl
							  );
		return json_encode($menutbl_nagl);
	}

	function getMenuArray($parent='0') {

		try {
			$res = $this->registry['db']->Execute("SELECT c.*, s.*, IF(c.name IS NULL ,CONCAT('brak nazwy - [',c_pl.name,']'),c.name) as name FROM tbl_menu_structure as s LEFT JOIN tbl_content as c ON (s.id_poz = c.id_poz AND c.lang = '".$this->registry->_lang."') LEFT JOIN tbl_content as c_pl ON (s.id_poz = c_pl.id_poz AND c_pl.lang = 'pl') WHERE parent = '".$parent."' AND id_menu = ".$this->menu." ORDER BY s.ord ASC");
			//$res = $this->registry['db']->Execute("SELECT s.*, IF(c.name IS NULL ,CONCAT('brak nazwy - [',c_pl.name,']'),c.name) as name FROM tbl_menu_structure as s LEFT JOIN tbl_content as c ON (s.id_poz = c.id_poz AND c.lang = '".$this->registry->_lang."') LEFT JOIN tbl_content as c_pl ON (s.id_poz = c_pl.id_poz AND c_pl.lang = 'pl') WHERE s.id_menu = '".$_SESSION['selectedMenu']."' AND s.parent = '".$parent."' AND s.type='kategoria' ORDER BY s.ord ASC");
    	} catch (exception $e) {
			return false;
		}
	    if ($res->_numOfRows > 0) {
	    	$data = $res->GetRows();
	    	$menu = array();
    		foreach ($data as $dane) {
		        $id = $dane['id_poz'];
		        $ikonka = ($dane['type'] == 'kategoria') ? 'folder' : (($dane['type'] == 'Content') ? 'Content' : 'page_link');
				//BLOKADA POZYCJI
				if ( $dane['name'] == null ){
					if ($this->registry->_lang != 'pl'){
						try {
							$plnames = $this->registry['db']->Execute("SELECT name FROM tbl_content WHERE id_poz = '".$id."' AND lang = '".$this->registry->_lang."'");
				    	} catch (exception $e) {
							return false;
						}
						$plname = $plnames->GetRowAssoc(false);
						$nazwa = '[ Brak nazwy ] '.(($plname['name'] != '') ? '(Wersja polska: '.$plname['name'].')' : '');
					}
					else {
						$nazwa = '[ Brak nazwy ]';
					}
				}
				else{
					$nazwa = $dane['name'];
				}
				/*
				if ($dane['status'] == 1) {
					try {
						$res2 = $this->registry['db']->Execute("SELECT name FROM tbl_auth WHERE id_usr = '".$dane['usr_block']."'");
					} catch (exception $e) {
						return false;
					}
					$log = $res2->GetRowAssoc(false);
					$link = '<a href="#" onclick="unblockPage('.$id.')"><img src="'. img_path .'menu_actions/locked.gif" title="header=[Kto zablokował] body=[<table><tr><td>Login: '.$log['name'].'</td></tr><tr><td>Data: '.$dane['date_block'].'</td></tr></table>]" onmouseover="this.src=\''. img_path .'menu_actions/unlocked.png\'" onmouseout="this.src=\''. img_path .'menu_actions/locked.png\'" border="0" /></a> '.$nazwa;
				} else {
					$link = '<a href="'.main_path.'/Administrator/page/,edit,'.$id.','.$this->registry->_lang.'">'.$nazwa.'</a>';
				}
				*/
				if ($dane['status'] == 1) {
					try {
						$res2 = $this->registry['db']->Execute("SELECT name FROM tbl_auth WHERE id_usr = '".$dane['usr_block']."'");
					} catch (exception $e) {
						return false;
					}
					$log = $res2->GetRowAssoc(false);
					$title = '<a href="#" onclick="unblockPage('.$id.')"><img src="'. img_path .'lock.png" title="Kto zablokował: '.$log['name'].', Data: '.$dane['date_block'].'" onmouseover="this.src=\''. img_path .'lock_open.png\'" onmouseout="this.src=\''. img_path .'menu_actions/locked.png\'" border="0" /></a> '.$nazwa;
					$edit = '<img src="'. img_path .'page_edit.png" title="Edycja zablokowana" />';
				} else {
					$title = $nazwa;
					$edit = '<a href="'.main_path.'/Administrator/page/,edit,'.$id.','.$this->registry->_lang.'"><img src="'. img_path .'page_edit.png" title="Edytuj" /></a>';
				}
				//PUBLIKUJ
				$publish = ($dane['publish'] == '1') ? '<a href="#" onclick="publishPage(0,'.$id.')"><img src="'. img_path .'page_go.png" title="Opublikowany" border="0" /></a>' : '<a href="#" onclick="publishPage(1,'.$id.')"><img src="'. img_path .'page_delete.png"  title="Nieopublikowany" border="0" /></a>';
				//Strona główna
				$home = ($dane['onmain'] == '1') ? '<img class="homePage" src="'. img_path .'menu_actions/home.gif" title="Wyświetla się na stronie głównej" border="0" />'
												 : '<a href="#" onclick="setHomePage('.$id.')"><img class="homePage" src="'. img_path .'menu_actions/home_disabled.gif" title="Ustaw jako strona główna" border="0" /></a>';

				$del = '<a href="#" onclick="deletePage('.$id.')">' .
						'<img src="'. img_path .'cross.png" title="Usuń Element we wszystkich wersjach językowych" border="0" />' .
						'</a>';
		        $menu[] = array('id' => $id,'info' => array((($ikonka == 'folder') ? '<a href="#" class="openClose" onclick="openCloseNode(this); return false;"></a> ' : '<img src="'.img_path.'/menu_actions/'.$ikonka.'.png" /> ') . $title, $edit.' | '.$publish.' | '.$home.' | '.$del));
		        $children = array();
		       	$children = $this->getMenuArray($id);
		      	if(!empty($children)){
					$menu[count($menu)-1]['children'] = $children;
		        }
		      }
			return $menu;
	    } else {
	    	return false;
	    }

	}

	function getNodeParents($id) {
		try {
			$parent = $this->registry['db']->GetOne("SELECT parent FROM tbl_menu_structure WHERE id_poz = '".$id."'");
    	} catch (exception $e) {
			return false;
		}
		try {
			$res = $this->registry['db']->Execute("SELECT m.*,c.* FROM tbl_menu_structure as m LEFT JOIN tbl_content as c ON (m.id_poz = c.id_poz) WHERE m.id_poz = '".$parent."' AND c.lang = '".$this->registry->_lang."'");
    	} catch (exception $e) {
			return false;
		}
	    if ($res->_numOfRows > 0) {
	    	$data = $res->FetchRow();
			$parents = $this->getNodeParents($parent);
			if (!empty($parents)) $parentsArray = $parents;
			$parentsArray[] = $data;
			return $parentsArray;
	    } else {
	    	return null;
	    }
	}

	function saveMenuOrder($tbl,$parent=0){

		$record['ord'] = 0;
		$record['parent'] = $parent;
		foreach ($tbl as $key => $page) {
			$page_id = $page['id'];
			try {
				$this->registry['db']->AutoExecute("tbl_menu_structure",$record,"UPDATE","id_poz = '$page_id'");
	    	} catch (exception $e) {
				die('Wystąpił błąd');
			}
			$record['ord']++;
			if (is_array($page['children'])) {
				if (!$this->saveMenuOrder($page['children'], $page_id)) die('Wystąpił błąd');
	    	}
		}
		return true;
	}

	function getMapaArray($parent='0') {
    $link = $this->registry->lang_path.'/page/,';
		try {
			$res = $this->registry['db']->Execute("SELECT m.id_poz,m.type,c.name FROM tbl_menu_structure as m LEFT JOIN tbl_content as c ON (m.id_poz = c.id_poz) WHERE m.parent = '".$parent."' AND m.id_menu = ".$this->menu." AND c.lang = '".$this->registry->_lang."' AND c.publish = 1 ORDER BY m.ord ASC");
    	} catch (exception $e) {
			return false;
		}
	    if ($res->_numOfRows > 0) {
	    	$data = $res->GetRows();
	    	$menu = array();
    		foreach ($data as $dane) {
		        $id = $dane['id_poz'];
		        $menu[] = array('name' => $dane['name'],
                            'link' => $link.(($dane['type'] == 'kategoria' || $dane['type'] == 'Content')?'Content,'.$dane['id_poz']:$dane['type']),
                            'link_name' => (($dane['type'] == 'Content')?$dane['name']:'')
                            );
		        $children = array();
		       	$children = $this->getMapaArray($id);
		       	if ($dane['type'] == 'Wydarzenia' || $dane['type'] == 'Oferta') {
		       		if (class_exists($class = $dane['type'])) {
        				$module = new $class($this->registry);
        				if (is_callable(array($module, $action = 'getMapaArray')) != false) {
      		    			$modules = $module->$action();
      		    			$children = (!empty($children))?array_merge($children, $modules):$modules;
      		    		}
        			}
        		}
		      	if(!empty($children)) {
		      		$menu[count($menu)-1]['children'] = $children;
		        }
			}
			return $menu;
	    } else {
	    	return false;
	    }

	}
	
	function getMenuTree($parent='0') {
    	$link = $this->registry->lang_path.'/page/,';
		try {
			$res = $this->registry['db']->Execute("SELECT m.id_poz,m.type,c.name FROM tbl_menu_structure as m LEFT JOIN tbl_content as c ON (m.id_poz = c.id_poz) WHERE m.parent = '".$parent."' AND m.id_menu = ".$this->menu." AND c.lang = '".$this->registry->_lang."' AND c.publish = 1 ORDER BY m.ord ASC");
    	} catch (exception $e) {
			return false;
		}
	    if ($res->_numOfRows > 0) {
	    	$data = $res->GetRows();
	    	$menu = array();
    		foreach ($data as $dane) {
		        $id = $dane['id_poz'];
		        $menu[] = array('name' => $dane['name'],
                            'link' => $link.(($dane['type'] == 'kategoria' || $dane['type'] == 'Content')?'Content,'.$dane['id_poz']:$dane['type']),
                            'link_name' => (($dane['type'] == 'Content')?$dane['name']:'')
                            );
		        $children = array();
		       	$children = $this->getMenuTree($id);
		      	if(!empty($children)) {
		      		$menu[count($menu)-1]['children'] = $children;
		        }
			}
			return $menu;
	    } else {
	    	return false;
	    }

	}

}

?>