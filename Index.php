<?php
//Update Core Version to App Version
$version = clone VERSION;
VERSION -> update(TITLE, 1,0,0,0);
VERSION -> clear();
VERSION -> add($version);

//Start Rendering
XHTML -> get('head', function(Php2Core\NoHTML\Xhtml $head)
{
    $head -> add('title', function(Php2Core\NoHTML\Xhtml $title)
    {
        $title -> text(TITLE);
    }); 
    $head -> add('link', function(\Php2Core\NoHTML\Xhtml $link)
    {
        $link -> attributes() -> set('rel', 'stylesheet');
        $link -> attributes() -> set('href', Php2Core::PhysicalToRelativePath(realpath(__DIR__.'/Assets/Style.css')));
    });
});
XHTML -> get('body', function(Php2Core\NoHTML\Xhtml $body)
{
    $dirname = pathinfo($_SERVER['SCRIPT_NAME'])['dirname'];
    
    $navBar = new \Php2Core\NoHTML\Materialize\Navigation();
    $navBar -> link('Home', $dirname.'/home');
    $navBar -> link('Map Viewer', $dirname.'/mapViewer');
    $navBar -> link('Downloads', $dirname.'/downloads');
    $navBar -> navBar($body);

    $body -> add('xmp', function(Php2Core\NoHTML\Xhtml $xmp)
    {
        $xmp -> text(print_r(ROUTE, true));
    });
    
    $body -> add('div', function(\Php2Core\NoHTML\Xhtml $div)
    {
        $div -> attributes() -> set('id', 'copyright');
        $div -> add('a', function(\Php2Core\NoHTML\Xhtml $a)
        {
            $a -> attributes() -> set('href', Php2Core::baseUrl().'/cv');
            $a -> text('&copy; Peter Overeijnder '.date('Y'));
        });
    });
    
    $body -> get('div/nav/div', function(\Php2Core\NoHTML\Xhtml $nav)
    {
        $nav -> add('a', function(Php2Core\NoHTML\Xhtml $a)
        {
            $a -> text(TITLE);
            $a -> attributes() -> set('href', '#!');
            $a -> attributes() -> set('class', 'brand-logo right');
        });
    });
});
