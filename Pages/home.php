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
        SERVER_SEVENDAYSTODIE => ['7daystodieserver.exe', '7daystodie.exe'],
        SERVER_MINECRAFT => ['javaw.exe', 'java.exe'],
        SERVER_PALWORLD => ['palserver-win64-shipping-cmd.exe']
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
            if (in_array(strtolower($x -> name()), $v)) {
                return true;
            }
            else
            {
                foreach($v as $processName)
                {
                    if(preg_match('/^'.$x -> name().'/i',  $processName))
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
                foreach($v as $processName)
                {
                    if(preg_match('/^'.$process -> name().'/i',  $processName))
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

            foreach (array_keys($serversInstances) as $instance)
            {
                $table -> add('tr', function(\Php2Core\NoHTML\Xhtml $tr) use($instance, $buffer, $ram)
                {
                    $isActive = isset($buffer[$instance]);
                    $pid = $isActive ? $buffer[$instance][1] -> get('ProcessId') : 'N/A';
                    $memory = $isActive ? $buffer[$instance][0] -> pidMemory($pid, true) : 'N/A';
                    $creationDate = $isActive ? $buffer[$instance][1] -> get('CreationDate') : 'N/A';

                    $uptime = $isActive ? secondsToDisplay(calculateUpTime($creationDate)) : 'N/A';
                    $memoryPerc = $isActive ?
                        number_format(
                            ($buffer[$instance][0] -> pidMemory($pid) / $ram -> value()) * 100,
                            2,
                            ',',
                            '.'
                        ) . ' %' :
                        'N/A';

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
