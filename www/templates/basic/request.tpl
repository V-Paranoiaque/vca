<h2 class="sub-header">{'Requests'|gettext} <a href="#" onclick="popupRequestAdd()" title="{'Create a new request'|gettext}"><span class="glyphicon glyphicon-plus"></span></a></h2>

{if $requestList != ''}
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'Subject'|gettext}</th>
					{if {$userRank} > 0}
						<th>{'Author'|gettext}</th>
					{/if}
					<th>{'Status'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$requestList item=request}
				<tr>
				  <td><a href="/request/{$request->topic}" alt="{$request->title}">{$request->title}</a></td>
				  {if {$userRank} > 0}
				  	<td><a href="/user/{$request->user_id}">{$request->user_name}</a></td>
				  {/if}
				  <td>
				  {if $request->resolved == 0}
				  	{'Open'|gettext}
				  {else}
				  	{'Closed'|gettext}
				  {/if}
				  </td>
				  <td><a href="/request/{$request->topic}" title="{$request->title}"><span class="glyphicon glyphicon-align-justify"></span></a></td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<div class="center">
		<h3>{'You don\'t have request'|gettext}</h3>
	</div>
{/if}
