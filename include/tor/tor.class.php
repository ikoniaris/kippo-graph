<?php

class Tor
{
    private $exit_node_array;

    function __construct()
    {
        if ($this->_TorBulkExitListWorking('https://check.torproject.org/cgi-bin/TorBulkExitList.py?ip=8.8.8.8')) {
            $exit_node_array = file('https://check.torproject.org/cgi-bin/TorBulkExitList.py?ip=8.8.8.8');
            file_put_contents(DIR_ROOT . '/include/tor/tor_exit_node_list.txt', $exit_node_array);
        } else {
            $exit_node_array = file(DIR_ROOT . '/include/tor/tor_exit_node_list.txt');
        }

        for ($i = 0; $i < 3; $i++)
            array_shift($exit_node_array);

        $this->exit_node_array = $exit_node_array;
    }

    public function isTorExitNode($ip)
    {
        return in_array($ip, $this->exit_node_array);
    }

    function _TorBulkExitListWorking($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200)
            $status = true;
        else
            $status = false;

        curl_close($ch);
        return $status;
    }
}

?>