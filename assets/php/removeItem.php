<?php

    require('./database-class.php');

    $data = array('deleted' => 1);
    $where = array('id' => $_GET['imgID']);
    
    $format = array('%s', '%s');

    $db->update('images', $data, $format, $where, array() );
