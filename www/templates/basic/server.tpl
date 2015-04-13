<h2 class="sub-header">
	{'Server list'|gettext} 
	<a href="#" onclick="popupServerAdd()" title="{'Add a new server'|gettext}" class="btn btn-success">
		<span class="glyphicon glyphicon-plus"></span>
	</a>
</h2>

{if isset($serverList) }
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'Name'|gettext}</th>
					<th>{'Address'|gettext}</th>
					<th>{'Port'|gettext}</th>
					<th>{'Vps'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$serverList item=server}
				<tr>
					<td><a href="/vpslist/{$server->id}" title="{$server->name}">{$server->name}</a></td>
					<td>{$server->address}</td>
					<td>{$server->port}</td>
					<td>{$server->nbvps}</td>
					<td>
						<a href="/vpslist/{$server->id}" title="{'Vps list'|gettext}" class="btn btn-success">
							<span class="glyphicon glyphicon-align-justify"></span>
						</a>
						<a href="/template/{$server->id}" title="{'Template list'|gettext}" class="btn btn-info">
							<span class="glyphicon glyphicon-folder-open"></span>
						</a>
						<a href="/backup/{$server->id}" title="{'Backup list'|gettext}" class="btn btn-info">
							<span class="glyphicon glyphicon-hdd"></span>
						</a>
						<a href="/avscan/{$server->id}" title="{'Antivirus scan'|gettext}" class="btn btn-danger">
							<span aria-hidden="true" class="glyphicon glyphicon-screenshot"></span>
						</a>
						<a href="#" title="{'Reload the server'|gettext}" onclick="popupServerReload({$server->id});" class="btn btn-primary">
							<span class="glyphicon glyphicon-refresh"></span>
						</a>
						<a href="#" title="{'Restart the server'|gettext}" onclick="popupServerRestart({$server->id});" class="btn btn-warning">
							<span class="glyphicon glyphicon-repeat"></span>
						</a>
						<a href="#" title="{'Edit'|gettext}" onclick="popupServerEdit({$server->id});" class="btn btn-primary">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<a href="#" title="{'Remove'|gettext}" onclick="popupServerRemove({$server->id});" class="btn btn-danger">
							<span class="glyphicon glyphicon-remove"></span>
						</a>
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}
