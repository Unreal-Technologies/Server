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
        'auth' => 'false'
    ],
//    [
//        'default' => 'false',
//        'method' => 'get',
//        'match' => 'mapviewer',
//        'target' => 'mapviewer.php',
//        'auth' => 'false'
//    ],
    [
        'default' => 'false',
        'method' => 'get',
        'match' => 'downloads',
        'target' => 'downloads.php',
        'auth' => 'false'
//    ],
//    [
//        'default' => 'false',
//        'method' => 'get',
//        'match' => 'cv',
//        'target' => 'cv.php',
//        'auth' => 'false'
    ],
    [
        'default' => 'false',
        'method' => 'get',
        'match' => 'Palworld/WorldOptions',
        'target' => 'palworld/worldoptions.php',
        'auth' => 'true'
    ],
    [
        'default' => 'false',
        'method' => 'post',
        'match' => 'Palworld/WorldOptions',
        'target' => 'palworld/worldoptions.php',
        'auth' => 'true'
    ],
    [
        'default' => 'false',
        'method' => 'get',
        'match' => 'Palworld/Players',
        'target' => 'palworld/xhr/players.php',
        'auth' => 'false'
    ],
];

foreach($routes as $route)
{
    $coreDbc -> query('insert into `route`(`instance-id`, `default`, `method`, `match`, `target`, `type`, `auth`)
    values
    ('.$instanceId.', \''.$route['default'].'\', \''.$route['method'].'\', \''.$route['match'].'\', \''.$route['target'].'\', \'file\', \''.$route['auth'].'\')');
    $coreDbc -> execute();
}
