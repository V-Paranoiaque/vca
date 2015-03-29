<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">{'Toggle navigation'|gettext}</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-logo"	href="/" title="{'Virtual Control Admin'|gettext}"><img src="/images/logo.png" alt="{'Virtual Control Admin'|gettext}" /></a> 
			<a class="navbar-brand" href="/" title="{'Virtual Control Admin'|gettext}">
				<span class="invisible-xs">{'Virtual Control Admin'|gettext}</span>
				<span class="display-xs">{'VCA'}</span>
			</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
			<!--<li><a href="/settings" title="{'Settings'|gettext}">{'Settings'|gettext}</a></li>-->
				<li><a href="/profile" title="{'Profile'|gettext}"><span aria-hidden="true" class="glyphicon glyphicon-user"></span> {'Profile'|gettext}</a></li>
				<li><a href="https://github.com/V-Paranoiaque/vca" title="{'Help'|gettext}" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-question-sign"></span> {'Help'|gettext}</a></li>
				<li><a href="#" title="{'Logout'|gettext}" onclick="logout()"><span aria-hidden="true" class="glyphicon glyphicon-off"></span> {'Logout'|gettext}</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-1 col-sm-3 col-md-3 col-lg-2 sidebar">
			<ul class="nav nav-sidebar">
				<li {if $currentPage == 'home'} class="active" {/if}>
					<a href="/" title="{'Dashboard'|gettext}">
						<span aria-hidden="true" class="glyphicon glyphicon-th-large"></span> 
						<span class="invisible-xs">{'Dashboard'|gettext}</span>
					</a>
				</li>
				{if {$userRank} > 0}
				<li {if $currentPage == 'server'} class="active" {/if}>
					<a href="/server" title="{'Physical servers'|gettext}">
						<span aria-hidden="true" class="glyphicon glyphicon-th-list"></span> 
						<span class="invisible-xs">{'Physical servers'|gettext}</span>
					</a>
				</li>
				{/if}
				{if isset($serverList) }
					{foreach from=$serverList item=server}
						<li class="smenu {if $currentPage == 'vpslist' and ${vpsCurrent} == {$server->id} } active {/if}">
							<a href="/vpslist/{$server->id}" title="{$server->name}">
								<span aria-hidden="true" class="glyphicon glyphicon-hdd"></span>
								<span class="invisible-xs">{$server->name} <span class="badge">{$server->nbvps}</span></span>
							</a>
						</li>
					{/foreach}
				{/if}
				{if {$userRank} == 0}
					<li {if $currentPage == 'vpslist' and ${vpsCurrent} == 0 } class="active" {/if}>
						<a href="/vpslist" title="{'Virtual servers'|gettext}">
							<span aria-hidden="true" class="glyphicon glyphicon-hdd"></span> 
							<span class="invisible-xs">{'Virtual servers'|gettext} <span class="badge">{$vpsNb}</span></span>
						</a>
					</li>
				{else}
					<li {if $currentPage == 'ip'} class="active" {/if}>
						<a href="/ip" title="{'IP Addresses'|gettext}">
							<span aria-hidden="true" class="glyphicon glyphicon-globe"></span>
							<span class="invisible-xs">{'IP Addresses'|gettext}</span>
						</a>
					</li>
				{/if}
				<li {if $currentPage == 'request'} class="active" {/if}>
					<a href="/request" title="{'Requests'|gettext}">
						<span aria-hidden="true" class="glyphicon glyphicon-tags"></span>
						<span class="invisible-xs">{'Requests'|gettext}</span>
					</a>
				</li>
				{if {$userRank} > 0}
					<li {if $currentPage == 'user'} class="active" {/if}>
						<a href="/user" title="{'Users'|gettext}">
							<span aria-hidden="true" class="glyphicon glyphicon-user"></span>
							<span class="invisible-xs">{'Users'|gettext}</span>
						</a>
					</li>
				{/if}
			</ul>
		</div>
		<div class="col-xs-offset-1 col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-10 col-lg-offset-2 main">
