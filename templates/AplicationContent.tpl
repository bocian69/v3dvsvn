<div id="mainContent">
	<div id="tables">
	{foreach from=$tables item=table}
		<div class="tableName dnd" id="{$table}">
		{$table}
		</div>
	{/foreach}
	</div>
	<div id="diagramArea">
	</div> 
</div>

<div id="additionInfo">
	<div id="tableInfo">
		<div id="tableInfoHeader">Informacje o tabeli:</div>
		<div id="tableInfoContent"></div>
	</div>
	<div id="sqlArea">
		<div id="sqlAreaHeader">
		Zapytanie SQL:
		</div>
		<div id="sqlAreaContent">
		<textarea id="sqlQuery"></textarea>
		</div>
	</div>
</div>

<div id="dataInfo">
	<div id="dataInfoHeader">
	Dane zapytania:
	</div>
	<div id="dataInfoContent">
	</div>
</div>

</script>
{literal}
<script type="text/javascript">
$(document).ready(function(){
	$('.tableName').click(function(){
		$.ajax({
			type: 'POST',
			url: MainPath + '/Ajax',
			data: {
				action: 'getTableInfo',
				table: $(this).attr('id')
			},
			success: function(msg){
				msg = $.parseJSON(msg);
				html = '';
				$.each(msg, function(key, val) {
					html += key + ': ' + val + '<br\/>';
				});
				$('#tableInfoContent').html(html);
			}
		});
	});
});
</script>
{/literal}