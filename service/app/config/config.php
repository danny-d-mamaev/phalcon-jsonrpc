<?php declare(strict_types=1);
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

$config = new \Phalcon\Config([
    'database' => [
        'adapter'    => getenv('DB_ADAPTER') ?: 'Postgresql', // 'Mysql',
        'host'       => getenv('DB_HOST') ?: 'localhost',
        'username'   => getenv('DB_USER') ?: 'pav',
        'password'   => getenv('DB_PASSWORD') ?: 'pav',
        'dbname'     => getenv('DB_NAME') ?:'pav',
        'charset'    => 'utf8',
    ],

    'application' => [
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'baseUri'        => '/',
        'controllersDir' => APP_PATH . '/controllers/',
    ]
]);

$localConfig = APP_PATH . '/config/config.local.php';
if (file_exists($localConfig)) {
    $config->merge(require $localConfig);
}

return $config;
