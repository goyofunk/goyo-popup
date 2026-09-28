<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access).

if ( ! class_exists( 'GOYO_Popup_Front' ) ) {
    class GOYO_Popup_Front extends GOYO_Popup_Basic {
        public static function init() {
            return new self();
        }

        public function __construct() {

            add_filter( 'body_class', array( $this, 'body_class' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'rest_api_init', array( $this, 'register_routes' ) );

            add_action( 'wp_ajax_goyo_popup', array( $this, 'ajax_popup_action' ) );
            add_action( 'wp_ajax_nopriv_goyo_popup', array( $this, 'ajax_popup_action' ) );
            // Accept requests from cached pages generated before the rename.
            add_action( 'wp_ajax_GOYO_Bd_UX_popup', array( $this, 'ajax_popup_action' ) );
            add_action( 'wp_ajax_nopriv_GOYO_Bd_UX_popup', array( $this, 'ajax_popup_action' ) );
        }

        public function body_class( $classes ) {
            if ( wp_is_mobile() ) { // Add Mobile class
                if ( ! in_array( 'mobile', $classes ) ) $classes[] = ' mobile';
            } else { // Add Desktop class
                if ( ! in_array( 'desktop', $classes ) ) $classes[] = ' desktop';
            }

            return $classes;
        }

        public function show() {
            $type = ( $this->is_mobile() ? 'mobile' : 'pc' );
            $list = $this->get_list( 'publish', $type );
            $config = $this->get_config();

            if ( ! ( is_home() || is_front_page() ) ) {
                $list = array_filter( $list, function ( $arr ) {
                    return ! $this->is_popup_cookie_dismiss_site_today( $arr['id'] ) && in_array( get_the_ID(), $arr['post_ids'] );
                } );
            } else {
                $list = array_filter( $list, function ( $arr ) {
                    return ! $this->is_popup_cookie_dismiss_site_today( $arr['id'] ) && empty( $arr['post_ids'] );
                } );
            }

            if ( $this->is_set( $_REQUEST['goyo-nopopup'] ) == 'Y' ) {
                $list = array();
            }

            if ( count( $list ) > 0 ) {
                wp_enqueue_style( 'GOYOP-popup-show', GOYO_POPUP_URL . '/css/goyo-popup-show.css', null, '1.1.7' );
                wp_enqueue_style( 'GOYOP-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );

                wp_enqueue_script( 'jquery-ui-draggable' );
                wp_enqueue_script( 'GOYOP-jquery-cookie', GOYO_POPUP_URL . '/js/jquery.cookie.js', array( 'jquery' ), '1.4.0', true );
                wp_enqueue_script( 'GOYOP-imageloaded', GOYO_POPUP_URL . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '3.1.8', true );
                wp_enqueue_script( 'GOYOP-show-script', GOYO_POPUP_URL . '/js/goyo-popup-show.js', array( 'jquery', 'GOYOP-jquery-cookie' ), '1.1.15', true );

                wp_localize_script( 'GOYOP-show-script', '__GOYOP', $this->get_frontend_script_data() );

                $template_path = $this->template_path( $type );

                ob_start();
                include $template_path;
                $output = ob_get_clean();

                echo $output;
            }
        }


        public function enqueue_scripts() {
            // 활성화된 팝업이 있는지 확인
            $type = ( $this->is_mobile() ? 'mobile' : 'pc' );
            $list = $this->get_list( 'publish', $type );

            // 현재 페이지에 표시될 팝업이 있는지 필터링
            if ( ! ( is_home() || is_front_page() ) ) {
                $list = array_filter( $list, function ( $arr ) {
                    return ! $this->is_popup_cookie_dismiss_site_today( $arr['id'] ) && in_array( get_the_ID(), $arr['post_ids'] );
                } );
            } else {
                $list = array_filter( $list, function ( $arr ) {
                    return ! $this->is_popup_cookie_dismiss_site_today( $arr['id'] ) && empty( $arr['post_ids'] );
                } );
            }
            if ( $this->is_set( $_REQUEST['goyo-nopopup'] ) == 'Y' ) {
                $list = array();
            }

            // 활성화된 팝업이 있을 때만 파일 로드
            if ( count( $list ) > 0 ) {
                wp_enqueue_style( 'GOYOP-popup-show', GOYO_POPUP_URL . '/css/goyo-popup-show.css', null, '1.1.7' );
                wp_enqueue_style( 'GOYOP-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );

                wp_enqueue_script( 'jquery-ui-draggable' );
                wp_enqueue_script( 'GOYOP-jquery-cookie', GOYO_POPUP_URL . '/js/jquery.cookie.js', array( 'jquery' ), '1.4.0', true );
                wp_enqueue_script( 'GOYOP-imageloaded', GOYO_POPUP_URL . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '3.1.8', true );
                wp_enqueue_script( 'GOYOP-show-script', GOYO_POPUP_URL . '/js/goyo-popup-show.js', array( 'jquery', 'GOYOP-jquery-cookie' ), '1.1.15', true );

                wp_localize_script( 'GOYOP-show-script', '__GOYOP', $this->get_frontend_script_data() );
            }
        }


        /** 프론트 스크립트에 넘길 변수(설정 ▸ 일반 타임존 기준 로컬 날짜) */
        private function get_frontend_script_data() {
            $config = $this->get_config();

            return array(
                'home_url'             => get_home_url(),
                'blog_id'              => get_current_blog_id(),
                'language'             => get_locale(),
                'is_home'              => is_front_page() || is_home(),
                'site_calendar_date'    => function_exists( 'wp_date' ) ? wp_date( 'Y-m-d' ) : date_i18n( 'Y-m-d' ),
                'position'              => $this->is_set( $config[ 'position' ], array( 'type' => 'center' ) ),
            );
        }


        public function register_routes() {
            // The legacy namespace supports cached pages during upgrades.
            foreach ( array( 'goyo-popup', 'goyo-bd-ux-popup' ) as $namespace ) {
                register_rest_route(
                    $namespace,
                    '/list/',
                    array(
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => array( $this, 'popup_list' ),
                        'permission_callback' => '__return_true',
                    )
                );
            }
        }


        public function ajax_popup_action() {
            wp_send_json( $this->popup_list() );
            exit;
        }


        public function popup_list( $request = null ) {
            $type = $this->popup_list_request_param( $request, 'type', 'pc' );
            $type = in_array( $type, array( 'mobile', 'pc' ), true ) ? $type : 'pc';

            $location_raw = (string) $this->popup_list_request_param( $request, 'location', '' );
            $language_in = $this->popup_list_request_param( $request, 'language', '' );
            $language = str_replace( '-', '_', sanitize_text_field( is_string( $language_in ) ? $language_in : '' ) );

            $is_home_param = $this->popup_list_request_param( $request, 'is_home', '' );
            $is_home = ( $is_home_param === 'true' || $is_home_param === true );

            $blog_id = absint( $this->popup_list_request_param( $request, 'blog_id', 0 ) );

            $location_parts = ( $location_raw !== '' ) ? wp_parse_url( $location_raw ) : null;

            $post = null;
            if ( is_array( $location_parts ) && ! empty( $location_parts['path'] ) ) {
                $path_for_lookup = trim( $location_parts['path'], '/' );
                if ( $path_for_lookup !== '' ) {
                    $post = get_page_by_path( $path_for_lookup, OBJECT, get_post_types( array( 'public' => true ) ) );
                }
            }

            $switched_blog = false;
            if ( $blog_id > 0 && function_exists( 'switch_to_blog' ) ) {
                switch_to_blog( $blog_id );
                $switched_blog = true;
            }

            $switched_locale_ok = false;
            if ( function_exists( 'switch_to_locale' ) && $language !== '' ) {
                $switched_locale_ok = (bool) switch_to_locale( $language );
            }

            $result = null;

            try {
                $list = $this->get_list( 'publish', $type );
                $config = $this->get_config();

                if ( ! is_textdomain_loaded( 'goyo-popup' ) && $language !== '' ) {
                    $mo_file = sprintf( 'goyo-popup-%s.mo', $language );
                    $mo_path = path_join( path_join( GOYO_POPUP_PATH, 'languages' ), $mo_file );

                    if ( file_exists( $mo_path ) ) {
                        load_textdomain( 'goyo-popup', $mo_path );
                    }
                }

                if ( is_object( $post ) && isset( $post->ID ) && (int) $post->ID > 0 ) {
                    $list = array_filter(
                        $list,
                        function ( $arr ) use ( $post ) {
                            return ! $this->is_popup_cookie_dismiss_site_today( $arr['id'] ) && in_array( (int) $post->ID, array_map( 'intval', (array) $arr['post_ids'] ), true );
                        }
                    );
                } elseif ( $this->popup_list_is_home_context( $location_raw, $is_home ) ) {
                    $list = array_filter(
                        $list,
                        function ( $arr ) {
                            return ! $this->is_popup_cookie_dismiss_site_today( $arr['id'] ) && empty( $arr['post_ids'] );
                        }
                    );
                } else {
                    $list = array();
                }

                $nopopup = $this->popup_list_request_param( $request, 'goyo-nopopup', '' );
                if ( is_string( $nopopup ) && $nopopup === 'Y' ) {
                    $list = array();
                }

                if ( count( $list ) > 0 ) {
                    $template_path = $this->template_path( $type );

                    ob_start();
                    include $template_path;
                    $output = ob_get_clean();

                    $result = array( 'data' => $list, 'html' => $output );
                }
            } finally {
                if ( $switched_locale_ok && function_exists( 'restore_previous_locale' ) ) {
                    restore_previous_locale();
                }
                if ( $switched_blog ) {
                    restore_current_blog();
                }
            }

            return $result;
        }

        /** REST/API 공통: 요청에서 단일 파라미터 읽기 */
        private function popup_list_request_param( $request, $key, $default = '' ) {
            if ( $request instanceof WP_REST_Request ) {
                $val = $request->get_param( $key );
                if ( null !== $val && '' !== $val ) {
                    return $val;
                }
                return $default;
            }

            if ( isset( $_REQUEST[ $key ] ) ) {
                return wp_unslash( $_REQUEST[ $key ] );
            }

            return $default;
        }

        /** 메인·홈 URL과 현재 location 비교 */
        private function popup_list_is_home_context( $location_raw, $is_home_flag ) {
            if ( $is_home_flag ) {
                return true;
            }
            if ( $location_raw === '' ) {
                return false;
            }

            $normalized_request = untrailingslashit( $location_raw );
            $normalized_home    = untrailingslashit( home_url() );

            return ( $normalized_request === $normalized_home );
        }
    }
}

if ( method_exists( 'GOYO_Popup_Front', 'init' ) ) GOYO_Popup_Front::init();
