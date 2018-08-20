<?php

/**
 * Класс позволяет создавать объект PDO однажды
 */
class DB
{
    /** @var PDO */
    private static $instance;

    /**
     * Создавать объект этого класса не нужно, он работает только со статическими методами
     */
    private function __construct()
    {
    }

    /**
     * Возвращает объект PDO
     *
     * @return PDO
     */
    public static function instance(): PDO
    {
        if (null === static::$instance) {
            static::$instance = new PDO('mysql:host=test_mysql;dbname=test', 'root', 'root');
        }

        return static::$instance;
    }

}
