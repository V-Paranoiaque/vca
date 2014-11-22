<h2 class="sub-header">{'IP management'|gettext}{if {$userRank} == 1} <a href="#" onclick="popupIpAdd()" title="{'Add a new IP'|gettext}"><span class="glyphicon glyphicon-plus"></span></a> {/if}</h2>

<div class="table-responsive"><br/>
	{if isset($ipList) }
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'IP'|gettext}</th>
					<th>{'Vps'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$ipList item=ip}
				<tr>
					<td>{$ip->ip}</td>
					<td>
						{if {$ip->id} > 0}
						<a href="/vps/{$ip->id}" title="{$ip->name}">{$ip->name}</a>
					</td>
					<td>
						{else}
					</td>
					<td>
						<a onclick="popupIpDelete('{$ip->ip}');" title="{'Remove this IP'|gettext}" href="#"><span class="glyphicon glyphicon-remove"></span></a>
						{/if}
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	{else}
		<div class="center">{'You don\'t have any IP'|gettext}</div>
	{/if}
</div>
