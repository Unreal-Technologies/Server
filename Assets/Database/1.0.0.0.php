<?php
$coreDbc = \Php2Core\IO\Data\Db\Database::getInstance('Php2Core');

//Create Instance
$coreDbc -> query('insert into `instance`(`name`) '
    . 'values '
    . '(\''. PHP2CORE -> get(Php2Core::Title).'\')');
$instanceId =  $coreDbc -> execute()['iLastInsertId'];

//Create Routes
$routes = [
    [
        'default' => 'true',
        'method' => 'get',
        'match' => 'home',
        'target' => 'home.php',
        'mode' => 'full',
        'auth' => 'false'
    ],
    [
        'default' => 'false',
        'method' => 'get',
        'match' => 'downloads',
        'target' => 'downloads.php',
        'mode' => 'full',
        'auth' => 'false'
//    ],
//    [
//        'default' => 'false',
//        'method' => 'get',
//        'match' => 'cv',
//        'target' => 'cv.php',
//        'mode' => 'full',
//        'auth' => 'false'
    ],
    [
        'default' => 'false',
        'method' => 'get',
        'match' => 'Palworld/WorldOptions',
        'target' => 'palworld/worldoptions.php',
        'mode' => 'full',
        'auth' => 'true'
    ],
    [
        'default' => 'false',
        'method' => 'post',
        'match' => 'Palworld/WorldOptions',
        'target' => 'palworld/worldoptions.php',
        'mode' => 'raw',
        'auth' => 'true'
    ],
    [
        'default' => 'false',
        'method' => 'get',
        'match' => 'Palworld/Players',
        'target' => 'palworld/xhr/players.php',
        'mode' => 'full',
        'auth' => 'false'
    ],
];

foreach($routes as $route)
{
    $coreDbc -> query('insert into `route`(`instance-id`, `default`, `method`, `match`, `target`, `type`, `mode`, `auth`)
    values
    ('.$instanceId.', \''.$route['default'].'\', \''.$route['method'].'\', \''.$route['match'].'\', \''.$route['target'].'\', \'file\', \''.$route['mode'].'\', \''.$route['auth'].'\')');
    $coreDbc -> execute();
}
