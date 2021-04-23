<?php
defined( 'ABSPATH' ) || exit;

 class Aioic_Invites_Widget extends  WP_Widget{


    function __construct()
    {
        parent::__construct("aioic_invites_widget", __("AIOIC Invites",'all_in_one_invite_codes'), array('description'=>__("Show the current member Invites Status.",'all_in_one_invite_codes')), );


    }

     public function widget( $args, $instance ) {
         $title = apply_filters( 'widget_title', $instance['title'] );
         echo $args['before widget'];

         if(is_user_logged_in()){
            $cuser = wp_get_current_user();
             $args = array(
                 'author'         => get_current_user_id(),
                 'posts_per_page' => - 1,
                 'post_type'      => 'tk_invite_codes', //you can use also 'any'
             );
             $active_codes = array();
             $pending_codes = array();
             $used_codes = array();
             $the_query = new WP_Query( $args );
             if ( $the_query->have_posts() ) {
                 while ( $the_query->have_posts() ) : $the_query->the_post();
                     $all_in_one_invite_codes_options = get_post_meta( get_the_ID(), 'all_in_one_invite_codes_options', true );
                     $email                           = empty( $all_in_one_invite_codes_options['email'] ) ? '' : $all_in_one_invite_codes_options['email'];
                     $status = all_in_one_invite_codes_get_status( get_the_ID() );
                     $code = get_post_meta( get_the_ID(), 'tk_all_in_one_invite_code', true );
                     if ( empty( $email ) && $status == 'Active' ) {
                         $active_codes[] ='<li>'.__("Code: ","tk_all_in_one_invite_code"). $code.'</li>';
                     }elseif ( !empty( $email ) && $status == 'Active'){

                         $pending_codes[] ='<li>'.__("Code: ","tk_all_in_one_invite_code"). $code. '<p> <b>'.__( 'Invite was sent to: ', 'all_in_one_invite_codes' ) . $email.'</b></p></li>' ;
                     }
                     elseif (!empty( $email ) && $status == 'Used'){
                         $used_codes[] ='<li>'.__("Code: ","tk_all_in_one_invite_code"). $code. '<p><b>'.__( 'Invite was sent to: ', 'all_in_one_invite_codes' ) . $email.'</b></p></li>' ;
                     }

                 endwhile;
             }
             //if title is present
             If ( ! empty ( $title ) )
                 Echo $args['before_title'] . $cuser->display_name.' : '.$title . $args['after_title'];
          echo '<div id="tabs">
              <ul>
                <li><a href="#tabs-1">'.__("Active","all_in_one_invite_codes").'</a></li>
                <li><a href="#tabs-2">'.__("Pending","all_in_one_invite_codes").'</a></li>
                <li><a href="#tabs-3">'.__("Used","all_in_one_invite_codes").'</a></li>
              </ul>
              <div id="tabs-1">'.implode("",$active_codes).'</div>
              <div id="tabs-2">'.implode("",$pending_codes) .'</div>
              <div id="tabs-3">'.implode("",$used_codes).' </div>
            </div>';
                echo ' <script>
             jQuery( function() {
                 jQuery( "#tabs" ).tabs();
             } );
               </script>';
       }else{
             If ( ! empty ( $title ) )
                 Echo $args['before_title'] .$title . $args['after_title'];

             echo '<a href="'.wp_login_url().'">'.__('Please log in','all_in_one_invite_codes').'</a>' ;
         }


        echo $args['after_widget'];
     }

     public function form( $instance ) {
         if ( isset( $instance[ 'title' ] ) )
             $title = $instance[ 'title' ];
         else
             $title = __( 'Invites Codes', 'all_in_one_invite_codes' );
         ?>
         <p>
             <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
             <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
         </p>
         <?php
     }





}