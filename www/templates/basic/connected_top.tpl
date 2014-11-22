<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">{'Toggle navigation'|gettext}</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-logo"  href="/" title="{'Virtual Control Admin'|gettext}"><img src="/images/logo.png" alt="{'Virtual Control Admin'|gettext}" /></a> 
			<a class="navbar-brand" href="/" title="{'Virtual Control Admin'|gettext}">{'Virtual Control Admin'|gettext}</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
			<!--<li><a href="/settings" title="{'Settings'|gettext}">{'Settings'|gettext}</a></li>-->
				<li><a href="/profile"  title="{'Profile'|gettext}"><span aria-hidden="true" class="glyphicon glyphicon-user"></span> {'Profile'|gettext}</a></li>
				<li><a href="/help" title="{'Help'|gettext}"><span aria-hidden="true" class="glyphicon glyphicon-question-sign"></span> {'Help'|gettext}</a></li>
				<li><a href="#" title="{'Logout'|gettext}" onclick="logout()"><span aria-hidden="true" class="glyphicon glyphicon-off"></span> {'Logout'|gettext}</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li {if $currentPage == 'home'} class="active" {/if}><a href="/" title="{'Dashboard'|gettext}">{'Dashboard'|gettext}</a></li>
				{if isset($serverList) }
					<li {if $currentPage == 'server'} class="active" {/if}><a href="/server" title="{'Physical Servers'|gettext}">{'Physical Servers'|gettext}</a></li>
						{foreach from=$serverList item=server}
							<li class="smenu {if $currentPage == 'vpslist' and ${vpsCurrent} == {$server->id} } active {/if}"><a href="/vpslist/{$server->id}" title="{$server->name}">{$server->name} <span class="badge">{$server->nbvps}</span></a></li>
						{/foreach}
				{/if}
				{if {$userRank} == 0}
					<li {if $currentPage == 'vpslist' and ${vpsCurrent} == 0 } class="active" {/if}><a href="/vpslist" title="{'Virtual Servers'|gettext}">{'Virtual Servers'|gettext} <span class="badge">{$vpsNb}</span></a></li>
				{else}
					<li {if $currentPage == 'ip'} class="active" {/if}><a href="/ip" title="{'IP Addresses'|gettext}">{'IP Addresses'|gettext}</a></li>
				{/if}
			</ul>
			{if {$userRank} > 0}
				<ul class="nav nav-sidebar">
					<li {if $currentPage == 'user'} class="active" {/if}><a href="/user" title="{'Users'|gettext}">{'Users'|gettext}</a></li>
				</ul>
			{/if}
		</div>
    	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
