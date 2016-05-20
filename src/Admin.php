<?php namespace Wappr;

class Admin extends Controller {
    protected $db;

    protected function adduser() {
        // I could check if logged in before calling these methods, but not all
        // of the methods will require a login.
        if(parent::checkAdmin) {
            if(parent::findUser()) {
                return false;
            }
            $password = password_hash(parent::post['password'], PASSWORD_DEFAULT);
            $query = 'INSERT INTO users
            (username, password)
            VALUES
            (:username, :password)';
            $stmt = parent::db->prepare($query);
            $stmt->bindParam(':username', parent::post['username'], \PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
            return $stmt->execute();
        }
    }
}
