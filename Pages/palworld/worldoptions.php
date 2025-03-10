<?php
$temp = Php2Core::temp();

$worldOptionsOrigional = \Php2Core\IO\File::fromString(__DIR__.'/../../Php2Core/Gaming/Assets/Palworld/WorldOption.sav');
$worldOptionsOrigional -> copyTo($temp);

$worldOptionsTemp = Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($temp, 'WorldOption.sav');
$worldOptionsTempGvas = $worldOptionsTemp -> decode($temp);

$basePath = null;
$xml = \Php2Core\IO\Common\Xml::fromString(__DIR__.'/worldoptions.xml') -> document();
if($xml instanceof Php2Core\IO\Xml\Document)
{
    $options = $xml -> children()[0];
    $basePath = $options -> attributes()['path'];
}

if(ROUTE -> route()['method'] === 'post' && $worldOptionsTempGvas instanceof \Php2Core\Gaming\Engines\Unreal\Gvas)
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
else
{
    if($worldOptionsTempGvas instanceof \Php2Core\Gaming\Engines\Unreal\Gvas)
    {
        XHTML -> get('body', function(Php2Core\NoHTML\Xhtml $body) use($worldOptionsTempGvas, $xml, $basePath)
        {
            $body -> get('div@.section/h6', function(\Php2Core\NoHTML\Xhtml $h6)
            {
                $h6 -> clear();
                $h6 -> text('Palworld - World Options');
            });
            
            if($xml instanceof Php2Core\IO\Xml\Document)
            {
                $options = $xml -> children()[0];

                $buffer = [];
                foreach($options -> children() as $option)
                {
                    $set = $option -> attributes();
                    $set['values'] = [];
                    
                    foreach($option -> children() as $value)
                    {
                        $set['values'][] = $value -> attributes();
                    }
                    
                    $buffer[] = $set;
                }
                
                $grouped = (new Php2Core\Collections\Linq($buffer)) -> orderBy(function($x)
                {
                    return $x['group'].'|'.$x['text'];
                }) -> groupBy(function($x)
                {
                    return $x['group'];
                }) -> toArray();
                
                $form = new \Php2Core\NoHTML\Materialize\Form($body, Php2Core\NoHTML\Materialize\Form\Methods::Post);
                
                foreach($grouped as $group)
                {
                    $groupText = $group[0]['group'];
                    $form -> reference() -> add('div@.section col s12 blue darken-4&style=text-align:center;border-radius: 10px;/h6') -> text($groupText);

                    foreach($group as $item)
                    {
                        $path = sprintf($basePath, $item['name']);
                        $value = $worldOptionsTempGvas -> get($path);
                        $type = Php2Core\NoHTML\Materialize\Form\InputTypes::fromString($item['type']);
                        
                        if($value === null)
                        {
                            $value = $item['default'];
                        }

                        if(is_array($value))
                        {
                            $value = $value['value'];
                        }
                        
                        $form -> field($item['name'], $item['text'], $type, $value, $item['required'] === 'true', function(Php2Core\NoHTML\Materialize\Form\Options $options) use($type, $item, $value)
                        {
                            $options -> size(Php2Core\NoHTML\Materialize\Columns::S3);
                            
                            if($type === Php2Core\NoHTML\Materialize\Form\InputTypes::Number)
                            {
                                $options -> min($item['min']);
                                $options -> max($item['max']);
                                $options -> step($item['step']);
                            }
                            
                            if($type === Php2Core\NoHTML\Materialize\Form\InputTypes::Select)
                            {
                                $selectOptions = new Php2Core\NoHTML\Materialize\Form\SelectOptions();
                                
                                foreach($item['values'] as $oVal)
                                {
                                    $selectOptions -> set($oVal['text'], $oVal['value'], $oVal['value'] === $value);
                                }
                                
                                $options -> options($selectOptions);
                            }
                        });
                    }
                }
                
                $form -> submit('Download', function(Php2Core\NoHTML\Materialize\Form\Options $options)
                {
                    $options -> size(Php2Core\NoHTML\Materialize\Columns::S2);
                    $options -> offset(Php2Core\NoHTML\Materialize\Columns::S10);
                }) -> parent() -> attributes() -> set('style', 'text-align: right;');
            }
        });
    }
}
