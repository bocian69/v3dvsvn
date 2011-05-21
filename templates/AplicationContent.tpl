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
    <!--
    <div id="relationsInfo">
			<div id="relationsInfoHeader">
			Propozycje połączeń:
			</div>
			<div id="relationsInfoContent">
				</!--<div class="relationsInfoContentElement">
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
				--/>
			</div>
		</div>
        -->
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
		<textarea id="sqlQuery">SELECT
                                    t.tield1, t.tield2, t.tield3, t.tield4,
                                    p.pield,
                                    f.field4
                                FROM
                                    table t

                                left JOIN
                                    fable f
                                ON
                                    f.field = t.tield1
                                AND
                                    f.field2 = t.tield3
                                right JOIN
                                    pable p
                                ON
                                    p.pield = t.tield1
                                AND
                                    p.pield2 = t.tield3

                                JOIN
                                    iable i
                                ON
                                    i.iield = p.pield1
                                AND
                                    i.iield2 = p.pield3

                                JOIN
                                    zable z
                                ON
                                    z.zield = p.pield1
                                AND
                                    z.zield2 = p.pield3

                                JOIN
                                    xable x
                                ON
                                    x.xield = p.pield1
                                AND
                                    x.xield2 = p.pield3

                                JOIN
                                    fffable fff
                                ON
                                    fff.fffield = f.field1
                                AND
                                    fff.fffield2 = f.field3

                                WHERE
                                    field3='val1'
                                AND
                                    field4=5
                                OR
                                    field5='val2'</textarea>
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

{literal}
<script type="text/javascript">
$(document).ready(function(){
//	$('.tableName').draggable({ appendTo: "body", helper: "clone", containment: '#mainContent' }).click(function(){
//		$.ajax({
//			type: 'POST',
//			url: MainPath + '/Ajax',
//			data: {
//				action: 'getTableInfo',
//				table: $(this).attr('id')
//			},
//			success: function(msg){
//				msg = $.parseJSON(msg);
//				html = '';
//				$.each(msg, function(key, val) {
//					html += key + ': ' + val + '<br\/>';
//				});
//				$('#tableInfoContent').html(html);
//			}
//		});
//	});
//	$.ajax({
//		type: 'POST',
//		url: MainPath + '/Ajax',
//		data: {
//			action: 'showRelations'
//		},
//		success: function(msg){
//			msg = $.parseJSON(msg);
//			html = '';
//			$.each(msg, function(key, val) {
//				var elem = '<div class="relationsInfoContentElement dnd"><div class="relationsInfoContentElementTable">';
//				elem += val.table;
//				elem += '</div><div class="relationsInfoContentElementColumn">';
//				elem += val.column;
//				elem += '</div>';
//				$('#relationsInfoContent').append(elem);
//			});
//			$('.relationsInfoContentElement').draggable({ appendTo: "body", helper: "clone", containment: '#diagramArea', cursor: 'move' })
//		}
//	});
//
//	/*
//	$("#diagramArea").droppable({
//		activeClass: "ui-state-default",
//		hoverClass: "ui-state-hover",
//		accept: ":not(.ui-sortable-helper)",
//		drop: function( event, ui ) {
//			$( this ).find( ".placeholder" ).remove();
//			$( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );
//		}
//	});
//	*/
//	$('#relationsInfo').draggable({containment: '#diagramArea', handle: '#relationsInfoHeader', cursor: 'move'});
});
</script>

<script type="text/javascript">
$(document).ready(function()
{
    V3Graph.init();
});


var V3Graph =
{
/* initials */
draggedNow : false,
// initials vars for drawer
stMx : 300,
stMy : 200,
svg : '',
// zmienne
rGap : 5, //circles space
rCircleS : 5, // small circle
rCircleL : 20, //bigger circle
//alpha : 360 / this.portions,
radConv : 0.017453292519943295,
cords : new Object(),
cS : new Object(),
levelsPortions : new Object(),

init : function()
{
    $('#diagramArea').svg();
    this.svg = $('#diagramArea').svg('get');
    this.svg.clear();

    /* kolko centrum - red */
    this.svg.circle(this.stMx, this.stMy, this.rCircleL, {fill: 'red', strokeWidth: 1, id: 'mainCircle'});
    this.getParsedQuery();

    for (var first in this.cords)
    {
        this.draw(this.cords[first]);
    }
    this.binds();
},

getParsedQuery : function()
{
    var postData = {action : 'getCoords', query : $('#sqlQuery').val()};

    $.ajax({
        type: "POST",
        url: MainPath + '/Graph',
        async: false,
        data: postData,
        success: function(msg)
        {
            var parsedQuery = jQuery.parseJSON(msg);

            V3Graph.cords = parsedQuery;
        }
    });
},

drawFromCords : function()
{
    this.draw();
},

draw : function(cords)
{
    var svg = this.svg;
    var cords = cords;

    if ( 0 != cords.level)
    {
        var path = svg.createPath();
        svg.path(
            path
                .move(cords.coords.start.xS, cords.coords.start.yS)
                .arc(cords.coords.rS, cords.coords.rS, 0,0,1, cords.coords.end.xS, cords.coords.end.yS)
                .line(cords.coords.end.xM, cords.coords.end.yM)
                .arc(cords.coords.rM, cords.coords.rM, 0,0,0, cords.coords.start.xM, cords.coords.start.yM)
                .line(cords.coords.start.xS, cords.coords.start.yS)
                .close(),
            {strokeWidth: 1, stroke: "blue", fill: '#aaa', class: 'testowaCSS', id: cords.from + '_join_area'}
            );
        var path = svg.createPath();
        svg.path(
            path
                .move(cords.coords.start.xM, cords.coords.start.yM)
                .arc(cords.coords.rM,cords.coords.rM, 0,0,1, cords.coords.end.xM, cords.coords.end.yM)
                .line(cords.coords.end.xL, cords.coords.end.yL)
                .arc(cords.coords.rL, cords.coords.rL, 0,0,0, cords.coords.start.xL, cords.coords.start.yL)
                .line(cords.coords.start.xM, cords.coords.start.yM)
                .close(),
            {strokeWidth: 1, stroke: "blue", fill: '#aaa', class: 'testowaCSS', id: cords.from + '_table_area'}
            );
    }
    for (var kk in cords.children)
    {
        this.draw(cords.children[kk]);
    }
},


binds : function()
{
	$('#relationsInfo').draggable({containment: '#diagramArea', handle: '#relationsInfoHeader', cursor: 'move'});
	$('.tableName').draggable(
    {
        appendTo: "body",
        helper: "clone",
        containment: '#mainContent',
        cursorAt: { cursor: "crosshair", top: -5, left: -5 },
        start: function() {
            V3Graph.draggedNow = $(this);
        },
        stop: function() {
            V3Graph.draggedNow = false;
        },
        cursor: 'move'
    }).click(function()
    {

	});
        /* bindy */

     $('.testowaCSS', this.svg.root())
        .bind('mouseup', this.svgMouseup)
        .bind('mouseover', this.svgOver)
        .bind('mouseout', this.svgOut);

    $('.testowaCSS', this.svg.root());
},

svgMouseup : function()
{
    if (false !== V3Graph.draggedNow)
    {
        alert('Just dropped "' + $(V3Graph.draggedNow).attr('id') + '" on: "' + $(this).attr('id') + '"');
        V3Graph.getParsedQuery();
    }
    else
        alert('I\'m "' + $(this).attr('id') + '"! You have clicked me.');
},

svgOver : function()
{
    if (false !== V3Graph.draggedNow)
    {
        $(this).attr({'opacity': '0.7',fill: '#afa'});
    }
    else
    {
        $(this).attr('opacity', '0.7');
    }
},

svgOut : function()
{
    $(this).attr({'opacity': '1',fill: '#aaa'});
}
}

</script>
{/literal}