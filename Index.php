<?php
include 'Php2core/Init.php';

//Update Core Version to App Version
$version = clone VERSION;
VERSION -> Update('Server Control', 1,0,0,0);
VERSION -> Clear();
VERSION -> Add($version);

//Start Rendering
HTML -> Head(function(\Php2Core\NoHTML\Head $head)
{
});
HTML -> Body(function(\Php2Core\NoHTML\Body $body)
{
    $body -> Raw(VERSION);
});

//output
echo '<xmp>';
echo HTML;
echo '</xmp><hr />';

echo HTML;
?>