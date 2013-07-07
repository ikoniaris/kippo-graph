<?php
#Copyright (C) 2010 by the PHP Documentation Group
#Attribution 3.0 Unported (CC BY 3.0)
#More information: http://www.php.net/manual/en/function.gethostbyaddr.php#99826

function get_host($ip){
        $ptr= implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
        $host = @dns_get_record($ptr,DNS_PTR);
        if ($host == null) return $ip;
        else return $host[0]['target'];
}
?>