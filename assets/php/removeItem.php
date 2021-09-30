<?php

    require('./database-class.php');

    $data = array('deleted'=>1);
    $types = array('%s','%s');

    $where = array('id'=>$_GET['imgID']);

    $db->update('images', $data, $types, $where, 'and');
