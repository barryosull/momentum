<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h3>Register</h3>
		<form action="/auth/register_post" method="post">
			<table border="0">
				<tr>
					<td>Name: </td><td><input type="text" name="name"></td>
				</tr>
				<tr>
					<td>Email Address: </td><td><input type="text" name="email"></td>
				</tr>
				<tr>
					<td>Password: </td><td><input type="password" name="password"></td>
				</tr>
				<tr>
					<td>Confirm Password:  </td><td><input type="password" name="password_confirm"></td>
				</tr>
				<tr>
					<td><a href="/auth/login">Already a member? Login here.</a></td>
					<td align="right"><input type="submit" value="Register"></td>
				</tr>
			</table>
		</form>
	</div>
</div>