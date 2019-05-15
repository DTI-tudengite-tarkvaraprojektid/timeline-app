<?php

class Response {

    /**
     * @var int HTTP status code
     */
    private $status;

    /**
     * @var array Response data
     */
    private $data;

    public function __construct($status, $data) {
        $this->status = $status;
        $this->data = $data;
    }

    /**
     * Gives the HTTP status code
     *
     * @return int HTTP status code
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the HTTP status code
     *
     * @param int $status HTTP Status code
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gives the Response data
     *
     * @return array Response data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the Response data
     *
     * @param array $data Response data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
