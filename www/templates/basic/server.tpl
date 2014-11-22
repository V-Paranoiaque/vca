<h2 class="sub-header">{$Serverlist} <a href="#" onclick="popupServerAdd()"><span class="glyphicon glyphicon-plus"></span></a></h2>

{if isset($serverList) }
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{$Name}</th>
					<th>{$Address}</th>
					<th>{$Vps}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$serverList item=server}
				<tr>
				  <td><a href="/vpslist/{$server->id}">{$server->name}</a></td>
				  <td>{$server->address}</td>
				  <td>{$server->nbvps}</td>
				  <td>
				  	<a href="/vpslist/{$server->id}" title="{$Vpslist}"><span class="glyphicon glyphicon-align-justify"></span></a>
				  	<a href="#" title="{$Reloadtheserver}" onclick="popupServerReload({$server->id});"><span class="glyphicon glyphicon-refresh"></span></a>
				  	<a href="#" title="{$Restarttheserver}" onclick="popupServerRestart({$server->id});"><span class="glyphicon glyphicon-repeat"></span></a>
				  	<a href="/serverinfo/{$server->id}" title="{$Edit}"><span class="glyphicon glyphicon-pencil"></span></a>
				  	<a href="#" title="{$Remove}" onclick="popupServerRemove({$server->id});"><span class="glyphicon glyphicon-remove"></span></a>
				  </td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}
