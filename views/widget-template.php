<?php 

global $post;
global $wpdb;

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}
if ( is_front_page() == true ) {

    $id = 0;

} else {

    $id = $post->ID;
}

$obj = WVRNP_Visitors::wvrnp_get_settings(); 

?>

<div class="vrnp-container" style="background: <?php echo $obj->background; ?>; max-width: 380px; font-family:arial; clear: both;">

    <?php if ($obj->show_title == 'true'): ?>

        <h3 class="vrnp-size-of" style="color: <?php echo $obj->color; ?>;">

            <?php echo $obj->title; ?>        

        </h3>
        <br>
        <h2 class="vrnp-title" style="color: <?php echo $obj->color; ?>;">

    <?php else: ?>

        <h2 class="vrnp-title" style="color: <?php echo $obj->color; ?>; margin-top: 20px;">
        
    <?php endif; ?>
    
        <div>
            Visitors Online: 
            <?php
                WVRNP_Visitors::wvrnp_get_users_online();
            ?> 
        </div>
        <br>
        <div>
            <?php
                if($obj->show_daily_visitors == 'true') {
                    ?> Visits today: <?php
                    WVRNP_Visitors::wvrnp_db_get_daily_visitor($wpdb);
                } 
            ?> 

        </div>
        <br>
        <div>
            <?php
                if($obj->show_monthly_visitors == 'true') {
                    ?> Visitors this month: <?php
                    WVRNP_Visitors::wvrnp_db_get_monthly_visitor($wpdb); 
                } 
            ?>                
        </div>
        <br>
        <div>
            <?php
                if($obj->show_total_visitors == 'true') {
                    ?> Total visitors: <?php
                    WVRNP_Visitors::wvrnp_db_get_all_visitor($wpdb); 
                } 
            ?>                 
        </div>
    </h2>

    <?php
        echo "<p style='color: " . $obj->color . "'>  Plugin by <a target='_blank' href='http://visitorsrightnow.co.uk/'>Visitors Right Now</a> </p>";
    ?>

</div>
