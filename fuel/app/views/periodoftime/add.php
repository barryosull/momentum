<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h3>
			Add a Time for <?=$day_date->format('d/m/Y')?>
			<?if($day_date == $todays_date):?>
				(Today)
			<?else:?>
				(<?=Date::time_ago(
					$day_date->getTimestamp(),
					$todays_date->getTimestamp()
				)?>)
			<?endif?>
		</h3> 
		<form class="form-horizontal" action="/periodoftime/add_post" method="post">
			<div class="control-group">
			    <label class="control-label" for="project_id">Project:</label>
			    <div class="controls">
			    	<select id="project_id" name="project_id">
						<?foreach($projects as $project):?>
							<option value="<?=$project->id?>"><?=$project->name?></option>
						<?endforeach;?>
					</select>
			    </div>
			</div>

			<div class="control-group">
			    <label class="control-label" for="hours">Hours:</label>
			    <div class="controls">
			    	<input type="text" class="slider-input hours" id="hours" name="hours" value="0">
			    	<div class="slider hours"></div>
			    </div>
			</div>

			<div class="control-group">
			    <label class="control-label" for="minutes">Minutes:</label>
			    <div class="controls">
			    	<input type="text" class="slider-input minutes" id="minutes" name="minutes" value="0">
			    	<div class="slider minutes"></div>
			    </div>
			</div>
			<input type="hidden" name="date" value="<?=$day_date->format('Y-m-d')?>">
			<div class="control-group">
   				<div class="controls">
					<input class="btn" type="submit" value="Add">
				</div>
			</div>
		</form>
	</div>
</div>