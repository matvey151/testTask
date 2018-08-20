<?php
require 'Scanner.php';

$dir = './datafiles';

$scan = new Scanner($dir);

$scan->setTemplate('/.*/');
echo '<h1>Вся директория</h1>';
foreach ($scan->find() as $file) {
    echo $file . "<br>";
}

$scan->setTemplate();
echo '<h1>Отфильтрованные файлы</h1>';
foreach ($scan->find() as $file) {
    echo $file . "<br>";
}
