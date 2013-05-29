<?php  

	 //Loads Faculty of Education Stylesheet
	 add_action('wp_enqueue_scripts', array(__CLASS__, 'theme_styles'));
	 //Add FOE Brand Header
     add_action( 'wp-hybrid-clf_after_header', array(__CLASS__, 'faculty_plugin_before_header_widget') , 9);
	 //Add FOE Featured Images to WordPress if one is present
	 add_filter('wp-hybrid-clf_before_content', array(__CLASS__,'output_foe_featured_img'), 10, 3);
	 
		//foe uploader scripts
		function foe_uploader_options_enqueue_scripts() {
		wp_register_script( 'foe-upload', plugins_url('arts-website-edits') .'/js/foe-upload.js', array('jquery','media-upload','thickbox') );	
	
			wp_enqueue_script('jquery');
			
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			
			wp_enqueue_script('media-upload');
			wp_enqueue_script('foe-upload');
			
		
	}
	add_action('admin_enqueue_scripts', 'foe_uploader_options_enqueue_scripts');
	 
    /**
     * admin function.
     * 
     * @access public
     * @return void
     */
    function admin(){
        
        //Add Arts Options tab in the theme options
        add_settings_section(
                'foe-options', // Unique identifier for the settings section
                'Faculty Of Education options', // Section title
                '__return_false', // Section callback (we don't want anything)
                'theme_options' // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
        );

        //Add Colour options
        add_settings_field(
                'foe-colours', // Unique identifier for the field for this section
                'Colour Options', // Setting field label
                array(__CLASS__,'foe_colour_options'), // Function that renders the settings field
                'theme_options', // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
                'foe-options' // Settings section. Same as the first argument in the add_settings_section() above
        );
        //Add faculty of Education options
        add_settings_field(
                'foe-brand-options', // Unique identifier for the field for this section
                'Faculty of Education Options', // Setting field label
                array(__CLASS__,'foe_brand_options'), // Function that renders the settings field
                'theme_options', // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
                'foe-options' // Settings section. Same as the first argument in the add_settings_section() above
        );        
        //Add Hardcoded list
        add_settings_field(
                'foe-hardcoded-options', // Unique identifier for the field for this section
                'Hardcoded Options', // Setting field label
                array(__CLASS__,'foe_hardcoded_options'), // Function that renders the settings field
                'theme_options', // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
                'foe-options' // Settings section. Same as the first argument in the add_settings_section() above
        );  
    }     

  
    /**
     * arts_colour_options.
     * Display colour options for Arts specific template
     * @access public
     * @return void
     */   
    function foe_colour_options(){ ?>

<div class="explanation"><a href="#" class="explanation-help">Info</a>
  <div> These colours are specific to each unit and represent the colour of Arts logo, and pieces of the items throughout the site.</div>
</div>
<div id="arts-unit-colour-box">
  <label><b>Unit/Website Main Colour:</b></label>
  <div class="arts-colour-item"><span>(A) Main colour: </span>
    <?php  UBC_Collab_Theme_Options::text( 'arts-main-colour' ); ?>
  </div>
  <br/>
  <div class="arts-colour-item"><span>(B) Gradient colour: </span>
    <?php  UBC_Collab_Theme_Options::text( 'arts-gradient-colour' ); ?>
  </div>
  <br/>
  <div class="arts-colour-item"><span>(C) Hover colour: </span>
    <?php  UBC_Collab_Theme_Options::text( 'arts-hover-colour' ); ?>
  </div>
  <br/>
  <div class="arts-colour-item"><span>(D) Reverse colour: </span></div>
  <ul>
    <?php	
                            foreach ( UBC_Arts_Theme_Options::arts_reverse_colour() as $option ) {
                                ?>
    <li class="layout">
      <?php UBC_Collab_Theme_Options::radio( 'arts-reverse-colour', $option['value'], $option['label']); ?>
    </li>
    <?php } ?>
  </ul>
</div>
<?php
	}
    /**
     * foe-options.
     * Display Faculty images
     * @access public
     * @return void
     */      
    function foe_options(){ 
	
		function foe_image_get_default_options() {
			$options = array(
				'banner' => '',
				'chevron-original' => '',
				'logo-retina' => '',
			);
			return $options;
		}
		
		function foe_options_options_setup() {
			global $pagenow;
			if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
				// Now we'll replace the 'Insert into Post Button inside Thickbox' 
				add_filter( 'gettext', 'replace_thickbox_text' , 1, 2 );
			}
		}
		add_action( 'admin_init', 'foe_options_options_setup' );
		
		function replace_thickbox_text($translated_text, $text ) {	
			if ( 'Insert into Post' == $text ) {
				$referer = strpos( wp_get_referer(), 'foe-image-settings' );
				if ( $referer != '' ) {
					return __('Setup', 'foe-images' );
				}
			}
		
			return $translated_text;
		}

	
	?>

<!-- If we have any error by submiting the form, they will appear here -->
<?php settings_errors( 'foe-options-settings-errors' ); ?>
    <div class="explanation">
        <a href="#" class="explanation-help">Info</a>
    <div> These Options are specific to the Faculty of Education only.</div>
<strong>Unit/ Department Banner:</strong><br />
<small>Find banner sizes and templates <a href="http://clf.educ.ubc.ca/design-style-guide/dimensions/#banner" target="_blank">here</a>.</small><br />
<form id="form-wptuts-options" action="arts-website.php" method="post" enctype="multipart/form-data">
  <?php
					settings_fields('foe-options');
					do_settings_sections('foe-images');
				?>
  <input name="theme_foe-images_options[submit]" id="submit_options_form" type="submit" class="button" value="<?php esc_attr_e('Save Settings', 'foe-images'); ?>" />
  <input name="theme_foe-images_options[reset]" type="submit" class="button" value="<?php esc_attr_e('Reset Defaults', 'foe-images'); ?>" />
  <br />
  <strong>Regular Chevron:</strong><br />
  <small>Find Regular Chevron sizes and templates <a href="http://clf.educ.ubc.ca/design-style-guide/dimensions/#chevron" target="_blank">here</a>.</small><br />
  <input id="upload_image" type="text" name="upload_image" value="" />
  <input id="upload_image_button" type="button" value="Upload Image" />
  <br />
  <strong>Retnia Display Chevron:</strong><br />
  <small>Find Retina Chevron sizes and templates <a href="http://clf.educ.ubc.ca/design-style-guide/dimensions/#chevron" target="_blank">here</a>.</small><br />
  <input id="upload_image" type="text" name="upload_image" value="" />
  <input id="upload_image_button" type="button" value="Upload Image" />
</form>
<?php function foe_image_options_settings_init() {
                register_setting( 'foe-options', 'foe-options', 'foe_images_options_validate' );
                
                // Add a form section for the Logo
                add_settings_section('foe_images_settings_header', __( 'Logo Options', 'foe-images' ), 'foe_images_settings_header_text', 'wptuts');
                
                // Add Logo uploader
                add_settings_field('wptuts_setting_logo',  __( 'Logo', 'foe-images' ), 'wptuts_setting_logo', 'wptuts', 'foe_images_settings_header');
                
                // Add Current Image Preview 
                add_settings_field('wptuts_setting_logo_preview',  __( 'Logo Preview', 'foe-images' ), 'wptuts_setting_logo_preview', 'foe-images', 'foe_images_settings_header');
            }
            add_action( 'admin_init', 'foe_image_options_settings_init' );
            
            function wptuts_setting_logo_preview() {
                $wptuts_options = get_option( 'foe-options' );  ?>
<div id="upload_logo_preview" style="min-height: 100px;"> <img style="max-width:100%;" src="<?php echo esc_url( $wptuts_options['banner'] ); ?>" /> </div>
<?php
            }
            
            function foe_images_settings_header_text() {
                ?>
<p>
  <?php _e( 'Manage Logo Options for your Faculty/ Department CLF.', 'foe-images' ); ?>
</p>
<?php
        }
        
	function wptuts_setting_logo() {
		$wptuts_options = get_option( 'foe-options' );
		?>
<input type="hidden" id="logo_url" name="theme_wptuts_options[logo]" value="<?php echo esc_url( $wptuts_options['banner'] ); ?>" />
<input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload Banner', 'wptuts' ); ?>" />
<?php if ( '' != $wptuts_options['banner'] ): ?>
<input id="delete_logo_button" name="theme_wptuts_options[delete_logo]" type="submit" class="button" value="<?php _e( 'Delete Logo', 'foe-images' ); ?>" />
<?php endif; ?>
<span class="description">
<?php _e('Upload an image for the banner.', 'foe-images' ); ?>
</span>
<?php
	}
	
    UBC_Arts_Theme_Options::foe_defaults();
    }    
    
    function arts_defaults(){
        UBC_Collab_Theme_Options::update('clf-unit-colour', '#002145');
    }
    
    /*********** 
     * Default Options
     * 
     * Returns the options array for arts.
     *
     * @since ubc-clf 1.0
     */
    function default_values( $options ) {

            if (!is_array($options)) { 
                    $options = array();
            }

            $defaults = array(
                'arts-main-colour'		=> '#5E869F',
                'arts-gradient-colour'		=> '#71a1bf',
                'arts-hover-colour'		=> '#002145',
				'foe-banner-image'    => '#',
				'foe-chevron-image-regular'    =>  plugins_url('arts-website-edits').'/img/foe-images/faculty-chevron.png',
				'foe-chevron-image-retina'    => plugins_url('arts-website-edits').'/img/foe-images/faculty-chevron-@2x.png',
            );

            $options = array_merge( $options, $defaults );

            return $options;
    }  
	/**
	 * Sanitize and validate form input. Accepts an array, return a sanitized array.
	 *
	 *
	 * @todo set up Reset Options action
	 *
	 * @param array $input Unknown values.
	 * @return array Sanitized theme options ready to be stored in the database.
	 *
	 */
	function validate( $output, $input ) {
		
		// Grab default values as base
		$starter = UBC_Arts_Theme_Options::default_values( array() );
		

	    // Validate Unit Colour Options A, B, and C
            $starter['arts-main-colour'] = UBC_Collab_Theme_Options::validate_text($input['arts-main-colour'], $starter['arts-main-colour'] );
            $starter['arts-gradient-colour'] = UBC_Collab_Theme_Options::validate_text($input['arts-gradient-colour'], $starter['arts-gradient-colour'] );
            $starter['arts-hover-colour'] = UBC_Collab_Theme_Options::validate_text($input['arts-hover-colour'], $starter['arts-hover-colour'] );
			
			            $output = array_merge($output, $starter);

            return $output;            
        }
	 /**
     * theme_styles
     * Adds the FOE Stylesheet
     * @access public
     * @return string
     */         
		function theme_styles()  
			{ 
			  // Register the style like this for a theme:  
			  // (First the unique name for the style (custom-style) then the src, 
			  // then dependencies and ver no. and media type)
			  wp_register_style( 'foe-clf', 
				get_template_directory_uri() . '/library/css/global.css', 
				array(), 
				'20120208', 
				'all' );
			
			  // enqueing:
			  wp_enqueue_style( 'foe-clf' );
			}
	 /**
     * output_foe_brand_header
     * Adds the FOE brand header
     * @access public
     * @return string
     */         

	function faculty_plugin_before_header_widget(){
		echo '    <div id="dept-brand" class=" row-fluid expand">
			 <div id="department-logo" class="row-fluid">
			   <a title="[bloginfo]"  href="/">[bloginfo]</a>
		   </div>
		</div>';
            }
	 /**
     * output_foe_featured_img
     * Adds the FOE brand header
     * @access public
     * @return string
     */         
        function output_foe_featured_img(){
		if ( is_page() ) {
			if (has_post_thumbnail()) {
			  $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
			  echo '<img class="pull-right contrast img-circle visible-desktop visible-tablet img-polaroid alignright featured-images-pages" src="' . $image_url[0] .'" title="' . the_title_attribute('echo=0') . '" alt="' . the_title_attribute('echo=0') . '" />';
			} else {
				echo '';
			}
		}
		}	
		
      /**
     * wp_head
     * Appends some of the dynamic css and js to the wordpress header
     * @access public
     * @return void
     */        
        function wp_head(){ ?>
        <style type="text/css" media="screen">
			#dept-brand {
				background: url(<?php echo UBC_Collab_Theme_Options::get('foe-banner-image')?>) #002145;
			}
			#department-logo {
			  background-image: url(<?php echo UBC_Collab_Theme_Options::get('foe-chevron-image-regular')?>); 
			}
			@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
			  #department-logo {
				background-image: url(<?php echo UBC_Collab_Theme_Options::get('foe-chevron-image-retina')?>;
			  }
			}
        </style>
<?php }
UBC_Arts_Theme_Options::init();


?>