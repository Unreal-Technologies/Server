<?php
$coreDbc = \Php2Core\Db\Database::getInstance('Php2Core');

$coreDbc -> query('insert into `instance`(`name`) '
    . 'values '
    . '(\''.TITLE.'\')');
$instanceId =  $coreDbc -> execute()['iLastInsertId'];

$coreDbc -> query('insert into `route`(`instance-id`, `default`, `method`, `match`, `target`, `type`)
values
('.$instanceId.', \'true\', \'get\', \'home\', \'home.php\', \'file\')');
$coreDbc -> execute();

$coreDbc -> query('insert into `route`(`instance-id`, `default`, `method`, `match`, `target`, `type`)
values
('.$instanceId.', \'false\', \'get\', \'mapviewer\', \'mapviewer.php\', \'file\')');
$coreDbc -> execute();

$coreDbc -> query('insert into `route`(`instance-id`, `default`, `method`, `match`, `target`, `type`)
values
('.$instanceId.', \'false\', \'get\', \'downloads\', \'downloads.php\', \'file\')');
$coreDbc -> execute();