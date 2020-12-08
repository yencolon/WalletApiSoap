<?php 

namespace App\Utils;

class CommonResponse {
    protected $status;
    protected $message;
    protected $data;

    public function __construct($status = 200, $message = '', $data = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}