<h1 class="sub-header">{$userInfo->user_name}</h1>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">{'User informations'|gettext} <a onclick="popupProfile()" href="#"><span class="glyphicon glyphicon-pencil"></span></a></h3>
	</div>
	<div class="panel-body">
	<table>
		<tr>
			<td>{'Name'|gettext}</td>
			<td>{$userInfo->user_name}</td>
		</tr>
		<tr>
			<td>{'Mail'|gettext}</td>
			<td>{$userInfo->user_mail}</td>
		</tr>
	</table>
	</div>
</div>
</div>
