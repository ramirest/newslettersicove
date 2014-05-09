<?php
/**
* This file handles processing / uploading / importing of the newsletter urls and files. It does it 'ajax' style so the whole page doesn't need to reload.
*
* @version     $Id: remote.php,v 1.11 2007/05/16 06:48:47 rodney Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Since we are calling this file differently, we need to include init ourselves and then include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/init.php');
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* The class is called in this file (processing wouldn't work by passing it like other sendstudio pages).
* Doing it this way means easy access to all regular sendstudio functions and restrictions (eg userid's etc).
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class RemoteUpload extends SendStudio_Functions
{

	/**
	* RemoteUpload
	* Loads up the 'newsletters' language language file.
	*
	* @return Void Doesn't return anything.
	*/
	function RemoteUpload()
	{
		$this->LoadLanguageFile('Newsletters');
	}

	/**
	* Process
	* This processes the ajax requests.
	* There are only two types of request - importfile and importurl.
	*
	* If it's importfile, it will display the 'fileupload' iframe again, and also process the file if there was one uploaded. It base 64 encodes the data to pass to javascript, this saves having to worry about newlines, quotes and so on. The javascript decodes it itself, then calls the DoImport function in the includes/templates/javascript.js file.
	*
	* If it's importurl, it simply calls GetPageContents and returns that.
	*
	* @see GetPageContents
	*
	* @return Void Doesn't return anything, simply prints out the results.
	*/
	function Process()
	{
		$GLOBALS['ImportType'] = 'HTML';
		if (isset($_GET['ImportType']) && strtolower($_GET['ImportType']) == 'text') {
			$GLOBALS['ImportType'] = 'Text';
		}

		if (isset($_GET['DisplayFileUpload'])) {
			$this->ParseTemplate('Editor_FileUpload');
			return;
		}

		if (isset($_POST['what'])) {
			$what = $_POST['what'];

			switch (strtolower($what)) {
				case 'importlinks':
					// make sure they are logged in appropriately.
					$session = &GetSession();
					if (!$session->LoggedIn()) {
						return;
					}

					$listid = false;
					$processing_list = $session->Get('LinksForList');
					if ($processing_list) {
						$listid = (int)$processing_list;
					}

					$user = &GetUser();
					$links = $user->GetAvailableLinks($listid);

					$link_list = 'mylinks[-1]=\'' . GetLang('FilterAnyLink') . '\';' . "\n";
					foreach ($links as $linkid => $url) {
						$link_list .= 'mylinks[' . $linkid . ']=\'' . urlencode(str_replace('"', '', $url)) . '\';' . "\n";
					}
					echo $link_list;
				break;

				case 'importnewsletters':
					// make sure they are logged in appropriately.
					$session = &GetSession();
					if (!$session->LoggedIn()) {
						return;
					}

					$listid = false;

					$processing_list = $session->Get('NewsForList');
					if ($processing_list) {
						$listid = (int)$processing_list;
					}

					$user = &GetUser();
					$news = $user->GetAvailableNewsletters($listid);

					$news_list = 'mynews[-1]=\'' . GetLang('FilterAnyNewsletter') . '\';' . "\n";
					foreach ($news as $newsid => $name) {
						$news_list .= 'mynews[' . $newsid . ']=\'' . urlencode(str_replace('"', '', $name)) . '\';' . "\n";
					}
					echo $news_list;
				break;

				case 'importfile':
					if (!empty($_FILES['newsletterfile'])) {
						if (is_uploaded_file($_FILES['newsletterfile']['tmp_name'])) {
							$page = file_get_contents($_FILES['newsletterfile']['tmp_name']);

							header('Content-type: text/html; charset="' . SENDSTUDIO_CHARSET . '"');

							?>
							<script>
								parent.ajaxData = parent.decode64('<?php echo base64_encode($page); ?>');
								parent.DoImport('file', '<?php echo $GLOBALS['ImportType']; ?>');
							</script>
							<?php
						}
					}
					$this->ParseTemplate('Editor_FileUpload');
				break;

				case 'importurl':
					$url = false;
					if (isset($_POST['url'])) {
						$url = $_POST['url'];
					}
					list($page, $statusmsg) = $this->GetPageContents($url);
					if ($page) {
						echo $page;
					}
				break;
			}
		}
	}
}

/**
* We need to manually set up the object and process the request, because the ../remote.php file has to include this file. This saves worrying about paths/urls to this file.
*/
$RemoteUploader = &new RemoteUpload();
$RemoteUploader->Process();

?>