<?php

namespace Elementor;

class Fahim_Product_Card_Hover_Widget extends Widget_Base {
    use Bacola_Helper;

    public function get_name() {
        return 'product_card_hover';
    }
    public function get_title() {
        return 'Product Card Hover (K)';
    }
    public function get_icon() {
        return 'eicon-slider-push';
    }
    public function get_categories() {
        return [ 'fahim' ];
    }

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'bacola-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

       // Posts Per Page
       $this->add_control( 
          'post_count',
       [
           'label' => esc_html__( 'Posts Per Page', 'machic' ),
           'type' => Controls_Manager::NUMBER,
           'min' => 1,
           'max' => count( get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) ),
           'default' => 4
       ]
   );
      //Filter Product
        $this->add_control( 
           'Product_filter',
            [
                'label' => esc_html__( 'Filter Product', 'bacola-core' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->fahim_cpt_get_post_title(),
                'description' => 'Select Product(s)',
                'default' => '',
				    'label_block' => true,
            ]
        );
        //Nav Show/hide
        $this->add_control(
			'nav',
			[
				'label' => esc_html__( 'Nav Show/Hide', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'your-plugin' ),
				'label_off' => esc_html__( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
      //Dots Show/hide
        $this->add_control(
			'dots',
			[
				'label' => esc_html__( 'Dots Show/Hide', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'your-plugin' ),
				'label_off' => esc_html__( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
      //Autoplay On/Off
      $this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay On/Off', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'your-plugin' ),
				'label_off' => esc_html__( 'Off', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
       
		/*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
		
		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$target = $settings['btn_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['btn_link']['nofollow'] ? ' rel="nofollow"' : '';
		
		$output = '';

         $args = array(
            'post_type' => 'product',
            'posts_per_page' => $settings['post_count'],
         );

			query_posts( $args );
			
			
			$output ='<div class="product-hover-card">';
  if(have_posts()) : while(have_posts()) : the_post();
  global $product;
  global $post;
  global $woocommerce;
  $id = get_the_ID();
  $allproduct = wc_get_product( get_the_ID() );
  
  $att=get_post_thumbnail_id();
  $image_src = wp_get_attachment_image_src( $att, 'medium' );
  $image_src = $image_src[0];
  

  $cart_url = wc_get_cart_url();
  $price = $allproduct->get_price_html();
  $weight = $product->get_weight();
  $stock_status = $product->get_stock_status();
  $managestock = $product->managing_stock();
  $stock_text = $product->get_availability();
  $rating = wc_get_rating_html($product->get_average_rating());
  $ratingcount = $product->get_review_count();
  $wishlist = get_theme_mod( 'machic_wishlist_button', '0' );
  $compare = get_theme_mod( 'machic_compare_button', '0' );
  $sale_price_dates_to    = ( $date = get_post_meta( $id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y/m/d', $date ) : '';
  $total_sales = $product->get_total_sales();
  $stock_quantity = $product->get_stock_quantity();
  $rating      = $product->get_average_rating();
  $review_count = $product->get_review_count();

  $output .='';
  $output .='
	 <div class="single-hc-product">
		<div class="hc-product-base">
         <img src="'.esc_url($image_src).'">
         <span class="angel-down"><i class="bi bi-chevron-compact-down"></i></span>
		</div>
		<div class="hc-product-card">
               <div class="product-image"> <img src="'.esc_url($image_src).'"></div>
					<h3 class="product__title"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
					<p class="product__price">'.$price.'</p>
					<div class="product__cart">
					'.do_shortcode( '[add_to_cart  show_price="FALASE" id='.$id.']' ).'
				</div>			
		</div>
		
	 </div>';  
endwhile;
wp_reset_query();
endif;
  $output.='</div>';
		echo $output;
		
	}

}
