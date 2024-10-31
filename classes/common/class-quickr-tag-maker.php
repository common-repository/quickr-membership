<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Tag_Maker
 *
 * @author nur85
 */
class Quickr_Tag_Maker {
    /**
     * 
     * @param type $args
     * @param type $return
     * @return string
     */
    public function selectbox_callback($args, $return = false) {
        $item = $args['item'];
        $options = $args['options'];
        $selected = $args['selected'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $output = "<select name='quickr-settings[" . $item . "]' >";
        $output .= "<option value=''></option>";
        if (is_array($options)){
            foreach ($options as $key => $value) {
                $is_selected = ($key == $selected) ? 'selected="selected"' : '';
                $output .= '<option ' . $is_selected . ' value="' . esc_attr($key) . '">' . esc_attr($value) . '</option>';
            }
        }
        if (is_string($options)){
            $output .= $options;
        }
        $output .= '</select>';
        $output .= '<br/><i>' . $msg . '</i>';
        if ($return) {return $output;}
        echo $output;
    }
    /**
     * 
     * @param type $args
     * @param type $return
     * @return string
     */
    public function checkbox_callback($args, $return = false) {
        $item = $args['item'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $is = esc_attr($args['value']);
        $output = "<input type='checkbox' $is name='quickr-settings[" . $item . "]' value=\"checked='checked'\" />";
        $output .= '<br/><i>' . $msg . '</i>';
        if ($return) {return $output;}
        echo $output;        
    }
    /**
     * 
     * @param type $args
     * @param type $return
     * @return string
     */
    public function textarea_callback($args, $return = false) {
        $item = $args['item'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $text = esc_attr($args['value']);
        $output  = "<textarea name='quickr-settings[" . $item . "]'  rows='6' cols='60' >" . $text . "</textarea>";
        $output .='<br/><i>' . $msg . '</i>';
        if ($return) {return $output;}
        echo $output;        
    }
    /**
     * 
     * @param type $args
     * @param type $return
     * @return string
     */
    public function textfield_small_callback($args, $return = false) {
        $item = $args['item'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $text = esc_attr($args['value']);
        $output = "<input type='text' name='quickr-settings[" . $item . "]'  size='5' value='" . $text . "' />";
        $output .='<br/><i>' . $msg . '</i>';
        if ($return) {return $output;}
        echo $output;
    }
    /**
     * 
     * @param type $args
     * @param type $return
     * @return string
     */
    public function textfield_callback($args, $return = false) {
        $item = $args['item'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $text = esc_attr($args['value']);
        $output = "<input type='text' name='quickr-settings[" . $item . "]'  size='50' value='" . $text . "' />";
        $output .='<br/><i>' . $msg . '</i>';
        if ($return) {return $output;}
        echo $output;
    }
    /**
     * 
     * @param type $args
     * @param type $return
     * @return string
     */
    public function textfield_long_readonly_callback($args, $return = false) {
        $item = $args['item'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $text = esc_attr($args['value']);
        $output = "<input type='text' readonly='readonly' name='quickr-settings[" . $item . "]'  size='100' value='" . $text . "' />";
        $output .='<br/><i>' . $msg . '</i>';
        if ($return) {return $output;}
        echo $output;        
    } 
    /**
     * 
     * @param type $args
     * @param type $return
     * @return string
     */
    public function textfield_long_callback($args, $return = false) {
        $item = $args['item'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $text = esc_attr($args['value']);
        $output = "<input type='text' name='quickr-settings[" . $item . "]'  size='100' value='" . $text . "' />";
        $output .='<br/><i>' . $msg . '</i>';
        if ($return) {return $output;}
        echo $output;        
    } 
}
