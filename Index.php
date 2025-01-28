<?php
//App Config
define('TITLE', 'A Lonely Gameserver');

//Update Core Version to App Version
$version = clone VERSION;
VERSION -> update(TITLE, 1,0,0,0);
VERSION -> clear();
VERSION -> add($version);

//Start Rendering
XHTML -> get('head', function(Php2Core\NoHTML\XHtml $head)
{
   $head -> add('title', function(Php2Core\NoHTML\XHtml $title)
   {
       $title -> text(TITLE);
   }); 
   $head -> add('link', function(\Php2Core\NoHTML\XHtml $link)
    {
        $link -> attributes() -> set('rel', 'stylesheet');
        $link -> attributes() -> set('href', Php2Core::PhysicalToRelativePath(realpath(__DIR__.'/Assets/Style.css')));
    });
});
XHTML -> get('body', function(Php2Core\NoHTML\XHtml $body)
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
    
    $body -> add('h3', function(Php2Core\NoHTML\XHtml $h2)
    {
        $h2 -> text(TITLE);
    });
    $body -> add('xmp', function(Php2Core\NoHTML\XHtml $xmp)
    {
        $xmp -> text(print_r(ROUTE, true));
    });
});
