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

    }

    protected function add() {

    }

    protected function update() {

    }

    protected function delete() {

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
