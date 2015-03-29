<h2 class="sub-header">{$requestInfo->title}{if $requestInfo->resolved == 0} <a href="#" onclick="popupRequestClose({$requestInfo->id})" title="{'Close this request'|gettext}"><span aria-hidden="true" class="glyphicon glyphicon-ok"></span></a>{/if}</h2>
<br/>
{if $requestInfo->resolved == 0}
<div class="row">
	<div class="col-sm-4 col-md-3 col-lg-2">
	</div>
	<div class="col-sm-offset-4 col-md-offset-3 col-lg-offset-2 col-sm-8 col-md-9 col-lg-10 center">
		<textarea class="form-control" rows="5" id="message"></textarea>
		<br/>
		<button class="btn btn-danger" type="button" onclick="formRequestAnswer({$requestInfo->id})">{'Send'|gettext}</button>
	</div>
</div>
{/if}
{foreach from=$requestInfo->messages item=message}
	<div class="row">
		<div class="col-sm-4 col-md-3 col-lg-2">
			<b>{$message->user_name}</b><br/>
			{$message->date|tsdate}
		</div>
		<div class="col-sm-8 col-md-9 col-lg-10">
			{$message->message|nl2br}
			<br/><br/>
		</div>
	</div>
{/foreach}
