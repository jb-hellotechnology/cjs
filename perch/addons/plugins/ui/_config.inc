<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="/perch/addons/apps/cjs_training/scripts.js"></script>
<script src="/perch/addons/apps/cjs_workdays/scripts.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="/perch/core/editors/redactor/redactor.min.css" />
<script src="/perch/core/editors/redactor/redactor.min.js"></script>

<script>
$(function() {
	$(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
});

$('#table').change(function(){
	
	var pTable = $('#table').val();
	$('#field').find('option').remove();
	$('#where').find('option').remove();
	$.post("/perch/addons/apps/cjs_training/find_replace/get_columns.php", { table: pTable }, function(data){
		pData = JSON.parse(data);
		$.each(pData, function() {
			console.log(this);
		    $('#field').append($("<option />").val(this.Field).text(this.Field));
		    $('#where').append($("<option />").val(this.Field).text(this.Field));
		});
	});
	
});

$('#value').keyup(function(){
	var pTable = $('#table').val();
	var pWhere = $('#where').val();
	var pSelector = $('#selector').val();
	var pValue = $('#value').val();
	$.post("/perch/addons/apps/cjs_training/find_replace/get_affected.php", { table: pTable, where: pWhere, selector: pSelector, value: pValue }, function(data){
		$('#count').html(data);
	});
});

$( function() {
	$('#sortable').sortable({
		containment: 'parent',
		axis: 'y',
		items: "div.top",
		tolerance: "pointer",
        cursor: "move",
        opacity: 0.7,
        revert: 300,
        delay: 150,
        handle: ".move",
		update: function (event, ui) {
			let pOrder = 0;
		   	$('#sortable div').each(function(){
			   	$(this).find('span').text(pOrder);
			   	pOrder++;
			   	pItemID = $(this).data('itemid');
			   	console.log(pItemID);
			   	$.post( "/perch/addons/apps/cjs_menu_manager/setOrder.php", { itemID: pItemID, order: pOrder }, function(data){
					console.log(data);   	
				});
		   	})
		},
		start: function(e, ui){
	        ui.placeholder.height(ui.item.height());
	    }
	});
	$('.children').sortable({
		containment: 'parent',
		axis: 'y',
		items: "div.second",
		handle: ".move-child",
		update: function (event, ui) {
			let pOrder = 0;
		   	$('#sortable div').each(function(){
			   	$(this).find('span').text(pOrder);
			   	pOrder++;
			   	pItemID = $(this).data('itemid');
			   	console.log(pItemID);
			   	$.post( "/perch/addons/apps/cjs_menu_manager/setOrder.php", { itemID: pItemID, order: pOrder }, function(data){
					console.log(data);   	
				});
		   	})
		},
	});
});
</script>

<style>
	.move,.move-child{
		margin-bottom:0px;
		float:left;
		display:block;
		background:#000;
		margin-right:1rem;
		color:#fff;
		font-weight:700;
		font-size:1.2rem;
		line-height:1rem;
		width:1.4rem;
		height:1.4rem;
		text-align: center;
	}
	.move:hover,.move-child:hover{
		background:#ccc;
	}
	.sortable-placeholder{
		width:100%;
		height:90px;
		background:red;
		float:left;
	}
	div.top,div.second{
		width:100%;
		float:left;
		background:#f1f2f2;
		padding:5px;
		margin-bottom:10px;
		cursor:pointer;
		z-index:2000;
		position:relative;
	}
	div.second{
		padding-right:0px;
		margin-top:10px;
		padding-left:50px;
		margin-bottom:0px;
	}
	div.top a,div.second a{color:#000;}
	div.top a.button,div.second a.button{float:right;}
	.children{
		float:left;
		width:100%;
	}
	div.top a.subitem{
		display:inline-block;
		color:#333;
		margin-left:50px;
		font-size:80%;
	}
.form-grid{
display:grid;
grid-template-columns:1fr 1fr;
grid-column-gap:2rem;
}
table tr td.pending a{
	background:orange;
	color:#fff;
	padding:5px;
	font-size:85%;
	padding:.3em .7em;
	border-radius:4px;
}
table tr td.published a{
	background:#468847;
	color:#fff;
	padding:5px;
	font-size:85%;
	padding:.3em .7em;
	border-radius:4px;
}
table tr td.suspended a{
	background:gold;
	color:#000;
	padding:5px;
	font-size:85%;
	padding:.3em .7em;
	border-radius:4px;
}
.input-simple.m{
	width:100%;
}
.field-wrap{
	width:100%;
	clear:both;
}
.field-wrap.checkbox-single{
	width:auto;
	float:left;
	clear:none;
}
h2.divider{
	width:100%;
}
table tr td .field-wrap.checkbox-single{
	clear:none;
	padding:0;
}

table tr td .checkbox-single > *{
	padding:0;
}

.form-simple .field-wrap:last-of-type{
	padding-bottom:0px;
}

.form-simple select{
	max-width:400px;
}

#main-panel{
	max-width:calc(100% - 3rem);
}

.redactor-dropdown a{
	font-family:'Arial', sans-serif;
}
</style>

