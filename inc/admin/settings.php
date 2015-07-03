<?php

/* 
 * Copyright (C) 2014 Velvary Pty Ltd
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace VanillaBeans\hangit;
            // If this file is called directly, abort.
            if ( ! defined( 'WPINC' ) ) {
                    die;
            }

function vbean_hangit_admin_scripts() {
    if(function_exists( 'wp_enqueue_media' )){
        wp_enqueue_media();
    }else{
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }
    wp_enqueue_script('jquery');
}
function vbean_hangit_admin_styles() {
    wp_enqueue_style('thickbox');
}
    add_action('admin_print_scripts', '\VanillaBeans\hangit\vbean_hangit_admin_scripts');
    add_action('admin_print_styles', '\VanillaBeans\hangit\vbean_hangit_admin_styles');    

function RegisterSettings(){
	register_setting( 'vbean-hangit-settings', 'vbean_hangit_linktext' );
	register_setting( 'vbean-hangit-settings', 'vbean_hangit_productname' );
	register_setting( 'vbean-hangit-settings', 'vbean_hangit_instructions' );
	register_setting( 'vbean-hangit-settings', 'vbean_hangit_rooms' );
	register_setting( 'vbean-hangit-settings', 'vbean_hangit_roomcategoryid' );
}

function SettingsPage(){
    
    $roomids = \VanillaBeans\vbean_setting('vbean_hangit_rooms','');
    $roomids = str_replace('undefined,', '', $roomids);
    $roomarray = explode(",", $roomids);
    $rooms='';
    foreach ($roomarray as $room){
        if($room!=''&&$room!='undefined'){
            $roomurl = wp_get_attachment_url($room).'';
            $rooms = $rooms.'rooms[rooms.length]=['.$room.',"'.$roomurl.'"];';
        }
    }
    
    $cat = \VanillaBeans\vbean_setting('vbean_hangit_roomcategoryid',-1);
    
            $backgrounds = array(
                'posts_per_page' => -1,
                'orderby' => 'rand',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'category' => $cat
            );
            $bgs = get_posts($backgrounds);    
            $backgroundcount = $bgs->post_count;
        $args = array(
	'show_option_all'    => '',
	'show_option_none'   => 'Choose a background image category',
	'option_none_value'  => '-1',
	'orderby'            => 'ID', 
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => 1, 
	'child_of'           => 0,
	'exclude'            => '',
	'echo'               => 1,
	'hierarchical'       => 0, 
        'selected'           => $cat,
    	'name'               => 'vbean_hangit_roomcategoryid',
	'id'                 => 'vbean_hangit_roomcategoryid',
	'class'              => 'postform',
	'depth'              => 0,
	'tab_index'          => 0,
	'taxonomy'           => 'category',
	'hide_if_empty'      => false,
	'value_field'	     => 'term_id',	
);
    
    ?>

<style>
    .roomcont{display:inline-block;margin-right: 5px;}
    .roomconthead{text-align: right;background:navy;color:white;font-weight:bold;width:100%;}
    .roomcontimgholder{display: inline-block;height:100px!important;}
    .roomcontimg{height:100%;}
</style>

        <div class="wrap">
        <h2>Vanilla Bean Hangit Settings</h2>
        <p>This is a very simple plugin for woocommrece. It utilizes the inbuilt wordpress javascript and jquery libraries to enable better visualisation of artworks for sale by providing placement options.</p>
        <p><b>Shortcode example: </b>[hangit linktext="See how it hangs..."]<br>
        linktext parameter is an optional override for Link Title specified below.
        </p>
        <?php if(!$bgs){
            ?><p style='color:red;'>You currently have no backgrounds available in your selected background category. This means that the showroom will not appear.</p>
            
            <?php    
        }else{
            ?>
            <p style="color:green"> backgrounds available.</p>
                <?php
        }
        ?>
            <form method="post" action="options.php">

    <?php settings_fields( 'vbean-hangit-settings' ); ?>
    <?php do_settings_sections( 'vbean-hangit-settings' ); ?>
                <table class="form-table">
                    
                    <tr valign="top">
                        <th scope="row">Background Images</th>
                        <td>
                            
                            <button id="upload_image_button" type="button" name="upload_image_button" class="btn btn-large">Add Images to use as backgrounds</button>
                            
                            <div class="description">Choose your list of images for hangit to use as backgrounds.</div>
                            
                            <div id="vbean_hangit_rooms_preview"></div>
                            <input type="hidden" name="vbean_hangit_rooms" id="vbean_hangit_rooms" value="<?php echo($roomids) ?>" />
                        </td>
                    </tr>
                    
                    
                    <tr valign="top">
                        <th scope="row">Link Title</th>
                        <td><textarea cols="60" rows="3" name="vbean_hangit_linktext" id="vbean_hangit_linktext"><?php echo \VanillaBeans\vbean_setting('vbean_hangit_linktext','Hang it!')?></textarea>
                            <div class="description">This is what the link to the popup will say</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Product label</th>
                        <td><textarea cols="60" rows="3" name="vbean_hangit_productname" id="vbean_hangit_productname"><?php echo \VanillaBeans\vbean_setting('vbean_hangit_productname','artwork')?></textarea>
                            <div class="description">A word for the type of thing being overlaid. eg: artwork, print, painting, photograph, frame</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Hangit Instructions</th>
                        <td><textarea cols="60" rows="3" name="vbean_hangit_instructions" id="vbean_hangit_instructions"><?php echo \VanillaBeans\vbean_setting('vbean_hangit_instructions','Move the artwork anywhere in the room.Change background room by clicking on above images.
Resize by grabbing the bottom right of the artwork.')?></textarea>
                            <div class="description">Instrucitons for how to drag and resize</div>
                        </td>
                    </tr>

                </table>



                

            <?php submit_button(); ?>
            </form>
        </div>    

<script language="javascript">

var vbean_currentpreview;

var vbean_media_frame;
var rooms=[];
<?php echo($rooms) ?>

jQuery(document).ready(function($){

        
$('#upload_image_button').click(function() {

  if ( vbean_media_frame ) {
    vbean_media_frame.open();
    return;
  }

  vbean_media_frame = wp.media.frames.vbean_media_frame = wp.media({
    multiple: true,
    library: {
      type: 'image'
    },
  });

  vbean_media_frame.on('select', function(){
    var selection = vbean_media_frame.state().get('selection');
    selection.map( function( attachment ) {
      attachment = attachment.toJSON();
          console.log(attachment);
          var addit=true;
          for(i=0;i<rooms.length;i++){
              if(rooms[i][0]==attachment.id){
                  addit=false;
              }
          }
          if(addit){
              rooms[rooms.length]=[attachment.id,attachment.url];
              $("#vbean_hangit_rooms_preview").append("<div class='roomcont'><div class='roomconthead'><button type='button' onclick='removeThisRoom(this)' data-attachmentid='"+attachment.id+"'>X</button></div><div class='roomcontimgholder'><img src='"+attachment.url+"' class='roomcontimg' ></div></div>");
                setRoomField();
          }
          // Do something with attachment.id and/or attachment.url here
    });
  });

  vbean_media_frame.open();

});

    preview_rooms();




    function preview_rooms(){
        $("#vbean_hangit_rooms_preview").html("");
        for(i=0;i<rooms.length;i++){
              $("#vbean_hangit_rooms_preview").append("<div class='roomcont'><div class='roomconthead'><button type='button' onclick='removeThisRoom(this)' data-attachmentid='"+rooms[i][0]+"'>X</button></div><div class='roomcontimgholder'><img src='"+rooms[i][1]+"' class='roomcontimg' ></div></div>");
        }
    }



    });
    
    function setRoomField(){
        var s='';
              for(i=0;i<rooms.length;i++){
                  if(i>0){
                      s+=',';
                    }
                    s+=rooms[i][0];
                }        
        jQuery("#vbean_hangit_rooms").val(s); 
    }
    
    
      
    function removeThisRoom(obj){
        var theid = jQuery(obj).data("attachmentid");
        var sp=-1;
        for(i=0;i<rooms.length;i++){
            if(rooms[i][0]==theid){
                  sp=i;
            }
        }
        
        if(sp>-1){
            rooms.splice(sp,1);
        }
        jQuery(obj).parent().parent().remove();
        setRoomField();
    }

  
    
</script>

<?php
}



