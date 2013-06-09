<html>
<head>
<link type="text/css" rel="stylesheet" href="/assets/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/assets/css/bootstrap-responsive.min.css">
<link type="text/css" rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
<?=$header?>
<?$error = Session::get_flash('error');
if($error):?>
	<div class="row">
		<div class="span12">
			<div class="alert alert-error">
		  		<button type="button" class="close" data-dismiss="alert">&times;</button>
		  		<strong>Error!</strong> <?=$error?>
			</div>
		</div>
	</div>
<?endif;?>
<?=$body?>
<?=$footer?>
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/highcharts.js"></script>
<script type="text/javascript" src="/assets/js/charts_from_tables.js"></script>

</body>
</html>