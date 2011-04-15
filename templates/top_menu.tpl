<div id="top_menu">
	<div id="tm_left">
		<div id="tm_right">
			<div class="padding">
				<ul class="adxm menu">
					<li><a href="#">Menu 1</a>
						<ul>
						<li><a href="{$main_path}/menu2">Menu 2</a></li>
						<li><a href="{$main_path}/menu3">Menu 3</a></li>
						</ul>
					</li>
					<li><a href="{$main_path}/logout">Wyloguj</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

{*
<div id="menuDiv"></div>

<script type="text/javascript">

	var menuModel = new DHTMLSuite.menuModel();

	DHTMLSuite.configObj.setCssPath('{$style_path}');
	DHTMLSuite.configObj.setImagePath('{$img_path}/images_dhtmlsuite/');
	DHTMLSuite.commonObj.setCssCacheStatus(false);
	menuModel.addItem(1,'Podgląd strony','{$img_path}cristal/menu/home.png','',false,'','window.open("{$main_path}", "_blank");');
	menuModel.addSeparator();
	menuModel.addItem(2,'Menu i podstrony','{$img_path}cristal/menu/pages.png','',false);
	menuModel.setSubMenuWidth(2,100);
		menuModel.addItem(5,'Przeglądaj','{$img_path}cristal/gif/page.gif','{$main_path}/Administrator/page/,menu,{$lang}',2);
		menuModel.addItem(6,'Dodaj','{$img_path}cristal/gif/page_add.gif','{$main_path}/Administrator/page/,edit,{$lang}',2);
	menuModel.addSeparator();
	menuModel.addItem(3,'Konfiguracja','{$img_path}cristal/menu/configure.png','',false);
	menuModel.setSubMenuWidth(3,150);
		{if $smarty.session.UserData.type eq 1}
		menuModel.addItem(7,'Użytkownicy','{$img_path}cristal/gif/users.gif','{$main_path}/Administrator/page/,user,{$lang}',3);
		menuModel.setSubMenuWidth(7,100);
		{else}
			menuModel.addItem(8,'Edytuj profil','{$img_path}cristal/users/list_user.png','{$main_path}/Administrator/page/,user,{$lang}',3);
		{/if}
		menuModel.addItem(13,'formularze ze strony','{$img_path}cristal/basic/popup.png','{$main_path}/Administrator/page/,formularze,{$lang}',3);
		menuModel.addItem(14,'E-maile','','{$main_path}/Administrator/page/,emaile,{$lang}',3);
	menuModel.addSeparator();
	menuModel.addItem(4,'Język','{$img_path}cristal/flags/{$lang}.png','',false);
	menuModel.setSubMenuWidth(4,100);
	menuModel.addSeparator();
	menuModel.addItem(10,'Polski','{$img_path}cristal/flags/pl.gif','{$main_path}/Administrator/page/,menu,pl',4);
	menuModel.addItem(11,'Angielski','{$img_path}cristal/flags/en.gif','{$main_path}/Administrator/page/,menu,en',4);
	menuModel.addItem(12,'Niemiecki','{$img_path}cristal/flags/de.gif','{$main_path}/Administrator/page/,menu,de',4);

	menuModel.init();

	var menuBar = new DHTMLSuite.menuBar();
	menuBar.addMenuItems(menuModel);
	menuBar.setTarget('menuDiv');
	menuBar.init();

</script>
*}

