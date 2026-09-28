<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access)

// 호출 규격: 관리 클래스에서 `$config = $this->get_config()` 후 include 함. 분석기·직접 include 대비 폴백.
if ( ! isset( $config ) || ! is_array( $config ) ) {
    $config = $this->get_config();
}

?>

<div id="goyo_popup" class="goyo_popup_config wrap">
    <div class="goyo_popup_head">
        <a href="<?php echo admin_url( 'admin.php?page=goyo-popup' ); ?>" class="goyopopup_head_btn btn_register"><span><?php echo esc_html__( '팝업 리스트', 'goyo-popup' ); ?></span></a>
        <div class="goyo_popup_head_inner">
            <h1 class="goyo_popup_title" lang="en">POPUP </h1>
        </div>
    </div><!-- .goyo_popup_head -->
    <div class="goyo_popup_contents">
        <form class="goyopopup_config_form">
            <?php echo $this->nonce( 'goyopopup_config_form' ); ?>
            <input type="hidden" name="action" value="goyopopup_action" />

            <div class="goyo_popup_list_box">
                <h2><span><?php echo esc_html__( '팝업 위치 지정', 'goyo-popup' ); ?></span></h2>
                <div class="goyo_config_field">
                    <label class="goyo_icheck_label">
                        <input type="radio" name="goyopopup[position][type]" class="goyo_icheck goyo_position_type" value="center" <?php checked( $config[ 'position' ][ 'type' ] != 'left', true ); ?> />
                        <span><?php echo esc_html__( '센터', 'goyo-popup' ); ?></span>
                    </label>
                    <label class="goyo_icheck_label">
                        <input type="radio" name="goyopopup[position][type]" class="goyo_icheck goyo_position_type" value="left" <?php checked( $config[ 'position' ][ 'type' ], 'left' ); ?> />
                        <span><?php echo esc_html__( '왼쪽', 'goyo-popup' ); ?></span>
                    </label>
                </div><!-- .goyo_config_field -->

                <div class="goyo_config_grid_2 goyo_position_offset_fields<?php echo ( $config[ 'position' ][ 'type' ] == 'left' ? ' active' : '' ); ?>">
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( '왼쪽', 'goyo-popup' ); ?> <span class="goyo_config_field_hint"><?php echo esc_html__( '[ 기본값 50px ]', 'goyo-popup' ); ?></span></h3>
                        <div class="goyo_position_offset_input">
                            <label class="goyo_label">
                                <input type="number" name="goyopopup[position][left][value]" class="goyo_form_field" placeholder="50" value="<?php echo esc_attr( $config[ 'position' ][ 'left' ][ 'value' ] ); ?>" />
                            </label>
                            <select name="goyopopup[position][left][unit]" class="goyo_form_field goyo_unit_select">
                                <option value="px" <?php selected( $config[ 'position' ][ 'left' ][ 'unit' ], 'px' ); ?>>px</option>
                                <option value="%" <?php selected( $config[ 'position' ][ 'left' ][ 'unit' ], '%' ); ?>>%</option>
                            </select>
                        </div><!-- .goyo_position_offset_input -->
                    </div><!-- .goyo_config_field -->
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( '위', 'goyo-popup' ); ?> <span class="goyo_config_field_hint"><?php echo esc_html__( '[ 기본값 50px ]', 'goyo-popup' ); ?></span></h3>
                        <div class="goyo_position_offset_input">
                            <label class="goyo_label">
                                <input type="number" name="goyopopup[position][top][value]" class="goyo_form_field" placeholder="50" value="<?php echo esc_attr( $config[ 'position' ][ 'top' ][ 'value' ] ); ?>" />
                            </label>
                            <select name="goyopopup[position][top][unit]" class="goyo_form_field goyo_unit_select">
                                <option value="px" <?php selected( $config[ 'position' ][ 'top' ][ 'unit' ], 'px' ); ?>>px</option>
                                <option value="%" <?php selected( $config[ 'position' ][ 'top' ][ 'unit' ], '%' ); ?>>%</option>
                            </select>
                        </div><!-- .goyo_position_offset_input -->
                    </div><!-- .goyo_config_field -->
                </div><!-- .goyo_position_offset_fields -->
                <p class="goyo_position_note"><?php echo esc_html__( '뷰포트 너비 820px 이하에서는 이 설정과 관계없이 항상 화면 중앙에 표시됩니다.', 'goyo-popup' ); ?></p>
            </div><!-- .goyo_popup_list_box -->

            <div class="goyo_popup_list_box">
                <h2><span><?php echo esc_html__( '오늘하루 보지 않음 배경 색상', 'goyo-popup' ); ?></span></h2>
                <div class="goyo_config_field">
                    <label class="goyo_label">
                        <input type="text" name="goyopopup[notoday_background]" class="goyo_form_field colorpicker" value="<?php echo esc_attr( $config[ 'notoday_background' ] ); ?>" />
                    </label>
                </div><!-- .goyo_config_field -->
            </div><!-- .goyo_popup_list_box -->

            <div class="goyo_popup_list_box">
                <h2><span><?php echo esc_html__( '팝업 그림자 조정', 'goyo-popup' ); ?></span></h2>
                <div class="goyo_config_field">
                    <label class="switch_wrap">
                        <input type="checkbox" name="goyopopup[shadow][use]" class="switch_input" value="Y" <?php checked( $config[ 'shadow' ][ 'use' ], 'Y' ); ?> />
                        <div class="switch"></div>
                    </label>
                    <span class="switch_label_txt"><?php echo esc_html__( '그림자 활성화', 'goyo-popup' ); ?></span>
                </div><!-- .goyo_config_field -->

                <div class="goyo_shadow_grid">
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( 'X축', 'goyo-popup' ); ?> <span class="goyo_config_field_hint"><?php echo esc_html__( '[ 기본값 5 ]', 'goyo-popup' ); ?></span></h3>
                        <label class="goyo_label">
                            <input type="number" name="goyopopup[shadow][x]" class="goyo_form_field" placeholder="5" min="1" max="30" value="<?php printf( '%d', $config[ 'shadow' ][ 'x' ] ); ?>" />
                            <b>px</b>
                        </label>
                    </div><!-- .goyo_config_field -->
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( 'Y축', 'goyo-popup' ); ?> <span class="goyo_config_field_hint"><?php echo esc_html__( '[ 기본값 5 ]', 'goyo-popup' ); ?></span></h3>
                        <label class="goyo_label">
                            <input type="number" name="goyopopup[shadow][y]" class="goyo_form_field" placeholder="5" min="1" max="30" value="<?php printf( '%d', $config[ 'shadow' ][ 'y' ] ); ?>" />
                            <b>px</b>
                        </label>
                    </div><!-- .goyo_config_field -->
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( '그림자 크기', 'goyo-popup' ); ?> <span class="goyo_config_field_hint"><?php echo esc_html__( '[ 기본값 25 ]', 'goyo-popup' ); ?></span></h3>
                        <label class="goyo_label">
                            <input type="number" name="goyopopup[shadow][size]" class="goyo_form_field" placeholder="25" min="1" max="50" value="<?php printf( '%d', $config[ 'shadow' ][ 'size' ] ); ?>" />
                            <b>px</b>
                        </label>
                    </div><!-- .goyo_config_field -->
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( '그림자 색상', 'goyo-popup' ); ?></h3>
                        <label class="goyo_label">
                            <input type="text" name="goyopopup[shadow][color]" class="goyo_form_field colorpicker" value="<?php echo $config[ 'shadow' ][ 'color' ]; ?>" />
                        </label>
                    </div><!-- .goyo_config_field -->
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( '그림자 투명도', 'goyo-popup' ); ?> <span class="goyo_config_field_hint"><?php echo esc_html__( '[ 기본값 0.2 ]', 'goyo-popup' ); ?></span></h3>
                        <div class="goyo_range_wrap goyo_range_shadow_opacity">
                            <input type="hidden" name="goyopopup[shadow][opacity]" value="<?php echo esc_attr( $config[ 'shadow' ][ 'opacity' ] ); ?>" />
                            <input type="range" class="goyo_range_native" min="0" max="1" step="0.1" value="<?php echo esc_attr( $config[ 'shadow' ][ 'opacity' ] ); ?>" />
                            <span class="goyo_range_value"><?php echo esc_attr( $config[ 'shadow' ][ 'opacity' ] ); ?></span>
                        </div><!-- .goyo_range_wrap -->
                    </div><!-- .goyo_config_field -->
                </div><!-- .goyo_shadow_grid -->

                <div class="goyo_shadow_preview_wrap">
                    <p class="desc"><?php echo esc_html__( '그림자 미리보기', 'goyo-popup' ); ?></p>
                    <div class="goyo_shadow_preview_canvas">
                        <div class="shadow_box"></div>
                    </div>
                </div><!-- .goyo_shadow_preview_wrap -->
            </div><!-- .goyo_popup_list_box -->

            <div class="goyo_popup_list_box">
                <h2><span><?php echo esc_html__( '오버레이 영역 조정', 'goyo-popup' ); ?></span></h2>
                <div class="goyo_config_grid_2">
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( '색상 선택', 'goyo-popup' ); ?></h3>
                        <p class="desc"><?php echo esc_html__( '팝업이 표시될때 백그라운드 오버레이 색상을 선택합니다.', 'goyo-popup' ); ?></p>
                        <label class="goyo_label">
                            <input type="text" name="goyopopup[overlay][color]" class="goyo_form_field colorpicker" value="<?php echo $config[ 'overlay' ][ 'color' ]; ?>" />
                        </label>
                    </div><!-- .goyo_config_field -->
                    <div class="goyo_config_field">
                        <h3><?php echo esc_html__( '투명도 조정', 'goyo-popup' ); ?></h3>
                        <p class="desc"><?php echo esc_html__( '0 - 투명, 기본값 0, 100 - 불투명', 'goyo-popup' ); ?></p>
                        <div class="goyo_range_wrap goyo_range_overlay">
                            <input type="hidden" name="goyopopup[overlay][opacity]" value="<?php printf( '%d', $config[ 'overlay' ][ 'opacity' ] ); ?>" />
                            <input type="range" class="goyo_range_native" min="0" max="100" step="1" value="<?php printf( '%d', $config[ 'overlay' ][ 'opacity' ] ); ?>" />
                            <span class="goyo_range_value"><?php printf( '%d', $config[ 'overlay' ][ 'opacity' ] ); ?></span>
                        </div><!-- .goyo_range_wrap -->
                    </div><!-- .goyo_config_field -->
                </div><!-- .goyo_config_grid_2 -->
                <div class="goyo_popup_overlay_ex">
                    <iframe src="<?php echo esc_url( home_url( '/?goyo-nopopup=Y' ) ); ?>" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
                    <div class="overlay_box" style="background:<?php echo $config[ 'overlay' ][ 'color' ]; ?>;"></div>
                    <span class="overlay_txt"><?php echo esc_html__( '오버레이 영역', 'goyo-popup' ); ?></span>
                    <div class="overlay_img"><img src="<?php echo GOYO_POPUP_URL . '/img/popup-overlay-img.png'; ?>" srcset="<?php echo GOYO_POPUP_URL . '/img/popup-overlay-img-2x.png 2x'; ?>" alt=""></div>
                </div><!-- .goyo_popup_overlay_ex -->
            </div><!-- .goyo_popup_list_box -->

            <div class="goyo_config_footer">
                <button type="button" class="goyo_popup_btn_undo"><span><?php echo esc_html__( '설정 초기화', 'goyo-popup' ); ?></span></button>
                <div class="goyo_popup_btn_right">
                    <button type="button" class="goyo_popup_btn goyo_popup_btn_delete"><span><?php echo esc_html__( '취소하기', 'goyo-popup' ); ?></span></button>
                    <button class="goyo_popup_btn goyo_popup_btn_save"><span><?php echo esc_html__( '저장하기', 'goyo-popup' ); ?></span></button>
                </div>
            </div><!-- .goyo_config_footer -->
        </form>
    </div><!-- .goyo_popup_contents -->
</div><!-- #goyo_popup -->
