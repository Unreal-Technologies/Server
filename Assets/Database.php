<?php
$coreDbc = \Php2Core\Db\Database::getInstance('Php2Core');

//Create Instance
$coreDbc -> query('insert into `instance`(`name`) '
    . 'values '
    . '(\''.TITLE.'\')');
$instanceId =  $coreDbc -> execute()['iLastInsertId'];

//Create Routes
$routes = [
    [
        'default' => 'true',
        'method' => 'get',
        'match' => 'home',
        'target' => 'home.php'
    ],
//    [
//        'default' => 'false',
//        'method' => 'get',
//        'match' => 'mapviewer',
//        'target' => 'mapviewer.php'
//    ],
    [
        'default' => 'false',
        'method' => 'get',
        'match' => 'downloads',
        'target' => 'downloads.php'
//    ],
//    [
//        'default' => 'false',
//        'method' => 'get',
//        'match' => 'cv',
//        'target' => 'cv.php'
    ]
];

foreach($routes as $route)
{
    $coreDbc -> query('insert into `route`(`instance-id`, `default`, `method`, `match`, `target`, `type`)
    values
    ('.$instanceId.', \''.$route['default'].'\', \''.$route['method'].'\', \''.$route['match'].'\', \''.$route['target'].'\', \'file\')');
    $coreDbc -> execute();
}
