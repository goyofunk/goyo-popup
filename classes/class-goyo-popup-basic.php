<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access).

if ( ! class_exists( 'GOYO_Popup_Basic' ) ) {
    class GOYO_Popup_Basic {
        protected function get_config( $is_default = false ) {
            $default = array(
                'style'     => 'type1',
                'position'  => array(
                                'type'      => 'center',
                                'left'      => array( 'value' => 50, 'unit' => 'px' ),
                                'top'       => array( 'value' => 50, 'unit' => 'px' ),
                            ),
                'notoday_background' => '#000000',
                'shadow'    => array(
                                'use'       => 'Y',
                                'x'         => 5,
                                'y'         => 5,
                                'size'      => 25,
                                'color'     => '#000000',
                                'opacity'   => 0.2,
                            ),
                'overlay'   => array(
                                'color'     => '#000000',
                                'opacity'   => 0,
                            ),
                'mobile'    => array(
                                'default'   => '#ffffff',
                                'active'    => '#00bcb4',
                                'close'     => '#ffffff'

                            ),
            );

            if ( $is_default ) {
                return $default;
            }

            $config = get_option( 'goyopopup_config' ) ?: array();

            return $this->esc_attr( $this->array_recursive( $default, $config ) );
        }

        protected function get_list( $type = 'publish', $flag = '' ) {
            global $wpdb;

            $table = $this->table();
            $str_now = $this->now( 'Y-m-d H:i:s' );
            $arr_where = array();

            $arr_where[] = $wpdb->prepare( ' ( del_flag = %s ) ', 'N' );

            if ( $type == 'pending' ) {
                $arr_where[] = $wpdb->prepare( ' ( use_flag = %s AND always_flag = %s AND start_date > %s ) ', 'Y', 'N', $str_now );
            } elseif ( $type == 'close' ) {
                $arr_where[] = $wpdb->prepare( ' ( use_flag = %s OR ( always_flag = %s AND ( %s > end_date OR end_date IS NULL ) ) ) ', 'N', 'N', $str_now );
            } else {
                $arr_where[] = $wpdb->prepare( ' ( use_flag = %s AND ( always_flag = %s OR ( %s BETWEEN start_date AND end_date ) ) ) ', 'Y', 'Y', $str_now );
            }

            if ( $flag == 'mobile' ) {
                $arr_where[] = $wpdb->prepare( ' ( platform = %s OR platform = %s ) ', 'A', 'M' );
            } elseif ( $flag == 'pc' ) {
                $arr_where[] = $wpdb->prepare( ' ( platform = %s OR platform = %s ) ', 'A', 'P' );
            }

            $sort = ( $this->is_mobile() || is_admin() ? ' sort ASC, id DESC ' : ' sort DESC, id ASC ' );
            $sql = " SELECT id FROM {$table} WHERE " . implode( ' AND ', $arr_where ) . " ORDER BY {$sort} ";
            $res = $wpdb->get_results( $sql, ARRAY_A );
            $data = array();

            if ( count( $res ) > 0 ) {
                foreach ( $res as $row ) {
                    if ( $this->is_set( $row['id'] ) ) {
                        $popup = $this->get_item( $row['id'] );

                        if ( $this->is_set( $popup ) ) {
                            $data[] = $popup;
                        }
                    }
                }
            }

            return $data;
        }

        protected function get_item( $id = 0 ) {
            global $wpdb;

            $table = $this->table();
            $res = null;

            if ( $id > 0 ) {
                // 테이블 컬럼명은 소문자 id (Linux/MySQL 환경에서 대소문자 구분 시 오류 방지)
                $sql = $wpdb->prepare( " SELECT * FROM {$table} WHERE id = %d ", $id );
                $res = $wpdb->get_row( $sql, ARRAY_A );
            }

            // 행이 없으면 신규 폼용 기본값만 반환 (null 접근 방지)
            if ( ! is_array( $res ) ) {
                return array(
                    'id'            => 0,
                    'image'         => 0,
                    'image_src'     => '',
                    'letina'        => 0,
                    'letina_src'    => '',
                    'use_flag'      => 'Y',
                    'platform'      => 'A',
                    'always_flag'   => 'Y',
                    'start_date'    => '',
                    'end_date'      => '',
                    'url'           => '',
                    'target'        => 'Y',
                    'post_ids'      => array(),
                    'options'       => array(
                        'top'                   => 0,
                        'left'                  => 0,
                        'width'                 => 0,
                        'height'                => 0,
                        'text_title'            => '',
                        'text_content'          => '',
                        'text_background_color' => '#667FFF',
                        'text_border_color'     => '#A2DBFF',
                        'text_border_width'     => 10,
                        'text_title_color'      => '#ffffff',
                        'text_content_color'    => '#ffffff',
                    ),
                    'sort'          => 0,
                );
            }

            $raw_options = isset( $res['options'] ) ? $res['options'] : '';
            $tmp_options = maybe_unserialize( $raw_options );
            if ( ! is_array( $tmp_options ) ) {
                $tmp_options = array();
            }
            $data = array(
                'id'            => $this->is_set( $res['id'], 0 ),
                'image'         => $this->is_set( $res['image'], 0 ),
                'image_src'     => $this->get_image_src( $res['image'], 'full' ),
                'letina'        => $this->is_set( $res['letina'], 0 ),
                'letina_src'    => $this->get_image_src( $res['letina'], 'full' ),
                'use_flag'      => $this->is_set( $res['use_flag'], 'Y' ),
                'platform'      => $this->is_set( $res['platform'], 'A' ),
                'always_flag'   => $this->is_set( $res['always_flag'], 'Y' ),
                'start_date'    => ( $this->is_set( $res['start_date'] ) ? date( 'Y-m-d H:i', strtotime( $res['start_date'] ) ) : '' ),
                'end_date'      => ( $this->is_set( $res['end_date'] ) ? date( 'Y-m-d H:i', strtotime( $res['end_date'] ) ) : '' ),
                'url'           => $this->is_set( $res['url'], '' ),
                'target'        => $this->is_set( $res['target'], 'Y' ),
                'post_ids'      => maybe_unserialize( isset( $res['post_ids'] ) ? $res['post_ids'] : '' ),
                'options'       => array(
                                    'top'           => $this->is_set( $tmp_options['top'], 0 ),
                                    'left'          => $this->is_set( $tmp_options['left'], 0 ),
                                    'width'         => $this->is_set( $tmp_options['width'], 0 ),
                                    'height'        => $this->is_set( $tmp_options['height'], 0 ),
                                    'text_title'    => htmlspecialchars_decode( htmlspecialchars_decode( $this->is_set( $tmp_options['text_title'], '' ), ENT_QUOTES ), ENT_QUOTES ),
                                    'text_content'  => htmlspecialchars_decode( htmlspecialchars_decode( $this->is_set( $tmp_options['text_content'], '' ), ENT_QUOTES ), ENT_QUOTES ),
                                    'text_background_color' => $this->is_set( $tmp_options['text_background_color'], '#667FFF' ),
                                    'text_border_color'     => $this->is_set( $tmp_options['text_border_color'], '#A2DBFF' ),
                                    'text_border_width'     => $this->is_set( $tmp_options['text_border_width'], 10 ),
                                    'text_title_color'      => $this->is_set( $tmp_options['text_title_color'], '#ffffff' ),
                                    'text_content_color'    => $this->is_set( $tmp_options['text_content_color'], '#ffffff' ),
                                ),
                'sort'          => $this->is_set( $res['sort'], 0 ),
            );

            if ( empty( $data['post_ids'] ) ) {
                $data['post_ids'] = array();
            } elseif ( ! is_array( $data['post_ids'] ) ) {
                $data['post_ids'] = array( $data['post_ids'] );
            }

            return $data;
        }


        // esc_attr 확장 함수( 배열 및 오브젝트 지원 )
        protected function esc_attr( $var ) {
            if ( is_string( $var ) || is_numeric( $var ) ) {
                return esc_attr( $var );
            } elseif ( empty( $var ) ) {
                return $var;
            } else {
                foreach ( $var as &$item ) {
                    $item = $this->esc_attr( $item );
                }

                return $var;
            }
        }

        // isset 확장 함수
        protected function is_set( &$var = null, $default = null ) {
            try {
                if ( ! isset( $var ) || empty( $var ) ) {
                    $var = $default;
                }

                return $var;
            } catch ( Exception $e ) {
                return null;
            }
        }

        // script console.log 대응 함수
        protected function console( $var, $var_name = '' ) {
            echo '<script>console.log( ' . ( $this->is_set( $var_name ) ? '"' . $var_name . '", ' : '' ) . json_encode( $var ) . ' );</script>';
        }

        // print_r, var_dump 확장 함수
        protected function debug( $var, $var_name = '', $show_type = false ) {
            echo '<pre>' . ( $this->is_set( $var_name ) ? $var_name . ' :: ' : '' );

            if ( $show_type ) {
                var_dump( $var );
            } else {
                print_r( $var );
            }

            echo '</pre>';
        }

        // ajax 여부
        protected function is_ajax() {
            if ( function_exists( 'wp_doing_ajax' ) ) {
                return wp_doing_ajax();
            }

            return defined( 'DOING_AJAX' ) && DOING_AJAX;
        }

        // mobile 여부
        protected function is_mobile() {
            return wp_is_mobile();
        }

        // Nonce Field
        protected function nonce( $name = '', $with_id = true ) {
            $nonce = wp_nonce_field( $name, $name, true, false );

            if ( ! $with_id ) {
                $nonce = preg_replace( '/id=\"\w+\"/', '', $nonce );
            }

            return $nonce;
        }

        // Check WP Nonce
        protected function check_nonce( $name = '' ) {
            if ( empty( $name ) || ! isset( $_REQUEST[ $name ] ) ) {
                return false;
            }
            return (bool) wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST[ $name ] ) ), $name );
        }

        // User IP
        protected function ip() {
            $ip = '';

            if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && filter_var( $_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP ) ) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif ( ! empty( $_SERVER['HTTP_X_SUCURI_CLIENTIP'] ) && filter_var( $_SERVER['HTTP_X_SUCURI_CLIENTIP'], FILTER_VALIDATE_IP ) ) {
                $ip = $_SERVER['HTTP_X_SUCURI_CLIENTIP'];
            } elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $ip = preg_replace( '/^(\d+\.\d+\.\d+\.\d+):\d+$/', '\1', $ip );

            return $ip;
        }

        protected function table() {
            global $wpdb;

            return self::table_name();
        }

        /** Reuse existing records; fresh installations use the canonical table. */
        public static function table_name() {
            global $wpdb;
            foreach ( array( 'goyo_bd_ux_popup', 'GOYO_Bd_UX_popup' ) as $suffix ) {
                $table = $wpdb->prefix . $suffix;
                if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) ) ) {
                    return $table;
                }
            }
            return $wpdb->prefix . GOYO_POPUP_TABLE;
        }

        protected function template_path( $type ) {
            $template = 'goyo-popup-' . $type . '.php';
            $theme_path = get_template_directory() . '/';
            if ( file_exists( $theme_path . $template ) ) {
                return $theme_path . $template;
            }
            // Preserve existing theme overrides.
            $legacy = $theme_path . 'bd-ux-popup-' . $type . '.php';
            return file_exists( $legacy ) ? $legacy : GOYO_POPUP_PATH . 'templates/' . $template;
        }

        protected function now( $format = 'Y-m-d H:i:s' ) {
            return date_i18n( $format );
        }

        protected function array_recursive( $array, $array1 ) {
            // handle the arguments, merge one by one
            $args = func_get_args();
            $array = $args[ 0 ];

            if ( ! is_array( $array ) ) {
              return $array;
            }

            for ( $i = 1; $i < count( $args ); $i++ ) {
                if ( is_array( $args[ $i ] ) ) {
                    if ( function_exists( 'array_replace_recursive' ) ) {
                        $array = array_replace_recursive( $array, $args[ $i ] );
                    } else {
                        $array = $this->_array_recursive( $array, $args[ $i ] );
                    }
                }
            }

            return $array;
        }

        private function _array_recursive( $array, $array1 ) {
            foreach ( $array1 as $key => $value ) {
                // create new key in $array, if it is empty or not an array
                if ( ! isset( $array[ $key ] ) || ( isset( $array[ $key ] ) && ! is_array( $array[ $key ] ) ) ) {
                    $array[ $key ] = array();
                }

                // overwrite the value in the base array
                if ( is_array( $value ) ) {
                  $value = $this->_array_recursive( $array[ $key ], $value );
                }

                $array[ $key ] = $value;
            }

            return $array;
        }


        // HEX 색상 + 투명도(0~1) -> "rgba(r, g, b, a)" 문자열 변환 (팝업 그림자 색상에 사용)
        protected function hex_to_rgba( $hex, $alpha = 1 ) {
            $hex = ltrim( (string) $hex, '#' );

            if ( strlen( $hex ) === 3 ) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }

            if ( ! preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
                $hex = '000000';
            }

            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
            $a = min( 1, max( 0, (float) $alpha ) );

            return "rgba({$r}, {$g}, {$b}, {$a})";
        }

        protected function get_image_src( $image_id = 0, $size = 'thumbnail' ) {
            try {
                if ( $image_id > 0 ) {
                    $image = wp_get_attachment_image_src( $image_id, $size );

                    return $this->is_set( $image[ 0 ], '' );
                } else {
                    return '';
                }
            } catch ( Exception $e ) {
                return '';
            }
        }

        /**
         * "오늘 하루 보지 않음" 쿠키가 활성화인지 확인합니다.
         * 쿠키 값은 사이트 로컬 일자(wp_date 기준 설정 ▸ 일반) Y-m-d 이어야 하며, 그 값이 현재 사이트 "오늘"과 같을 때만 숨깁니다.
         * 과거 레거시 값(today_none)은 서버에서 더 이상 막지 않습니다(브라우저 만료 또는 재클릭으로 이행).
         */
        protected function is_popup_cookie_dismiss_site_today( $popup_id ) {
            $popup_id = (int) $popup_id;

            if ( $popup_id <= 0 ) {
                return false;
            }

            $key = 'goyo_popup_' . $popup_id;

            if ( ! isset( $_COOKIE[ $key ] ) ) {
                return false;
            }

            $val = sanitize_text_field( wp_unslash( (string) $_COOKIE[ $key ] ) );

            if ( '' === $val || ! preg_match( '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $val ) ) {
                return false;
            }

            $today_site = function_exists( 'wp_date' ) ? wp_date( 'Y-m-d' ) : date_i18n( 'Y-m-d' );

            return ( $val === $today_site );
        }
    }
}