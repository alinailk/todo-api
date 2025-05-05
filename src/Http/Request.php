<?php

namespace App\Http;

class Request
{
    private $data;

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST);
    }

    public function all()
    {
        return $this->data;
    }

    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }
}