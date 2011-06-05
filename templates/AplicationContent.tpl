<div id="mainContent">
	<div id="tables" style="overflow: auto">
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

<div id="additionInfo" style="height:920px">
	<div id="tableInfo">
		<div id="tableInfoHeader">Informacje o tabeli:</div>
		<div id="tableInfoContent"></div>
	</div>
	<div id="sqlArea" style="height:900px">
		<div id="sqlAreaHeader">
		Zapytanie SQL:
		</div>
		<div id="sqlAreaContent" style="height:820px">
<!--            <textarea id="sqlQuery" style="height:800px">
SELECT
*
FROM
    table t

left JOIN
    aaa a
ON
    a.aield = t.tield1
AND
    a.field2 = t.tield3

right JOIN
    pable b
ON
    b.bield = t.tield1
AND
    b.bield2 = t.tield3

JOIN
    pable p
ON
    p.pield = b.tield1
AND
    p.pield2 = b.tield3
</textarea>
-->
<!-- 

SELECT
*
FROM
    table table

left JOIN
    aaa aaa
ON
    aaa.field2 = table.tield3

left JOIN
    aaa aaa
ON
    aaa.aield = table.tield1

right JOIN
    pable pable
ON
    pable.bield2 = table.tield3

right JOIN
    third third
ON
    third.bield = aaa.tield1

-->

<!--     
SELECT
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
    field5='val2'
-->
<!--SELECT
* 
FROM
ksiazki k 
JOIN 
kategorie k1
ON
k.idKategoria = k1.id
JOIN
tagbook_join t
ON
t.idKsiazka = k.id-->
<textarea id="sqlQuery" style="height:800px">

</textarea>

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

<div id="pickJoinOn" style="display:none;width:300px;height:300px">
</div>

<div id="joinInfo" class="joinInfo" style="display:none;width:300px;height:300px">
    <div class="joinInfo" style="width:300px" >
        diff info etc
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
rGap : 10, //circles space
rCircleS : 10, // small circle
rCircleL : 30, //bigger circle
//alpha : 360 / this.portions,
radConv : 0.017453292519943295,
cords : new Object(),
cS : new Object(),
levelsPortions : new Object(),
joins : new Array(),
noNeedToBindDiagramArea : false,

drawCircle : function()
{
    $("#diagramArea").droppable( 'disable' );

    this.noNeedToBindDiagramArea = true;
    this.svg.circle(this.stMx, this.stMy, this.rCircleL, {fill: 'gray', strokeWidth: 1, id: this.tableIdPrefix + this.draggedNow.attr('id') + "___" + this.draggedNow.attr('id').substr(0, 1), class: 'JSfancy JSgraphElement JSgraphTable JSgraphTableDropp'});
    
    $('#sqlQuery').text('SELECT * FROM ' + this.draggedNow.attr('id') + ' ' + this.draggedNow.attr('id').substr(0, 1) + ' ');
    
    this.binds();
},

init : function()
{
    $('#diagramArea').svg();
    this.svg = $('#diagramArea').svg('get');
    this.svg.clear();
    if (this.noNeedToBindDiagramArea == false)
    {
        $("#diagramArea").droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ":not(.ui-sortable-helper)",
            drop: function( event, ui ) 
            {
                V3Graph.drawCircle();
            }
        });
    }
    
    this.getCoords();
    this.getJoins();

    for (var k in this.cords)
    {
        if ('' != k)
        {
            this.svg.circle(this.stMx, this.stMy, this.rCircleL, {fill: 'gray', strokeWidth: 1, id: this.tableIdPrefix + k + "___" + this.cords[k].alias, class: 'JSfancy JSgraphElement JSgraphTable JSgraphTableDropp'});
        }
    }
    

    for (var first in this.cords)
    {
        this.aliasesUsed[this.cords[first].alias] = true;
        this.draw(this.cords[first]);
    }
    this.binds();
},

tableIdPrefix : 'JS__V3DV__table__',
joinIdPrefix : 'JS__V3DV__join__',

elementsData : new Object(),

draw : function(cords)
{
    var svg = this.svg;

    this.aliasesUsed[cords.alias] = true;
    
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
            {strokeWidth: 2, stroke: "white", fill: '#aaa', class: 'JSfancy JSgraphElement JSgraphJoin', id: this.joinIdPrefix + cords.from + "___" + cords.alias}
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
            {strokeWidth: 2, stroke: "white", fill: '#aaa', class: 'JSfancy JSgraphElement JSgraphTable JSgraphTableDropp', id: this.tableIdPrefix + cords.from + "___" + cords.alias}
            );
                
        this.elementsData[this.joinIdPrefix + cords.from + "___" + cords.alias] = cords;
        this.elementsData[this.joinIdPrefix + cords.from + "___" + cords.alias].referingTo = this.tableIdPrefix + cords.from + "___" + cords.alias;
            
        this.elementsData[this.tableIdPrefix + cords.from + "___" + cords.alias] = cords;
        this.elementsData[this.tableIdPrefix + cords.from + "___" + cords.alias].referingTo = this.joinIdPrefix + cords.from + "___" + cords.alias;
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
    }).mousedown(function()
    {
		$.ajax({
			type: 'POST',
			url: MainPath + '/Ajax',
			data: {
				action: 'getTableInfo',
				table: $(this).attr('id')
			},
			success: function(msg)
            {
				msg = $.parseJSON(msg);
				html = '';
				$.each(msg, function(key, val) {
					html += key + ': ' + val + '<br \/>';
                });
				$('#tableInfoContent').html(html);
			}
		});
	});
        /* bindy */
      
     $('.JSgraphElement', this.svg.root())
        .bind('mouseup', this.svgMouseup)
        .bind('mouseover', this.svgOver)
        .bind('mouseout', this.svgOut);

    $('.JSgraphElement', this.svg.root());
    
	$('.JSgraphTable').fancybox(
    {
        'content' : $('#pickJoinOn').html(),
        'type' : 'ajax',
        'href' : MainPath + '/Ajax',
        'ajax' : 
        {
            type: 'POST',
            complete : function()
            {
                $('.JSconstraintOfTable1SelectedToPickConstraint').bind('change', function(){V3Graph.constraintOfTable1SelectedToPickConstraint = $(this).find('option:selected').val();});
                $('.JSconstraintOfTable2SelectedToPickConstraint').bind('change', function(){V3Graph.constraintOfTable2SelectedToPickConstraint = $(this).find('option:selected').val();});
                $('.JSjunctionOfTablesSelectedToPickConstraint').bind('change', function(){V3Graph.junctionOfTablesSelectedToPickConstraint = $(this).find('option:selected').val();});
                
                V3Graph.constraintOfTable1SelectedToPickConstraint = $('.JSconstraintOfTable1SelectedToPickConstraint').find('option:selected').val();  
                V3Graph.constraintOfTable2SelectedToPickConstraint = $('.JSconstraintOfTable2SelectedToPickConstraint').find('option:selected').val();  
                V3Graph.junctionOfTablesSelectedToPickConstraint = $('.JSjunctionOfTablesSelectedToPickConstraint').find('option:selected').val();    
         
                $('a.JSmarkConstraintChoises').bind('click', function(event)
                {
                    event.preventDefault();
                    V3Graph.extendJoin();
                    //zamkniecie fancy przeniesione do powyzszej funkcji
                });
                
                $('a.JScancelConstraintChoises').bind('click', function(event)
                {
                    event.preventDefault();  
                    $.fancybox.close();
                });
                
            }
        },
        'onClose' : function() 
        {
            V3Graph.constraintOfTable1SelectedToPickConstraint = false;  
            V3Graph.constraintOfTable2SelectedToPickConstraint = false;  
            V3Graph.junctionOfTablesSelectedToPickConstraint = false;  
        }
    });
    
    $('.JSgraphJoin').fancybox(
    {
        'content' : $('#joinInfo').html()
    });
},

svgMouseup : function()
{
    if (0 < $(this).attr('class').baseVal.indexOf('JSgraphTableDropp') && false !== V3Graph.draggedNow)
    {
//        alert('Just dropped "' + $(V3Graph.draggedNow).attr('id') + '" on: "' + $(this).attr('id') + '"');
//        V3Graph.extendJoin($(V3Graph.draggedNow).attr('id'), $(this).attr('id'));
    }
    else
    {
        if (false === V3Graph.draggedNow)
        {
//            alert('I\'m "' + $(this).attr('id') + '"! You have clicked me.');
        }
    }
},

svgOver : function()
{
    if (0 < $(this).attr('class').baseVal.indexOf('JSgraphTableDropp') && false !== V3Graph.draggedNow)
    {
        $(this).attr({'opacity': '0.7',fill: '#000'});
    }
    else if (false === V3Graph.draggedNow)
    {
        $(this).attr('opacity', '0.7');
    }
},

svgOut : function()
{
    $(this).attr({'opacity': '1',fill: '#aaa'});
},


//element na ktory upuszczamy
table1SelectedToPickConstraint : false,
constraintOfTable1SelectedToPickConstraint : false,
//element upuszczanego
table2SelectedToPickConstraint : false,
constraintOfTable2SelectedToPickConstraint : false,

junctionOfTablesSelectedToPickConstraint : false,

aliasesUsed : new Object(),
setAliasUsed : function(aliasToCheck, addition)
{
    var textToAdd = '';
    var aliasToSet = '';
    
    if ('undefined' == typeof addition)
    {
        textToAdd = '';
        addition = 0;
    }
    else
    {
        textToAdd = '_' + addition;
    }

    if ('undefined' == typeof this.aliasesUsed[aliasToCheck + textToAdd])
    {
        this.aliasesUsed[aliasToCheck + textToAdd] = true;
        return aliasToCheck + textToAdd;    
    }
    else
    {
        var aliasToSet = this.setAliasUsed( aliasToCheck, addition + 1);
    }
    
    return aliasToSet;
},

getCoords : function()
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

getJoins : function()
{
    var postData = {action : 'getJoinsAndAliases', query : $('#sqlQuery').val()};

    $.ajax({
        type: "POST",
        url: MainPath + '/Graph',
        async: false,
        data: postData,
        success: function(msg)
        {
            var msg = jQuery.parseJSON(msg);

            V3Graph.joins = msg.joins;
            V3Graph.aliases = msg.aliases;
        }
    });
},

getQuery : function()
{
    var postData = {action : 'getQuery', query : $('#sqlQuery').val(), joins : this.joins, idAndAlias : this.idAndAlias};

    $.ajax({
        type: "POST",
        url: MainPath + '/Graph',
        async: false,
        data: postData,
        success: function(msg)
        {
            var query = msg;
            $('#sqlQuery').val(query);
        }
    });
},

markConstraintChoises : function(invoker)
{
    event.preventDefault();
    console.log(invoker);
},

extendJoin : function()
{
    if (false === this.table1SelectedToPickConstraint || false === this.table2SelectedToPickConstraint)
    {
        return false;
    }
    
    if ('undefined' == typeof this.joins)
    {
        this.getJoins();
    }

    for (var k in this.joins)
    {
        this.aliasesUsed[this.joins[k].to.alias] = true;
    }
    var alias1 = this.table1SelectedToPickConstraint[1];

    var alias2 = this.setAliasUsed( this.aliases[this.table2SelectedToPickConstraint[0]]);

    if ('undefined' == typeof this.joins)
    {
        this.joins = new Object();
        k = -1;
    }
    this.joins[parseInt(k)+1] =
    {
        from : this.table1SelectedToPickConstraint[0],
        to : { name :  this.table2SelectedToPickConstraint[0],  alias : alias2 },
        on :
        {
            0 :
            {
                0 : { from : this.table1SelectedToPickConstraint[0], alias : alias1, column : this.constraintOfTable1SelectedToPickConstraint},
                1 : { from : this.table2SelectedToPickConstraint[0], alias : alias2, column : this.constraintOfTable2SelectedToPickConstraint},
                junction: this.junctionOfTablesSelectedToPickConstraint
            }
        },
        type : 'inner'
    };

    this.getQuery();
    this.init();
    
    //dla kolejnosci akcji przeniesione tutaj
    $.fancybox.close();
},

idAndAlias : false,

removeTableFromGraph : function(invokersId)
{
    event.preventDefault();
        
    if ('undefined' == typeof this.joins)
    {
        this.getJoins();
    }
    
    temp = invokersId.split(V3Graph.joinIdPrefix).join('');
    this.idAndAlias = temp.split('___');
    temp = '';
    
    for (var k in this.joins)
    {
        if (this.joins[k].to.alias == this.idAndAlias[1] && this.joins[k].to.name == this.idAndAlias[0])
        {
            delete this.joins[k];
        }
    }

    this.getQuery();
    this.init();
    
    //dla kolejnosci akcji przeniesione tutaj
    $.fancybox.close();
}
}

</script>
{/literal}