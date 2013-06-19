<div class="row-fluid">
	<div class="span12">
		<div class="navbar">
		  	<div class="navbar-inner">
			    <a class="brand" href="/">Momentum</a>
			    <a href="#" class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                	<span class="icon-bar"></span>
                	<span class="icon-bar"></span>
                	<span class="icon-bar"></span>
            	</a>
			    <ul class="menu nav nav-collapse collapse navbar-responsive-collapse">
					<li class="<?=($active=='periodoftime/view')?'active':''?>"><a href="/periodoftime/view">Times</a></li>
					<li class="<?=($active=='project/view')?'active':''?>"><a href="/project/view">View Projects</a></li>
					<li class="<?=($active=='project/timetotals')?'active':''?>"><a href="/project/timetotals">Project times</a></li>
				</ul>
				<ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$member->user->name?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          	<li><a href="/auth/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
			</div>

		</div>
	</div>
</div>