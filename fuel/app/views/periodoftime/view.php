<div class="row">
	<div class="span6">

		<h4>Time Spent Today on Projects - <a class="btn" href="/periodoftime/add">Add time</a></h4> 
		<br/>
		<br/>
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<th>Date</th>
				<th>Project</th>
				<th>Time</th>
				<th>Option</th>
			</thead>
			<tbody>
				<?$total = 0;?>
				<?foreach($times as $periodoftime):?>
					<tr>
						<td><?=$day_date->format('d/m/Y')?></td>
						<td><?=$periodoftime->project->name?></td>
						<td><?=$periodoftime->minutes?></td>
						<td><a class="btn" href="/periodoftime/delete/<?=$periodoftime->id?>">Delete</a></td>
					</tr>
					<?$total += $periodoftime->minutes?>
				<?endforeach;?>
				<tr>
					<td></td>
					<td></td>
					<td style="padding-top:20px"><b>Total</b></td>
					<td style="padding-top:20px"><?=$total?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>