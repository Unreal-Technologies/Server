<?php
//App Config
define('DEBUG', true);
define('TITLE', 'Server Control');

include 'Php2core/Init.php';

//Update Core Version to App Version
$version = clone VERSION;
VERSION -> Update(TITLE, 1,0,0,0);
VERSION -> Clear();
VERSION -> Add($version);

//Start Rendering
HTML -> Head(function(\Php2Core\NoHTML\Head $head)
{
    $head -> Title(TITLE);
});
HTML -> Body(function(\Php2Core\NoHTML\Body $body)
{
    $body -> H2(TITLE);
});
?>