<div class="row-fluid">
	<div class="span3"></div>
	<div class="span6">
		<h3>Login</h3> 
		<form action="/auth/login_post" method="post">
			<table border="0">
				<tr>
					<td>Email Address: </td><td><input type="text" name="email"></td>
				</tr>
				<tr>
					<td>Password: </td><td><input type="password" name="password"></td>
				</tr>
				<tr>
					<td><a href="/auth/register">Not a member? Register here.</a></td>
					<td align="right"><input type="submit" value="Login"></td></tr>
			</table>
		</form>
	</div>
</div>
