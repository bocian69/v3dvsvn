<?php
Class Template extends Smarty {
        private $registry;
		private $vars = array();

        function __construct() {
				$this->compile_check = true;
				$this->debugging = false;
				$this->template_dir = site_path.'templates';
				$this->compile_dir = smarty_path.'templates_c';
				$this->cache_dir = smarty_path.'cache';
				$this->config_dir = site_path;
		}
}
?>