<?php

/**
 * @package simple-note-widget
*/
/*
Plugin Name: Simple Note Widget
Plugin URI: http://www.crayfishcreative.com
Description: Thanks for installing Simple Note Widget on your Wordpress Website.
Version: 1.0
Author: Amisha Patel
Author URI: http://www.crayfishcreative.com
*/

add_action('widgets_init','register_easy_simple_note_widget');

function register_easy_simple_note_widget()
{
	register_widget('simple_note_widget');
}

class simple_note_widget extends WP_Widget{

	public function __construct() {
        $params = array(
            'description' => 'Simple Note Widget',
            'name' => 'Simple Note Widget'
        );
        parent::__construct('simple_note_widget','',$params);
	   add_action('wp_enqueue_scripts', array($this, 'simple_note_font_css'));
	   add_action('admin_enqueue_scripts', array($this,'my_admin_theme_style'));
	   add_action( 'admin_enqueue_scripts', array($this,'color_picker_assets' ));
    }
	
     
	function simple_note_font_css(){
		wp_register_style( 'simple-style-css', plugins_url( '/assets/css/simple-style.css', __FILE__ ));
        wp_register_style( 'font-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style( 'simple-style-css' );
        wp_enqueue_style( 'font-css' );
    }
	
	/*  date js & css */
		function my_admin_theme_style() {
			wp_enqueue_style('my-admin-theme', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
			wp_enqueue_style( 'my-admin-style', plugin_dir_url(__FILE__) . '/assets/css/easy-admin.css');
		}

       /* color picker*/
		function color_picker_assets($hook_suffix) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker'  );
		}
		


	/*-------------------------------------------------------
	 *				Front-end display of widget
	 *-------------------------------------------------------*/

	function widget( $args, $instance ) {

		extract( $args );
		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		if(empty($auto)) $auto = "true";
		if(empty($autocontrol)) $autocontrol = "true";
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		?>

         <div class="bs-note-widget-wrapper">
           <div class="bs-note-header">
               <?php echo '<p>' .$instance['simple_title']. '</p>';?>
               <?php if($instance['display_date']=='true'){ ?> 
               <small><?php echo $instance['simple_date'];?></small>
               <?php } else {} ?>
           </div>
           <div class="bs-note-content">
               <?php 
			    $content=$instance['simple_content'];
			    $lines = explode("\n", $content);
					if ( !empty($lines) ) {
					  echo '<ul>';
					  foreach ( $lines as $line ) {
						echo '<li>' .'<i class="fa fa-chevron-right" aria-hidden="true"></i>' . trim( $line ) .'</li>';
					  }
					  echo '</ul>';
					}
			   ?>
           </div>
           <div class="bs-note-foter">
              <a href="<?php echo $instance['simple_footer_link'];?>" target="_blank"> <?php echo $instance['simple_footer'];?></a>
           </div>
         </div>
          <style>
			  .bs-note-widget-wrapper {
					background:<?php echo $instance['border_style'];?> ;
					padding: 1px;
					height: <?php echo $instance['height'];?>px;
					overflow-y: scroll;
					border-radius: 2px;
					max-width: <?php echo $instance['width'];?>;
			 }
	
			 i.fa.fa-chevron-right {
					margin: 0px 5px;
					color: <?php echo $instance['list_style'];?>;
			}
			 .bs-note-content ul li {
                     background: <?php echo $instance['bg_style'];?>;
			}
			
          </style> 
        

		<?php echo $after_widget;
	}


	/*-------------------------------------------------------
	 *				Sanitize data, save and retrive
	 *-------------------------------------------------------*/

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['simple_title'] 		= $new_instance['simple_title'];
		$instance['simple_content'] 	= $new_instance['simple_content'];
		$instance['simple_date'] 		= $new_instance['simple_date'];
		$instance['simple_footer'] 		= $new_instance['simple_footer'];
		$instance['simple_footer_link'] = $new_instance['simple_footer_link'];
		$instance['display_date']   	= $new_instance['display_date'];		
		$instance['list_style'] 		= $new_instance['list_style'];
		$instance['bg_style'] 		    = $new_instance['bg_style'];
		$instance['border_style'] 		    = $new_instance['border_style'];
		$instance['width'] 	            = $new_instance['width'];
		$instance['height'] 		    = $new_instance['height'];
	
		return $instance;
	}


	/*-------------------------------------------------------
	 *				Back-End display of widget
	 *-------------------------------------------------------*/
	
	function form( $instance )
	{

		$defaults = array(  'title' => '',
			'simple_title' => 'HOLIDAYS TODAY',
			'simple_content' => '',
			'easy_img_link2' => '#',
			'simple_date' => '',
			'simple_footer' => '',
			'simple_footer_link' => '#',
			'display_date' => 'true',
			'list_style' => '#111',
			'bg_style'=>'#ddd',
			'border_style'=>'#ddd',
			'width' => '100%',
			'height' => '300'
			);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
        <script>
		  jQuery(document).ready(function() {
			jQuery(".datepicker").datepicker();
			jQuery(".my-input-class").wpColorPicker();
		  });
		  </script>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
        
        <p>
			<label for="<?php echo $this->get_field_id( 'simple_title' ); ?>">Simple Widget Title:</label>
			<input id="<?php echo $this->get_field_id( 'simple_title' ); ?>" name="<?php echo $this->get_field_name( 'simple_title' ); ?>" value="<?php echo $instance['simple_title']; ?>" style="width:100%;" />
		</p>
	    
        <p>
			<label for="<?php echo $this->get_field_id( 'simple_content' ); ?>">Simple Widget Title:</label>
			<textarea id="<?php echo $this->get_field_id( 'simple_content' ); ?>" name="<?php echo $this->get_field_name( 'simple_content' ); ?>" style="width:100%;" >
            <?php echo $instance['simple_content']; ?>
            </textarea>
		</p>
        
        <p>
			<label for="<?php echo $this->get_field_id( 'simple_date' ); ?>">Simple Widget Date:</label>
               <input class="datepicker <?php echo $this->get_field_id( 'simple_date' ); ?>" name="<?php echo $this->get_field_name( 'simple_date' ); ?>" value="<?php echo $instance['simple_date']; ?>" style="width:100%;" />
                   
       </p>
       
       <p>
			<label for="<?php echo $this->get_field_id( 'simple_footer' ); ?>">Simple Widget Footer Text:</label>
			<input id="<?php echo $this->get_field_id( 'simple_footer' ); ?>" name="<?php echo $this->get_field_name( 'simple_footer' ); ?>" value="<?php echo $instance['simple_footer']; ?>" style="width:100%;" />
		</p>
        
        <p>
			<label for="<?php echo $this->get_field_id( 'simple_footer_link' ); ?>">Simple Widget Footer Link:</label>
			<input id="<?php echo $this->get_field_id( 'simple_footer_link' ); ?>" name="<?php echo $this->get_field_name( 'simple_footer_link' ); ?>" value="<?php echo $instance['simple_footer_link']; ?>" style="width:100%;" />
		</p>

              <p class="box-admin">Widget Configuration</p>
		<p>
		    <label for="<?php echo $this->get_field_id( 'display_date' ); ?>">Display Date:</label> 
		    <select id="<?php echo $this->get_field_id( 'display_date' ); ?>"
		        name="<?php echo $this->get_field_name( 'display_date' ); ?>"
		        class="widefat" style="width:100%;">
                <option value="true"<?php selected( $instance['display_date'], 'true' ); ?>>Yes</option>  
                <option value="false"<?php selected( $instance['display_date'], 'false' ); ?>>No</option>       
		    </select>
       </p>	

       <p>
           <label for="<?php echo $this->get_field_id( 'list_style' ); ?>">List Color:</label>
           <input class="my-input-class <?php echo $this->get_field_id( 'list_style' ); ?>" name="<?php echo $this->get_field_name( 'list_style' ); ?>" value="<?php echo $instance['list_style']; ?>"  />
         
       </p>
          <p>
           <label for="<?php echo $this->get_field_id( 'bg_style' ); ?>">Background Color:</label>
           <input class="my-input-class <?php echo $this->get_field_id( 'bg_style' ); ?>" name="<?php echo $this->get_field_name( 'bg_style' ); ?>" value="<?php echo $instance['bg_style']; ?>"  />
           
       </p>
       
       <p>
           <label for="<?php echo $this->get_field_id( 'border_style' ); ?>">Border Color:</label>
           <input class="my-input-class <?php echo $this->get_field_id( 'border_style' ); ?>" name="<?php echo $this->get_field_name( 'border_style' ); ?>" value="<?php echo $instance['border_style']; ?>"  />
           
       </p>

       <p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">Width:</label>
			<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" style="width:100%;" /><small>width should be %</small>
		</p>
        
         <p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>">Height:</label>
			<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" style="width:100%;" />
		</p>	



		<?php
	}
}
?>