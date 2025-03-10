<?php
class Palworld
{
    /**
     * @return Php2Core\IO\Directory
     */
    public static function installation(): Php2Core\IO\Directory
    {
        $palServerDir = \Php2Core\IO\Directory::fromString('D:\PalServer');
	if(!$palServerDir -> exists())
	{
            $palServerDir = \Php2Core\IO\Directory::fromString('/home/petero/.local/share/Steam/steamapps/compatdata/1623730/pfx/drive_c/users/steamuser/AppData/Local/');
	}
        return $palServerDir;
    }
    
    /**
     * @return Php2Core\IO\Directory
     */
    public static function saves(): array
    {
        $saveGames = \Php2Core\IO\Directory::fromDirectory(self::installation(), 'Pal/Saved/SaveGames');

        $buffer = [];
        
        foreach($saveGames -> list() as $entry)
        {
            if($entry instanceof Php2Core\IO\Directory)
            {
                $level = \Php2Core\IO\File::fromDirectory($entry, 'Level.sav');
                if($level -> exists())
                {
                    $buffer[] = $entry;
                }
                else
                {
                    foreach($entry -> list() as $subEntry)
                    {
                        if($subEntry instanceof Php2Core\IO\Directory)
                        {
                            $level = \Php2Core\IO\File::fromDirectory($subEntry, 'Level.sav');
                            if($level -> exists())
                            {
                                $buffer[] = $subEntry;
                            }
                        }
                    }
                }
            }
        }
        
        return $buffer;
    }
    
    /**
     * @return Php2Core\IO\Directory
     */
    public static function temp(): Php2Core\IO\Directory
    {
        $temp = Php2Core\IO\Directory::fromString('__TEMP__');
        if($temp -> exists())
        {
            $temp -> remove();
        }
        $temp -> create();
        
        return $temp;
    }
}


if(ROUTE -> route()['method'] === 'post')
{
//    $username = filter_input(INPUT_POST, 'username');
//    $password = filter_input(INPUT_POST, 'password');
//    
//    $coreDbc = \Php2Core\Db\Database::getInstance('Php2Core');
//    $coreDbc -> query('select `id` from `user` where `username` = "'.$username.'" and `password` = user_password("'.$password.'")');
//    $result = $coreDbc -> execute();
//    
//    if($result['iRowCount'] === 0)
//    {
//        throw new \Php2Core\Exceptions\NotImplementedException('not found');
//    }
//    
//    $id = $result['aResults'][0]['id'];
//    Php2Core::session_set('user/id', $id);
//    Php2Core::refresh(Php2Core::baseUrl());
    
    echo '<xmp>';
    var_dump(__FILE__.':'.__LINE__);
    print_r($_POST);
    echo '</xmp>';
}
else
{
    $temp = Palworld::Temp();
    $saves = Palworld::Saves();
    
    $worldOptionsOrigional = \Php2Core\IO\File::fromDirectory($saves[0], 'WorldOption.sav');
    $worldOptionsOrigional -> copyTo($temp);
    
    $worldOptionsTemp = Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($temp, 'WorldOption.sav');
    $worldOptionsTempGvas = $worldOptionsTemp -> decode($temp);
    
    if($worldOptionsTempGvas instanceof \Php2Core\Gaming\Engines\Unreal\Gvas)
    {
        XHTML -> get('body', function(Php2Core\NoHTML\Xhtml $body) use($worldOptionsTempGvas)
        {
            $body -> get('div@.section/h6', function(\Php2Core\NoHTML\Xhtml $h6)
            {
                $h6 -> clear();
                $h6 -> text('Palworld - World Options');
            });
            
            $xml = \Php2Core\IO\Common\Xml::fromString(__DIR__.'/worldoptions.xml') -> document();
            if($xml instanceof Php2Core\IO\Xml\Document)
            {
                $options = $xml -> children()[0];
                $basePath = $options -> attributes()['path'];
                
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
                
                $form -> submit('Save', function(Php2Core\NoHTML\Materialize\Form\Options $options)
                {
                    $options -> size(Php2Core\NoHTML\Materialize\Columns::S1);
                    $options -> offset(Php2Core\NoHTML\Materialize\Columns::S11);
                }) -> parent() -> attributes() -> set('style', 'text-align: right;');
            }
        });
    }
}
