<?php
namespace Dashboard\Controllers;

abstract class AbstractController
{
    /**
     * @param int $code
     * @param array $data
     * @return array
     */
    protected function generateResponse(int $code, array $data)
    {
        http_response_code($code);
        return $data;
    }
}