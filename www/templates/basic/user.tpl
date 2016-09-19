{if !isset($userInfo)}
	<h3 class="sub-header">
		{'User list'|gettext} 
		<a onclick="popupUserAdd()" title="{'Add a new user'|gettext}">
			<button class="btn btn-success" type="button">
				<span class="glyphicon glyphicon-plus"></span>
			</button>
		</a>
	</h3>
	
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{'Name'|gettext}</th>
					<th>{'Email'|gettext}</th>
					<th>{'Vps'|gettext}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$userList item=user}
				<tr>
					<td><a href="/user/{$user->user_id}" title="{$user->user_name}">{$user->user_name}</a></td>
					<td>{$user->user_mail}</td>
					<td>{$user->nb_vps}</td>
					<td>
						<a href="/user/{$user->user_id}" title="{'Edit'|gettext}">
							<button type="button" class="btn btn-primary">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
						</a>
						<a onclick="popupUserDelete({$user->user_id})" title="{'Delete'|gettext}">
							<button type="button" class="btn btn-danger">
								<span class="glyphicon glyphicon-remove"></span>
							</button>
						</a>
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<h1 class="sub-header">{$userInfo->user_name}</h1>
	<div class="row">
	<div class="col-sm-12 col-md-6">
		<h3 class="sub-header">{'User information'|gettext}</h3>
		<form method="post" role="form" action="/user/{$userInfo->user_id}">
			{if {$userUpdate} != ''}
			
				<div class="alert alert-danger alert-dismissible center" 
				     role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{'Close'|gettext}</span></button>
					{$userUpdate}
				</div>
			
			{/if}
			
			<div class="input-group">
				<span class="input-group-addon" id="spanName">{'Name'|gettext}</span>
				<input type="text" class="form-control" 
				       name="name" value="{$userInfo->user_name}"
				       placeholder="{'Name'|gettext}" required>
			</div>
			<br/>
			<div class="input-group">
				<span class="input-group-addon" id="spanMail">{'Email'|gettext}</span>
				<input type="text" class="form-control"
				       name="mail" value="{$userInfo->user_mail}"
				       placeholder="{'Email'|gettext}" required>
			</div>
			<br/>
			<div class="input-group">
				<span class="input-group-addon" id="spanLang">{'Language'|gettext}</span>
				<select class="selectpicker form-control" name="language"
				        id="idLang">
					{foreach $languageList as $key => $lang}
						<option value="{$key}">{$lang}</option>
					{/foreach}
				</select>
			</div>
			<br/>
			<div class="input-group">
				<span class="input-group-addon" id="spanRank">{'Rank'|gettext}</span>
				<select class="selectpicker form-control" name="rank"
				        id="idRank">
					<option value="0">User</option>
					<option value="1">Admin</option>
				</select>
			</div>
			<br/>
			<div class="center">
				<button class="btn btn-danger" 
				        type="submit">{'Save'|gettext}</button>
			</div>
		</form>
	</div>
	<div class="col-sm-12 col-md-6">
		<h3 class="sub-header">{'User password'|gettext}</h3>
		<form method="post" role="form" action="/user/{$userInfo->user_id}">
			<div class="input-group">
				<span class="input-group-addon" id="spanNewPassord">{'New password'|gettext}</span>
				<input type="password" class="form-control" 
				       name="password" placeholder="{'New password'|gettext}" required>
			</div>
			<br/>
			<div class="input-group">
				<span class="input-group-addon" id="spanConfirm">{'Confirm'|gettext}</span>
				<input type="password" class="form-control" 
				       name="confirm" placeholder="{'Confirm'|gettext}" required>
			</div>
			<br/>
			<div class="center">
				<button class="btn btn-danger" 
				        type="submit">{'Save'|gettext}</button>
			</div>
		</form>
	</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-6">
			<h3 class="sub-header">{'Vps'|gettext}</h3>
			{if $userVps != ''}
				<table class="table table-striped">
				{foreach from=$userVps item=server}
					<tr>
						<td class="left">
							{if {$server->nproc} == 0}
								<span class="glyphicon glyphicon-record offline" title="{'Offline'|gettext}"></span>
							{else}
								<span class="glyphicon glyphicon-record online" title="{'Online'|gettext}"></span>
							{/if}
							<a href="/vps/{$server->id}" title="{'Information'|gettext}">{$server->name}</a></td>
					</tr>
				{/foreach}
				</table>
			{else}
				<div class="center">{'No virtual server'|gettext}</div>
			{/if}
		</div>
		<div class="col-sm-12 col-md-6">
			<h3 class="sub-header">{'Strong authentication'|gettext}</h3>
			<form method="post" role="form" action="/user/{$userInfo->user_id}">
				<div class="input-group">
					<input type="checkbox" 
					       name="useractivated"
					       {if {$userInfo-> user_strongauth } == 1}checked="checked"{/if}> {'Activate token authentication'|gettext}
				</div>
				<br/>
				<div class="input-group">
					<span class="input-group-addon" id="tokenId">{'User token ID'|gettext}</span>
					<input type="text" class="form-control" 
					       name="usertokenid" value="{$userInfo->user_tokenid}"
					       placeholder="{'User token ID'|gettext}" required>
				</div>
				<br/>
				<div class="input-group">
					<span class="input-group-addon" id="pin">{'User PIN'|gettext}</span>
					<input type="password" class="form-control"
					       name="userpin" value=""
					       placeholder="{'User PIN'|gettext}">
				</div>
				<br/>
				<div class="center">
					<button class="btn btn-danger" 
					        type="submit">{'Save'|gettext}</button>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
	if($("#spanNewPassord").width() > $("#spanConfirm").width()) {
		$("#spanConfirm").width($("#spanNewPassord").width()+"px");
	}
	else {
		$("#spanNewPassord").width($("#spanConfirm").width()+"px");
	}
	
	var userinfo = $("#spanMail").width();
	if($("#spanName").width() > userinfo) {
		userinfo = $("#spanName").width();
	}
	if($("#spanRank").width() > userinfo) {
		userinfo = $("#spanRank").width();
	}
	if($("#spanLang").width() > userinfo) {
		userinfo = $("#spanLang").width();
	}
	$("#spanMail").width(userinfo+"px");
	$("#spanName").width(userinfo+"px");
	$("#spanRank").width(userinfo+"px");
	$("#spanLang").width(userinfo+"px");
	
	if($("#pin").width() > $("#tokenId").width()) {
		$("#tokenId").width($("#pin").width()+"px");
	}
	else {
		$("#pin").width($("#tokenId").width()+"px");
	}
	$("#idLang").val('{$userInfo->user_language}');
	$("#idRank").val({$userInfo->user_rank});
	</script>
{/if}
