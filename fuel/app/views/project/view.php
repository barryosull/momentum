<html>
<body>
<?=View::forge('menu');?>
<h4>Projects - <a href="/project/add">Add</a></h4> 
<table>
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
</body>
</html>