/*
 * File : js/goyo-popup.js
 * Author : GOYO (KMS)
 * Guideline: GOYOstyle.1.1
 *
 * SUMMARY:
 * 1)
 */

window.GOYO_POPUP = {};

jQuery( function ( $ ) {
    window.GOYO_POPUP.init = function () {
        var $container = $( '#goyo_popup_container' );
        var $popup_group = $( '#goyo_popup_playground' );
        var $overlay = $( '#goyo_popup_overlay' );
        var is_mobile = checkDeviceType();
        var popup_count = 0; // Popup Number
        var language = $( 'html' ).attr( 'lang' ) ;
        var home_url = '';

        try {
            home_url = ( window.__GOYOP.home_url || '' );
        } catch ( e ) { }

        $.ajax( {
            url: home_url + '/wp-json/goyo-popup/list/',
            method: 'get',
            data: {
                type: ( is_mobile ? 'mobile' : 'pc' ),
                location: location.href,
                language: ( typeof window.__GOYOP.language !== 'undefined' ? window.__GOYOP.language : language ),
                is_home : ( typeof window.__GOYOP.is_home !== 'undefined' ? window.__GOYOP.is_home : $( 'body' ).hasClass( 'home' ) ),
                blog_id : ( typeof window.__GOYOP.blog_id !== 'undefined' ? window.__GOYOP.blog_id : null )
            },
            success: function ( res ) {
                if ( res && typeof res.html !== 'undefined' ) {
                    $( 'body' ).append( res.html );

                    $container = $( '#goyo_popup_container' );
                    $popup_group = $( '#goyo_popup_playground' );
                    $overlay = $( '#goyo_popup_overlay' );

                    run();
                }
            },
            error : function ( res ) {
                $.ajax( {
                    url: home_url + '/wp-admin/admin-ajax.php',
                    method: 'get',
                    data: {
                        action: 'goyo_popup',
                        type: ( is_mobile ? 'mobile' : 'pc' ),
                        location: location.href,
                        language: ( typeof window.__GOYOP.language !== 'undefined' ? window.__GOYOP.language : language ),
                        is_home : ( typeof window.__GOYOP.is_home !== 'undefined' ? window.__GOYOP.is_home : $( 'body' ).hasClass( 'home' ) ),
                        blog_id : ( typeof window.__GOYOP.blog_id !== 'undefined' ? window.__GOYOP.blog_id : null )
                    },
                    success: function ( res ) {
                        if ( res && typeof res.html !== 'undefined' ) {
                            $( 'body' ).append( res.html );

                            $container = $( '#goyo_popup_container' );
                            $popup_group = $( '#goyo_popup_playground' );
                            $overlay = $( '#goyo_popup_overlay' );

                            run();
                        }
                    }
                } );
            }
        } );

        function dismissCookieMatchesSiteToday( cookieRaw ) {
            try {
                var siteToday = ( window.__GOYOP && window.__GOYOP.site_calendar_date ) ? window.__GOYOP.site_calendar_date : '';
                if ( ! cookieRaw || ! siteToday ) {
                    return false;
                }
                var valueTrim = $.trim( String( cookieRaw ) );
                return /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test( valueTrim ) && valueTrim === siteToday;
            } catch ( ignore ) {
                return false;
            }
        }

        function run() {
            $( '.goyo_popup_item' ).each( function () {
                if ( dismissCookieMatchesSiteToday( $.cookie( $( this ).attr( 'id' ) ) ) ) {
                    $( this ).remove();
                } else {
                    popup_count++;
                }
            } );

            // Start Device PC Popup
            if ( is_mobile == false ) {
                $( '.goyo_popup_item' ).each( function () {
                    var $this = $( this );

                    $this.imagesLoaded( function () {
                        // Single Popup (Only One)
                        if ( popup_count == 1 && ! parseInt( $( '.goyo_popup_item' ).css( 'left' ).replace( 'px', '' ) ) && ! parseInt( $( '.goyo_popup_item' ).css( 'top' ).replace( 'px', '' ) ) ) {
                            apply_single_popup_position( $( '.goyo_popup_item' ) );
                        }
                        
                        // Multiple Popups - Center positioning
                        if ( popup_count > 1 ) {
                            var $allPopups = $( '.goyo_popup_item' );
                            var totalWidth = 0;
                            var maxHeight = 0;
                            
                            // Add multiple class for styling
                            $allPopups.addClass( 'multiple' );
                            
                            // Calculate total width and max height
                            $allPopups.each( function () {
                                totalWidth += $( this ).outerWidth( true );
                                maxHeight = Math.max( maxHeight, $( this ).outerHeight( true ) );
                            } );
                            
                            var startX = ( $( window ).width() - totalWidth ) / 2;
                            var startY = ( $( window ).height() - maxHeight ) / 2;
                            var currentX = startX;
                            
                            // Position each popup
                            $allPopups.each( function ( index ) {
                                var $popup = $( this );
                                var popupWidth = $popup.outerWidth( true );
                                
                                $popup.css( {
                                    'position': 'fixed',
                                    'top': startY + 'px',
                                    'left': currentX + 'px',
                                    'z-index': 100010 + index
                                } );
                                
                                currentX += popupWidth + 20; // 20px gap between popups
                            } );
                        }

                        // Show Popup
                        if ( ! $overlay.is( ':visible' ) ) {
                            $overlay.fadeIn( 'fast' );
                        }

                        $this.fadeIn( 'fast' );

                        // Popup Drag - jQuery UI
                        $this.draggable( {
                            start: function ( event, ui ) {
                                var popupZIndex = 0;

                                $( '.goyo_popup_item' ).each( function () {
                                    popupZIndex = ( popupZIndex < parseInt( $( this ).css( 'z-index' ) ) ? parseInt( $( this ).css( 'z-index' ) ) : popupZIndex );
                                } );

                                $this.css( 'z-index', popupZIndex + 1 );
                            }
                        } );
                    } );
                } );

                // Custom Cursor & Close All Popup
                // 임시로 배경 클릭으로 팝업 닫기 기능 비활성화
                /*
                $overlay.on( 'click', function () { // Close All
                    $container.fadeOut( 'fast', function () {
                        $( this ).remove();
                    } );
                } );
                */
            }
            // End Device PC Popup

            // Start Device Mobile Popup
            if ( is_mobile == true ) {
                if ( $( '.goyo_popup_item' ).length > 0 ) {
                    // Scroll Remove
                    $( 'body' ).addClass( 'goyo_popup_scroll_remove' );

                    // Overlay Height 100%
                    window.scroll( function () {
                        $overlay.height( window.innerHeight );
                    } );

                    // Two Or More slide
                    if ( $( '.goyo_popup_item' ).length > 1 ) {
                        // Show Popup
                        $overlay.fadeIn( 'fast' );
                        // $( '#goyo_popup_mobile_close_all' ).fadeIn( 'fast' );

                        $popup_group.css( {
                            opacity: 1,
                            visibility: 'visible'
                        } );

                        // Overlapping positioning for mobile
                        var $allPopups = $( '.goyo_popup_item' );
                        var overlapX = 20;
                        var overlapY = 20;
                        
                        // Add overlapping class
                        $allPopups.addClass( 'overlapping' );

                        // Position each popup with overlap
                        $allPopups.each( function ( index ) {
                            var $popup = $( this );
                            var offsetX = index * overlapX;
                            var offsetY = index * overlapY;
                            var popupWidth = getPopupWidth( $popup, true );
                            var popupHeight = getPopupHeight( $popup, true );

                            // 뷰포트 520px 이하에서는 무슨 일이 있어도 폭이 320px 을 넘지 않도록 강제 (원본 비율 유지)
                            if ( $( window ).width() <= 520 ) {
                                var clampedMulti = clamp_popup_size_by_width( popupWidth, popupHeight, 320 );
                                popupWidth = clampedMulti.width;
                                popupHeight = clampedMulti.height;
                            }

                            var centerX = ( $( window ).width() - popupWidth ) / 2;
                            var centerY = ( $( window ).height() - popupHeight ) / 2;
                            
                            $popup.css( {
                                'position': 'fixed',
                                'top': ( centerY + offsetY ) + 'px',
                                'left': ( centerX + offsetX ) + 'px',
                                'z-index': 100010 + index,
                                'width': popupWidth + 'px',
                                'height': popupHeight + 'px'
                            } );
                        } );
                    // Only One Slide
                    } else {
                        $( '#goyo_popup_container' ).addClass( 'only_one_slide' );

                        $( '.goyo_popup_item' ).each( function () {
                            var $this = $( this );

                            function renderSingleMobilePopup() {
                                var is_text_only = $this.hasClass( 'goyo_popup_item_text_only' );
                                var viewport_width = $( window ).width();
                                var popup_width = getPopupWidth( $this, true );
                                var popup_height = getPopupHeight( $this, true );
                                var max_width = Math.floor( viewport_width * 0.9 );

                                // 뷰포트 520px 이하에서는 무슨 일이 있어도 폭이 320px 을 넘지 않도록 강제
                                if ( viewport_width <= 520 && max_width > 320 ) {
                                    max_width = 320;
                                }

                                if ( is_text_only ) {

                                    popup_width = max_width;

                                } else {

                                    // 폭을 줄여야 하면 원본 비율 그대로 세로도 함께 축소
                                    var clampedSingle = clamp_popup_size_by_width( popup_width, popup_height, max_width );
                                    popup_width = clampedSingle.width;
                                    popup_height = clampedSingle.height;

                                }

                                $this.css( {
                                    'position': 'fixed',
                                    'top': '25%',
                                    'left': ( is_text_only ? '5%' : '50%' ),
                                    'margin-left': ( is_text_only ? '0' : - ( popup_width / 2 ) + 'px' ),
                                    'width': popup_width + 'px',
                                    'height': ( is_text_only ? 'auto' : popup_height + 'px' )
                                } );

                                if ( ! $overlay.is( ':visible' ) ) {
                                    $overlay.fadeIn( 'fast' );
                                }

                                $this.fadeIn( 'fast' );
                            }

                            if ( $this.find( 'img' ).length > 0 ) {
                                $this.imagesLoaded( function () {
                                    renderSingleMobilePopup();
                                } );
                            } else {
                                renderSingleMobilePopup();
                            }
                        } );
                    }

                    // Close All
                    /* $( '#goyo_popup_mobile_close_all' ).on( 'click', function ( e ) {
                        e.preventDefault();

                        // Scroll Active
                        $( 'body' ).removeClass( 'goyo_popup_scroll_remove' );

                        $container.fadeOut( 'fast', function () {
                            $( this ).remove();
                        } );
                    } ); */
                }
            }
            // End Device Mobile Popup

            // Close Popup
            $( '.goyo_popup_close' ).on( 'click', function ( e ) {
                e.preventDefault();

                if ( $( '.goyo_popup_item' ).length > 0 ) { // Single
                    if ( is_mobile == true ) {
                        // Scroll Active
                        $( 'body' ).removeClass( 'goyo_popup_scroll_remove' );
                    }

                    if ( $( '.goyo_popup_item' ).length > 1 ) { // two or more
                        // Remove the clicked popup
                        $( this ).parents( '.goyo_popup_item' ).fadeOut( 'fast', function () {
                            $( this ).remove();
                            
                            // Check if there are remaining popups
                            if ( $( '.goyo_popup_item' ).length == 0 ) {
                                // Scroll Active
                                if ( is_mobile == true ) {
                                    $( 'body' ).removeClass( 'goyo_popup_scroll_remove' );
                                }
                                
                                $container.fadeOut( 'fast', function () {
                                    $( this ).remove();
                                } );
                            }
                        } );
                    } else { // Single popup
                        $container.fadeOut( 'fast', function () {
                            $( this ).remove();
                        } );
                    }
                }
            } );

            $( '.goyo_popup_notoday' ).on( 'click', function ( e ) {
                e.preventDefault();
                var parent_wrap = $( this ).parents( '.goyo_popup_item' );
                var dismissVal = '';
                try {
                    dismissVal = ( window.__GOYOP && window.__GOYOP.site_calendar_date ) ? window.__GOYOP.site_calendar_date : '';
                } catch ( err2 ) {
                    dismissVal = '';
                }
                if ( ! dismissVal ) {
                    return false;
                }
                $.cookie( parent_wrap.attr( 'id' ), dismissVal, { expires: 8, path: '/' } );

                // Popup Close
                if ( $( '.goyo_popup_item' ).length > 0 ) { // Single
                    if ( is_mobile == true ) {
                        // Scroll Active
                        $( 'body' ).removeClass( 'goyo_popup_scroll_remove' );
                    }

                    if ( $( '.goyo_popup_item' ).length > 1 ) { // two or more
                        // Remove the clicked popup
                        $( this ).parents( '.goyo_popup_item' ).fadeOut( 'fast', function () {
                            $( this ).remove();
                            
                            // Check if there are remaining popups
                            if ( $( '.goyo_popup_item' ).length == 0 ) {
                                // Scroll Active
                                if ( is_mobile == true ) {
                                    $( 'body' ).removeClass( 'goyo_popup_scroll_remove' );
                                }
                                
                                $container.fadeOut( 'fast', function () {
                                    $( this ).remove();
                                } );
                            }
                        } );
                    } else { // Single popup
                        $container.fadeOut( 'fast', function () {
                            $( this ).remove();
                        } );
                    }
                }
            } );

        }
    }

    /**
     * 팝업 1개만 노출될 때(PC)의 위치 결정 - 관리자 설정(센터/왼쪽) 반영.
     * 뷰포트 너비가 820px 이하면 설정과 무관하게 항상 화면 중앙에 표시한다.
     */
    function apply_single_popup_position( $popup ) {
        var POSITION_FORCE_CENTER_WIDTH = 820;
        var position = ( window.__GOYOP && window.__GOYOP.position ) ? window.__GOYOP.position : { type: 'center' };
        var useLeft = ( position.type === 'left' && $( window ).width() > POSITION_FORCE_CENTER_WIDTH );

        if ( useLeft ) {
            var leftValue = ( position.left && typeof position.left.value !== 'undefined' ) ? position.left.value : 50;
            var leftUnit  = ( position.left && position.left.unit ) ? position.left.unit : 'px';
            var topValue  = ( position.top && typeof position.top.value !== 'undefined' ) ? position.top.value : 50;
            var topUnit   = ( position.top && position.top.unit ) ? position.top.unit : 'px';

            $popup.css( {
                'position'    : 'fixed',
                'margin-top'  : 0,
                'margin-left' : 0,
                'top'         : topValue + topUnit,
                'left'        : leftValue + leftUnit
            } );
        } else {
            $popup.css( {
                'position'    : 'fixed',
                'margin-top'  : - ( $popup.height() / 2 ) + 'px',
                'margin-left' : - ( $popup.width() / 2 ) + 'px',
                'top'         : '50%',
                'left'        : '50%'
            } );
        }
    }

    /**
     * 폭이 maxWidth 를 넘으면 원본 비율(width:height) 그대로 세로도 함께 줄여서 반환.
     * 넘지 않으면 원본 값 그대로 반환.
     */
    function clamp_popup_size_by_width( width, height, maxWidth ) {
        if ( ! ( width > maxWidth ) ) {
            return { width: width, height: height };
        }

        return {
            width: maxWidth,
            height: Math.round( height * ( maxWidth / width ) )
        };
    }

    function getPopupWidth( $popup, is_mobile ) {
        var popupWidth = parseInt( $popup.data( 'popup-width' ), 10 );
        if ( popupWidth > 0 ) {
            return popupWidth;
        }

        if ( is_mobile ) {
            return 300;
        }

        return $popup.outerWidth( true );
    }

    function getPopupHeight( $popup, is_mobile ) {
        var popupHeight = parseInt( $popup.data( 'popup-height' ), 10 );
        if ( popupHeight > 0 ) {
            return popupHeight;
        }

        if ( is_mobile ) {
            return 383;
        }

        return $popup.outerHeight( true );
    }

    window.GOYO_POPUP.init();

    // Device type
    function checkDeviceType() {
        var ua = navigator.userAgent.toLowerCase(),
            is = function(t) {
                return ua.indexOf(t) > -1;
            };

        var isMobileOrTablet = [
            is('android'),
            is('tablet'),
            is('j2me'),
            is('ipad; u; cpu os'),
            is('ipad;u;cpu os'),
            is('iphone'),
            is('ipod'),
            is('ipad'),
            (is('mac') && navigator.maxTouchPoints > 1),
            (is('x11') || (!is('android') && is('linux'))) && navigator.maxTouchPoints > 1
        ].some(function(condition) {
            return condition === true;
        });

        return isMobileOrTablet;
    }
} ); // End jQuery
