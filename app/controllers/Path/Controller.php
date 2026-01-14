<?php

class Controller
{
  protected $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    protected function view(string $path, array $data = [])
    {
        extract($data);
        require "../app/views/{$path}.php";
    }
}
