<?php
require 'Test.php';

$test = new Test();

foreach ($test->get() as $item) {
    foreach ($item as $key => $val) {
        echo $key . ': ' . $val . '; ';
    }
    echo '<br>';
}
