<?php

function buddy_social_button_activity_filter() {

    $activity_type = bp_get_activity_type();
    $activity_link = bp_get_activity_thread_permalink();
    $activity_title = bp_get_activity_feed_item_title();
    $plugin_path = plugins_url();

    $buddy_social_facebook = '<a class="new-window social foundicon-facebook" href="https://www.facebook.com/sharer/sharer.php?t='.$activity_title.'&u=' . $activity_link . '" rel="facebox"></a>';

    $buddy_social_twitter = '<a class="new-window social foundicon-twitter" href="http://twitter.com/share?text='.$activity_title.'&url=' . $activity_link . '" rel="twitter"></a>';

    $buddy_social_google = '<a class="new-window social foundicon-google-plus" href="https://plus.google.com/share?url=' . $activity_link . '" rel="google-plus"></a>';

    $buddy_social_email = '<a class="general foundicon-mail" href="mailto:?body='.$activity_title .' ' . $activity_link . '" rel="nofollow"></a>';


    ?><span class="bp-social-button">
<a class="button item-button bp-secondary-action buddypress-social-button" rel="nofollow">Share</a></span>
    
    <div class="social-buttons <?php $activity_type ?>" style="display: none;">
            <?php if(get_option('social_button_facebook')==1) echo "$buddy_social_facebook"; ?>
            <?php if(get_option('social_button_twitter')==1) echo "$buddy_social_twitter"; ?>
            <?php if(get_option('social_button_google')==1) echo "$buddy_social_google"; ?>
            <?php if(get_option('social_button_email')==1) echo "$buddy_social_email"; ?>
    </div>

    <?php
}
add_action('bp_activity_entry_meta', 'buddy_social_button_activity_filter', 999);

?>