<?php

function bppp_register_profile_fields_points(){
    //get all the fields
    if ( !$fields = wp_cache_get( 'xprofile_fields', bppp()->prefix ) ) {
            $fields = bppp_retrieve_profile_fields();
            wp_cache_set( 'xprofile_fields', $fields, bppp()->prefix );
    }

    // remove first field coz we don't want to count base field (Name)
    array_shift($fields);

    foreach ((array)$fields as $field){

        $field_value = bp_get_profile_field_data( array('field' => $field->id,'user_id' => $user_id) );
        if (!empty($field_value)) $percent_of_point = 100;

        bppp_register_progression_point(
                'profile-field-'.$field->id, 
                'bppp_get_user_progression_for_field',
                1,
                array('field-id'=>$field->id)
        );

    }

}

function bppp_retrieve_profile_fields(){
    if ( !$groups = wp_cache_get( 'xprofile_groups_inc_empty', bppp()->prefix ) ) {
            $groups = BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
            wp_cache_set( 'xprofile_groups_inc_empty', $groups, bppp()->prefix );
    }

    foreach ((array)$groups as $group) {

        foreach((array)$group->fields as $key=>$field) {

            $fields[]=$field;

            }
    }

    return $fields;

}

function bppp_get_user_progression_for_field(){
    
    //get current point item
    $point_item = bppp()->query->point;
    $field_id = $point_item['args']['field-id'];

    if(!$field_id) return false;
    
    //get field value
    $user_id = bppp_get_user_id($user_id);
    $value = bp_get_profile_field_data( array('field' => $field_id,'user_id' => $user_id) );

    return (bool)$value; //return TRUE (100% of potential points)
}

//register profile fields points
add_action('bppp_register_progression_points','bppp_register_profile_fields_points',9);
    

?>