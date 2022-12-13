<?php

// Fuente: https://gist.github.com/mdoelker/9269378

// Strips BOM from all php files in the current dir and subdirs
$files = stripUtf8BomFromFiles(".", "*.php");

/**
* Remove BOM from a single file
*
* @param string $filename
* @return bool
*/
function stripUtf8BomFromFile($filename) {
    $bom = pack('CCC', 0xEF, 0xBB, 0xBF);
    $file = @fopen($filename, "r");
    $hasBOM = fread($file, 3) === $bom;
    fclose($file);
 
    if ($hasBOM) {
        $contents = file_get_contents($filename);
        file_put_contents($filename, substr($contents, 3));
    }
 
    return $hasBOM;
}