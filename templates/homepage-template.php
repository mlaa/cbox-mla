<?php
/**
 * Template Name: Homepage Template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://shop.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @since 1.0
 */
    infinity_get_header();
?>
<div id="uvTab" style="background-image: url(http://widget.uservoice.com/images/clients/widget2/tab-right-dark.png); border-top-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: solid none solid solid; border-top-color: rgb(255, 255, 255); border-bottom-color: rgb(255, 255, 255); border-left-color: rgb(255, 255, 255); border-top-left-radius: 4px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 4px; -webkit-box-shadow: rgba(255, 255, 255, 0.247059) 1px 1px 1px inset, rgba(0, 0, 0, 0.498039) 0px 1px 2px; box-shadow: rgba(255, 255, 255, 0.247059) 1px 1px 1px inset, rgba(0, 0, 0, 0.498039) 0px 1px 2px; font-style: normal; font-variant: normal; font-weight: bold; font-size: 14px; line-height: 1em; font-family: Arial, sans-serif; position: fixed; right: 0px; top: 50%; z-index: 9999; background-color: rgb(204, 109, 0); margin-top: -60px; margin-right: 0px; display: block; background-position: 50% 0px; background-repeat: no-repeat no-repeat;"><a id="uvTabLabel" style="background-color: transparent; display:block;padding:39px 5px 10px 5px;text-decoration:none;" href="javascript:return false;"><img src="http://widget.uservoice.com/dcache/widget/feedback-tab.png?t=feedback&amp;c=ffffff&amp;r=90" alt="feedback" style="border:0; background-color: transparent; padding:0; margin:0;"></a></div>

<div id="content" role="main" class="column sixteen">
	<div id="top-homepage" class="row">
		<div id="flex-slider-wrap-full" class="column ten">
			<!-- load template for the slider-->
			<?php
				infinity_load_template( 'engine/includes/feature-slider/template.php' );
			?>
			<!-- end -->
		</div>	
		<div id="homepage-sidebar-right" class="column six">
			<div id="homepage-sidebar">
		        <?php
		            dynamic_sidebar( 'Homepage Top Right' );
		        ?>
			</div>
		</div>
	</div>
    <?php
        do_action( 'open_content' );
        do_action( 'open_home' );
    ?>   
 
 	<div id="center-homepage" class="homepage-widgets row">
	    <div id="homepage-widget-left" class="column five homepage-widget">         
	            <?php
	                dynamic_sidebar( 'Homepage Center Left' );
	            ?>
	    </div>
	             
	    <div id="homepage-widget-middle" class="column five homepage-widget">  
	            <?php
	                dynamic_sidebar( 'Homepage Center Middle' );
	            ?>
	    </div>
	     
	    <div id="homepage-widget-right" class="column six homepage-widget">   
	            <?php
	            	dynamic_sidebar( 'Homepage Center Right' );
	            ?>
	    </div>  
	</div>      

 
     
	<div class="homepage-widgets row">
	    <div id="homepage-widget-left" class="column five homepage-widget">         
	            <?php
	                dynamic_sidebar( 'Homepage Left' );
	            ?>
	    </div>
	             
	    <div id="homepage-widget-middle" class="column five homepage-widget">  
	            <?php
	                dynamic_sidebar( 'Homepage Middle' );
	            ?>
	    </div>
	     
	    <div id="homepage-widget-right" class="column six homepage-widget">   
	            <?php
	            	dynamic_sidebar( 'Homepage Right' );
	            ?>
	    </div>  
	</div>      
    <?php
        do_action( 'close_home' );
        do_action( 'close_content' );
    ?>
</div>
<?php
    infinity_get_footer();
?>
