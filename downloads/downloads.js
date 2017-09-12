function _ed_downloads_submit()
{
	if(document.ed_form.ed_form_downloadurl.value == "")
	{
		alert("Please upload your file to generate download link or enter your download link.")
		document.ed_form.ed_form_downloadurl.focus();
		return false;
	}
	else if(document.ed_form.ed_form_title.value == "")
	{
		alert("Please enter title for your download link.")
		document.ed_form.ed_form_title.focus();
		return false;
	}
	else if(document.ed_form.ed_form_expirationdate.value == "")
	{
		alert("Please enter expiration date for this download link.")
		document.ed_form.ed_form_expirationdate.focus();
		return false;
	}
}

function _ed_delete(guid)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_ed_display.action="admin.php?page=ed-downloads&ac=del&guid="+guid;
		document.frm_ed_display.submit();
	}
}

function _ed_redirect()
{
	window.location = "admin.php?page=ed-downloads";
}

function _ed_help()
{
	window.open("http://www.gopiplus.com/work/2016/03/01/email-download-link-wordpress-plugin/");
}