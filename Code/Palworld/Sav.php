<?php
namespace Code\Palworld;

class Sav extends \Php2Core\IO\File
{
    /**
     * @param \Php2Core\IO\Directory $directory
     * @return Gvas
     */
    public function decode(\Php2Core\IO\Directory $directory): Gvas
    {
        $gvas = $this -> binaryDecode($this -> read());
        
        $file = Gvas::fromDirectory($directory, $this -> basename().'.gvas');
        $file -> write(serialize($gvas));

        return $file;
    }
    
    /**
     * @param string $bytes
     * @return array
     * @throws \Php2Core\Exceptions\NotImplementedException
     */
    private function binaryDecode(string $bytes): array
    {
        $uncompressedLength = unpack('v', substr($bytes, 0, 4))[1];
        $compressedLength = unpack('v', substr($bytes, 4, 4))[1];
        $magicBytes = substr($bytes, 8, 3);
        $saveType = substr($bytes, 11, 1);

        $startOffset = 12;
        
        if($magicBytes === 'CNK')
        {
            $uncompressedLength = unpack('v', substr($bytes, 12, 4))[1];
            $compressedLength = unpack('v', substr($bytes, 16, 4))[1];
            $magicBytes = substr($bytes, 20, 3);
            $saveType = substr($bytes, 23, 1);
            $startOffset = 24;
        }
        
        if(!in_array($saveType, ['0', '1', '2']))
        {
            throw new \Php2Core\Exceptions\NotImplementedException('unknown save type: '.$saveType);
        }

        if(!in_array($saveType, ['1', '2']))
        {
            throw new \Php2Core\Exceptions\NotImplementedException('unhandled compression type: '.$saveType);
        }

        if($saveType === '1' && $compressedLength !== strlen($bytes) - $startOffset)
        {
            throw new \Php2Core\Exceptions\NotImplementedException('incorrect compressed length: '.$compressedLength);
        }
        
        $uncompressedData = zlib_decode(substr($bytes, $startOffset, $compressedLength));
        if($saveType === '2')
        {
            if($compressedLength !== strlen($uncompressedData))
            {
                throw new \Php2Core\Exceptions\NotImplementedException('incorrect compressed length: '.$compressedLength);
            }
            $uncompressedData = zlib_decode($uncompressedData);
        }
        
        if($uncompressedLength !== strlen($uncompressedData))
        {
            throw new \Php2Core\Exceptions\NotImplementedException('incorrect uncompressed length: '.$uncompressedLength);
        }

        return ['type' => $saveType, 'data' => $uncompressedData];
    }
}