<?php
//App Config
define('TITLE', 'A Lonely Gameserver');

//Update Core Version to App Version
$version = clone VERSION;
VERSION -> Update(TITLE, 1,0,0,0);
VERSION -> Clear();
VERSION -> Add($version);

//Start Rendering
XHTML -> Get('head', function(Php2Core\NoHTML\XHtml $head)
{
   $head -> Add('title', function(Php2Core\NoHTML\XHtml $title)
   {
       $title -> Text(TITLE);
   }); 
   $head -> Add('link', function(\Php2Core\NoHTML\XHtml $link)
    {
        $link -> Attributes() -> Set('rel', 'stylesheet');
        $link -> Attributes() -> Set('href', Php2Core::PhysicalToRelativePath(realpath(__DIR__.'/Assets/Style.css')));
    });
});
XHTML -> Get('body', function(Php2Core\NoHTML\XHtml $body)
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
    
    new Php2Core\NoHTML\Materialize\Navigation($body, $links);
    
    $body -> Add('h3', function(Php2Core\NoHTML\XHtml $h2)
    {
        $h2 -> Text(TITLE);
    });
    $body -> Add('xmp', function(Php2Core\NoHTML\XHtml $xmp)
    {
        $xmp -> Text(print_r(ROUTE, true));
    });
});
?>