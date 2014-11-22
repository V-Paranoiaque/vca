{*Load number modifier*}
{0|number}
<div class="col-sm-6">
	<h2 class="sub-header">{$Title}{if {$userRank>0}} <a href="#" onclick="popupVpsAdd({$serverCurrent})"><span class="glyphicon glyphicon-plus"></span></a>{/if}</h2>
</div>

<div class="col-sm-6">
	<div id="vpsAll" role="alert" class="alert alert-info center">
		<a href="#" title="{$Start}"   onclick="popupVpsStartAll({$serverCurrent});"><span class="glyphicon glyphicon-play"></span></a>
		<a href="#" title="{$Stop}"    onclick="popupVpsStopAll({$serverCurrent});"><span class="glyphicon glyphicon-stop"></span></a>
		<a href="#" title="{$Restart}" onclick="popupVpsRestartAll({$serverCurrent});"><span class="glyphicon glyphicon-repeat"></span></a>
	</div>
</div>

<div class="col-sm-12">
{if isset($vpsList) }
	<div class="table-responsive"><br/>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><input type="checkbox" id="vps_list" /></th>
					<th></th>
					<th>{$Name}</th>
					<th>{$Ipv4}</th>
					<th>{$Memory}</th>
					<th>{$Disk}</th>
					<th>{$OsTemplate}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$vpsList item=server}
				<tr>
				  <td><input type="checkbox" class="vps_list" name="vps_list[]" value="{$server->id}"/></td>
				  <td>
				  
				  {if {$server->nproc} == 0}
				  	<span class="glyphicon glyphicon-record offline"></span>
				  {else}
				  	<span class="glyphicon glyphicon-record online"></span>
				  {/if}
				  
				  </td>
				  <td><a href="/vps/{$server->id}" title="{$Informations}">{$server->name}</a></td>
				  <td>{$server->ipv4}</td>
				  <td>{$server->ramCurrent|numberRamSizeCurrent}/{$server->ram|numberRamSize}</td>
				  <td>{$server->diskspaceCurrent|numberDiskSpace}/{$server->disk|numberDiskSpace}</td>
				  <td>{$server->ostemplate}</td>
				  <td>
				  	<a href="/vps/{$server->id}" title="{$Informations}"><span class="glyphicon glyphicon-align-justify"></span></a>
				  	<a href="#" title="{$Start}" onclick="popupVpsStart({$server->serverId}, {$server->id});"><span class="glyphicon glyphicon-play"></span></a>
				  	<a href="#" title="{$Stop}" onclick="popupVpsStop({$server->serverId}, {$server->id});"><span class="glyphicon glyphicon-stop"></span></a>
				  	<a href="#" title="{$Restart}" onclick="popupVpsRestart({$server->serverId}, {$server->id});"><span class="glyphicon glyphicon-repeat"></span></a>
				  	{if {$userRank > 0}}<a href="#" title="{$Delete}" onclick="popupVpsDelete({$server->id});"><span class="glyphicon glyphicon-remove"></span></a>{/if}
				  </td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}
</div>

<script type="text/javascript">

$(".vps_list").change(function () {
	if($(this).is(':checked')) {
		$("#vpsAll").show();
	}
	else {
		$("#vps_list").prop('checked', false);
		check_vps_list();
	}
});

$("#vps_list").change(function () {
	if($("#vps_list").is(':checked')) {
		$(".vps_list").prop('checked', true);
		$("#vpsAll").show();
	}
	else {
		$(".vps_list").prop('checked', false);
		$("#vpsAll").hide();
	}
});

function check_vps_list() {
	var nb = 0;
	$(".vps_list").each(function() {
		if($(this).is(':checked')) {
			nb = nb + 1
		}
	});
	
	if(nb == 0) {
		$("#vpsAll").hide();
	}
	else {
		$("#vpsAll").show();
	}
}

$(function() {
	$("input.vps_list:checked").each(function() {
		$(this).prop('checked', false);
	});
});

function popupVpsStartAll(server_id) {
	$("input.vps_list:checked").each(function() {
		popupVpsStart(server_id, $(this).val());
	});
}

function popupVpsStopAll(server_id) {
	$("input.vps_list:checked").each(function() {
		popupVpsStop(server_id, $(this).val());
	});
}

function popupVpsRestartAll(server_id) {
	$("input.vps_list:checked").each(function() {
		popupVpsRestart(server_id, $(this).val());
	});
}

</script>