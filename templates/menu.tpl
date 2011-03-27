{if !empty($success)}
	{foreach from=$success item=i}
		<div class="{$i.level}">
		<p>{$i.info}</p>
		</div>
		<br /><br />
	{/foreach}
{/if}
{$menus}
<div class="tip_box">
	Wybierz z listy menu którym chcesz zarządzać<br /><br />
</div>
<div id="zawartosc"></div>
