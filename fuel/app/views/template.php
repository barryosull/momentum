<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link type="text/css" rel="stylesheet" href="/assets/css/bootstrap.css">
<link type="text/css" rel="stylesheet" href="/assets/css/bootstrap-responsive.css">
<link type="text/css" rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
	<div id="header"></div>
	<div id="error_box" class="row-fluid">
		<div class="span12">
			<div class="alert alert-error">
		  		
		  		<strong>Error!</strong> <span class="error_message"></span>
			</div>
		</div>
	</div>
	<div id="success_box" class="row-fluid">
		<div class="span12">
			<div class="alert alert-success">
		  		
		  		<span class="success_message"></span>
			</div>
		</div>
	</div>
	<div id="content"></div>
	<div id="previous_content"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="footer">
				<p>Developed by Barry O Sullivan <a class="external_link" href="http://twitter.com/barryosull" target="_blank">@barryosull</a></p>
			</div>
		</div>
	</div>

</div>
<div id="templates">
<?foreach($views as $view):
	$view_formatted = str_replace('/', '_', $view)?>
	<div id="<?=$view_formatted?>_template">
		<?=View::forge($view)?>
	</div>
<?endforeach;?>
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/highcharts.js"></script>
<script type="text/javascript" src="/assets/js/charts_from_tables.js"></script>
<script type="text/javascript" src="/assets/js/app.js"></script>
</body>
</html>