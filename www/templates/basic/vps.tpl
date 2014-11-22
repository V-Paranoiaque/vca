<h1 class="center">
	{if {$vps->nproc} == 0}
		<span class="glyphicon glyphicon-record offline"></span>
	{else}
		<span class="glyphicon glyphicon-record online"></span>
	{/if}

	{$vps->name}</h1>

<div class="rows center bs-glyphicons">
	<br/>
	
	<a href="#" title="{$Edit}"    onclick="popupVpsEdit({$vps->id});"><span class="glyphicon glyphicon-pencil"></span></a>
	<a href="#" title="{$Reinstall}" onclick="popupVpsReinstall({$vps->id});"><span class="glyphicon glyphicon-cog"></span></a>
	<a href="#" title="{$Rootpassword}" onclick="popupVpsPassword({$vps->id});"><span class="glyphicon glyphicon-lock"></span></a>
	<a href="#" title="{$Start}"   onclick="popupVpsStart({$vps->id});"><span class="glyphicon glyphicon-play"></span></a>
	<a href="#" title="{$Stop}"    onclick="popupVpsStop({$vps->id});"><span class="glyphicon glyphicon-stop"></span></a>
	<a href="#" title="{$Restart}" onclick="popupVpsRestart({$vps->id});"><span class="glyphicon glyphicon-repeat"></span></a>
	{if $vps->serverId > 0}
	  <a href="#" title="{$Reload}"  onclick="popupVpsReload({$vps->id});"><span class="glyphicon glyphicon-refresh"></span></a>
	  <a href="#" title="{$Clone}"   onclick="popupVpsClone({$vps->id});"><span class="glyphicon glyphicon-tags"></span></a>
	  <a href="#" title="{$Delete}"  onclick="popupVpsDelete({$vps->id});"><span class="glyphicon glyphicon-remove"></span></a>
	{/if}
	<br/><br/><br/>
</div>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">{$VpsStatistics}</h3>
	</div>
	<div class="panel-body">
	<table>
		<tr>
			<td>{$CPUload}</td>
			<td>{$vps->loadavg} ({$core} : {$vps->cpus})</td>
		</tr>
		<tr>
			<td>{$Diskusage}</td>
			<td>{$vps->diskspaceCurrent|numberDiskSpace}/{$vps->diskspace|numberDiskSpace}</td>
		</tr>
		<tr>
			<td>{$Memoryusage}</td>
			<td>{$vps->ramCurrent|numberRamSizeCurrent}/{$vps->ram|numberRamSize} (swap : {$vps->swap|numberSwapSize})</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td></td>
		</tr>
	</table>
	</div>
</div>
</div>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">{$Vpsinformation}</h3>
	</div>
	<div class="panel-body">
	<table>
		<tr>
			<td>{$Owner}</td>
			<td>
			{if $vps->ownerId == 0}
				{$Nobody}
			{else}
				{$vps->ownerName}
			{/if}
			</td>
		</tr>
		<tr>
			<td>{$Onboot}</td>
			<td>
			{if $vps->onboot == 0}
				{$No}
			{else}
				{$Yes}
			{/if}
			</td>
		</tr>
		<tr>
			<td>{$OSTemplate}</td>
			<td>{$vps->ostemplate}</td>
		</tr>
		<tr>
			<td>IPv4</td>
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
			<h3 class="panel-title center">{$Shell}</h3>
		</div>
		<div class="panel-body">
			<div id="shell-result"></div>	
			<div id="shell-cmd"><div class="input-group">
	      <input type="text" id="shell-input" placeholder="{$Command}" class="form-control">
	      <span class="input-group-addon" id="shell-send" onclick="formVpsCmd({$vps->id})">{$Send}</span>
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
