<?php
class WorldOption
{
    public const DayTimeSpeedRate =                     [ 
            'path' => 'OptionWorldData/Settings/DayTimeSpeedRate', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ];
    public const NightTimeSpeedRate =                   [ 
            'path' => 'OptionWorldData/Settings/NightTimeSpeedRate', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ]; 
    public const ExpRate =                              [ 
            'path' => 'OptionWorldData/Settings/ExpRate', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ]; 
    public const PalCaptureRate =                       [ 
            'path' => 'OptionWorldData/Settings/PalCaptureRate', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ]; 
    public const PalSpawnNumRate =                      [ 
            'path' => 'OptionWorldData/Settings/PalSpawnNumRate', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ]; 
    public const PalDamageRateAttack =                  [ 
            'path' => 'OptionWorldData/Settings/PalDamageRateAttack', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ]; 
    public const PalDamageRateDefense =                 [ 
            'path' => 'OptionWorldData/Settings/PalDamageRateDefense', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ];
    public const PlayerDamageRateAttack =               [ 
            'path' => 'OptionWorldData/Settings/PlayerDamageRateAttack', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ]; 
    public const PlayerDamageRateDefense =              [ 
            'path' => 'OptionWorldData/Settings/PlayerDamageRateDefense', 
            'default' => 1, 
            'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text
        ];
    public const PlayerStomachDecreaceRate =            [ 'path' => 'OptionWorldData/Settings/PlayerStomachDecreaceRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PlayerStaminaDecreaceRate =            [ 'path' => 'OptionWorldData/Settings/PlayerStaminaDecreaceRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PlayerAutoHPRegeneRate =               [ 'path' => 'OptionWorldData/Settings/PlayerAutoHPRegeneRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PlayerAutoHpRegeneRateInSleep =        [ 'path' => 'OptionWorldData/Settings/PlayerAutoHpRegeneRateInSleep', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PalStomachDecreaceRate =               [ 'path' => 'OptionWorldData/Settings/PalStomachDecreaceRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PalStaminaDecreaceRate =               [ 'path' => 'OptionWorldData/Settings/PalStaminaDecreaceRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PalAutoHPRegeneRate =                  [ 'path' => 'OptionWorldData/Settings/PalAutoHPRegeneRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PalAutoHpRegeneRateInSleep =           [ 'path' => 'OptionWorldData/Settings/PalAutoHpRegeneRateInSleep', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BuildObjectHpRate =                    [ 'path' => 'OptionWorldData/Settings/BuildObjectHpRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BuildObjectDamageRate =                [ 'path' => 'OptionWorldData/Settings/BuildObjectDamageRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BuildObjectDeteriorationDamageRate =   [ 'path' => 'OptionWorldData/Settings/BuildObjectDeteriorationDamageRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const CollectionDropRate =                   [ 'path' => 'OptionWorldData/Settings/CollectionDropRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const CollectionObjectHpRate =               [ 'path' => 'OptionWorldData/Settings/CollectionObjectHpRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const CollectionObjectRespawnSpeedRate =     [ 'path' => 'OptionWorldData/Settings/CollectionObjectRespawnSpeedRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const EnemyDropItemRate =                    [ 'path' => 'OptionWorldData/Settings/EnemyDropItemRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const DeathPenalty =                         [ 'path' => 'OptionWorldData/Settings/DeathPenalty', 'default' => 'EPalOptionWorldDeathPenalty::None', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BEnablePlayerToPlayerDamage =          [ 'path' => 'OptionWorldData/Settings/bEnablePlayerToPlayerDamage', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BEnableFriendlyFire =                  [ 'path' => 'OptionWorldData/Settings/bEnableFriendlyFire', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BEnableInvaderEnemy =                  [ 'path' => 'OptionWorldData/Settings/bEnableInvaderEnemy', 'default' => true, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const EnablePredatorBossPal =                [ 'path' => 'OptionWorldData/Settings/EnablePredatorBossPal', 'default' => true, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BActiveUNKO =                          [ 'path' => 'OptionWorldData/Settings/bActiveUNKO', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BEnableAimAssistPad =                  [ 'path' => 'OptionWorldData/Settings/bEnableAimAssistPad', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BEnableAimAssistKeyboard =             [ 'path' => 'OptionWorldData/Settings/bEnableAimAssistKeyboard', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const DropItemMaxNum =                       [ 'path' => 'OptionWorldData/Settings/DropItemMaxNum', 'default' => 3000, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const DropItemMaxNum_UNKO =                  [ 'path' => 'OptionWorldData/Settings/DropItemMaxNum_UNKO', 'default' => 100, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BaseCampMaxNum =                       [ 'path' => 'OptionWorldData/Settings/BaseCampMaxNum', 'default' => 5, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BaseCampMaxNumInGuild =                [ 'path' => 'OptionWorldData/Settings/BaseCampMaxNumInGuild', 'default' => 15, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BaseCampWorkerMaxNum =                 [ 'path' => 'OptionWorldData/Settings/BaseCampWorkerMaxNum', 'default' => 50, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const DropItemAliveMaxHours =                [ 'path' => 'OptionWorldData/Settings/DropItemAliveMaxHours', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BAutoResetGuildNoOnlinePlayers =       [ 'path' => 'OptionWorldData/Settings/bAutoResetGuildNoOnlinePlayers', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const AutoResetGuildTimeNoOnlinePlayers =    [ 'path' => 'OptionWorldData/Settings/AutoResetGuildTimeNoOnlinePlayers', 'default' => 72, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const GuildPlayerMaxNum =                    [ 'path' => 'OptionWorldData/Settings/GuildPlayerMaxNum', 'default' => 4, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PalEggDefaultHatchingTime =            [ 'path' => 'OptionWorldData/Settings/PalEggDefaultHatchingTime', 'default' => 72, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const WorkSpeedRate =                        [ 'path' => 'OptionWorldData/Settings/WorkSpeedRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const AutoSaveSpan =                         [ 'path' => 'OptionWorldData/Settings/AutoSaveSpan', 'default' => 30, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BIsMultiplay =                         [ 'path' => 'OptionWorldData/Settings/bIsMultiplay', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BIsPvP =                               [ 'path' => 'OptionWorldData/Settings/bIsPvP', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BHardcore =                            [ 'path' => 'OptionWorldData/Settings/bHardcore', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BPalLost =                             [ 'path' => 'OptionWorldData/Settings/bPalLost', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BCanPickupOtherGuildDeathPenaltyDrop = [ 'path' => 'OptionWorldData/Settings/bCanPickupOtherGuildDeathPenaltyDrop', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BEnableNonLoginPenalty =               [ 'path' => 'OptionWorldData/Settings/bEnableNonLoginPenalty', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BEnableFastTravel =                    [ 'path' => 'OptionWorldData/Settings/bEnableFastTravel', 'default' => true, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BIsStartLocationSelectByMap =          [ 'path' => 'OptionWorldData/Settings/bIsStartLocationSelectByMap', 'default' => true, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BExistPlayerAfterLogout =              [ 'path' => 'OptionWorldData/Settings/bExistPlayerAfterLogout', 'default' => true, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BEnableDefenseOtherGuildPlayer =       [ 'path' => 'OptionWorldData/Settings/bEnableDefenseOtherGuildPlayer', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BInvisibleOtherGuildBaseCampAreaFX =   [ 'path' => 'OptionWorldData/Settings/bInvisibleOtherGuildBaseCampAreaFX', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const BBuildAreaLimit =                      [ 'path' => 'OptionWorldData/Settings/bBuildAreaLimit', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const ItemWeightRate =                       [ 'path' => 'OptionWorldData/Settings/ItemWeightRate', 'default' => 1, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BShowPlayerList =                      [ 'path' => 'OptionWorldData/Settings/bShowPlayerList', 'default' => true, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const ServerPlayerMaxNum =                   [ 'path' => 'OptionWorldData/Settings/ServerPlayerMaxNum', 'default' => 8, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const ServerName =                           [ 'path' => 'OptionWorldData/Settings/ServerName', 'default' => 'Default Palworld Server', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const ServerDescription =                    [ 'path' => 'OptionWorldData/Settings/ServerDescription', 'default' => '', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const AdminPassword =                        [ 'path' => 'OptionWorldData/Settings/AdminPassword', 'default' => 'Hbhv22kv', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const ServerPassword =                       [ 'path' => 'OptionWorldData/Settings/ServerPassword', 'default' => 'logitech123', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PublicPort =                           [ 'path' => 'OptionWorldData/Settings/PublicPort', 'default' => 8211, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const PublicIP =                             [ 'path' => 'OptionWorldData/Settings/PublicIP', 'default' => '', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const RCONEnabled =                          [ 'path' => 'OptionWorldData/Settings/RCONEnabled', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const RCONPort =                             [ 'path' => 'OptionWorldData/Settings/RCONPort', 'default' => 25575, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const RESTAPIEnabled =                       [ 'path' => 'OptionWorldData/Settings/RESTAPIEnabled', 'default' => false, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::YesNo];
    public const RESTAPIPort =                          [ 'path' => 'OptionWorldData/Settings/RESTAPIPort', 'default' => 8212, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const Region =                               [ 'path' => 'OptionWorldData/Settings/Region', 'default' => '', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const BanListURL =                           [ 'path' => 'OptionWorldData/Settings/BanListURL', 'default' => 'https://api.palworldgame.com/api/banlist.txt', 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const SupplyDropSpan =                       [ 'path' => 'OptionWorldData/Settings/SupplyDropSpan', 'default' => 60, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const ChatPostLimitPerMinute =               [ 'path' => 'OptionWorldData/Settings/ChatPostLimitPerMinute', 'default' => 15, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const MaxBuildingLimitNum =                  [ 'path' => 'OptionWorldData/Settings/MaxBuildingLimitNum', 'default' => 0, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
    public const ServerReplicatePawnCullDistance =      [ 'path' => 'OptionWorldData/Settings/ServerReplicatePawnCullDistance', 'default' => 15000, 'type' => \Php2Core\NoHTML\Materialize\Form\InputTypes::Text];
}

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
            
            $rc = new ReflectionClass('WorldOption');
            $form = new \Php2Core\NoHTML\Materialize\Form($body, Php2Core\NoHTML\Materialize\Form\Methods::Post);
            
            foreach($rc -> getConstants() as $name => $data)
            {
                $value = $worldOptionsTempGvas -> get($data['path']);
                $type = $data['type'];
                
                if($value === null)
                {
                    $value = $data['default'];
                }
                
                if(is_array($value))
                {
                    $value = $value['value'];
                }
                
                $form -> field(lcfirst($name), $name, $type, $value, function(Php2Core\NoHTML\Materialize\Form\Options $options)
                {
                    $options -> size(Php2Core\NoHTML\Materialize\Columns::S3);
                });
            }
            
            $form -> submit('Save', function(Php2Core\NoHTML\Materialize\Form\Options $options)
            {
                $options -> size(Php2Core\NoHTML\Materialize\Columns::S1);
                $options -> offset(Php2Core\NoHTML\Materialize\Columns::S11);
            }) -> parent() -> attributes() -> set('style', 'text-align: right;');
        });
    }
    
}
