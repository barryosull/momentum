<html>
<body>
<?=View::forge('menu');?>
<h4>Add Time</h4> 
<?$error = Session::get_flash('error');
if($error):?>
	Error: <?=$error?>
<?endif;?>

<form action="/periodoftime/add_post" method="post">
	Project: <select name="project_id">
		<?foreach($projects as $project):?>
			<option value="<?=$project->id?>"><?=$project->name?></option>
		<?endforeach;?>
	</select></br>
	Minutes: <input type="text" name="minutes"></br>
	<input type="submit" value="Add">
</form>
</body>
</html>