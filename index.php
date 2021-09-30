<?php

require('./assets/php/database-class.php');

if ( isset($_GET['tag']) ) {
    if ( strpos($_GET['tag'], ',') === false ) {
        $selected_tag[] = $_GET['tag'];
    } else {
        $selected_tag = explode(',', $_GET['tag']);
    }
} else {
    $selected_tag = array();
}

$failed = false;
if ( isset($_GET['failed']) ) {
    $failed = true;
}

$tags = $db->select('SELECT * FROM tags ORDER BY `tag` ASC', array(), array() );
foreach ($tags as $tag) {
    $tagArr[$tag->id] = $tag->tag;
}
foreach ($tags as $tag) {
    $tagArrj[] = $tag->tag."||".$tag->id;
}
$jsonTags = json_encode($tagArrj);

if ( !empty($selected_tag) ) {
    foreach ( $selected_tag as $key => $tag ) {
        if ( $key == 0 ) {
            $search = "HAVING FIND_IN_SET( '".$tag."', tags)";
        } else {
            if ( $tag != "" ) {
                $search .= " AND FIND_IN_SET( '".$tag."', tags)";
            }
        }
    }
    $images = $db->select("SELECT t2i.imgID, GROUP_CONCAT(t2i.tagID ORDER BY t2i.tagID) as tags, i.* from tag2img as t2i LEFT JOIN images as i ON t2i.imgID = i.id WHERE t2i.approved = ? AND deleted = ? GROUP BY t2i.imgID ".$search, array('1','0'), array('%d','%d'));
    $tags = $db->select('SELECT GROUP_CONCAT(t2i.tagID ORDER BY t2i.tagID) as tags from tag2img as t2i LEFT JOIN images as i ON t2i.imgID = i.id WHERE t2i.approved = 1 AND deleted = 0 GROUP BY t2i.imgID '.$search, array(), array() );
    $subTagsOut = array();
    foreach ($tags as $tag) {
        $tagArray = explode(',', $tag->tags);
        foreach ( $tagArray as $subTag ) {
            if (!in_array($subTag, $subTagsOut)) {
                $subTagsOut[$subTag] = $tagArr[$subTag];
            }
        }
    }
    asort($subTagsOut);
    $tagArr = $subTagsOut;
} else {
    $images = $db->select('SELECT i.* FROM images i WHERE i.deleted = ? ORDER BY RAND() LIMIT 48', array(0), array('%d'));
}

$directory = './storage';
?>
<html>
<head>
    <title>Image Labeler</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="./js/jQuery.js"></script>
    <script type="text/javascript" src="./js/scripts.js"></script>
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>
<body>
    <div id="navigation">
        <a href="/projects/images/">Home</a>
        <a href="./tagless.php">Tagless</a>
        <a class="upload">Upload Image</a>
    </div>
    <div id="uploadBox" class="<?=( $failed ? 'failed' : '' )?>" >
        <div id="failed_notice">Upload failed</div>
        <form action="/assets/php/upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </div>
    <div id="content">
        <div id="tagList">
        <div class="item">
            <a href="./">Clear Tags</a>
        </div>
    <?php foreach ( $tagArr as $tag => $name ) : ?>
        <div class="item<?=( in_array($tag, $selected_tag) ? ' active' : '' )?>">
            <a href="?tag=<?=$tag?>"><?=$name?></a>
            <?php if ( !in_array($tag, $selected_tag) && !empty($selected_tag) ) : ?>
            <a class="add" href="?<?=$_SERVER['QUERY_STRING'].",".$tag?>">+</a>
            <?php elseif (!empty($selected_tag) ) :
                $tempTags = $selected_tag;
                if (($key = array_search($tag, $tempTags)) !== false) {
                    unset($tempTags[$key]);
                }
                $url = implode(',', $tempTags);
                if ( $url != "" ) {
                    $url = '?tag='.$url;
                } else {
                    $url = "/";
                }
                ?>
            <a class="remove" href="<?=$url?>">-</a>
            <?php endif; ?>
        </div>
        <?php
        $a++;
    endforeach;
    ?>
        <script type="text/javascript">var tags = <?=$jsonTags?>;</script>
    </div>
        <div id="imageList">
    <?php if ( !empty($images) ) : ?>
        <?php foreach ( $images as $image ) :
            $ext = substr($image->filename, strrpos($image->filename, '.')+1 );
            ?>
        <div class="item">
            <div class="actions">
                <a class="addTag" data-imgID="<?=$image->id?>">Add Tag</a>
                <!-- <a href="#copy">Copy Image URL</a> -->
            </div>
            <?php if ( $ext=="webm" || $ext == "mp4" ) :?>
            <video src="<?=$directory."/".$image->filename?>" muted controls></video>
            <?php else : ?>
            <a href="image.php?imgID=<?=$image->id?>"><img src="<?=$directory."/".$image->filename?>" /></a>
            <?php endif; ?>
            <div class="data">
                <a href="image.php?imgID=<?=$image->id?>">View</a>
                <!-- Created on: <?=date ("F d Y H:i:s", strtotime($image->createdate)); ?><br/> -->
                <!-- ID: <?=$image->id?> -->
            </div>
        </div>
            <?php
            $a++;
        endforeach;
        ?>
    <?php else : ?>
        <div class="no_images">No images matching these tags</div>
    <?php endif; ?>
    </div>
    </div>
</body>
</html
