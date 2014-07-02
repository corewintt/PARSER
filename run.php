<?php
include 'setup.php';
$sapi = php_sapi_name();
if($sapi !== 'cli')
{
    die('Разрешен только запуск из командной строки ' . PHP_EOL);
}
new cli($argv);
