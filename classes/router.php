<?php

Class Router {
        private $registry;
		private $path;
        private $args = array();
        var $lang = array();

        function __construct(&$registry) {
                $this->registry = $registry;
        }

		function setPath($path) {
		        $path = trim($path, '/\\');
		        $path .= DIRSEP;

		        if (is_dir($path) == false) {
		                throw new Exception ('Invalid controller path: `' . $path . '`');
		        }

		        $this->path = $path;
		}

		function delegate() {
		        // Analyze route
		        $this->getController($file, $controller, $action, $args);

		        // File available?
		        if (is_readable($file) == false) {
	                //die ('404 Not Found');
	                $this->registry['template']->assign('page',array("val" => "Brak strony do wyświetlenia"));
	                $this->registry['template']->display('index.tpl');
	                die();
		        }

		        // Include the file
		        include ($file);

		        // Initiate the class
		        $class = 'Controller_' . $controller;
		        $controller = new $class($this->registry);

		        // Action available?
		        if (is_callable(array($controller, $action)) == false) {
	                $this->registry['template']->assign('page',array("val" => "Brak strony do wyświetlenia"));
	                $this->registry['template']->display('index.tpl');
	                die();
		        }

		        // Run action
		        $controller->$action();
		}



		private function getController(&$file, &$controller, &$action, &$args) {
		        $route = (empty($_GET['route'])) ? '' : $_GET['route'];

		        if (empty($route)) { $route = 'index'; }

		        // Get separate parts
		        $route = trim($route, '/\\');
		        $parts = explode('/', $route);
		        // Find right controller
		        $cmd_path = $this->path;
		        foreach ($parts as $part) {
		                $fullpath = $cmd_path . $part;

		                // Is there a dir with this path?
		                if (is_dir($fullpath)) {
		                        $cmd_path .= $part . DIRSEP;
		                        array_shift($parts);
		                        continue;
		                }

		                // Find the file
		                if (is_file($fullpath . '.php')) {
		                        $controller = $part;
		                        array_shift($parts);
		                        break;
	                	}

		        }

		        // Get action
		        $action = array_shift($parts);
		        if (empty($action)) { $action = 'index'; }

		        $file = $cmd_path . $controller . '.php';
		        $args = $parts;
		}


}
?>
