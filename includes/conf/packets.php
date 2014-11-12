<?php
// To be dynamically generated eventually from TMWA protocols.py
$packets = array(
    0x7918 => array('packet' => array('packet' => 'v','encryption_zero' => 'v','account_pass' => 'a24'), 'len' => '28'),
    0x7530 => array('packet' => array('packet' => 'v'), 'len' => '2'),
    0x7934 => array('packet' => array('packet' => 'v','account_name' => 'a24','account_password' => 'a24'), 'len' => '50'),
    0x7930 => array('packet' => array('packet' => 'v','account_name' => 'a24','account_password' => 'a24', 'sex' => 'a1','email' => 'a40'), 'len' => '91'),
    0x793a => array('packet' => array('packet' => 'v','account_name' => 'a24','account_password' => 'a24'), 'len' => '50'),
    0x7952 => array('packet' => array('packet' => 'v','account_name' => 'a24'), 'len' => '26'),
    0x7919 => array('packet' => array('packet' => 'v','error' => 'C'), 'len' => 3),
    0x7531 => array('packet' => array('packet' => 'v','version' => 'C*'), 'len' => 10),
    0x7935 => array('packet' => array('packet' => 'v','account_id' => 'V','account_name' => 'a24'), 'len' => 30),
    0x7931 => array('packet' => array('packet' => 'v','account_id' => 'V','account_name' => 'a24'), 'len' => 30), 
    0x793b => array('packet' => array('packet' => 'v','account_id' => 'V','account_name' => 'a24'), 'len' => 30),
    0x7953 => array('packet' => array('packet' => 'v','account_id' => 'V','gm_level' => 'C','account_name' => 'a24','sex' => 'C','login_count' => 'V','state' => 'V','error_message' => 'a20','last_login_string' => 'a24','ip_string' => 'a16','email' => 'a40','connect_until' => 'V','ban_until' => 'V','packet' => 'v'), 'len' => 151)
);
?>
