<?php 

	if ( ! defined( 'ABSPATH' ) ) {   

		exit; // Exit if accessed directly.

	}

	$obj = WVRNP_Visitors::wvrnp_get_settings(); 

	?>

	<form class="vnrp-container" method="POST">    

		<input type="hidden" name="vnrp_save" value="true">    

		<input type="hidden" name="bg-color" id="bg-color" value="<?php echo $obj->background; ?>">    

		<input type="hidden" name="text-color" id="text-color" value="<?php echo $obj->color; ?>">    

		<div>        

			<h2 class="cc-admin-title">Visitors Right Now</h2>    

		</div>    

		<div>        
			<label for="title">Widget title</label>
			 <input type="text" name="title" value="<?php echo ( ( empty( $title ) ) ? $obj->title : $title ) ?>" class="cc-input-title">     

			<input type="checkbox" <?php echo ( $obj->show_title != 'true' ) ? 'checked' : 'true'; ?> name="show-title"> <span>Hide</span>   

		</div> 
		<div>        
			<label for="title">Show daily visitors</label>   
			<input type="checkbox" <?php echo ( $obj->show_daily_visitors != 'true' ) ? 'checked' : 'true'; ?> name="show-daily-visitors"> <span>Hide</span>   
		</div> 
		<div>        
			<label for="title">Show monthly visitors</label>   
			<input type="checkbox" <?php echo ( $obj->show_monthly_visitors != 'true' ) ? 'checked' : 'true'; ?> name="show-monthly-visitors"> <span>Hide</span>   
		</div>  
		<div>        
			<label for="title">Show total visitors</label>   
			<input type="checkbox" <?php echo ( $obj->show_total_visitors != 'true' ) ? 'checked' : 'true'; ?> name="show-total-visitors"> <span>Hide</span>   
		</div>       

		<div>        

			<div class="bg-color" id="back">            

				<div class="color" id="back-title" style="background-color: <?php echo $obj->background; ?>;"></div>            

				<div class="title">Background color</div>       

			</div>    

		</div>    

		<div>        

			<div class="bg-color" id="text">            

				<div class="color" id="text-title" style="background-color: <?php echo $obj->color; ?>;"></div>            

				<div class="title">Text color</div>       

			</div>    

		</div>    

		<div>        

			<h2>Shortcode: <span>[visitors]</span></h2>    

		</div>    

		<input type="submit" class="vnrp-save" value="save">

	</form><?php WVRNP_Visitors::wvrnp_view('admin/tinycolor'); ?>