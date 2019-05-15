<?php

class Response {

    private $status;
    private $data;

    public function __construct($status, $data) {
        $this->status = $status;
        $this->data = $data;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}