<?php

require('./assets/php/database-class.php');

$directory = './storage';
$files = array_diff(scandir($directory), array('..', '.'));

foreach ( $files as $file ) :
    $check = $db->select('SELECT * FROM images WHERE filename = ?', array($file), array('%s'));
    if ( !$check ) {
        $data = array('filename'=>$file,'createdate'=>date('Y-m-d H:i:s', filemtime($directory."/".$file)));
        $types = array('%s','%s');
        echo "Inserted $file into DB";
        $db->insert('images', $data, $types);
        echo "<hr/>";
    }
endforeach;
