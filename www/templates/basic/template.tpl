<h2 class="sub-header">
	{'Template list'|gettext} 
	<a href="#" onclick="popupTemplateAdd({$server})" title="{'Add a new template'|gettext}">
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
					<th>{'Template'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$serverTemplate item=template}
				<tr>
					<td>{$template}</td>
					<td>
						<a href="#" title="{'Edit'|gettext}" onclick="popupTemplateEdit({$server}, '{$template}');">
							<button class="btn btn-primary" type="button">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
						</a>
						<a href="#" title="{'Remove'|gettext}" onclick="popupTemplateDelete({$server}, '{$template}');">
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
