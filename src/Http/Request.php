<?php

namespace App\Http;

class Request // Bu sınıf gelen HTTP istek verilerini işlemek için kullanılır.
{
    private $data; // GET ve POST dizilerinin birleştirilmiş halini tutacaktır

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST); // GET ve POST birleştirilir.
    }

    //  Bu metod sınıfın içindeki tüm veriyi döndürür. 
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