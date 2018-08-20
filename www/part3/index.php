<?php
require '../common/DB.php';

$sql = "
    SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
    SET AUTOCOMMIT = 0;
    START TRANSACTION;
    SET time_zone = \"+00:00\";
    
    CREATE TABLE `author` (
      `id` int(11) NOT NULL,
      `name` varchar(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    INSERT INTO `author` (`id`, `name`) VALUES
    (1, 'Иванов'),
    (2, 'Петров'),
    (3, 'Сергеев'),
    (4, 'Михайлов');
    
    CREATE TABLE `author_book` (
      `book_id` int(11) NOT NULL,
      `author_id` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    INSERT INTO `author_book` (`book_id`, `author_id`) VALUES
    (1, 2),
    (2, 1),
    (2, 2),
    (2, 3),
    (2, 4),
    (3, 1),
    (3, 2),
    (3, 3),
    (4, 2),
    (4, 3),
    (4, 4),
    (5, 2),
    (5, 3);
    
    CREATE TABLE `book` (
      `id` int(11) NOT NULL,
      `name` varchar(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    INSERT INTO `book` (`id`, `name`) VALUES
    (1, 'Книга о Петрове'),
    (2, 'Книга о нас всех'),
    (3, 'Книга о нас троих'),
    (4, 'Книга о других троих'),
    (5, 'Книга о Сергееве с Петровым');
    
    ALTER TABLE `author`
      ADD PRIMARY KEY (`id`);
    
    ALTER TABLE `author_book`
      ADD PRIMARY KEY (`book_id`,`author_id`);
    
    ALTER TABLE `book`
      ADD PRIMARY KEY (`id`);
    
    ALTER TABLE `author`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
    
    ALTER TABLE `book`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
    COMMIT;
";
$sth = DB::instance()->prepare($sql);
$sth->execute();


$sql = "
    SELECT `book`.`name`, COUNT(`book_id`) as `count_authors`
    FROM `author_book` 
    LEFT JOIN `book` ON `author_book`.`book_id`=`book`.`id`
    LEFT JOIN `author` ON `author_book`.`author_id`=`author`.`id`
    GROUP BY `book_id`
    HAVING COUNT(`book_id`) >= 3
";
$sth = DB::instance()->prepare($sql);
$sth->execute();
echo 'Для хранения книг и авторов, в этой задаче, требуется реализовать связь между таблицами book и author много ко многим.<br>
    В данном случае это реализуется через таблицу связки author_book.<br>
    SQL запрос получающий список книг, которые написаны 3-мя и более со-авторами:<br>
    <code>SELECT `book`.`name`, COUNT(`book_id`) as `count_authors`<br>
    FROM `author_book` <br>
    LEFT JOIN `book` ON `author_book`.`book_id`=`book`.`id`<br>
    LEFT JOIN `author` ON `author_book`.`author_id`=`author`.`id`<br>
    GROUP BY `book_id`<br>
    HAVING COUNT(`book_id`) >= 3<br>
    </code>
    Результат: <br>
    ';
foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $item) {
    echo 'Книга: ' . $item['name'] . ', Число соваторов: ' . $item['count_authors'] . "<br>";
}