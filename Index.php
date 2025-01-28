<?php
//App Config
define('TITLE', 'A Lonely Gameserver');

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
    $dirname = pathinfo($_SERVER['SCRIPT_NAME'])['dirname'];
    $links = [
        ['Home', $dirname.'/home'],
        ['Map Viewer', $dirname.'/mapViewer'],
        ['Downloads', $dirname.'/downloads'],
        ['Drop', [
            ['Link1', $dirname.'/link1'],
            ['Link2', $dirname.'/link2'],
            null,
            ['Link3', $dirname.'/link3']
        ]]
    ];
    
    $body -> NavigationMA($links);

    $body -> H2(TITLE);
    $body -> Raw('<xmp>'.print_r(ROUTE, true).'</xmp>');
});
?>