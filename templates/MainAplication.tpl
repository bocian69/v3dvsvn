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
<link href="{$style_path}aplication_content.css" rel="stylesheet" type="text/css" media="all"/>

<script type="text/javascript" src="{$js_path}jquery/jquery-1.5.2.min.js"></script>
<script type="text/javascript">
var MainPath = "{$main_path}";
var ImgPath = "{$img_path}";
var StylePath = "{$style_path}";
var selectedMenu = "{$selectedMenu}";
</script>
{literal}
<script type="text/javascript">
$(document).ready(
  	function(){
  	}
);
</script>
{/literal}
<script type="text/javascript" language="javascript" src="{$js_path}fx_styles.js"></script>
<!--[if lte IE 6]>
<script defer type="text/javascript" src="{$js_path}pngfix.js"></script>
{literal}
<style type="text/css">
	.bottom_line_left, .bottom_line_right, #wt_left, #preview_link, #v3dv, a, li, td, .tip_box, .download_box, .information_box, .warning_box, .note_box, .favorite_box, .service_box, .links_box { behavior: url({/literal}{$style_path}{literal}iepngfix.htc) }
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
						<p id="breadcrumb" style="text-align: center;"><strong>Visiual Drag & Drop Database Viewer v 1.0</strong></p>
						<img src="{$img_path}window_top_right.png" id="tm_r" alt="V3DV" title="V3DV" />
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

											{include file="AplicationContent.tpl"}

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
						<img src="{$img_path}bottom_line_right_bg.png" id="bt_r" />
						<div class="bottom_line_left">
							<div class="bottom_line_bg">
								<div id="bottom_nav">
									<div id="logo">Nasze logo</div>
									<div id="v3dv">
										<p><a href="http://www.freq.pl/trac/v3dv" title="Strona projektu V3DV"  target="_blank">V3DV</a><br />Strona projektu V3DV</p>
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
