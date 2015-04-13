<h2 class="sub-header">{'Antivirus scan'|gettext}</h2>

{if isset($serverScan) &&  $serverScan != ''}
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'Vps'|gettext}</th>
					<th>{'File'|gettext}</th>
					<th>{'Danger'|gettext}</th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$serverScan item=scan}
				<tr>
					<td><a href="/vps/{$scan->vps}">{$scan->vps}</a></td>
					<td>{$scan->msg}</td>
					<td class="left">{$scan->info}</td>
				</tr>
			{/foreach}
			</tbody>
	  </table>
  </div>
{else}
	<br/>
	<br/>
	<div class="alert alert-warning center">
		{'No antivirus scan informations for this server'|gettext}
	</div>
{/if}
