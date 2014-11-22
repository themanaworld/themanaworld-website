<?php
/*
phpLadmin
author: wushin
License: GPL 3
*/
ob_implicit_flush();

class phpLadmin {

    public function __construct() {
        global $wgLadminHost,$wgLadminPort,$wgLadminPass;
        $tmwa = new TMWA();
        $this->packets = $tmwa->packets;

        $address = gethostbyname($wgLadminHost);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 5, 'usec' => 0));
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 5, 'usec' => 0));
        if ($socket === False) {
            $this->socket = False;
            return False;
        }
        if(socket_connect($socket, $address, $wgLadminPort)) {
                $this->socket = $socket;
                if (self::admin_login()) {
                    return True;
                }
        }
        $this->socket = False;
        return False;
    }

    private function ladmin_sendrecv($packet_data) {
        $packet_send = '';
        foreach ($this->packets[$packet_data['packet']]['packet'] as $pack_name => $pack_item) {
            $packet_send .= pack($pack_item, $packet_data[$pack_name]);
        }
        socket_write($this->socket, $packet_send);
        $packet_send = '';
        socket_recv($this->socket, $packet_pack, 2, MSG_PEEK);
        $packet_recv = unpack('vpacket/', $packet_pack);
        socket_recv($this->socket, $packet_pack, $this->packets[$packet_recv['packet']]['len'], MSG_DONTWAIT);
        $unpack_payload = '';
        foreach ($this->packets[$packet_recv['packet']]['packet'] as $pack_name => $pack_item) {
            $unpack_payload .= $pack_item.$pack_name.'/';
        }
        $payload_recv = unpack($unpack_payload, $packet_pack);
        return $payload_recv;
    }

    private function admin_login() {
        // Admin Login
        $login_response = self::ladmin_sendrecv(array('packet' => 0x7918, 'encryption_zero' => 0,'account_pass' => get_cfg_var("tmwa.cfg.LADMIN_PASS")));
        if($login_response['error'] != 0) {
            return False;
        }
        return self::login_version();
    }

    private function login_version() {
        // Login version
        $packet_data['packet'] = 0x7530;
        return self::ladmin_sendrecv($packet_data);
    }

    private function add_account($args) {
        // Add Account
        $packet_data['packet'] = 0x7930;
        $user_check = self::ladmin_sendrecv(array_merge($packet_data, $args));
        return ($user_check['account_id'] > 0 ? True : False);
    }

    private function check_password($args) {
        // Check Password
        $packet_data['packet'] = 0x793a;
        $user_check = self::ladmin_sendrecv(array_merge($packet_data, $args));
        return ($user_check['account_id'] > 0 ? True : False);
    }

    private function who_account($args) {
        // Who Account
        $packet_data['packet'] = 0x7952;
        return self::ladmin_sendrecv(array_merge($packet_data, $args));
    }

    private function change_password($args) {
        // Change Password
        $packet_data['packet'] = 0x7934;
        $pass_result = self::ladmin_sendrecv(array_merge($packet_data, $args));
        return ($pass_result['account_id'] > 0 ? True : False);
    }

    public function close() {
        socket_close($this->socket);
    }

    public function account_info($username) {
        $args = array('account_name' => $username);
        $account_info = self::who_account($args);
        return $account_info;
    }

    public function account_exists($username) {
        $args = array('account_name' => $username);
        $user_name_exists = self::who_account($args);
        return ($user_name_exists['account_id'] > 0 ? True : False);
    }

    public function check_auth($username, $userpass) {
        $args = array('account_name' => $username, 'account_password' => $userpass);
        return self::check_password($args);
    }

    public function check_ban($username) {
        $account_info = self::account_info($username);
        if ($account_info['state'] > 0) {
            return True;
        } elseif ($account_info['ban_until'] > 0) {
            return True;
        }
        return False;
    }

    public function change_pass($username, $userpass) {
        $args = array('account_name' => $username, 'account_password' => $userpass);
        return self::change_password($args);
    }

    public function reset_password($username, $userpass, $newuserpass) {
        $args = array('account_name' => $username, 'account_password' => $userpass);
        $user_check = self::check_password($args);
        return ($user_check ? self::change_pass($username, $newuserpass) : False);
    }

    public function create_account($username, $userpass, $sex, $email) {
        $user_name_exists = self::account_exists($username);
        $args = array('account_name' => $username, 'account_password' => $userpass, 'sex' => $sex, 'email' => $email);
        return (!$user_name_exists ? self::add_account($args) : False);
    }
}
