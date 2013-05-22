<html>
<body>
<?=View::forge('menu');?>
<h4>Time Spent Today on Projects - <a href="/periodoftime/add">Add time</a></h4> 
<h5>Google docs speadsheet format</h5>
<table cellpadding="3" cellspacing="0" border="1">
	<thead>
		<th>Timestamp</th>
		<th>Date</th>
		<th>Project</th>
		<th>Time</th>
		<th>User</th>
	</thead>
	<tbody>
		<?$total = 0;?>
		<?foreach($times as $periodoftime):?>
			<tr>
				<td>
					<?$date = new DateTime('@'.$periodoftime->created_at);?>
					<?=$date->format('n/j/Y H:i:s')?>
				</td>
				<td><?=$day_date->format('n/j/Y')?></td>
				<td><?=$periodoftime->project->name?></td>
				<td><?=$periodoftime->minutes?></td>
				<td>01-Barry</td>
			</tr>
			<?$total += $periodoftime->minutes?>
		<?endforeach;?>
		<tr>
			<td></td>
			<td></td>
			<td style="padding-top:20px"><b>Total</b></td>
			<td style="padding-top:20px"><?=$total?></td>
			<td></td>
		</tr>
	</tbody>
</table>
</body>
</html>