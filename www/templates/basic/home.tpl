<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title center">{'Statistics'|gettext}</h3>
			</div>
			<div class="panel-body">
			<table>
				<tbody>
				{if {$userRank} > 0}
					<tr>
						<td>{'Physical servers'|gettext}</td>
						<td>{$vcastats->nbServer}</td>
					</tr>
					<tr>
						<td>{'Virtual servers'|gettext}</td>
						<td>{$vcastats->nbVps}</td>
					</tr>
					<tr>
						<td>{'Open requests'|gettext}</td>
						<td>{$vcastats->request}</td>
					</tr>
				{else}
					<tr>
						<td>{'Virtual servers'|gettext}</td>
						<td>{$vcastats->nbVps}</td>
					</tr>
					<tr>
						<td>{'Open requests'|gettext}</td>
						<td>{$vcastats->request}</td>
					</tr>
				{/if}				
			</tbody></table>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title center">{'Virtual servers'|gettext}</h3>
			</div>
			<div class="panel-body">
			<table>
				<tbody><tr>
					<td>{'Running virtual servers'|gettext}</td>
					<td>{$vcastats->nbVpsRun}</td>
				</tr>
				<tr>
					<td>{'Stopped virtual servers'|gettext}</td>
					<td>{$vcastats->nbVpsStop}</td>
				</tr>
				{if {$userRank} > 0}
					<tr><td>&nbsp;</td><td></td></tr>
				{/if}
			</tbody></table>
			</div>
		</div>
	</div>
</div>
