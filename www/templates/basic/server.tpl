<h2 class="sub-header">
	{'Server list'|gettext} 
	<a href="#" onclick="popupServerAdd()" title="{'Add a new server'|gettext}">
		<button class="btn btn-success" type="button">
			<span class="glyphicon glyphicon-plus"></span>
		</button>
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
						<a href="/vpslist/{$server->id}" title="{'Vps list'|gettext}">
							<button class="btn btn-success" type="button">
								<span class="glyphicon glyphicon-align-justify"></span>
							</button>
						</a>
						<a href="/template/{$server->id}" title="{'Template list'|gettext}">
							<button class="btn btn-info" type="button">
								<span class="glyphicon glyphicon-folder-open"></span>
							</button>
						</a>
						<a href="/backup/{$server->id}" title="{'Backup list'|gettext}">
							<button class="btn btn-info" type="button">
								<span class="glyphicon glyphicon-hdd"></span>
							</button>
						</a>
						<a href="#" title="{'Reload the server'|gettext}" onclick="popupServerReload({$server->id});">
							<button class="btn btn-primary" type="button">
								<span class="glyphicon glyphicon-refresh"></span>
							</button>
						</a>
						<a href="#" title="{'Restart the server'|gettext}" onclick="popupServerRestart({$server->id});">
							<button class="btn btn-warning" type="button">
								<span class="glyphicon glyphicon-repeat"></span>
							</button>
						</a>
						<a href="#" title="{'Edit'|gettext}" onclick="popupServerEdit({$server->id});">
							<button class="btn btn-primary" type="button">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
						</a>
						<a href="#" title="{'Remove'|gettext}" onclick="popupServerRemove({$server->id});">
							<button class="btn btn-danger" type="button">
								<span class="glyphicon glyphicon-remove"></span>
							</button>
						</a>
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}
