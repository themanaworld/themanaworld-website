<?php
/*
phpLadmin by wushin AGPL
*/
ob_implicit_flush();

class phpLadmin {

    public function __construct() {
        require('includes/conf/config.php');
        require('includes/conf/packets.php');
        $this->packets = $packets;
        $this->admin_args = array('encryption_zero' => 0,'account_pass' => $account_pass);
        self::ladmin_connect($ladmin_host, $ladmin_port);
    }

    private function ladmin_connect($ladmin_host, $ladmin_port) {
        $address = gethostbyname($ladmin_host);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 5, 'usec' => 0));
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 5, 'usec' => 0));
        if ($socket === false) {
            error_log("ladmin failed to create socket.\n", 0);
            exit;
        }
        $result = socket_connect($socket, $address, $ladmin_port) or error_log("ladmin failed to connect to login-server.\n", 0);
        if ($result === false ) {
            exit;
        }
        $this->socket = $socket;
        self::admin_login();
    }

    private function ladmin_sendrecv($packet_data) {
        $packet_send = '';
        foreach ($this->packets[$packet_data['packet']]['packet'] as $pack_name => $pack_item) {
            $packet_send .= pack($pack_item, $packet_data[$pack_name]);
        }
        socket_write($this->socket, $packet_send);
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
        $packet_data['packet'] = 0x7918;
        $login_response = self::ladmin_sendrecv(array_merge($packet_data, $this->admin_args));
        if($login_response['error'] != 0) {
            error_log("ladmin failed to login.\n", 0);
            exit;
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
        return self::ladmin_sendrecv(array_merge($packet_data, $args));
    }

    private function check_password($args) {
        // Check Password
        $packet_data['packet'] = 0x793a;
        return self::ladmin_sendrecv(array_merge($packet_data, $args));
    }

    private function who_account($args) {
        // Who Account
        $packet_data['packet'] = 0x7952;
        return self::ladmin_sendrecv(array_merge($packet_data, $args));
    }

    public function close() {
        socket_close($this->socket);
    }

    public function account_info($username) {
        $args = array('account_name' => $username);
        $account_info = self::who_account($args);
        return $account_info;
    }

    public function reset_password($username, $userpass, $newuserpass) {
        $args = array('account_name' => $username, 'account_password' => $userpass);
        $user_check = self::check_password($args);
        if ($user_check['account'] != 0) {
            $args = array('account_name' => $username, 'account_password' => $newuserpass);
            $user_check_res = self::change_password($args);
            return $user_check_res;
        } else {
            return "Wrong User/Pass\n";
        }
    }

    public function create_account($username, $userpass, $sex, $email) {
        $args = array('account_name' => $username);
        $user_name_exists = self::who_account($args);
        if ($user_name_exists['account_id'] > 0) {
            return "Username unavailiable\n";
        } else {
            $args = array('account_name' => $username, 'account_password' => $userpass, 'sex' => $sex, 'email' => $email);
            $create_result = self::add_account($args);
            return $create_result;
        }
    }
}
?>
