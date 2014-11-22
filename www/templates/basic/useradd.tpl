<h2 class="sub-header">{'Add a new user'|gettext}</h2>

{if {$userAdd} != ''}

	<div class="alert alert-danger alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{'Close'|gettext}</span></button>
	  {$userAdd}
	</div>

{/if}

<form method="post" role="form" action="/useradd">

	<div class="row">
		<div class="col-sm-6">{'Name'|gettext}</div>
		<div class="col-sm-6"><input type="text" class="form-control" 
		                             name="name" 
		                             placeholder="{'Name'|gettext}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-6">{'Mail'|gettext}</div>
		<div class="col-sm-6"><input type="text" class="form-control"
		                             name="mail" 
		                             placeholder="{'Mail'|gettext}" required></div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<button class="btn btn-lg btn-danger btn-block" 
		        type="submit">{'Save'|gettext}</button>
	</div>
	
</form>
