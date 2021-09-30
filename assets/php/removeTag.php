<?php

    require('./database-class.php');

    $check = $db->select('SELECT * FROM tag2img WHERE tagID = ? AND imgID = ?', array($_GET['tagID'],$_GET['imgID']), array('%s','%s'));
    if( $check ){ $db->delete('tag2img',$check[0]->id); }
