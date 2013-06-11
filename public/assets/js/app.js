(function(){

var user;

var Helpers = {};

Helpers.Time = {};
Helpers.Time.mins_to_string = function(mins)
{
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

Controllers.Periodoftime = {};

Controllers.Periodoftime.view = function()
{
	var template = $('#periodoftime_view_template').html();
	$('#content').html(template);
}

Controllers.Periodoftime.add = function()
{
	var template = $('#periodoftime_add_template').html();
	$('#content').html(template);
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
Views.Success.show = function()
{
	$("#success_box").fadeIn();
	$("#success_box .error_message").html(error);
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

	Controllers[controller_ucfirst][action](param);
}

$(function(){
	prepare_actions();
	init();
});

})();