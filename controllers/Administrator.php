<?php

Class Controller_Administrator Extends Controller_Base {

	var $active_page = false;

	var $menu = array();

	var $klucze;

	var $del;

	var $up;

	var $down;

	var $pub;

	var $npub;

	function index(){
		$this->page();
	}

    function page() {
		if (!isset($_SESSION['UserData'][md5(PREFIX)]['id']) && !isset($_SESSION['UserData'][md5(PREFIX)]['login'])){
			if (isset($_POST['login']) && isset($_POST['haslo'])){
			  $Users = new Users($this->registry);
				if (!$Users->checkLogin($_POST['login'], $_POST['haslo'])){
				  $this->registry['template']->assign('login_error', true);
				}
			}
		}

		if (isset($_SESSION['UserData'][md5(PREFIX)]['id']) && isset($_SESSION['UserData'][md5(PREFIX)]['login'])) {
			//aktywne wykonywanie skryptow musi zaczac sie z tego miejsca
			$this->active_page = true;
			$this->route = explode(",",$_GET['route']);
//			echo 'przed sprawdzaniem->'.$this->registry->_lang.'<-';
			if (MULTILANGUAGE == false) {
				$_SESSION['UserData'][md5(PREFIX)]['lang'] = 'pl';
			}
			else {
				if (!isset($_SESSION['UserData'][md5(PREFIX)]['lang'])) {
					$_SESSION['UserData'][md5(PREFIX)]['lang'] = $this->registry->getDefaultLanguage();
				}
				$this->registry['template']->assign('available_languages', $this->registry->getLanguagesNames());
			}
			$this->registry->_lang = $_SESSION['UserData'][md5(PREFIX)]['lang'];
			$this->registry['template']->assign('MULTILANGUAGE', MULTILANGUAGE);
			$this->registry['template']->assign('lang', $this->registry->_lang);
//			echo 'po sprawdzaniu->'.$this->registry->_lang.'<-';
			$this->toolbar = new Toolbar($this->registry);
			$this->manageSuccess();
			$this->attachments = site_path.'attachments';
			$this->registry['template']->assign('attachments_path', main_path.'/attachments');
			//$this->registry['db']->debug=1;
			$this->registry['template']->assign('shipmentCountries', $this->registry->getShipmentCountries());
			$this->registry['template']->assign('languages',$this->registry->getLanguagesNames());
            $this->registry['template']->config_load('localization.conf', 'pl');
			if (count($this->route) == 1){
				$this->engageOrders();
			} else {
				if( method_exists($this, $this->route[1]) === true) {
					call_user_func( array(&$this, $this->route[1]) );
				}
				else{
					$this->engageOrders();
				}
			}
			$this->registry['template']->assign('admin_modules', $this->getModules());
			$this->registry['template']->display('Administrator.tpl');
		}
		else {
			$this->registry['template']->display('login.tpl');
		}
    }
    
    function engageOrders() {
    	$Orders = new Orders($this->registry);
    	if (is_callable(array($Orders, $action = 'lista')) != false) {    		
    		$Orders->$action();
    	}
    	else
    		$this->menu();
    }
    
    function setLanguage() {
    	if (isset($this->route[2]) && strlen($this->route[2]) == 2) {
    		$_SESSION['UserData'][md5(PREFIX)]['lang'] = $this->route[2];
    		$_SESSION['success'][] = array('level'=>'information_box','info'=>'Język został ustawiony');
    		header('location: ' . main_path .'/Administrator/page/');
			die;
    	}
    	else {
    		$_SESSION['success'][] = array('level'=>'warning_box','info'=>'Język nie został ustawiony');
    		header('location: ' . main_path .'/Administrator/page/');
			die;
    	}
    }

    /*
     * Przekierowanie do konkretnego modulu
     */

    function manageSuccess(){
    	if (isset($_SESSION['success'])) {
			$this->registry['template']->assign('success', $_SESSION['success']);
			unset($_SESSION['success']);
		}
    }

    function modules(){
    	if (count($this->route) >= 3)
    		$module = new $this->route[2]($this->registry);
    	if (count($this->route) >= 4 && is_callable(array($module, $action = $this->route[3])) != false) {
    		if (count($this->route) > 4) $module->$action(array_slice($this->route, 4));
    		else $module->$action();
    	}
    }

    function user(){

    	$dane = explode(",",$_GET['route']);

    	if(isset($_POST) && !empty($_POST)){
		    $dane = $_POST;
			$dane['password'] = md5(PREFIX.$dane['password'].$dane['username']);

	    		if(!empty($dane['id_usr'])){
	    			try {
	    				$this->registry['db']->AutoExecute('tbl_auth', $dane, 'UPDATE', "id_usr = '".$dane['id_usr']."'");
	    			} catch (exception $e) {
						$this->registry['template']->assign('errorA', 'Błąd, profil nie zaostal zaktualizowany');
					}
	    		}
	    		else{
	    			$res = $this->registry['db']->Execute("SELECT * FROM tbl_auth WHERE username = '".$dane['username']."'");
					if($res->_numOfRows == 1){
						$this->registry['template']->assign('errorA', 'Podany login juz istnieje');
					}
					else{
						try {
							$this->registry['db']->AutoExecute('tbl_auth', $dane, 'INSERT');
				    	} catch (exception $e) {
							$this->registry['template']->assign('errorA', 'Błąd, profil nie zaostal zaktualizowany');
						}
					}
				}
    	}
		$sql = "SELECT id_usr, username, name, type FROM tbl_auth ";

		if($_SESSION['UserData'][md5(PREFIX)]['type'] == '1'){
    		if(isset($dane['3']) && !empty($dane['3']) && is_numeric($dane['3'])){
    			$sql .= 'WHERE id_usr = '.$dane['3'];
    			$fetch = true;
    		}
		}
		else{
			$sql .= 'WHERE id_usr = '.$_SESSION['UserData'][md5(PREFIX)]['id'];
			$fetch = true;
		}

    	$this->registry['db']->SetFetchMode(ADODB_FETCH_ASSOC);
		try {
			$res = $this->registry['db']->Execute($sql);
    	} catch (exception $e) {
    		$this->registry['template']->assign('errorA', 'Wystąpił błąd');
		}
		($fetch) ? $users = $res->FetchRow() : $users = $res->GetRows();
    	$this->registry['template']->assign('users',$users);
		$this->registry['template']->assign('inc','user');
    }

	function delUser(){


		//czy usuwamy z konta admina
		if($_SESSION['UserData'][md5(PREFIX)]['type'] == '1'){
			$dane = explode(",",$_GET['route']);
			$id = $this->quote_smart($dane['3']);
			// nie mozna skasowac glownego admina ani siebie samego
			if($id != 1 && $_SESSION['UserData'][md5(PREFIX)]['id'] != $id && is_numeric($id)){
				try {
					$this->registry['db']->Execute("DELETE FROM tbl_auth WHERE id_usr = '".$id."'");
		    	} catch (exception $e) {
					die('Wystąpił błąd: nie można usunąć.');
				}

			}
		}
		else
			die('Nie masz prawa!');

		echo '<script type="text/javascript">window.location = "'. main_path .'/Administrator/page/,user,pl"</script>';

	}
	function editUser(){

		$this->registry['template']->assign('inc','user');

	}


    function deletePage(){
		$page_id = $this->route[2];
		$this->registry['db']->SetFetchMode(ADODB_FETCH_ASSOC);
		if($page_id != 0 && is_numeric($page_id)){
			$this->deleteRecursive($page_id);
		}
		die('1');
    }

    function deleteRecursive($parent){
		try {
			$res = $this->registry['db']->Execute("SELECT * FROM tbl_menu_structure WHERE parent = '".$parent."'");
    	} catch (exception $e) {
			die('Wystąpił błąd');
		}
	    $data = $res->GetRows();
		foreach ($data as $val){
			$this->deleteRecursive($val['id_poz']);
		}

		try {
			$attach = $this->registry['db']->Execute("SELECT attach FROM tbl_content WHERE id_poz = '".$parent."'");
    	} catch (exception $e) {
			die('Wystąpił błąd');
		}
		$data = $res->GetRows();
		foreach ($data as $val){
			if ($val['attach'] != '') @unlink($this->attachments.'/'.$val['attach']);
		}

    	try {
			$this->registry['db']->Execute("DELETE FROM tbl_menu_structure WHERE id_poz = '".$parent."'");
			$this->registry['db']->Execute("DELETE FROM tbl_content WHERE id_poz = '".$parent."'");
    	} catch (exception $e) {
			die('Wystąpił błąd');
		}
    }

	function setHomePage($ajax=true){
		$record['onmain'] = 0;
		$page_id = $this->quote_smart($this->route[2]);

		try {
			$this->registry['db']->AutoExecute("tbl_content",$record,"UPDATE","lang = '".$this->registry->_lang."'");
    	}
    	catch (exception $e) {
    		if ($ajax) die('Wystąpił błąd'); else return false;
		}

		$record['onmain'] = 1;
		try {
			$this->registry['db']->AutoExecute("tbl_content",$record,"UPDATE","id_poz = '".$page_id."' AND lang = '".$this->registry->_lang."'");
    	}
    	catch (exception $e) {
    		if ($ajax) die('Wystąpił błąd'); else return false;
		}
		if ($ajax) die('1'); else return true;
	}

	function publishPage(){

		$page_id = $this->quote_smart($this->route[2]);
		$record['publish'] = $this->route[3];

		if (!is_numeric($record['publish']))
			die('Wystąpił błąd');

		try {
			$this->registry['db']->AutoExecute("tbl_content",$record,"UPDATE","id_poz = '".$page_id."' AND lang = '".$this->registry->_lang."'");
    	}
    	catch (exception $e) {
    		die('Wystąpił błąd');
		}

		die('1');
	}

	function unblockPage(){
		$record['status'] = 0;
		$page_id = $this->quote_smart($this->route[2]);
		try {
			$this->registry['db']->AutoExecute("tbl_content",$record,"UPDATE","id_poz = '".$page_id."' AND lang = '".$this->registry->_lang."'");
    	}
    	catch (exception $e) {
    		die('Wystąpił błąd');
		}
		die('1');
	}

	function cancelSave(){
		$record['status'] = 0;
		$page_id = $this->quote_smart($this->route[2]);
		try {
			$this->registry['db']->AutoExecute("tbl_content",$record,"UPDATE","id_poz = '".$page_id."' AND lang = '".$this->registry->_lang."'");
    	}
    	catch (exception $e) {
    		die('Wystąpił błąd');
		}
		$this->menu();
	}

	function save(){

		$dane = explode(",",$_GET['route']);

    	$page_id = $dane[3];

		$ajaxfilemanager = $_POST['ajaxfilemanager'];

		$record['name'] = stripslashes($_POST['name']);
		$record['val'] = stripslashes($ajaxfilemanager);
		$record['last_modyf'] = time();
		$record['status'] = 0;

		$this->registry['db']->SetFetchMode(ADODB_FETCH_ASSOC);

		if(!empty($dane[3])){

			try {
				$res = $this->registry['db']->Execute("SELECT name FROM tbl_content WHERE id_poz = '".$page_id."' AND lang = '".$this->registry->_lang."'");
	    	} catch (exception $e) {
				echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
				adodb_backtrace($e->gettrace());
				exit;
			}

			if ($res->_numOfRows > 0) {
				try {
					$this->registry['db']->AutoExecute("tbl_content",$record,"UPDATE","id_poz = '$page_id' AND lang = '".$this->registry->_lang."'");
		    	} catch (exception $e) {
					echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
					adodb_backtrace($e->gettrace());
					exit;
				}
			} else {
				$record['id_poz'] = $page_id;
				$record['lang'] = $this->registry->_lang;
				try {
					$this->registry['db']->AutoExecute("tbl_content",$record,"INSERT");
		    	} catch (exception $e) {
					echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
					adodb_backtrace($e->gettrace());
					exit;
				}
			}
		}
		else{

			try {
				$res = $this->registry['db']->Execute("SELECT ord FROM tbl_menu_structure where parent = 0 ORDER BY ord DESC LIMIT 1");
	    	} catch (exception $e) {
				echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
				adodb_backtrace($e->gettrace());
				exit;
			}
			$order = $res->GetRowAssoc(false);

			$menu['ord'] = $order['ord'] + 1;
			$menu['parent'] = 0;
			try {
				$this->registry['db']->AutoExecute("tbl_menu_structure",$menu,"INSERT");
	    	} catch (exception $e) {
				echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
				adodb_backtrace($e->gettrace());
				exit;
			}
			$page_id = $this->registry['db']->Insert_ID();
			$record['id_poz'] = $page_id;
			$record['lang'] = $this->registry->_lang;
			try {
				$this->registry['db']->AutoExecute("tbl_content",$record,"INSERT");
	    	} catch (exception $e) {
				echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
				adodb_backtrace($e->gettrace());
				exit;
			}
		}

		if(!empty($_POST['kat'])){
			$type['type'] = 'kategoria';
		}
		else{
			$type['type'] = 'Content';
		}
		try {
			$this->registry['db']->AutoExecute("tbl_menu_structure",$type,"UPDATE","id_poz = '$page_id'");
    	} catch (exception $e) {
			echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
			adodb_backtrace($e->gettrace());
			exit;
		}

		echo '<script>document.location = "'. main_path.'/Administrator/";</script>';
	}

	function emaile() {


    	$this->registry['db']->SetFetchMode(ADODB_FETCH_ASSOC);
		try {
			$res = $this->registry['db']->Execute("SELECT * FROM tbl_emaile");
    	} catch (exception $e) {
			echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
			adodb_backtrace($e->gettrace());
			exit;
		}

		$this->registry['template']->assign('emaile', $res->GetRowAssoc(false));
    	$this->registry['template']->assign('inc', 'emaile');
    }

    function saveEmaile() {

		$record['first'] = $_POST['first'];
		$record['second'] = $_POST['second'];
		$record['third'] = $_POST['third'];

		try {
			$res = $this->registry['db']->AutoExecute("tbl_emaile",$record,"UPDATE","1");
    	} catch (exception $e) {
			echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
			adodb_backtrace($e->gettrace());
			exit;
		}
		$this->emaile();
    }

    function deleteAttach($reload=true) {
    	if (empty($this->route[2]) || !is_numeric($this->route[2])) {
    		$_SESSION['success'][] = array('level'=>'warning_box','info'=>'Błędny identyfikator');
    		header('location: ' . main_path .'/Administrator/page/,menu');
			die;
		}
		$attach = $this->registry['db']->GetOne("SELECT attach FROM tbl_content WHERE id_poz = '".$this->route[2]."' AND lang = '".$this->registry->_lang."'");
		try {
			$dbRm = $this->registry['db']->AutoExecute("tbl_content",array('attach'=>''),"UPDATE","id_poz = '".$this->route[2]."' AND lang = '".$this->registry->_lang."'");
    	} catch (exception $e) {
			$_SESSION['success'][] = array('level'=>'warning_box','info'=>'Załącznik nie został usunięty');
		}
		if ($attach != '' && $dbRm == true) {
			$unl = unlink($this->attachments.'/'.$attach);
			if (!$unl) $_SESSION['success'][] = array('level'=>'warning_box','info'=>'Załącznik nie został usunięty');
		}
		if ($reload) {
			header('location: ' . main_path .'/Administrator/page/,edit,'.$this->route[2]);
			die;
		}
    }

    function uploadAttach($file) {
		$handle = new Upload($file);
		if ($handle->uploaded) {
			$handle->Process($this->attachments);
			if ($handle->processed) {
				$files['attach'] = $handle->file_dst_name;
			}
			else return $handle->error;
		} else return $handle->error;
		return $files;
	}

    function edit(){
		if (empty($this->route[2]) || !is_numeric($this->route[2])) {
			$_SESSION['success'][] = array('level'=>'warning_box','info'=>'Nie wybrano pozycji do edycji');
			header('location: ' . main_path .'/Administrator/page/,menu');
			die;
		}
    	$page_id = $this->route[2];
    	if (!empty($this->route[3]) || strlen($this->route[3]) == 2) {
    		$lang = $this->route[3];
    	}
    	else {
    		$lang = $this->registry->_lang;
    	}
    	if (!empty($_POST)) {
			foreach ($_POST['record'] as $k => $v)
				$record[$k] = stripslashes($v);
			$record['status'] = 0;
			$structure = $_POST['record_structure'];
			if (isset($_FILES['attach']) && $_FILES['attach']['error'] == 0) {
				$this->deleteAttach(false);
				$attach = $this->uploadAttach($_FILES['attach']);
				if (is_array($attach) == true) $record = array_merge($record, $attach);
				else $_SESSION['success'][] = array('level'=>'warning_box','info'=>'Załącznik nie został dodany.<br />Komunikat: '.$attach);
			}
			if ($record['onmain'] == 1) $home = $this->setHomePage(false);
			$this->registry['db']->AutoExecute("tbl_menu_structure",$structure,"UPDATE","id_poz = $page_id");
			//sprawdzanie czy juz taki content w danym jezyku istnieje
			$exist = $this->registry['db']->GetOne("SELECT count(*) as ile FROM tbl_content WHERE id_poz = '".$page_id."' AND lang = '".$lang."'");
			if ($exist > 0) {
				$this->registry['db']->AutoExecute("tbl_content",$record,"UPDATE","id_poz = $page_id AND lang = '".$lang."'");
			}
			else {
				$record['id_poz'] = $page_id;
				$record['lang'] = $lang;
				$this->registry['db']->AutoExecute("tbl_content",$record,"INSERT");
			}
			$_SESSION['success'][] = array('level'=>'information_box','info'=>'Zmiany zostały pomyślnie zapisane');
			if ($_POST['reload'] == 1) {
				header('location: ' . main_path .'/Administrator/page/,menu');
				die;
			}
			$this->manageSuccess();
		}
		try {
			$res = $this->registry['db']->Execute("SELECT v.*, m.* FROM tbl_menu_structure as m LEFT JOIN tbl_content as v ON (v.id_poz = m.id_poz AND v.lang = '".$lang."') WHERE m.id_poz = '".$page_id."' ");
    	} catch (exception $e) {
			echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
			adodb_backtrace($e->gettrace());
			exit;
		}
		$dane = $res->GetRowAssoc(false);
		
		$this->registry['template']->assign('lang', $lang);
		$this->registry['template']->assign('record', $dane);
		$this->registry['template']->assign('kategorie', $this->getKategorie($dane['parent']));
		$this->registry['template']->assign('modules', $this->getMenuModules());
		$this->registry['template']->assign('toolbar', $this->toolbar->getPageToolbar('menu'));
		$this->registry['template']->assign('tinymce',true);
		$this->registry['template']->assign('id', $page_id);
    	$this->registry['template']->assign('inc', 'edit');
    }

    function naprostuj(){
		if (!$this->active_page) { $this->page(); };
		die();
    }

    /** Sekcja Zawartość strony **/

	function changeSelectedMenu() {
		if (isset($this->route[2]) && is_numeric($this->route[2])) {
			$_SESSION['selectedMenu'] = $this->route[2];
		}
		header('location: ' . main_path .'/Administrator/page/,newPageElement');
		die;
	}

    function newPageElement() {
    	if (!empty($_POST)) {
			if (!isset($_SESSION['selectedMenu']) && !is_numeric($_SESSION['selectedMenu'])) {
				header('location: ' . main_path .'/Administrator/page/');
				die;
			}
			$structure = $_POST['record_structure'];
			$structure['id_menu'] = $_SESSION['selectedMenu'];
			$ord = $this->registry['db']->GetOne("SELECT ord FROM tbl_menu_structure WHERE parent = '".$structure['parent']."' ORDER BY ord DESC");
			$structure['ord'] = $ord+1;
			$this->registry['db']->AutoExecute("tbl_menu_structure",$structure,"INSERT");
			$id = $this->registry['db']->Insert_ID();
			foreach ($_POST['record'] as $k => $v)
				$record[$k] = stripslashes($v);
			$record['lang'] = $this->registry->_lang;
			$record['id_poz'] = $id;
			$this->registry['db']->AutoExecute("tbl_content",$record,"INSERT");
			$_SESSION['success'][] = array('level'=>'information_box','info'=>'Element został dodany');
			if ($structure['type']=='Content')
				header('location: ' . main_path .'/Administrator/page/,edit,'.$id);
			else
				header('location: ' . main_path .'/Administrator/page/');
			die;
		}
		$Menu = new Menu($this->registry);
		$selectedMenu = isset($_SESSION['selectedMenu']) ? $_SESSION['selectedMenu'] : '';
	    $this->registry['template']->assign('menus', $Menu->getMenus($selectedMenu));
	    $this->registry['template']->assign('selectedMenu', $selectedMenu);
    	$this->registry['db']->SetFetchMode(ADODB_FETCH_ASSOC);
		$this->registry['template']->assign('kategorie', $this->getKategorie());
    	$this->registry['template']->assign('toolbar', $this->toolbar->getPageToolbar('menu'));
    	$this->registry['template']->assign('modules', $this->getMenuModules());
    	if($selectedMenu != '')
			$this->registry['template']->assign('tinymce',true);
    	$this->registry['template']->assign('inc', 'newPageElement');
    }

    /** Sekcja Struktura strony **/

	/*
	 * Metoda do pobierania listy modułów uzywanych w zarzadzaniu menu w systemie
	 */

    function getMenuModules() {
    	$modules = array(
    				'kategoria'=>'Kategoria Artykułów',
    				'Content'=>'Artykuł'
//    				,'Galeria'=>'Galeria',
//    				'Wydarzenia'=>'Wydarzenia',
//    				'Oferta'=>'Oferta',
//    				'Praca'=>'Praca',
//    				'NasiKlienci'=>'Nasi Klienci'
    				);
    	return $modules;
    }
    
	/*
	 * Metoda do pobierania listy modułów w systemie
	 * Narazie statyczna, poznie zrobi sie oparta o baze danych lub XML
	 */

    function getModules() {
    	return $this->registry->getModules();
    }

	/*
	 * Metoda do pobierania wszystkich gałęzi kategorii
	 * Zwraca je w postaci sformatowanych opcji dla selecta
	 *
	 */

    function getKategorie($selected=false, $parent = 0, $node = 0) {
		$tree = '';
		++$node;
		$res = $this->registry['db']->Execute("SELECT s.*, IF(c.name IS NULL ,CONCAT('brak nazwy - [',c_pl.name,']'),c.name) as name FROM tbl_menu_structure as s LEFT JOIN tbl_content as c ON (s.id_poz = c.id_poz AND c.lang = '".$this->registry->_lang."') LEFT JOIN tbl_content as c_pl ON (s.id_poz = c_pl.id_poz AND c_pl.lang = 'pl') WHERE s.id_menu = '".$_SESSION['selectedMenu']."' AND s.parent = '".$parent."' AND s.type='kategoria' ORDER BY s.ord ASC");
		$rows = $res->GetRows();
		if (!empty($rows)) {
			foreach ($rows as $row) {
				$tree .= '<option value="'.$row['id_poz'].'" '.(($selected && $selected == $row['id_poz'])?'selected="selected"':'').'>';
				if ($node > 1) {
					for($i=0;$i<=(($node-2)*4);$i++) $tree .= '&nbsp;';
					$tree .= '|_ ';
				}
				$tree .= $row['name'].'</option>';
				$tree .= $this->getKategorie($selected, $row['id_poz'], $node);
			}
		}
		else {
			return null;
		}
		return $tree;
	}

    /*
	 * Uruchomienie modułu struktury strony
	 */

    function menu(){
    	$Menu = new Menu($this->registry);
    	$this->registry['template']->assign('toolbar', $this->toolbar->getPageToolbar('menu'));
    	$this->registry['template']->assign('selectedMenu', $_SESSION['selectedMenu']);
    	$this->registry['template']->assign('menus', $Menu->getMenus($_SESSION['selectedMenu']));
		$this->registry['template']->assign('inc', 'menu');
    }

    /*
	 * AJAX - Zapisywanie struktury menu w postaci JSON
	 */

    function saveMenuJSON(){

		if(is_array($_POST['menuJSON']['items'])){
	    	$Menu = new Menu($this->registry);
	    	$Menu->setMenu($_SESSION['selectedMenu']);
			$Menu->saveMenuOrder($_POST['menuJSON']['items']);
		}
		else {
			die('Wystąpił błąd');
		}
		die('1');
	}

	/*
	 * AJAX - Wysylanie struktury menu w postaci JSON
	 */

  function menuJSON(){
    $_SESSION['selectedMenu'] = $this->route[2];
    $Menu = new Menu($this->registry);
    $Menu->setMenu($this->route[2]);
    $menutbl_nagl = $Menu->menuJSON();
    echo $menutbl_nagl;
    die();
  }

	function getPageAjax () {

		$dane = explode(",",$_GET['route']);
		$id = $dane[3];

	    $this->registry['db']->SetFetchMode(ADODB_FETCH_ASSOC);
	    try {
			$res = $this->registry['db']->Execute("SELECT val FROM tbl_content WHERE id_poz = '".$id."' AND lang = '".$this->registry->_lang."'");
    	} catch (exception $e) {
			echo '<HR>error: '.$this->registry['db']->ErrorMsg().'<BR><HR>';
			adodb_backtrace($e->gettrace());
			exit;
		}

	    $ret = $res->GetRowAssoc(false);
	    echo $ret['val'];
	    exit;
	}
  
  function logout() {
		session_unset();
		session_destroy();
		echo '<script type="text/javascript">window.location = "'. main_path .'/Administrator/"</script>';
	}

}

?>