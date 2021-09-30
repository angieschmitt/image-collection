<?php

    require('../php/database-class.php');

    //if( $_POST['acc_dec'] ){
    if( $_GET['url'] ){

        // if( $_POST['acc_dec'] == "Accept" ){

            // $name = substr($_POST['url'], strrpos($_POST['url'],'/')+1 ,strlen($_POST['url']));
            $name = substr($_GET['url'], strrpos($_GET['url'],'/')+1 ,strlen($_GET['url']));
            $date = date('Y-m-d H:i:s');

            if (!file_exists('../../storage/'.$name)){
                // file_put_contents("../../storage/".$name, fopen($_POST['url'], 'r'));
                file_put_contents("../../storage/".$name, fopen($_GET['url'], 'r'));
                $data = array('filename'=>$name,'createdate'=>$date);
    			$types = array('%s','%s');
    			$db->insert('images',$data,$types);
            } else {
                for($a=0;$a<100;$a++){
                    $ext = substr($name, strrpos($name,'.') ,strlen($name));
                    $name_new = substr($name, 0, strrpos($name,'.'));
                    $full_name = $name_new."_".$a.$ext;
                    if (!file_exists('../../storage/'.$full_name)){
                        // file_put_contents("../../storage/".$full_name, fopen($_POST['url'], 'r'));
                        file_put_contents("../../storage/".$full_name, fopen($_GET['url'], 'r'));
                        $data = array('filename'=>$name,'createdate'=>$date);
            			$types = array('%s','%s');
            			$db->insert('images',$data,$types);
                        break;
                    }
                }
            }

            echo "<h2>Downloaded</h2>";

        // }

    } else {

?>
<style type="text/css">
    form{ display: flex; flex-direction: row; justify-content: space-around; margin: 10px 0; }
    form input{ width: 45%; background-color: #1abc9c; border: none; padding: 5px; font-weight: bold; color: #000; text-transform: uppercase; }
    form input:hover{ background-color: #000; color: #1abc9c; }
    img{ max-width: 100%; max-height: 200px; }
</style>
<div style="text-align: center;">
    <img src="<?=$_GET['url']?>" />
    <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
        <input type="hidden" name="url" value="<?=$_GET['url']?>" />
        <input type="submit" name="acc_dec" value="Accept" />
        <input type="submit" name="acc_dec" value="Decline" />
    </form>
</div>
<?php
    }
?>
