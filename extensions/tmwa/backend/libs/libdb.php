<?php
/*
dbLadmin
author: wushin
License: GPL 3
*/
class dbadmin {

    public function __construct() {
        $this->socket = True;
    }

    public function account_exists($username) {
    	global $wgTMWAccountDB;
        $dbr = wfGetDB(DB_SLAVE);
        $res = $dbr->select('tmw_accounts', array('USERNAME'), 'USERNAME = "'.$username.'"', __METHOD__, array());
        foreach ($res as $row) {
            return True;
        }
        $res = $dbr->select($wgTMWAccountDB.'.login', array('USERID'), 'USERID = "'.$username.'"', __METHOD__, array());
        foreach ($res as $row) {
            return True;
        }
        return False;
    }

    public function create_account($username, $userpass, $sex, $email) {
    	global $wgTMWAccountDB;
        $dbw = wfGetDB(DB_MASTER);
        $dbw->insert('tmw_accounts', array('USERNAME' => $username, 'PASSWORD' => $userpass, 'GENDER' => $sex, 'EMAIL' => $email), __METHOD__, 'IGNORE');
        if ( !  $dbw->affectedRows() ) {
            return False;
        }
        $dbw = wfGetDB(DB_MASTER);
        $dbw->insert($wgTMWAccountDB.'.login', array('USERID' => $username, 'USER_PASS' => $userpass, 'SEX' => $sex, 'EMAIL' => $email), __METHOD__, 'IGNORE');
        if ( ! $dbw->affectedRows() ) {
            return False;
        }
        return True;
    }

    public function close() {
        return True;
    }
}
