<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access).
/**
 * 부모에서 include 될 때 `$list`, `$config`가 설정됨 (실행에는 문제 없음).
 *
 * @var array<int, array<string, mixed>> $list
 * @var array<string, mixed> $config
 */
    $config = isset( $config ) && is_array( $config ) ? $config : $this->get_config();
    $shadow_style = '';

    if ( $this->is_set( $config[ 'shadow' ][ 'use' ] ) === 'Y' ) {
        $shadow_x       = intVal( $this->is_set( $config[ 'shadow' ][ 'x' ], 5 ) );
        $shadow_y       = intVal( $this->is_set( $config[ 'shadow' ][ 'y' ], 5 ) );
        $shadow_size    = intVal( $this->is_set( $config[ 'shadow' ][ 'size' ], 15 ) );
        $shadow_color   = $this->is_set( $config[ 'shadow' ][ 'color' ], '#000000' );
        $shadow_opacity = is_numeric( $config[ 'shadow' ][ 'opacity' ] ) ? (float) $config[ 'shadow' ][ 'opacity' ] : 0.2;

        $shadow_style = 'box-shadow:' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_size . 'px ' . $this->hex_to_rgba( $shadow_color, $shadow_opacity ) . ';';
    }

    $overlay_color   = $this->is_set( $config[ 'overlay' ][ 'color' ], '#000000' );
    $overlay_opacity = is_numeric( $config[ 'overlay' ][ 'opacity' ] ) ? ( (float) $config[ 'overlay' ][ 'opacity' ] / 100 ) : 0.6;
    // 오버레이가 투명(0)이면 안 보이는 채로 클릭까지 가로채면 안 되므로 뒤쪽 사이트로 마우스 이벤트를 흘려보낸다.
    $overlay_style   = 'background-color:' . $overlay_color . ';opacity:' . $overlay_opacity . ';' . ( $overlay_opacity <= 0 ? 'pointer-events:none;' : '' );

    $notoday_bg_style = 'background:' . $this->is_set( $config[ 'notoday_background' ], '#000000' ) . ';';
?>
<!-- 팝업 시작 -->
<div id="goyo_popup_container" class="mobile">
    <div id="goyo_popup_overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
    <div id="goyo_popup_playground" class="goyo_popup_playground">
        <?php
        /** @var array<int, array<string, mixed>> $list */
        foreach ( $list as $popup ) :
            ?>
            <?php
                $popup_id   = $this->is_set( $popup[ 'id' ] );
                $popup_width = intVal( $this->is_set( $popup[ 'options' ][ 'width' ], 0 ) );
                $popup_height = intVal( $this->is_set( $popup[ 'options' ][ 'height' ], 0 ) );
                $popup_total_height = ( $popup_height > 0 ? $popup_height + 41 : 0 );
                $image_style = array();
                if ( $popup_width > 0 ) {
                    $image_style[] = 'width:' . $popup_width . 'px';
                }
                if ( $popup_height > 0 ) {
                    $image_style[] = 'height:' . $popup_height . 'px';
                }
                if ( $popup_width > 0 && $popup_height > 0 ) {
                    // 520px 이하 뷰포트에서 폭을 320px로 강제 축소할 때, 지정된 비율 그대로 세로도 함께 줄어들게 함
                    $image_style[] = 'aspect-ratio:' . $popup_width . '/' . $popup_height;
                }
                $content_style_attr = ( count( $image_style ) > 0 ? 'style="' . implode( ';', $image_style ) . '"' : '' );
                $image_src  = $this->get_image_src( $popup[ 'image' ], 'full' );
                $letina_src = $this->get_image_src( $popup[ 'letina' ], 'full' );
                $target     = ( $this->is_set( $popup[ 'target' ], 'Y' ) == 'Y' ? 'target="_blank" rel="noopener noreferrer"' : '' );
                $text_title = trim( $this->is_set( $popup[ 'options' ][ 'text_title' ], '' ) );
                $text_content = trim( $this->is_set( $popup[ 'options' ][ 'text_content' ], '' ) );
                $text_background_color = $this->is_set( $popup[ 'options' ][ 'text_background_color' ], '#667FFF' );
                $text_border_color = $this->is_set( $popup[ 'options' ][ 'text_border_color' ], '#A2DBFF' );
                $text_border_width = intVal( $this->is_set( $popup[ 'options' ][ 'text_border_width' ], 10 ) );
                if ( $text_border_width < 0 ) {
                    $text_border_width = 0;
                }
                $text_title_color = $this->is_set( $popup[ 'options' ][ 'text_title_color' ], '#ffffff' );
                $text_content_color = $this->is_set( $popup[ 'options' ][ 'text_content_color' ], '#ffffff' );
                $text_box_style_attr = 'style="background:' . $text_background_color . ';border:' . $text_border_width . 'px solid ' . $text_border_color . ( count( $image_style ) > 0 ? ';' . implode( ';', $image_style ) : '' ) . '"';
                $text_title_style_attr = 'style="color:' . $text_title_color . ';"';
                $text_content_style_attr = 'style="color:' . $text_content_color . ';"';
                $is_text_only = ( empty( $image_src ) && ( ! empty( $text_title ) || ! empty( $text_content ) ) );
            ?>

            <article id="goyo_popup_<?php echo $popup_id; ?>" class="goyo_popup_item<?php echo ( $is_text_only ? ' goyo_popup_item_text_only' : '' ); ?>" data-popup-width="<?php echo $popup_width; ?>" data-popup-height="<?php echo $popup_total_height; ?>" <?php echo ( $shadow_style !== '' ? 'style="' . $shadow_style . '"' : '' ); ?>>
                <div class="goyo_popup_item_content">
                    <?php if ( $is_text_only ) : ?>
                        <?php if ( $this->is_set( $popup[ 'url' ] ) ) : ?>
                            <a href="<?php echo esc_url( $this->is_set( $popup[ 'url' ] ) ); ?>" <?php echo $target; ?> class="goyo_popup_text_only_box" <?php echo $text_box_style_attr; ?>>
                                <?php if ( ! empty( $text_title ) ) : ?><h4 <?php echo $text_title_style_attr; ?>><?php echo wp_kses_post( $text_title ); ?></h4><?php endif; ?>
                                <?php if ( ! empty( $text_content ) ) : ?><p <?php echo $text_content_style_attr; ?>><?php echo wp_kses_post( $text_content ); ?></p><?php endif; ?>
                            </a>
                        <?php else : ?>
                            <div class="goyo_popup_text_only_box" <?php echo $text_box_style_attr; ?>>
                                <?php if ( ! empty( $text_title ) ) : ?><h4 <?php echo $text_title_style_attr; ?>><?php echo wp_kses_post( $text_title ); ?></h4><?php endif; ?>
                                <?php if ( ! empty( $text_content ) ) : ?><p <?php echo $text_content_style_attr; ?>><?php echo wp_kses_post( $text_content ); ?></p><?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if ( $this->is_set( $popup[ 'url' ] ) ) : ?>
                            <a href="<?php echo esc_url( $this->is_set( $popup[ 'url' ] ) ); ?>" <?php echo $target; ?>>
                                <?php if ( $this->is_set( $popup[ 'letina' ] ) > 0 ) : ?>
                                    <img srcset="<?php echo $letina_src; ?> 2x" src="<?php echo $image_src; ?>" alt="POPUP <?php echo $popup_id; ?>" <?php echo ( count( $image_style ) > 0 ? 'style="' . implode( ';', $image_style ) . '"' : '' ); ?> />
                                <?php else : ?>
                                    <img src="<?php echo $image_src; ?>" alt="POPUP <?php echo $popup_id; ?>" <?php echo ( count( $image_style ) > 0 ? 'style="' . implode( ';', $image_style ) . '"' : '' ); ?> />
                                <?php endif; ?>
                            </a>
                        <?php else : ?>
                            <?php if ( $this->is_set( $popup[ 'letina' ] ) > 0 ) : ?>
                                <img srcset="<?php echo $letina_src; ?> 2x" src="<?php echo $image_src; ?>" alt="POPUP <?php echo $popup_id; ?>" <?php echo ( count( $image_style ) > 0 ? 'style="' . implode( ';', $image_style ) . '"' : '' ); ?> />
                            <?php else : ?>
                                <img src="<?php echo $image_src; ?>" alt="POPUP <?php echo $popup_id; ?>" <?php echo ( count( $image_style ) > 0 ? 'style="' . implode( ';', $image_style ) . '"' : '' ); ?> />
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div><!-- .goyo_popup_item_content -->
                <div class="goyo_popup_controller" style="<?php echo esc_attr( $notoday_bg_style ); ?>">
                    <a class="goyo_popup_notoday" href="#" ><?php _e( '오늘 하루 보지 않음', 'goyo-popup' ); ?></a>
                </div><!-- .goyo_popup_controller -->
                <a class="goyo_popup_close" href="#"><i ><?php _e( '닫기', 'goyo-popup' ); ?></i></a>
            </article><!-- .goyo_popup_item -->
        <?php endforeach; ?>
    </div><!-- .goyo_popup_playground -->
</div><!-- #goyo_popup_container -->
<!-- 팝업 종료 -->