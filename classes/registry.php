<?php

Class Registry Implements ArrayAccess {
        private $vars = array();


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

}

?>