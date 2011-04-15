<?php

Class Controller_Index Extends Controller_Base {

    function index($in = false) {
    	
    	$connected = false;
    	
		if (!isset($_SESSION['UserData']['host']) && !isset($_SESSION['UserData']['login'])){
			if (isset($_POST['host']) || isset($_POST['port']) || isset($_POST['login']) || isset($_POST['haslo']) || isset($_POST['db']) || isset($_POST['dbtype'])){
				$validate = $this->registry->validateConnection($_POST);
				if (!empty($validate['errors'])) {
					$this->registry['template']->assign('errors', $validate['errors']);
					$this->registry['template']->display('login.tpl');
					die;
				}
				$dbtype =	$validate['return']['dbtype'];
				$host =		$validate['return']['host'];
				$port =		$validate['return']['port'];
				$login =	$validate['return']['login'];
				$haslo =	$validate['return']['haslo'];
				$dbname =	$validate['return']['db'];
			}
		}
		
		elseif (!empty($_SESSION['UserData']['host']) && !empty($_SESSION['UserData']['login'])) {
			$dbtype =	$_SESSION['UserData']['dbtype'];
			$host =		$_SESSION['UserData']['host'];
			$port =		$_SESSION['UserData']['port'];
			$login =	$_SESSION['UserData']['login'];
			$haslo =	$_SESSION['UserData']['haslo'];
			$dbname =	$_SESSION['UserData']['db'];
		}
		if (class_exists($dbtype)) {
	    	//staramy sie stworzyć obiekt z konektora do bazy danych
			$connect = $this->beginConnection($dbtype,$host,$port,$login,$pass,$dbname);
			if (is_object($connect) === true){
				$connected = true;
				$_SESSION['UserData']['host'] = $host;
				$_SESSION['UserData']['port'] = $port;
				$_SESSION['UserData']['login'] = $login;
				$_SESSION['UserData']['haslo'] = $pass;
				$_SESSION['UserData']['dbtype'] = $dbtype;
				$_SESSION['UserData']['db'] = $dbname;
				//zalogowalismy sie do bazy
			}
			else {
				$this->registry['template']->assign('errors', array('error_msg' => array('Błąd połączenia do bazy:<br />'.$connect)));
			}
		}
		elseif (isset($dbtype)) {
			$this->registry['template']->assign('errors', array('error_msg' => array('Błąd klasy do połączenia z wybranym typem bazy')));
		}
		if ($connected) {
//          $this->registry['template']->config_load('localization.conf', 'pl');  //do wersji jezykowych
			if (!$in) {
				$this->mainView();
				$this->registry['template']->display('MainAplication.tpl');
			}
		}
		else {
			$this->registry['template']->display('login.tpl');
		}
    }
    
    
	function beginConnection($dbtype,$host, $port,$login,$pass,$dbname) {
    	$DbConnector = new $dbtype($this->registry);
		return $DbConnector->connect($this->registry,$host,$port,$login,$pass,$dbname);
    }
    
    function switchDatabase($args = array()) {
    	if (empty($args['dbname'])) {
    		//blad
    		$errors = array('error_msg' => array('Nie przekazano nazwy bazy danych'));
			$errors['error_msg'][] = print_r($args, true);
			$this->registry['template']->assign('errors', $errors);
			$this->registry['template']->display('error.tpl');
			die();
    	}
    	$_SESSION['UserData']['db'] = $args['dbname'];
    	$this->index();
    }
    
    
	/**
	 * Uruchomienie modułu struktury strony
	 */

    function mainView($args = array()){
    	$Content = new Controller_Content($this->registry);
    	$Content->setArgs($args);
    	$Content->index();
    }
    
    
    /**
     * 
     * Kontroler odpowiadający za zapytania ajaxowe
     * @param $args
     */
    
    function Ajax($args = array()) {
    	$this->index(true);
    	$Ajax = new Controller_Ajax($this->registry);
    	$Ajax->setArgs($args);
    	$Ajax->index();
    }
    
  
	function logout() {
		session_unset();
		session_destroy();
		echo '<script type="text/javascript">window.location = "index"</script>';
	}

}
