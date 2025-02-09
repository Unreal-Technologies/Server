<?php
namespace Code\Palworld;

class Gvas extends \Php2Core\IO\File
{
    /**
     * @var int
     */
    private int $iSaveType = -1;
    
    /**
     * @var Header|null
     */
    private ?Header $oHeader = null;
    
    /**
     * @var array
     */
    private array $aProperties = [];
    
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
        $bsr = new \Php2Core\IO\Data\BinaryStreamReader($data['data']);
        
        $this -> oHeader = new Header($bsr);
        $this -> aProperties = $this -> propertiesUntilEnd($bsr);
        
        $f = \Php2Core\IO\File::fromDirectory($this -> parent(), $this -> basename().'.txt');
        $f -> write(print_r($this, true));
    }
    
    private function propertiesUntilEnd(\Php2Core\IO\Data\BinaryStreamReader $bsr): array
    {
        $properties = [];
        while(true)
        {
            $name = $bsr -> fString();
            if($name === 'None')
            {
                break;
            }
            $typeName = $bsr -> fString();
            $size = $bsr -> u64();
            
            $properties[$name] = $this -> property($bsr, $name, $typeName, $size);
        }
        return $properties;
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @return array
     */
    private function struct(\Php2Core\IO\Data\BinaryStreamReader $bsr): array
    {
        $structType = $bsr -> fString();
        
        return [
            'struct_type' => $structType,
            'struct_id' => $bsr -> guid(),
            'id' => $bsr -> optionalGuid(),
            'value' => $this -> structValue($bsr, $structType)
        ];
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @param string $structType
     * @return type
     */
    private function structValue(\Php2Core\IO\Data\BinaryStreamReader $bsr, string $structType): mixed
    {
        switch($structType)
        {
            
            case 'LinearColor':
                return $this -> colorDict($bsr);
            case 'Vector':
                return $this -> vectorDict($bsr);
            case 'DateTime':
                return $bsr -> u64();
            case 'Guid':
                return $bsr -> guid();
            case 'Quat':
                return $this -> quatDict($bsr);
            default:
                return $this -> propertiesUntilEnd($bsr);
        }
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @return array
     */
    private function colorDict(\Php2Core\IO\Data\BinaryStreamReader $bsr): array
    {
        return [
            'r' => $bsr -> float(),
            'g' => $bsr -> float(),
            'b' => $bsr -> float(),
            'a' => $bsr -> float()
        ];
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @return array
     */
    private function vectorDict(\Php2Core\IO\Data\BinaryStreamReader $bsr): array
    {
        return [
            'x' => $bsr -> double(),
            'y' => $bsr -> double(),
            'z' => $bsr -> double()
        ];
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @return array
     */
    private function quatDict(\Php2Core\IO\Data\BinaryStreamReader $bsr): array
    {
        return [
            'x' => $bsr -> double(),
            'y' => $bsr -> double(),
            'z' => $bsr -> double(),
            'w' => $bsr -> double()
        ];
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @param string $arrayType
     * @param int $count
     * @param int $size
     * @return array
     * @throws \Php2Core\Exceptions\NotImplementedException
     */
    private function arrayValue(\Php2Core\IO\Data\BinaryStreamReader $bsr, string $arrayType, int $count, int $size): array
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
        
        $values = [];
        
        for($i=0; $i<$count; $i++)
        {
            $values[] = $callback($bsr);
        }
        
        return $values;
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @param string $arrayType
     * @param int $size
     * @return array
     */
    private function arrayProperty(\Php2Core\IO\Data\BinaryStreamReader $bsr, string $arrayType, int $size): array
    {
        $count = $bsr -> u32();
        $value =  null;
        if($arrayType === 'StructProperty')
        {
//        if array_type == "StructProperty":
//            prop_name = self.fstring()
//            prop_type = self.fstring()
//            self.u64()
//            type_name = self.fstring()
//            _id = self.guid()
//            self.skip(1)
//            prop_values = []
//            for _ in range(count):
//                prop_values.append(self.struct_value(type_name, f"{path}.{prop_name}"))
//            value = {
//                "prop_name": prop_name,
//                "prop_type": prop_type,
//                "values": prop_values,
//                "type_name": type_name,
//                "id": _id,
//            }
            
            echo '<xmp>';
            var_dump(__FILE__.':'.__LINE__);
            var_dumP($arrayType);
            var_dump($size);
            echo '</xmp>';
            exit;
        }
        else
        {
            $value = [
                'values' => $this -> arrayValue($bsr, $arrayType, $count, $size)
            ];
        }
        return $value;


    }
    
    private function propertyValue(\Php2Core\IO\Data\BinaryStreamReader $bsr, string $type, ?string $structType)
    {
        echo '<xmp>';
        var_dump(__FILE__.':'.__LINE__);
        var_dump($type);
        var_dump($structType);
        echo '</xmp>';
        
        return null;
    }
    
    /**
     * @param \Php2Core\IO\Data\BinaryStreamReader $bsr
     * @param string $name
     * @param string $typeName
     * @param string $size
     * @return array|null
     */
    private function property(\Php2Core\IO\Data\BinaryStreamReader $bsr, string $name, string $typeName, string $size): ?array
    {
        $value = null;
        switch($typeName)
        {
            case 'MapProperty':
                $keyType = $bsr -> fString();
                $valueType = $bsr -> fString();
                $id = $bsr -> optionalGuid();
                $bsr -> u32();
                $count = $bsr -> u32();
                
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
                    $key = $this -> propertyValue($bsr, $keyType, $keyStructType);
                    $value = $this -> propertyValue($bsr, $valueType, $valueStructType);
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
                $arrayType = $bsr -> fstring();
                
                $value = [
                    'array_type' => $arrayType,
                    'id' => $bsr -> optionalGuid(),
                    'value' => $this -> ArrayProperty($bsr, $arrayType, $size - 4)
                ];
                break;
            case 'NameProperty':
            case 'StrProperty':
                $value = [
                    'id' => $bsr -> optionalGuid(),
                    'value' => $bsr -> fString()
                ];
                break;
            case 'BoolProperty':
                $value = [
                    'value' => $bsr -> bool(),
                    'id' => $bsr -> optionalGuid()
                ];
                break;
            case 'IntProperty':
                $value = [
                    'id' => $bsr -> optionalGuid(),
                    'value' => $bsr -> i32()
                ];
                break;
            case 'FloatProperty':
                $value = [
                    'id' => $bsr -> optionalGuid(),
                    'value' => $bsr -> float()
                ];
                break;
            case 'StructProperty':
                $value = $this -> struct($bsr);
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
//         value = {}
//        if path in self.custom_properties and (
//            path is not nested_caller_path or nested_caller_path == ""
//        ):
//            value = self.custom_properties[path][0](self, type_name, size, path)
//            value["custom_type"] = path
//        elif type_name == "UInt16Property":
//            value = {
//                "id": self.optional_guid(),
//                "value": self.u16(),
//            }
//        elif type_name == "UInt32Property":
//            value = {
//                "id": self.optional_guid(),
//                "value": self.u32(),
//            }
//        elif type_name == "Int64Property":
//            value = {
//                "id": self.optional_guid(),
//                "value": self.i64(),
//            }
//        elif type_name == "FixedPoint64Property":
//            value = {
//                "id": self.optional_guid(),
//                "value": self.i32(),
//            }


//        elif type_name == "EnumProperty":
//            enum_type = self.fstring()
//            _id = self.optional_guid()
//            enum_value = self.fstring()
//            value = {
//                "id": _id,
//                "value": {
//                    "type": enum_type,
//                    "value": enum_value,
//                },
//            }

//        elif type_name == "ByteProperty":
//            enum_type = self.fstring()
//            _id = self.optional_guid()
//            if enum_type == "None":
//                enum_value = self.byte()
//            else:
//                enum_value = self.fstring()
//            value = {
//                "id": _id,
//                "value": {
//                    "type": enum_type,
//                    "value": enum_value,
//                },
//            }


//        else:
//            raise Exception(f"Unknown type: {type_name} ({path})")
//        value["type"] = type_name
//        return value
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
