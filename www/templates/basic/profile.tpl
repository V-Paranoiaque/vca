<h1 class="sub-header">{$userInfo->user_name}
	<a onclick="popupProfile()" href="#" title="{'Edit your profile'|gettext}">
		<button class="btn btn-primary" type="button">
			<span class="glyphicon glyphicon-pencil"></span>
		</button>
	</a>
	<a onclick="popupUserPassword()" href="#" title="{'User password'|gettext}">
		<button class="btn btn-warning" type="button">
			<span class="glyphicon glyphicon-lock"></span>
		</button>
	</a>
</h1>

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">
			{'User information'|gettext} 
		</h3>
	</div>
	<div class="panel-body">
	<table class="table table-invisible">
		<tr>
			<td>{'Name'|gettext}</td>
			<td>{$userInfo->user_name}</td>
		</tr>
		<tr>
			<td>{'Email'|gettext}</td>
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

<div class="col-sm-6">
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title center">
			{'Accounts'|gettext} 
		</h3>
	</div>
	<div class="panel-body">
	<table class="table table-invisible">
		<tr>
			<td>
				<span class="glyphicon glyphicon-lock"></span> {'Password'|gettext} 
				{if $bkppassStatus == 0}
					<span aria-hidden="true" class="glyphicon glyphicon-remove text-danger"></span>
				{else}
					<span aria-hidden="true" class="glyphicon glyphicon-ok text-success"></span>
				{/if}
			</td>
			<td>
				<a href="#" onclick="popupUserBkppass()" class="btn btn-primary">
					<span class="glyphicon glyphicon-pencil"></span>
				</a>
			</td>
		</tr>
		{if $dropboxPossible == 1}
			<tr>
				<td>
					<span class="glyphicon glyphicon-hdd"></span> {'Dropbox'|gettext} 
					{if $dropboxStatus == 0}
						<span aria-hidden="true" class="glyphicon glyphicon-remove text-danger"></span>
					{else}
						<span aria-hidden="true" class="glyphicon glyphicon-ok text-success"></span>
					{/if}
				</td>
				<td>
					<a href="#" onclick="popupUserDropbox()" class="btn btn-primary">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
				</td>
			</tr>
		{/if}
	</div>
</div>
</div>
