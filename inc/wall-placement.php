<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace VanillaBeans\hangit;

if (!function_exists('\VanillaBeans\hangit\hangit_showroom')) {

    function hangit_showroom($attributes) {
            global $wp_query;
            global $post;
            add_thickbox();

          

        $s='';
    $roomids = \VanillaBeans\vbean_setting('vbean_hangit_rooms','');
    $roomids = str_replace('undefined,', '', $roomids);
    $roomarray = explode(",", $roomids);
//        if($room!=''&&$room!='undefined'){
//            $roomurl = wp_get_attachment_url($room).'';
//            $rooms = $rooms.'rooms[rooms.length]=['.$room.',"'.$roomurl.'"];';
//        }
//    }
    
        $linktext = \VanillaBeans\vbean_setting('vbean_hangit_linktext','Hang it!');
        $instructions = \VanillaBeans\vbean_setting('vbean_hangit_instructions','Move the artwork anywhere in the room. Change background room by clicking on above images. Resize by grabbing the bottom right of the artwork.');
        $instructions = nl2br($instructions);
        if ($attributes && $attributes["linktext"] != "") {
            $linktext = $attributes["linktext"];
        }       

        $hangingid = get_post_thumbnail_id($wp_query->post->ID);
        $hanging = wp_get_attachment_url($hangingid).'';
         ob_start();
        if($hanging==''){
           echo('');
        }
        else{
        $s= ob_get_contents();
        ob_end_clean();


            $firstroom = '';
            if ($roomarray) {
                ob_start();
                ?>
                <a href="#TB_inline?width=600&height=550&inlineId=showroombox" class="thickbox" id="hangerlink"><?php echo($linktext) ?></a>	
                <div id="showroombox" class="thickbox" style="display:none;">
                    <div id="showroom-canvas">
                        <div id="showroom-rooms">
                            <div class="showroom-roomchoice" style="vertical-align: top;width:80px;overflow:hidden">
                                &nbsp;My room...<br />
                            <form id="myownshowroom" onsubmit="return false;">
                                <input type='file' id="myroom" style="border:0;" value="" />
                            </form>
                            </div>
                            <?php
                            foreach ($roomarray as $room) {
                                if (is_numeric($room)) {
                                    if($firstroom==''){
                                       $firstroom = wp_get_attachment_url($room);
                                    }
                                        $theroom = wp_get_attachment_url($room);
                                }
                                //setup_postdata( $post );
                                //the_title();
                                ?>
                                <div class="showroom-roomchoice"><img src="<?php echo($theroom)?>" class="showroom-roomchoice-img"  /></a></div>
                                <?php
                                //                       the_guid( $post->ID);
                                //                        the_excerpt();
                            }
                            ?>
                        </div>
                        <div id="the-showroom"><img src="<?php echo($firstroom) ?>" id="the-showroom-backgroundimg"  /><div id="theframe" class="ui-widget-content"><img src="<?php echo($hanging) ?>" name="thehanging" id="thehanging" /></div></div>
                    </div>
                    <div>
                            <?php echo($instructions)?>
                    </div>
                </div>
                <?php
                $s=$s.ob_get_contents();
                ob_end_clean();
                
            } else {
                echo('');
            }
        }
        return $s;
    }

}



if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Put your plugin code here
}

//add_action('woocommerce_show_product_images', '\VanillaBeans\hangit\hangit_showroom', 15);
add_shortcode( 'hangit', '\VanillaBeans\hangit\hangit_showroom' );


function hangit_inline($atts) {
     return \VanillaBeans\hangit\hangit_showroom($atts);
}
add_shortcode('hangit', '\VanillaBeans\hangit\hangit_inline');


if (!function_exists('load_hangit_showroomscripts')) {

    function load_hangit_showroomscripts() {
        global $wp_scripts;

        wp_enqueue_script(
                'hangit_showroom_vanillabean', VBEANHANGIT_PLUGIN_URL . 'assets/vanillabean.js', array('jquery')
        );
        wp_enqueue_script('jquery-ui-core', array('jquery'));
        wp_enqueue_script('jquery-ui-draggable', array('jquery'));
        wp_enqueue_script('jquery-ui-resizable', array('jquery'));
        wp_enqueue_script('jquery-ui-droppable', array('jquery'));

        wp_enqueue_style('thickbox');
        wp_enqueue_style("jquery-ui-css", VBEANHANGIT_PLUGIN_URL . 'assets/css/jquery-ui.min.css');
        wp_enqueue_style("jquery-ui2-css", VBEANHANGIT_PLUGIN_URL . 'assets/css/jquery-ui.structure.min.css');
        wp_enqueue_style("jquery-ui3-css", VBEANHANGIT_PLUGIN_URL . 'assets/css/jquery-ui.theme.min.css');
        wp_enqueue_style(
                'hangit_showroom_styles', VBEANHANGIT_PLUGIN_URL . 'assets/showroom.css'
        );
    }

}

add_action('wp_enqueue_scripts', '\VanillaBeans\hangit\load_hangit_showroomscripts');
