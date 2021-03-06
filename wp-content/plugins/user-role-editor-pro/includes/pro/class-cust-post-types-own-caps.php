<?php

/*
 * User Role Editor WordPress plugin
 * Force custom post types to use their own capabilities set
 * Author: Vladimir Garagulya
 * Author email: support@role-editor.com
 * Author URI: https://www.role-editor.com
 * License: GPL v2+ 
 */

class URE_CustPostTypesOwnCaps {
    
    private $lib = null;
    
    public function __construct($lib) {        
        
        $this->lib = $lib;
        add_action('init', array($this, 'set_own_caps'), 11, 2);
    }
    // end of __construct()
    
    
    public function set_own_caps() {
        global $wp_post_types;

        $post_types = get_post_types(array(), 'objects');
        $_post_types = $this->lib->_get_post_types();
        foreach ($post_types as $post_type) {
            if (!in_array($post_type->name, $_post_types)) {
                continue;
            }
            if ($post_type->name == 'attachment') {   // exclude Media
                continue;
            }
            if ($post_type->name == 'post' || $post_type->capability_type != 'post') {
                continue;
            }

            $wp_post_types[$post_type->name]->capability_type = $post_type->name;
            $wp_post_types[$post_type->name]->map_meta_cap = true;
            $cap_object = new stdClass();
            $cap_object->capability_type = $wp_post_types[$post_type->name]->capability_type;
            $cap_object->map_meta_cap = true;
            $cap_object->capabilities = array();
            $wp_post_types[$post_type->name]->cap = get_post_type_capabilities($cap_object);
        }
        
    }
    // end of set_own_caps
    
    
}
// end of URE_CustPostTypesOwnCaps class