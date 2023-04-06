<?php

namespace Elementor;

class Fahim_Product_Grid_Widget extends Widget_Base {
    use Bacola_Helper;

    public function get_name() {
        return 'bacola-product-grid';
    }
    public function get_title() {
        return 'Product Grid (K)';
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
	
        $this->add_control( 'cat_filter',
            [
                'label' => esc_html__( 'Filter Category', 'bacola-core' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->fahim_cpt_taxonomies(),
                'description' => 'Select Category(s)',
                'default' => '',
				'label_block' => true,
            ]
        );
        $this->add_control(
			'columns',
			[
				'label' => esc_html__( 'Colums', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'1'  => esc_html__( '1 columns ', 'plugin-name' ),
					'2' => esc_html__( '2 columns', 'plugin-name' ),
					'3' => esc_html__( '3 columns', 'plugin-name' ),
					'4' => esc_html__( '4 columns', 'plugin-name' ),
                    '5' => esc_html__( '5 columns', 'plugin-name' ),
                    '6' => esc_html__( '6 columns', 'plugin-name' ),
				
				],
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

        if($settings['cat_filter']){
        $args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $settings['cat_filter'],
                ),
            ),
        );
    }

    if($settings['columns'] == '4') {
        $columns_markup = 'col-4';
    }else if($settings['columns'] == '3') {
        $columns_markup = 'col-3';
    }else if($settings['columns'] == '2') {
        $columns_markup = 'col-2';
    }else {
        $columns_markup = 'col';
    }
    $output = '<div class="'.$columns_markup.'">';
    foreach( $settings['cat_filter'] as $cat) {
        $thumbnail_id = get_woocommerce_term_meta( $cat, 'thumbnail_id', true );
        $image = wp_get_attachment_url( $thumbnail_id, 'medium' );
        $info = get_term($cat,'product_cat');
      
        $output .= '<div class="cat">
        <a href="">
            <div class="cat-img">
                 <img src=" '.$image.'" alt="">
            </div>
            <div class="cat-title">
                <h3> '.$info->name.'</h3>
            </div>
            <div class="cat-description">
                 <p> '.$info->description.'</p>
            </div>
        </a>
          </div>';
    }
    $output .= '</div>';	
	
		wp_reset_query();
		echo $output;
	}

}
