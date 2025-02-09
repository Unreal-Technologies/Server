<?php
namespace Code\Palworld;

class Gvas extends \Php2Core\IO\File
{
    /**
     * @var int
     */
    private int $iSaveType = -1;
    
    /**
     * @var \Php2Core\Gaming\UnrealEngine\Gvas\Header|null
     */
    private ?\Php2Core\Gaming\UnrealEngine\Gvas\Header $oHeader = null;
    
    /**
     * @var array
     */
    private array $aProperties = [];
    
    /**
     * @var \Php2Core\IO\Data\BinaryStreamReader|null
     */
    private ?\Php2Core\IO\Data\BinaryStreamReader $bsr = null;
    
    #[\Override]
    public function write(string $sStream, bool $bCreateDirectory = true): void 
    {
        parent::write($sStream, $bCreateDirectory);
        
        $this -> initialize();
    }
    
    #[\Override]
    public static function fromString(string $sPath): \Php2Core\IO\IFile 
    {
        $res = parent::fromString($sPath);
        $res -> initialize();
        return $res;
    }
    
    #[\Override]
    public static function fromFile(\Php2Core\IO\IFile $oFile): \Php2Core\IO\IFile 
    {
        $res = parent::fromFile($oFile);
        $res -> initialize();
        return $res;
    }
    
    #[\Override]
    public static function fromDirectory(\Php2Core\IO\IDirectory $oDir, string $sName): ?\Php2Core\IO\IFile
    {
        $res = parent::fromDirectory($oDir, $sName);
        $res -> initialize();
        return $res;
    }
    
    /**
     * @param \Php2Core\IO\Directory $directory
     * @return Sav
     */
    public function encode(\Php2Core\IO\Directory $directory): Sav
    {
        $gvas = unserialize($this -> read());
        
        $sav = $this -> binaryEncode($gvas['data'], $gvas['type']);
        
        $file = Sav::fromDirectory($directory, $this -> basename().'.sav');
        $file -> write($sav);
        
        return $file;
    }
    
    /**
     * @return void
     */
    private function initialize(): void
    {
        if(!$this -> exists())
        {
            return;
        }
        $bytes = $this -> read();
        if(strlen($bytes) === 0)
        {
            return;
        }
        
        $data = unserialize($bytes);
        $this -> iSaveType = $data['type'];
        $this -> bsr = new \Php2Core\IO\Data\BinaryStreamReader($data['data']);
        
        $this -> oHeader = new \Php2Core\Gaming\UnrealEngine\Gvas\Header($this -> bsr);
        $this -> aProperties = $this -> propertiesUntilEnd();
        $this -> bsr = null;
        
        $f = \Php2Core\IO\File::fromDirectory($this -> parent(), $this -> basename().'.txt');
        $f -> write(print_r($this, true));
    }
    
    /**
     * @return array
     */
    private function propertiesUntilEnd(): array
    {
        $properties = [];
        while(true)
        {
            $name = $this -> bsr -> fString();
            if($name === 'None')
            {
                break;
            }
            $typeName = $this -> bsr -> fString();
            $size = $this -> bsr -> u64();
            
            $properties[$name] = $this -> property($name, $typeName, $size);
        }
        return $properties;
    }
    
    /**
     * @return array
     */
    private function struct(): array
    {
        $structType = $this -> bsr -> fString();
        
        return [
            'struct_type' => $structType,
            'struct_id' => $this -> bsr -> guid(),
            'id' => $this -> bsr -> optionalGuid(),
            'value' => $this -> structValue($structType)
        ];
    }
    
    /**
     * @param string $structType
     * @return type
     */
    private function structValue(string $structType): mixed
    {
        switch($structType)
        {
            
            case 'LinearColor':
                return $this -> colorDict();
            case 'Vector':
                return $this -> vectorDict();
            case 'DateTime':
                return $this -> bsr -> u64();
            case 'Guid':
                return $this -> bsr -> guid();
            case 'Quat':
                return $this -> quatDict();
            default:
                return $this -> propertiesUntilEnd();
        }
    }
    
    /**
     * @return array
     */
    private function colorDict(): array
    {
        return [
            'r' => $this -> bsr -> float(),
            'g' => $this -> bsr -> float(),
            'b' => $this -> bsr -> float(),
            'a' => $this -> bsr -> float()
        ];
    }
    
    /**
     * @return array
     */
    private function vectorDict(): array
    {
        return [
            'x' => $this -> bsr -> double(),
            'y' => $this -> bsr -> double(),
            'z' => $this -> bsr -> double()
        ];
    }
    
    /**
     * @return array
     */
    private function quatDict(): array
    {
        return [
            'x' => $this -> bsr -> double(),
            'y' => $this -> bsr -> double(),
            'z' => $this -> bsr -> double(),
            'w' => $this -> bsr -> double()
        ];
    }
    
    /**
     * @param string $arrayType
     * @param int $count
     * @param int $size
     * @return array
     * @throws \Php2Core\Exceptions\NotImplementedException
     */
    private function arrayValue(string $arrayType, int $count, int $size): array
    {
        $values = [];
        $callback = null;
        
        switch($arrayType)
        {
            case 'EnumProperty':
            case 'NameProperty':
                $callback = function(\Php2Core\IO\Data\BinaryStreamReader $bsr)
                {
                    return $bsr -> fString();
                };
                break;
            case 'Guid':
                $callback = function(\Php2Core\IO\Data\BinaryStreamReader $bsr)
                {
                    return $bsr -> guid();
                };
                break;
            case 'ByteProperty':
                if($size === $count)
                {
                    $callback = function(\Php2Core\IO\Data\BinaryStreamReader $bsr) use($size)
                    {
                        return $bsr -> bytes($size);
                    };
                }
                else
                {
                    throw new \Php2Core\Exceptions\NotImplementedException('Labelled ByteProperty not implemented');
                }
                break;
            default:
                echo '<xmp>';
                var_dump(__FILE__.':'.__LINE__);
                var_dumP($arrayType);
                var_dump($count);
                var_dump($size);
                echo '</xmp>';
                exit;
        }
        
        for($i=0; $i<$count; $i++)
        {
            $values[] = $callback($this -> bsr);
        }
        
        return $values;
    }
    
    /**
     * @param string $arrayType
     * @param int $size
     * @return array
     */
    private function arrayProperty(string $arrayType, int $size): array
    {
        $count = $this -> bsr -> u32();
        $value =  null;
        if($arrayType === 'StructProperty')
        {
            $propName = $this -> bsr -> fString();
            $propType = $this -> bsr -> fString();
            $this -> bsr -> u64();
            $typeName = $this -> bsr -> fString();
            $id = $this -> bsr -> guid();
            $this -> bsr -> skip(1);
            
            $propValues = [];
            for($i=0; $i<$count; $i++)
            {
                $propValues[] = $this -> structValue($typeName);
            }
            
            $value = [
                'prop_name' => $propName,
                'prop_type' => $propType,
                'values' => $propValues,
                'type_name' => $typeName,
                'id' => $id
            ];
        }
        else
        {
            $value = [
                'values' => $this -> arrayValue($arrayType, $count, $size)
            ];
        }
        return $value;


    }
    
    /**
     * @param string $typeName
     * @param string|null $structTypeName
     * @return mixed
     * @throws \Php2Core\Exceptions\UnexpectedValueException
     */
    private function propertyValue(string $typeName, ?string $structTypeName): mixed
    {
        switch($typeName)
        {
            case 'BoolProperty':
                return $this -> bsr -> bool();
            case 'IntProperty':
                return $this -> bsr -> i32();
            case 'StructProperty':
                return $this -> structValue($structTypeName);
            case 'EnumProperty':
            case 'NameProperty':
                return $this -> bsr -> fString();
            default:
                throw new \Php2Core\Exceptions\UnexpectedValueException('Unknown property value type: '.$typeName);
        }

        return null;
    }
    
    /**
     * @param string $name
     * @param string $typeName
     * @param string $size
     * @return array|null
     */
    private function property(string $name, string $typeName, string $size): ?array
    {
        $value = null;
        switch($typeName)
        {
            case 'MapProperty':
                $keyType = $this -> bsr -> fString();
                $valueType = $this -> bsr -> fString();
                $id = $this -> bsr -> optionalGuid();
                $this -> bsr -> u32();
                $count = $this -> bsr -> u32();
                
                $keyStructType = null;
                if($keyType === 'StructProperty')
                {
                    //key_struct_type = self.get_type_or(key_path, "Guid")
                    throw new \Php2Core\Exceptions\NotImplementedException();
                }
                
                $valueStructType = null;
                if($valueType === 'StructProperty')
                {
                    //value_struct_type = self.get_type_or(value_path, "StructProperty")
                    throw new \Php2Core\Exceptions\NotImplementedException();
                }
                
                $values = [];
                for($i=0; $i<$count; $i++)
                {
                    $key = $this -> propertyValue($keyType, $keyStructType);
                    $value = $this -> propertyValue($valueType, $valueStructType);
                    $values[] = [
                        'key' => $key,
                        'value' => $value
                    ];
                }
                
                $value = [
                    'key_type' => $keyType,
                    'value_type' => $valueType,
                    'key_struct_type' => $keyStructType,
                    'value_struct_type' => $valueStructType,
                    'id' => $id,
                    'value' => $values
                ];
                break;
            case 'ArrayProperty':
                $arrayType = $this -> bsr -> fstring();
                
                $value = [
                    'array_type' => $arrayType,
                    'id' => $this -> bsr -> optionalGuid(),
                    'value' => $this -> arrayProperty($arrayType, $size - 4)
                ];
                break;
            case 'NameProperty':
            case 'StrProperty':
                $value = [
                    'id' => $this -> bsr -> optionalGuid(),
                    'value' => $this -> bsr -> fString()
                ];
                break;
            case 'BoolProperty':
                $value = [
                    'value' => $this -> bsr -> bool(),
                    'id' => $this -> bsr -> optionalGuid()
                ];
                break;
            case 'IntProperty':
                $value = [
                    'id' => $this -> bsr -> optionalGuid(),
                    'value' => $this -> bsr -> i32()
                ];
                break;
            case 'FloatProperty':
                $value = [
                    'id' => $this -> bsr -> optionalGuid(),
                    'value' => $this -> bsr -> float()
                ];
                break;
            case 'StructProperty':
                $value = $this -> struct();
                break;
            default:
                echo '<xmp>';
                var_dump(__FILE__.':'.__LINE__);
                var_dump($name);
                var_dump($typeName);
                var_dump($size);
                echo '</xmp>';
        }
        return $value;
    }
    
    /**
     * @param string $bytes
     * @param int $type
     * @return string
     */
    private function binaryEncode(string $bytes, int $type): string
    {
        $enc = ZLIB_ENCODING_DEFLATE;
        
        $uncompressedLength = strlen($bytes);
        $compressedData = zlib_encode($bytes, $enc);
        $compressedLength = strlen($compressedData);
        
        if($type === '2')
        {
            $compressedData = zlib_encode($compressedData, $enc, -1);
        }
        
        $upl = pack('v', $uncompressedLength);
        $cpl = pack('v', $compressedLength);
        while(strlen($upl) < 4)
        {
            $upl .= chr(0);
        }
        while(strlen($cpl) < 4)
        {
            $cpl .= chr(0);
        }
        
        $result = $upl;
        $result .= $cpl;
        $result .= 'PlZ';
        $result .= $type;
        $result .= $compressedData;
        return $result;
    }
}
