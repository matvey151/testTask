<?php
require '../common/DB.php';

/**
 * При создании объекта этого класса будет создана таблица test и добавлены случайные записи
 */
final class Test
{
    /** @var array Все варианты поля result */
    const RESULTS = ['normal', 'illegal', 'failed', 'success'];

    public function __construct()
    {
        $this->init();
        $this->fill();
    }

    /**
     * Возвращает все записи из таблицы test у которых поле result равно 'normal' или 'success'
     *
     * @return array
     */
    public function get()
    {
        $sql = "SELECT * FROM `test` 
            WHERE `result` IN ('normal', 'success')";
        $sth = DB::instance()->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Создает таблицу test, перед этим удаляет уже существующую таблицу test
     *
     * @return int
     */
    private function init(): int
    {
        $db = DB::instance();
        $db->prepare("DROP TABLE `test`")->execute();

        $results = array_map(function ($result) { return '"' . $result . '"';}, static::RESULTS);
        $sth = $db->prepare("CREATE TABLE
            `test` (
                `id` INT(11) AUTO_INCREMENT NOT NULL,
                `script_name` VARCHAR (25),
                `start_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                `sort_index` DECIMAL (3,0),
                `result` ENUM(" . implode(', ', $results) . ") NOT NULL,
                PRIMARY KEY(`id`)
            )
        ");
        return $sth->execute();
    }

    /**
     * Заполняет test случайными данными
     */
    private function fill()
    {
        for ($i = 0; $i < 100; $i++) {
            $this->insertRand();
        }
    }

    /**
     * Добавляет в таблицу случайную запись
     *
     * @return bool
     */
    private function insertRand()
    {
        $sql = "INSERT INTO `test` 
            (`script_name`, `sort_index`, `result`) 
            VALUES (:script_name, :sort_index, :result)";
        $sth = DB::instance()->prepare($sql);
        return $sth->execute([
            ":script_name" => substr(md5(rand(0, 1000)), 0, 25),
            ":sort_index" => rand(0, 999),
            ":result" => static::RESULTS[rand(0, count(static::RESULTS) - 1)],
        ]);
    }

}
