(function(){

//Stores the current users details
var user;

//Helpers for rendering
var Helpers = {};

Helpers.Time = {};
Helpers.Time.mins_to_string = function(mins)
{
	mins = new Number(mins);
	mins = Math.floor(mins);
	var hours = Math.floor(mins/60);
	var mins_remainder = (mins%60);

	if(hours == 0){
		return mins_remainder+'mins';
	}
	if(mins_remainder == 0){
		return hours+'hr';
	}
	return hours+'hr '+mins_remainder+'mins';
}

//Collection of models and helper functions
var Models = {};

Models.get = function(url, callback)
{
	signed_url = Models.sign_url(url);
	$.get(
		signed_url, 
		function(json){
			if(json.error){
				if(json.type == 'hash'){
					Views.Error.show(json.error);	
					return Controllers.Auth.logout();
				}
				return Views.Error.show(json.error);	
			}
			callback(json);
		}
	);
}

Models.sign_url = function(url)
{
	if(!user){
		return url;
	}
	return url+'?hash='+user.login_hash;
}

Models.post = function(url, data, callback)
{
	signed_url = Models.sign_url(url);
	$.post(
		signed_url, 
		data,
		function(json){
			if(json.error){
				Views.Error.show(json.error);
				if(json.type == 'hash'){	
					return Controllers.Auth.logout();
				}
				return false;
			}
			callback(json);
		}
	);
}

Models.User = {};

Models.User.login = function(data, callback)
{
	Models.post(
		'/auth/login.json', 
		data,
		callback
	);
}

Models.User.register = function(data, callback)
{
	Models.post(
		'/auth/register.json', 
		data,
		callback
	);
}

Models.User.get_local_user = function()
{
	var user_json = localStorage.user;

	if(!user_json || user_json=='undefined'){
		return undefined;
	}
	console.log(typeof(user_json));

	var local_user = $.parseJSON(user_json);
	return local_user;
}

Models.User.set_local_user = function(user)
{
	localStorage.user = JSON.stringify(user);
}

Models.User.remove_local_user = function()
{
	localStorage.removeItem('user');
}

Models.Periodoftime = {};

Models.Periodoftime.list = function(callback)
{
	Models.get(
		'/periodoftime/list.json', 
		callback
	);
}

Models.Periodoftime.add = function(data, callback)
{
	Models.post(
		'/periodoftime/list.json', 
		data,
		callback
	);
}

Models.Periodoftime.delete = function(id, callback)
{
	Models.get(
		'/periodoftime/delete/'+id+'.json', 
		callback
	);
}

Models.Project = {};

Models.Project.list = function(callback)
{	
	Models.get(
		'/project/list.json', 
		callback
	);
}

Models.Project.add = function(data, callback)
{	
	Models.post(
		'/project/list.json', 
		data,
		callback
	);
}

Models.Project.delete = function(id, callback)
{	
	Models.get(
		'/project/delete/'+id+'.json', 
		callback
	);
}

Models.Project.timetotals = function(start_date, callback)
{	
	Models.get(
		'/project/timetotals/'+start_date+'.json', 
		callback
	);
}

//The controllers for handling user interation with the system
var Controllers = {};
Controllers.Auth = {};

Controllers.Auth.login = function()
{
	user = Models.User.get_local_user();
	if(user){
		Views.Header.loggedin();	
		Controllers.Periodoftime.view();
	}else{
		Views.Page.change('auth_login');
		Views.Header.login();
	}
}

Controllers.Auth.login_post = function(e)
{
	var email = $('#login input[name=email]').val();
	var password = $('#login input[name=password]').val();
	Models.User.login(
		{email:email, password:password},
		Controllers.Auth.got_user_info
	);
}

Controllers.Auth.got_user_info = function(json)
{
	user = json.data;
	Models.User.set_local_user(user);
	Views.Header.loggedin();	
	Controllers.Periodoftime.view();
}

Controllers.Auth.logout = function(e)
{
	user = undefined;
	Models.User.remove_local_user();
	Controllers.Auth.login();
}

Controllers.Auth.register = function()
{
	Views.Page.change('auth_register');
}

Controllers.Auth.register_post = function(e)
{
	var name = $('#content input[name=name]').val();
	var email = $('#content input[name=email]').val();
	var password = $('#content input[name=password]').val();
	var password_confirm = $('#content input[name=password_confirm]').val();
	
	Models.User.register(
		{
			name: name,
			email: email, 
			password: password,
			password_confirm: password_confirm
		},
		Controllers.Auth.got_registered_user_info
	);
}

Controllers.Auth.got_registered_user_info = function(json)
{
	user = json.data;
	Views.Header.loggedin();
	Views.Success.show("You do not have any projects. Please add a project to start using momentum");	
	Controllers.Project.add();
}

Controllers.Periodoftime = {};

Controllers.Periodoftime.view = function()
{
	Views.Page.change('periodoftime_view');

	Models.Periodoftime.list(
		Controllers.Periodoftime.got_times
	);
}

Controllers.Periodoftime.got_times = function(json)
{
	var times = json.data;
	if(times.length == 0){
		Views.Success.show("You do not have any times. Please add a time to start using momentum");
		return Controllers.Periodoftime.add();
	}
	Views.Periodoftime.view(times);
}

Controllers.Periodoftime.add = function()
{
	Views.Page.change('periodoftime_add');

	Models.Project.list(
		Controllers.Periodoftime.got_projects
	);
}

Controllers.Periodoftime.got_projects = function(json)
{
	var projects = json.data;
	if(projects.length == 0){
		Views.Success.show("You do not have any projects. Please add a project to start using momentum");
		return Controllers.Project.add();
	}
	Views.Periodoftime.add(projects);
}

Controllers.Periodoftime.add_post = function()
{
	var project_id = $('#content select[name=project_id]').val();
	var minutes = $('#content input[name=minutes]').val();
	
	Models.Periodoftime.add(
		{project_id: project_id, minutes: minutes},
		Controllers.Periodoftime.added
	);
}

Controllers.Periodoftime.added = function(json)
{
	Controllers.Periodoftime.view();
}

Controllers.Periodoftime.delete = function(id)
{
	Models.Periodoftime.delete(
		id,
		Controllers.Periodoftime.deleted
	);
}

Controllers.Periodoftime.deleted = function(json)
{
	Controllers.Periodoftime.view();
}

Controllers.Project = {};

Controllers.Project.view = function()
{
	Views.Page.change('project_view');
	Models.Project.list(
		Controllers.Project.got_projects
	);
}

Controllers.Project.got_projects = function(json)
{
	var projects = json.data;
	Views.Project.view(projects);
}

Controllers.Project.add = function()
{
	Views.Page.change('project_add');
}

Controllers.Project.add_post = function()
{
	var name = $('#content input[name=project_name]').val();
	
	Models.Project.add(
		{name: name},
		Controllers.Project.added
	);
}

Controllers.Project.added = function(json)
{
	Controllers.Project.view();
}

Controllers.Project.delete = function(id)
{
	Models.Project.delete(
		id,
		Controllers.Project.deleted
	);
}

Controllers.Project.deleted = function(json)
{
	Views.Project.delete(json.data);
}

Controllers.Project.timetotals = function(start)
{
	if(!start){
		start = '';
	}

	Views.Page.change('project_timetotals');

	Models.Project.timetotals(
		start,
		Controllers.Project.got_times
	);
}

Controllers.Project.got_times = function(json)
{
	Views.Project.timetotals(json.data);
}

//Views and helper functions, handles rendering of data
var Views = {};
Views.Page = {};
Views.Page.change = function(view)
{
	$("#previous_content").html($("#content").html());
	
	var template = $('#'+view+'_template').html();

	$("#content").hide();
	$("#content").html(template);

	$('#previous_content').fadeOut(
		300,
		function(){
			$("#content").fadeIn(300);
			$("#previous_content").html('');
		}
	);
}
Views.Header = {};
Views.Header.login = function()
{
	var header = $('#header_login_template').html();
	$('#header').html(header);
}

Views.Header.loggedin = function()
{
	var header = $('#header_loggedin_template').html();
	
	$('#header').html(header);
	$('#header .username').html(user.name);
}
Views.Header.activate_button = function(button_link)
{
	$("#header .menu li.active").removeClass('active');
	$('#header').find('a[href="'+button_link+'"]').parent().addClass('active');
}

Views.Error = {};
Views.Error.show = function(error)
{
	$("#error_box").fadeIn();
	$("#error_box .error_message").html(error);
}
Views.Error.hide = function()
{
	$("#error_box").fadeOut();
}

Views.Success = {};
Views.Success.show = function(message)
{
	$("#success_box").fadeIn();
	$("#success_box .success_message").html(message);
}
Views.Success.hide = function()
{
	$("#success_box").fadeOut();
}

Views.Project = {};

Views.Project.view = function(projects)
{
	$("#content .projects .row_dynamic").remove();
	$.each(projects, function(key, project){
		
		var row = $("#content .projects .row_template").clone();
		row.attr('id', 'project_'+project.id);
		row.find('.project').html(project.name);
		row.find('.time').html(Helpers.Time.mins_to_string(project.totaltime));
		
		var href = row.find('a').attr('href')+'/'+project.id;
		row.find('a').attr('href', href);

		row.removeClass('row_template');
		row.addClass('row_dynamic');
		
		row.hide();
		$("#content .projects tbody").append(row);
		row.fadeIn();
	});
}

Views.Project.delete = function(project)
{
	$('#project_'+project.id).fadeOut();
}

Views.Project.timetotals = function(data)
{
	var projects = data.projects;

	$('#content .week_start').html(data.week_start);
	$('#content .week_end').html(data.week_end);

	var prev_href = $('#content .totalschart_prev').attr('href');
	$('#content .totalschart_prev').attr('href', prev_href+data.week_prev);

	var next_href = $('#content .totalschart_next').attr('href');
	$('#content .totalschart_next').attr('href', next_href+data.week_next);

	$('#content .cell_dynamic').remove();
	$.each(projects, function(key, project){
		var header_cell = '<th class="cell_dynamic">'+project.name+'</th>';
		var body_cell = '<td class="cell_dynamic">'+project.timetotal_for_range+'</td>';

		$('#content .project_times_table thead tr').append(header_cell);
		$('#content .project_times_table tbody tr').append(body_cell);
	});

	Chart.render_table_chart( $('#content .project_times_table'), 'column');
}

Views.Periodoftime = {};

Views.Periodoftime.view = function(times)
{
	var total = 0;
	$("#content .times .row_dynamic").remove();
	$.each(times, function(key, time){
		
		var row = $("#content .times .row_template").clone();
		row.attr('id', 'time_'+time.id);
		row.find('.project').html(time.project.name);
		row.find('.time').html(Helpers.Time.mins_to_string(time.minutes));

		var href = row.find('a').attr('href')+'/'+time.id;
		row.find('a').attr('href', href);

		row.removeClass('row_template');
		row.addClass('row_dynamic');
		
		row.hide();
		$("#content .times tbody").append(row);
		row.fadeIn();

		total += new Number(time.minutes);
	});

	$("#content .times .total").html(
		Helpers.Time.mins_to_string(total)
	);
}

Views.Periodoftime.add = function(projects)
{
	$("#content .project_id option").remove();
	$.each(projects, function(key, project){
		
		var option = '<option value="'+project.id+'">'+project.name+'</option>';
		console.log(option);
		$("#content select[name=project_id]").append(option);
	});
}

Views.Periodoftime.delete = function(project)
{
	$('#time_'+project.id).fadeOut();
}

App = {};
App.init = function()
{
	$(document).on('click', 'a', App.handle_click);
	Controllers.Auth.login();
}

//User the HREF attribute to figure out which controller and action to call
App.handle_click = function(e)
{
	$this = $(this);

	if($this.hasClass('external_link')){
		window.location.href = $this.attr('href');
	}

	e.preventDefault();
	url = $this.attr('href');
	url_parts = url.split('/');

	var controller = url_parts[1];
	var action = url_parts[2];
	var param = url_parts[3];

	if(!action){
		return false;
	}
	
	controller_ucfirst = controller.substring(0,1).toUpperCase()+controller.substring(1);

	Views.Error.hide();
	Views.Success.hide();
	Views.Header.activate_button(url);

	Controllers[controller_ucfirst][action](param);
}

$(function(){
	App.init();
});

})();