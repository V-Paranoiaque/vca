<h2 class="sub-header">{'Server list'|gettext} <a href="#" onclick="popupServerAdd()" title="{'Add a new server'|gettext}"><span class="glyphicon glyphicon-plus"></span></a></h2>

{if isset($serverList) }
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'Name'|gettext}</th>
					<th>{'Address'|gettext}</th>
					<th>{'Vps'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$serverList item=server}
				<tr>
				  <td><a href="/vpslist/{$server->id}" title="{$server->name}">{$server->name}</a></td>
				  <td>{$server->address}</td>
				  <td>{$server->nbvps}</td>
				  <td>
				  	<a href="/vpslist/{$server->id}" title="{'Vps list'|gettext}"><span class="glyphicon glyphicon-align-justify"></span></a>
				  	<a href="/template/{$server->id}" title="{'Template list'|gettext}"><span class="glyphicon glyphicon-folder-open"></span></a>
				  	<a href="/backup/{$server->id}" title="{'Backup list'|gettext}"><span class="glyphicon glyphicon-hdd"></span></a>
				  	<a href="#" title="{'Reload the server'|gettext}" onclick="popupServerReload({$server->id});"><span class="glyphicon glyphicon-refresh"></span></a>
				  	<a href="#" title="{'Restart the server'|gettext}" onclick="popupServerRestart({$server->id});"><span class="glyphicon glyphicon-repeat"></span></a>
				  	<a href="#" title="{'Edit'|gettext}" onclick="popupServerEdit({$server->id});"><span class="glyphicon glyphicon-pencil"></span></a>
				  	<a href="#" title="{'Remove'|gettext}" onclick="popupServerRemove({$server->id});"><span class="glyphicon glyphicon-remove"></span></a>
				  </td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}
