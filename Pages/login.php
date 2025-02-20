<?php
if(ROUTE -> route()['method'] === 'post')
{
    echo '<xmp>';
    var_dump(__FILE__.':'.__LINE__);
    print_r($_POST);
    print_r(ROUTE);
    echo '</xmp>';
}
else
{
    XHTML -> get('body', function(Php2Core\NoHTML\Xhtml $body)
    {
        $body -> get('div@.section/h6', function(\Php2Core\NoHTML\Xhtml $h6)
        {
            $h6 -> clear();
            $h6 -> text('Login');
        });
        
        $form = new \Php2Core\NoHTML\Materialize\Form($body, Php2Core\NoHTML\Materialize\Form\Methods::Post);
        $form -> field('username', 'Username', \Php2Core\NoHTML\Materialize\Form\InputTypes::Text);
        $form -> field('password', 'Password', \Php2Core\NoHTML\Materialize\Form\InputTypes::Password);
        $form -> button('Login', 'submit()', \Php2Core\NoHTML\Materialize\Columns::S1, \Php2Core\NoHTML\Materialize\Columns::S11);

        $body -> add('script', function(\Php2Core\NoHTML\Xhtml $js)
        {
            $js -> attributes() -> set('type', 'text/javascript');
        }) -> text('function submit()'
            . '{'
                . 'document.forms[0].submit();'
            . '}');
    });
}
