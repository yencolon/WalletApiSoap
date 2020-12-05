<?php


namespace App\Services;


class TestService
{
    public function Hola()
    {
        $response = array();
        $response['out'] = 'Holla Mundo';
        return $response;
    }
}
