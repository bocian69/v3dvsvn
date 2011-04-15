<?php

Class Registry Implements ArrayAccess {
	private $vars = array();
	
	private $supportedDatabases = array('mysql','postgres');


	function set($key, $var) {
	        if (isset($this->vars[$key]) == true) {
	                throw new Exception('Unable to set var `' . $key . '`. Already set.');
	        }

	        $this->vars[$key] = $var;
	        return true;
	}

	function get($key) {
	        if (isset($this->vars[$key]) == false) {
	                return null;
	        }

	        return $this->vars[$key];
	}

	function remove($var) {
	        unset($this->vars[$key]);
	}


	function offsetExists($offset) {
	        return isset($this->vars[$offset]);
	}

	function offsetGet($offset) {
	        return $this->get($offset);
	}

	function offsetSet($offset, $value) {
	        $this->set($offset, $value);
	}

	function offsetUnset($offset) {
	        unset($this->vars[$offset]);
	}

	function pokapoka($dane) {
      	echo '<br /><div style="float:left;border:1px #000 solid;"><span style="color:red"><pre>'; print_r($dane); echo '</pre></font></div><br style="clear:both;" />';
    }
    
    function quote_smart($val) {
		if (get_magic_quotes_gpc()) $value = stripslashes($val);
		if (!is_numeric($val)) $value = mysql_real_escape_string($val);
		return $val;
	}
	
	function validateConnection($post) {
		$errors = array();
		$return = array();
		
		if (empty($post['host']))
			$errors['input_host'] = 'Podaj host';
		//portu nie wymagamy
		if (empty($post['login']))
			$errors['input_login'] = 'Podaj login';
		//hasla nie wymagamy w razie takiej konfiguracji serwera
		if (empty($post['db']))
			$errors['input_db'] = 'Podaj nazwę bazy';
		if (empty($post['dbtype']))
			$errors['select_dbtype'] = 'Wybierz typ bazy danych';
		
		if (!empty($errors))
			$errors['error_msg'][] = 'Wypełnij wszystkie pola';
		else 
			$return = $post;
			
		$post['dbtype'] = strtolower($post['dbtype']);
		if (!in_array($post['dbtype'], $this->supportedDatabases)) {
			$errors['error_msg'][] = 'Błąd, nieobsługiwana baza danych';
		}
		return array('return' => $return, 'errors' => $errors);
	}

}

?>