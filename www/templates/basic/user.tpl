{if !isset($userInfo)}
	<h2 class="sub-header">{$Userlist} <a href="/useradd"><span class="glyphicon glyphicon-plus"></span></a></h2>
	
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{$Name}</th>
					<th>{$Mail}</th>
					<th>{$Vps}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$userList item=user}
				<tr>
				  <td><a href="/user/{$user->user_id}">{$user->user_name}</a></td>
				  <td>{$user->user_mail}</td>
				  <td>{$user->nb_vps}</td>
				  <td>
				  	<a href="/user/{$user->user_id}"><span class="glyphicon glyphicon-pencil"></span></a>
				  	<a onclick="popupUserDelete({$user->user_id})"><span class="glyphicon glyphicon-remove"></span></a>
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
		<h2 class="sub-header">{$Userinformations}</h2>
		<form method="post" role="form" action="/user/{$userInfo->user_id}">
			{if {$userUpdate} != ''}
			
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{$Close}</span></button>
				  {$userUpdate}
				</div>
			
			{/if}
			
			<div class="input-group">
				<span class="input-group-addon" id="spanName">{$Name}</span>
				<input type="text" class="form-control" 
		               name="name" value="{$userInfo->user_name}"
		               placeholder="{$Name}" required>
		    </div>
		    <br/>
			<div class="input-group">
				<span class="input-group-addon" id="spanMail">{$Mail}</span>
				<input type="text" class="form-control"
	                   name="mail" value="{$userInfo->user_mail}"
	                   placeholder="{$Mail}" required>
		    </div>
		    <br/>
			<button class="btn btn-lg btn-danger btn-block" 
			        type="submit">{$Save}</button>
		</form>
	</div>
	<div class="col-sm-12 col-md-6">
		<h2 class="sub-header">{$Userpassword}</h2>
		<form method="post" role="form" action="/user/{$userInfo->user_id}">
			<div class="input-group">
				<span class="input-group-addon" id="spanNewPassord">{$Newpassword}</span>
				<input type="password" class="form-control" 
		               name="password" placeholder="{$Newpassword}" required>
			</div>
			<br/>
			<div class="input-group">
				<span class="input-group-addon" id="spanConfirm">{$Confirm}</span>
				<input type="password" class="form-control" 
		               name="confirm" placeholder="{$Confirm}" required>
		    </div>
		    <br/>
			<button class="btn btn-lg btn-danger btn-block" 
			        type="submit">{$Modify}</button>
	    </form>
	</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-6">
			<h2 class="sub-header">{$Vps}</h2>
			{if $userVps != ''}
				<table class="table table-striped">
				{foreach from=$userVps item=server}
					<tr>
					  <td class="left">
						  {if {$server->nproc} == 0}
						  	<span class="glyphicon glyphicon-record offline"></span>
						  {else}
						  	<span class="glyphicon glyphicon-record online"></span>
						  {/if}
					      <a href="/vps/{$server->id}" title="{$Informations}">{$server->name}</a></td>
					</tr>
				{/foreach}
				</table>
			{else}
			  <div class="center">{$Novirtualserver}</div>
			{/if}
		</div>
	</div>
	<script type="text/javascript">
	if($("#spanNewPassord").width() > $("#spanConfirm").width()) {
		$("#spanConfirm").width($("#spanNewPassord").width()+"px");
	}
	else {
		$("#spanNewPassord").width($("#spanConfirm").width()+"px");
	}
	if($("#spanName").width() > $("#spanMail").width()) {
		$("#spanMail").width($("#spanName").width()+"px");
	}
	else {
		$("#spanName").width($("#spanMail").width()+"px");
	}
	</script>
{/if}
