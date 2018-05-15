<?php
   class prism {
       public function __construct(){
          add_action( 'wp', array( $this, 'singular_post' ) );
       }
       public function singular_post() {
          if ( is_singular() || is_page('blog') ) {
               add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_prism_scripts' ), 2 );
               add_filter( 'the_content', array( $this, 'filter_html') );
               add_filter( 'the_excerpt', array( $this, 'filter_html') );
          }
       }
       public function enqueue_prism_scripts(){
          wp_enqueue_script( 'prismjs', get_template_directory_uri() . '/assets/js/prism.js', '', '1.14.0', true );
          wp_enqueue_style( 'prismcss', get_template_directory_uri() . '/assets/css/prism.css', array(), '1.14.0', 'all' );
       }
       private function _call_back( $matches ) {
          if (version_compare(PHP_VERSION, '5.2.3') >= 0) {
              $output = htmlspecialchars($matches[2], ENT_NOQUOTES, get_bloginfo('charset'), false);
          } else {
              // Define html characters
              $special_chars = array(
                '&' => '&amp;',
                '<' => '&lt;',
                '>' => '&gt;'
              );
              $data = htmlspecialchars_decode( $matches[2] );
              $output = strtr($data, $special_chars);
          }
          if ( !empty( $output ) ) {
               return $matches[1] . $output . $matches[3];
          } else {
               return $matches[1] . $matches[2] . $matches[3];
          }
       }
       public function filter_html( $content ) {
          $content = preg_replace_callback('@(<code.*>)(.*)(<\/code>)@isU', array( $this, '_call_back' ), $content );
          return $content;
       }

   }
   $prism = new prism;
?>