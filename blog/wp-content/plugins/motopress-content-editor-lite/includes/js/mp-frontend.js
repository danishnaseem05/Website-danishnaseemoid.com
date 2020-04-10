(function ($) {
    'use strict';
    function convertDataAttrToBool(attr) {
        if (typeof attr === 'undefined') {
            return false;
        }
        attr = attr.toLowerCase();
        return !(attr === '' || attr === 'false' || attr === 'off' || attr === '0');
    }
    (function ($) {
        $(function () {
            var FULLWIDTH_CLASS = 'mp-row-fullwidth', FULLWIDTH_CONTENT_CLASS = 'mp-row-fullwidth-content', FIXED_WIDTH_CONTENT_CLASS = 'mp-row-fixed-width-content';
            var isEditor = function isEditor() {
                return parent.hasOwnProperty('MP') && parent.MP.hasOwnProperty('Editor');
            };
            var resetRow = function resetRow(el) {
                el.css({
                    'width': '',
                    'padding-left': '',
                    'margin-left': '',
                    'padding-right': '',
                    'margin-right': ''
                });
            };
            motopressUpdateFullwidthRow();
            if (isEditor()) {
                parent.MP.Editor.onIfr('Resize EditorLoad LeftBarShow LeftBarHide', function () {
                    motopressUpdateFullwidthRow();
                });
            }
            var motopressUpdateFullwidthRowTimeout;
            $(window).resize(function () {
                if (motopressUpdateFullwidthRowTimeout)
                    clearTimeout(motopressUpdateFullwidthRowTimeout);
                motopressUpdateFullwidthRowTimeout = setTimeout(function () {
                    motopressUpdateFullwidthRow();
                }, 500);
            });
            function motopressUpdateFullwidthRow() {
                var fullWidthRows = $('.mp-row-fluid.' + FULLWIDTH_CLASS + ', .mp-row-fluid.' + FULLWIDTH_CONTENT_CLASS + ', .mp-row-fluid.' + FIXED_WIDTH_CONTENT_CLASS);
                $.each(fullWidthRows, function () {
                    var $html = $('html');
                    var $row = $(this);
                    var rowWidth = $html.width();
                    resetRow($row);
                    var isFullWidthContent = $row.hasClass(FULLWIDTH_CONTENT_CLASS);
                    var isFixedWidthContent = $row.hasClass(FIXED_WIDTH_CONTENT_CLASS);
                    var rowOffsetLeft = $row.offset().left;
                    if (isEditor()) {
                        rowOffsetLeft -= CE.LeftBar.myThis.getSpace();
                    }
                    var rowOffsetRight = rowWidth - rowOffsetLeft - $row.width();
                    var rowPaddingLeft, rowPaddingRight;
                    rowPaddingLeft = rowPaddingRight = '';
                    if (isFixedWidthContent) {
                        var $clmns = $row.children('.motopress-clmn');
                        if ($clmns.length) {
                            var clmnsWidth, leftoverPadding, _rowPaddingLeft, _rowPaddingRight;
                            $row.css('max-width', parseInt(MPCEVars.fixedRowWidth));
                            clmnsWidth = $row.innerWidth();
                            $row.css('max-width', '');
                            $row.css('width', rowWidth);
                            rowPaddingLeft = parseInt($row.css('padding-left'));
                            rowPaddingRight = parseInt($row.css('padding-right'));
                            leftoverPadding = ($row.innerWidth() - rowPaddingLeft - rowPaddingRight - clmnsWidth) / 2;
                            leftoverPadding = leftoverPadding > 0 ? leftoverPadding : 0;
                            _rowPaddingLeft = rowPaddingLeft + leftoverPadding;
                            _rowPaddingRight = rowPaddingRight + leftoverPadding;
                            rowPaddingLeft = _rowPaddingLeft > 0 ? _rowPaddingLeft : rowPaddingLeft;
                            rowPaddingRight = _rowPaddingRight > 0 ? _rowPaddingRight : rowPaddingRight;
                        }
                    } else if (!isFullWidthContent) {
                        rowPaddingLeft = rowOffsetLeft - parseInt($row.css('border-left-width')) + parseInt($row.css('padding-left'));
                        rowPaddingRight = rowOffsetRight - parseInt($row.css('border-right-width')) - parseInt($row.css('padding-right'));
                    }
                    $row.css({
                        'width': rowWidth,
                        'padding-left': rowPaddingLeft,
                        'margin-left': -rowOffsetLeft,
                        'padding-right': rowPaddingRight,
                        'margin-right': -rowOffsetRight
                    });
                });
                $(window).trigger('mpce-row-size-update');
            }
        });
    }(jQuery));
    jQuery(window).load(function () {
        if (typeof jQuery.fn.stellar === 'undefined')
            return false;
        jQuery.stellar({
            horizontalScrolling: false,
            verticalScrolling: true,
            responsive: true
        });
    });
    $(window).load(function () {
        mpFixBackgroundVideoSize();
    });
    $(window).resize(function () {
        if (this.mpResizeTimeout)
            clearTimeout(this.mpResizeTimeout);
        this.mpResizeTimeout = setTimeout(function () {
            $(this).trigger('mpResizeEnd');
        }, 500);
    });
    $(window).on('mpResizeEnd mpce-row-size-update', function () {
        mpFixBackgroundVideoSize();
    });
    $(window).on('MPCE-RenderRowYoutubeBG', function (e, el) {
        mpInitYouTubePlayers($(el).children('.mp-video-container').find('.mp-youtube-video'));
        mpFixBackgroundVideoSize($(el).children('.mp-video-container'));
    });
    $(window).on('MPCE-RenderRowVideoBG', function (e, el) {
        mpFixBackgroundVideoSize($(el).children('.mp-video-container'));
    });
    function onYouTubeIframeAPIReady() {
        mpInitYouTubePlayers();
    }
    function mpInitYouTubePlayers(players) {
        if (typeof players === 'undefined') {
            players = $('.mp-video-container>.mp-youtube-container>.mp-youtube-video');
        }
        players.each(function (index, player) {
            var $player = $(player);
            var ytplayer = new YT.Player(players[index], {
                videoId: $player.attr('data-src'),
                events: { 'onReady': mpCreateYTEvent(index) }
            });
            $player.data('ytplayer', ytplayer);
        });
        function mpCreateYTEvent(index) {
            return function (evt) {
                var $player = $(players[index]);
                if ($player.attr('data-mute') === '1') {
                    evt.target.mute();
                }
            };
        }
    }
    $('.mp-video-container').on('click', function (e) {
        if ($(this).children('video').length) {
            var player = $(this).children('video')[0];
            if (player.paused) {
                player.play();
            } else {
                player.pause();
            }
        } else {
            var player = $(this).find('iframe.mp-youtube-video').data('ytplayer');
            if (player) {
                if (player.getPlayerState() === 2) {
                    player.playVideo();
                } else {
                    player.pauseVideo();
                }
            }
        }
    });
    $('.mp-row-video').on('click', function (e) {
        if ($(e.target).is('.mp-row-fluid')) {
            $(this).children('.mp-video-container').trigger('click');
        } else if ($(e.target).is('[class*=mp-span]')) {
            $(this).closest('.mp-row-video').children('.mp-video-container').trigger('click');
        }
    });
    function mpFixBackgroundVideoSize(videos) {
        if (typeof videos === 'undefined') {
            videos = $('.mp-video-container');
        }
        $.each(videos, function (index) {
            mpFixVideoSize(videos[index]);
        });
    }
    function mpRememberOriginalSize(video) {
        if (!video.originalsize) {
            video.originalsize = {
                width: video.width(),
                height: video.height()
            };
        }
    }
    function mpFixVideoSize(div) {
        var video, fixHeight;
        if ($(div).children().is('video')) {
            video = $(div).children();    
        } else {
            video = $(div).find('iframe');
            if (!video.length) {
                video = $(div).find('img');
            }
        }
        mpRememberOriginalSize(video);
        var targetwidth = $(div).width();
        var targetheight = $(div).height();
        var srcwidth = video.originalsize.width;
        var srcheight = video.originalsize.height;
        var scaledVideo = mpScaleVideo(srcwidth, srcheight, targetwidth, targetheight);
        $(div).find('.mp-youtube-container').height(scaledVideo.height);
        $(div).find('.mp-youtube-container').width(scaledVideo.width);
        video.width(scaledVideo.width);
        video.height(scaledVideo.height);
        video.css('max-width', scaledVideo.width);
        $(video).css('left', scaledVideo.targetleft);
        $(video).css('top', scaledVideo.targettop);
    }
    function mpScaleVideo(srcwidth, srcheight, targetwidth, targetheight) {
        var result = {
            width: 0,
            height: 0,
            fScaleToTargetWidth: true
        };
        if (srcwidth <= 0 || srcheight <= 0 || targetwidth <= 0 || targetheight <= 0) {
            return result;
        }
        var scaleX1 = targetwidth;
        var scaleY1 = srcheight * targetwidth / srcwidth;
        var scaleX2 = srcwidth * targetheight / srcheight;
        var scaleY2 = targetheight;
        var fScaleOnWidth = scaleX2 <= targetwidth;
        if (fScaleOnWidth) {
            result.width = Math.floor(scaleX1);
            result.height = Math.floor(scaleY1);
            result.fScaleToTargetWidth = true;
        } else {
            result.width = Math.floor(scaleX2);
            result.height = Math.floor(scaleY2);
            result.fScaleToTargetWidth = false;
        }
        result.targetleft = Math.floor((targetwidth - result.width) / 2);
        result.targettop = Math.floor((targetheight - result.height) / 2);
        return result;
    }
    (function ($) {
        $(document).ready(function () {
            var isEditor = parent.hasOwnProperty('MP') && parent.MP.hasOwnProperty('Editor');
            var magnificPopupExists = typeof $.fn.magnificPopup !== 'undefined';
            if (!isEditor && magnificPopupExists) {
                $('[data-action="motopressLightbox"]').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-img-mobile',
                    image: { verticalFit: true }
                });
                $('[data-action="motopressGalleryLightbox"]').magnificPopup({
                    type: 'image',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [
                            0,
                            1
                        ]    
                    }
                });
                var modalButtons = $('[data-action="motopress-modal"]');
                modalButtons.each(function () {
                    var modalButton = $(this);
                    var showAnimation = modalButton.attr('data-mfp-show-animation').length ? ' ' + modalButton.attr('data-mfp-show-animation') : '';
                    var hideAnimation = modalButton.attr('data-mfp-hide-animation').length ? ' ' + modalButton.attr('data-mfp-hide-animation') : '';
                    var hideAnimationDuration = hideAnimation !== '' ? 500 : 0;
                    var modalStyle = modalButton.attr('data-modal-style');
                    var uniqid = modalButton.attr('data-uniqid');
                    var uniqueClass = ' motopress-modal-' + uniqid;
                    modalButton.magnificPopup({
                        key: 'motopress-modal-obj',
                        mainClass: 'motopress-modal' + uniqueClass + ' ' + modalStyle,
                        midClick: true,
                        closeBtnInside: false,
                        fixedBgPos: true,
                        removalDelay: hideAnimationDuration,
                        closeMarkup: '<button title="%title%" class="motopress-modal-close"></button>',
                        callbacks: {
                            beforeOpen: function beforeOpen() {
                                modalButton.attr('disabled', true);
                                if (showAnimation) {
                                    var background = $(this.bgOverlay);
                                    var wrapper = $(this.wrap);
                                    background.add(wrapper).addClass(showAnimation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                        $(this).removeClass(showAnimation);
                                    });
                                }
                            },
                            beforeClose: function beforeClose() {
                                if (hideAnimation) {
                                    var background = $(this.bgOverlay);
                                    var wrapper = $(this.wrap);
                                    background.add(wrapper).addClass(hideAnimation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                        $(this).removeClass(hideAnimation);
                                    });
                                }
                            },
                            afterClose: function afterClose() {
                                modalButton.removeAttr('disabled');
                            }
                        }
                    });
                });
                var waypointExists = typeof $.fn.waypoint !== 'undefined';
                if (waypointExists) {
                    var popupTriggers = $('.motopress-popup-trigger');
                    popupTriggers.each(function () {
                        var popupTrigger = $(this);
                        var delay = popupTrigger.attr('data-delay').length ? parseInt(popupTrigger.attr('data-delay')) : 0;
                        var showAnimation = popupTrigger.attr('data-mfp-show-animation').length ? ' ' + popupTrigger.attr('data-mfp-show-animation') : '';
                        var hideAnimation = popupTrigger.attr('data-mfp-hide-animation').length ? ' ' + popupTrigger.attr('data-mfp-hide-animation') : '';
                        var hideAnimationDuration = hideAnimation !== '' ? 500 : 0;
                        var modalStyle = popupTrigger.attr('data-modal-style');
                        var uniqid = popupTrigger.attr('data-uniqid');
                        var uniqueClass = ' motopress-modal-' + uniqid;
                        var customClasses = popupTrigger.attr('data-custom-classes');
                        popupTrigger.waypoint(function (direction) {
                            this.enabled = false;
                            clearTimeout(window.motopressPopupTimeout);
                            window.motopressPopupTimeout = setTimeout(function () {
                                popupTrigger.magnificPopup({
                                    key: 'motopress-popup-obj' + uniqid,
                                    mainClass: 'motopress-modal ' + modalStyle + ' ' + customClasses + uniqueClass,
                                    closeBtnInside: false,
                                    fixedBgPos: true,
                                    removalDelay: hideAnimationDuration,
                                    closeMarkup: '<button title="%title%" class="motopress-modal-close"></button>',
                                    items: {
                                        src: '#motopress-modal-content-' + uniqid,
                                        type: 'inline'
                                    },
                                    callbacks: {
                                        beforeOpen: function beforeOpen() {
                                            if (showAnimation) {
                                                var background = $(this.bgOverlay);
                                                var wrapper = $(this.wrap);
                                                background.add(wrapper).addClass(showAnimation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                                    $(this).removeClass(showAnimation);
                                                });
                                            }
                                        },
                                        beforeClose: function beforeClose() {
                                            if (hideAnimation) {
                                                var background = $(this.bgOverlay);
                                                var wrapper = $(this.wrap);
                                                background.add(wrapper).addClass(hideAnimation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                                    $(this).removeClass(hideAnimation);
                                                });
                                            }
                                        },
                                        open: function open() {
                                            if (typeof MPCECookies !== 'undefined') {
                                                var showOnceCoockie = popupTrigger.attr('data-show-once');
                                                if (typeof showOnceCoockie !== 'undefined') {
                                                    MPCECookies.set(showOnceCoockie, 'true');
                                                }
                                            }
                                        }
                                    }
                                }).magnificPopup('open');
                                clearTimeout(window.motopressPopupTimeout);
                            }, delay);
                        }, {
                            offset: '100%',
                            continuous: false,
                            group: 'motopress-popups'
                        });
                    });
                }
            }
        });
    }(jQuery));
    (function ($) {
        $(function () {
            function mpRecalcGridGalleryMargins(gridGalleries) {
                if (typeof gridGalleries === 'undefined') {
                    gridGalleries = $('.motopress-grid-gallery-obj.motopress-grid-gallery-need-recalc');
                }
                if (!gridGalleries.length) {
                    return;
                }
                var phoneMaxWidth = 767;
                var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                gridGalleries.each(function (index, el) {
                    var spanMarginLeft = '', rows = $(el).find('.mp-row-fluid').not(':first.mp-row-fluid');
                    if (windowWidth > phoneMaxWidth) {
                        spanMarginLeft = $(el).find('[class*="mp-span"]').eq(1).css('margin-left');
                    }
                    rows.css('margin-top', spanMarginLeft);
                });
            }
            $(window).resize(function () {
                if (this.mpGridGalleryResizeTimeout)
                    clearTimeout(this.mpGridGalleryResizeTimeout);
                this.mpGridGalleryResizeTimeout = setTimeout(function () {
                    $(this).trigger('mpGridGalleryResizeEnd');
                }, 500);
            });
            $(window).on('mpGridGalleryResizeEnd', function () {
                mpRecalcGridGalleryMargins();
            }).on('load', function () {
                mpRecalcGridGalleryMargins();
            });
            $('body').on('MPCESceneRevisionPicked', function () {
                mpRecalcGridGalleryMargins();
            });
            $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (event, wrapper, shortcodeName) {
                if (shortcodeName == 'mp_grid_gallery') {
                    var galleries = wrapper.find('.motopress-grid-gallery-obj.motopress-grid-gallery-need-recalc');
                    if (galleries.length) {
                        mpRecalcGridGalleryMargins(galleries);
                    }
                }
            });
        });
    }(jQuery));
    jQuery(document).ready(function () {
        if (typeof jQuery.fn.waypoint !== 'undefined') {
            jQuery('.motopress-cta.motopress-need-animate').waypoint({
                offset: '85%',
                handler: function handler() {
                    this.enabled = false;
                    var animation = jQuery(this.element).data('animation');
                    jQuery(this.element).addClass('motopress-animation-' + animation);
                    jQuery(this.element).find('[data-animation]').each(function () {
                        var child_animation = jQuery(this).data('animation');
                        jQuery(this).addClass('motopress-animation-' + child_animation);
                    });
                }
            });
            jQuery('.motopress-ce-icon-obj.motopress-need-animate').waypoint(function () {
                this.enabled = false;
                var animation = jQuery(this.element).attr('data-animation');
                jQuery(this.element).addClass('motopress-animation-' + animation);
            }, { offset: '98%' });
        }    
    });
    (function ($) {
        $('.motopress-share-buttons a').click(function () {
            var thisOne = $(this), thisName = thisOne.attr('title'), thisLink = null, pageLink = encodeURIComponent(document.URL);
            if (thisName === 'Facebook') {
                thisLink = 'https://www.facebook.com/sharer/sharer.php?u=';
            } else if (thisName === 'Twitter') {
                thisLink = 'https://twitter.com/share?url=';
            } else if (thisName === 'Google +') {
                thisLink = 'https://plus.google.com/share?url=';
            } else if (thisName === 'Pinterest') {
                thisLink = '//www.pinterest.com/pin/create/button/?url=';
            }
            motoOpenShareWindow(thisLink + pageLink, thisName);
            return false;
        });
        function motoOpenShareWindow(link, name) {
            var leftvar = (screen.width - 640) / 2;
            var topvar = (screen.height - 480) / 2;
            window.open(link, name, 'width=640,height=480,left=' + leftvar + ',top=' + topvar + ',status=no,toolbar=no,menubar=no,resizable=yes');
        }
    }(jQuery));
    jQuery(document).ready(function ($) {
        $('.motopress-google-map-obj').on('click', function () {
            $(this).find('iframe').addClass('mpce-clicked');
        }).on('mouseleave', function () {
            $(this).find('iframe').removeClass('mpce-clicked');
        });
    });
    (function ($) {
        if (typeof google === 'undefined' || typeof google.load === 'undefined')
            return;
        var PIE_HOLE = 0.5, MIN_HEIGHT = 200, EMPTY_CHART = 'No Data';
        function validateData(dataToValidate) {
            var ethalon = null;
            if (dataToValidate) {
                for (var i = 0; i < dataToValidate.length; i++) {
                    ethalon = dataToValidate[0].length;
                    if (dataToValidate.length === 1 && dataToValidate[0][0] === null || ethalon != dataToValidate[i].length) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        }
        function chartType(type) {
            if (type == 'PieChart3D') {
                type = 'PieChart';
            }
            return type;
        }
        function init(charts) {
            if (typeof charts === 'undefined') {
                charts = $('.motopress-google-chart');
            }
            charts.each(function () {
                var chartEl = $(this);
                mpceDrawChart(chartEl, detectHeight(chartEl));
            });
        }
        function detectHeight(chartEl) {
            var height;
            if (!MPCEVars.isEditor) {
                var heightFinder = chartEl.attr('style');
                if (heightFinder === undefined) {
                    heightFinder = chartEl.parent().attr('style');
                }
                if (heightFinder !== undefined) {
                    heightFinder = heightFinder.split('min-height:');
                    heightFinder = heightFinder[1].split('px;');
                    height = Number(heightFinder[0]);
                } else {
                    height = MIN_HEIGHT;
                }    
            } else {
                height = chartEl.parent().parent().height();
                if (height < 100) {
                    height = MIN_HEIGHT;
                }
            }
            return height;
        }
        function mpceDrawChart(chartEl, verticalSize) {
            var motopressGoogleChartData = $.parseJSON(chartEl.attr('data-chart')) || {};
            motopressGoogleChartData.height = typeof verticalSize !== 'udnefined' ? verticalSize : MIN_HEIGHT;
            var chartObject = {};
            chartObject.chartType = chartType(motopressGoogleChartData.type);
            if (validateData(motopressGoogleChartData.table)) {
                chartObject.dataTable = motopressGoogleChartData.table;
            } else {
                chartObject.dataTable = null;
            }
            chartObject.options = {
                'title': motopressGoogleChartData.title,
                'height': motopressGoogleChartData.height,
                'colors': motopressGoogleChartData.colors
            };
            if (motopressGoogleChartData.backgroundColor !== null) {
                chartObject.options.backgroundColor = { 'fill': 'transparent' };
            }
            if (motopressGoogleChartData.type == 'PieChart3D') {
                chartObject.options.is3D = true;
            }
            if (motopressGoogleChartData.donut != false && motopressGoogleChartData.donut !== 'false') {
                chartObject.options.pieHole = PIE_HOLE;
            }
            if (chartObject.dataTable !== null) {
                var wrapper = new google.visualization.ChartWrapper(chartObject);
                wrapper.draw(chartEl.get(0));
                chartEl.addClass('motopress-google-chart-loaded');
            } else {
                chartEl.addClass('motopress-empty-chart').text(EMPTY_CHART);
            }
        }
        google.load('visualization', '1');
        if (MPCEVars.isEditor) {
            $(document).ready(function () {
                $(document).on('dragstop', '.motopress-content-wrapper .motopress-splitter', function (e) {
                    var thisChartParent = $(this).parent().parent().parent(), thisChart = thisChartParent.find('.motopress-google-chart');
                    if (thisChart.length !== 0) {
                        thisChart.each(function () {
                            mpceDrawChart($(this));
                        });
                    }
                });
                var timer;
                $(window).on('resize', function () {
                    if ($('.motopress-google-chart').length) {
                        timer && clearTimeout(timer);
                        timer = setTimeout(init, 100);
                    }
                });    
            });
            $('body').on('MPCESceneRevisionPicked', function () {
                init();
            });
            $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (e, wrapper, shortcodeName) {
                if (shortcodeName === 'mp_google_chart') {
                    var charts = wrapper.find('.motopress-google-chart');
                    if (charts.length) {
                        init(charts);
                    }
                }
            });
        } else {
            $(document).ready(function () {
                var timer;
                $(window).resize(function (e) {
                    timer && clearTimeout(timer);
                    timer = setTimeout(init, 100);
                });
            });
        }
        google.setOnLoadCallback(function () {
            init();
        });
    }(jQuery));
    (function ($) {
        $(function () {
            var FILTER_ACTIVE_STYLE_CLASS = 'ui-state-active';
            var FILTER_REAL_ACTIVE_CLASS = 'motopress-active-filter';
            var postsGrids = $('.motopress-posts-grid-obj');
            function getActiveFilters(postsGrid) {
                var filters, filtersWrapper = postsGrid.children('.motopress-filter'), activeFilters = filtersWrapper.find('.' + FILTER_REAL_ACTIVE_CLASS);
                if (activeFilters.length) {
                    filters = {};
                    activeFilters.each(function () {
                        var tax = $(this).closest('.motopress-filter-group').attr('data-group');
                        var term = $(this).attr('data-filter');
                        filters[tax] = term !== '' ? [term] : [];
                    });
                } else {
                    filters = false;
                }
                return filters;
            }
            function filterPosts(postsGrid, filters) {
                var shortcodeAttrs = postsGrid.attr('data-shortcode-attrs');
                var postID = postsGrid.attr('data-post-id');
                $.post(MPCEVars.postsGridData.admin_ajax, {
                    'action': 'motopress_ce_posts_grid_filter',
                    'nonce': MPCEVars.postsGridData.nonces.motopress_ce_posts_grid_filter,
                    'shortcode_attrs': shortcodeAttrs,
                    'filters': filters,
                    'post_id': postID,
                    'page_has_presets': $('#motopress-ce-presets-styles').length !== 0
                }, function (response) {
                    if (response.success) {
                        var items = $(response.data.items), loadMoreButton = $(response.data.load_more), pagination = $(response.data.pagination);
                        postsGrid.children(':not(.motopress-filter)').remove().end().append(items, loadMoreButton, pagination);
                        if (response.hasOwnProperty('custom_styles')) {
                            updateCustomStyles(response.custom_styles);
                        }
                    }
                });
            }
            function loadMorePosts(postsGrid, page, filters) {
                var shortcodeAttrs = postsGrid.attr('data-shortcode-attrs');
                var postID = postsGrid.attr('data-post-id');
                $.post(MPCEVars.postsGridData.admin_ajax, {
                    'action': 'motopress_ce_posts_grid_load_more',
                    'nonce': MPCEVars.postsGridData.nonces.motopress_ce_posts_grid_load_more,
                    'shortcode_attrs': shortcodeAttrs,
                    'filters': filters,
                    'page': page,
                    'post_id': postID,
                    'page_has_presets': $('#motopress-ce-presets-styles').length !== 0
                }, function (response) {
                    if (response.success) {
                        var itemsWrapper = postsGrid.children('.motopress-paged-content'), itemsColumns = parseInt(itemsWrapper.attr('data-columns')), items = response.data.items, loadMoreButton = $(response.data.load_more);
                        postsGrid.children(':not(.motopress-filter, .motopress-paged-content)').remove();
                        var lastRow = itemsWrapper.children('.motopress-filter-row:last');
                        for (var i = lastRow.children('.motopress-filter-col').length; i < itemsColumns; i++) {
                            if (items.length) {
                                lastRow.append(items.shift());
                            }
                        }
                        var rowPrototype = lastRow.clone().empty();
                        $.each(items, function (index, el) {
                            rowPrototype.append($(el));
                            if ((index + 1) % itemsColumns === 0 || items.length === index + 1) {
                                itemsWrapper.append(rowPrototype.clone());
                                rowPrototype.empty();
                            }
                        });
                        if (response.hasOwnProperty('custom_styles')) {
                            updateCustomStyles(response.custom_styles);
                        }
                        itemsWrapper.after(loadMoreButton);
                    }
                });
            }
            function updateCustomStyles(customStyles) {
                var privateStyleTag = $('#motopress-ce-private-styles');
                if (customStyles.hasOwnProperty('private')) {
                    var postsPrintedStyles = privateStyleTag.attr('data-posts') !== '' ? privateStyleTag.attr('data-posts').split(',') : [];
                    $.each(postsPrintedStyles, function (postId) {
                        delete customStyles['private'][postId];
                    });
                    var privateStyles = privateStyleTag.text();
                    $.each(customStyles['private'], function (postId, style) {
                        privateStyles += style;
                        postsPrintedStyles.push(postId);
                    });
                    privateStyleTag.text(privateStyles);
                    privateStyleTag.attr('data-posts', postsPrintedStyles.join(','));
                }
                if (customStyles.hasOwnProperty('presets') && !$('#motopress-ce-presets-styles').length) {
                    privateStyleTag.before(customStyles.presets);
                }
            }
            function turnPage(postsGrid, page, filters) {
                var shortcodeAttrs = postsGrid.attr('data-shortcode-attrs');
                var postID = postsGrid.attr('data-post-id');
                $.post(MPCEVars.postsGridData.admin_ajax, {
                    'action': 'motopress_ce_posts_grid_turn_page',
                    'nonce': MPCEVars.postsGridData.nonces.motopress_ce_posts_grid_turn_page,
                    'shortcode_attrs': shortcodeAttrs,
                    'filters': filters,
                    'page': page,
                    'post_id': postID,
                    'page_has_presets': $('#motopress-ce-presets-styles').length !== 0
                }, function (response) {
                    if (response.success) {
                        var items = $(response.data.items), pagination = $(response.data.pagination);
                        postsGrid.children(':not(.motopress-filter)').remove().end().append(items, pagination);
                        if (response.data.hasOwnProperty('custom_styles')) {
                            updateCustomStyles(response.data.custom_styles);
                        }
                    }
                });
            }
            function showPreloader(el) {
                el.addClass('ui-state-loading');
            }
            function hidePreloader(postsGrid) {
                postsGrid.find('.motopress-paged-content').addClass('ui-state-loading');
            }
            postsGrids.on('click', '.motopress-filter [data-filter]:not(.' + FILTER_REAL_ACTIVE_CLASS + ')', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var postsGrid = $(this).closest('.motopress-posts-grid-obj');
                var currentFilterGroup = $(this).closest('.motopress-filter-group');
                showPreloader(postsGrid.children('.motopress-paged-content'));
                currentFilterGroup.find('.' + FILTER_ACTIVE_STYLE_CLASS).removeClass(FILTER_ACTIVE_STYLE_CLASS + ' ' + FILTER_REAL_ACTIVE_CLASS);
                $(this).addClass(FILTER_ACTIVE_STYLE_CLASS + ' ' + FILTER_REAL_ACTIVE_CLASS);
                var filtersWrapper = postsGrid.find('.motopress-filter');
                var filters = getActiveFilters(postsGrid);
                filterPosts(postsGrid, filters);
            });
            postsGrids.on('click', '.motopress-posts-grid-pagination a[data-page]', function (e) {
                e.preventDefault();
                e.stopPropagation();
                showPreloader($(this).parent());
                var postsGrid = $(this).closest('.motopress-posts-grid-obj');
                var page = $(this).attr('data-page');
                var filters = getActiveFilters(postsGrid);
                turnPage(postsGrid, page, filters);
            });
            postsGrids.on('click', '.motopress-load-more', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var postsGrid = $(this).closest('.motopress-posts-grid-obj');
                var page = $(this).attr('data-page');
                var filters = getActiveFilters(postsGrid);
                showPreloader($(this).parent());
                loadMorePosts(postsGrid, page, filters);
            });
        });
    }(jQuery));
    $(function () {
        function getAtts(el) {
            return $.parseJSON(el.attr('data-atts'));
        }
        function init(sliders) {
            if (typeof sliders === 'undefined') {
                sliders = $('.motopress-image-slider-obj:not(.motopress-flexslider-inited)');
            }
            sliders.each(function () {
                var slider = $(this);
                var sliderAtts = getAtts(slider);
                if (slider.data('flexslider')) {
                    slider.flexslider('destroy');
                }
                if (!sliderAtts.controlNav) {
                    slider.css('margin-bottom', 0);
                }
                slider.flexslider({
                    slideshow: sliderAtts.slideshow,
                    animation: sliderAtts.animation,
                    controlNav: sliderAtts.controlNav,
                    slideshowSpeed: sliderAtts.slideshowSpeed,
                    animationSpeed: sliderAtts.animationSpeed,
                    smoothHeight: sliderAtts.smoothHeight,
                    keyboard: sliderAtts.keyboard
                });
                slider.addClass('motopress-flexslider-inited');
            });
        }
        $('body').on('MPCESceneRevisionPicked', function () {
            init();
        });
        $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (event, wrapper, shortcodeName) {
            if (shortcodeName == 'mp_image_slider') {
                var sliders = wrapper.find('.motopress-image-slider-obj:not(.motopress-flexslider-inited)');
                if (sliders.length) {
                    init(sliders);
                }
            }
        });
        init();
    });
    $(function () {
        function getAtts(el) {
            return $.parseJSON(el.attr('data-atts'));
        }
        function init(sliders) {
            if (typeof sliders === 'undefined') {
                sliders = $('.motopress-posts_slider-obj .motopress-flexslider:not(.motopress-flexslider-inited)');
            }
            sliders.each(function () {
                var slider = $(this);
                var sliderAtts = getAtts(slider);
                if (slider.data('flexslider')) {
                    slider.flexslider('destroy');
                }
                if (!sliderAtts.showNav) {
                    slider.addClass('motopress-margin-bottom-0');
                }
                slider.flexslider({
                    animation: sliderAtts.animation,
                    animationLoop: sliderAtts.animationLoop,
                    smoothHeight: sliderAtts.smoothHeight,
                    slideshow: sliderAtts.slideshow,
                    slideshowSpeed: sliderAtts.slideshowSpeed,
                    maxItems: sliderAtts.maxItems,
                    controlNav: sliderAtts.showNav,
                    pauseOnHover: sliderAtts.pauseOnHover,
                    prevText: sliderAtts.prevText,
                    nextText: sliderAtts.nextText
                });
                slider.addClass('motopress-flexslider-inited');
            });
        }
        init();
        $('body').on('MPCESceneRevisionPicked', function () {
            init();
        });
        $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (event, wrapper, shortcodeName) {
            if (shortcodeName == 'mp_posts_slider') {
                var sliders = wrapper.find('.motopress-posts_slider-obj .motopress-flexslider:not(.motopress-flexslider-inited)');
                if (sliders.length) {
                    init(sliders);
                }
            }
        });
    });
    (function ($) {
        $(function () {
            function getAtts(el) {
                return $.parseJSON(el.attr('data-atts')) || {
                    endDate: 0,
                    format: '',
                    layout: '',
                    serverDate: 0
                };
            }
            function init(timers) {
                if (typeof timers === 'undefined') {
                    timers = $('.motopress-countdown_timer-el');
                }
                timers.each(function () {
                    var timer = $(this);
                    var timerAtts = getAtts(timer);
                    var endDate = timerAtts.endDate;
                    var serverTime = timerAtts.serverDate;
                    if (serverTime) {
                        var userTime = new Date().getTime();
                        var diff = userTime - serverTime;
                        endDate += diff;
                    }
                    timer.countdown({
                        format: timerAtts.format,
                        padZeroes: true,
                        until: new Date(endDate),
                        layout: timerAtts.layout
                    });
                    if (MPCEVars.isEditor) {
                        timer.countdown('pause');
                    }
                });
            }
            $('body').on('MPCESceneRevisionPicked', function () {
                init();
            });
            $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (event, wrapper, shortcodeName) {
                if (shortcodeName === 'mp_countdown_timer') {
                    var timers = wrapper.find('.motopress-countdown_timer-el');
                    if (timers.length) {
                        init(timers);
                    }
                }
            });
            init();
        });
    }(jQuery));
    (function ($) {
        $(function () {
            function init(audios) {
                if (typeof audios === 'undefined') {
                    audios = $('.motopress-audio-object');
                }
                $.each(audios, function () {
                    var audio = $(this);
                    if (audio.find('.wp-audio-shortcode').length) {
                        audio.find('.wp-audio-shortcode').mediaelementplayer();
                    }
                });
            }
            $('body').on('MPCESceneRevisionPicked', function () {
                init();
            });
            $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (e, wrapper, shortcodeName) {
                if (shortcodeName === 'mp_wp_audio') {
                    var audios = wrapper.find('.motopress-audio-object');
                    if (audios.length) {
                        init(audios);
                    }
                }
            });
            init();
        });
    }(jQuery));
    (function ($) {
        $(function () {
            function fixHref(tabsEl) {
                if ($('base').length) {
                    tabsEl.find('ul li a').each(function () {
                        $(this).attr('href', location.href.toString() + $(this).attr('href'));
                    });
                }
            }
            function getTabsAtts(tabsEl) {
                return $.parseJSON(tabsEl.attr('data-atts')) || {
                    active: 0,
                    rotateTime: 0,
                    tabsCount: 0,
                    vertical: false
                };
            }
            function init(tabs) {
                if (typeof tabs === 'undefined') {
                    tabs = $('.motopress-tabs-obj');
                }
                tabs.each(function () {
                    var tabsEl = $(this);
                    var tabsAtts = getTabsAtts(tabsEl);
                    fixHref(tabsEl);
                    if (tabsEl.data('uiTabs')) {
                        tabsEl.tabs('destroy');
                    }
                    if (typeof $.ui !== 'undefined' && typeof $.ui.tabs !== 'undefined') {
                        tabsEl.tabs({ active: tabsAtts.active });
                    }
                    if (!MPCEVars.isEditor && tabsAtts.rotateTime !== 0) {
                        rotateTabs(tabsEl);
                    }
                    if (tabsAtts.vertical) {
                        fullHeight(tabsEl);
                    }
                });
            }
            function nextTab(tabsEl) {
                var active = tabsEl.tabs('option', 'active');
                var tabsAtts = getTabsAtts(tabsEl);
                active++;
                if (active >= tabsAtts.tabsCount) {
                    active = 0;
                }
                tabsEl.tabs('option', 'active', active);
            }
            function rotateTabs(tabsEl) {
                var tabsAtts = getTabsAtts(tabsEl);
                var interval = setInterval(function () {
                    nextTab(tabsEl);
                }, tabsAtts.rotateTime);
                tabsEl.hover(function () {
                    clearInterval(interval);
                }, function () {
                    interval = setInterval(function () {
                        nextTab(tabsEl);
                    }, tabsAtts.rotateTime);
                });
            }
            function fullHeight(tabsEl) {
                var navHeight = tabsEl.find('.ui-tabs-nav').height();
                tabsEl.find('.motopress-tab').css('min-height', navHeight);
            }
            $(window).load(function () {
                $('.motopress-tabs-obj').each(function () {
                    var tabsEl = $(this);
                    var tabsAtts = getTabsAtts(tabsEl);
                    if (tabsAtts.vertical) {
                        fullHeight(tabsEl);
                    }
                });
            });
            $('body').on('MPCESceneRevisionPicked', function () {
                init();
            });
            $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (e, wrapper, shortcodeName) {
                if (shortcodeName === 'mp_tabs') {
                    var tabs = wrapper.find('.motopress-tabs-obj');
                    if (tabs.length) {
                        init(tabs);
                    }
                }
            });
            init();
        });
    }(jQuery));
    (function ($) {
        $(function ($) {
            function getAtts(accordion) {
                return $.parseJSON(accordion.attr('data-atts')) || {
                    active: 0,
                    collapsible: true,
                    header: ' > div > h3',
                    heightStyle: 'content'
                };
            }
            function init(accordions) {
                if (typeof accordions === 'undefined') {
                    accordions = $('.motopress-accordion-obj');
                }
                accordions.each(function () {
                    var accordion = $(this);
                    var accordionAtts = getAtts(accordion);
                    if (accordion.data('uiAccordion')) {
                        accordion.accordion('destroy');
                    }
                    if (typeof $.ui !== 'undefined' && typeof $.ui.accordion !== 'undefined') {
                        accordion.accordion({
                            active: accordionAtts.active,
                            collapsible: accordionAtts.collapsible,
                            header: accordionAtts.header,
                            heightStyle: accordionAtts.heightStyle
                        });
                    }
                });
            }
            $('body').on('MPCESceneRevisionPicked', function () {
                init();
            });
            $('body').on('MPCEObjectCreated MPCEObjectUpdated', function (e, wrapper, shortcodeName) {
                if (shortcodeName === 'mp_accordion') {
                    var accordions = wrapper.find('.motopress-accordion-obj');
                    if (accordions.length) {
                        init(accordions);
                    }
                }
            });
            init();
        });
    }(jQuery));
}(jQuery));