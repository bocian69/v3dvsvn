<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>mplusp</title>
		<meta name="robots" content="all" />
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="pl" />
		<link rel="stylesheet" type="text/css" href="{$site_url}/css/main.css" media="all" />
		<link rel="stylesheet" type="text/css" href="{$site_url}/css/MenuMatic.css" media="all" />
		<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="{$site_url}/css/ie6.css" media="all" />
		<link rel="stylesheet" type="text/css" href="{$site_url}/css/MenuMatic-ie6.css" media="all" />
		<![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="{$site_url}/css/ie7.css" media="all" /><![endif]-->
		<script type="text/javascript" src="{$site_url}/js/mootools-1.2.1-core-nc.js"></script>
		<script src="{$site_url}/js/MenuMatic_0.68.3.js" type="text/javascript" charset="utf-8"></script>
		{literal}
		<script type="text/javascript" >
			window.addEvent('domready', function() {			
				var myMenu = new MenuMatic({ orientation:'vertical' });			
			});
		</script>
		{/literal}
	</head>
	<body>
		<div id="main">
			<ul id="top_bar">
				{include file="front/oneLvlMenu_top.tpl"}			
				<li><a href="{$site_url}/pl/" title="Wersja polska"><img src="{$main_path}/img/flag_pl.jpg" alt="Wersja polska" /></a></li>
				<li><a href="{$site_url}/en/" title="English version"><img src="{$main_path}/img/flag_uk.jpg" alt="English version" /></a></li>
				<li><a href="{$site_url}/ro/" title="Limba engleza"><img src="{$main_path}/img/flag_ro.jpg" alt="Limba engleza" /></a></li>
				
			</ul>
			<div id="top_content">
				<h1><a href="{$lang_path}/"><img src="{$main_path}/img/logo.jpg" alt="mplusp" id="logo" /></a></h1>
				<div id="space">
					{include file="front/MainMenu.tpl"}					
				</div>
				{if isset($client_authorized)}
				<div style="display: block;	padding-left: 70px;" id="client-auth">
				{$client_authorized}
				</div>
				{else}
				<form method="post" action="{$lang_path}/page/,Client,login" id="login">
					<div>
						<input type="text" name="email" value="{#email_address#}" class="text" onfocus="if(this.value=='{#email_address#}')this.value='';" onblur="if(this.value=='')this.value='{#email_address#}';" />
						<input type="password" name="password" value="{#password#}" class="text" onfocus="if(this.value=='{#password#}')this.value='';" onblur="if(this.value=='')this.value='{#password#}';" />
						<input type="image" src="{$main_path}/img/button.gif" />
					</div>
					<div>
						<a href="{$lang_path}/page/,Registration">{#sign_in#}</a>
						<a href="{$lang_path}/page/,Client,lostPassword">{#forgot_password#}</a>
					</div>
				</form>
				{/if}
			</div>
			<div id="middle">
				{include file="front/pathCreator.tpl"}
				<div id="left">
					<ul>
						{if isset($clientMenu)}
						{include file="front/clientMenu.tpl"}
						{else}
						{include file="front/oneLvlMenu.tpl"}
						{/if}
					</ul>
				</div>
				{if isset($template) eq false} {include file="front/404.tpl"}
				{else} {include file="front/$template.tpl"} {/if}
				{include file="front/right_col.tpl"}
			</div>
			<div class="clear"></div>
			<div id="bottom">
				<ul>
					{include file="front/oneLvlMenu_bottom.tpl"}
					<li><a href="{$lang_path}/page/,MapaSerwisu">{#site_map#}</a></li>
				</ul>
				<p>
					Copyright &#169; 2011
					<br />
					V3DV
				</p>
				<div class="clear"></div>
			</div>
		</div>
	</body>
</html>
