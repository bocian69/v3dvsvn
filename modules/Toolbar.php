<?php

Class Toolbar {

	function __construct($registry) {
		$this->registry = $registry;
	}

	function getPageToolbar($page) {
		switch($page) {
			case 'menu':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Struktura strony'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,newPageElement'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowy element'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,menu'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Drzewo menu'
		    			)
		    	);
				break;
			case 'wydarzenia':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Wydarzenia'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowe wydarzenie'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista wydarzeń'
		    			)
		    	);
				break;
			case 'oferta':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Oferta'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Oferta,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowa oferta'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Oferta,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista ofert'
		    			)
		    	);
				break;
			case 'wydarzenia_add':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Dodawanie Wydarzenia'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowe wydarzenie'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista wydarzeń'
		    			)
		    	);
				break;
			case 'wydarzenia_edit':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Edycja Wydarzenia'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,publish'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_go.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Publikuj'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,dpublish'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_delete.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Odpublikuj'
		    			)
		    	);
				break;
			case 'aktualnosci':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Dodawanie Aktualności'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Aktualnosci,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowa aktualność'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Aktualnosci,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista aktualności'
		    			)
		    	);
				break;
			case 'praca':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Dodawanie ofert pracy'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Praca,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowa Oferta'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Praca,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista ofert'
		    			)
		    	);
				break;
			case 'nasi_klienci':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Nasi Klienci'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,NasiKlienci,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowy klient'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,NasiKlienci,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista klientów'
		    			)
		    	);
				break;
			case 'galeria':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Galeria'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Galeria,add,galeria'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'image_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowa galeria'
		    			),
		    		/*
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Galeria,add,kategoria'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'image_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowa kategoria'
		    			),
		    		*/
		    		4 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Galeria,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_image.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista galerii'
		    			)
		    	);
				break;
			case 'users':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Użytkownicy'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Users,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'user_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowy użytkownik'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Users,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_user.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista użytkowników'
		    			)
		    	);
				break;
			case 'wydarzenia_add':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Dodawanie Wydarzenia'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'page_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowe wydarzenie'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Wydarzenia,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_page.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista wydarzeń'
		    			)
		    	);
				break;
			case 'languages':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Wielojęzyczność'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Languages,add'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'world_add.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Nowy język'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Languages,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'world.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista języków'
		    			)
		    	);
				break;
			case 'client':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Klienci'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Client,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_user.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista klientów'
		    			)
		    	);
				break;
			case 'orders':
				return array(
		    		0 => array(
		    				'class' => 'toolbar_name',
		    				'text'	=> 'Zamówienia'
		    			),
		    		1 => array(
		    				'class' => 'separator'
		    			),
		    		2 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Orders,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'package.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista bieżących zamowień'
		    			),
		    		3 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Orders,listaArch'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'package.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista zrealizowanych zamowień'
		    			),
		    		4 => array(
		    				'link'  => array(
		    							'id' 	=> '',
		    							'class' => '',
		    							'href'	=> main_path . '/Administrator/page/,modules,Client,lista'
		    						   ),
		    				'img'	=> array(
		    						   	'src'	=> img_path . 'folder_user.png',
		    						   	'title' => '',
		    						   	'alt'	=> ''
		    						   ),
		    				'label'	=> 'Lista klientów'
		    			)
		    	);
				break;
			default: break;
		}
	}
}

?>