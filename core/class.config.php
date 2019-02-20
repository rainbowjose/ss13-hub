<?php

if(!defined("INDEX")) die("ACCESS DENIED");

/**
 * Возвращает данные о конфигурации БД.
 *
 * Пример:
 * $config->SQL_CONFIG();
 *
 * @return array возвращает массив
 */

function SQL () {
    return array (
        'mysql_host'       => 'localhost',
        'mysql_user'       => 'root',
        'mysql_password'   => 'anime',
        'mysql_db'         => 'yellow',
    );
}

/**
 * Возвращает данные о конфигурации хаба.
 *
 * Пример:
 * $config->HUB_CONFIG();
 *
 * @return array возвращает массив
 */
function HUB () {
    return array (
        'hub_name'        => 'Frosty HUB',
        'hub_theme'       => 'orange',
        'hub_type'        => 'tg',
    );
}

?>
