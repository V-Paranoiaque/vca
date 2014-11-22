<div class="container">
	<form method="post" class="form-signin" role="form">
		<h2 class="form-signin-heading">{$Page_Title}</h2>
		
		<div class="alert alert-warning alert-dismissible" role="alert">
		  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{$Close}</span></button>
		  <div id="error"></div>
		</div>
		
		<input type="text" class="form-control" 
		       placeholder="{$Login}" name="login" required autofocus>
		<input type="password" class="form-control" 
		       placeholder="{'Password'|gettext}" name="password" required>
		<button class="btn btn-lg btn-danger btn-block" 
		        type="submit">{$Sign_in}</button>
	</form>
</div>
