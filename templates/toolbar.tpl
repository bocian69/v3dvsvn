<ul>
	{foreach from=$toolbar item=i}
	    <li {if isset($i.class) eq true}class="{$i.class}"{/if}>
	    	{if isset($i.link) eq true}<a href="{$i.link.href}" id="{$i.link.id}" class="{$i.link.class}">{/if}
	    	{if isset($i.text) eq true}{$i.text}{/if}
	    	{if isset($i.img) eq true}<span><img src="{$i.img.src}" title="{$i.img.title}" alt="{$i.img.alt}" /></span>{/if}
	    	{if isset($i.label) eq true}<span class="label">{$i.label}</span>{/if}
	    	{if isset($i.link) eq true}</a>{/if}
	    </li>
	{/foreach}
</ul>