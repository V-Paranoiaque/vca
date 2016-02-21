<h1 class="sub-header">{'Configuration'|gettext}</h1>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">
			{'Strong authentication'|gettext}
			<a href="#" onclick="popupConfToken()"> 
				<span aria-hidden="true" class="glyphicon glyphicon-pencil"></span>
			</a>
		</h3>
	</div>
	<div class="panel-body">
	<table class="table table-invisible">
		<tr>
			<td>{'Domain key'|gettext}</td>
			<td>{$configuration->domain_key}</td>
		</tr>
		<tr>
			<td>{'Key size'|gettext}</td>
			<td>{$configuration->key_size} {'characters'|gettext}</td>
		</tr>
		<tr>
			<td>{'Validity'|gettext}</td>
			<td>{$configuration->key_period} {'seconds'|gettext}</td>
		</tr>
	</table>
	</div>
</div>
</div>
