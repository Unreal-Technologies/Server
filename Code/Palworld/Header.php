<?php
namespace Code\Palworld;

class Header extends \Php2Core\IO\Data\BinaryStreamReader
{
    private int $iMagic = -1;
    private int $iSavegameVersion = -1;
    private int $iPackageFileVersionUe4 = -1;
    private int $iPackageFileVersionUe5 = -1;
    private int $iEngineVersionMajor = -1;
    private int $iEngineVersionMinor = -1;
    private int $iEngineVersionPatch = -1;
    private int $iEngineVersionChangelist = -1;
    private string $sEngineVersionBranch = '';
    private int $iCustomVersionFormat = -1;
    private array $aCustomVersions = [];
    private string $sSaveGameClassName = '';
    
    #[\Override]
    public function __construct(string $stream) 
    {
        parent::__construct($stream);
        
        $this -> iMagic = $this -> i32();
        if($this -> iMagic !== 0x53415647)
        {
            throw new \Php2Core\Exceptions\NotImplementedException('Invalid Magic: '.$this -> iMagic);
        }
        $this -> iSavegameVersion = $this -> i32();
        if($this -> iSavegameVersion !== 3)
        {
            throw new \Php2Core\Exceptions\NotImplementedException('Expected save game version 3, got: '.$this -> iSavegameVersion);
        }
        # Unreal Engine Version
        $this -> iPackageFileVersionUe4 = $this -> i32();
        $this -> iPackageFileVersionUe5 = $this -> i32();
        
        # Saved Engine Version
        $this -> iEngineVersionMajor = $this -> u16();
        $this -> iEngineVersionMinor = $this -> u16();
        $this -> iEngineVersionPatch = $this -> u16();
        $this -> iEngineVersionChangelist = $this -> u32();
        $this -> sEngineVersionBranch = $this -> fString();
        
        # Custom Version Format
        $this -> iCustomVersionFormat = $this -> i32();
        if($this -> iCustomVersionFormat !== 3)
        {
            throw \Php2Core\Exceptions\NotImplementedException('Expected custom version format 3, got: '.$this -> iCustomVersionFormat);
        }
        $this -> aCustomVersions = $this -> tArray(function(\Php2Core\IO\Data\BinaryStreamReader $reader)
        {
            return [$reader -> guid(), $reader -> i32()];
        });
        $this -> sSaveGameClassName = $this -> fstring();
    }
}