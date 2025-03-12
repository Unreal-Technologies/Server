<?php
//Update Core Version to App Version
$bVersion = PHP2CORE -> get(\Php2Core::Version);

$version = clone $bVersion;
$bVersion -> update(PHP2CORE -> get(\Php2Core::Title), 1,0,0,2);
$bVersion -> clear();
$bVersion -> add($version);

//Start Rendering
XHTML -> get('head', function(Php2Core\GUI\NoHTML\Xhtml $head)
{
    $head -> add('title') -> text(PHP2CORE -> get(\Php2Core::Title));
    $head -> add('link@rel=stylesheet', function(\Php2Core\GUI\NoHTML\Xhtml $link)
    {
        $link -> attributes() -> set('href', PHP2CORE -> physicalToRelativePath(realpath(__DIR__.'/Assets/style.css')));
    });
});

XHTML -> get('body', function(Php2Core\GUI\NoHTML\Xhtml $body)
{
    $baseUrl = PHP2CORE -> baseUrl();
    
    $navBar = new \Php2Core\GUI\NoHTML\Materialize\Navigation();
    $navBar -> link('Home', $baseUrl.'/home');
//    $navBar -> link('Map Viewer', $baseUrl.'/mapViewer');
    $navBar -> link('Downloads', $baseUrl.'/downloads');
    if(Php2Core::isAuthenticated())
    {
        $navBar -> submenu('Palworld', function(Php2Core\GUI\NoHTML\Materialize\Submenu $palworld) use($baseUrl)
        {
            $palworld -> link('World Options', $baseUrl.'/Palworld/WorldOptions');
        });
    }
    $navBar -> submenu('Account', function(Php2Core\GUI\NoHTML\Materialize\Submenu $account) use($baseUrl)
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

    $targetFile = realpath(PHP2CORE -> get(\Php2Core::Root) -> path().'/Pages/'.ROUTE -> target()['target']);
    
    $body -> add('div@.section/h6') -> text('-title-');
    $body -> add('div@.divider');

    if($targetFile !== false)
    {
        include($targetFile);
    }
    
    $body -> add('div@#copyright/a', function(\Php2Core\GUI\NoHTML\Xhtml $a)
    {
        $a -> attributes() -> set('href', PHP2CORE -> baseUrl().'/cv');
    }) -> text('&copy; Peter Overeijnder '.date('Y'));

    $body -> get('div/nav/div', function(\Php2Core\GUI\NoHTML\Xhtml $nav)
    {
        $nav -> add('a@.brand-logo right&href=#!') -> text(PHP2CORE -> get(\Php2Core::Title));
    });
});
