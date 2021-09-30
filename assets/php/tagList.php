<?php

require('./database-class.php');

$tags = $db->select('SELECT * FROM tags ORDER BY `tag` ASC', array(), array());
foreach ($tags as $tag) {
    $tagArr[$tag->id] = $tag->tag;
}
foreach ($tags as $tag) {
    $tagArrj[] = $tag->tag."||".$tag->id;
}
$jsonTags = json_encode($tagArrj);

if ( isset($_GET['imgID']) ) {
    $image = $db->select('SELECT i.*,(SELECT GROUP_CONCAT(t.tagID) FROM tag2img t WHERE t.imgID = i.id) AS tagList FROM images i WHERE i.deleted = 0 AND i.id = ?', array($_GET['imgID']), array('%d'));

    if ( !empty($image[0]->tagList) ) {
        $tagList = explode(',', $image[0]->tagList);
    } else {
        $tagList = '';
    }
    
    foreach ($tagList as $tag) :
        ?>
<a href="/?tag=<?=$tag?>"><?=$tagArr[$tag]?></a>
        <?php
    endforeach;
    ?>
<script type="text/javascript">var tags = <?=$jsonTags?>;</script>
    <?php
} else {
    ?>
<a href="./" class="item">Clear Tag</a>
    <?php
    foreach ( $tags as $tag ) :
        ?>
<a href="?tag=<?=$tag->id?>" class="item<?=($selected_tag==$tag->id ? ' active' : '' )?>"><?=$tag->tag?></a>
        <?php
        $a++;
    endforeach;
    ?>
<script type="text/javascript">var tags = <?=$jsonTags?>;</script>
    <?php
}