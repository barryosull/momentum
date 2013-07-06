<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h3>Projects</h3>
		All the projects that are currently active
		<a class="btn pull-right" href="/project/add">Add Project</a>
		<br/><br/>
		<table class="table table-condensed table-bordered">
			<thead>
				<th>Name</th>
				<th>Time</th>
				<th>Options</th>
			</thead>
			<tbody>
				<?foreach($projects as $project):?>
					<tr class="
					<?if (! $project->is_active()) :?>
						is_deactivated
					<?endif;?>
					">
						<td><?=$project->name?>
							<?if (! $project->is_active()) :?>
								<span class="label pull-right">Inactive</span>
							<?endif;?>
						</td>
						<td><?=Model_TimeFormat::mins_to_string($project->get_totaltime())?></td>
						<td>
							<a class="btn btn-small btn-danger delete pull-right" href="/project/delete/<?=$project->id?>">Delete</a>
							<?if ($project->is_active()) :?>
								<a class="btn btn-small pull-right" href="/project/deactivate/<?=$project->id?>">De-activate</a>
							<?else:?>
								<a class="btn btn-small pull-right" href="/project/activate/<?=$project->id?>">Activate</a>
							<?endif;?>
						</td>
					</tr>
				<?endforeach;?>
			</tbody>
		</table>
	</div>
</div>