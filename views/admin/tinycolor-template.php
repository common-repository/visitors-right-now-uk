<?php    
	if ( ! defined( 'ABSPATH' ) ) {  
	    exit; // Exit if accessed directly.    
	} 
?>
	<div class="color-picker-panel">    
		<div class="close-picer">x</div>    
		<div class="panel-row">        
			<div class="swatches default-swatches"></div>        
			<!--                        <button class="button eyedropper">Get Color</button>-->    
		</div>    
		<div class="panel-row">        
			<div class="spectrum-map">            
				<button id="spectrum-cursor" class="color-cursor"></button>            
				<canvas id="spectrum-canvas"></canvas>        
			</div>        
			<div class="hue-map">            
				<button id="hue-cursor" class="color-cursor"></button>            
				<canvas id="hue-canvas"></canvas>        
			</div>   
		</div>    
		<div class="panel-row">        
			<div id="rgb-fields" class="field-group value-fields rgb-fields active">            
				<div class="field-group">                
					<label for="" class="field-label">R:</label>                
					<input type="number" max="255" min="0" id="red" class="field-input rgb-input"/>            
				</div>            
				<div class="field-group">                
					<label for="" class="field-label">G:</label>                
					<input type="number" max="255" min="0" id="green" class="field-input rgb-input"/>            
				</div>           
				<div class="field-group">                
					<label for="" class="field-label">B:</label>                
					<input type="number" max="255" min="0" id="blue" class="field-input rgb-input"/>            
				</div>        
			</div>        
			<div id="hex-field" class="field-group value-fields hex-field">            
				<label for="" class="field-label">Hex:</label>            
				<input type="text" id="hex" class="field-input"/>        
			</div>        
			<button id="mode-toggle" class="button mode-toggle">Mode</button>    
		</div>    
		<div class="panel-row">        
			<h2 class="panel-header">User Colors</h2>        
			<div id="user-swatches" class="swatches custom-swatches"></div>        
			<button id="add-swatch" class="button add-swatch"><span id="color-indicator" class="color-indicator"></span><span>Add to Swatches</span></button>    
		</div>
	</div>