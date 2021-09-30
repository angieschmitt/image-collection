<?php

require('./assets/php/database-class.php');

// Get ID
if ( isset($_GET['imgID']) ) {
    $imgID = $_GET['imgID'];
} else {
    header('Location: /');
    exit();
}

// Get Tags
$tags = $db->select('SELECT * FROM tags ORDER BY `tag` ASC', array(), array());
foreach ($tags as $tag) {
    $tagArr[$tag->id] = $tag->tag;
}

// Get Image
$image = $db->select('SELECT i.*,(SELECT GROUP_CONCAT(t.tagID) FROM tag2img t WHERE t.imgID = i.id) AS tagList FROM images i WHERE i.deleted = 0 AND i.id = ?', array($imgID), array('%d'));
$ext = substr($image[0]->filename, strrpos($image[0]->filename, '.')+1 );

if ( !$image ) {
    header('Location: /');
    exit();
}

if ( !empty($image[0]->tagList) ) {
    $tagList = explode(',', $image[0]->tagList);
} else {
    $tagList = '';
}

foreach ($tags as $tag) {
    $tagArrj[] = $tag->tag."||".$tag->id;
}
$jsonTags = json_encode($tagArrj);

$directory = './storage';

// Process new comment
if ( !empty($_POST['name']) && !empty($_POST['comment']) ) {
    $data = array('imgID'=>$imgID,'name'=>$_POST['name'],'comment'=>$_POST['comment'],'createdate'=>date('Y-m-d H:i:s'));
    $types = array('%s','%s','%s','%s');
    $db->insert('comments', $data, $types);
    header('Location: '.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    exit();
}

// Comments
$comments = $db->select('SELECT * FROM comments WHERE imgID = ? ORDER BY createdate ASC ', array($imgID), array('%d'));
if ( !$comments ) {
    $comments = array();
}
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
        <div id="uploadBox">
            <form action="/assets/php/upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>
        </div>
        <div id="image" class="container">
            <div class="item">
                <?php if ( $ext=="webm" || $ext == "mp4" ) :?>
                <video src="<?=$directory."/".$image[0]->filename?>" controls></video>
                <?php else : ?>
                <img src="<?=$directory."/".$image[0]->filename?>" />
                <?php endif; ?>
            </div>
            <div class="data">
                <div id="actions">
                    <a class="addTag" data-imgID="<?=$image[0]->id?>">Add Tag</a>
                    <span class="removeNote">Right click tag to remove</span>
                </div>
                <div id="tagList">
                    <?php foreach ($tagList as $tag) : ?>
                        <a data-tag="<?=$tag?>" href="/?tag=<?=$tag?>"><?=$tagArr[$tag]?></a>
                    <?php endforeach;?>
                    <script type="text/javascript">var tags = <?=$jsonTags?>;</script>
                </div>
                <div id="create_id">
                    Created on: <?=date ("F d Y H:i:s", strtotime($image[0]->createdate)); ?><br/>
                    ID: <?=$image[0]->id?>
                </div>
                <div id="remove">
                    <a class="remove" data-imgID="<?=$image[0]->id?>">Remove Image</a>
                </div>
                <div id="comments">
                    <h3>Comments</h3>
                    <?php foreach ( $comments as $comment ) : ?>
                    <div class="comment">
                        <div class="poster">
                            <?=$comment->name?>
                            <span class="datetime"><?=$comment->createdate?></span>
                        </div>
                        <div class="content"><?=$comment->comment?></div>
                    </div>
                    <?php endforeach; ?>
                    <form id="commentForm" method="post" action="<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>">
                        <input type="text" name="name" placeholder="Name" value="<?=$_POST['name']?>" />
                        <textarea name="comment" placeholder="Comment"><?=$_POST['comment']?></textarea>
                        <input type="submit" name="Submit" value="Post" />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
