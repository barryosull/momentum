<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h3>Projects</h3>
		All the projects that are currently active
		<a class="btn pull-right" href="/project/add">Add Project</a>
		<br/><br/>
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<th>Name</th>
				<th>Time</th>
				<th>Options</th>
			</thead>
			<tbody>
				<?foreach($projects as $project):?>
					<tr>
						<td><?=$project->name?></td>
						<td><?=Model_TimeFormat::mins_to_string($project->get_totaltime())?></td>
						<td><a class="btn btn-danger delete" href="/project/delete/<?=$project->id?>">Delete</a></td>
					</tr>
				<?endforeach;?>
			</tbody>
		</table>
	</div>
</div>