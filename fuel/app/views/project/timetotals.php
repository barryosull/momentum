<div class="row-fluid">
	<div class="span6">
		<h3>Project Times</h3>
		The total time spent on each project for the following week
		<?
		$day_before_week_end = clone $week_end;
		$day_before_week_end->modify('-1 day');
		?>
		<h4>Week <?=$week_start->format('d/m/Y')?> - <?=$day_before_week_end->format('d/m/Y')?></h4>
		<a href="/project/timetotals/<?=$last_week_start->format('Y-m-d')?>" class="btn pull-left">&lt;&lt; Prev</a>
		<a href="/project/timetotals/<?=$next_week_start->format('Y-m-d')?>" class="btn pull-right">Next &gt;&gt;</a>
	</div>
</div>
<div class="row-fluid">
	<div class="span6">
		<div id="project_times" style="height: 250px"></div>
		<table data-chart-id="project_times" class="table table-bordered table-condensed view_as_stacked_bar_chart">
			<thead>
				<th>Times</th>
				<?foreach($projects as $project):?>
					<th><?=$project->name?></th>
				<?endforeach;?>
			</thead>
			<tbody>
				<?for($day = clone $week_start; $day < $week_end; $day->modify('+1 days')):?>
					<tr>
						<?
						$next_day = clone $day;
						$next_day->modify('+1 days');
						?>
						<th><?=$day->format('D')?></th>
						<?foreach($projects as $project):?>
							<td><?=$project->get_totaltime_for_date_range($day, $next_day)?></td>
						<?endforeach;?>
					</tr>
				<?endfor;?>
			</tbody>
		</table>
	</div>
	<div class="span6">
		<table class="table table-bordered">
			<thead>
				<th>Project</th>
				<th>Total Time</th>
			</thead>
			<?foreach($projects as $project):?>
				<?$total_time = $project->get_totaltime_for_date_range($week_start, $week_end);
				if ($total_time > 0): ?> 
					<tr>
						<td><?=$project->name?></td>
						<td><?=Model_TimeFormat::mins_to_string($total_time)?></td>
					</tr>
				<?endif;?>
			<?endforeach;?>
		</table>
	</div>
</div>