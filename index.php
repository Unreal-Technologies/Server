<?php
//Update Core Version to App Version
$version = clone VERSION;
VERSION -> update(TITLE, 1,0,0,1);
VERSION -> clear();
VERSION -> add($version);

//Start Rendering
XHTML -> get('head', function(Php2Core\NoHTML\Xhtml $head)
{
    $head -> add('title') -> text(TITLE);
    $head -> add('link@rel=stylesheet', function(\Php2Core\NoHTML\Xhtml $link)
    {
        $link -> attributes() -> set('href', Php2Core::PhysicalToRelativePath(realpath(__DIR__.'/Assets/style.css')));
    });
});

XHTML -> get('body', function(Php2Core\NoHTML\Xhtml $body)
{
    $baseUrl = Php2Core::baseUrl();
    
    $navBar = new \Php2Core\NoHTML\Materialize\Navigation();
    $navBar -> link('Home', $baseUrl.'/home');
//    $navBar -> link('Map Viewer', $baseUrl.'/mapViewer');
    $navBar -> link('Downloads', $baseUrl.'/downloads');
    if(Php2Core::isAuthenticated())
    {
        $navBar -> submenu('Palworld', function(Php2Core\NoHTML\Materialize\Submenu $palworld) use($baseUrl)
        {
            $palworld -> link('World Options', $baseUrl.'/Palworld/WorldOptions');
        });
    }
    $navBar -> submenu('Account', function(Php2Core\NoHTML\Materialize\Submenu $account) use($baseUrl)
    {
        if(!Php2Core::isAuthenticated())
        {
            $account -> link('Login', $baseUrl.'/login');
        }
        else
        {
            $account -> link('Logout', $baseUrl.'/logout');
        }
    });
    $navBar -> navBar($body);

    $targetFile = realpath(Php2Core::root().'/Pages/'.ROUTE -> target()['target']);
    
    $body -> add('div@.section/h6') -> text('-title-');
    $body -> add('div@.divider');

    if($targetFile !== false)
    {
        include($targetFile);
    }
    
    $body -> add('div@#copyright/a', function(\Php2Core\NoHTML\Xhtml $a)
    {
        $a -> attributes() -> set('href', Php2Core::baseUrl().'/cv');
    }) -> text('&copy; Peter Overeijnder '.date('Y'));

    $body -> get('div/nav/div', function(\Php2Core\NoHTML\Xhtml $nav)
    {
        $nav -> add('a@.brand-logo right&href=#!') -> text(TITLE);
    });
});
