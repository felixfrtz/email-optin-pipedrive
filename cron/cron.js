function _ed_submit()
{
	if(document.es_form.es_cron_mailcount.value == "")
	{
		alert("Please select enter number of mails you want to send per hour/trigger.")
		document.es_form.es_cron_mailcount.focus();
		return false;
	}
	else if(isNaN(document.es_form.es_cron_mailcount.value))
	{
		alert("Please enter the mail count, only number.")
		document.es_form.es_cron_mailcount.focus();
		return false;
	}
}

function _ed_redirect()
{
	window.location = "admin.php?page=ed-cron";
}

function _ed_help()
{
	window.open("http://www.gopiplus.com/work/2016/03/01/email-download-link-wordpress-plugin/");
}