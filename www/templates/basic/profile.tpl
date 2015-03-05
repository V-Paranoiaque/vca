<h1 class="sub-header">{$userInfo->user_name}</h1>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">{'User information'|gettext} 
		<a onclick="popupProfile()" href="#" title="{'Edit your profile'|gettext}"><span class="glyphicon glyphicon-pencil"></span></a>
		<a onclick="popupUserPassword()" href="#" title="{'User password'|gettext}"><span class="glyphicon glyphicon-lock"></span></a></h3>
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
		<tr>
			<td>{'Language'|gettext}</td>
			<td>{$languageList->{$language}}</td>
		</tr>
	</table>
	</div>
</div>
</div>
