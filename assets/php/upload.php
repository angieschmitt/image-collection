<?php

    require('./database-class.php');

    $target_dir = "../../storage/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    $name = $_FILES['fileToUpload']['name'];
    $date = date('Y-m-d H:i:s');

    if (!file_exists($target_file)){

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $data = array('filename'=>$name,'createdate'=>$date);
            $types = array('%s','%s');
            $id = $db->insert('images',$data,$types);
            header('Location: /image.php?imgID='.$id );
            exit();
        } else {
            header('Location: /?failed' );
            exit();
        }

    } else {

        for($a=0;$a<100;$a++){
            $ext = substr($name, strrpos($name,'.') ,strlen($name));
            $name_new = substr($name, 0, strrpos($name,'.'));
            $full_name = $name_new."_".$a.$ext;
            $target_file = '../../storage/'.$full_name;
            if (!file_exists('storage/'.$full_name)){
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $data = array('filename'=>$full_name,'createdate'=>$date);
                    $types = array('%s','%s');
                    $id = $db->insert('images',$data,$types);
                    header('Location: /image.php?imgID='.$id );
                    exit();
                } else {
                    header('Location: /?failed' );
                    exit();
                }
                break;
            }
        }
        
    }
