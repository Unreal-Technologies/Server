<?php
//App Config
define('DEBUG', true);
define('TITLE', 'A Lonely Gameserver');

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
    $head -> Link(function(\Php2Core\NoHTML\Link $link)
    {
        $link -> Attributes() -> Set('rel', 'stylesheet');
        $link -> Attributes() -> Set('href', Php2Core::PhysicalToRelativePath(realpath(__DIR__.'/Assets/Style.css')));
    });
});
HTML -> Body(function(\Php2Core\NoHTML\Body $body)
{
    $body -> H2(TITLE);
});
?>