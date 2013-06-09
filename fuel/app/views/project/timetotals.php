<div class="row">
	<div class="span6">
		<h3>Project Times</h3>
		<h4>Week <?=$week_start->format('d/m/Y')?> - <?=$week_end->format('d/m/Y')?></h4>
		<div id="project_times" style="height: 250px"></div>
		<table data-chart-id="project_times" class="table table-bordered table-condensed view_as_bar_chart">
			<thead>
				<th>Times</th>
				<?foreach($projects as $project):?>
					<th><?=$project->name?></th>
				<?endforeach;?>
			</thead>
			<tr>
				<th></th>
				<?foreach($projects as $project):?>
					<th><?=$project->get_totaltime_for_date_range($week_start, $week_end)?></th>
				<?endforeach;?>
			</tr>
		</table>
		<a href="/project/timetotals/<?=$last_week_start->format('Y-m-d')?>" class="btn pull-left">&lt;&lt; Prev</a>
		<a href="/project/timetotals/<?=$next_week_start->format('Y-m-d')?>" class="btn pull-right">Next &gt;&gt;</a>
	</div>
</div>