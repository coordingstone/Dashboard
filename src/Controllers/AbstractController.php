<?php
namespace Dashboard\Controllers;

abstract class AbstractController
{
    /**
     * @param int $code
     * @param object $data
     * @return object
     */
    protected function generateResponse(int $code, $data)
    {
        http_response_code($code);
        return $data;
    }

    /**
     * @param int $code
     * @param array $data
     * @return array
     */
    protected function generateError(int $code, array $data)
    {
        http_response_code($code);
        return $data;
    }
}