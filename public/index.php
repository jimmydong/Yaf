<?php
/**
 * @name index.php
 * YAF框架入口文件
 * @author jimmy.dong@gmail.com
 */
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));
$application = new \Yaf\Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
?>
