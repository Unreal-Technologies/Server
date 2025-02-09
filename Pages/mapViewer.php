<?php
require_once('Code/Palworld/Sav.php');
require_once('Code/Palworld/Gvas.php');

class PalServer
{
    /**
     * @var PalSave[]
     */
    private array $aSaves = [];
    
    /**
     * @param \Php2Core\IO\Directory $palServer
     * @throws \Php2Core\Exceptions\NotImplementedException
     */
    public function __construct(\Php2Core\IO\Directory $palServer)
    {
        if(!$palServer -> exists())
        {
            throw new \Php2Core\Exceptions\NotImplementedException();
        }
        $saveGames = \Php2Core\IO\Directory::fromDirectory($palServer, 'Pal/Saved/SaveGames');
        if(!$saveGames -> exists())
        {
            throw new \Php2Core\Exceptions\NotImplementedException();
        }
        
        foreach($saveGames -> list() as $instance)
        {
            $path = $instance -> name();
            foreach($instance -> list() as $hashed)
            {
                $path .= '/'.$hashed -> name();
                
                $this -> aSaves[] = new PalSave($hashed, $path);
            }
        }
    }
}

class PalSave
{
    /**
     * @var string
     */
    private string $sName = '';
    
    /**
     * @var \Code\Palworld\Gvas|null
     */
    private ?\Code\Palworld\Gvas $oWorldOption = null;
    
    /**
     * @var \Code\Palworld\Sav|null
     */
    private ?\Code\Palworld\Sav $oLevel = null;
    
    /**
     * @var \Code\Palworld\Gvas|null
     */
    private ?\Code\Palworld\Gvas $oLevelMeta = null;
    
    /**
     * @var \Code\Palworld\Gvas[]
     */
    private array $aPlayers = [];


    public function __construct(\Php2Core\IO\Directory $save, string $name)
    {
        $temp = \Php2Core\IO\Directory::fromString('__TEMP__');
        if(!$temp -> exists())
        {
            $temp -> create();
        }
        $temp2 = \Php2Core\IO\Directory::fromDirectory($temp, 'Players');
        if(!$temp2 -> exists())
        {
            $temp2 -> create();
        }
        
        $this -> sName = $name;
        $this -> oWorldOption = \Code\Palworld\Sav::fromDirectory($save, 'WorldOption.sav') -> decode($temp);
        //$this -> oLevel = \Code\Palworld\Sav::fromDirectory($save, 'level.sav');
        $this -> oLevelMeta = \Code\Palworld\Sav::fromDirectory($save, 'levelMeta.sav') -> decode($temp);
        
        foreach(\Php2Core\IO\Directory::fromDirectory($save, 'players') -> list() as $player)
        {
            $this -> aPlayers[] = \Code\Palworld\Sav::fromString($player -> path()) -> decode($temp2);
        }
    }
}

XHTML -> get('body', function(Php2Core\NoHTML\Xhtml $body)
{
    //Set Title
    $body -> get('div@.section/h6', function(\Php2Core\NoHTML\Xhtml $h6)
    {
        $h6 -> clear();
        $h6 -> text('Map Viewer');
    });
    
    $palServer = new PalServer(\Php2Core\IO\Directory::fromString('D:\PalServer'));
    
//    echo '<xmp>';
//    print_r($palServer);
//    echo '</xmp>';
});
