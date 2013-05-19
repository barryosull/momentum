<html>
<body>
<?=View::forge('menu');?>
<h4>Add Project</h4> 
<?$error = Session::get_flash('error');
if($error):?>
	Error: <?=$error?>
<?endif;?>

<form action="/project/add_post" method="post">
	<input type="text" name="name"></br>
	<input type="submit" value="Add">
</form>
</body>
</html>