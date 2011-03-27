<?php

Abstract Class Controller_Base {
        protected $registry;

        function __construct($registry) {
                $this->registry = $registry;
        }

        abstract function index();

        static function pokapoka($dane){
        	echo '<br><font color="red"><pre>'; print_r($dane); echo '</pre></font><br>';
        }

        public function quote_smart($val) {
		   if (get_magic_quotes_gpc()) {
		       $value = stripslashes($val);
		   }
		   if (!is_numeric($val)) {
		       $value = mysql_real_escape_string($val);
		   }
		   return $val;
		}
		function secureData($value)
		{
			if ( $value == Null ) {
				$value = '';
			} else {
				$value = strip_tags($value);
				$value = htmlspecialchars($value);
			}
	   		return $value;
		}
}

?>