
<div class="detail">
    <h1><?php echo $detail->talk_title?></h1>

    <p class="info">
        <strong>
            <?php
            $speaker_names = array();
            foreach ($speakers as $speaker): ?>
            <?php
            if (!empty($speaker->speaker_id) && $speaker->status!='pending') {
                if (empty($speaker->full_name)) { $speaker->full_name = 'N/A'; }
                $speaker_link = '<a href="/user/view/'.$speaker->speaker_id.'">'.$speaker->full_name.'</a> ';
                if ($admin) {
                    $speaker_link .= '<a class="btn-small" href="/talk/unlink/'.$speaker->talk_id.'/'.$speaker->speaker_id.'">< unlink</a>';
                }
                $speaker_names[] = $speaker_link;
            } else {
                $speaker_names[] = $speaker->speaker_name;
            }
            ?>
            <?php endforeach; echo implode(', ', $speaker_names); ?>
        </strong> (<?php echo $detail->display_datetime; ?>)
        <br/>
        <?php echo escape($detail->tcid); ?> at <strong><a href="/event/view/<?php echo $detail->event_id; ?>"><?php echo escape($detail->event_name); ?></a></strong> (<?php echo escape($detail->lang_name);?>)
    </p>
                <p class="opts">
                <?php
                /*
                if its set, but the talk was in the past, just show the text "I was there!"
                if its set, but the talk is in the future, show a link for "I'll be there!"
                if its not set show the "I'll be there/I was there" based on time
                */
                if ($attend && user_is_auth()) {
                    if ($detail->date_given<time()) {
                        $link_txt="I attended"; $showt=1;
                    } else { $link_txt="I'm attending"; $showt=2; }
                } else {
                    if ($detail->date_given<time()) {
                        $link_txt="I attended"; $showt=3;
                    } else { $link_txt="I'm attending"; $showt=4; }
                }
                //if they're not logged in, show the questions
                if (!user_is_auth()) { $attend=false; }
                ?>

                    <a class="btn<?php echo $attend ? ' btn-success' : ''; ?>" id="mark-attending" href="javascript:void(0);" onclick="return markAttendingTalk(this,<?php echo $detail->ID?>,<?php echo $detail->date_given<time() ? 'true' : 'false'; ?>);"><?php echo $link_txt?></a>
                </p>

    <p class="rating">
        <?php echo $rstr; ?>
    </p>

    <div class="desc">
        <span align="left"><?php
        if (!empty($speaker_img)) {
            foreach ($speaker_img as $uid => $img) {
                echo '<a href="/user/view/'.$uid.'"><img src="'.$img.'" align="left" border="0" style="margin-right:10px;" height="50" width="50"></a>';
            }
        }
        ?></span>
        <?php echo auto_p(auto_link(escape_allowing_presentation_tags($detail->talk_desc)));?>
    </div>

    <p class="quicklink">
        Quicklink: <strong><a href="<?php echo $this->config->site_url(); ?><?php echo $detail->tid; ?>"><?php echo $this->config->site_url(); ?><?php echo $detail->tid; ?></a></strong>
    <?php
        if ($admin) {
            echo "(<a href=\"http://chart.apis.google.com/chart?chs=400x400&cht=qr&chl=" . urlencode($this->config->site_url() . '/' . $detail->tid) . "\"/> QR code </a>)";
        }
    ?>
    </p>

    <?php if (!empty($track_info)): ?>
    <p class="quicklink">
    <?php
    echo '<b>Track(s):</b> '; foreach ($track_info as $t) { echo $t->track_name; }
    ?>
    </p>
    <?php endif; ?>

    <?php if (!empty($detail->slides_link)): ?>
    <p class="quicklink">
        Slides: <strong><a href="<?php echo $detail->slides_link; ?>"><?php echo $detail->talk_title; ?></a></strong>
    </p>
    <?php endif; ?>

    <div class="clear"></div>
</div>
