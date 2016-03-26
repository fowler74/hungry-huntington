<?php namespace Wappr;

class Controller extends HungryHuntington {
    public $loggedIn;

    protected $actions;
    protected $post;

    public function __construct(Array $post) {
        $this->post = $post;
        $this->loadActions();
    }

    public function run() {
            // if posted action is in the actions property call the action
            if(in_array($this->post['action'], $this->actions)) {
                $this->$this->post['action']();
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

    }

    protected function logout() {

    }

    protected function add() {

    }

    protected function update() {

    }

    protected function delete() {

    }

    protected function loadActions() {
        include(ROOT . DS . 'actions.php');
        $this->actions = $actions;
    }
}
