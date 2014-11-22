<h2 class="sub-header">{$serverInfo->name}</h2>

<form method="post" role="form" action="/serverinfo/{$serverInfo->id}">

	<div class="row">
		<div class="col-sm-6">{$Name}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="name" value="{$serverInfo->name}"
		                             placeholder="{$Name}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-6">{$Address}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="address" value="{$serverInfo->address}"
		                             placeholder="{$Address}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-6">{$Key}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="key" value="{$serverInfo->key}"
		                             placeholder="{$Key}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-6">{$Description}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="description" value="{$serverInfo->description}"
		                             placeholder="{$Description}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<button class="btn btn-lg btn-danger btn-block" 
		        type="submit">{$Save}</button>
	</div>
	
</form>
