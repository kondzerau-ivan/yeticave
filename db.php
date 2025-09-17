<?php
$con = mysqli_connect('db', 'master', 'master123', 'yeticave');
if (!$con) {
    throw new Exception('Ошибка подключения: ' . mysqli_connect_error());
}
mysqli_set_charset($con, 'utf8');
