function goyoPopupTranslate( text ) {
    var messages = window.goyopopupAdmin && window.goyopopupAdmin.messages;
    return messages && Object.prototype.hasOwnProperty.call( messages, text ) ? messages[ text ] : text;
}

jQuery( function ( $ ) {


/* **************************************** *
 * Functions Init
 * **************************************** */

high_class_settings();
magnific_popup();
click_button();

switch_init();
icheck_init();
accordion_init();
accordion_list();
range_handle_all();
match_height();
color_picker();
datetime_picker();
overlay_color();
btn_hover();
admin_studio_goyo_link();
position_type_toggle();

focus_on_tab_only();

goyopopup_form_submit();

sortable_handle();

page_exposure_list_action();

// AJAX 대응
$( document ).ajaxComplete( function () {

    switch_init();
    icheck_init();
    accordion_init();
    range_handle_all();
    match_height();
    color_picker();
    datetime_picker();

    focus_on_tab_only();

    sortable_handle();

    page_exposure_list_init();

} );

if ( typeof wp.media !== 'undefined' ) {

    var wp_media = wp.media( {
        title       : wp.media.view.l10n.addMedia,
        multiple    : false
    } );

}

/* **************************************** *
 * Functions
 * **************************************** */

/**
 * 고급설정 아코디언
 *
 * @author STUDIO-GOYO (JDY)
 */
function high_class_settings() {

    $( document ).on( 'click', '.goyopopup-expand-container .goyopopup-expand-label', function () {

        var $target = $( this ).closest( '.goyopopup-expand-container' );
        var height  = $target.find( '.goyopopup-expand-content' ).outerHeight();

        $target.toggleClass( 'open' );
        $target.find( '.goyopopup-expand-content ul' ).slideToggle( 300 );

        return false;

    } );

}



/**
 * layer popup
 *
 * @author STUDIO-GOYO (SUMI, 201)
 */
function magnific_popup() {

    // POPUP ADD
    $( document ).on( 'click', '.goyo_popup_item_add a', function () {

        var $this = $( this );

        if ( $this.hasClass( 'clicked' ) ) {

            return false;

        }

        $this.toggleClass( 'clicked' );

        $.get( ajaxurl, { action: 'goyopopup_admin_form', nonce: ( typeof goyopopupAdmin !== 'undefined' ? goyopopupAdmin.nonceAdminForm : '' ) }, function ( res ) {

            if ( $( '#goyo_popup_register' ).length == 0 ) {

                $( res ).appendTo( 'body' );

            } else {

                $( '#goyo_popup_register' ).replaceWith( res );

            }

            var $form = $( '#goyo_popup_register' );

            $.magnificPopup.open( {
                items       : { src: '#goyo_popup_register' },
                type        : 'inline',
                preloader   : false,
                fixedBgPos  : true,
                modal       : true,

            } );

            $this.toggleClass( 'clicked' );

        } );

        return false;

    } );

    // POPUP PREVIEW
    $( document ).on( 'click', '.btn_preview', function () {

        var $this   = $( this );
        var id      = $( this ).closest( '.goyo_popup_item' ).data( 'goyopopup-id' );

        if ( $this.hasClass( 'clicked' ) ) { return false; }

        $this.toggleClass( 'clicked' );

        $.get( ajaxurl, { action: 'goyopopup_preview', goyopopup_id: id, nonce: ( typeof goyopopupAdmin !== 'undefined' ? goyopopupAdmin.noncePreview : '' ) }, function (res ) {

            if ( res.success ) {

                var $html = $( res.data );

                if ( $( '#goyo_popup_container' ).length > 0 ) {

                    $( '#goyo_popup_container' ).replaceWith( $html );

                } else {

                    $html.appendTo( 'body' );

                }

                $( '#goyo_popup_container' ).imagesLoaded( goyopopup_show );
				// goyopopup_show();

            } else {

                alert( res.data );

            }

            $this.toggleClass( 'clicked' );

        } );

    } );

    // POPUP EDIT
    $( document ).on( 'click', '.btn_modify', function () {

        var $this   = $( this );
        var id      = $this.closest( '.goyo_popup_item' ).data( 'goyopopup-id' );

        if ( $this.hasClass( 'clickd' ) ) { return false; }

        $this.toggleClass( 'clicked' );

        $.get( ajaxurl, { action: 'goyopopup_admin_form', goyop_id: id, nonce: ( typeof goyopopupAdmin !== 'undefined' ? goyopopupAdmin.nonceAdminForm : '' ) }, function ( res ) {

            if ( $( '#goyo_popup_register' ).length == 0 ) {

                $( res ).appendTo( 'body' );

            } else {

                $( '#goyo_popup_register' ).replaceWith( res );

            }

            var $form = $( '#goyo_popup_register' );

            $.magnificPopup.open( {
                items       : { src: '#goyo_popup_register' },
                type        : 'inline',
                preloader   : false,
                fixedBgPos  : false,
                modal       : true,
            } );

            $this.toggleClass( 'clicked' );

        } );

        return false;

    } );

    // POPUP REMOVE
    $( document ).on( 'click', '.btn_delete', function () {

        var $this   = $( this );
        var $form   = $( '.goyopopup_remove_form' );
        var data    = $form.serializeObject();

        if ( $this.hasClass( 'clicked' ) ) { return false; }

        $this.toggleClass( 'clicked' );

        if ( data ) {

            if ( confirm( goyoPopupTranslate( '팝업을 삭제하시겠습니까?' ) ) ) {

                data.goyopopup_id = $this.closest( '.goyo_popup_item' ).data( 'goyopopup-id' );

                $.post( ajaxurl, data, function ( res ) {

                    if ( res.success ) {

                        alert( goyoPopupTranslate( '삭제되었습니다' ) );

                        $this.closest( '.goyo_popup_item' ).fadeOut( 'slow', function () { $( this ).remove(); } );

                    } else {

                        alert( res.data );

                    }

                } );

            }

            $this.toggleClass( 'clicked' );

        } else {

            alert( goyoPopupTranslate( '잘못된 접근입니다.' ) );
            location.reload();

        }

        return false;

    } );

    // MOBILE POPUP MENU TOGGLE
    if ( is_mobile() ) {

        $( document ).on( 'click', function ( e ) {

            if ( $( e.target ).closest( '.goyo_popup_item' ).length > 0 ) {

                $( e.target ).closest( '.goyo_popup_item' ).toggleClass( 'hover' );

            } else {

                $( '.goyo_popup_item' ).removeClass( 'hover' );

            }

        } );

    }

}

/**
 * switch button init
 *
 * @author STUDIO-GOYO (SUMI, 201)
 */
function switch_init() {

    // SWITCH BUTTON INIT
    $( '.switch_input:checked' ).each( function () {

        $( this ).closest( '.switch_wrap' ).addClass( 'switch_on' );

    } );

}

/**
 * click button
 *
 * @author STUDIO-GOYO (SUMI, 201)
 */
function click_button() {
    var DEFAULT_TEXT_BACKGROUND_COLOR = '#667FFF';
    var DEFAULT_TEXT_BORDER_COLOR = '#A2DBFF';
    var DEFAULT_TEXT_BORDER_WIDTH = 10;
    var DEFAULT_TEXT_TITLE_COLOR = '#ffffff';
    var DEFAULT_TEXT_CONTENT_COLOR = '#ffffff';

    function sync_text_only_ui( $register_wrap ) {
        var title = $.trim( $register_wrap.find( '[name="goyopopup[options][text_title]"]' ).val() );
        var content = $.trim( $register_wrap.find( '[name="goyopopup[options][text_content]"]' ).val() );
        var has_text = ( !! title || !! content );
        var $desc = $register_wrap.find( '.goyo_popup_register_text_only_desc' );
        var $actions = $register_wrap.find( '.goyo_popup_register_text_only_actions' );

        $desc.toggleClass( 'active', has_text );
        $actions.toggleClass( 'active', has_text );
        $desc.find( 'strong' ).text( title || goyoPopupTranslate( '제목 없음' ) );
        $desc.find( 'span' ).text( content || goyoPopupTranslate( '내용 없음' ) );
    }

    $( document ).on( 'click', '.url_clear', function () {

        var $this   = $( this );
        var $wrap   = $this.closest( '.goyo_label_link' );
        var $target = $wrap.find( 'input[name="goyopopup[url]"]' );

        if ( $target.length > 0 ) {

            $target.val( '' ).focus();

        }

        return false;

    } );

    // SWITCH BUTTON
    $( document ).on( 'click', '.switch, .switch+span', function () {

        var $wrap = $( this ).closest( '.switch_wrap' );

        $wrap.toggleClass( 'switch_on' );
        $wrap.find( 'input.switch_input' ).prop( 'checked', ! $wrap.hasClass( 'switch_on' ) );

    } );

    // TOOLTIP BUTTON
    $( document )
        .on( 'mouseover', '.goyo_popup_tooltip', function () {

            $( this ).parents( '.goyo_popup_form_item' ).find( '.goyo_popup_tooltip_cont' ).addClass( 'active' );

        } ).on( 'mouseleave', function () {

            $( this ).parents( '.goyo_popup_form_item' ).find( '.goyo_popup_tooltip_cont' ).removeClass( 'active' );

        } );

    if ( is_mobile() ) {

        $( document ).on( 'click', '.goyo_popup_tooltip', function () {

            $( this ).parents( '.goyo_popup_form_item' ).find( '.goyo_popup_tooltip_cont' ).toggleClass( 'active' );

        } );

    }

    // IMAGE SELECT
    $( document ).on( 'click', '.btn_img_add, .goyo_popup_register_img', function () {

        var $this       = $( this );
        var $wrap       = $this.closest( '.goyo_popup_register_item' );
        var is_letina   = ( $this.closest( '.goyo_popup_register_img_wrap' ).find( '.goyo_popup_register_item' ).index( $wrap ) == 1 );
        var goyo_media    = wp.media( { multiple: false } );

        goyo_media.on( 'select', function () {

            var attachment  = goyo_media.state().get( 'selection' ).first().toJSON();
            var $html       = $( $( '#jtAfterImageAdd' ).html() );
            var $input      = $( '[name="goyopopup[image]"]' );

            if ( is_letina ) {

                $html   = $( $( '#jtAfterLetinaAdd' ).html() );
                $input  = $( '[name="goyopopup[letina]"]' );

            }

            var img_src = attachment.sizes.full.url;

            if ( typeof attachment.sizes.thumbnail !== 'undefined' ) {

                img_src = attachment.sizes.thumbnail.url;

            }

            if ( ! is_mobile() && typeof attachment.sizes.medium !== 'undefined' ) {

                img_src = attachment.sizes.medium.url;

            }

            var file_name       = attachment.sizes.full.url.split( '/' ).pop().split( '.' )[ 0 ];
            var file_extension  = attachment.sizes.full.url.split( '.' ).pop();

            file_name = ( ( file_name + '.' + file_extension ).length > 20 ? file_name.substr( 0, 15 ) + ' ... .' + file_extension : file_name + '.' + file_extension );

            $( 'p.desc', $html ).text( file_name );
            $( 'img', $html ).attr( 'src', img_src );
            $input.val( attachment.id );

            $wrap.replaceWith( $html );

        } );

        goyo_media.open();

        return false;

    } );

    // TEXT ONLY MODAL OPEN
    $( document ).on( 'click', '.btn_text_only_open, .btn_text_only_edit', function () {

        var $register_wrap = $( this ).closest( '#goyo_popup_register' );
        var $modal = $register_wrap.find( '.goyo_popup_text_only_modal' );
        var title = $register_wrap.find( '[name="goyopopup[options][text_title]"]' ).val() || '';
        var content = $register_wrap.find( '[name="goyopopup[options][text_content]"]' ).val() || '';
        var background_color = $register_wrap.find( '[name="goyopopup[options][text_background_color]"]' ).val() || DEFAULT_TEXT_BACKGROUND_COLOR;
        var border_color = $register_wrap.find( '[name="goyopopup[options][text_border_color]"]' ).val() || DEFAULT_TEXT_BORDER_COLOR;
        var border_width = parseInt( $register_wrap.find( '[name="goyopopup[options][text_border_width]"]' ).val(), 10 );
        border_width = ( isNaN( border_width ) || border_width < 0 ) ? DEFAULT_TEXT_BORDER_WIDTH : border_width;
        var title_color = $register_wrap.find( '[name="goyopopup[options][text_title_color]"]' ).val() || DEFAULT_TEXT_TITLE_COLOR;
        var content_color = $register_wrap.find( '[name="goyopopup[options][text_content_color]"]' ).val() || DEFAULT_TEXT_CONTENT_COLOR;
        var has_minicolors = ( typeof $.fn.minicolors !== 'undefined' );

        $modal.find( '.text_only_title' ).val( title );
        $modal.find( '.text_only_content' ).val( content );
        $modal.find( '.text_only_background_color' ).val( background_color );
        $modal.find( '.text_only_border_color' ).val( border_color );
        $modal.find( '.text_only_border_width' ).val( border_width );
        $modal.find( '.text_only_title_color' ).val( title_color );
        $modal.find( '.text_only_content_color' ).val( content_color );

        if ( has_minicolors ) {
            $modal.find( '.text_only_background_color' ).minicolors( 'value', background_color );
            $modal.find( '.text_only_border_color' ).minicolors( 'value', border_color );
            $modal.find( '.text_only_title_color' ).minicolors( 'value', title_color );
            $modal.find( '.text_only_content_color' ).minicolors( 'value', content_color );
        }

        $modal.fadeIn( 120 );

        return false;

    } );

    // TEXT ONLY MODAL CLOSE (취소 버튼)
    $( document ).on( 'click', '.btn_text_only_cancel', function () {

        $( this ).closest( '.goyo_popup_text_only_modal' ).fadeOut( 120 );

        return false;

    } );

    // TEXT ONLY MODAL CLOSE (배경 클릭)
    $( document ).on( 'click', '.goyo_popup_text_only_modal', function ( e ) {

        if ( e.target !== this ) {
            return;
        }

        $( this ).fadeOut( 120 );

        return false;

    } );

    // TEXT ONLY DELETE
    $( document ).on( 'click', '.btn_text_only_delete', function () {

        var $register_wrap = $( this ).closest( '#goyo_popup_register' );

        $register_wrap.find( '[name="goyopopup[options][text_title]"]' ).val( '' );
        $register_wrap.find( '[name="goyopopup[options][text_content]"]' ).val( '' );
        $register_wrap.find( '[name="goyopopup[options][text_background_color]"]' ).val( DEFAULT_TEXT_BACKGROUND_COLOR );
        $register_wrap.find( '[name="goyopopup[options][text_border_color]"]' ).val( DEFAULT_TEXT_BORDER_COLOR );
        $register_wrap.find( '[name="goyopopup[options][text_border_width]"]' ).val( DEFAULT_TEXT_BORDER_WIDTH );
        $register_wrap.find( '[name="goyopopup[options][text_title_color]"]' ).val( DEFAULT_TEXT_TITLE_COLOR );
        $register_wrap.find( '[name="goyopopup[options][text_content_color]"]' ).val( DEFAULT_TEXT_CONTENT_COLOR );

        sync_text_only_ui( $register_wrap );

        return false;

    } );

    // TEXT ONLY MODAL SAVE
    $( document ).on( 'click', '.btn_text_only_save', function () {

        var $modal = $( this ).closest( '.goyo_popup_text_only_modal' );
        var $register_wrap = $modal.closest( '#goyo_popup_register' );
        var title = $.trim( $modal.find( '.text_only_title' ).val() );
        var content = $.trim( $modal.find( '.text_only_content' ).val() );
        var background_color = $.trim( $modal.find( '.text_only_background_color' ).val() ) || DEFAULT_TEXT_BACKGROUND_COLOR;
        var border_color = $.trim( $modal.find( '.text_only_border_color' ).val() ) || DEFAULT_TEXT_BORDER_COLOR;
        var border_width = parseInt( $.trim( $modal.find( '.text_only_border_width' ).val() ), 10 );
        border_width = ( isNaN( border_width ) || border_width < 0 ) ? DEFAULT_TEXT_BORDER_WIDTH : border_width;
        var title_color = $.trim( $modal.find( '.text_only_title_color' ).val() ) || DEFAULT_TEXT_TITLE_COLOR;
        var content_color = $.trim( $modal.find( '.text_only_content_color' ).val() ) || DEFAULT_TEXT_CONTENT_COLOR;
        var $title_input = $register_wrap.find( '[name="goyopopup[options][text_title]"]' );
        var $content_input = $register_wrap.find( '[name="goyopopup[options][text_content]"]' );
        var $background_color_input = $register_wrap.find( '[name="goyopopup[options][text_background_color]"]' );
        var $border_color_input = $register_wrap.find( '[name="goyopopup[options][text_border_color]"]' );
        var $border_width_input = $register_wrap.find( '[name="goyopopup[options][text_border_width]"]' );
        var $title_color_input = $register_wrap.find( '[name="goyopopup[options][text_title_color]"]' );
        var $content_color_input = $register_wrap.find( '[name="goyopopup[options][text_content_color]"]' );

        if ( ! title && ! content ) {
            alert( goyoPopupTranslate( '제목 또는 내용을 입력해주세요.' ) );
            return false;
        }

        $title_input.val( title );
        $content_input.val( content );
        $background_color_input.val( background_color );
        $border_color_input.val( border_color );
        $border_width_input.val( border_width );
        $title_color_input.val( title_color );
        $content_color_input.val( content_color );

        sync_text_only_ui( $register_wrap );

        $modal.fadeOut( 120 );

        return false;

    } );

    // TEXT ONLY MODAL INPUT ENTER 방지 (폼 submit로 인한 닫힘 방지)
    $( document ).on( 'keydown', '.goyo_popup_text_only_modal .text_only_title', function ( e ) {

        if ( e.key === 'Enter' ) {
            e.preventDefault();
            return false;
        }

    } );

    // IMAGE REMOVE
    $( document ).on( 'click', '.btn_img_delete', function () {

        var $this       = $( this );
        var $wrap       = $this.closest( '.goyo_popup_register_item' );
        var is_letina   = ( $( '.goyo_popup_register_item' ).index( $wrap ) == 1 );
        var $preview    = $wrap.find( '.goyo_popup_register_fig' );
        var $html       = $( $( '#jtBeforeImageAdd' ).html() );
        var $input      = $( '[name="goyopopup[image]"]' );

        if ( is_letina ) {

            $html   = $( $( '#jtBeforeLetinaAdd' ).html() );
            $input  = $( '[name="goyopopup[letina]"]' );

        }

        $wrap.replaceWith( $html );
        $input.val( '' );

    } );

    // RESET CONFIG
    $( document ).on( 'click', '.goyo_popup_btn_undo, .goyo_popup_btn_delete', function () {

        var msg = ( $( this ).hasClass( 'goyo_popup_btn_undo' ) ? goyoPopupTranslate( '설정을 초기화 하시겠습니까?' ) : goyoPopupTranslate( '설정을 취소하시겠습니까?' ) );

        if ( ! confirm( msg ) ) { return false; }

        if ( typeof goyopopupAdminConfig === 'undefined' ) { return false; }

        var data = ( $( this ).hasClass( 'goyo_popup_btn_delete' ) ? goyopopupAdminConfig.currentConfig : goyopopupAdminConfig.defaultConfig );

        if ( data ) {

            set_config_data( data );

        }

        function set_config_data( data, pathParts ) {

            pathParts = pathParts || [];

            $.each( data, function ( key, value ) {

                var path = pathParts.concat( [ key ] );

                if ( typeof value === 'object' && value !== null ) {

                    set_config_data( value, path );

                } else {

                    var name = 'goyopopup[' + path.join( '][' ) + ']';

                    var $target = $( '[name="' + name + '"]' );
                    var type    = $target.attr( 'type' );

                    if ( type == 'radio' || type == 'checkbox' ) {

                        $target.each( function () {

                            var $this = $( this );

                            if ( $this.hasClass( 'goyo_icheck' ) ) {

                                $this.iCheck( $this.val() == value ? 'check' : 'uncheck' );

                            } else {

                                $this.prop( 'checked', $this.val() == value );

                            }

                            if ( $this.hasClass( 'switch_input' ) ) {

                                $this.closest( '.switch_wrap' ).removeClass( 'switch_on' );

                                if ( $this.prop( 'checked' ) ) {

                                    $this.closest( '.switch_wrap' ).addClass( 'switch_on' );

                                }

                            }
                        } );

                    } else {

                        $target.val( value );

                        if ( $target.closest( '.goyo_range_wrap' ).length > 0 ) {

                            $target.closest( '.goyo_range_wrap' ).find( '.goyo_range_native' ).val( value ).trigger( 'input' );

                        }

                        if ( $target.hasClass( 'colorpicker' ) ) {

                            $target.minicolors( 'value', value );

                        }

                    }

                }

            } );

        }

        return false;

    } );

    goyopopup_shadow_preview();
    $( document ).on( 'input', '[name*=shadow]', goyopopup_shadow_preview );

    function goyopopup_shadow_preview() {

        var $target     = $( '.shadow_box' );
        var use_flag    = $( '[name="goyopopup[shadow][use]"]' ).is( ':checked' );
        var x           = $( '[name="goyopopup[shadow][x]"]' ).val();
        var y           = $( '[name="goyopopup[shadow][y]"]' ).val();
        var size        = $( '[name="goyopopup[shadow][size]"]' ).val();
        var color       = $( '[name="goyopopup[shadow][color]"]' ).val();
        var opacity     = parseFloat( $( '[name="goyopopup[shadow][opacity]"]' ).val() );

        opacity = ( isNaN( opacity ) ? 0.2 : Math.min( 1, Math.max( 0, opacity ) ) );

        if ( parseInt( x ) ) {

            if ( parseInt( x ) > 30 ) {

                $( '[name="goyopopup[shadow][x]"]' ).val( 30 );
                x = 30;

            } else {

                x = parseInt( x );

            }

        } else {

            $( '[name="goyopopup[shadow][x]"]' ).val( 1 );
            x = 1;

        }

        if ( parseInt( y ) ) {

            if ( parseInt( y ) > 30 ) {

                $( '[name="goyopopup[shadow][y]"]' ).val( 30 );
                y = 30;

            } else {

                y = parseInt( y );

            }

        } else {

            $( '[name="goyopopup[shadow][y]"]' ).val( 1 );
            y = 1;

        }

        if ( parseInt( size ) ) {

            if ( parseInt( size ) > 50 ) {

                $( '[name="goyopopup[shadow][size]"]' ).val( 50 );
                size = 50;

            } else {

                size = parseInt( size );

            }

        } else {

            $( '[name="goyopopup[shadow][size]"]' ).val( 1 );
            size = 1;

        }


        if ( use_flag ) {

            $target.css( 'box-shadow', x + 'px ' + y + 'px ' + size + 'px ' + goyo_hex_to_rgba( color, opacity ) );

        } else {

            $target.css( 'box-shadow', 'none' );

        }

    }

    // 항상 노출 클릭시 시작일, 종료일은 비활성화
    $( document ).on( 'ifClicked', 'input:checkbox[name="goyopopup[always_flag]"]', function () {

        var $this   = $( this );
        var checked = ! $this.prop( 'checked' );
        var $wrap   = $this.closest( '.goyo_popup_register_item_cont' );
        var $start  = $( 'input[name="goyopopup[start_date]"]', $wrap );
        var $end    = $( 'input[name="goyopopup[end_date]"]', $wrap );

        $start.prop( 'disabled', checked );
        $end.prop( 'disabled', checked );

        if ( checked ) {

            $start.closest( '.goyo_label' ).addClass( 'disabled' );
            $end.closest( '.goyo_label' ).addClass( 'disabled' );

        } else {

            $start.closest( '.goyo_label' ).removeClass( 'disabled' );
            $end.closest( '.goyo_label' ).removeClass( 'disabled' );

        }

    } );

}



 /**
 * icheck plugin init 함수
 * checkbox와 radio 커스텀 스타일을 설정합니다.
 * 각 사이트별 맞춤 skin css 파일을 연동합니다. (ex. minimal.css)
 *
 * @author STUDIO-GOYO (KMS)
 * @see {@link http://icheck.fronteed.com|icheck API}
 * @requires icheck.js
 * @requires /icheck/*.css
 *
 * @example
 * // markup sample
 * <label class="goyo_icheck_label"><input class="goyo_icheck" type="checkbox" /> <span>체크박스</span></label>
 * <label class="goyo_icheck_label"><input class="goyo_icheck" type="radio" /> <span>라디오</span></label>
 *
 */
function icheck_init() {

    if ( typeof $.fn.iCheck === 'undefined' ) {
        return;
    }

    $( '.goyo_icheck' ).each( function () {
        var $el = $( this );
        if ( $el.parent().is( '[class*="icheckbox"], [class*="iradio"]' ) ) {
            return;
        }
        $el.iCheck( {
            checkboxClass   : 'icheckbox_minimal',
            radioClass      : 'iradio_minimal'
        } );
    } );

}

/**
 * ACCORDION
 *
 * @author STUDIO-GOYO (SUMI)
 */
function accordion_init() {

    $( '.goyo_accordion_item' ).each( function () {

        if ( ! $( this ).hasClass( 'active' ) ) {

            $( this ).find( '.goyo_accordion_content_wrap' ).hide();

        }

    } );

}

function accordion_list() {

    $( document ).on( 'click', '.goyo_accordion .goyo_accordion_title', function () {

        var $item = $( this ).parent( '.goyo_accordion_item' );

        if ( $item.hasClass( 'active' ) ) { // CLOSE

            $item.removeClass( 'active' );
            $item.find( '.goyo_accordion_content_wrap' ).slideUp();

        } else { // OPEN

            $item.addClass( 'active' );
            $item.siblings( 'div' ).removeClass( 'active' );
            $item.siblings( 'div' ).find( '.goyo_accordion_content_wrap' ).slideUp();
            $item.find( '.goyo_accordion_content_wrap' ).slideDown();

        }

        return false;

    } );

}



/**
 * 네이티브 range 인풋 공통 처리 - .goyo_range_wrap 마다 값 동기화 + 미리보기 갱신
 * (오버레이 투명도, 그림자 투명도 등 여러 슬라이더에서 재사용)
 *
 * @author STUDIO-GOYO (201)
 */
function range_handle_all() {

    $( '.goyo_range_wrap' ).each( function () {

        var $wrap   = $( this );
        var $range  = $( '.goyo_range_native', $wrap );
        var $target = $( 'input:hidden', $wrap );
        var $value  = $( '.goyo_range_value', $wrap );

        if ( ! $range.length ) {
            return;
        }

        var min  = parseFloat( $range.attr( 'min' ) );
        var max  = parseFloat( $range.attr( 'max' ) );
        var step = parseFloat( $range.attr( 'step' ) ) || 1;
        var decimals = ( String( step ).split( '.' )[ 1 ] || '' ).length;

        function update( val ) {

            val = parseFloat( val );
            val = ( isNaN( val ) ? min : Math.min( max, Math.max( min, val ) ) );
            val = parseFloat( val.toFixed( decimals ) );

            $target.val( val );
            $value.text( val );

            if ( $wrap.hasClass( 'goyo_range_overlay' ) ) {

                $( '.overlay_box' ).css( { opacity: val * 0.01 } );

            }

            if ( $wrap.hasClass( 'goyo_range_shadow_opacity' ) ) {

                // 그림자 미리보기는 [name*=shadow] input 이벤트에 위임되어 있음
                $target.trigger( 'input' );

            }

        }

        update( $range.val() );

        $range.off( 'input.goyoRange change.goyoRange' ).on( 'input.goyoRange change.goyoRange', function () {

            update( $( this ).val() );

        } );

    } );

}

function overlay_color() {

    $( document ).on( 'input change', 'input[name="goyopopup[overlay][color]"]', function () {

        $( '.overlay_box' ).css( 'background', $( this ).val() );

    } );

}

/**
 * 팝업 위치 지정 - "왼쪽" 선택 시에만 좌표 입력 필드 노출
 *
 * @author STUDIO-GOYO (201)
 */
function position_type_toggle() {

    function update() {

        var type = $( 'input[name="goyopopup[position][type]"]:checked' ).val();

        $( '.goyo_position_offset_fields' ).toggleClass( 'active', type === 'left' );

    }

    update();

    $( document ).on( 'ifChecked change', 'input[name="goyopopup[position][type]"]', update );

}

function btn_hover() {

	var $target = $('.goyo_popup_item .goyo_popup_item_fig .goyo_popup_item_util_down_btn button')

    $target.hover(function() {

		$target.addClass("btn_hover");

	}, function(){

		$target.removeClass("btn_hover");

	});

}



function admin_studio_goyo_link() {

	$('.goyo_popup_head_inner .goyo_popup_title').append('')

}



/**
 * element height matching function
 * v1.0 notice: inner 아이템이 아닌 리스트 outer wrap에 셋팅해야 합니다.
 *
 * @version 1.0.0
 * @since 2018-02-03
 * @author STUDIO-GOYO (KMS)
 * @see {@link https://codepen.io/micahgodbolt/pen/FgqLc|Reference}
 */
function match_height() {

    // ELEMENT
    var $item = $( '.goyo_popup_mobile_box .goyo_popup_form_item > .desc' );

    // INIT
    goyo_equal_height();

    // RESIZE
    $( window ).resize( goyo_equal_height );

    // ADD CLOSURES TO KEEP THE $ITEM ALIVE
    function goyo_equal_height() {

        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0;

        $item.each( function() {

            $el = $( this );
            $el.height( 'auto' );
            topPostion = $el.position().top;

            if ( currentRowStart != topPostion ) {

                for ( currentDiv = 0; currentDiv < rowDivs.length; currentDiv++ ) {

                    rowDivs[ currentDiv ].height( currentTallest );

                }

                rowDivs.length  = 0;
                currentRowStart = topPostion;
                currentTallest  = $el.height();

                rowDivs.push( $el );

            } else {

                rowDivs.push( $el );
                currentTallest = ( currentTallest < $el.height() ) ? ( $el.height() ) : ( currentTallest );

            }

            for ( currentDiv = 0; currentDiv < rowDivs.length; currentDiv++ ) {

                rowDivs[ currentDiv ].height( currentTallest );

            }

        } );

    } // goyo_equal_height()

}



/**
 * Color Picker
 *
 * @author STUDIO-GOYO (201)
 */
function color_picker() {

    if ( $( '.colorpicker' ).length === 0 || typeof $.fn.minicolors === 'undefined' ) {
        return;
    }

    $( '.colorpicker' ).each( function () {
        var $el = $( this );
        if ( $el.closest( '.minicolors' ).length ) {
            return;
        }
        $el.minicolors( { inline: false } );
    } );

}



/**
 * Date Time Picker
 *
 * @author STUDIO-GOYO (201)
 */
function datetime_picker() {

    if ( typeof $.fn.datetimepicker === 'undefined' ) {
        return;
    }

    var $start_date = $( 'input.datetime_picker[name="goyopopup[start_date]"]' );
    var $end_date   = $( 'input.datetime_picker[name="goyopopup[end_date]"]' );

    if ( ! $start_date.length || ! $end_date.length ) {
        return;
    }

    if ( $start_date.hasClass( 'hasDatepicker' ) || $end_date.hasClass( 'hasDatepicker' ) ) {
        return;
    }

    $start_date.datetimepicker( {
            controlType     : 'select',
            showButtonPanel : false,
            oneLine         : true,
            dateFormat      : 'yy-mm-dd',
            timeFormat      : 'HH:mm',
            currentText     : goyoPopupTranslate( '현재' ),
            closeText       : goyoPopupTranslate( '닫기' ),
            timeText        : goyoPopupTranslate( '시간' ),
            onClose         : function () {

                                if ( ! $start_date.val() ) {

                                    $end_date.datetimepicker( 'option', 'minDate', null );

                                } else if ( $end_date.val() && $start_date.datetimepicker( 'getDate' ) > $end_date.datetimepicker( 'getDate' ) ) {

                                    $end_date.datetimepicker( 'setDate', $start_date.datetimepicker( 'getDate' ) );

                                }

                            },
            onSelect        : function ( datetimeText, instance ) {

                                $end_date.datetimepicker( 'option', 'minDate', $start_date.datetimepicker( 'getDate' ) );

                            }
        } );


        $end_date.datetimepicker( {
            controlType     : 'select',
            showButtonPanel : false,
            oneLine         : true,
            dateFormat      : 'yy-mm-dd',
            timeFormat      : 'HH:mm',
            currentText     : goyoPopupTranslate( '현재' ),
            closeText       : goyoPopupTranslate( '닫기' ),
            timeText        : goyoPopupTranslate( '시간' ),
            hour            : 23,
            minute          : 59,
            onClose         : function () {

                                if ( ! $end_date.val() ) {

                                    $start_date.datetimepicker( 'option', 'minDate', null );

                                } else if ( $start_date.val() && $start_date.datetimepicker( 'getDate' ) > $end_date.datetimepicker( 'getDate' ) ) {

                                    $start_date.datetimepicker( 'setDate', $end_date.datetimepicker( 'getDate' ) );

                                }

                            },
            onSelect        : function () {

                                $start_date.datetimepicker( 'option', 'maxDate', $end_date.datetimepicker( 'getDate' ) );

                            }
        } );

        if ( $start_date.val() ) {

            $end_date.datetimepicker( 'option', 'minDate', $start_date.datetimepicker( 'getDate' ) );

        }

        if ( $end_date.val() ) {

            $start_date.datetimepicker( 'option', 'maxDate', $end_date.datetimepicker( 'getDate' ) );

        }

}



/**
 * 접근성 & UX 개선 (키보드 사용할때만 포커스 나오게)
 *
 * @author STUDIO-GOYO (Nico)
 */
function focus_on_tab_only() {

    var $body = $( 'body' );

    $body.on( 'mousedown', function () {

        $body.addClass( 'use_mouse' );

    } ).on( 'keydown', function () {

        $body.removeClass( 'use_mouse' );

    } );

}



/**
 * 팝업 Submit 처리
 *
 * @author STUDIO-GOYO (201)
 */
function goyopopup_form_submit() {

    $( document ).on( 'submit', '.goyo_popup_form', function () {

        try {

            var $form = $( this );
            var postData = $form.serialize();
            var $startIn = $( 'input[name="goyopopup[start_date]"]', $form );
            var $endIn = $( 'input[name="goyopopup[end_date]"]', $form );

            if ( $startIn.prop( 'disabled' ) && $startIn.val() ) {
                postData += ( postData.length ? '&' : '' ) + encodeURIComponent( 'goyopopup[start_date]' ) + '=' + encodeURIComponent( $startIn.val() );
            }

            if ( $endIn.prop( 'disabled' ) && $endIn.val() ) {
                postData += ( postData.length ? '&' : '' ) + encodeURIComponent( 'goyopopup[end_date]' ) + '=' + encodeURIComponent( $endIn.val() );
            }

            if ( ! $( '[name="goyopopup[image]"]', $form ).val() ) {
                var text_title = $.trim( $( '[name="goyopopup[options][text_title]"]', $form ).val() );
                var text_content = $.trim( $( '[name="goyopopup[options][text_content]"]', $form ).val() );

                if ( ! text_title && ! text_content ) {
                    alert( goyoPopupTranslate( '기본 이미지를 선택하거나 텍스트를 입력해주세요.' ) );
                    return false;
                }

            }

            if ( $( '[name="goyopopup[platform]"]:checked', $form ).length == 0 ) {
                alert( goyoPopupTranslate( '팝업 노출 위치를 선택해주세요.' ) );
                return false;
            }

            if ( $( '[name="goyopopup[always_flag]"]:checked', $form ).length == 0 ) {
                if ( ! $( '[name="goyopopup[start_date]"]', $form ).val() || ! $( '[name="goyopopup[end_date]"]', $form ).val() ) {
                    alert( goyoPopupTranslate( '팝업 게시기간을 설정해주세요.' ) );
                    return false;
                }
            }

            if ( confirm( goyoPopupTranslate( '팝업을 저장하시겠습니까?' ) ) ) {

                $.ajax( {
                    url: ajaxurl,
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function ( res ) {

                        if ( res && res.success ) {
                            $.magnificPopup.close();
                            alert( goyoPopupTranslate( '저장되었습니다.' ) );
                            window.location.reload();
                            return;
                        }

                        alert( ( res && res.data ) ? res.data : goyoPopupTranslate( '저장에 실패했습니다.' ) );

                    },
                    error: function () {
                        alert( goyoPopupTranslate( '저장 요청 처리 중 오류가 발생했습니다. 페이지를 새로고침한 뒤 다시 시도해 주세요.' ) );
                    }
                } );

            }

        } catch ( e ) {

            alert( e.message );
            location.reload();

        }

        return false;

    } );

    $( document ).on( 'submit', '.goyopopup_config_form', function () {

        try {

            var $form   = $( this );
            var postCfg = $form.serialize();

            if ( confirm( goyoPopupTranslate( '설정을 저장하시겠습니까?' ) ) ) {
                $.ajax( {
                    url: ajaxurl,
                    type: 'POST',
                    data: postCfg,
                    dataType: 'json',
                    success: function ( res ) {
                        if ( res && res.success ) {
                            alert( goyoPopupTranslate( '설정이 저장되었습니다' ) );
                        } else {
                            alert( ( res && res.data ) ? res.data : goyoPopupTranslate( '저장에 실패했습니다.' ) );
                        }
                    },
                    error: function () {
                        alert( goyoPopupTranslate( '저장 요청 처리 중 오류가 발생했습니다.' ) );
                    }
                } );

            }

        } catch ( e ) {

            alert( e.message );
            location.reload();

        }

        return false;

    } );

}



/**
 * Sortable List
 *
 * @author STUDIO-GOYO (201)
 */
function sortable_handle() {

    if ( ! $( '.goyo_popup_item_wrap' ).length || typeof $.fn.sortable !== 'function' ) {
        return;
    }

    $( '.goyo_popup_item_wrap' ).each( function () {
        var $w = $( this );
        if ( $w.data( 'ui-sortable' ) ) {
            try {
                $w.sortable( 'destroy' );
            } catch ( e ) {
                return;
            }
        }
    } );

    $( '.goyo_popup_item_wrap' ).sortable( {
        items       : '.goyo_popup_item:not(.add_button)',
        connectWith : '.goyo_popup_item_wrap',
        update      : function ( event, ui ) {

                        try {

                            var $wrap           = $( this ).closest( '.goyo_popup_list_box' );
                            var data            = $( '.goyopopup_sort_form' ).serializeObject();
                            var tmp_title       = goyoPopupTranslate( '게시 중지' );

                            data.type           = 'close';
                            data.goyopopup_ids    = [];

                            if ( $wrap.hasClass( 'goyo_popup_coming_box' ) ) {

                                data.type = 'pending';

                            } else if ( $wrap.hasClass( 'goyo_popup_publish_box' ) ) {

                                data.type = 'publish';

                            }

                            $wrap.find( 'h2 span.list_count' ).text( $wrap.find( '.goyo_popup_item:not(.add_button)' ).length );

                            if ( $( this ).find( '.goyo_popup_item[data-goyopopup-id]' ).length > 0 ) {

                                $( this ).find( '.goyo_popup_item[data-goyopopup-id]' ).each( function () {

                                    data.goyopopup_ids.push( $( this ).data( 'goyopopup-id' ) );

                                } );

                                $.post( ajaxurl, data, function ( res ) {

                                    if ( res.success ) {

                                        $( res.data ).each( function ( idx, item ) {

                                            var $target = $( '.goyo_popup_item[data-goyopopup-id="' + item.id + '"]' );

                                            if ( item.always_flag == 'Y' ) {

                                                $( '.post_date', $target ).text( goyoPopupTranslate( '항상 노출' ) );

                                            } else {

                                                var str_date = '';

                                                if ( item.start_date ) {

                                                    str_date += item.start_date;

                                                }

                                                if ( item.end_date ) {

                                                    str_date += ' ~ ' + item.end_date;

                                                }

                                                $( '.post_date', $target ).text( str_date );

                                            }

                                            if ( data.type == 'close' ) {

                                                if ( ! $target.hasClass( 'term_end_item' ) ) {

                                                    $target.addClass( 'term_end_item' );

                                                }

                                                if ( ( ( new Date() ) > ( new Date( item.end_date ) ) ) && ( $( '.term_end_sticker', $target ).length == 0 ) ) {

                                                    $( '.goyo_popup_item_fig', $target ).append( $( '<p />', { class: 'term_end_sticker', html: $( '<span />', { text: goyoPopupTranslate( '기간종료' ) } ) } ) );

                                                }

                                            } else if ( $target.hasClass( 'term_end_item' ) ) {

                                                $target.removeClass( 'term_end_item' );
                                                $( '.term_end_sticker', $target ).remove();

                                            }

                                        } );

                                    } else if ( res.data ) {

                                        alert( res.data );
                                        location.reload();

                                    }

                                } );

                            }

                        } catch ( e ) {

                            alert( e.message );
                            location.reload();

                        }

                    }
    } );

}


function page_exposure_list_init() {

    if ( typeof $.fn.select2 === 'undefined' ) {
        return;
    }

    $( '.page_exposure_select' ).each( function () {
        if ( ! $( this ).data( 'select2' ) ) {
            $( this ).val( '' ).select2( {
                dropdownParent: $( '.page_exposure_container' ),
                multiple: 1,
                maximumSelectionSize: 1,
                ajax: {
                    url: ( typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php' ),
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: function ( params ) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            action: 'goyopopup_get_post_list',
                            nonce: ( typeof goyopopupAdmin !== 'undefined' ? goyopopupAdmin.noncePostList : '' ),
                        };
                        return query;
                    },
                },
            } );

        }

    } );

}

function page_exposure_list_action() {
    $( document ).on( 'select2:select', '.page_exposure_select', function ( e ) {

        var $this = $( this );

        if ( $this.val() ) {

            var post_id     = String( $this.val() );
            var $option     = $this.find( 'option:selected' );
            var $template   = $( $( '#jtPostSelected' ).html() );

            $( '[name="goyopopup[post_ids][]"]', $template ).val( post_id );
            $( 'span', $template ).text( $option.text() );

            $option.prop( 'disabled', true );

            $template.appendTo( '.page_exposure' );

        }

        $( this ).val( [] ).trigger( 'change' );

        page_exposure_list_init();

    } );

    $( document ).on( 'click', '.page_exposure_delete', function () {

        var $this = $( this );

        $this.closest( 'li' ).fadeOut( 'fast', function () {

            var post_id = $( 'input[name="goyopopup[post_ids][]"]', $( this ) ).val();

            $( '.page_exposure_select' ).find( 'option[value=' + post_id + ']' ).prop( 'disabled', false );
            page_exposure_list_init();

            $( this ).remove();

        } );

        return false;

    } );

}



/* ********************************************* *
 * HELPERS
 * ********************************************* */
// SIMPLE MOBILE CHECK
function is_mobile() {

    return ( /Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i ).test( navigator.userAgent || navigator.vendor || window.opera );

}

// HEX 색상 + 투명도(0~1) -> "rgba(r, g, b, a)" 변환 (그림자 미리보기가 실제 사이트 렌더링과 같은 값을 쓰도록)
function goyo_hex_to_rgba( hex, alpha ) {

    hex = ( hex || '' ).replace( '#', '' );

    if ( hex.length === 3 ) {

        hex = hex[ 0 ] + hex[ 0 ] + hex[ 1 ] + hex[ 1 ] + hex[ 2 ] + hex[ 2 ];

    }

    if ( ! /^[0-9a-fA-F]{6}$/.test( hex ) ) {

        hex = '000000';

    }

    var r = parseInt( hex.substring( 0, 2 ), 16 );
    var g = parseInt( hex.substring( 2, 4 ), 16 );
    var b = parseInt( hex.substring( 4, 6 ), 16 );
    var a = ( isNaN( alpha ) ? 1 : Math.min( 1, Math.max( 0, alpha ) ) );

    return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';

}



// GOYO POPUP PREVIEW
function goyopopup_show() {

    var $container      = $( '#goyo_popup_container' );
    var $popup_group    = $( '#goyo_popup_playground', $container );
    var $overlay        = $( '#goyo_popup_overlay', $container );
    var is_mobile       = $container.hasClass( 'mobile' );

    $( '#goyo_popup_overlay, .goyo_popup_item', $container ).css( 'z-index', 99999 );
    $overlay.stop( true, true ).fadeIn( 'fast' );

    if ( is_mobile ) { // START DEVICE MOBILE POPUP

        $overlay.fadeIn( 'fast' );
        $( '#goyo_popup_mobile_close_all' ).fadeIn( 'fast' );

        $popup_group.css( {
            opacity     : 1,
            visibility  : 'visible'
        } );

    }

    function show_popup_item( $popup_item ) {
        $popup_item.css( { 'position' : 'fixed' } );

        // CENTER POPUP
        if ( ! parseInt( $popup_item.css( 'left' ).replace( 'px', '' ) ) &&
            ! parseInt( $popup_item.css( 'top' ).replace( 'px', '' ) ) ) {

            $popup_item.css( {
                'margin-top'  : - ( $popup_item.height() / 2 ) + 'px',
                'margin-left' : - ( $popup_item.width() / 2 ) + 'px',
                'top'         : '50%',
                'left'        : '50%'
            } );

        }

        // 모바일에서는 X축 중앙 + 상단 25% 위치를 고정한다.
        if ( is_mobile ) {
            var popup_half_width = $popup_item.outerWidth() / 2;

            if ( $popup_item.hasClass( 'goyo_popup_item_text_only' ) ) {
                $popup_item.css( {
                    'top'         : '25%',
                    'left'        : '5%',
                    'margin-top'  : '0',
                    'margin-left' : '0',
                    'transform'   : 'none'
                } );
            } else {
                $popup_item.css( {
                    'top'         : '25%',
                    'left'        : '50%',
                    'margin-top'  : '0',
                    'margin-left' : - popup_half_width + 'px',
                    'transform'   : 'none'
                } );
            }
        }

        $popup_item.fadeIn( 'fast' );

        if ( ! is_mobile ) {

            // POPUP DRAG - jQuery UI
            $popup_item.draggable( {
                stop    : function ( event, ui ) {

                            if ( ui.position ) {

                                console.log( ui.position );

                            }

                        }
            } );

        }
    }

    $( '.goyo_popup_item', $container ).each( function () {

        var $this = $( this );
        var has_image = ( $this.find( 'img' ).length > 0 );

        if ( has_image ) {
            $this.imagesLoaded( function () {
                show_popup_item( $this );
            } );
        } else {
            show_popup_item( $this );
        }

    } );

    // CLOSE ALL POPUP
    // 임시로 배경 클릭으로 팝업 닫기 기능 비활성화 (overlay 제외)
    $( '.goyo_popup_notoday, .goyo_popup_close, #goyo_popup_mobile_close_all', $container ).on ( 'click', function () {

        $container.fadeOut( 'fast', function () { $( this ).remove(); } );
        return false;

    } );



}



} );



// jQuery DatePicker Default Setting ( KOR )
// 설정 페이지 등 datepicker 위젯을 로드하지 않는 화면도 이 스크립트 번들을 공유하므로 존재 여부를 확인한다.
if ( typeof jQuery.datepicker !== 'undefined' ) {

    jQuery.datepicker.setDefaults( {
        dateFormat          : 'yy-mm-dd',
        prevText            : goyoPopupTranslate( '이전 달' ),
        nextText            : goyoPopupTranslate( '다음 달' ),
        monthNames          : [ goyoPopupTranslate( '1월' ), goyoPopupTranslate( '2월' ), goyoPopupTranslate( '3월' ), goyoPopupTranslate( '4월' ), goyoPopupTranslate( '5월' ), goyoPopupTranslate( '6월' ), goyoPopupTranslate( '7월' ), goyoPopupTranslate( '8월' ), goyoPopupTranslate( '9월' ), goyoPopupTranslate( '10월' ), goyoPopupTranslate( '11월' ), goyoPopupTranslate( '12월' ) ],
        monthNamesShort     : [ goyoPopupTranslate( '1월' ), goyoPopupTranslate( '2월' ), goyoPopupTranslate( '3월' ), goyoPopupTranslate( '4월' ), goyoPopupTranslate( '5월' ), goyoPopupTranslate( '6월' ), goyoPopupTranslate( '7월' ), goyoPopupTranslate( '8월' ), goyoPopupTranslate( '9월' ), goyoPopupTranslate( '10월' ), goyoPopupTranslate( '11월' ), goyoPopupTranslate( '12월' ) ],
        dayNames            : [ goyoPopupTranslate( '일' ), goyoPopupTranslate( '월' ), goyoPopupTranslate( '화' ), goyoPopupTranslate( '수' ), goyoPopupTranslate( '목' ), goyoPopupTranslate( '금' ), goyoPopupTranslate( '토' ) ],
        dayNamesShort       : [ goyoPopupTranslate( '일' ), goyoPopupTranslate( '월' ), goyoPopupTranslate( '화' ), goyoPopupTranslate( '수' ), goyoPopupTranslate( '목' ), goyoPopupTranslate( '금' ), goyoPopupTranslate( '토' ) ],
        dayNamesMin         : [ goyoPopupTranslate( '일' ), goyoPopupTranslate( '월' ), goyoPopupTranslate( '화' ), goyoPopupTranslate( '수' ), goyoPopupTranslate( '목' ), goyoPopupTranslate( '금' ), goyoPopupTranslate( '토' ) ],
        showMonthAfterYear  : true,
        yearSuffix          : goyoPopupTranslate( '년' )
    } );

}
