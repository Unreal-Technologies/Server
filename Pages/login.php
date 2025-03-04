<?php
if(ROUTE -> route()['method'] === 'post')
{
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    
    $coreDbc = \Php2Core\Db\Database::getInstance('Php2Core');
    $coreDbc -> query('select `id` from `user` where `username` = "'.$username.'" and `password` = user_password("'.$password.'")');
    $result = $coreDbc -> execute();
    
    if($result['iRowCount'] === 0)
    {
        throw new \Php2Core\Exceptions\NotImplementedException('not found');
    }
    
    $id = $result['aResults'][0]['id'];
    Php2Core::session_set('user/id', $id);
    Php2Core::refresh(Php2Core::baseUrl());
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
        $form -> field('username', 'Username', \Php2Core\NoHTML\Materialize\Form\InputTypes::Text, '', true);
        $form -> field('password', 'Password', \Php2Core\NoHTML\Materialize\Form\InputTypes::Password, '', true);
        $form -> submit('Login', function(Php2Core\NoHTML\Materialize\Form\Options $options)
        {
            $options -> size(Php2Core\NoHTML\Materialize\Columns::S1);
            $options -> offset(Php2Core\NoHTML\Materialize\Columns::S11);
        }) -> parent() -> attributes() -> set('style', 'text-align: right;');
    });
}
