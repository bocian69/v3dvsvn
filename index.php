<?php
# Startup tasks (define constants, etc) bbb
require 'config/startup.php';

# Load template object
$template = new Template();
$registry->set('template', $template);

$template->assign('main_path', main_path);
$template->assign('img_path', img_path);
$template->assign('js_path', js_path);
$template->assign('style_path', style_path);
$template->assign('site_path', site_path);
$template->assign('site_url', SITE_URL);

# Load router
$router = new Router($registry);
$registry->set('router', $router);
//$router->setPath (site_path . 'controllers');
$router->setPath('controllers');
$router->delegate();


?>
