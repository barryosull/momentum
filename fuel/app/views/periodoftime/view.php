<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h3>Time Spent Today on Projects</h3> 
		The amount of time that you've spent on a projects today.
		<a class="btn pull-right" href="/periodoftime/add">Add time</a>
		<br/>
		<br/>
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<th>Project</th>
				<th>Time</th>
				<th>Options</th>
			</thead>
			<tbody>
				<?$total = 0;?>
				<?foreach($times as $periodoftime):?>
					<tr>					
						<td><?=$periodoftime->project->name?></td>
						<td><?=Model_TimeFormat::mins_to_string($periodoftime->minutes)?></td>
						<td><a class="btn" href="/periodoftime/delete/<?=$periodoftime->id?>">Delete</a></td>
					</tr>
					<?$total += $periodoftime->minutes?>
				<?endforeach;?>
				<tr>
					<td></td>
					<td style="padding-top:20px"><?=Model_TimeFormat::mins_to_string($total)?></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>