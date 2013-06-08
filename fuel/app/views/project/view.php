<div class="row">
	<div class="span6">
		<h4>Projects - <a class="btn" href="/project/add">Add</a></h4> 
	</div>
</div>
<div class="row">
	<div class="span6">
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<th>Name</th>
				<th></th>
			</thead>
			<tbody>
				<?foreach($projects as $project):?>
					<tr>
						<td><?=$project->name?></td>
						<td><a href="/project/delete/<?=$project->id?>">Delete</a></td>
					</tr>
				<?endforeach;?>
			</tbody>
		</table>
	</div>
</div>