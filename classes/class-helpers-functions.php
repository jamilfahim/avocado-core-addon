<?php
namespace Elementor;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
trait Bacola_Helper
{

    /*
     * List Categories
     */
    public function bacola_get_categories()
    {
        $terms = get_terms( 'category', array(
            'orderby'    => 'count',
            'hide_empty' => 0
        ) );
        $options = array();
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $options[ $term->term_id ] = $term->name;
            }
        }
        return $options;
    }
    /*
    * List Tags
    */
    public function bacola_get_tags()
    {
        $tags = get_tags();
        $options = array();
        if ( ! empty( $tags ) && ! is_wp_error( $tags ) ){
            foreach ( $tags as $tag ) {
                $options[ $tag->term_id ] = $tag->name;
            }
        }
        return $options;
    }
    /*
     * List Posts
     */
    public function bacola_get_posts() {
        $list = get_posts( array(
            'post_type'         => 'post',
            'posts_per_page'    => -1,
        ) );
        $options = array();
        if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
            foreach ( $list as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        }
        return $options;
    }
     /**
    * Get CPT post title (All product title)
    * @return array
    */
        public function fahim_cpt_get_post_title(){
            $lists = get_posts( array(
                'post_type'  => 'product',
                'posts_per_page'  => -1,
            ));
            $options =  array();
            if(!empty($lists)){
                foreach($lists as $post){
                    $options[$post->ID] = $post->post_title;
                }
            }
            return $options;
        }


    /**
    * Get All Post Types
    * @return array
    */
    public function bacola_get_post_types()
    {
        $bacola_cpts = get_post_types(array('public' => true, 'show_in_nav_menus' => true), 'object');
        $post_types = array_merge($bacola_cpts);
        foreach ($post_types as $type) {
            $types[$type->name] = $type->label;
        }
        return $types;
    }
    /**
    * Get CPT Taxonomies
    * @return array
    */
    public function fahim_cpt_taxonomies(){
        $terms = get_terms('product_cat');
        $options = array();
        if(!empty($terms)){
            foreach( $terms as $term ){
                $options[$term->term_id] = $term->name;
            }
        }
        
        return $options;
    }


    /**
    * Get WooCommerce Attributes
    * @return array
    */
    public function bacola_woo_attributes()
    {
        $options = array();
        if ( class_exists( 'WooCommerce' ) ) {
            global $product;
            $terms = wc_get_attribute_taxonomies();
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $options[$term->attribute_name] = $term->attribute_label;
                }
            }
        }
        return $options;
    }
    /**
    * Get WooCommerce Attributes Taxonomies
    * @return array
    */
    public function bacola_woo_attributes_taxonomies()
    {
        $options = array();
        if ( class_exists( 'WooCommerce' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            foreach ($attribute_taxonomies as $tax) {
                $terms = get_terms( 'pa_'.$tax->attribute_name, 'orderby=name&hide_empty=0' );
                foreach ($terms as $term) {
                    $options[$term->name] = $term->name;
                }
            }
        }
        return $options;
    }
    /**
    * Get WooCommerce Product Skus
    * @return array
    */
    public function bacola_woo_get_skus()
    {
        $options = array();
        if ( class_exists( 'WooCommerce' ) ) {
            $args = array(
                'post_type' => 'product', 
                'posts_per_page' => -1
            );
            
            $wcProductsArray = get_posts($args);
            
            if (count($wcProductsArray)) {
                foreach ($wcProductsArray as $productPost) {
                    $productSKU = get_post_meta($productPost->ID, '_sku', true);
                    $options[$productSKU] = $productSKU;
                }
            }
        }
        return $options;
    }
   
	
}