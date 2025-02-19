<?php
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
			if($instance instanceof \Php2Core\IO\File)
			{
				continue;
			}

            $path = $instance -> name();
            foreach($instance -> list() as $hashed)
            {
				if($hashed instanceof \Php2Core\IO\File)
				{
					continue;
				}
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
     * @var \Php2Core\Gaming\Engines\Unreal\Gvas|null
     */
    private ?\Php2Core\Gaming\Engines\Unreal\Gvas $oWorldOption = null;
    
    /**
     * @var  \Php2Core\Gaming\Engines\Unreal\Gvas|null
     */
    private ? \Php2Core\Gaming\Engines\Unreal\Gvas $oLevel = null;
    
    /**
     * @var \Php2Core\Gaming\Engines\Unreal\Gvas|null
     */
    private ?\Php2Core\Gaming\Engines\Unreal\Gvas $oLevelMeta = null;
    
    /**
     * @var \Php2Core\Gaming\Engines\Unreal\Gvas[]
     */
    private array $aPlayers = [];

	/**
	 * @param \Php2Core\IO\Directory $save
	 * @param string $name
	 */
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
        $this -> oWorldOption = \Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($save, 'WorldOption.sav') -> decode($temp);
        $this -> oLevelMeta = \Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($save, 'LevelMeta.sav') -> decode($temp);
        $this -> oLevel = \Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($save, 'Level.sav') -> decode($temp);
		
		//$this -> oLevelMeta -> save();
		
//		$new = \Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($temp, 'LevelMeta.gvas2');
//		$comp = new Php2Core\IO\Data\BinaryCompare($this -> oLevelMeta, $new);
//		
//		echo $comp;
		
		
//        $this -> oLevel = \Php2Core\Gaming\Games\Palworld\Sav::fromDirectory($save, 'level.sav');
//        
//        
        foreach(\Php2Core\IO\Directory::fromDirectory($save, 'Players') -> list() as $player)
        {
            $player = \Php2Core\Gaming\Games\Palworld\Sav::fromString($player -> path()) -> decode($temp2);
//            $player -> save();
            $this -> aPlayers[] = $player;
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
    
	$palServerDir = \Php2Core\IO\Directory::fromString('D:\PalServer');
	if(!$palServerDir -> exists())
	{
		$palServerDir = \Php2Core\IO\Directory::fromString('/home/petero/.local/share/Steam/steamapps/compatdata/1623730/pfx/drive_c/users/steamuser/AppData/Local/');
	}
	
    new PalServer($palServerDir);
});
