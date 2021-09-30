<?php
    require('./database-class.php');

    $check = $db->select('SELECT * FROM tag2img WHERE tagID = ? AND imgID = ?', array($_GET['tagID'],$_GET['imgID']), array('%s','%s'));
    if( !$check ){

        $data = array('imgID'=>$_GET['imgID'],'tagID'=>$_GET['tagID'],'createdate'=>date('Y-m-d H:i:s',filemtime($directory."/".$file)));
        $types = array('%s','%s','%s');
        $db->insert('tag2img',$data,$types);

        echo "Added tag.";

    } else {

        echo "Already has tag.";

    }

exit();
