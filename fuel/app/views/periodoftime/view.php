<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h3>
			Time Spent on Projects <?=$day_date->format('d/m/Y')?> 
			<?if($day_date == $todays_date):?>
				(Today)
			<?else:?>
				(<?=Date::time_ago(
					$day_date->getTimestamp(),
					$todays_date->getTimestamp()
				)?>)
			<?endif?>
		</h3> 
		<?
		$yesterday = clone $day_date;
		$yesterday->modify('-1 days');

		$tomorrow = clone $day_date;
		$tomorrow->modify('+1 days');
		?>
		<a href="/periodoftime/view/<?=$yesterday->format('Y-m-d')?>" class="btn">&lt;&lt; -1 day</a>
		<?if($day_date < $todays_date):?>
			<a href="/periodoftime/view/<?=$tomorrow->format('Y-m-d')?>" class="btn">+1 day &gt;&gt;</a>
		<?endif;?>
		<a class="btn pull-right" href="/periodoftime/add/<?=$day_date->format('Y-m-d')?>">Add time</a>
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
						<td><a class="btn btn-danger delete" href="/periodoftime/delete/<?=$periodoftime->id?>">Delete</a></td>
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