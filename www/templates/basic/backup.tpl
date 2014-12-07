<h2 class="sub-header">{'Backup list'|gettext}</h2>

{if isset($serverBackup) }
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'Date'|gettext}</th>
					<th>{'Vps'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$serverBackup item=backup}
				<tr>
				  <td>{$backup->date|tsdate}</td>
				  <td>
				  {if isset($vpsList->{$backup->vps})}
  				  {$vpsList->{$backup->vps}->name}
				  {/if}
				  </td>
				  <td>
				  	<a href="#" title="{'Remove'|gettext}" onclick="popupServerBackupDelete({$server}, {$backup->vps}, '{$backup->date}');"><span class="glyphicon glyphicon-remove"></span></a>
				  </td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}
