<?php namespace Wappr;

class Controller extends HungryHuntington {
    public $loggedIn;

    protected $db;
    protected $actions;
    protected $post;

    public function __construct(Array $post) {
        $this->post = $post;
        $this->loadActions();
        parent::__construct();
        $this->db = parent::getDb();
    }

    public function run() {
        // See if user is already logged in
        $this->checkLoggedIn();
        // Check and see if post['action'] isset
        if(!isset($this->post['action'])) {
            return null;
        }
        // if posted action is in the actions property call the action
        if(in_array($this->post['action'], $this->actions)) {
            $this->{$this->post['action']}();
        }
    }

    /**
    * Attempt to login
    *
    * Check the username and password - if they match set property loggedIn
    * to true.
    *
    */
    protected function login() {
        $query = 'SELECT username, password
        FROM users
        WHERE username = :username';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $_POST['username'], \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(password_verify($this->post['password'], $data['password'])) {
            $this->loggedIn = true;
            $_SESSION['loggedIn'] = true;
            return true;
        } else {
            return false;
        }
    }

    protected function logout() {
        if($this->loggedIn) {

        }
    }

    protected function add() {
        if($this->loggedIn) {

        }
    }

    protected function update() {
        if($this->loggedIn) {

        }
    }

    protected function delete() {
        if($this->loggedIn) {

        }
    }

    protected function adduser() {
        // I could check if logged in before calling these methods, but not all
        // of the methods will require a login.
        if($this->loggedIn) {
            $password = password_hash($this->post['password'], PASSWORD_DEFAULT);
            $query = 'INSERT INTO users
            (username, password)
            VALUES
            (:username, :password)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $this->post['username'], \PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
            return $stmt->execute();
        }
    }

    protected function deluser() {
        if($this->loggedIn) {

        }
    }

    protected function checkLoggedIn() {
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
            $this->loggedIn = true;
            $_SESSION['loggedIn'] = true;
        }
    }

    protected function loadActions() {
        include(ROOT . DS . 'actions.php');
        $this->actions = $actions;
    }
}
