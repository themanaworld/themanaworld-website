<?php
/*
dbLadmin
author: wushin
License: GPL 3
*/

class dbLadmin {

    public function __construct() {
        $this->socket = True;
    }

    public function account_exists($username) {
        $dbr = wfGetDB(DB_SLAVE);
        $res = $dbr->select('tmw_accounts', array('USERNAME'), 'USERNAME = "'.$username.'"', __METHOD__, array());
        foreach ($res as $row) {
            return True;
        }
        return False;
    }

    public function create_account($username, $userpass, $sex, $email) {
        $dbw = wfGetDB(DB_MASTER);
        $dbw->insert('tmw_accounts', array('USERNAME' => $username, 'PASSWORD' => $userpass, 'GENDER' => $sex, 'EMAIL' => $email), __METHOD__, 'IGNORE');
        if ( $dbw->affectedRows() ) {
            return True;
        } else {
            return False;
        }
    }

    public function close() {
        return True;
    }
}
