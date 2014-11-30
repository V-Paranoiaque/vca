<h2 class="sub-header">{'Template list'|gettext} <a href="#" onclick="popupTemplateAdd({$server})" title="{'Add a new template'|gettext}"><span class="glyphicon glyphicon-plus"></span></a></h2>

{if isset($serverList) }
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'Template'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$serverTemplate item=template}
				<tr>
				  <td>{$template}</td>
				  <td>
				  	<a href="#" title="{'Edit'|gettext}" onclick="popupTemplateEdit({$server}, '{$template}');"><span class="glyphicon glyphicon-pencil"></span></a>
				  	<a href="#" title="{'Remove'|gettext}" onclick="popupTemplateDelete({$server}, '{$template}');"><span class="glyphicon glyphicon-remove"></span></a>
				  </td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}
