<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access) ?>

<!-- 새 팝업 등록하기 -->
<div id="goyo_popup_register" class="goyo_popup_register mfp-hide">
    <form class="goyo_popup_form" autocomplete="off">
        <?php echo $this->nonce( 'goyopopup_form' ); ?>
        <input type="hidden" name="action" value="goyopopup_action" />
        <input type="hidden" name="goyopopup[image]" value="<?php echo $this->is_set( $goyopopup[ 'image' ] ); ?>" />
        <input type="hidden" name="goyopopup[letina]" value="<?php echo $this->is_set( $goyopopup[ 'letina' ] ); ?>" />
        <input type="hidden" name="goyopopup[options][text_title]" value="<?php echo esc_attr( $this->is_set( $goyopopup[ 'options' ][ 'text_title' ] ) ); ?>" />
        <input type="hidden" name="goyopopup[options][text_content]" value="<?php echo esc_attr( $this->is_set( $goyopopup[ 'options' ][ 'text_content' ] ) ); ?>" />
        <input type="hidden" name="goyopopup[options][text_background_color]" value="<?php echo esc_attr( $this->is_set( $goyopopup[ 'options' ][ 'text_background_color' ], '#667FFF' ) ); ?>" />
        <input type="hidden" name="goyopopup[options][text_border_color]" value="<?php echo esc_attr( $this->is_set( $goyopopup[ 'options' ][ 'text_border_color' ], '#A2DBFF' ) ); ?>" />
        <input type="hidden" name="goyopopup[options][text_border_width]" value="<?php echo esc_attr( $this->is_set( $goyopopup[ 'options' ][ 'text_border_width' ], 10 ) ); ?>" />
        <input type="hidden" name="goyopopup[options][text_title_color]" value="<?php echo esc_attr( $this->is_set( $goyopopup[ 'options' ][ 'text_title_color' ], '#ffffff' ) ); ?>" />
        <input type="hidden" name="goyopopup[options][text_content_color]" value="<?php echo esc_attr( $this->is_set( $goyopopup[ 'options' ][ 'text_content_color' ], '#ffffff' ) ); ?>" />
        <input type="hidden" name="goyopopup[id]" value="<?php echo $this->is_set( $goyopopup[ 'id' ] ); ?>" />

        <div class="goyo_popup_register_head">
            <?php if ( $this->is_set( $goyopopup[ 'id' ] ) ) : ?>

                <h2><?php echo esc_html__( '팝업 수정하기', 'goyo-popup' ); ?></h2>

            <?php else : ?>

                <h2><?php echo esc_html__( '새 팝업 등록하기', 'goyo-popup' ); ?></h2>

            <?php endif; ?>
            <button class="btn_close mfp-close" type="button"><span class="sr_only"><?php echo esc_html__( '닫기', 'goyo-popup' ); ?></span></button>
        </div><!-- .goyo_popup_register_head -->
        <div class="goyo_popup_register_content">
            <div class="goyo_popup_register_img_wrap">
                <?php if ( $this->is_set( $goyopopup[ 'image' ] ) ) : ?>

                    <?php
                        $image_name = basename( $goyopopup[ 'image_src' ] );
                        $image_info = pathinfo( $goyopopup[ 'image_src' ] );
                        $image_name = ( mb_strlen( $image_name ) > 20 ? mb_substr( $image_info[ 'filename' ], 0, 15 ) . ' ... .' . $image_info[ 'extension' ] : $image_name );
                    ?>

                    <div class="goyo_popup_register_item required">
                        <h3><?php echo esc_html__( '기본 이미지 등록', 'goyo-popup' ); ?></h3>
                        <div class="goyo_popup_register_fig">
                            <figure class="goyo_popup_register_img"><img src="<?php echo $this->is_set( $goyopopup[ 'image_src' ] ); ?>" alt=""></figure>
                        </div><!-- .goyo_popup_register_fig -->
                        <p class="desc"><?php echo $image_name; ?></p>
                        <button type="button" class="btn_img_delete"><span><?php echo esc_html__( '삭제하기', 'goyo-popup' ); ?></span></button>
                    </div><!-- .goyo_popup_register_item -->

                <?php else : ?>

                    <div class="goyo_popup_register_item goyo_popup_register_img_add required">
                        <h3><?php echo esc_html__( '기본 이미지 등록', 'goyo-popup' ); ?></h3>
                    
                        <div class="goyo_popup_register_fig">
                            <button type="button" class="btn_img_add"><span><?php echo esc_html__( '이미지 추가', 'goyo-popup' ); ?></span></button>
                        </div><!-- .goyo_popup_register_fig -->
                    </div><!-- .goyo_popup_register_item -->

                <?php endif; ?>

                <?php if ( $this->is_set( $goyopopup[ 'letina' ] ) ) : ?>

                    <?php
                        $letina_name = basename( $goyopopup[ 'letina_src' ] );
                        $letina_info = pathinfo( $goyopopup[ 'letina_src' ] );
                        $letina_name = ( mb_strlen( $letina_name ) > 20 ? mb_substr( $letina_info[ 'filename' ], 0, 15 ) . ' ... .' . $letina_info[ 'extension' ] : $letina_name );
                    ?>

                    <div class="goyo_popup_register_item">
                        <h3><?php echo esc_html__( '2배', 'goyo-popup' ); ?> <span><?php echo esc_html__( '사이즈', 'goyo-popup' ); ?></span> <?php echo esc_html__( '이미지 등록', 'goyo-popup' ); ?></h3>
                        <div class="goyo_popup_register_fig">
                            <figure class="goyo_popup_register_img"><img src="<?php echo $this->is_set( $goyopopup[ 'letina_src' ] ); ?>" alt=""></figure>
                            <p class="sticker_2x"><span>2x</span></p>
                        </div><!-- .goyo_popup_register_fig -->
                        <p class="desc"><?php echo $letina_name; ?></p>
                        <button type="button" class="btn_img_delete"><span><?php echo esc_html__( '삭제하기', 'goyo-popup' ); ?></span></button>
                    </div><!-- .goyo_popup_register_item -->


                <?php else : ?>

                    <div class="goyo_popup_register_item goyo_popup_register_img_add">
                        <h3><?php echo esc_html__( '2배', 'goyo-popup' ); ?> <span><?php echo esc_html__( '사이즈', 'goyo-popup' ); ?></span> <?php echo esc_html__( '이미지 등록', 'goyo-popup' ); ?></h3>
                        <p><?php echo esc_html__( '선택사항 : Retina Display', 'goyo-popup' ); ?></p>
                        <div class="goyo_popup_register_fig">
                            <button type="button" class="btn_img_add"><span><?php echo esc_html__( '이미지 추가', 'goyo-popup' ); ?></span></button>
                        </div><!-- .goyo_popup_register_fig -->
                    </div><!-- .goyo_popup_register_item -->

                <?php endif; ?>

                <?php
                    $text_title = trim( $this->is_set( $goyopopup[ 'options' ][ 'text_title' ], '' ) );
                    $text_content = trim( $this->is_set( $goyopopup[ 'options' ][ 'text_content' ], '' ) );
                    $has_text_only = ( ! empty( $text_title ) || ! empty( $text_content ) );
                ?>
                <div class="goyo_popup_register_text_only_wrap">
                    <div class="goyo_popup_register_item goyo_popup_register_text_only_add">
                        <div class="goyo_popup_register_fig">
                            <button type="button" class="btn_text_only_open"><span><?php echo esc_html__( '텍스트만 넣기', 'goyo-popup' ); ?></span></button>
                        </div>
                    </div>
                    <p class="goyo_popup_register_text_only_desc <?php echo ( $has_text_only ? 'active' : '' ); ?>">
                        <?php if ( $has_text_only ) : ?>
                            <strong><?php echo ( ! empty( $text_title ) ? $text_title : __( '제목 없음', 'goyo-popup' ) ); ?></strong>
                            <span><?php echo ( ! empty( $text_content ) ? $text_content : __( '내용 없음', 'goyo-popup' ) ); ?></span>
                        <?php else : ?>
                            <strong></strong>
                            <span></span>
                        <?php endif; ?>
                    </p>
                    <div class="goyo_popup_register_text_only_actions <?php echo ( $has_text_only ? 'active' : '' ); ?>">
                        <button type="button" class="btn_text_only_edit"><?php echo esc_html__( '수정', 'goyo-popup' ); ?></button>
                        <button type="button" class="btn_text_only_delete"><?php echo esc_html__( '삭제', 'goyo-popup' ); ?></button>
                    </div>
                </div><!-- .goyo_popup_register_text_only_wrap -->
            </div><!-- .goyo_popup_register_img_wrap -->

            <div class="goyo_popup_register_setting">
                 <div class="goyo_popup_register_item required">
                    <h3><?php echo esc_html__( '팝업 노출 여부', 'goyo-popup' ); ?></h3>
                    <div class="goyo_popup_register_item_cont">
                        <label class="switch_wrap">
                            <input type="checkbox" name="goyopopup[use_flag]" class="switch_input" value="Y" <?php checked( $this->is_set( $goyopopup[ 'use_flag' ], 'Y' ), 'Y' ); ?> />
                            <div class="switch"></div>
                        </label>
                        <span class="switch_label_txt"><?php echo esc_html__( '노출 활성화', 'goyo-popup' ); ?></span>
                    </div><!-- .goyo_popup_register_item_cont -->
                </div><!-- .goyo_popup_register_item -->

                <div class="goyo_popup_register_item">
                    <h3><?php echo esc_html__( '팝업 사이즈', 'goyo-popup' ); ?></h3>
                    <div class="goyo_popup_register_item_cont">
                     
                        <div class="white_space_setting popup_size_setting">
                            <label class="goyo_label">
                                <span><?php echo esc_html__( '가로', 'goyo-popup' ); ?></span>
                                <input type="number" name="goyopopup[options][width]" class="goyo_form_field" min="0" step="1" placeholder="0" value="<?php printf( '%d', $this->is_set( $goyopopup[ 'options' ][ 'width' ] ) ); ?>" />
                                <b>px</b>
                            </label>
                            <label class="goyo_label">
                                <span><?php echo esc_html__( '세로', 'goyo-popup' ); ?></span>
                                <input type="number" name="goyopopup[options][height]" class="goyo_form_field" min="0" step="1" placeholder="0" value="<?php printf( '%d', $this->is_set( $goyopopup[ 'options' ][ 'height' ] ) ); ?>" />
                                <b>px</b>
                            </label>
                        </div><!-- .white_space_setting -->
                    </div><!-- .goyo_popup_register_item_cont -->
                </div><!-- .goyo_popup_register_item -->

                <div class="goyo_popup_register_item goyo_popup_register_position required">
                    <h3><?php echo esc_html__( '팝업 노출 위치', 'goyo-popup' ); ?></h3>
                    <div class="goyo_popup_register_item_cont">
                        <label class="goyo_icheck_label"><input type="radio" name="goyopopup[platform]" class="goyo_icheck" value="A" <?php checked( $this->is_set( $goyopopup[ 'platform' ], 'A' ), 'A' ); ?> /><span>ALL</span></label>
                        <label class="goyo_icheck_label"><input type="radio" name="goyopopup[platform]" class="goyo_icheck" value="P" <?php checked( $this->is_set( $goyopopup[ 'platform' ], 'A' ), 'P' ); ?> /><span>PC</span></label>
                        <label class="goyo_icheck_label"><input type="radio" name="goyopopup[platform]" class="goyo_icheck" value="M" <?php checked( $this->is_set( $goyopopup[ 'platform' ], 'A' ), 'M' ); ?> /><span>MOBILE</span></label>
                        <!--
                        <label class="goyo_icheck_label"><input type="checkbox" name="goyopopup[pc_flag]" class="goyo_icheck" value="Y" <?php checked( $this->is_set( $goyopopup[ 'pc_flag' ], 'Y' ), 'Y' ); ?> /><span>PC</span></label>
                        <label class="goyo_icheck_label"><input type="checkbox" name="goyopopup[mobile_flag]" class="goyo_icheck" value="Y" <?php checked( $this->is_set( $goyopopup[ 'mobile_flag' ], 'Y' ), 'Y' ); ?> /><span>Mobile</span></label>
                        -->
                    </div><!-- .goyo_popup_register_item_cont -->
                </div><!-- .goyo_popup_register_item -->

                <div class="goyo_popup_register_item required">
                    <h3><?php echo esc_html__( '팝업 게시기간 설정', 'goyo-popup' ); ?></h3>
                    <div class="goyo_popup_register_item_cont">
                        <label class="goyo_icheck_label"><input type="checkbox" name="goyopopup[always_flag]" class="goyo_icheck" value="Y" <?php checked( $this->is_set( $goyopopup[ 'always_flag' ], 'Y' ), 'Y' ); ?> /><span><?php echo esc_html__( '항상 노출', 'goyo-popup' ); ?></span></label>
                        <fieldset class="field_calendar_wrap">
                            <label class="goyo_label field_calendar <?php echo ( $goyopopup[ 'always_flag' ] == 'Y' ? 'disabled' : '' ); ?>">
                                <span class="sr_only"><?php echo esc_html__( '팝업 게시 시작 기간을 설정해주세요', 'goyo-popup' ); ?></span>
                                <input type="text" name="goyopopup[start_date]" class="goyo_form_field datetime_picker" value="<?php echo $this->is_set( $goyopopup[ 'start_date' ] ); ?>" <?php echo ( $goyopopup[ 'always_flag' ] == 'Y' ? 'disabled' : '' ); ?> readonly />
                            </label>
                            <span>~</span>
                            <label class="goyo_label field_calendar <?php echo ( $goyopopup[ 'always_flag' ] == 'Y' ? 'disabled' : '' ); ?>">
                                <span class="sr_only"><?php echo esc_html__( '팝업 게시 마감 기간을 설정해주세요', 'goyo-popup' ); ?></span>
                                <input type="text" name="goyopopup[end_date]" class="goyo_form_field datetime_picker" value="<?php echo $this->is_set( $goyopopup[ 'end_date' ] ); ?>" <?php echo ( $goyopopup[ 'always_flag' ] == 'Y' ? 'disabled' : '' ); ?> readonly />
                            </label>
                        </fieldset>
                    </div><!-- .goyo_popup_register_item_cont -->
                </div><!-- .goyo_popup_register_item -->

                <div class="goyo_popup_register_item goyo_popup_register_link">
                    <h3><?php echo esc_html__( '팝업 링크', 'goyo-popup' ); ?></h3>
                    <div class="goyo_popup_register_item_cont">
                        <p class="desc"><?php echo esc_html__( '팝업을 클릭했을 때 이동되는 링크 페이지 URL 주소를 입력해 주세요.  Ex) http://www..co.kr', 'goyo-popup' ); ?></p>
                        <label class="goyo_label goyo_label_link">
                            <input type="text" name="goyopopup[url]" class="goyo_form_field" placeholder="http://" value="<?php echo $this->is_set( $goyopopup[ 'url' ] ); ?>" />
                            <button type="button" class="url_clear" tabindex="-1"><span class="sr_only"><?php echo esc_html__( '취소', 'goyo-popup' ); ?></span></button>
                        </label>
                        <label class="goyo_icheck_label"><input type="checkbox" name="goyopopup[target]" class="goyo_icheck" value="Y" <?php checked( $this->is_set( $goyopopup[ 'target' ], 'Y' ), 'Y' ); ?> /><span><?php echo esc_html__( '새창으로 링크 열기', 'goyo-popup' ); ?></span></label>
                    </div><!-- .goyo_popup_register_item_cont -->
                </div><!-- .goyo_popup_register_item -->



                <div class="goyo_popup_register_item goyo_popup_register_page_exposure" style="display: none;">
                    <h3><?php echo esc_html__( '팝업을 특정페이지에 노출', 'goyo-popup' ); ?></h3>
                    <div class="goyo_popup_register_item_cont">
                        <p class="desc"><?php echo esc_html__( '현재 팝업을 노출 시킬 페이지를 검색하여 적용합니다.', 'goyo-popup' ); ?></p>
                        <div class="page_exposure_container">
                            <label class="goyo_label">
                                <select class="goyo_form_field page_exposure_select">
                                </select>
                            </label>
                        </div>
                        <ul class="page_exposure">
                            <?php if ( $this->is_set( $goyopopup[ 'post_ids' ] ) ) : ?>

                                <?php foreach ( $goyopopup[ 'post_ids' ] as $post_id ) : ?>

                                    <li>
                                        <input type="hidden" name="goyopopup[post_ids][]" value="<?php echo $post_id; ?>" />
                                        <span><?php echo get_the_title( $post_id ); ?></span>
                                        <button type="button" class="page_exposure_delete"></button>
                                    </li>

                                <?php endforeach; ?>

                            <?php endif; ?>
                        </ul>
                    </div><!-- .goyo_popup_register_item_cont -->
                </div><!-- .goyo_popup_register_item -->

                <div class="goyo_popup_register_item" style="display: none;">
                    <div class="goyo_accordion">
                        <div class="goyo_accordion_item">
                            <button class="goyo_accordion_title">
                                <h3><?php echo esc_html__( '고급설정', 'goyo-popup' ); ?></h3>
                                <span class="control"><i class="sr_only"><?php echo esc_html__( '펼치기/접기', 'goyo-popup' ); ?></i></span>
                            </button><!-- .goyo_accordion_title -->
                            <div class="goyo_accordion_content_wrap">
                                <div class="goyo_accordion_content">
                                    <div class="advance_item">
                                        <h4><?php echo esc_html__( '팝업 위치 조정', 'goyo-popup' ); ?></h4>
                                        <div class="advance_item_cont">
                                            <p class="desc"><?php echo esc_html__( '팝업이 하나일때, 여백값을 입력하지 않으면 화면 중앙에 위치합니다.', 'goyo-popup' ); ?></p>
                                            <div class="white_space_setting">
                                                <label class="goyo_label">
                                                    <span><?php echo esc_html__( '상단 여백', 'goyo-popup' ); ?></span>
                                                    <input type="number" name="goyopopup[options][top]" class="goyo_form_field" placeholder="0" value="<?php printf( '%d', $this->is_set( $goyopopup[ 'options' ][ 'top' ] ) ); ?>" />
                                                    <b>px</b>
                                                </label>
                                                <label class="goyo_label">
                                                    <span><?php echo esc_html__( '좌측 여백', 'goyo-popup' ); ?></span>
                                                    <input type="number" name="goyopopup[options][left]" class="goyo_form_field" placeholder="0" value="<?php printf( '%d', $this->is_set( $goyopopup[ 'options' ][ 'left' ] ) ); ?>" />
                                                    <b>px</b>
                                                </label>
                                            </div><!-- white_space_setting -->
                                            <figure class="popup_white_space_example"><img src="<?php echo GOYO_POPUP_URL . '/img/popup-white-space-example.jpg'; ?>" srcset="<?php echo GOYO_POPUP_URL . '/img/popup-white-space-example-2x.jpg 2x'; ?>" alt=""></figure>
                                        </div><!-- .advance_item_cont -->
                                    </div><!-- .advance_item -->

                                    <div class="advance_item_wrap">
                                        <div class="advance_item">
                                            <h4><?php echo esc_html__( '글자 색상 변경', 'goyo-popup' ); ?></h4>
                                            <div class="advance_item_cont">
                                                <p class="desc"><span><?php echo esc_html__( '오늘하루보지않음, X 닫기', 'goyo-popup' ); ?></span> <?php echo esc_html__( '글자 색상 변경', 'goyo-popup' ); ?></p>
                                                <label class="goyo_label">
                                                    <input type="text" name="goyopopup[options][color]" class="goyo_form_field colorpicker" value="<?php echo $this->is_set( $goyopopup[ 'options' ][ 'color' ] ); ?>" />
                                                </label>
                                            </div><!-- .advance_item_cont -->
                                        </div><!-- .advance_item -->
                                        <div class="advance_item">
                                            <h4><?php echo esc_html__( '글자 배경 색상', 'goyo-popup' ); ?></h4>
                                            <div class="advance_item_cont">
                                                <p class="desc"><span><?php echo esc_html__( '오늘하루보지않음, X 닫기', 'goyo-popup' ); ?></span> <?php echo esc_html__( '글자 배경 색상 변경', 'goyo-popup' ); ?></p>
                                                <label class="goyo_label">
                                                    <input type="text" name="goyopopup[options][background]" class="goyo_form_field colorpicker" value="<?php echo $this->is_set( $goyopopup[ 'options' ][ 'background' ] ); ?>" />
                                                </label>
                                            </div><!-- .advance_item_cont -->
                                        </div><!-- .advance_item -->
                                    </div><!-- .advance_item -->
                                </div><!-- .goyo_accordion_content -->
                            </div><!-- .goyo_accordion_content_wrap -->
                        </div><!-- .goyo_accordion_item -->
                    </div><!-- .goyo_accordion -->
                </div><!-- .goyo_popup_register_item -->

            </div><!-- .goyo_popup_register_setting -->

            <button class="goyo_popup_btn goyo_popup_btn_save"><span><?php echo esc_html__( '저장하기', 'goyo-popup' ); ?></span></button>
        </div><!-- .goyo_popup_register_content -->
    </form>

    <div class="goyo_popup_text_only_modal" style="display:none;">
        <div class="goyo_popup_text_only_modal_inner">
            <h3><?php echo esc_html__( '텍스트 팝업 입력', 'goyo-popup' ); ?></h3>
            <label class="goyo_label">
                <span><?php echo esc_html__( '제목', 'goyo-popup' ); ?></span>
                <input type="text" class="goyo_form_field text_only_title" maxlength="100" placeholder="<?php echo esc_attr__( '제목을 입력하세요.', 'goyo-popup' ); ?>" />
            </label>
            <label class="goyo_label">
                <span><?php echo esc_html__( '내용', 'goyo-popup' ); ?></span>
                <textarea class="goyo_form_field text_only_content" rows="5" maxlength="1000" placeholder="<?php echo esc_attr__( '내용을 입력하세요.', 'goyo-popup' ); ?>"></textarea>
            </label>
            <div class="goyo_popup_text_only_modal_color_row">
                <label class="goyo_label">
                    <span><?php echo esc_html__( '배경색', 'goyo-popup' ); ?></span>
                    <input type="text" class="goyo_form_field colorpicker text_only_background_color" value="#667FFF" />
                </label>
                <label class="goyo_label">
                    <span><?php echo esc_html__( '보더색', 'goyo-popup' ); ?></span>
                    <input type="text" class="goyo_form_field colorpicker text_only_border_color" value="#A2DBFF" />
                </label>
                <label class="goyo_label">
                    <span><?php echo esc_html__( '보더폭', 'goyo-popup' ); ?></span>
                    <input type="number" class="goyo_form_field text_only_border_width" min="0" step="1" value="10" />
                </label>
                <label class="goyo_label">
                    <span><?php echo esc_html__( '제목색', 'goyo-popup' ); ?></span>
                    <input type="text" class="goyo_form_field colorpicker text_only_title_color" value="#ffffff" />
                </label>
                <label class="goyo_label">
                    <span><?php echo esc_html__( '내용색', 'goyo-popup' ); ?></span>
                    <input type="text" class="goyo_form_field colorpicker text_only_content_color" value="#ffffff" />
                </label>
            </div>
            <div class="goyo_popup_text_only_modal_btns">
                <button type="button" class="btn_text_only_cancel"><?php echo esc_html__( '취소', 'goyo-popup' ); ?></button>
                <button type="button" class="btn_text_only_save"><?php echo esc_html__( '저장', 'goyo-popup' ); ?></button>
            </div>
        </div>
    </div><!-- .goyo_popup_text_only_modal -->
</div>
<!-- //새 팝업 등록하기 -->

<script type="text/html" class="template" id="jtBeforeImageAdd">
<div class="goyo_popup_register_item goyo_popup_register_img_add">
    <h3><?php echo esc_html__( '기본 이미지 등록', 'goyo-popup' ); ?></h3>
    <div class="goyo_popup_register_fig">
        <button type="button" class="btn_img_add"><span><?php echo esc_html__( '이미지 추가', 'goyo-popup' ); ?></span></button>
    </div><!-- .goyo_popup_register_fig -->
</div>
</script>

<script type="text/html" class="template" id="jtAfterImageAdd">
<div class="goyo_popup_register_item">
    <h3><?php echo esc_html__( '기본 이미지 등록', 'goyo-popup' ); ?></h3>
    <div class="goyo_popup_register_fig">
        <figure class="goyo_popup_register_img">
            <img src="" />
        </figure>
    </div><!-- .goyo_popup_register_fig -->
    <p class="desc"></p>
    <button type="button" class="btn_img_delete"><span><?php echo esc_html__( '삭제하기', 'goyo-popup' ); ?></span></button>
</div>
</script>

<script type="text/html" class="template" id="jtBeforeLetinaAdd">
<div class="goyo_popup_register_item goyo_popup_register_img_add">
    <h3><?php echo esc_html__( '2배', 'goyo-popup' ); ?> <span><?php echo esc_html__( '사이즈', 'goyo-popup' ); ?></span> <?php echo esc_html__( '이미지 등록', 'goyo-popup' ); ?></h3>
    <div class="goyo_popup_register_fig">
        <button type="button" class="btn_img_add"><span><?php echo esc_html__( '이미지 추가', 'goyo-popup' ); ?></span></button>
    </div><!-- .goyo_popup_register_fig -->
</div>
</script>

<script type="text/html" class="template" id="jtAfterLetinaAdd">
<div class="goyo_popup_register_item">
    <h3><?php echo esc_html__( '2배', 'goyo-popup' ); ?> <span><?php echo esc_html__( '사이즈', 'goyo-popup' ); ?></span> <?php echo esc_html__( '이미지 등록', 'goyo-popup' ); ?></h3>
    <div class="goyo_popup_register_fig">
        <figure class="goyo_popup_register_img">
            <img src="" />
        </figure>
        <p class="sticker_2x"><span>2x</span></p>
    </div><!-- .goyo_popup_register_fig -->
    <p class="desc"></p>
    <button type="button" class="btn_img_delete"><span><?php echo esc_html__( '삭제하기', 'goyo-popup' ); ?></span></button>
</div>
</script>

<script type="text/html" class="template" id="jtPostSelected">
<li>
    <input type="hidden" name="goyopopup[post_ids][]" value="" />
    <span></span>
    <button type="button" class="page_exposure_delete"></button>
</li>
</script>
