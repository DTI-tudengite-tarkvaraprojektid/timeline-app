<?php
class User {
    private $id;
    private $created;
    private $removed;
    private $email;
    private $password;

    public function __construct($id, $created, $removed, $email, $password) {
        $this->id = $id;
        $this->created = $created;
        $this->removed = $removed;
        $this->email = $email;
        $this->password = $password;
    }
}