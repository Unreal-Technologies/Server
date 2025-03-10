<?php
const SERVER_SEVENDAYSTODIE = '7 Days To Die';
const SERVER_MINECRAFT = 'Minecraft';
const SERVER_PALWORLD = 'Palworld';

/**
 * @param int $seconds
 * @return string
 */
function secondsToDisplay(int $seconds): string
{
    $hours = floor($seconds / 3600);
    $seconds -= $hours * 3600;
    $minutes = floor($seconds / 60);
    $seconds -= $minutes * 60;

    $days = floor($hours / 24);
    $hours -= $days * 24;

    return $days .
        'd ' .
        str_pad($hours, 2, '0', 0) .
        ':' .
        str_pad($minutes, 2, '0', 0) .
        ':' .
        str_pad($seconds, 2, '0', 0);
}

/**
* @param string $creationDate
* @return int
*/
function calculateUpTime(string $creationDate): int
{
    $y = substr($creationDate, 0, 4);
    $M = substr($creationDate, 4, 2);
    $d = substr($creationDate, 6, 2);
    $h = substr($creationDate, 8, 2);
    $m = substr($creationDate, 10, 2);
    $s = substr($creationDate, 12, 9);

    $start = new \DateTime($y . '-' . $M . '-' . $d . ' ' . $h . ':' . $m . ':' . $s);
    $now = new \DateTime();

    return $now -> format('U') - $start -> format('U');
}

XHTML -> get('body', function(Php2Core\NoHTML\Xhtml $body)
{
    $processes = \Php2Core\IO\Process::list();
    $serversInstances = [
        SERVER_SEVENDAYSTODIE => [
            'call' => null, 
            'processes' => ['7daystodieserver.exe', '7daystodie.exe']
        ],
        SERVER_MINECRAFT => [
            'call' => null, 
            'processes' => ['javaw.exe', 'java.exe']
        ],
        SERVER_PALWORLD => [
//            'call' => function(\Php2Core\NoHTML\Xhtml $div)
//            {
//                $url = Php2Core::baseUrl().'/Palworld/Players?mode=xhr';
//        
//                $div -> add('span/xmp@#palworld-players');
//                $div -> add('script', function(\Php2Core\NoHTML\Xhtml $script)
//                {
//                    $script -> attributes() -> set('type', 'text/javascript');
//                }) -> text('function getPalWorldPlayers()'
//                    . '{'
//                        . 'Xhr.get(\''.$url.'\', function(data)'
//                        . '{'
//                            . 'document.getElementById(\'palworld-players\').innerHTML = data;'
//                            . 'setTimeout(function()'
//                            . '{'
//                                . 'getPalWorldPlayers()'
//                            . '}, 5 * 1000);'
//                        . '},'
//                        . 'function(data)'
//                        . '{'
//                            . 'alert(data)'
//                        . '});'
//                    . '}'
//                    . 'getPalWorldPlayers();'
//                );
//            }, 
            'call' => null,
            'processes' => ['palserver-win64-shipping-cmd.exe', 'palworld.exe']
        ]
    ];
    
    $ram = \Php2Core\IO\Memory::fromInt(
        (new Php2Core\Collections\Linq(Php2Core\IO\Server::ram()))
        -> select(function (\Php2Core\IO\Memory $x) 
        {
            return $x -> value();
        })
        -> sum(function (int $x) 
        {
            return $x;
        })
        -> firstOrDefault()
    );
 
    $active = (new Php2Core\Collections\Linq($processes))
    -> toArray(function ($x) use ($serversInstances) 
    {
        foreach ($serversInstances as $v) {
            if (in_array(strtolower($x -> name()), $v['processes'])) {
                return true;
            }
            else
            {
                foreach($v['processes'] as $processName)
                {
                    $processedName = str_replace('/', '\\/', preg_quote($x -> name()));
                    if(preg_match('/'.$processedName.'/i',  preg_quote($processName)) !== 0 || stristr($processedName, preg_quote($processName)))
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    });
     
    $buffer = [];
    foreach ($active as $process)
    {
        $instance = null;
        foreach ($serversInstances as $k => $v) 
        {
            if (in_array(strtolower($process -> name()), $v)) 
            {
                $instance = $k;
            }
            else
            {
                foreach($v['processes'] as $processName)
                {
                    $processedName = str_replace('/', '\\/', preg_quote($process -> name()));

                    if(preg_match('/'.$processedName.'/i',  preg_quote($processName)) !== 0 || stristr($processedName, preg_quote($processName)))
                    {
                        $instance = $k;
                        break;
                    }
                }
            }
        }
        
        $isValidatedInstance = false;
        $selectedInfo = null;

        foreach ($process -> pidList() as $pid) 
        {
            $info = $process -> pidInfo($pid);
            
            if (
                $instance === SERVER_MINECRAFT &&
                (
                    strpos($info -> get('CommandLine'), 'minecraft') !== false || 
                    strpos($info -> get('CommandLine'), 'neoforged\\neoforge') !== false
                )
            ) {
                $isValidatedInstance = true;
                $selectedInfo = $info;
            } elseif ($instance === SERVER_SEVENDAYSTODIE || $instance === SERVER_PALWORLD) {
                $isValidatedInstance = true;
                $selectedInfo = $info;
            }
        }

        if ($isValidatedInstance) 
        {
            $buffer[$instance] = [$process, $selectedInfo];
        }
    }
    
    $body -> get('div@.section/h6', function(\Php2Core\NoHTML\Xhtml $h6)
    {
        $h6 -> clear();
        $h6 -> text('Home');
    });
    
    $body -> add('div@.container/div@.row/div@.col s6 offset-s3', function(\Php2Core\NoHTML\Xhtml $col) use($serversInstances, $buffer, $ram)
    {
        $col -> add('table@.striped', function(\Php2Core\NoHTML\Xhtml $table) use($serversInstances, $buffer, $ram)
        {
            $table -> add('tr', function(\Php2Core\NoHTML\Xhtml $tr) use($ram)
            {
                $tr -> add('th@.center-align&colspan=2') -> text('Game Servers');
                $tr -> add('th@.center-align&colspan=2') -> text('Memory '.$ram -> format(0));
            });

            $table -> add('tr', function(\Php2Core\NoHTML\Xhtml $tr)
            {
                $tr -> add('th') -> text('Server');
                $tr -> add('th') -> text('State');
                $tr -> add('th') -> text('Usage');
                $tr -> add('th') -> text('%');
                $tr -> add('th') -> text('PID');
                $tr -> add('th') -> text('Uptime');
            });
            
            $serversInstances[SERVER_PALWORLD]['call']($table -> parent()); //Temp

            foreach (array_keys($serversInstances) as $instance)
            {
                $table -> add('tr', function(\Php2Core\NoHTML\Xhtml $tr) use($instance, $buffer, $ram, $serversInstances, $table)
                {
                    $isActive = isset($buffer[$instance]);
                    
                    $pid = $isActive && $buffer[$instance][1] !== null ? $buffer[$instance][1] -> get('ProcessId') : 'N/A';
                    $memory = $isActive && $pid !== 'N/A' ? $buffer[$instance][0] -> pidMemory($pid, true) : 'N/A';
                    $creationDate = $isActive && $buffer[$instance][1] !== null ? $buffer[$instance][1] -> get('CreationDate') : 'N/A';

                    $uptime = $isActive && $creationDate !== 'N/A' ? secondsToDisplay(calculateUpTime($creationDate)) : 'N/A';
                    $memoryPerc = $isActive && $pid !== 'N/A' ?
                        number_format(
                            ($buffer[$instance][0] -> pidMemory($pid) / $ram -> value()) * 100,
                            2,
                            ',',
                            '.'
                        ) . ' %' :
                        'N/A';

                    if($isActive && $serversInstances[$instance]['call'] !== null)
                    {
                        $serversInstances[$instance]['call']($table -> parent());
                    }
                    
                    $tr -> add('td') -> text($instance);
                    $tr -> add('td/span@.'.($isActive ? 'green' : 'red')) -> text($isActive ? 'Online' : 'Offline');
                    $tr -> add('td@.right-align') -> text($memory);
                    $tr -> add('td@.right-align') -> text($memoryPerc);
                    $tr -> add('td@.right-align') -> text($pid);
                    $tr -> add('td@.right-align') -> text($uptime);
                });
            }
        });
    });
});
