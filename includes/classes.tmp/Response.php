<?php

class Response {

    private $status;
    private $data;

    public function __construct(int $status, array $data) {
        $this->status = $status;
        $this->data = $data;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getData() : array
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }
}