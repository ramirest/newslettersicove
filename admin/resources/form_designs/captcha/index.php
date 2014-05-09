<?php
$me = dirname(__FILE__);
require($me . '/../../../functions/api/captcha.php');

$captcha = &new Captcha_API();
$captcha->OutputImage();
?>
