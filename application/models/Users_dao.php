<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_dao extends MY_Model {
    var $_table = "users";
    var $_key = "usersId";

    var $usersId;
    var $nickName;
    var $email;
    var $trx;
    var $ipAddress;
    var $regDate;
    var $winner;

    public function __construct() {
        parent::__construct();
    }
}
?>