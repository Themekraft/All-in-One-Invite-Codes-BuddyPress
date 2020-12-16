
<h2 class="screen-heading general-settings-screen">
    <?php _e( 'Sent Invites', 'buddyboss' ); ?>
</h2>

    <p class="info invite-info">
        <?php _e( 'You have sent invitation emails to the following people:', 'buddyboss' ); ?>
    </p>
    <table class="invite-settings bp-tables-user" id="<?php echo esc_attr( 'member-invites-table' ); ?>">
        <thead>
        <tr>
            <th class="title"><?php esc_html_e( 'Email', 'buddyboss' ); ?></th>
            <th class="title"><?php esc_html_e( 'Codes', 'buddyboss' ); ?></th>
            <th class="title"><?php esc_html_e( 'Invited', 'buddyboss' ); ?></th>
            <th class="title"><?php esc_html_e( 'Status', 'buddyboss' ); ?></th>
        </tr>
        </thead>

        <tbody>

        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $sent_invites_pagination_count = apply_filters( 'sent_invites_pagination_count', 25 );
        $args = array(
            'posts_per_page' => $sent_invites_pagination_count,
            'post_type'      => 'tk_invite_codes',
            'author'         => get_current_user_id(),
            'paged'          => $paged,
        );
        $the_query = new WP_Query( $args );

        if($the_query->have_posts()) {

            while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
            <?php
                $current_id = get_the_ID();
                $all_in_one_invite_codes_options = get_post_meta($current_id,'all_in_one_invite_codes_options',true);
                $email          = isset( $all_in_one_invite_codes_options['email'] ) ? $all_in_one_invite_codes_options['email'] : '';
                $generate_codes = isset( $all_in_one_invite_codes_options['generate_codes'] ) ? $all_in_one_invite_codes_options['generate_codes'] : '';
                $type           = isset( $all_in_one_invite_codes_options['type'] ) ? $all_in_one_invite_codes_options['type'] : 'any';
                if(!empty($email)){
                ?>

                <tr>
                    <td class="field-name">
                        <span><?php echo $email; ?></span>
                    </td>
                    <td class="field-email">
                        <span><?php echo $generate_codes; ?></span>
                    </td>
                    <td class="field-email">
					<span>
						<?php
                        $date = get_the_date( '',get_the_ID() );
                        echo $date;
                        ?>
					</span>
                    </td>
                    <td class="field-email">
                        <span><?php echo get_post_meta( get_the_ID(), 'tk_all_in_one_invite_code_status', true ); ?></span>
                    </td>

                </tr>
            <?php }
            endwhile;

        } else {
            ?>
            <tr>
                <td colspan="4" class="field-name">
                    <span><?php esc_html_e( 'You haven\'t sent any invitations yet.', 'buddyboss' ); ?></span>
                </td>
            </tr>
            <?php
        }

        $total_pages = $the_query->max_num_pages;

        if ( $total_pages > 1 ){

            $current_page = max(1, get_query_var('paged'));

            echo paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '?paged=%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text'    => __('« Prev', 'buddyboss'),
                'next_text'    => __('Next »', 'buddyboss'),
            ));
        }

        wp_reset_postdata();
        ?>

        </tbody>
    </table>