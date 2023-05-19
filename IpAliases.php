<?php

    require('./config.php');
    require('./class/KippoIP.class.php');

    $kippoIP = new KippoIp();
    $kippoIP->getAllIps();

?>