<?php
/**
 * @param \Php2Core\IO\Directory $dir
 * @return array
 */
function tree(\Php2Core\IO\Directory $dir, int $depth = 1, array $parentTags = []): array
{
    $buffer = [];
    $width = 0;
    
    $list = $dir -> list();
    $count = count($list);
    foreach($list as $idx => $entry)
    {
        $isLast = $idx === $count - 1;
        
        if($entry instanceof \Php2Core\IO\Directory)
        {
            $tags = [];
            foreach($parentTags as $tag)
            {
                $tags[] = $tag === 'down-right' ? 'down' : null;
            }
            $tags[] = $isLast ? 'right' : 'down-right';
            
            $subtree = tree($entry, $depth + 1, $tags);
            
            if($width < $subtree['width'])
            {
                $width = $subtree['width'];
            }
            
            $buffer[$entry -> name()] = [
                'text' => $entry -> name(),
                'last' => $isLast,
                'children' => $subtree['content'],
                'tags' => $tags,
                'type' => 'directory',
                'path' => $entry -> path()
            ];
            
            if(count($tags) > $width)
            {
                $width = count($tags);
            }
                    
            continue;
        }
        
        $tags = [];
        foreach($parentTags as $tag)
        {
            $tags[] = $tag === 'down-right' || $tag === 'down' ? 'down' : null;
        }
        $tags[] = $isLast ? 'right' : 'down-right';
        
        if(count($tags) > $width)
        {
            $width = count($tags);
        }
        
        $buffer[$entry -> name()] = [
            'text' => $entry -> name(),
            'last' => $isLast,
            'children' => [],
            'tags' => $tags,
            'type' => 'file',
            'path' => $entry -> path()
        ];
    }
    
    return [ 'content' => $buffer, 'width' => $width ];
}

function treeRecursion(array $tree, Closure $callback)
{
    foreach($tree as $entry)
    {
        $children = $entry['children'];
        unset($entry['children']);
        $callback($entry);
        
        treeRecursion($children, $callback);
    }
}

XHTML -> get('body', function(Php2Core\GUI\NoHTML\Xhtml $body)
{
    //Set Title
    $body -> get('div@.section/h6', function(\Php2Core\GUI\NoHTML\Xhtml $h6)
    {
        $h6 -> clear();
        $h6 -> text('Downloads');
    }); 
    
    //Read Downloads folder
    $downloads = \Php2Core\IO\Directory::fromString('Assets/Downloads');
    if(!$downloads -> exists())
    {
        $downloads -> create();
    }
    
    //Craft Tree
    $tree = tree($downloads);
    
    $body -> add('div@.container/div@.row/div@.col s6 offset-s3', function(\Php2Core\GUI\NoHTML\Xhtml $col) use($tree)
    {
        $col -> add('table@#directory-render', function(\Php2Core\GUI\NoHTML\Xhtml $table) use($tree)
        {
            $width = $tree['width'];
            $table -> add('tr/th@colspan='.($width + 1)) -> text('Downloads');

            treeRecursion($tree['content'], function(array $data) use($table, $width)
            {
                $table -> add('tr', function(\Php2Core\GUI\NoHTML\Xhtml $tr) use($data, $width)
                {
                    foreach($data['tags'] as $tag)
                    {
                        $tr -> add('td'.($tag === null ? null : '@.'.$tag));
                    }
                    
                    $colspan = ($width + 1) - count($data['tags']);
                    if($data['type'] === 'file')
                    {
                        $tr -> add('td@colspan='.$colspan.'/a@target=_blank', function(\Php2Core\GUI\NoHTML\Xhtml $a) use($data)
                        {
                            $path = $data['path'];
                            $relative = PHP2CORE -> physicalToRelativePath($path);

                            $a -> attributes() -> set('href', $relative);

                        }) -> text($data['text']);
                    }
                    else
                    {
                        $tr -> add('td@colspan='.$colspan) -> text($data['text']);
                    }
                });
            });
        });
    });
});
