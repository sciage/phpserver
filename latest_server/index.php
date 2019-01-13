<?php

$token = rand(8, 10);
$bytes = bin2hex(random_bytes($token));

    echo 'token : ' . $bytes;


?>