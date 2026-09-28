<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access).

if ( ! class_exists( 'GOYO_Popup_Admin' ) ) {
    class GOYO_Popup_Admin extends GOYO_Popup_Basic {
        // add_menu_page/add_submenu_page 가 반환하는 실제 훅 이름을 저장.
        // 상위 메뉴 타이틀이 한글('팝업창')이라 sanitize_title() 결과가 퍼센트 인코딩되어
        // 하위 메뉴 훅 이름이 'goyo-popup_page_...' 형태로 고정되지 않는다. 하드코딩 비교 금지.
        private $hook_list = '';
        private $hook_config = '';

        public static function init() {
            return new self();
        }

        public static function active() {
            global $wpdb;

            $table = self::table_name();
            $char = $wpdb->get_charset_collate();
            $sql = "    CREATE TABLE IF NOT EXISTS `{$table}` (
                            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '팝업 ID',
                            `image` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '기본 이미지',
                            `letina` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '2배 사이즈 이미지',
                            `use_flag` ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT '팝업 노출 여부',
                            `del_flag` ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT '팝업 삭제 여부',
                            `platform` ENUM('A','P','M') NOT NULL DEFAULT 'A' COMMENT '팝업 노출 위치 [A:모두,P:PC,M:MOBILE]',
                            `always_flag` ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT '팝업 게시기간 - 항상 노출',
                            `start_date` DATETIME NULL DEFAULT NULL COMMENT '팝업 게시기간 - 시작일',
                            `end_date` DATETIME NULL DEFAULT NULL COMMENT '팝업 게시기간 - 종료일',
                            `url` TEXT NULL COMMENT '팝업 링크',
                            `target` ENUM('Y','N') NULL DEFAULT 'Y' COMMENT '새창으로 링크 열기',
                            `post_ids` TEXT NULL COMMENT '팝업을 특정페이지에 노출(serialize)',
                            `options` TEXT NULL COMMENT '고급 설정(serialize)',
                            `sort` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '정렬순서 ASC',
                            PRIMARY KEY (`id`),
                            INDEX `use_flag` (`use_flag`),
                            INDEX `always_flag` (`always_flag`),
                            INDEX `start_date` (`start_date`),
                            INDEX `end_date` (`end_date`),
                            INDEX `del_flag` (`del_flag`),
                            INDEX `platform` (`platform`)
                        )
                        COMMENT='GOYO POPUP'
                        {$char}
                        ;
            ";

            $wpdb->query( $sql );
            
        }

        public static function uninstall() {
            global $wpdb;
            $table = self::table_name();
            $wpdb->query( "DROP TABLE IF EXISTS `{$table}`" );
            delete_option( 'goyopopup_config' );
        }


        public function __construct() {
            add_action( 'init', array( $this, 'plugins_load_textdomain' ) );

            add_action( 'admin_enqueue_scripts', array( $this, 'admin_style' ), 9999, 1 );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'wp_ajax_goyopopup_admin_form', array( $this, 'admin_form' ) );
            add_action( 'wp_ajax_goyopopup_preview', array( $this, 'admin_preview' ) );
            add_action( 'wp_ajax_goyopopup_action', array( $this, 'admin_action' ) );

            add_action( 'wp_ajax_goyopopup_get_post_list', array( $this, 'admin_get_post_list' ) );
        }


        public function plugins_load_textdomain() {
            load_plugin_textdomain( 'goyo-popup', false, GOYO_POPUP_BASENAME . '/languages/' );
        }


        private function script_messages() {
            return array(
                '10월' => __( '10월', 'goyo-popup' ),
                '11월' => __( '11월', 'goyo-popup' ),
                '12월' => __( '12월', 'goyo-popup' ),
                '1월' => __( '1월', 'goyo-popup' ),
                '2월' => __( '2월', 'goyo-popup' ),
                '3월' => __( '3월', 'goyo-popup' ),
                '4월' => __( '4월', 'goyo-popup' ),
                '5월' => __( '5월', 'goyo-popup' ),
                '6월' => __( '6월', 'goyo-popup' ),
                '7월' => __( '7월', 'goyo-popup' ),
                '8월' => __( '8월', 'goyo-popup' ),
                '9월' => __( '9월', 'goyo-popup' ),
                '게시 중지' => __( '게시 중지', 'goyo-popup' ),
                '금' => __( '금', 'goyo-popup' ),
                '기간종료' => __( '기간종료', 'goyo-popup' ),
                '기본 이미지를 선택하거나 텍스트를 입력해주세요.' => __( '기본 이미지를 선택하거나 텍스트를 입력해주세요.', 'goyo-popup' ),
                '내용 없음' => __( '내용 없음', 'goyo-popup' ),
                '년' => __( '년', 'goyo-popup' ),
                '다음 달' => __( '다음 달', 'goyo-popup' ),
                '닫기' => __( '닫기', 'goyo-popup' ),
                '목' => __( '목', 'goyo-popup' ),
                '삭제되었습니다' => __( '삭제되었습니다', 'goyo-popup' ),
                '설정을 저장하시겠습니까?' => __( '설정을 저장하시겠습니까?', 'goyo-popup' ),
                '설정을 초기화 하시겠습니까?' => __( '설정을 초기화 하시겠습니까?', 'goyo-popup' ),
                '설정을 취소하시겠습니까?' => __( '설정을 취소하시겠습니까?', 'goyo-popup' ),
                '설정이 저장되었습니다' => __( '설정이 저장되었습니다', 'goyo-popup' ),
                '수' => __( '수', 'goyo-popup' ),
                '시간' => __( '시간', 'goyo-popup' ),
                '월' => __( '월', 'goyo-popup' ),
                '이전 달' => __( '이전 달', 'goyo-popup' ),
                '일' => __( '일', 'goyo-popup' ),
                '잘못된 접근입니다.' => __( '잘못된 접근입니다.', 'goyo-popup' ),
                '저장 요청 처리 중 오류가 발생했습니다.' => __( '저장 요청 처리 중 오류가 발생했습니다.', 'goyo-popup' ),
                '저장 요청 처리 중 오류가 발생했습니다. 페이지를 새로고침한 뒤 다시 시도해 주세요.' => __( '저장 요청 처리 중 오류가 발생했습니다. 페이지를 새로고침한 뒤 다시 시도해 주세요.', 'goyo-popup' ),
                '저장되었습니다.' => __( '저장되었습니다.', 'goyo-popup' ),
                '저장에 실패했습니다.' => __( '저장에 실패했습니다.', 'goyo-popup' ),
                '제목 또는 내용을 입력해주세요.' => __( '제목 또는 내용을 입력해주세요.', 'goyo-popup' ),
                '제목 없음' => __( '제목 없음', 'goyo-popup' ),
                '토' => __( '토', 'goyo-popup' ),
                '팝업 게시기간을 설정해주세요.' => __( '팝업 게시기간을 설정해주세요.', 'goyo-popup' ),
                '팝업 노출 위치를 선택해주세요.' => __( '팝업 노출 위치를 선택해주세요.', 'goyo-popup' ),
                '팝업을 삭제하시겠습니까?' => __( '팝업을 삭제하시겠습니까?', 'goyo-popup' ),
                '팝업을 저장하시겠습니까?' => __( '팝업을 저장하시겠습니까?', 'goyo-popup' ),
                '항상 노출' => __( '항상 노출', 'goyo-popup' ),
                '현재' => __( '현재', 'goyo-popup' ),
                '화' => __( '화', 'goyo-popup' ),
            );
        }

        public function admin_style( $hook ) {
            if ( $hook == $this->hook_list ) {
                wp_enqueue_style( 'GOYOP-font', GOYO_POPUP_URL . '/css/font.css', array(), '1.0.0' );
                wp_enqueue_style( 'magnific-popup', GOYO_POPUP_URL . '/css/vendors/popup/magnific-popup.css', array(), '1.1.0');
                wp_enqueue_style( 'magnific-popup-motion', GOYO_POPUP_URL . '/css/vendors/popup/magnific-popup-motion.css', array(), '1.1.0');
                wp_enqueue_style( 'icheck', GOYO_POPUP_URL . '/css/vendors/icheck/minimal.css', array(), '1.1.0');
                wp_enqueue_style( 'jquery-ui-custom', GOYO_POPUP_URL . '/css/jquery-ui-1.9.2.custom.css', array(), '1.0.3' );
                wp_enqueue_style( 'jquery-datetimepicker', GOYO_POPUP_URL . '/css/jquery-ui-timepicker-addon.css', false, '1.0.0' );
                wp_enqueue_style( 'jquery-colorpicker', GOYO_POPUP_URL . '/css/jquery.minicolors.css', false, '1.0.0' );
                wp_enqueue_style( 'jquery-select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css', array(), '4.0.13' );
                wp_enqueue_style( 'GOYOP-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );
                wp_enqueue_style( 'GOYOP-popup-show', GOYO_POPUP_URL . '/css/goyo-popup-show.css', null, '1.1.7' );
                wp_enqueue_style( 'GOYOP-admin-style', GOYO_POPUP_URL . '/css/goyo-popup-admin.css', false, '1.2.1' );

                wp_enqueue_media();
                wp_enqueue_script( 'media-upload' );
                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-mouse' );
                wp_enqueue_script( 'jquery-ui-draggable' );
                wp_enqueue_script( 'jquery-ui-sortable' );
                wp_enqueue_script( 'jquery-ui-datepicker' );
                wp_enqueue_script( 'jQuery-ui-touch-punch', GOYO_POPUP_URL . '/js/jquery.ui.touch-punch.min.js', array( 'jquery', 'jquery-ui-draggable' ), '1.0.0', true );
                wp_enqueue_script( 'jQuery-datetimepicker', GOYO_POPUP_URL . '/js/jquery-ui-timepicker-addon.js', array( 'jquery', 'jquery-ui-datepicker' ), '1.0.0', true );
                wp_enqueue_script( 'jQuery-colorpicker', 'https://cdn.jsdelivr.net/npm/@claviska/jquery-minicolors@2.3.6/jquery.minicolors.min.js', array( 'jquery' ), '2.3.6', true );
                wp_enqueue_script( 'jQuery-serialize', GOYO_POPUP_URL . '/js/jquery.serialize-object.min.js', array( 'jquery' ), '1.0.0', true );

                wp_enqueue_script( 'magnific-popup', GOYO_POPUP_URL . '/js/vendors/popup/jquery.magnific-popup.js', array( 'jquery' ), '1.0.0', true );
                wp_enqueue_script( 'icheck', GOYO_POPUP_URL . '/js/vendors/icheck/icheck.min.js', array( 'jquery' ), '1.0.0', true );
                wp_enqueue_script( 'GOYOP-imageloaded', GOYO_POPUP_URL . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '3.1.8', true );
                wp_enqueue_script( 'jQuery-select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js', array( 'jquery' ), '4.0.13', true );
                wp_enqueue_script( 'GOYOP-admin-script', GOYO_POPUP_URL . '/js/goyo-popup-admin.js', array( 'jquery', 'jQuery-datetimepicker', 'jQuery-colorpicker', 'jQuery-serialize', 'magnific-popup', 'icheck', 'GOYOP-imageloaded', 'jQuery-select2' ), '1.1.1', true );

                wp_localize_script(
                    'GOYOP-admin-script',
                    'goyopopupAdmin',
                    array(
                        'messages' => $this->script_messages(),
                        'nonceAdminForm' => wp_create_nonce( 'goyopopup_admin_form' ),
                        'noncePreview'   => wp_create_nonce( 'goyopopup_preview' ),
                        'noncePostList'  => wp_create_nonce( 'goyopopup_get_post_list' ),
                    )
                );
            }

            if ( $hook == $this->hook_config ) {
                wp_enqueue_style( 'GOYOP-font', GOYO_POPUP_URL . '/css/font.css', array(), '1.0.0' );
                wp_enqueue_style( 'icheck', GOYO_POPUP_URL . '/css/vendors/icheck/minimal.css', array(), '1.1.0');
                wp_enqueue_style( 'jquery-colorpicker', GOYO_POPUP_URL . '/css/jquery.minicolors.css', false, '1.0.0' );
                wp_enqueue_style( 'GOYOP-admin-style', GOYO_POPUP_URL . '/css/goyo-popup-admin.css', false, '1.2.1' );

                wp_enqueue_script( 'jQuery-colorpicker', 'https://cdn.jsdelivr.net/npm/@claviska/jquery-minicolors@2.3.6/jquery.minicolors.min.js', array( 'jquery' ), '2.3.6', true );
                wp_enqueue_script( 'icheck', GOYO_POPUP_URL . '/js/vendors/icheck/icheck.min.js', array( 'jquery' ), '1.0.0', true );
                wp_enqueue_script( 'jQuery-serialize', GOYO_POPUP_URL . '/js/jquery.serialize-object.min.js', array( 'jquery' ), '1.0.0', true );
                wp_enqueue_script( 'GOYOP-admin-script', GOYO_POPUP_URL . '/js/goyo-popup-admin.js', array( 'jquery', 'jQuery-colorpicker' ), '1.1.1', true );

                wp_localize_script(
                    'GOYOP-admin-script',
                    'goyopopupAdmin',
                    array(
                        'messages' => $this->script_messages(),
                        'nonceAdminForm' => wp_create_nonce( 'goyopopup_admin_form' ),
                        'noncePreview'   => wp_create_nonce( 'goyopopup_preview' ),
                        'noncePostList'  => wp_create_nonce( 'goyopopup_get_post_list' ),
                    )
                );

                // 설정 초기화/취소 버튼에서 사용할 기본값·현재값 데이터
                wp_localize_script(
                    'GOYOP-admin-script',
                    'goyopopupAdminConfig',
                    array(
                        'defaultConfig' => $this->get_config( true ),
                        'currentConfig' => $this->get_config(),
                    )
                );
            }
        }

        public function admin_menu() {
            add_menu_page(
                __( '팝업창', 'goyo-popup' ),
                __( '팝업창', 'goyo-popup' ),
                'edit_posts',
                'goyo-popup',
                array( $this, 'admin_list' )
            );

            $this->hook_list = add_submenu_page(
                'goyo-popup',
                __( '팝업 리스트', 'goyo-popup' ),
                __( '팝업 리스트', 'goyo-popup' ),
                'edit_posts',
                'goyo-popup',
                array( $this, 'admin_list' )
            );

            $this->hook_config = add_submenu_page(
                'goyo-popup',
                __( '팝업 설정', 'goyo-popup' ),
                __( '팝업 설정', 'goyo-popup' ),
                'edit_posts',
                'goyo-popup-config',
                array( $this, 'admin_config_page' )
            );
        }

        public function admin_list() {
            $publish_list = $this->get_list( 'publish' );
            $pending_list = $this->get_list( 'pending' );
            $close_list = $this->get_list( 'close' );

            // 게시 예정에서 게시 중으로 올라온 애들의 정렬값을 재 정의
            // - 게시중     : 0++
            // - 게시예정   : 0--
            // - 게시중지   : 0++
            $this->resort();

            include GOYO_POPUP_PATH . 'pages/admin_list.php';
        }

        public function admin_config_page() {
            $config = $this->get_config();

            include GOYO_POPUP_PATH . 'pages/admin_config.php';
        }

        public function admin_form() {
            if ( ! current_user_can( 'edit_posts' ) ) {
                wp_die( esc_html__( '이 페이지를 볼 권한이 없습니다.', 'goyo-popup' ), '', array( 'response' => 403 ) );
            }
            check_ajax_referer( 'goyopopup_admin_form', 'nonce' );

            $goyop_id = $this->is_set( $_REQUEST['goyop_id'], 0 );
            $goyopopup = $this->get_item( $goyop_id );

            include GOYO_POPUP_PATH . 'pages/admin_form.php';
        }

        public function admin_preview() {
            if ( ! current_user_can( 'edit_posts' ) ) {
                wp_send_json_error( __( '이 작업을 할 권한이 없습니다.', 'goyo-popup' ) );
            }
            if ( ! check_ajax_referer( 'goyopopup_preview', 'nonce', false ) ) {
                wp_send_json_error( __( '보안 검증에 실패했습니다.', 'goyo-popup' ) );
            }
            try {
                $goyopopup_id = $this->is_set( $_REQUEST['goyopopup_id'], 0 );

                if ( $goyopopup_id > 0 ) {
                    $goyopopup = $this->get_item( $goyopopup_id );
                    $type = ( $this->is_mobile() ? 'mobile' : 'pc' );
                    $list = array( $goyopopup );
                    $config = $this->get_config();
                    $template_path = $this->template_path( $type );

                    ob_start();
                    include $template_path;
                    $output = ob_get_clean();

                    wp_send_json_success( $output );
                } else {
                    wp_send_json_error( __( '잘못된 접근입니다.', 'goyo-popup' ) );
                }
            } catch ( Exception $e ) {
                wp_send_json_error( $e->getMessage() );
            }
        }

        public function admin_action() {
            if ( $this->is_ajax() ) {
                if ( $this->check_nonce( 'goyopopup_form' ) ) {
                    try {
                        $data = $this->esc_attr( $this->is_set( $_POST['goyopopup'], array() ) );

                        // 텍스트 전용 제목/내용은 HTML 태그를 허용해야 하므로 엔티티 인코딩을 복원 후 허용 태그만 남긴다.
                        if ( isset( $data['options'] ) && is_array( $data['options'] ) ) {
                            if ( isset( $data['options']['text_title'] ) ) {
                                $decoded_title = htmlspecialchars_decode( htmlspecialchars_decode( $data['options']['text_title'], ENT_QUOTES ), ENT_QUOTES );
                                $data['options']['text_title'] = wp_kses_post( $decoded_title );
                            }
                            if ( isset( $data['options']['text_content'] ) ) {
                                $decoded_content = htmlspecialchars_decode( htmlspecialchars_decode( $data['options']['text_content'], ENT_QUOTES ), ENT_QUOTES );
                                $data['options']['text_content'] = wp_kses_post( $decoded_content );
                            }
                        }

                        // 체크박스 uncheck 처리 - 기본값 설정
                        if ( ! isset( $data['use_flag'] ) ) {
                            $data['use_flag'] = 'N';
                        }
                        if ( ! isset( $data['platform'] ) ) {
                            $data['platform'] = 'A';
                        }
                        if ( ! isset( $data['always_flag'] ) ) {
                            $data['always_flag'] = 'N';
                        }
                        if ( ! isset( $data['target'] ) ) {
                            $data['target'] = 'N';
                        }

                        if ( $data ) {
                            $popup_id = $this->is_set( $data['id'], 0 );

                            if ( ! $this->is_set( $data['image'] ) ) {
                                $text_options = $this->is_set( $data['options'], array() );
                                $text_title = trim( $this->is_set( $text_options['text_title'], '' ) );
                                $text_content = trim( $this->is_set( $text_options['text_content'], '' ) );

                                if ( empty( $text_title ) && empty( $text_content ) ) {
                                    wp_send_json_error( __( '기본 이미지를 선택하거나 텍스트를 입력해주세요.', 'goyo-popup' ) );
                                }
                            }

                            $res = $this->set_item( $data, $popup_id );

                            if ( $res ) {
                                wp_send_json_success();
                            } else {
                                wp_send_json_error( __( '저장 중 오류가 발생했습니다.', 'goyo-popup' ) );
                            }
                        } else {
                            wp_send_json_error( __( '잘못된 접근입니다.', 'goyo-popup' ) );
                        }
                    } catch ( Exception $e ) {
                        wp_send_json_error( $e->getMessage() );
                    }
                }

                if ( $this->check_nonce( 'goyopopup_config_form' ) ) {
                    try {
                        if ( $this->is_set( $_POST['goyopopup'] ) ) {
                            $data = $this->array_recursive( $this->get_config( true ), $this->esc_attr( $_POST['goyopopup'] ) );

                            update_option( 'goyopopup_config', $data );

                            wp_send_json_success( $data );
                        } else {
                            wp_send_json_error( __( '잘못된 접근입니다.', 'goyo-popup' ) );
                        }
                    } catch ( Exception $e ) {
                        wp_send_json_error( $e->getMessage() );
                    }
                }

                if ( $this->check_nonce( 'goyopopup_remove' ) ) {
                    try {
                        if ( $this->is_set( $_POST['goyopopup_id'] ) ) {
                            $popup_id = $this->esc_attr( $_POST['goyopopup_id'] );
                            $res = $this->remove_item( $popup_id );

                            if ( $res ) {
                                wp_send_json_success();
                            } else {
                                wp_send_json_error( __( '삭제 중 오류가 발생했습니다.', 'goyo-popup' ) );
                            }
                        } else {
                            wp_send_json_error( __( '잘못된 접근입니다.', 'goyo-popup' ) );
                        }
                    } catch ( Exception $e ) {
                        wp_send_json_error( $e->getMessage() );
                    }
                }

                if ( $this->check_nonce( 'goyopopup_sort' ) ) {
                    try {
                        if ( $this->is_set( $_POST['goyopopup_ids'] ) ) {
                            $popup_ids = ( array ) $this->esc_attr( $_POST['goyopopup_ids'] );
                            $type = $this->esc_attr( $_POST['type'] );
                            $res = array();

                            if ( is_array( $popup_ids ) && count( $popup_ids ) > 0 ) {
                                foreach ( $popup_ids as $idx => $popup_id ) {
                                    $popup = $this->get_item( $popup_id );

                                    $popup['sort'] = $idx;

                                    if ( $type == 'publish' ) {
                                        $check = true;
                                        $check = ( $check && ( $this->is_set( $popup['start_date'] ) && date( 'YmdHis' ) > date( 'YmdHis', strtotime( $popup['start_date'] ) ) ) );
                                        $check = ( $check && ( $this->is_set( $popup['end_date'] ) && date( 'YmdHis', strtotime( $popup['end_date'] ) ) > date( 'YmdHis' ) ) );
                                        $check = ( $check || $popup['always_flag'] == 'Y' );

                                        $popup['use_flag'] = 'Y';

                                        if ( ! $check ) {
                                            if ( $this->is_set( $popup['end_date'] ) && date( 'YmdHis' ) < date( 'YmdHis', strtotime( $popup['end_date'] ) ) ) {
                                                $popup['start_date'] = date( 'Y-m-d 00:00:00' );
                                            } else {
                                                $popup['always_flag'] = 'Y';
                                            }
                                        }
                                    } elseif ( $type == 'pending' ) {
                                        $popup['use_flag'] = 'Y';
                                        $popup['always_flag'] = 'N';

                                        if ( ! $this->is_set( $popup['start_date'] ) || date( 'YmdHis' ) > date( 'YmdHis', strtotime( $popup['start_date'] ) ) ) {
                                            $popup['start_date'] = date('Y-m-d 00:00:00', strtotime( '+1 day' ) );
                                        }

                                        if ( ! $this->is_set( $popup['end_date'] ) || date( 'YmdHis' ) > date( 'YmdHis', strtotime( $popup['end_date'] ) ) ) {
                                            $popup['end_date'] = date('Y-m-d 23:59:59', strtotime( '+1 day' ) );
                                        }

                                        // 게시예정인 애들은 자동으로 게시중으로 넘어갈때 앞에 위치하도록 정렬을 음수로 지정
                                        $popup['sort'] = 0 - count( $popup_ids ) + $idx;
                                    } else {
                                        $popup['use_flag'] = 'N';
                                    }

                                    $this->set_item( $popup, $popup_id );

                                    $res[] = $this->get_item( $popup_id );
                                }

                                wp_send_json_success( $res );
                            } else {
                                wp_send_json_error( __( '잘못된 접근입니다', 'goyo-popup' ) );
                            }
                        } else {
                            wp_send_json_error( __( '잘못된 접근입니다.', 'goyo-popup' ) );
                        }
                    } catch ( Exception $e ) {
                        wp_send_json_error( $e->getMessage() );
                    }
                }
            }

            if ( $this->is_ajax() ) {
                wp_send_json_error( array( 'message' => __( '보안 검증에 실패했습니다.', 'goyo-popup' ) ), 403 );
            }

            exit;
        }

        public function admin_get_post_list() {
            if ( ! $this->is_ajax() ) {
                return;
            }
            if ( ! current_user_can( 'edit_posts' ) ) {
                wp_send_json_error( array( 'message' => __( '이 작업을 할 권한이 없습니다.', 'goyo-popup' ) ), 403 );
            }
            if ( ! check_ajax_referer( 'goyopopup_get_post_list', 'nonce', false ) ) {
                wp_send_json_error( array( 'message' => __( '보안 검증에 실패했습니다.', 'goyo-popup' ) ), 403 );
            }

            $page = max( 1, absint( $this->is_set( $_GET['page'], 1 ) ) );
            $search = $this->esc_attr( $this->is_set( $_GET['search'], '' ) );

            $query = new WP_Query(
                    array(
                        'posts_per_page' => 20,
                        'post_status'    => 'publish',
                        'post_type'      => get_post_types(
                            array(
                                'public' => true,
                            )
                        ),
                        's'              => $search,
                        'paged'          => $page,
                    )
            );

            if ( $query->found_posts > 0 ) {

                $result = array_map( function ( $obj ) {
                    return array(
                        'id'    => $obj->ID,
                        'text'  => ! empty( $obj->post_title ) ? $obj->post_title : '[' . $obj->ID . '] UNTITLED',
                    );
                }, $query->posts );

                wp_send_json( array(
                    'results'       => $result,
                    'pagination'    => array(
                        'more'  => $query->max_num_pages > $page,
                    ),
                ) );
            } else {
                wp_send_json( array(
                    'results'       => array(),
                    'pagination'    => array(
                        'more'  => false,
                    ),
                ) );
            }

            exit;
        }

        private function set_item( $param = array(), $id = 0 ) {
            try {
                global $wpdb;

                $table = $this->table();
                $data = array(
                    'image'         => $this->is_set( $param['image'], 0 ),
                    'letina'        => $this->is_set( $param['letina'], 0 ),
                    'use_flag'      => $this->is_set( $param['use_flag'], 'Y' ),
                    'platform'      => $this->is_set( $param['platform'], 'A' ),
                    'always_flag'   => $this->is_set( $param['always_flag'], 'Y' ),
                    'start_date'    => ( $this->is_set( $param['start_date'] ) ? date( 'Y-m-d H:i:00', strtotime( $param['start_date'] ) ) : null ),
                    'end_date'      => ( $this->is_set( $param['end_date'] ) ? date( 'Y-m-d H:i:59', strtotime( $param['end_date'] ) ) : null ),
                    'url'           => esc_url_raw( (string) $this->is_set( $param['url'], '' ) ),
                    'target'        => $this->is_set( $param['target'], 'Y' ),
                    'post_ids'      => serialize( $this->is_set( $param['post_ids'], null ) ),
                    'options'       => serialize( $this->is_set( $param['options'], null ) ),
                    'sort'          => $this->is_set( $param['sort'], 0 ),
                );

                $res = false;

                if ( $id > 0 ) {
                    $res = $wpdb->update( $table, $data, array( 'id' => $id ) );
                } else {
                    $res = $wpdb->insert( $table, $data );
                }

                if ( $res === false ) {
                    return false;
                } else {
                    return ( $id > 0 ? $id : $wpdb->insert_id );
                }
            } catch ( Exception $e ) {
                throw $e;
            }
        }

        private function remove_item( $id = 0 ) {
            global $wpdb;

            $table = $this->table();

            if ( $id > 0 ) {
                return $wpdb->update( $table, array( 'del_flag' => 'Y' ), array( 'id' => $id ) );
            }

            return false;
        }

        private function resort() {
            $publish_list = $this->get_list( 'publish' );

            if ( count( $publish_list ) > 0 ) {
                foreach ( $publish_list as $idx => $tmp ) {
                    $tmp['sort'] = $idx;

                    $this->set_item( $tmp, $tmp['id'] );
                }
            }

            $pending_list = $this->get_list( 'pending' );

            if ( count( $pending_list ) > 0 ) {
                foreach ( $pending_list as $idx => $tmp ) {
                    $tmp['sort'] = 0 - count( $pending_list ) + $idx;

                    $this->set_item( $tmp, $tmp['id'] );
                }
            }

            $close_list = $this->get_list( 'close' );

            if ( count( $close_list ) > 0 ) {
                foreach ( $close_list as $idx => $tmp ) {
                    $tmp['sort'] = $idx;

                    $this->set_item( $tmp, $tmp['id'] );
                }
            }
        }
    }
}

if ( method_exists( 'GOYO_Popup_Admin', 'init' ) ) GOYO_Popup_Admin::init();
