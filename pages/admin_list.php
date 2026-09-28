<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access)

/**
 * 목록 페이지. $publish_list, $pending_list, $close_list 는 GOYO_Popup_Admin::admin_list() 에서 주입합니다.
 *
 * @var array $publish_list
 * @var array $pending_list
 * @var array $close_list
 */

?>

<div  id="goyo_popup" class="goyo_popup_list wrap">
    <div class="goyo_popup_head">
        <a href="<?php echo admin_url( 'admin.php?page=goyo-popup-config' ); ?>" class="goyopopup_head_btn" style="display: none;"><span><?php echo esc_html__( '팝업 공통 설정', 'goyo-popup' ); ?></span></a>
        <div class="goyo_popup_head_inner">
			<h1 class="goyo_popup_title" lang="en">POPUP </h1>
		</div>
    </div><!-- .goyo_popup_head -->
    <div class="goyo_popup_contents">
        

        <div class="goyo_popup_list_boxWrap col2">
            <div class="goyo_popup_list_box goyo_popup_publish_box">
                <h2>
                    <span><?php echo esc_html__( '게시 중 (', 'goyo-popup' ); ?><span class="list_count"><?php printf( '%d', count( $publish_list ) ); ?></span>)</span>
                </h2>
                <div class="goyo_popup_item_wrap">
                    <div class="goyo_popup_item add_button">
                        <div class="goyo_popup_item_add">
                            <a href="<?php echo admin_url( 'admin-ajax.php?action=goyopopup_admin_form' ); ?>"><span><?php echo esc_html__( '팝업추가', 'goyo-popup' ); ?></span></a>
                        </div><!-- .goyo_popup_item_add -->
                    </div><!-- .goyo_popup_item -->

                    <?php if ( is_array( $publish_list ) && count( $publish_list ) > 0 ) : ?>

                        <?php foreach ( $publish_list as $item ) : ?>

                            <?php
                                $is_always  = ( $this->is_set( $item[ 'always_flag' ], 'Y' ) == 'Y' );
                                $start_date = ( $is_always || ! $this->is_set( $item[ 'start_date' ] ) ? '' : date( 'Y-m-d H:i', strtotime( $item[ 'start_date' ] ) ) );
                                $end_date   = ( $is_always || ! $this->is_set( $item[ 'end_date' ] ) ? '' : date( 'Y-m-d H:i', strtotime( $item[ 'end_date' ] ) ) );
                                $image      = $this->get_image_src( $item[ 'image' ], 'medium' );
                                $text_title = trim( $this->is_set( $item[ 'options' ][ 'text_title' ], '' ) );
                                $text_content = trim( $this->is_set( $item[ 'options' ][ 'text_content' ], '' ) );
                                $is_text_only = ( empty( $image ) && ( ! empty( $text_title ) || ! empty( $text_content ) ) );
                                $text_background_color = $this->is_set( $item[ 'options' ][ 'text_background_color' ], '#667FFF' );
                            ?>

                            <div class="goyo_popup_item" data-goyopopup-id="<?php echo $item[ 'id' ]; ?>">
                                <div class="goyo_popup_item_fig">
                                    <?php if ( $is_text_only ) : ?>
                                        <figure class="goyo_popup_item_img goyo_popup_item_img_text_only" style="background-color: <?php echo esc_attr( $text_background_color ); ?>;"></figure>
                                    <?php else : ?>
                                        <figure class="goyo_popup_item_img"><img src="<?php echo $image; ?>" alt=""></figure>
                                    <?php endif; ?>
                                    <div class="goyo_popup_item_util">
                                        <div class="goyo_popup_item_util_inner">
                                            <button type="button" class="btn_preview"><span><?php echo esc_html__( '미리보기', 'goyo-popup' ); ?></span></button>
                                            <div class="goyo_popup_item_util_down_btn">
                                                <button type="button" class="btn_modify"><span><?php echo esc_html__( '수정', 'goyo-popup' ); ?></span></button>
                                                <button type="button" class="btn_delete"><span><?php echo esc_html__( '삭제', 'goyo-popup' ); ?></span></button>
                                            </div><!-- .goyo_popup_item_util_down_btn -->
                                        </div><!-- .goyo_popup_item_util_inner -->
                                    </div><!-- .goyo_popup_item_util -->
                                </div><!-- .goyo_popup_item_fig -->

                                <?php if ( $is_always ) : ?>

                                    <span class="post_date"><?php echo esc_html__( '항상 노출', 'goyo-popup' ); ?></span>

                                <?php else : ?>

                                    <span class="post_date">
                                        <span class="post_date_inner"><?php echo ( $start_date ?: '' ); ?></span>
                                        <span class="post_date_inner"><?php echo ( $end_date ? ' ~ ' . $end_date : '' ); ?></span>
                                    </span>

                                <?php endif; ?>
                            </div><!-- .goyo_popup_item -->

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div><!-- .goyo_popup_item_wrap -->
            </div><!-- .goyo_popup_list_box -->

            <div class="goyo_popup_list_box goyo_popup_coming_box">
                <h2>
                    <span><?php echo esc_html__( '게시 예정 (', 'goyo-popup' ); ?><span class="list_count"><?php printf( '%d', count( $pending_list ) ); ?></span>)</span>
                
                </h2>
                <div class="goyo_popup_item_wrap">
                    <div class="goyo_popup_item add_button">
                        <div class="goyo_popup_item_add">
                            <a href="<?php echo admin_url( 'admin-ajax.php?action=goyopopup_admin_form' ); ?>"><span><?php echo esc_html__( '팝업추가', 'goyo-popup' ); ?></span></a>
                        </div><!-- .goyo_popup_item_add -->
                    </div><!-- .goyo_popup_item -->

                    <?php if ( is_array( $pending_list ) && count( $pending_list ) > 0 ) : ?>

                        <?php foreach ( $pending_list as $item ) : ?>

                            <?php
                                $is_always  = ( $this->is_set( $item[ 'always_flag' ], 'Y' ) == 'Y' );
                                $start_date = ( $is_always || ! $this->is_set( $item[ 'start_date' ] ) ? '' : date( 'Y-m-d H:i', strtotime( $item[ 'start_date' ] ) ) );
                                $end_date   = ( $is_always || ! $this->is_set( $item[ 'end_date' ] ) ? '' : date( 'Y-m-d H:i', strtotime( $item[ 'end_date' ] ) ) );
                                $image      = $this->get_image_src( $item[ 'image' ], 'medium' );
                                $text_title = trim( $this->is_set( $item[ 'options' ][ 'text_title' ], '' ) );
                                $text_content = trim( $this->is_set( $item[ 'options' ][ 'text_content' ], '' ) );
                                $is_text_only = ( empty( $image ) && ( ! empty( $text_title ) || ! empty( $text_content ) ) );
                                $text_background_color = $this->is_set( $item[ 'options' ][ 'text_background_color' ], '#667FFF' );
                            ?>

                            <div class="goyo_popup_item" data-goyopopup-id="<?php echo $item[ 'id' ]; ?>">
                                <div class="goyo_popup_item_fig">
                                    <?php if ( $is_text_only ) : ?>
                                        <figure class="goyo_popup_item_img goyo_popup_item_img_text_only" style="background-color: <?php echo esc_attr( $text_background_color ); ?>;"></figure>
                                    <?php else : ?>
                                        <figure class="goyo_popup_item_img"><img src="<?php echo $image; ?>" alt=""></figure>
                                    <?php endif; ?>
                                    <div class="goyo_popup_item_util">
                                        <div class="goyo_popup_item_util_inner">
                                            <button type="button" class="btn_preview"><span><?php echo esc_html__( '미리보기', 'goyo-popup' ); ?></span></button>
                                            <div class="goyo_popup_item_util_down_btn">
                                                <button type="button" class="btn_modify"><span><?php echo esc_html__( '수정', 'goyo-popup' ); ?></span></button>
                                                <button type="button" class="btn_delete"><span><?php echo esc_html__( '삭제', 'goyo-popup' ); ?></span></button>
                                            </div><!-- .goyo_popup_item_util_down_btn -->
                                        </div><!-- .goyo_popup_item_util_inner -->
                                    </div><!-- .goyo_popup_item_util -->
                                </div><!-- .goyo_popup_item_fig -->

                                <?php if ( $is_always ) : ?>

                                    <span class="post_date"><?php echo esc_html__( '항상 노출', 'goyo-popup' ); ?></span>

                                <?php else : ?>

                                    <span class="post_date">
                                        <span class="post_date_inner"><?php echo ( $start_date ?: '' ); ?></span>
                                        <span class="post_date_inner"><?php echo ( $end_date ? ' ~ ' . $end_date : '' ); ?></span>
                                    </span>

                                <?php endif; ?>
                            </div><!-- .goyo_popup_item -->

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div><!-- .goyo_popup_item_wrap -->
            </div><!-- .goyo_popup_list_box -->

            <div class="goyo_popup_list_box goyo_popup_stop_box">
                <h2>
                    <span><?php echo esc_html__( '게시 중지 (', 'goyo-popup' ); ?><span class="list_count"><?php printf( '%d', count( $close_list ) ); ?></span>)</span>
                
                </h2>
                <div class="goyo_popup_item_wrap">
                    <?php if ( is_array( $close_list ) && count( $close_list ) > 0 ) : ?>

                        <?php foreach ( $close_list as $item ) : ?>

                            <?php
                                $is_always  = ( $this->is_set( $item[ 'always_flag' ], 'Y' ) == 'Y' );
                                $start_date = ( $is_always || ! $this->is_set( $item[ 'start_date' ] ) ? '' : date( 'Y-m-d H:i', strtotime( $item[ 'start_date' ] ) ) );
                                $end_date   = ( $is_always || ! $this->is_set( $item[ 'end_date' ] ) ? '' : date( 'Y-m-d H:i', strtotime( $item[ 'end_date' ] ) ) );
                                $image      = $this->get_image_src( $item[ 'image' ], 'medium' );
                                $text_title = trim( $this->is_set( $item[ 'options' ][ 'text_title' ], '' ) );
                                $text_content = trim( $this->is_set( $item[ 'options' ][ 'text_content' ], '' ) );
                                $is_text_only = ( empty( $image ) && ( ! empty( $text_title ) || ! empty( $text_content ) ) );
                                $text_background_color = $this->is_set( $item[ 'options' ][ 'text_background_color' ], '#667FFF' );
                                $is_end     = ( ! $is_always && $item[ 'end_date' ] < $this->now() );
                            ?>

                            <div class="goyo_popup_item term_end_item" data-goyopopup-id="<?php echo $item[ 'id' ]; ?>">
                                <div class="goyo_popup_item_fig">
                                    <?php if ( $is_text_only ) : ?>
                                        <figure class="goyo_popup_item_img goyo_popup_item_img_text_only" style="background-color: <?php echo esc_attr( $text_background_color ); ?>;"></figure>
                                    <?php else : ?>
                                        <figure class="goyo_popup_item_img"><img src="<?php echo $image; ?>" alt=""></figure>
                                    <?php endif; ?>
                                    <div class="goyo_popup_item_util">
                                        <div class="goyo_popup_item_util_inner">
                                            <button type="button" class="btn_preview"><span><?php echo esc_html__( '미리보기', 'goyo-popup' ); ?></span></button>
                                            <div class="goyo_popup_item_util_down_btn">
                                                <button type="button" class="btn_modify"><span><?php echo esc_html__( '수정', 'goyo-popup' ); ?></span></button>
                                                <button type="button" class="btn_delete"><span><?php echo esc_html__( '삭제', 'goyo-popup' ); ?></span></button>
                                            </div><!-- .goyo_popup_item_util_down_btn -->
                                        </div><!-- .goyo_popup_item_util_inner -->
                                    </div><!-- .goyo_popup_item_util -->

                                    <?php if ( $is_end ) : ?>

                                        <p class="term_end_sticker"><span><?php echo esc_html__( '기간종료', 'goyo-popup' ); ?></span></p>

                                    <?php endif; ?>
                                </div><!-- .goyo_popup_item_fig -->

                                <?php if ( $is_always ) : ?>

                                    <span class="post_date"><?php echo esc_html__( '항상 노출', 'goyo-popup' ); ?></span>

                                <?php else : ?>

                                    <span class="post_date">
                                        <span class="post_date_inner"><?php echo ( $start_date ?: '' ); ?></span>
                                        <span class="post_date_inner"><?php echo ( $end_date ? ' ~ ' . $end_date : '' ); ?></span>
                                    </span>

                                <?php endif; ?>
                            </div><!-- .goyo_popup_item -->

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div><!-- .goyo_popup_item_wrap -->
            </div><!-- .goyo_popup_list_box -->
        </div>
        

        <div class="goyo_popup_info" style="display: none;">
            <p class="goyo_popup_info_title" >POPUP INFO</p>
            <ul class="goyo_popup_info_list">
                <li><?php echo esc_html__( 'Drag&Drop 을 통해 팝업의 노출 순서를 수정할 수 있습니다.', 'goyo-popup' ); ?> </li>
            </ul><!-- .goyo_popup_info_list -->
        </div><!-- .goyo_popup_info -->
    </div><!-- .goyo_popup_contents -->
</div><!-- #goyo_popup -->

<form class="goyopopup_remove_form">
    <?php echo $this->nonce( 'goyopopup_remove' ); ?>
    <input type="hidden" name="action" value="goyopopup_action" />
</form>

<form class="goyopopup_sort_form">
    <?php echo $this->nonce( 'goyopopup_sort' ); ?>
    <input type="hidden" name="action" value="goyopopup_action" />
</form>