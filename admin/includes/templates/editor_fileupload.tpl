<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=%%GLOBAL_CHARSET%%">
<link rel="stylesheet" href="%%GLOBAL_APPLICATION_URL%%/admin/includes/styles/stylesheet.css" type="text/css">

<script language="javascript" src="%%GLOBAL_APPLICATION_URL%%/admin/includes/templates/javascript.js"></script>
<script language="javascript">
	function UploadFile() {
		if (document.getElementById('newsletterfile').value == '') {
			alert('%%LNG_Editor_ChooseFileToUpload%%');
			return false;
		}
		Butt = document.getElementById('uploadButton');
		Butt.value = '%%LNG_Editor_Import_File_Wait%%';
		Butt.style.width = "150px";
		Butt.disabled = true;
		return true;
	}
</script>
<body style="margin: 0px; padding: 0px; background-color: #F7F7F7">
<form STYLE="margin: 0px; padding: 0px;" method="post" action="%%GLOBAL_APPLICATION_URL%%/admin/functions/remote.php?ImportType=%%GLOBAL_ImportType%%" enctype="multipart/form-data" onsubmit="return UploadFile();">
&nbsp;&nbsp;&nbsp;
<input type="hidden" name="what" value="importfile">
<input type="file" name="newsletterfile" id="newsletterfile" value="" class="field">
<input class="formbutton" type="submit" id="uploadButton" name="upload" value="%%LNG_UploadNewsletter%%" class="field" style="width:60px">
</form>
</body>
</html>
