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

//        properties = {}
//        while True:
//            name = self.fstring()
//            if name == "None":
//                break
//            type_name = self.fstring()
//            size = self.u64()
//            properties[name] = self.property(type_name, size, f"{path}.{name}")
//        return properties
    }
    
    private function struct(\Php2Core\IO\Data\BinaryStreamReader $bsr)
    {
        $structType = $bsr -> fString();
        
        return [
            'struct_type' => $structType,
            'struct_id' => $bsr -> guid(),
            'id' => $bsr -> optionalGuid(),
            'value' => $this -> structValue($bsr, $structType)
        ];
//        struct_type = self.fstring()
//        struct_id = self.guid()
//        _id = self.optional_guid()
//        value = self.struct_value(struct_type, path)
//        return {
//            "struct_type": struct_type,
//            "struct_id": struct_id,
//            "id": _id,
//            "value": value,
//        }
    }
    
    private function structValue(\Php2Core\IO\Data\BinaryStreamReader $bsr, string $structType)
    {
        switch($structType)
        {
            case 'Vector':
            case 'DateTime':
            case 'Guid':
            case 'Quat':
            case 'LinearColor':
                var_dump($structType);
                break;
            default:
                return $this -> propertiesUntilEnd($bsr);
                break;
        }
//        if struct_type == "Vector":
//            return self.vector_dict()
//        elif struct_type == "DateTime":
//            return self.u64()
//        elif struct_type == "Guid":
//            return self.guid()
//        elif struct_type == "Quat":
//            return self.quat_dict()
//        elif struct_type == "LinearColor":
//            return {
//                "r": self.float(),
//                "g": self.float(),
//                "b": self.float(),
//                "a": self.float(),
//            }
//        else:
//            if self.debug:
//                print(f"Assuming struct type: {struct_type} ({path})")
//            return self.properties_until_end(path)
    }
    
    private function property(\Php2Core\IO\Data\BinaryStreamReader $bsr, string $name, string $typeName, string $size): ?array
    {
        $value = null;
        switch($typeName)
        {
            case 'StrProperty':
                $value = [
                    'value' => $bsr -> bool(),
                    'id' => $bsr -> fString()
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

//        elif type_name == "NameProperty":
//            value = {
//                "id": self.optional_guid(),
//                "value": self.fstring(),
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
//        elif type_name == "ArrayProperty":
//            array_type = self.fstring()
//            value = {
//                "array_type": array_type,
//                "id": self.optional_guid(),
//                "value": self.array_property(array_type, size - 4, path),
//            }
//        elif type_name == "MapProperty":
//            key_type = self.fstring()
//            value_type = self.fstring()
//            _id = self.optional_guid()
//            self.u32()
//            count = self.u32()
//            key_path = path + ".Key"
//            if key_type == "StructProperty":
//                key_struct_type = self.get_type_or(key_path, "Guid")
//            else:
//                key_struct_type = None
//            value_path = path + ".Value"
//            if value_type == "StructProperty":
//                value_struct_type = self.get_type_or(value_path, "StructProperty")
//            else:
//                value_struct_type = None
//            values: list[dict[str, Any]] = []
//            for _ in range(count):
//                key = self.prop_value(key_type, key_struct_type, key_path)
//                value = self.prop_value(value_type, value_struct_type, value_path)
//                values.append(
//                    {
//                        "key": key,
//                        "value": value,
//                    }
//                )
//            value = {
//                "key_type": key_type,
//                "value_type": value_type,
//                "key_struct_type": key_struct_type,
//                "value_struct_type": value_struct_type,
//                "id": _id,
//                "value": values,
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