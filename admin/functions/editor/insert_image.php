<?php
/**
* This file handles the 'insert image' popup window.
*
* @package Editor
*/

/**
* Include the base init package. This sets up the session, and also allows us to print headers, load the language file and so on.
*/
require('../init.php');

/**
* Set up the session.
*/
$session = &GetSession();
$user = &$session->Get('UserDetails');

/**
* Include the base sendstudio functions - this allows us to print the header etc.
*/
require('../sendstudio_functions.php');
$SendStudio_Functions = &new SendStudio_Functions();

/**
* if there's an id (eg templateid, newsletterid etc) - use it. otherwise, we can save it temporarily under the user id and move it when we save it.
*/
$type = (isset($_GET['type'])) ? urldecode($_GET['type']) : 'user';
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 1;

$type = strtolower($type);

$imagedir = TEMP_DIRECTORY . '/' . $type . '/' . $id;

$SendStudio_Functions->PrintHeader(true, false);
$SendStudio_Functions->LoadLanguageFile('Insert_Image');

if (!is_dir($imagedir)) {
	$result = CreateDirectory($imagedir);
	if (!$result) {
		$GLOBALS['Error'] = GetLang('ImageDirectoryPermissions');
		$GLOBALS['Message'] = $SendStudio_Functions->ParseTemplate('ErrorMsg', true, false);
		$SendStudio_Functions->PrintFooter(true);
		exit();
	}
}

if (!function_exists('EasySize')) {
	function EasySize($size=0) {
		if ($size < 1024) return $size . ' b';
		if ($size >= 1024 && $size < (1024*1024)) return number_format(($size/1024), 2) . ' Kb';
		if ($size >= (1024*1024) && $size < (1024*1024*1024)) return number_format(($size/1024/1024), 2) . ' Mb';
		if ($size >= (1024*1024*1024)) return number_format(($size/1024/1024/1024), 2) . ' Gb';
	}
}

$valid_file_types = array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg');
$valid_file_exts  = array('gif', 'jpg', 'jpeg', 'png');

$upload_success_report = '';
$upload_failure_report = '';

if (isset($_REQUEST['MyAction'])) {
	switch($_REQUEST['MyAction']) {
		case 'Delete':
			$filename = $_GET['Image'];
			if (is_file($imagedir . '/' . $filename)) {
				if (!unlink($imagedir . '/' . $filename)) {
					$upload_failure_report .= '<span class="imageerror">' . sprintf(GetLang('UnableToDeleteFile'), $filename) . '\'.</span><br />';
				} else {
					$upload_success_report .= sprintf(GetLang('DeleteFile'), $filename) . '.<br />';
				}
			} else {
				$upload_failure_report .= '<span class="imageerror">' . sprintf(GetLang('NoSuchFile'), $filename) . '</span><br />';
			}
		break;
		case 'UploadImages':
			foreach($_FILES as $file) {
				if ($file['name'] == '') continue;
				if (!in_array($file['type'], $valid_file_types)) {
					$upload_failure_report .= sprintf(GetLang('UploadFailed_InvalidFileType'), $file['name']) . '<br/>';
					continue;
				}
				$orig_file = $file['tmp_name'];
				$new_file  = $imagedir . '/' . $file['name'];
				if (!move_uploaded_file($orig_file, $new_file)) {
					$upload_failure_report .= sprintf(GetLang('UploadFailed_Move'), $file['name'], str_replace(TEMP_DIRECTORY, '', $imagedir)) . '<br/>';
				} else {
					if (!chmod($new_file, 0644)) {
						$upload_success_report .= sprintf(GetLang('UploadFailed_Permissions'), $file['name']) . '<br/>';
					} else {
						$upload_success_report .= sprintf(GetLang('UploadSuccess'), $file['name']) . '<br/>';
					}
				}
			}
		break;
	}
}

?>
<script language="javascript">
	function insertImage(imgSrc) {
		if (window.opener.browser.IE) {
			var sel = window.opener.foo.document.selection;
			if (sel!=null) {
				var rng = sel.createRange();
			if (rng!=null) {
					HTMLTextField = '<img src="'+imgSrc+'">';
					rng.pasteHTML(HTMLTextField)
				} // End if
			} // End If

			window.opener.foo.focus();
			self.close();
		} else {
			window.opener.getIFrameDocument('foo').execCommand('InsertImage', false, imgSrc);
			self.close();
		}
	} // End function
</script>
<form name="imagemanager" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $type; ?>&id=<?php echo $id; ?>" enctype="multipart/form-data">
<input type="hidden" name="MyAction" value="">
	<table border="0" cellspacing="0" cellpadding="0" width="100%" style="margin: 15px;">
		<tr>
			<td align="right">
				<a href="#" onclick="javascript: window.close(); return false;"><?php echo GetLang('PopupCloseWindow'); ?></a>
			</td>
		</tr>
		<tr>
			<td class="heading1">
				<?php echo GetLang('ImageManager'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo GetLang('UploadInsertManageImages'); ?>
			</td>
		</tr>
		<?php
			if ($upload_success_report != '') {
					?>
					<tr><td><br>
					<table border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td valign="top" width="20px"><img src="<?php echo SENDSTUDIO_IMAGE_URL ?>/success.gif" width="18" height="18" hspace="10" vspace="5" align="middle"></td>
							<td><?php echo $upload_success_report; ?></td>
							</tr>
					</table>
					</td></tr>
					<?php
				}

			if ($upload_failure_report != '') {
					?>
					<tr><td><br>
					<table border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td valign="top" width="20px"><img src="<?php echo SENDSTUDIO_IMAGE_URL ?>/error.gif" width="18" height="18" hspace="10" vspace="5" align="middle"></td>
							<td><?php echo $upload_failure_report; ?></td>
							</tr>
					</table>
					</td></tr>
					<?php
				}
		?>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td width="100%">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="Panel">
					<tr class="heading3">
						<td colspan="3">
							<?php echo GetLang('InternalImage'); ?>
						</td>
					</tr>

					<?php
					if (!empty($all_files)) {
					?>
					<tr>
						<td width="50%">
							<b><?php echo GetLang('Name'); ?></b>
						</td>
						<td width="25%">
							<b><?php echo GetLang('FileSize'); ?></b>
						</td>
						<td width="25%">
							<b><?php echo GetLang('Action'); ?></b>
						</td>
					</tr>
					<?php
					}
					?>

					<?php
						$all_files = array();
						if ($handle = opendir($imagedir)) {
							while(($f = readdir($handle)) !== false) {
								if ($f == '.' || $f == '..') continue;
								$ext = substr(strrchr($f, '.'), 1);
								if (!in_array($ext, $valid_file_exts)) continue;
								$all_files[] = $f;
							}

							if (empty($all_files)) {
								?>
									<tr>
										<td colspan="3">
											&nbsp;<?php echo GetLang('NoImages'); ?>
										</td>
									</tr>
								<?php
							} else {
								foreach($all_files as $filename) {
									$filedetails = stat($imagedir . '/' . $filename);
									$imagehref = SENDSTUDIO_APPLICATION_URL . '/temp/' . $type . '/' . $id . '/' . $filename;
								?>
									<tr>
										<td>
											<?php echo $filename; ?>
										</td>
										<td>
											<?php echo EasySize($filedetails[7]); ?>
										</td>
										<td>
											<a href="<?php echo $imagehref; ?>" target="_blank"><?php echo GetLang('View'); ?></a>
											&nbsp;&nbsp;&nbsp;
											<a href="#" onclick="javascript:insertImage('<?php echo $imagehref; ?>'); return false;"><?php echo GetLang('Insert'); ?></a>
											&nbsp;&nbsp;&nbsp;
											<a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $id; ?>&type=<?php echo $type; ?>&MyAction=Delete&Image=<?php echo $filename; ?>"><?php echo GetLang('Delete'); ?></a>
										</td>
									</tr>
								<?php
								}
							}
							closedir($handle);

						} else {
							?>
								<tr>
									<td colspan="3">
										<?php echo GetLang('ImageDirectoryPermissions'); ?>
									</td>
								</tr>
							<?php
						}
					?>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr>
				<td>

			<table border=0 width=100% cellpadding=3 cellspacing=0 class="panel">
			<tr class="heading3">
				<td>
					<?php echo GetLang('ExternalImage'); ?>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;<?php echo GetLang('ExternalImage'); ?>:&nbsp;
						<input type="text" name="ExternalImage" class="field250" value="http://">&nbsp;&nbsp;<input type="button" value="<?php echo GetLang('Insert'); ?>" class="formButton" onclick="javascript:if (document.imagemanager.ExternalImage.value == '' || document.imagemanager.ExternalImage.value == 'http://') { alert('<?php echo GetLang('EnterExternalImageURL'); ?>'); document.imagemanager.ExternalImage.focus(); return false; } else { insertImage(document.imagemanager.ExternalImage.value); }">
				</td>
			</tr>
			</table>

			</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr><td>

			<table border=0 width=100% cellpadding=2 cellspacing=0 class=panel>
			<tr class="heading3">
				<td>
					<?php echo GetLang('UploadImages'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="1" width="100%" class="">
						<tr>
							<td width="50%">
								<input type="file" class="field" name="File1">
							</td>
							<td width="50%">
								<input type="file" class="field" name="File2">
							</td>
						</tr>
						<tr>
							<td width="50%">
								<input type="file" class="field" name="File3">
							</td>
							<td width="50%">
								<input type="file" class="field" name="File4">
							</td>
						</tr>
						<tr>
							<td width="50%">
								<input type="file" class="field" name="File5">
							</td>
							<td width="50%">
								<input type="file" class="field" name="File6">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td>
								<input type="button" class="formButton" value="<?php echo GetLang('Upload'); ?>" onclick="javascript:form.MyAction.value='UploadImages';form.submit();">
							</td>
							<td>
								&nbsp;
							</td>
						</tr>
					</table>
				</td>
			</tr>
			</td>
			</tr>
			</table>
		</td>
		</tr>
	</table>
</form>
<?php

$SendStudio_Functions->PrintFooter(true);

?>
