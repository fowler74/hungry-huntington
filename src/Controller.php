<?php namespace Wappr;

use Mailgun\Mailgun;

class Controller extends HungryHuntington {
    public $loggedIn;
    public $username;
    public $userId;

    protected $db;
    protected $d;
    protected $actions;
    protected $post;

    public function __construct(Array $post) {
        $this->post = $post;
        $this->loadActions();
        parent::__construct();
        $this->db = parent::getDb();
        $this->d  = parent::getD();
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
        WHERE username = :username
        LIMIT 1';
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
            $url_title = $this->sluggit($this->post['headline']);
            $query = 'INSERT INTO deals
            (company_id, headline, url_title, description, type_id, added_by)
            VALUES
            (:company_id, :headline, :url_title, :description, :type_id, :added_by)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_id', $this->post['company_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':headline', $this->post['headline'], \PDO::PARAM_STR);
            $stmt->bindParam(':url_title', $url_title, \PDO::PARAM_STR);
            $stmt->bindParam(':description', $this->post['description'], \PDO::PARAM_STR);
            $stmt->bindParam(':type_id', $this->post['type'], \PDO::PARAM_INT);
            $stmt->bindParam(':added_by', $this->userId, \PDO::PARAM_INT);
            $result = $stmt->execute();
            $this->addDaysOfWeek($this->db->lastInsertId());
            return $result;
        }
    }

    protected function addDaysOfWeek($dealId) {
        if($this->loggedIn) {
            $dows = '';
            for($i=0;$i<count($this->post['dow']);$i++) {
                $dows .= '(' . $dealId . ', ' . $this->post['dow'][$i] . '), ';
            }
            $dows = rtrim($dows, ', ');
            $query = 'INSERT INTO dow_deal
            (deals_id_fk, dow_id_fk)
            VALUES ' . $dows;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }
    }

    protected function update() {
        if($this->loggedIn) {

        }
    }

    protected function delete() {
        if($this->loggedIn) {
            $query = 'UPDATE deals
            SET deleted = 1,
            deleted_by = :deleted_by
            WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $this->post['deal_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':deleted_by', $this->userId, \PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    protected function adduser() {
        // I could check if logged in before calling these methods, but not all
        // of the methods will require a login.
        if($this->loggedIn) {
            if($this->findUser()) {
                return false;
            }
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

    protected function addcompany() {
        if($this->loggedIn) {
            $url_title = $this->sluggit($this->post['name']);
            $this->post['google_map'] = str_replace('http://', 'https://', $this->post['google_map']);
            $query = 'INSERT INTO companies
            (name, url_title, google_map, website, phone, address)
            VALUES
            (:name, :url_title, :google_map, :website, :phone, :address)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $this->post['name'], \PDO::PARAM_STR);
            $stmt->bindParam(':url_title', $url_title, \PDO::PARAM_STR);
            $stmt->bindParam(':google_map', $this->post['google_map'], \PDO::PARAM_STR);
            $stmt->bindParam(':website', $this->post['website'], \PDO::PARAM_STR);
            $stmt->bindParam(':phone', $this->post['phone'], \PDO::PARAM_STR);
            $stmt->bindParam(':address', $this->post['address'], \PDO::PARAM_STR);
            return $stmt->execute();
        }
    }

    protected function deluser() {
        if($this->loggedIn) {

        }
    }

    protected function sendemail() {
        if($this->loggedIn) {
            $mg = new Mailgun($this->d['api_key']);
            $mg->sendMessage($this->d['domain'], array(
                'from'    => 'support@hungryhuntington.com',
                'to'      => $this->post['email'],
                'subject' => $this->post['subject'],
                'text'    => $this->post['body']
            ));
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

    protected function findUser() {
        $query = 'SELECT username FROM users WHERE username = :username LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $this->post['username'], \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Using this function I found on stackoverflow: http://stackoverflow.com/a/9535967
    function sluggit($string, $separator = '-')
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array('&' => 'and', "'" => '');
        $string = mb_strtolower(trim( $string ), 'UTF-8');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
        $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }

    protected function loadActions() {
        include(ROOT . DS . 'actions.php');
        $this->actions = $actions;
    }
}
