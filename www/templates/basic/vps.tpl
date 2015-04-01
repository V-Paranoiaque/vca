<h1 class="center">
	{if {$vps->nproc} == 0}
		<span class="glyphicon glyphicon-record offline" title="{'Offline'|gettext}"></span>
	{else}
		<span class="glyphicon glyphicon-record online" title="{'Online'|gettext}"></span>
	{/if}

	{$vps->name}</h1>

<div class="row center">
	<br/>
	
	<a href="#" title="{'Edit'|gettext}" onclick="popupVpsEdit({$vps->id});">
		<button class="btn btn-lg btn-primary" type="button">
			<span class="glyphicon glyphicon-pencil"></span>
		</button>
	</a>
	
	{if $vps->protected == 0}
		<a href="#" title="{'Reinstall'|gettext}" onclick="popupVpsReinstall({$vps->id});">
			<button class="btn btn-lg btn-warning" type="button">
				<span class="glyphicon glyphicon-cog"></span>
			</button>
		</a>
	{/if}
	
	<a href="#" title="{'Root password'|gettext}" onclick="popupVpsPassword({$vps->id});">
		<button class="btn btn-lg btn-warning" type="button">
			<span class="glyphicon glyphicon-lock"></span>
		</button>
	</a>
	
	<a href="#" title="{'Backups'|gettext}" onclick="popupBackupList({$vps->id});">
		<button class="btn btn-lg btn-info" type="button">
			<span aria-hidden="true" class="glyphicon glyphicon-hdd"></span>
		</button>
	</a>
	
	<a href="#" title="{'Schedule backups'|gettext}" onclick="popupBackupSchedule({$vps->id}, 0);">
		<button class="btn btn-lg btn-info" type="button">
			<span aria-hidden="true" class="glyphicon glyphicon-time"></span>
		</button>
	</a>
	
	<a href="#" title="{'Start'|gettext}" onclick="popupVpsStart({$vps->id});">
		<button class="btn btn-lg btn-success" type="button">
			<span class="glyphicon glyphicon-play"></span>
		</button>
	</a>
	<a href="#" title="{'Stop'|gettext}" onclick="popupVpsStop({$vps->id});">
		<button class="btn btn-lg btn-danger" type="button">
			<span class="glyphicon glyphicon-stop"></span>
		</button>
	</a>
	
	<a href="#" title="{'Restart'|gettext}" onclick="popupVpsRestart({$vps->id});">
		<button class="btn btn-lg btn-warning" type="button">
			<span class="glyphicon glyphicon-repeat"></span>
		</button>
	</a>

	{if $vps->serverId > 0}
		<a href="#" title="{'Reload'|gettext}" onclick="popupVpsReload({$vps->id});">
			<button class="btn btn-lg btn-primary" type="button">
				<span class="glyphicon glyphicon-refresh"></span>
			</button>
		</a>
		
		<a href="#" title="{'Clone'|gettext}" onclick="popupVpsClone({$vps->serverId}, {$vps->id});">
			<button class="btn btn-lg btn-info" type="button">
				<span class="glyphicon glyphicon-duplicate"></span>
			</button>
		</a>
		
		{if $vps->protected == 0}
			<a href="#" title="{'Delete'|gettext}" onclick="popupVpsDelete({$vps->id});">
				<button class="btn btn-lg btn-danger" type="button">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</a>
		{/if}
	{/if}
	<br/><br/><br/>
</div>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">{'Vps Statistics'|gettext}</h3>
	</div>
	<div class="panel-body">
	<table class="table table-invisible">
		<tr>
			<td>{'Cpu load'|gettext}</td>
			<td>{$vps->loadavg} ({'core'|gettext} : {$vps->cpus})</td>
		</tr>
		<tr>
			<td>{'Disk usage'|gettext}</td>
			<td>{$vps->diskspaceCurrent|numberDiskSpace}/{$vps->diskspace|numberDiskSpace}</td>
		</tr>
		<tr>
			<td>{'Memory usage'|gettext}</td>
			<td>{$vps->ramCurrent|numberRamSizeCurrent}/{$vps->ram|numberRamSize} ({'Swap'|gettext} : {$vps->swap|numberSwapSize})</td>
		</tr>
		<tr>
			<td>{'Backups'|gettext}</td>
			<td>{$nbCurrent}/{$vps->backup_limit|numberOrUnlimited}</td>
		</tr>
	</table>
	</div>
</div>
</div>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">{'Vps information'|gettext}</h3>
	</div>
	<div class="panel-body">
	<table class="table table-invisible">
		<tr>
			<td>{'Owner'|gettext}</td>
			<td>
			{if $vps->ownerId == 0}
				{'Nobody'|gettext}
			{else}
				{$vps->ownerName}
			{/if}
			</td>
		</tr>
		<tr>
			<td>{'On boot'|gettext}</td>
			<td>
			{if $vps->onboot == 0}
				{'No'|gettext}
			{else}
				{'Yes'|gettext}
			{/if}
			</td>
		</tr>
		<tr>
			<td>{'OS Template'|gettext}</td>
			<td>{$vps->ostemplate}</td>
		</tr>
		<tr>
			<td>{'IPv4'|gettext}</td>
			<td>{$vps->ipv4}</td>
		</tr>
	</table>
	</div>
</div>
</div>
{if $vps->serverId > 0}
	<div class="col-sm-12" id="shell">
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title center">{'Shell'|gettext}</h3>
		</div>
		<div class="panel-body">
			<div id="shell-result"></div>	
			<div id="shell-cmd"><div class="input-group">
				<input type="text" id="shell-input" placeholder="{'Command'|gettext}" class="form-control">
				<span class="input-group-addon" id="shell-send" onclick="formVpsCmd({$vps->id})">{'Send'|gettext}</span>
		</div></div>
		</div>
	</div>
	</div>
	
	<script type="text/javascript">
	$('body').keyup(function(e) {
		if(e.keyCode == 13) {
			$("#shell-send").click();
		}
	});
	</script>
{/if}
