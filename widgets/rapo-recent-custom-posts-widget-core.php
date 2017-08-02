<?php 

class Rapo_Recent_Custom_Posts_Widget extends WP_Widget {
 
	/**
	 * Sets up a new Rapo Recent Custom Posts Widget instance.
	 *
	 * @access public
	 */
	function __construct() {
		$rapo_rcp_widget = array('classname' => 'Rapo_Recent_Custom_Posts_Widget', 'description' => __( "Recent Posts by post type/custom post type") );
		parent::__construct('Rapo_Recent_Custom_Posts_Widget', __('Rapo Recent Custom Posts Widget'), $rapo_rcp_widget);
		$this->alt_option_name = 'rapo_rcp_widget';
	}
 
	/**
	 * Outputs the content for the current Rapo Recent Custom Posts Widget instance.
	 *
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
 
		$title = ( ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' ));
 
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
 
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
		if ( ! $number )
			$number = 10;
		$posttype = (( ! empty( $instance['posttype'] ) ) ? $instance['posttype'] :  'posts' );
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false;	
				
		/**
		 * Filter the arguments for the Recent Custom Posts widget.
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$rapo_rcpw = new WP_Query( apply_filters( 'widget_posts_args', array(
			'post_status'         => 'publish',
			'posts_per_page'      => $number,
			'post_type'			  => $posttype,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
			'orderby' => 'post_date',
			'order' => 'DESC',
		) ) );

		if ($rapo_rcpw->have_posts()) :
			$thumbnail_colors = array( "Red","Green","Blue","Yellow");
		?>
		<?php echo $args['before_widget']; ?>
		
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul class="rapo-rcpw-wrapper">
		<?php $index=0; while ( $rapo_rcpw->have_posts() ) : $rapo_rcpw->the_post();  ?>
			<?php  if ($index %2 == 0) :?>
				<li>
				<?php  endif; ?>
				<div class="rapo-rcpw-item">
					<div class="rapo-rcpw-thumbnail">
						<?php if (has_post_thumbnail()): ?>
							<?php the_post_thumbnail('thumbnail'); ?>
						<?php else: ?>
							<img src="<?php echo rapo_recent_custom_posts_widget_ASSETS ?>images/Thumbnail-Default-<?php echo $thumbnail_colors[rand(0, 3)]; ?>.png" alt="" />
						<?php endif; ?>
						</div><!-- .rapo-rcpw-thumbnail -->
					
					<div class="rapo-rcpw-content">
						<div class="rapo-rcpw-item-title">
						   <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
						</div><!-- .rapo-rcpw-item-title -->
					</div><!-- .rapo-rcpw-content -->
				
				</div><!-- .rapo-rcpw-item -->
				<?php $index++; if ($index %2 == 0) :?> 
			</li><?php endif; ?>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as it would be overwritten
		wp_reset_postdata();
 
		endif;
	}
 
	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['posttype'] = sanitize_text_field( $new_instance['posttype']); 
		return $instance;
	}
 
	/**
	 * Outputs the settings form
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 10;
		$posttype = ! empty( $instance['posttype'] ) ? $instance['posttype'] : esc_html__( 'posts', 'rapo-rcpw' );
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
 
		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'posttype' ); ?>"><?php _e( 'Select Post Type:' ); ?></label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'posttype' ) ); ?>">
			<?php 
				$p_types = get_post_types(array('public'=> true), 'names', 'and' );
				foreach($p_types as $p_type){
					echo '<option value="',$p_type,'"',($p_type==$posttype)?'selected':'','>',$p_type,'</option>';
				}
			?>
		</select>
		</p>

		
<?php
	}
}

 ?>
