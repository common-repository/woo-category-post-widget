<?php

class WOOCP_Widget extends WP_Widget{

	public function __construct(){
		$widget_ops = array( 'classname' => 'woocpw_posts_entries', 'description' => __( 'Your site products.', 'woocommerce-category-post-widget' ) );
		parent::__construct( 'woocommerce-category-post-widget-posts', __( 'Woocommerce Category Post Widget', 'woocommerce-category-post-widget' ), $widget_ops );
		$this->alt_option_name = 'widget_woocommerce_category_post_widget_posts';
		add_action( 'wp_head', array($this,'public_scripts'));
	}

	public function widget($args,$instance){
		
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Woocommerce Category Post Widget', 'woocommerce-category-post-widget' ) : $instance['title'], $instance, $this->id_base );

		$posttype = 'product';
		
		if ( empty( $instance['number_of_post'] ) || ! $number = absint( $instance['number_of_post'] ) ) {
			$number = 5;
		}

		$show_post_date = isset( $instance['show_post_date'] ) ? $instance['show_post_date'] : false;
		
		$category=isset( $instance['category'] ) ? $instance['category']: '';
		
		$post_args=array(
			'post_type' => $posttype,
			'posts_per_page' => $number,
			'no_found_rows' => true,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
		);

		if(!empty($category) && $category!="all"){
			$post_args['tax_query']=array(
	            array(
	                'taxonomy' => 'product_cat',
	                'field' => 'id',
	                'terms' => $category
	            )
	        );
		}

		$r = new WP_Query( apply_filters( 'widget_posts_args', $post_args ) );

		if ( $r->have_posts() ) : ?>
			<?php echo $args['before_widget']; ?>
			<?php if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
			<ul class="woocpw-product-ul">
			<?php while ( $r->have_posts() ) : $r->the_post(); ?>
				<li>
					<?php if(has_post_thumbnail()){?>
						<div class="woocpw-thumbnail"><?php the_post_thumbnail(array(75,75));?></div>
					<?php }else{?>
						<div class="woocpw-thumbnail"><img src="<?php echo WOOCPW_ASSETS_URL;?>default.png"></div>
					<?php }?>
					<div class="half_title">
						<a href="<?php the_permalink() ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
						<?php if ( $show_post_date ) : ?>
							<span class="post-date"><?php echo get_the_date(); ?></span>
						<?php endif; ?>
					</div>
				</li>
			<?php endwhile; ?>
			</ul>
			<?php echo $args['after_widget']; ?>
			<?php
			wp_reset_postdata();
		endif;
		
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['number_of_post'] = (int) $new_instance['number_of_post'];
		$instance['show_post_date'] = isset( $new_instance['show_post_date'] ) ? (bool) $new_instance['show_post_date'] : false;
		return $instance;
	}

	public function form($instance){
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$categories=$this->get_all_product_category();
		$category=isset( $instance['category'] ) ? $instance['category']: 'all';
		$number_of_post= isset( $instance['number_of_post'] ) ? esc_attr( $instance['number_of_post'] ) : 5;
		$show_post_date = isset( $instance['show_post_date'] ) ? (bool) $instance['show_post_date'] : false;
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' );?>"><?php _e('Title:',WOOCPW_NAME);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('category');?>"><?php _e( 'Category:', WOOCPW_NAME);?></label>
				<select id="<?php echo $this->get_field_id('category');?>" name="<?php echo $this->get_field_name('category');?>" class="widefat">
					<option value="all" <?php echo selected($category,'all',true);?>>All</option>
					<?php if($categories && count($categories)>0){?>
						<?php foreach ($categories as $cat) {?>
							<option value="<?php echo $cat->term_id;?>" <?php echo selected($category,$cat->term_id,true);?>><?php echo $cat->name;?></option>
						<?php }?>
					<?php }?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'number_of_post' );?>"><?php _e('Number of products to show:',WOOCPW_NAME);?></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id('number_of_post');?>" name="<?php echo $this->get_field_name('number_of_post');?>" type="number" step="1" min="1" size="3" value="<?php echo $number_of_post; ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $show_post_date ); ?> id="<?php echo $this->get_field_id( 'show_post_date' ); ?>" name="<?php echo $this->get_field_name( 'show_post_date' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_post_date' ); ?>"><?php _e( 'Display product date?', WOOCPW_NAME ); ?></label></p>
		<?php
		
	}

	
	private function get_all_product_category(){
		$args = array(
			'taxonomy'     => 'product_cat',
		  	'orderby'      => 'name',
		  	'hierarchical' => true,
		  	'hide_empty'   => false
		);
		return get_categories( $args );
	}

	public function public_scripts(){
		wp_enqueue_style('woocpw-style',WOOCPW_ASSETS_URL.'css/woocpw.css');
	}


}