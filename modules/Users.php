<?php

Class Users {

	function __construct($registry) {
		$this->registry = $registry;
		$this->toolbar = new Toolbar($this->registry);
		$this->registry['template']->assign('toolbar', $this->toolbar->getPageToolbar('users'));
		$this->registry['template']->assign('inc','users');
		if (isset($_SESSION['success'])) {
			$this->registry['template']->assign('success', $_SESSION['success']);
			unset($_SESSION['success']);
		}
	}

	/** Panel Admina  **/

	function lista(){
		$res = $this->registry['db']->Execute("SELECT * FROM tbl_auth ORDER BY username DESC");
		$data = $res->GetRows();
		$this->registry['template']->assign('lista', $data);
	}

	function edit($args) {
		if (empty($args) || !is_numeric($args[0])) {
			$_SESSION['success'][] = array('level'=>'warning_box','info'=>'Nie wybrano użytkowanika do edycji');
			header('location: ' . main_path .'/Administrator/page/,modules,Users,lista');
			die;
		}
		if (!empty($_POST)) {
		  //sprawdzamy czy login ktory podal user nie istnieje
      $iloscUsr = $this->registry['db']->GetOne("SELECT COUNT(id_usr) as ile FROM tbl_auth WHERE username = '".$_POST['record']['username']."' AND id_usr <> ".$args[0]);
		  if ($iloscUsr > 0) {
        $_SESSION['success'][] = array('level'=>'warning_box','info'=>'Podany login jest już zajęty, wprowadź inny');
        header('location: ' . main_path .'/Administrator/page/,modules,Users,edit,'.$args[0]);
        die;      
      }
      //sprawdzamy czy usr chce zmienic swoje haslo
		  if (isset($_POST['record']['pass']) && $_POST['record']['pass'] != '') {
        if ($_POST['record']['pass'] != $_POST['record']['pass_ret']) {
          $_SESSION['success'][] = array('level'=>'warning_box','info'=>'Podane hasła nie są takie same');
          header('location: ' . main_path .'/Administrator/page/,modules,Users,edit,'.$args[0]);
          die;
        } 
        else {
          $record['password'] = $this->makePassword($_POST['record']['username'], $_POST['record']['pass']);
        }
      }
		  //koniec sprawdzania
			//usuwamy z tabilcy pozycje z haslami
			unset($_POST['record']['pass']);
			unset($_POST['record']['pass_ret']);
			
      foreach ($_POST['record'] as $k => $v)
				$record[$k] = stripslashes($v);
			$log = $this->update($args[0], $record);
			$_SESSION['success'][] = ($log === true) ? array('level'=>'information_box','info'=>'Zmiany zostały zapisane') :
																							 array('level'=>'warning_box','info'=>'Zmiany nie zostały zapisane.<br />Komunikat błędu :<br /<code>'.$log.'</code>');																				 
			header('location: ' . main_path .'/Administrator/page/,modules,Users,lista');
			die;
		}
		$res = $this->registry['db']->Execute("SELECT * FROM tbl_auth WHERE id_usr = ".$args[0]);
		$record = $res->GetRowAssoc(false);
		$this->registry['template']->assign('toolbar', $this->toolbar->getPageToolbar('users'));
		$this->registry['template']->assign('record', $record);
		$this->registry['template']->assign('inc','users_edit');
	}

	function add(){
    if (!empty($_POST)) {
		  //sprawdzamy czy login ktory podal user nie istnieje
      $iloscUsr = $this->registry['db']->GetOne("SELECT COUNT(id_usr) as ile FROM tbl_auth WHERE username = '".$_POST['record']['username']."'");
		  if ($iloscUsr > 0) {
        $_SESSION['success'][] = array('level'=>'warning_box','info'=>'Podany login jest już zajęty, wprowadź inny');
        header('location: ' . main_path .'/Administrator/page/,modules,Users,add');
        die;      
      }
      //sprawdzamy czy usr chce zmienic swoje haslo
		  if (isset($_POST['record']['pass']) && $_POST['record']['pass'] != '') {
        if ($_POST['record']['pass'] != $_POST['record']['pass_ret']) {
          $_SESSION['success'][] = array('level'=>'warning_box','info'=>'Podane hasła nie są takie same');
          header('location: ' . main_path .'/Administrator/page/,modules,Users,add');
          die;
        }
        else {
          
          $record['password'] = $this->makePassword($_POST['record']['username'], $_POST['record']['pass']);
          //die;
        }
      } else {
        $_SESSION['success'][] = array('level'=>'warning_box','info'=>'Wpisz hasło');
        header('location: ' . main_path .'/Administrator/page/,modules,Users,add');
        die;
      }
		  //koniec sprawdzania
			//usuwamy z tabilcy pozycje z haslami
			unset($_POST['record']['pass']);
			unset($_POST['record']['pass_ret']);
			
      foreach ($_POST['record'] as $k => $v)
				$record[$k] = stripslashes($v);
			$log = $this->insert($record);
			$_SESSION['success'][] = ($log === true) ? array('level'=>'information_box','info'=>'Nowy użytkownik został dodany') :
																							 array('level'=>'warning_box','info'=>'Użytkownik nie został dodany.<br />Komunikat błędu :<br /<code>'.$log.'</code>');																				 
			header('location: ' . main_path .'/Administrator/page/,modules,Users,lista');
			die;
		}
    $this->registry['template']->assign('toolbar', $this->toolbar->getPageToolbar('users'));
    $this->registry['template']->assign('inc','users_add');
	}

	function delete($args) {
		try {
			$this->registry['db']->Execute("DELETE FROM tbl_auth WHERE id_usr = ".$args[0]);
		} catch (exception $e) {
			$log = $this->registry['db']->ErrorMsg();
		}
		$_SESSION['success'][] = (isset($log)) ? array('level'=>'warning_box','info'=>'Wystąpił błąd przy usuwaniu użytkownika.<br />Komunikat błędu :<br /><code>'.$log.'</code>') :
												 array('level'=>'information_box','info'=>'Użytkownik został usunięty');
		header('location: ' . main_path .'/Administrator/page/,modules,Users,lista');
		die;
	}

	function blocked($args) {
		if (empty($args) || !is_numeric($args[0]) || !is_numeric($args[1])) {
			$_SESSION['success'][] = array('level'=>'warning_box','info'=>'Podano złe argumenty');
			header('location: ' . main_path .'/Administrator/page/,modules,Users,lista');
			die;
		}
		try {
			$this->registry['db']->Execute("UPDATE tbl_auth SET blocked = ".$args[1]." WHERE id_usr = ".$args[0]);
		} catch (exception $e) {
			$log = $this->registry['db']->ErrorMsg();
		}
		$_SESSION['success'][] = (isset($log)) ? array('level'=>'warning_box','info'=>'Wystąpił błąd.<br />Komunikat błędu :<br /><code>'.$log.'</code>') :
												 array('level'=>'information_box','info'=>(($args[1] == 1) ? 'Użytkownik został zablokowany' : 'Użytkownik został odblokowany'));
		header('location: ' . main_path .'/Administrator/page/,modules,Users,lista');
		die;
	}

	function update($id, $records) {
		try {
			$this->registry['db']->AutoExecute("tbl_auth",$records,"UPDATE","id_usr = ".$id);
		} catch (exception $e) {
			return $this->registry['db']->ErrorMsg();
		}
		return true;
	}
	
	function insert($records) {
		try {
			$this->registry['db']->AutoExecute("tbl_auth",$records,"INSERT");
		} catch (exception $e) {
			return $this->registry['db']->ErrorMsg();
		}
		return true;
	}
  
  /** Sekcja Autoryzacja **/

	function checkLogin ($login, $pass) {
		$this->registry['db']->debug = false;
		$sql = "SELECT * FROM tbl_auth WHERE username = '". $this->registry->quote_smart($login). "'" .
									   " AND password = '".$this->makePassword($login, $pass)."' AND blocked = 0";
	    $res =$this->registry['db']->Execute($sql);
	    if ( $res->_numOfRows == 1 ) {
      	  		$dane = $res->GetRowAssoc(false);
				$_SESSION['UserData'][md5(PREFIX)]['id']=$dane['id_usr'];
				$_SESSION['UserData'][md5(PREFIX)]['login']=$dane['username'];
				$_SESSION['UserData'][md5(PREFIX)]['name']=$dane['name'];
				$_SESSION['UserData'][md5(PREFIX)]['type']=$dane['type'];
         	return true;
	  	}
	    else {
	  	  return false;
	  	}
  }
  
  function makePassword($login, $pass) {
    return md5(PREFIX.$pass.$login);
  }


}
?>