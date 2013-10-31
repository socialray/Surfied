<?php
/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the plugin templates dir, then STYLESHEETPATH and TEMPLATEPATH.
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
 * @return string The template filename if one is located.
 */
function bppp_locate_template( $template_names, $load = false, $require_once = false ) {
    

	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name )
			continue;
                
                

		if ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {
			$located = STYLESHEETPATH . '/' . $template_name;
			break;
		} else if ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {
			$located = TEMPLATEPATH . '/' . $template_name;
			break;
		} else if ( file_exists( bppp()->templates_dir . $template_name ) ) {
			$located = bppp()->templates_dir . $template_name;
			break;
		}
	}

	if ( $load && '' != $located ){
            load_template( $located, $require_once );
        }
		

	return $located;
}



function bppp_progression_block($user_id=false,$args=false){
    echo bppp_get_progression_block($user_id,$args);
}

    function bppp_get_progression_block($user_id=false,$args=false){
        
        $user_id = bp_displayed_user_id($user_id);
		
		if(!$user_id) return false;
       
        //populate user ID
        bppp()->user_id = $user_id;

        ob_start();
        bppp_locate_template( 'bppp-member.php', true );
        $block = ob_get_contents();
        ob_end_clean();
        
        return apply_filters('bppp_get_progression_block',$block,$user_id);
    }
    
function bppp_title($user_id=false){
    
    $user_id = bppp_get_user_id($user_id);
    
    $title = bppp_get_title($user_id);
    
    if(bp_is_my_profile()){
        $title = '<a title="'.bppp_get_caption($user_id).'" href="'.bppp_get_link($user_id).'">'.$title.'</a>';
    }
    
    echo $title;
    
}
    function bppp_get_title($user_id=false){
        
        $user_id = bppp_get_user_id($user_id);
        
        if($user_id==get_current_user_id()){
            $title = __('My Profile Progression','bppp'); 

        }else{
            $title = sprintf(__("%s's Profile Progression","bppp"),bp_core_get_username($user_id));

        }
        return apply_filters('bppp_title',$title,$user_id);
    }
    
function bppp_caption($user_id=false){
    echo bppp_get_caption($user_id);
}
    function bppp_get_caption($user_id=false){
        
        $user_id = bppp_get_user_id($user_id);
        

        $percent = bppp_get_user_progression_percent($user_id);

        if($user_id==get_current_user_id()){
            $caption = sprintf(__("Your Profile is %1d%% complete","bppp"),$percent);

        }else{
            $caption = sprintf(__("%2s's Profile is %1d%% complete","bppp"),$percent,bp_core_get_username($user_id));

        }
        return apply_filters('bppp_caption',$caption,$user_id,$percent);
    }
    
function bppp_link($user_id=false){
    echo bppp_get_link($user_id);
}
    function bppp_get_link($user_id=false){
        global $bp;
        
        $user_id = bppp_get_user_id($user_id);
        
        if (!$user_id) return false;
        
        $domain = bp_core_get_user_domain( $user_id );

        $link = $domain.$bp->profile->slug . '/edit';//TO FIX is this clean ?
        
        return apply_filters('bppp_link',$link);
    }

    
/**
 * Function that checks if we have points registered.
 * Contains the hook onto which the points must be registered.
 * @return type 
 */
    
function bppp_has_point_items(){

    do_action('bppp_register_progression_points');

    $available_points = bppp()->query->points;
    $valid_points = array();

    //check points are valid
    foreach((array)$available_points as $point_item){
        if (!$point_item['callback']) continue;
        if (!function_exists($point_item['callback'])) continue;
        $valid_points[]=$point_item;
    }
    //
    bppp()->query->current_point = -1;
    bppp()->query->points = $valid_points;
    bppp()->query->point_count = count($valid_points);

    return apply_filters( 'bppp_has_points', bppp()->have_points(), bppp()->query->points );
            
}

function bppp_user_progression_percent($user_id=false) {
    echo bppp_get_user_progression_percent($user_id);
}

    function bppp_get_user_progression_percent($user_id=false) {

        $user_id = bppp_get_user_id($user_id);

        if(!$user_id) return false;

        if (!bppp_has_point_items()) return false;

        while ( bppp()->have_points() ) : bppp()->the_point();

            $item = bppp()->query->point;
            $potential_points+=$item['points'];

            $item_points = bppp()->query->point['callback']();

            if($item_points===true){ //returned TRUE, wich means 100% of potential points
                $item_points=100;
            }

            //balance points for this item
            $add_points = ($item_points/100)*$item['points'];

            $user_points+=$add_points;

        endwhile;

        //calculate total
        if (!empty($potential_points)) {	
                $percent = round(($user_points/$potential_points)*100);
        }

        return (int)$percent;     
    }





?>
