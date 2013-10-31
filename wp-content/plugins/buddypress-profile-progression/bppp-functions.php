<?php

/**
 * Use this function to register a new progression point item.
 * Be sure the callback is valid or the point will not be registered.
 * The callback must either return bool (eg. TRUE, which equals 100%) or a percentage (eg. 50)
 * @param type $label
 * @param type $callback
 * @param type $points
 * @param type $args 
 */

function bppp_register_progression_point($label,$callback,$points=1,$args=false){

    $point = array(
        'label'         => $label,      //label for this item
        'callback'      => $callback,   //name of the function used to retrieve the user's percents for this item
        'points'        => $points      // number of points this item potentially gives  
    );

    if($args)
        $point['args'] = $args;

    bppp()->query->points[] = apply_filters('bppp_register_progression_point_'.$label,$point);

}

function bppp_get_user_id($user_id=false){
    if (!$user_id) $user_id = bppp()->user_id;

    return $user_id;
}

function bppp_get_total_points(){

    $total=0;

    foreach((array)bppp()->query->points as $item){
        $total+=$item['points'];
    }

    return $total;
}




?>
