<div class="box">
<?php
if(!$detail->allow_comments) {
	$this->load->view('msg_info', array('msg' => 'Comments closed.'));
	$comment_closed=true;
}
if (empty($comments)) {
?>
<?php $this->load->view('msg_info', array('msg' => 'No comments yet.')); ?>
<?php

} else {
	echo '<h2 id="comments">Comments</h2>';
	
    foreach ($comments as $k => $v) {
	
		//print_r($v);
	
        if ($v->private && !$admin){ 
            continue; 
        }
    
        if (isset($v->user_id) && $v->user_id != 0){ 
    		$uname = '<a href="/user/view/'.$v->user_id.'">'.escape($v->uname).'</a> ';
    	}else{ 
    		$uname = '<span class="anonymous">Anonymous</span>'; 
    	}

    	$class = '';

    	if ($v->user_id == 0) {
    	    $class .= ' row-talk-comment-anonymous';
    	}

        if ($v->private == 1) {
    	    $class .= ' row-talk-comment-private';
    	}
    	
    	if (isset($claimed[0]->userid) && $claimed[0]->userid != 0 && isset($v->user_id) && $v->user_id == $claimed[0]->userid) {
    	    $class .= ' row-talk-comment-speaker';
    	}

?>
<div id="comment-<?php echo $v->ID ?>" class="row row-talk-comment<?php echo $class?>">
	<div class="img">
	<?php if (isset($claimed[0]->userid) && $claimed[0]->userid != 0 && isset($v->user_id) && $v->user_id == $claimed[0]->userid): ?>
		<span class="speaker">Speaker comment:</span>
	<?php else: ?>
		<?php echo rating_image($v->rating); ?><br/>
		<?php if(!empty($v->twitter_username)): ?>
		<a href="http://twitter.com/<?php echo $v->twitter_username; ?>"><img src="/inc/img/twitter_share_icon.gif" style="margin-top:10px" width="20"/></a>
		<?php endif; ?>
		<?php if(!empty($v->gravatar)){ 
			echo '<a href="/user/view/'.$v->user_id.'">'.str_replace('/>','height="45" align="right" style="margin:10px"/>',$v->gravatar).'</a>'; } 
		?>
	<?php endif; ?>
	</div>
	<div class="text">
    	<p class="info">
    		<strong><?php echo date('M j, Y, H:i',$v->date_made); ?></strong> by <strong><?php echo $uname; ?></strong>
    	<?php if ($v->private == 1): ?>
    		<span class="private">Private</span>
    	<?php endif; ?>
    	</p>
    	<div class="desc">
    		<?php echo auto_p(escape($v->comment)); ?>
    	</div>
		<p class="admin">
			<?php if (user_is_admin() || $v->user_id==$user_id): ?>
				<a class="btn-small edit-talk-comment-btn" href="#" id="<?php echo $v->ID; ?>">Edit</a>
			<?php endif; ?>
			<?php if (user_is_admin()): ?>
				<a class="btn-small" href="#" onClick="delTalkComment(<?php echo $v->ID?>);return false;">Delete</a>
			<?php endif; ?>
			<?php if (
				(isset($claimed[0]->userid) && $claimed[0]->userid != 0 && isset($v->user_id) && $v->user_id == $claimed[0]->userid) || $admin): ?>
				<a class="btn-small" href="#" onClick="commentIsSpam(<?php echo $v->ID?>,'talk');return false;">Is Spam</a>
			<?php endif; ?>
		</p>
		<?php if (user_is_admin()): ?>
		<p class="admin">
			
		</p>
		<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>
<?php
    }
}
?>
</div>
