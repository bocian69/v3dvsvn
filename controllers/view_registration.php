<?php

Class View_Registration {

	public $args = null;
	private $moduleName = 'Registration';
	public $captchaImageSize = array('width'=>200,'height'=>60);

	function __construct(&$registry, $args) {
		$this->registry =& $registry;
		$this->args = $args;
		$this->registry->PathCreator->addStep($this->registry['template']->get_config_vars('registration'), $this->registry->PathCreator->prepareArgs($this->moduleName));
		$this->registry['template']->assign('shipmentCountries', $this->registry->getShipmentCountries());
		$this->registry['template']->assign('captchaImageSize', $this->captchaImageSize);
		$this->registry['template']->assign('template', 'Registration');
	}
	
	function activate() {
		if (empty($this->args[1])) {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_7'));
			
		}
		$client = $this->checkToken($this->registry->quote_smart($this->args[1]));
		if (!$client) {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_8'));
		}
		else {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_9'));
			$_SESSION['client'] = $client;
			$client = new Client($this->registry);
			$client->showWhoIsLogged();
			$client->packageExist();
		}
		$this->registry['template']->assign('title', 'Aktywacja konta');
		$this->registry['template']->assign('template', 'blank');
		
	}
	
	function checkToken($token) {
		try {
		$status = $this->registry['db']->GetOne("SELECT count(*) as ile FROM tbl_clients WHERE token = ?", array($token));
    	} catch (exception $e) {
			return false;
		}
		if ($status==0) {
			return false;
		}
		elseif ($status==1) {
			try {
				$res = $this->registry['db']->Execute("SELECT * FROM tbl_clients WHERE token = ?", array($token));
				$this->registry['db']->Execute("UPDATE tbl_clients SET token = '' WHERE token = ?", array($token));
	    	} catch (exception $e) {
				return false;
			}
			return $res->FetchRow();
		}
		return false;
	}
	
	function validate() {
		if (empty($_POST)) {
			header('location: ' . $this->registry->lang_path .'/page/,'.$this->moduleName);
			die;
		}
		$status = true;
		$dostepne = array('company_name','sex','first_name','surname','address','city','post_code','country','phone','email','lang','nip');
		$obowiazkowe = array('sex','first_name','surname','address','city','post_code','country','phone','email');
		if (!isset($_POST['rules']) || $_POST['rules'] != 1) {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_1'));
			return false;
		}
		if (empty($_POST['captcha']) || $this->validateCaptcha($_POST['captcha']) == false) {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_2'));
			return false;
		}
		$pola_rejestracji = array();
		foreach($_POST['registration'] as $pole => $wartosc) {
			if (!in_array($pole,$dostepne)) {
				//hack attempt
				continue;
			}
			if (in_array($pole,$obowiazkowe) && $wartosc == '') {
				$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_3'));
				return false;
			}
			if (is_array($wartosc)) {
				$wartosc_temp = '';
				foreach ($wartosc as $w) {
					$wartosc_temp .= $w.' ';
				}
				$wartosc = $wartosc_temp;
			}
			$pola_rejestracji[$pole] = $this->registry->quote_smart($wartosc);
		}
		//upewnienie sie ze jest ustawiony jezyk, bo sie posypia linki
		if (empty($pola_rejestracji['lang'])) $pola_rejestracji['lang'] = $this->registry->_lang;
		//dodanie daty rejestracji
		$pola_rejestracji['register_date'] = NULL; 
		if (!empty($_POST['password']) && !empty($_POST['confirm_password']) && $_POST['password'] == $_POST['confirm_password']) {
			$pola_rejestracji['password'] = md5($_POST['password']);
			$pola_rejestracji['token'] = md5('token'.$pola_rejestracji['email'].$_POST['password']);
		} else {
			unset($_POST['password']);
			unset($_POST['confirm_password']);
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_4'));
			return false;
		}
		if (!$this->registry->isEmailValid($pola_rejestracji['email'])) {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_5'));
			return false;
		}
		if (!$this->isEmailUnique($pola_rejestracji['email'])) {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_6'));
			return false;
		}	
		$Client = new Client($this->registry);
		if ($Client->addClient($pola_rejestracji)) {
			$mail = new Mail();
			$message = $this->registry['template']->get_config_vars('mail_registration_html_1');
			$message .= $pola_rejestracji['token'];
			$message .= $this->registry['template']->get_config_vars('mail_registration_html_2');
			$altMessage = $this->registry['template']->get_config_vars('mail_registration_plain_1');
			$altMessage .= $pola_rejestracji['token'];
            if ($mail->sendMail($pola_rejestracji['email'], $this->registry['template']->get_config_vars('account_activation'), $message, $altMessage)) {
				$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_10'));
				$this->registry['template']->assign('title', $this->registry['template']->get_config_vars('registration_form'));
				$this->registry['template']->assign('template', 'blank');
			}
			else
				$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_error_1'));
		}
		else {
			$this->registry['template']->assign('status', $this->registry['template']->get_config_vars('message_registration_error_2'));
		}
	}
	
	function isEmailUnique($email) {
		try {
		$status = $this->registry['db']->GetOne("SELECT count(*) as ile FROM tbl_clients WHERE email = ?", array($email));
    	} catch (exception $e) {
			die('Database Query Error!');
		}
		return (($status==0)?true:false);
	}
		
	function captcha() {
		require 'libraries/captcha/php-captcha.inc.php';
		$aFonts = array('libraries/captcha/fonts/VeraBd.ttf', 'libraries/captcha/fonts/VeraIt.ttf', 'libraries/captcha/fonts/Vera.ttf');
		$oVisualCaptcha = new PhpCaptcha($aFonts, $this->captchaImageSize['width'], $this->captchaImageSize['height']);
		$oVisualCaptcha->Create();
	}
	
	function validateCaptcha($code='') {
		require 'libraries/captcha/php-captcha.inc.php';
		return PhpCaptcha::Validate($code);
	}

}

?>