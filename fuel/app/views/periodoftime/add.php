<div class="row">
	<div class="span12">
		<h4>Add Time</h4> 
		<form action="/periodoftime/add_post" method="post">
			Project: <select name="project_id">
				<?foreach($projects as $project):?>
					<option value="<?=$project->id?>"><?=$project->name?></option>
				<?endforeach;?>
			</select></br>
			Minutes: <input type="text" name="minutes"></br>
			<input type="submit" value="Add">
		</form>
	</div>
</div>