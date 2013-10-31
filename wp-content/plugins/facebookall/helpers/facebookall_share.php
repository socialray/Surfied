<?php
/*
 * Adding comments.
 */
 
function facebookall_get_fb_comments() {
  $fball_settings = get_option('fball_settings');
  if ($fball_settings['enablecomments'] == '1') {
		  $comment_script = '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId='.$fball_settings['comment_appid'].'";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>';
    $show_comments = '';
      if (!empty($fball_settings['comment_title'])) {
	    $show_comments .= '<div style="margin:0"><b>'.ucfirst($fball_settings['comment_title']).'</b></div>';
	  }
   if ($fball_settings['comment_color'] == '0') {$colorscheme = 'dark';}
   else {$colorscheme = 'light';}
   $show_comments .= $comment_script.'<div class="fb-comments" data-href="'. facebookall_get_current_url() .'" data-width="'.$fball_settings['comment_width'].'" data-num-posts="'.$fball_settings['comment_numpost'].'" data-colorscheme="'.$colorscheme.'"></div>';
   return $show_comments;
  }
  else {
    $show_comments ="";
    return $show_comments;
  }
}

/*
 * Showing comments.
 */
function facebookall_add_fb_comments($content){
  $fball_settings = get_option('fball_settings');
  global $post;
  $html = facebookall_get_fb_comments();
  if((isset( $fball_settings['comment_homepage']) && is_front_page()) || ( isset( $fball_settings['comment_posts'] ) && is_single() ) || ( isset( $fball_settings['comment_pages'] ) && is_page() ) || ( isset( $fball_settings['comment_postecerpts'] ) && has_excerpt() ) || ( isset( $fball_settings['comment_archives'] ) && is_archive() ) || ( isset( $fball_settings['comment_feed'] ) && is_feed() ) ) {	
    if(isset($fball_settings['comment_top'] ) && isset($fball_settings['comment_bottom'])) {
      $content = $html.'<br/>'.$content.'<br/>'.$html;
    }
    else {
      if(isset($fball_settings['comment_top'])){
        $content = $html.$content;
      }
      elseif (isset($fball_settings['comment_bottom'])){
        $content = $content.$html;
      }
    }
  }
  return $content;
}
add_filter('the_content', 'facebookall_add_fb_comments');

/*
 * Adding social share.
 */
function facebookall_get_socialshre() {
global $post;
  $fball_settings = get_option('fball_settings');
  if (!empty($fball_settings['enableshare']) AND $fball_settings['enableshare'] == '1') {
    $layout_style = (!empty($fball_settings['share_layout']) ? $fball_settings['share_layout'] : "");
    //Button Style
    $linkedin_style = ($layout_style=='1') ? "right" : "top";
    $twitter_style = ($layout_style=='1') ? "horizontal" : "vertical";
    $gplus_style = ($layout_style=='1') ? "medium" : "tall";
    $pin_style = ($layout_style=='1') ? "beside" : "above";
    $digg_style = ($layout_style=='1') ? "DiggCompact" : "DiggMedium";
    $like_btn_style = ($layout_style=='1') ? "button_count" : "box_count";
	$title 		= str_replace('+','%20',urlencode($post->post_title));
	    
    $output = "";
   
	//Output
    $output .= "<style>.fballshare_left {float:left}.fballshare {margin:0px;text-align:center}.fball_fblike{width:95px;}.fball_pinterest, .fball_linkedin, .fball_digg {margin-right:10px;} .fballshare .fball_fblike span{width: 535px!important;}";
	if($layout_style=='1') {
	$output .= ".fball_plusone {width:70px}.fball_twitter {width:106px}.fball_digg {margin-left:25px}";
	 } 
	 else {
	 $output .= ".fball_twitter {width:66px}.fball_plusone {width:62px;margin-left:5px}.fball_pinterest{margin-top:20px}";
	 }
	 
$output .="</style><div class='fballshare'>";
    
    if ($fball_settings['share_linkedin']== '1') {
      $output .= "<div class='fball_linkedin fballshare_left'><script type='IN/Share' data-url=" . get_permalink() . " data-counter='" . $linkedin_style . "'></script></div>";
	  }
    if ($fball_settings['share_twitter']== '1') {
      $output .= "<div class='fball_twitter fballshare_left'><a href='http://twitter.com/share' class='twitter-share-button' data-text='" . $title. "' data-url='" . get_permalink() . "' data-count='" . $twitter_style . "'>Tweet</a></div>";
	  }
    if ($fball_settings['share_gplus']== '1') {
      $output .= "<div class='fball_plusone fballshare_left'><g:plusone href='" . get_permalink() . "' size='" . $gplus_style . "'></g:plusone></div>";
	  }
    if ($fball_settings['share_pin']== '1') {
      $output .= "<div class='fball_pinterest fballshare_left'><a data-pin-config='" . $pin_style ."' href='//pinterest.com/pin/create/button/?url=" . get_permalink() . "&media=&description=" . $title . "' data-pin-do = 'buttonPin' ><img src='//assets.pinterest.com/images/pidgets/pin_it_button.png'/></a></div>";
	  }
    if ($fball_settings['share_digg']== '1') {
	  $output .= "<script>(function() {
					  var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
					  s.type = 'text/javascript';
					  s.async = true;
					  s.src = 'http://widgets.digg.com/buttons.js';
					  s1.parentNode.insertBefore(s, s1);
					})();</script>";
      $output .= "<div class='fball_digg fballshare_left'><a class='DiggThisButton " . $digg_style . "' href='http://digg.com/submit?url='".get_permalink()."'></a></div>";
	  }
	  if ($fball_settings['share_facebook']== '1' ) {
	  $output .= "<script>(function(d){
					  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
					  js = d.createElement('script'); js.id = id; js.async = true;
					  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
					  d.getElementsByTagName('head')[0].appendChild(js);
					}(document));</script>";
					
	    $output .= "<div class='fball_fblike fballshare_left'><div class='fb-like' data-href='" . get_permalink() . "' data-send='false' data-layout='" . $like_btn_style . "' data-width='120' data-show-faces='false'></div></div>";
    }
      $output .="<div style='clear:both'></div></div>";
	  $sharetitle = '';
	  if (!empty($fball_settings['share_title'])) {
	    $sharetitle = '<div style="margin:0"><b>'.ucfirst($fball_settings['share_title']).'</b></div>';
	  }
      return $sharetitle.$output;	
    }
    else {
      $output = "";
      return $output;
    }	
}

/*
 * Showing social share.
 */
function facebookall_add_socialshre($content){
  $fball_settings = get_option('fball_settings');
  global $post;
  $html = facebookall_get_socialshre();
  if((isset( $fball_settings['share_home']) && is_front_page()) || ( isset( $fball_settings['share_posts'] ) && is_single() ) || ( isset( $fball_settings['share_pages'] ) && is_page() ) || ( isset( $fball_settings['share_postexcerpts'] ) && has_excerpt() ) || ( isset( $fball_settings['share_archives'] ) && is_archive() ) || ( isset( $fball_settings['share_feed'] ) && is_feed() ) ) {	
    if(isset($fball_settings['share_top'] ) && isset($fball_settings['share_bottom'])) {
      $content = $html.'<br/>'.$content.'<br/>'.$html;
    }
    else {
      if(isset($fball_settings['share_top'])){
        $content = $html.$content;
      }
      elseif (isset($fball_settings['share_bottom'])){
        $content = $content.$html;
      }
    }
  }
  return $content;
}
add_filter('the_content', 'facebookall_add_socialshre');