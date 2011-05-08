<div id="mainContent">
	<div id="tables">
	{foreach from=$tables item=table}
		<div class="tableName dnd" id="{$table}">
		{$table}
		</div>
	{/foreach}
	</div>
	<div id="diagramArea">
		<div id="relationsInfo">
			<div id="relationsInfoHeader">
			Propozycje połączeń:
			</div>
			<div id="relationsInfoContent">
				<!--<div class="relationsInfoContentElement">
					<div class="relationsInfoContentElementTable">
					Table1
					</div>
					<div class="relationsInfoContentElementColumn">
					Column1
					</div>
				</div>
				<div class="relationsInfoContentElement">
					<div class="relationsInfoContentElementTable">
					Table2
					</div>
					<div class="relationsInfoContentElementColumn">
					Column2
					</div>
				</div>
				-->
			</div>
		</div>
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
	$('.tableName').draggable({ appendTo: "body", helper: "clone", containment: '#mainContent' }).click(function(){
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
	$.ajax({
		type: 'POST',
		url: MainPath + '/Ajax',
		data: {
			action: 'showRelations'
		},
		success: function(msg){
			msg = $.parseJSON(msg);
			html = '';
			$.each(msg, function(key, val) {
				var elem = '<div class="relationsInfoContentElement dnd"><div class="relationsInfoContentElementTable">';
				elem += val.table;
				elem += '</div><div class="relationsInfoContentElementColumn">';
				elem += val.column;
				elem += '</div>';
				$('#relationsInfoContent').append(elem);
			});
			$('.relationsInfoContentElement').draggable({ appendTo: "body", helper: "clone", containment: '#diagramArea', cursor: 'move' })
		}
	});
	
	/*
	$("#diagramArea").droppable({
		activeClass: "ui-state-default",
		hoverClass: "ui-state-hover",
		accept: ":not(.ui-sortable-helper)",
		drop: function( event, ui ) {
			$( this ).find( ".placeholder" ).remove();
			$( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );
		}
	});
	*/
	$('#relationsInfo').draggable({containment: '#diagramArea', handle: '#relationsInfoHeader', cursor: 'move'});
});
</script>
{/literal}