(function(){

var user;

var Helpers = {};

Helpers.Time = {};
Helpers.Time.mins_to_string = function(mins)
{
	mins = new Number(mins);
	mins = mins.toFixed(0);
	var hours = (mins/60).toFixed(0);
	var mins_remainder = (mins%60);

	if(hours == 0){
		return mins_remainder+'mins';
	}
	if(mins_remainder == 0){
		return hours+'hr';
	}
	return hours+'hr '+mins_remainder+'mins';
}

var Controllers = {};
Controllers.Auth = {};

Controllers.Auth.login = function()
{
	var template = $('#auth_login_template').html();
	$('#content').html(template);
	Views.Header.login();
}

Controllers.Auth.login_post = function(e)
{
	var email = $('#login input[name=email]').val();
	var password = $('#login input[name=password]').val();
	$.post(
		'/auth/login.json', 
		{email: email, password: password},
		Controllers.Auth.got_user_info
	);
}

Controllers.Auth.got_user_info = function(json)
{
	if(json.error){
		return Views.Error.show(json.error);	
	}
	user = json.data;

	Views.Header.loggedin();
	
	Controllers.Periodoftime.view();
}

Controllers.Auth.logout = function(e)
{
	user = undefined;
	Controllers.Auth.login();
}

Controllers.Auth.register = function()
{
	var template = $('#auth_register_template').html();
	$('#content').html(template);
}

Controllers.Auth.register_post = function(e)
{
	var name = $('#content input[name=name]').val();
	var email = $('#content input[name=email]').val();
	var password = $('#content input[name=password]').val();
	var password_confirm = $('#content input[name=password_confirm]').val();
	$.post(
		'/auth/register.json', 
		{
			name: name,
			email: email, 
			password: password,
			password_confirm: password_confirm
		},
		Controllers.Auth.got_user_info
	);
}

Controllers.Periodoftime = {};

Controllers.Periodoftime.view = function()
{
	var template = $('#periodoftime_view_template').html();
	$('#content').html(template);

	$.get(
		'/periodoftime/list.json?hash='+user.login_hash, 
		Controllers.Periodoftime.got_times
	);
}

Controllers.Periodoftime.got_times = function(json)
{
	var times = json.data;
	if(times.length == 0){
		Views.Success.show("You do not have any projects. Please add a project");
		return Controllers.Project.add();
	}
	Views.Periodoftime.view(times);
}

Controllers.Periodoftime.add = function()
{
	var template = $('#periodoftime_add_template').html();
	$('#content').html(template);

	$.get(
		'/project/list.json?hash='+user.login_hash, 
		Controllers.Periodoftime.got_projects
	);
}

Controllers.Periodoftime.got_projects = function(json)
{
	var projects = json.data;
	Views.Periodoftime.add(projects);
}

Controllers.Periodoftime.add_post = function()
{
	var project_id = $('#content select[name=project_id]').val();
	var minutes = $('#content input[name=minutes]').val();
	$.post(
		'/periodoftime/list.json?hash='+user.login_hash, 
		{project_id: project_id, minutes: minutes},
		Controllers.Periodoftime.added
	);
}

Controllers.Periodoftime.added = function(json)
{
	if(json.error){
		return Views.Error.show(json.error);	
	}
	Controllers.Periodoftime.view();
}

Controllers.Periodoftime.delete = function(id)
{
	$.get(
		'/periodoftime/delete/'+id+'.json?hash='+user.login_hash, 
		Controllers.Periodoftime.deleted
	);
}

Controllers.Periodoftime.deleted = function(json)
{
	if(json.error){
		return Views.Error.show(json.error);	
	}
	Views.Periodoftime.delete(json.data);
}

Controllers.Project = {};

Controllers.Project.view = function()
{
	var template = $('#project_view_template').html();
	$('#content').html(template);
	$.get(
		'/project/list.json?hash='+user.login_hash, 
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
	var template = $('#project_add_template').html();
	$('#content').html(template);
}

Controllers.Project.add_post = function()
{
	var name = $('#content input[name=project_name]').val();
	
	$.post(
		'/project/list.json?hash='+user.login_hash, 
		{name: name},
		Controllers.Project.added
	);
}

Controllers.Project.added = function(json)
{
	if(json.error){
		return Views.Error.show(json.error);	
	}
	Controllers.Project.view();
}

Controllers.Project.delete = function(id)
{
	$.get(
		'/project/delete/'+id+'.json?hash='+user.login_hash, 
		Controllers.Project.deleted
	);
}

Controllers.Project.deleted = function(json)
{
	if(json.error){
		return Views.Error.show(json.error);	
	}
	Views.Project.delete(json.data);
}

Controllers.Project.timetotals = function(start)
{
	if(!start){
		start = '';
	}
	var template = $('#project_timetotals_template').html();
	$('#content').html(template);

	$.get(
		'/project/timetotals/'+start+'.json?hash='+user.login_hash, 
		Controllers.Project.got_times
	);
}

Controllers.Project.got_times = function(json)
{
	if(json.error){
		return Views.Error.show(json.error);	
	}
	console.log(json.data);
	Views.Project.timetotals(json.data);
}


var Views = {};
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

	$('#content .project_times_table cell_dynamic').remove();
	$.each(projects, function(key, project){
		var header_cell = '<th>'+project.name+'</th>';
		var body_cell = '<td>'+project.timetotal_for_range+'</td>';

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

function init()
{
	Controllers.Auth.login();
}

function prepare_actions()
{
	$(document).on('click', 'a', handle_click);
}

function handle_click(e)
{
	$this = $(this);
	e.preventDefault();
	url = $this.attr('href');
	url_parts = url.split('/');

	var controller = url_parts[1];
	var action = url_parts[2];
	var param = url_parts[3];
	controller_ucfirst = controller.substring(0,1).toUpperCase()+controller.substring(1);

	Views.Error.hide();
	Views.Success.hide();

	Controllers[controller_ucfirst][action](param);
}

$(function(){
	prepare_actions();
	init();
});

})();