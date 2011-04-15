<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Panel Administracyjny</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<link href="{$style_path}mainstyles.css" rel="stylesheet" type="text/css" media="all"/>
<link href="{$style_path}typography.css" rel="stylesheet" type="text/css" media="all"/>
<link href="{$style_path}columns.css" rel="stylesheet" type="text/css" media="all"/>
<link href="{$style_path}topmenu.css" rel="stylesheet" type="text/css" media="screen, tv, projection"/>

<script type="text/javascript" src="{$js_path}advajax.js"></script>
<script type="text/javascript">
var MainPath = "{$main_path}";
var ImgPath = "{$img_path}";
var StylePath = "{$style_path}";
var selectedMenu = "{$selectedMenu}";
</script>
{if $inc eq 'menu'}
<script type="text/javascript" src="{$js_path}jquery/jquery-1.1.4.js"></script>
<script type="text/javascript" src="{$js_path}jquery/plugins/jquery.dimensions.js"></script>
<script type="text/javascript" src="{$js_path}interface/interface.js"></script>
<style type="text/css" media="screen">@import "{$style_path}jquery.nestedsortablewidget.css";</style>
<script type="text/javascript" src="{$js_path}jquery/plugins/jquery.easing.js"></script>
<script type="text/javascript" src="{$js_path}jquery/plugins/nestedSortable/json.js"></script>
<script type="text/javascript" src="{$js_path}jquery/plugins/nestedSortable/inestedsortable.js"></script>
<script type="text/javascript" src="{$js_path}jquery/plugins/nestedSortable/jquery.nestedsortablewidget.js"></script>
<script type="text/javascript" src="{$js_path}menu.js"></script>
{literal}
<script type="text/javascript">
$(document).ready(
  	function()
  	{
		$('#sel_menu').change(function(){
			if (this.selectedIndex != 0) {
				jQuery('.tip_box').hide();
				selectedMenu = $(this).val();
				menuInit(selectedMenu);
			}
		});
		if (selectedMenu != "") {
			jQuery('.tip_box').hide();
			menuInit(selectedMenu);
		}
  	}
);
</script>
{/literal}
{else}
	{if isset($mootools) eq true}
	<script type="text/javascript" src="{$js_path}mootools.js" charset="utf-8"></script>
	{else}
	<script type="text/javascript" src="{$js_path}jquery/jquery-1.2.6.pack.js"></script>
	<script type="text/javascript" src="{$js_path}jquery/plugins/ui.datepicker.js"></script>
	<script type="text/javascript" src="{$js_path}jquery/plugins/ui.datepicker-pl.js"></script>
	<link href="{$style_path}thickbox.css" rel="stylesheet" type="text/css" />
	<script language="javascript" type="text/javascript" src="{$js_path}jquery/plugins/thickbox.js"></script>
	<link href="{$style_path}ui.datepicker.css" rel="stylesheet" type="text/css" />
	{/if}
{/if}
<script type="text/javascript" language="javascript" src="{$js_path}fx_styles.js"></script>
{if isset($tinymce)}
<script language="javascript" type="text/javascript" src="{$main_path}/libraries/tinymce2/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="{$js_path}tinymce_editor.js"></script>
{/if}
<!--[if lte IE 6]>
<script defer type="text/javascript" src="{$js_path}pngfix.js"></script>
{literal}
<style type="text/css">
	.bottom_line_left, .bottom_line_right, #wt_left, #preview_link, #virtueworld, a, li, td, .tip_box, .download_box, .information_box, .warning_box, .note_box, .favorite_box, .service_box, .links_box { behavior: url({/literal}{$style_path}{literal}iepngfix.htc) }
</style>
<style type="text/css">
	.bottom_line_bg { filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src='{/literal}{$img_path}{literal}bottom_line_bg.png'); }
	.tip_box, .download_box, .information_box, .warning_box, .note_box, .favorite_box, .service_box, .links_box { width: 90%; }
</style>
<link href="{/literal}{$style_path}{literal}ie6tm.css" rel="stylesheet" type="text/css" media="screen, tv, projection"/>
<script type="text/javascript" src="{/literal}{$js_path}{literal}ADxMenu.js"></script>
<![endif]-->
<!--[if lte IE 7]>
<style type="text/css">
#wrap_style {
	background: none;
}
</style>
<![endif]-->
{/literal}
</head>
<body>
<div id="wrap_style">
<div id="wrap">
	<div id="main_wrapper_style">
		<div style="height: 100%;">
			<div id="window_top">
				<div id="wt_left">
					<div id="wt_right">
						<p id="breadcrumb">VirtueCMS - Administration Panel: <strong><a href="">Strona główna</a></strong></p>
						<img src="{$img_path}window_top_right.png" id="tm_r" alt="VirtueCMS - Administration Panel" title="VirtueCMS - Administration Panel" />
					</div>
				</div>
			</div>

			{include file="top_menu.tpl"}

			<div id="window_main">
				<div id="wm_right">
					<div id="wm_lt">
						<div id="wm_rt">
							<div id="window_pad">
								<div class="padding">

									<div class="toolbar">

									{include file="toolbar.tpl"}

									</div>

									<div class="special_cols">
										<div class="right">

											{include file="$inc.tpl"}

										<div class="both"><!-- --></div>
									</div>

								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="window_bottom">
				<div id="wb_left">
					<div id="wb_right">
					<!-- -->
					</div>
				</div>
			</div>
		</div>

		<div id="main_separator"><!-- --></div>

		<div id="bot_wrapper">
			<div id="bot_content">
				<div id="bottom_line">
					<div id="bot_nav">
						<img src="{$img_path}bottom_line_right_bg.png" id="bt_r" alt="VirtueCMS - Administration Panel" title="VirtueCMS - Administration Panel" />
						<div class="bottom_line_left">
							<div class="bottom_line_bg">
								<div id="bottom_nav">
									<img src="{$img_path}virtuecms.png" alt="VirtueCMS - Administration Panel" title="VirtueCMS - Administration Panel" id="logo" />
									<div id="virtueworld">
										<a href="#" title="Strona firmy VirtueWorld Ltd."><img src="{$img_path}virtueworld.png" alt="Strona firmy VirtueWorld Ltd." title="Strona firmy VirtueWorld Ltd." style="float: right" /></a>
										<p><a href="#" title="Strona firmy VirtueWorld Ltd.">VirtueWorld</a><br />Strona firmy VirtueWorld Ltd.</p>
									</div>
									<div id="preview_link">
										<a href="{$main_path}/" title="Podgląd strony" target="_blank"><img src="{$img_path}preview_ico.png" alt="Podgląd strony" title="Podgląd strony" style="float: right" /></a>
										<p><a href="#" title="Podgląd strony">Podgląd strony</a><br />Opcja otwiera nowe okno</p>
									</div>
									<div class="both"><!-- --></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="both"><!-- --></div>
</body>

</html>
