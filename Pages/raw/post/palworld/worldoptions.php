<?php
$temp = PHP2CORE -> get(\Php2Core::Temp);
$root = PHP2CORE -> get(Php2Core::Root);

$worldOptionsOrigional = \Php2Core\IO\File::fromString($root -> path().'/Php2Core/Gaming/Assets/Palworld/WorldOption.sav');
$worldOptionsOrigional -> copyTo($temp);

$worldOptionsTemp = Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($temp, 'WorldOption.sav');
$worldOptionsTempGvas = $worldOptionsTemp -> decode($temp);

$basePath = null;
$xml = \Php2Core\IO\Common\Xml::fromString($root -> path().'/Assets/worldoptions.xml') -> document();
if($xml instanceof Php2Core\IO\Xml\Document)
{
    $options = $xml -> children()[0];
    $basePath = $options -> attributes()['path'];
}

if($worldOptionsTempGvas instanceof \Php2Core\Gaming\Engines\Unreal\Gvas)
{
    foreach(array_keys($_POST) as $key)
    {
        $path = sprintf($basePath, $key);
        $value = filter_input(INPUT_POST, $key);
        
        $worldOptionsTempGvas -> set($path, $value);
    }
    $newworldOptionsTempGvas = $worldOptionsTempGvas -> save();

    if($newworldOptionsTempGvas instanceof \Php2Core\Gaming\Engines\Unreal\Gvas)
    {
        $newSav = \Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($newworldOptionsTempGvas -> parent(), $newworldOptionsTempGvas -> basename().'.sav');
        if($newSav instanceof \Php2Core\Gaming\Games\Palworld\Sav)
        {
            $newSav -> encode($newworldOptionsTempGvas);
        }
        
        $newSav -> forceDownload('WorldOption.sav');
        exit;
    }
}