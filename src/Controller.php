<?php namespace Wappr;

class Controller extends HungryHuntington {
    public $loggedIn;
    public $username;
    public $userId;

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
        $query = 'SELECT user_id, username, password
        FROM users
        WHERE username = :username';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $_POST['username'], \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(password_verify($this->post['password'], $data['password'])) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['userId'] = $data['user_id'];
            $this->loggedIn = true;
            $this->username = $data['username'];
            $this->userId   = $data['user_id'];
            return true;
        } else {
            return false;
        }
    }

    protected function logout() {
        if($this->loggedIn) {
            session_destroy();
            header("Location: https://hungryhuntington.com/admin/");
        }
    }

    protected function add() {
        if($this->loggedIn) {
            $query = 'INSERT INTO deals
            (company_id, headline, description, type_id, added_by)
            VALUES
            (:company_id, :headline, :description, :type_id, :added_by)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_id', $this->post['company_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':headline', $this->post['headline'], \PDO::PARAM_STR);
            $stmt->bindParam(':description', $this->post['description'], \PDO::PARAM_STR);
            $stmt->bindParam(':type_id', $this->post['type'], \PDO::PARAM_INT);
            $stmt->bindParam(':added_by', $this->userId, \PDO::PARAM_INT);
            return $stmt->execute();
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
            $this->username = $_SESSION['username'];
            $this->userId   = $_SESSION['userId'];
            $_SESSION['loggedIn'] = true;
        }
    }

    protected function loadActions() {
        include(ROOT . DS . 'actions.php');
        $this->actions = $actions;
    }
}
