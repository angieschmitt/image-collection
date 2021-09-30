<?php
    require('./database-class.php');

    $check = $db->select('SELECT * FROM tags WHERE tag COLLATE UTF8_GENERAL_CI LIKE ?', array("%".strtolower($_GET['tag'])."%"), array('%s'));
    if( $check ){

        $tagID = $check[0]->id;
        $check = $db->select('SELECT * FROM tag2img WHERE tagID = ? AND imgID = ?', array($tagID,$_GET['imgID']), array('%s','%s'));
        if( !$check ){
            $data = array('imgID'=>$_GET['imgID'],'tagID'=>$tagID,'createdate'=>date('Y-m-d H:i:s',filemtime($directory."/".$file)));
            $types = array('%s','%s','%s');
            $db->insert('tag2img',$data,$types);
            echo "Added tag.";
        } else{
            echo "Already has tag.";
        }

    } else {

        $data = array('tag'=>$_GET['tag'],'createdate'=>date('Y-m-d H:i:s',filemtime($directory."/".$file)));
        $types = array('%s','%s');
        $tagID = $db->insert('tags',$data,$types);

        $data = array('imgID'=>$_GET['imgID'],'tagID'=>$tagID,'createdate'=>date('Y-m-d H:i:s',filemtime($directory."/".$file)));
        $types = array('%s','%s','%s');
        $db->insert('tag2img',$data,$types);
        echo "Created and added tag.";

    }

exit();
