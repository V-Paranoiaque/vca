<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">{$Togglenavigation}</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-logo" href="/" title="Virtual Control Admin"><img src="images/logo.png" alt="{'Virtual Control Admin'|gettext}" /></a> 
			<a class="navbar-brand" href="/" title="Virtual Control Admin">Virtual Control Admin</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
			<!--<li><a href="/settings" title="{$Settings}">{$Settings}</a></li>-->
				<li><a href="/profile"  title="{$Profile}"><span aria-hidden="true" class="glyphicon glyphicon-user"></span> {$Profile}</a></li>
				<li><a href="/help" title="{$Help}"><span aria-hidden="true" class="glyphicon glyphicon-question-sign"></span> {$Help}</a></li>
				<li><a href="#" title="{$Logout}" onclick="logout()"><span aria-hidden="true" class="glyphicon glyphicon-off"></span> {$Logout}</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li {if $currentPage == 'home'} class="active" {/if}><a href="/" title="{$Dashboard}">{$Dashboard}</a></li>
				{if isset($serverList) }
					<li {if $currentPage == 'server'} class="active" {/if}><a href="/server" title="{$PhysicalServers}">{$PhysicalServers}</a></li>
						{foreach from=$serverList item=server}
							<li class="smenu {if $currentPage == 'vpslist' and ${vpsCurrent} == {$server->id} } active {/if}"><a href="/vpslist/{$server->id}" title="{$server->name}">{$server->name} <span class="badge">{$server->nbvps}</span></a></li>
						{/foreach}
				{/if}
				{if {$userRank} == 0}
					<li {if $currentPage == 'vpslist' and ${vpsCurrent} == 0 } class="active" {/if}><a href="/vpslist" title="{$VirtualServers}">{$VirtualServers} <span class="badge">{$vpsNb}</span></a></li>
				{else}
					<li {if $currentPage == 'ip'} class="active" {/if}><a href="/ip" title="{$IPAddresses}">{$IPAddresses}</a></li>
				{/if}
			</ul>
			{if {$userRank} > 0}
				<ul class="nav nav-sidebar">
					<li {if $currentPage == 'user'} class="active" {/if}><a href="/user" title="{$Users}">{$Users}</a></li>
				</ul>
			{/if}
		</div>
    	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
