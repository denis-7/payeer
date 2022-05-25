<?php

namespace Payeer;

class PayeerRequestOptions {

    public $method = '';
    public $post = [];
    public $url = '';
    
    /**
     * Create object
     *
     * @return PayeerRequestOptions
     */
    public static function create() {
        return new self();
    }

    /**
     * Specifying a method of HTTP Request to an API server
     *
     * @param string $value
     * @return PayeerRequestOptions
     */
    public function method($value)
    {
        $this->method = $value;
        return $this;
    }

    /**
     * Specifying a URL of HTTP Request to an API server
     *
     * @param string $value
     * @return PayeerRequestOptions
     */
    public function url($value)
    {
        $this->url = $value;
        return $this;
    }

    /**
     * Specifying a parameters of HTTP Request to an API server
     *
     * @param mixed $value
     * @return PayeerRequestOptions
     */
    public function post($value)
    {
        if (is_array($value))
        {
            $this->post = $value;
        } else {
            array_push($this->post, $value);
        }
        
        return $this;
    }

    /**
     * Return compleeted array of options
     *
     * @return string[]
     */
    public function get()
    {
        return array(
            'method' => $this->method,
            'url' => $this->url,
            'post' => $this->post
        );
    }
}

?>