<h2 class="sub-header">{$serverInfo->name}</h2>

<form method="post" role="form" action="/serverinfo/{$serverInfo->id}">

	<div class="row">
		<div class="col-sm-6">{'Name'|gettext}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="name" value="{$serverInfo->name}"
		                             placeholder="{'Name'|gettext}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-6">{'Address'|gettext}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="address" value="{$serverInfo->address}"
		                             placeholder="{'Address'|gettext}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-6">{'Security key'|gettext}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="key" value="{$serverInfo->key}"
		                             placeholder="{'Security key'|gettext}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-6">{'Description'|gettext}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="description" value="{$serverInfo->description}"
		                             placeholder="{'Description'|gettext}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<button class="btn btn-lg btn-danger btn-block" 
		        type="submit">{'Save'|gettext}</button>
	</div>
	
</form>
