(function($) {

	window.CE = window.CE || {};
	window.IframeCE = {};


	window.MP = {
		Loader: (function() {
			var callbacks = [], map = {};
			return {
				add: function(name, callback) {
					callbacks.push(callback);
					map[name] = callbacks.length - 1;
				},
				execSingle: function(name) {
					if (map[name] !== undefined) {
						callbacks[map[name]]();
					}
				},
				execAll: function() {
					var cbLen = callbacks.length;
					for (var i = 0; i < cbLen; i++) {
						if (typeof callbacks[i] === 'function') {
							callbacks[i]();
						}
					}
				}
			}
		})(),
		Error: {
			terminate: function() {
				jQuery('html').css({
					overflow: '',
					paddingTop: 32
				});
				jQuery('body > #wpadminbar').prependTo('#wpwrap > #wpcontent');
				var mpce = jQuery('#motopress-content-editor');
				mpce.siblings('.motopress-hide').removeClass('motopress-hide');
				jQuery('#wpwrap').height('');
				var preload = jQuery('#motopress-preload');
				preload.hide();
				var error = preload.children('#motopress-error');
				error.find('#motopress-system').prevAll().remove();
				error.hide();
				mpce.hide();
				jQuery(window).trigger('resize'); 

				window.location.href = motopressCE.postData.viewUrl;
			},
			log: function(e, isMainEditor) {
				isMainEditor = isMainEditor !== undefined && isMainEditor;

				console.group('CE error');
				console.warn('Name: ' + e.name);
				console.warn('Message: ' + e.message);
				if (e.hasOwnProperty('fileName')) console.warn('File: ' + e.fileName);
				if (e.hasOwnProperty('lineNumber')) console.warn('Line: ' + e.lineNumber);
				console.warn('Browser: ' + navigator.userAgent);
				console.warn('Platform: ' + navigator.platform);
				console.log(e);
				console.groupEnd();

				var error = jQuery('#motopress-preload > #motopress-error');
				var text = e.name + ': ' + e.message + '.';
				if (e.hasOwnProperty('fileName')) {
					text += ' ' + e.fileName;
				}
				if (e.hasOwnProperty('lineNumber')) {
					text += ':' + e.lineNumber;
				}
				error.find('#motopress-system').before(jQuery('<p />', {text: text}));
				error.show();

				if (isMainEditor) {
					jQuery('#motopress-preload').stop().show();
				}
			}
		}
	};

	jQuery(document).ready(function($) {
		$('#motopress-knob').knob({
			draw : function () {
					var a = this.angle(this.cv)  
						, sa = this.startAngle          
						, sat = this.startAngle         
						, ea                            
						, eat = sat + a                 
						, r = 1;

					this.g.lineWidth = this.lineWidth;

					this.o.cursor
						&& (sat = eat - 0.3)
						&& (eat = eat + 0.3);

					if (this.o.displayPrevious) {
						ea = this.startAngle + this.angle(this.v);
						this.o.cursor
							&& (sa = ea - 0.3)
							&& (ea = ea + 0.3);
						this.g.beginPath();
						this.g.strokeStyle = this.pColor;
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
						this.g.stroke();
					}

					this.g.beginPath();
					this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
					this.g.stroke();

					this.g.lineWidth = 2;
					this.g.beginPath();
					this.g.strokeStyle = this.o.fgColor;
					this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
					this.g.stroke();

					return false;
			}
		});

		$('#motopress-system')
			.children('#motopress-browser').text('Browser: ' + navigator.userAgent)
			.end()
			.children('#motopress-platform').text('Platform: ' + navigator.platform);

		$('#motopress-terminate').on('click', function() {
			MP.Error.terminate();
		});

		var preloader = $('#motopress-preload');
		preloader.show();

		motopressCE.tinyMCEInited = prepareTinyMCEPromises();

		mpceRunEditor();
	});

	function prepareTinyMCEPromises(){


		var mpceEditorId = 'motopresscecontent',
			wpEditorId = 'content',
			mpceCodeEditorId = 'motopresscodecontent';

		var editorIds = [wpEditorId, mpceCodeEditorId, mpceEditorId];
		var tinyMCEInitedDefers = {};

		var promises = {};

		editorIds.forEach(function(id) {
			var defer = $.Deferred();
			var promise = defer.promise();

			tinyMCEInitedDefers[id] = defer;
			promises[id] = promise;
		});

		function resolveMPTinyMCEEditors(editorID, editor) {
			if (tinyMCEInitedDefers.hasOwnProperty(editorID)) {
				tinyMCEInitedDefers[editorID].resolve(editor);
			}
		}

		if (tinyMCE.majorVersion === '4') {
			tinyMCE.on('AddEditor', function(args) {
				args.editor.on('init', function(ed) {
					resolveMPTinyMCEEditors(args.editor.id, args.editor);
				});
			});
		} else {
			tinyMCE.onAddEditor.add(function(mce, ed) {
				ed.onInit.add(function(ed) {
					resolveMPTinyMCEEditors(ed.editorId, ed);
				});
			});
		}

		return promises;
	}

})(jQuery);
function mpceRunEditor() {
    try {
        (function () {
            'use strict';
            function _defineProperty(obj, key, value) {
                if (key in obj) {
                    Object.defineProperty(obj, key, {
                        value: value,
                        enumerable: true,
                        configurable: true,
                        writable: true
                    });
                } else {
                    obj[key] = value;
                }
                return obj;
            }
            function _typeof(obj) {
                if (typeof Symbol === 'function' && typeof Symbol.iterator === 'symbol') {
                    _typeof = function _typeof(obj) {
                        return typeof obj;
                    };
                } else {
                    _typeof = function _typeof(obj) {
                        return obj && typeof Symbol === 'function' && obj.constructor === Symbol && obj !== Symbol.prototype ? 'symbol' : typeof obj;
                    };
                }
                return _typeof(obj);
            }
            (function ($) {
                CE.EventDispatcher = {};
                var subscribers = [];
                var listeners = [];
                var prevents = [];
                var oneTimeEvents = function () {
                    var names = [];
                    var events = {};
                    return {
                        add: function add(name, event) {
                            if (!this.exists(name)) {
                                names.push(name);
                            }
                            events[name] = event;
                        },
                        exists: function exists(name) {
                            return $.inArray(name, names) !== -1;
                        },
                        getByName: function getByName(name) {
                            return events[name];
                        }
                    };
                }();
                var fireListener = function fireListener(listener, event) {
                    var eventName = listener[0];
                    var cb = listener[1];
                    if (typeof event === 'undefined') {
                        event = oneTimeEvents.getByName(eventName);
                    }
                    if (can.isFunction(cb)) {
                        cb(event);
                    }
                };
                CE.EventDispatcher.Dispatcher = can.Construct.extend({
                    addSubscriber: function addSubscriber(subscriber) {
                        subscribers.push(subscriber);
                        return subscriber;
                    },
                    deleteSubscriber: function deleteSubscriber(subscriber) {
                        var index = subscribers.indexOf(subscriber);
                        if (index >= 0) {
                            subscribers.splice(index, 1);
                        }
                    },
                    addListener: function addListener(eventName, cb) {
                        var listener = [
                            eventName,
                            cb
                        ];
                        if (oneTimeEvents.exists(eventName)) {
                            fireListener(listener);
                        } else {
                            listeners.push(listener);
                        }
                        return listener;
                    },
                    deleteListener: function deleteListener(listener) {
                        var index = listeners.indexOf(listener);
                        if (index >= 0) {
                            listeners.splice(index, 1);
                        }
                    },
                    dispatch: function dispatch(eventName, event) {
                        if (!(event instanceof CE.EventDispatcher.Event)) {
                            throw new Error('event must be type of CE.EventDispatcher.Event');
                        }
                        if (this.isPrevented(eventName)) {
                            this.removeFromPrevented(eventName);
                            return;
                        }
                        can.each(subscribers, function (subscriber) {
                            if (subscriber === undefined)
                                return;
                            var events = subscriber.getSubscribedEvents();
                            for (var i = 0; i < events.length; i++) {
                                var eventNames = $.makeArray(events[i][0]);
                                var cbs = $.makeArray(events[i][1]);
                                for (var j = 0; j < eventNames.length; j++) {
                                    if (eventNames[j] === eventName) {
                                        for (var k = 0; k < cbs.length; k++) {
                                            if (can.isFunction(cbs[k])) {
                                                cbs[k](event);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        can.each(listeners, function (listener) {
                            if (listener) {
                                var listenerEvent = listener[0];
                                if (listenerEvent === eventName) {
                                    fireListener(listener, event);
                                }
                            }
                        });
                    },
                    dispatchOnce: function dispatchOnce(eventName, event) {
                        this.dispatch(eventName, event);
                        oneTimeEvents.add(eventName, event);
                    },
                    prevent: function prevent(eventName) {
                        if ($.inArray(eventName, prevents) === -1) {
                            prevents.push(eventName);
                        }
                    },
                    isPrevented: function isPrevented(eventName) {
                        return $.inArray(eventName, prevents) !== -1;
                    },
                    removeFromPrevented: function removeFromPrevented(eventName) {
                        prevents = $.grep(prevents, function (value) {
                            return value !== eventName;
                        });
                    },
                    clearPrevents: function clearPrevents() {
                        prevents = [];
                    }
                }, {});
            }(jQuery));
            (function ($) {
                CE.EventDispatcher.Subscriber = can.Construct.extend({}, {
                    getSubscribedEvents: function getSubscribedEvents() {
                        throw new Error('Must be implemented in sub-class!');
                    }
                });
            }(jQuery));
            (function ($) {
                CE.EventDispatcher.Event = can.Construct.extend({ NAME: null }, {
                    getEventName: function getEventName() {
                        return this.constructor.NAME;
                    }
                });
            }(jQuery));
            (function ($) {
                var subscribers = [];
                var listeners = [];
                CE.IframeEventStorage = can.Construct.extend({
                    storeSubscriber: function storeSubscriber(subscriber) {
                        subscribers.push(subscriber);
                    },
                    storeListener: function storeListener(listener) {
                        listeners.push(listener);
                    },
                    getSubscribers: function getSubscribers() {
                        return subscribers;
                    },
                    getListeners: function getListeners() {
                        return listeners;
                    }
                }, {});
            }(jQuery));
            (function ($) {
                can.Construct('MP.Editor', 
                {
                    myThis: null,
                    eventPrefix: 'MPCE-',
                    on: function on(eventName, callback) {
                        eventName = this.prepareEventName(eventName);
                        $(window).on(eventName, callback);    
                    },
                    one: function one(eventName, callback) {
                        eventName = this.prepareEventName(eventName);
                        $(window).one(eventName, callback);
                    },
                    onIfr: function onIfr(eventName, callback) {
                        eventName = this.prepareEventName(eventName);
                        CE.Iframe.window.jQuery(CE.Iframe.window).on(eventName, callback);    
                    },
                    oneIfr: function oneIfr(eventName, callback) {
                        eventName = this.prepareEventName(eventName);
                        CE.Iframe.window.jQuery(CE.Iframe.window).one(eventName, callback);    
                    },
                    trigger: function trigger(eventName, eventData) {
                        eventName = this.prepareEventName(eventName);
                        $(window).trigger(eventName, eventData);    
                    },
                    triggerIfr: function triggerIfr(eventName, eventData) {
                        eventName = this.prepareEventName(eventName);
                        CE.Iframe.window.jQuery(CE.Iframe.window).trigger(eventName, eventData);    
                    },
                    triggerEverywhere: function triggerEverywhere(eventName, eventData) {
                        this.trigger(eventName, eventData);
                        this.triggerIfr(eventName, eventData);    
                    },
                    prepareEventName: function prepareEventName(name) {
                        return name.split(' ').map(function (eName) {
                            return MP.Editor.eventPrefix + eName;
                        }).join(' ');
                    }
                }, 
                {
                    loaded: false,
                    opened: false,
                    preloader: null,
                    flash: null,
                    utils: null,
                    settings: null,
                    lang: null,
                    iframeControl: null,
                    init: function init() {
                        MP.Editor.myThis = this;
                        new MP.Preloader($('#motopress-preload'));
                        new MP.Flash($('#motopress-flash'));
                        new MP.Utils();
                        new MP.Settings();
                        new MP.Language();
                        new CE.PresetSaveModal($('#motopress-ce-save-preset-modal'));
                        new CE.SaveObjectModal($('#motopress-ce-save-object-modal'));
                        new CE.Panels.Navbar($('.motopress-content-editor-navbar'), { PageSettings: CE.Settings.Page });
                        new CE.PreviewDevice($('#motopress-content-editor-preview-device-panel'));
                        new CE.StyleModeSwitcher(CE.StyleModeSwitcher.DESKTOP);
                        new CE.ResponsiveSwitcher($('#mpce-responsive-switcher'), { switcher: CE.StyleModeSwitcher });
                        new CE.ImageLibrary();
                        new CE.WPGallery();
                        new CE.WPMedia();
                        new CE.WPAudio();
                        new CE.WPVideo();
                        this._initIframe();
                        new CE.CodeModal($('#motopress-code-editor-modal'));
                        MP.Preloader.myThis.load(MP.Editor.shortName);
                        $(window).on('beforeunload', this.proxy('beforeunload'));
                        this.open();
                    },
                    _initIframe: function _initIframe() {
                        MP.Editor.one('SceneInited', this.onSceneInited);
                        var iframe = $('<iframe />', {
                            src: motopressCE.postData.ceUrl,
                            id: 'motopress-content-editor-scene',
                            'class': 'motopress-content-editor-scene mpce-device-mode-desktop',
                            name: 'motopress-content-editor-scene',
                            style: 'min-width: 100% !important;'
                        });
                        CE.Panels.Navbar.myThis.editorWrapperEl.html(iframe);
                        this.iframeControl = new CE.Iframe(iframe);
                    },
                    onSceneInited: function onSceneInited() {
                        new CE.WidgetsLibrary({ library: motopressCE.settings.library });
                        new CE.ObjectTemplatesLibrary(motopressCE.settings.objectTemplatesLibrary);
                        new CE.Panels.DialogsManager();
                        new CE.Panels.SettingsDialog($('#motopress-dialog'));
                        new CE.Panels.LayoutChooserDialog($('#mpce-layout-chooser'), { layouts: motopressCE.settings.rowLayouts });
                        new CE.Panels.TemplateChooserDialog($('#mpce-template-chooser'), {});
                        new CE.Panels.WidgetsDialog($('#mpce-widgets-panel'), {
                            library: $.extend({}, CE.WidgetsLibrary.myThis.getLibrary()),
                            grid: $.extend({}, CE.WidgetsLibrary.myThis.getGrid())
                        });
                        new CE.Panels.PageDialog($('#mpce-page-dialog'), {});
                        new CE.Panels.HistoryDialog($('#mpce-history-dialog'), {});
                    },
                    load: function load() {
                        this.loaded = true;
                        MP.Editor.triggerEverywhere('EditorLoad');
                    },
                    unload: function unload() {
                        this.loaded = false;
                        MP.Editor.trigger('EditorUnLoad');
                    },
                    reloadScene: function reloadScene(nonce) {
                        CE.Iframe.window.MPCESceneStatus = 'pending';
                        this.iframeControl.destroy();
                        var href = CE.Iframe.window.location.href;
                        href = MP.Utils.removeParamFromUrl(href, '_wpnonce');
                        href = MP.Utils.addParamToUrl(href, '_wpnonce', nonce);
                        CE.Iframe.window.location.href = href;
                        this.iframeControl = new CE.Iframe($('#motopress-content-editor-scene'));
                    },
                    open: function open() {
                        this.opened = true;
                        $('body').addClass('motopress-editor-open');
                        MP.Editor.trigger('EditorOpen');
                    },
                    close: function close() {
                        var href;
                        if (CE.Settings.Page.attr('status') === 'draft') {
                            href = motopressCE.postData.previewUrl;
                        } else {
                            href = motopressCE.postData.viewUrl;
                        }
                        window.location.href = href;    
                    },
                    redirect: function redirect(url) {
                        MP.Preloader.myThis.show();
                        window.location.href = url;
                    },
                    isOpen: function isOpen() {
                        return this.opened;
                    },
                    beforeunload: function beforeunload() {
                        if (IframeCE.SceneState && IframeCE.SceneState.isContentChanged()) {
                            return true;
                        }
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Settings = {};
            }(jQuery));
            (function ($) {
                CE.Settings.Page = new can.Map({
                    title: motopressCE.postData.settings.page.title,
                    hideTitle: motopressCE.postData.settings.page.hideTitle,
                    template: motopressCE.postData.settings.page.template,
                    status: motopressCE.postData.settings.page.status
                });
            }(jQuery));
            (function ($) {
                MP.Preloader = can.Control.extend(
                { myThis: null }, 
                {
                    stages: {
                        Navbar: false,
                        Language: false,
                        Iframe: false,
                        DragDrop: false,
                        Editor: false
                    },
                    loaded: 0,
                    step: null,
                    knob: null,
                    init: function init() {
                        MP.Preloader.myThis = this;
                        this.step = Math.round(100 / Object.keys(this.stages).length * 100) / 100;
                        this.knob = this.element.find('#motopress-knob');
                        this.set();
                    },
                    reopen: function reopen() {
                        for (var stage in this.stages) {
                            if (stage === 'Navbar' || stage === 'Language') {
                                delete this.stages[stage];
                            } else {
                                this.stages[stage] = false;
                            }
                        }
                    },
                    show: function show() {
                        this.loaded = 0;
                        this.set();
                        this.element.stop().show();
                    },
                    hide: function hide() {
                        this.element.fadeOut('slow');
                        this.loaded = 100;
                        MP.Flash.showMessage();
                    },
                    load: function load(stage) {
                        if (this.stages.hasOwnProperty(stage) && !this.stages[stage]) {
                            this.stages[stage] = true;
                            this.loaded += this.step;
                            this.set();    
                        }
                    },
                    set: function set() {
                        this.knob.val(this.loaded).trigger('change');
                    }
                });
            }(jQuery));
            (function ($) {
                var MSG_TIMEOUT = 10000;
                var messages = {};
                var storeMessage = function storeMessage(id, $alert, tID) {
                    messages[id] = {
                        element: $alert,
                        t_id: tID
                    };
                };
                var getMessage = function getMessage(id) {
                    return messages[id] !== undefined ? messages[id] : null;
                };
                var deleteMessage = function deleteMessage(id) {
                    delete messages[id];
                };
                MP.Flash = can.Control.extend(
                {
                    myThis: null,
                    setFlash: function setFlash(message, type, id) {
                        if (!id) {
                            id = parent.MP.Utils.uniqid();
                        }
                        var alert = MP.Flash.myThis.create();
                        if (typeof type === 'undefined')
                            type = 'warning';
                        var cssClass = '';
                        switch (type) {
                        case 'info':
                            cssClass = 'alert-info';
                            break;
                        case 'success':
                            cssClass = 'alert-success';
                            break;
                        case 'error':
                            cssClass = 'alert-error';
                            break;
                        }
                        if (cssClass.length)
                            alert.addClass(cssClass);
                        if (message.length)
                            alert.children('span').html(message);
                        alert.attr('data-id', id);
                        return id;
                    },
                    showMessage: function showMessage() {
                        MP.Flash.myThis.element.children('.alert:not([data-shown])').each(function () {
                            var alert = $(this);
                            var alertID = alert.attr('data-id');
                            alert.show().mpalert();
                            alert.attr('data-shown', '');
                            MP.Flash.closeAlert(alertID);
                            var flashTimer = setTimeout(function () {
                                MP.Flash.closeAlert(alertID);
                            }, MSG_TIMEOUT);
                            storeMessage(alertID, alert, flashTimer);
                        });
                    },
                    closeAlert: function closeAlert(alertID) {
                        var msg = getMessage(alertID);
                        if (msg) {
                            msg.element.mpalert('close');
                            clearTimeout(msg.t_id);
                            deleteMessage(alertID);
                        }
                    }
                }, 
                {
                    alertEl: $('<div />', {
                        'class': 'alert fade in',
                        style: 'display: none;'
                    }),
                    closeEl: $('<div />', {
                        'class': 'motopress-close motopress-icon-remove',
                        'data-dismiss': 'alert'
                    }),
                    messageEl: $('<span />'),
                    create: function create() {
                        return this.alertEl.clone().append(this.closeEl.clone(), this.messageEl.clone()).appendTo(this.element);
                    },
                    init: function init() {
                        MP.Flash.myThis = this;
                    }
                });
            }(jQuery));
            (function ($) {
                MP.Utils = can.Construct(
                {
                    validationError: $('<div />', { 'class': 'motopress-validation-error' }),
                    tbStyle: null,
                    wpFrontEndEditorEvents: [],
                    strtr: function strtr(str, from, to) {
                        if (_typeof(from) === 'object') {
                            var cmpStr = '';
                            for (var j = 0; j < str.length; j++) {
                                cmpStr += '0';
                            }
                            var offset = 0;
                            var find = -1;
                            var addStr = '';
                            for (var fr in from) {
                                offset = 0;
                                while ((find = str.indexOf(fr, offset)) != -1) {
                                    if (parseInt(cmpStr.substr(find, fr.length)) != 0) {
                                        offset = find + 1;
                                        continue;
                                    }
                                    for (var k = 0; k < from[fr].length; k++) {
                                        addStr += '1';
                                    }
                                    cmpStr = cmpStr.substr(0, find) + addStr + cmpStr.substr(find + fr.length, cmpStr.length - (find + fr.length));
                                    str = str.substr(0, find) + from[fr] + str.substr(find + fr.length, str.length - (find + fr.length));
                                    offset = find + from[fr].length + 1;
                                    addStr = '';
                                }
                            }
                            return str;
                        }
                        for (var i = 0; i < from.length; i++) {
                            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                        }
                        return str;
                    },
                    uniqid: function uniqid(prefix, more_entropy) {
                        if (typeof prefix == 'undefined') {
                            prefix = '';
                        }
                        var retId;
                        var formatSeed = function formatSeed(seed, reqWidth) {
                            seed = parseInt(seed, 10).toString(16);
                            if (reqWidth < seed.length) {
                                return seed.slice(seed.length - reqWidth);
                            }
                            if (reqWidth > seed.length) {
                                return Array(1 + (reqWidth - seed.length)).join('0') + seed;
                            }
                            return seed;
                        };
                        if (!this.php_js) {
                            this.php_js = {};
                        }
                        if (!this.php_js.uniqidSeed) {
                            this.php_js.uniqidSeed = Math.floor(Math.random() * 123456789);
                        }
                        this.php_js.uniqidSeed++;
                        retId = prefix;
                        retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
                        retId += formatSeed(this.php_js.uniqidSeed, 5);
                        if (more_entropy) {
                            retId += (Math.random() * 10).toFixed(8).toString();
                        }
                        return retId;
                    },
                    version_compare: function version_compare(v1, v2, operator) {
                        this.php_js = this.php_js || {};
                        this.php_js.ENV = this.php_js.ENV || {};
                        var i = 0, x = 0, compare = 0,
                            vm = {
                                'dev': -6,
                                'alpha': -5,
                                'a': -5,
                                'beta': -4,
                                'b': -4,
                                'RC': -3,
                                'rc': -3,
                                '#': -2,
                                'p': 1,
                                'pl': 1
                            },
                            prepVersion = function prepVersion(v) {
                                v = ('' + v).replace(/[_\-+]/g, '.');
                                v = v.replace(/([^.\d]+)/g, '.$1.').replace(/\.{2,}/g, '.');
                                return !v.length ? [-8] : v.split('.');
                            },
                            numVersion = function numVersion(v) {
                                return !v ? 0 : isNaN(v) ? vm[v] || -7 : parseInt(v, 10);
                            };
                        v1 = prepVersion(v1);
                        v2 = prepVersion(v2);
                        x = Math.max(v1.length, v2.length);
                        for (i = 0; i < x; i++) {
                            if (v1[i] == v2[i]) {
                                continue;
                            }
                            v1[i] = numVersion(v1[i]);
                            v2[i] = numVersion(v2[i]);
                            if (v1[i] < v2[i]) {
                                compare = -1;
                                break;
                            } else if (v1[i] > v2[i]) {
                                compare = 1;
                                break;
                            }
                        }
                        if (!operator) {
                            return compare;
                        }
                        switch (operator) {
                        case '>':
                        case 'gt':
                            return compare > 0;
                        case '>=':
                        case 'ge':
                            return compare >= 0;
                        case '<=':
                        case 'le':
                            return compare <= 0;
                        case '==':
                        case '=':
                        case 'eq':
                            return compare === 0;
                        case '<>':
                        case '!=':
                        case 'ne':
                            return compare !== 0;
                        case '':
                        case '<':
                        case 'lt':
                            return compare < 0;
                        default:
                            return null;
                        }
                    },
                    inObject: function inObject(value, obj) {
                        var result = false;
                        for (var key in obj) {
                            if (obj[key].toLowerCase() == value.toLowerCase()) {
                                result = true;
                                break;
                            }
                        }
                        return result;
                    },
                    removeByValue: function removeByValue(value, arr) {
                        if (arr.indexOf(value) !== -1) {
                            arr.splice(arr.indexOf(value), 1);
                            return true;
                        } else {
                            return false;
                        }
                    },
                    doSortSelectByText: function doSortSelectByText(select) {
                        if (!select.children('optgroup').length) {
                            var sortedVals = $.makeArray(select.children('option')).sort(function (a, b) {
                                return $(a).text() > $(b).text() ? 1 : $(a).text() < $(b).text() ? -1 : 0;
                            });
                            select.empty().html(sortedVals);
                        } else {
                            select.children('optgroup').each(function () {
                                var sortedVals = $.makeArray($(this).children('option')).sort(function (a, b) {
                                    return $(a).text() > $(b).text() ? 1 : $(a).text() < $(b).text() ? -1 : 0;
                                });
                                $(this).empty().html(sortedVals);
                            });
                        }
                    },
                    addParamToUrl: function addParamToUrl(url, key, value) {
                        var query = url.indexOf('?');
                        var anchor = url.indexOf('#');
                        if (query == url.length - 1) {
                            url = url.substring(0, query);
                            query = -1;
                        }
                        return (anchor > 0 ? url.substring(0, anchor) : url) + (query > 0 ? '&' + key + '=' + value : '?' + key + '=' + value) + (anchor > 0 ? url.substring(anchor) : '');
                    },
                    removeParamFromUrl: function removeParamFromUrl(url, param) {
                        var expr = new RegExp(param + '\\=([a-z0-9]+)', 'i');
                        var match = url.match(expr);
                        if (match) {
                            var urlPart = match[0];
                            if (url.search('&' + urlPart) >= 0) {
                                url = url.replace('&' + urlPart, '');
                            } else if (url.search('\\?' + urlPart + '&') >= 0) {
                                url = url.replace('?' + urlPart + '&', '');
                            } else if (url.search('\\?' + urlPart) >= 0) {
                                url = url.replace('?' + urlPart, '');
                            }
                        }
                        return url;
                    },
                    showValidationError: function showValidationError(message, afterElement) {
                        var oldValidationError = afterElement.next('.motopress-validation-error');
                        if (oldValidationError.length)
                            oldValidationError.remove();
                        var validationError = this.validationError.clone();
                        validationError.text(message).insertAfter(afterElement);
                    },
                    getScrollbarWidth: function getScrollbarWidth() {
                        var scrollWidth = window.browserScrollbarWidth;
                        if (typeof scrollWidth === 'undefined') {
                            var div = $('<div style="width: 50px; height: 50px; position: absolute; left: -100px; top: -100px; overflow: auto;"><div style="width: 1px; height: 100px;"></div></div>');
                            $('body').append(div);
                            scrollWidth = div[0].offsetWidth - div[0].clientWidth;
                            div.remove();
                        }
                        return scrollWidth;
                    },
                    getSpanClass: function getSpanClass(classes) {
                        var expr = new RegExp('^(' + parent.CE.Iframe.myThis.gridObj.span['class'] + ')\\d{1,2}$', 'i');
                        var spanClass = '';
                        for (var i = 0; i < classes.length; i++) {
                            if (expr.test(classes[i])) {
                                spanClass = classes[i];
                                break;
                            }
                        }
                        return spanClass;
                    },
                    getSpanNumber: function getSpanNumber(spanClass) {
                        var exprNumber = new RegExp('\\d{1,2}', 'i');
                        var matched = spanClass.match(exprNumber);
                        return matched !== null ? parseInt(matched) : 0;
                    },
                    isTheSameElement: function isTheSameElement(span, spanToCompare) {
                        return spanToCompare !== null && span[0] === spanToCompare[0];
                    },
                    calcSpanNumber: function calcSpanNumber(row, ignoreSpan) {
                        var rowEdge = row.hasClass('motopress-row-edge') ? row : row.find('.motopress-row-edge').first();
                        var spans = rowEdge.children('.motopress-clmn');
                        var spanCount = 0;
                        if (spans.length) {
                            spans.each(function () {
                                if (!MP.Utils.isTheSameElement($(this), ignoreSpan)) {
                                    var spanNumber = MP.Utils.getSpanNumber(MP.Utils.getSpanClass($(this).prop('class').split(' ')));
                                    spanCount += spanNumber;
                                }
                            });
                        }
                        return spanCount;
                    },
                    getEdgeSpan: function getEdgeSpan(span) {
                        var edgeSpan = span.hasClass('motopress-clmn-edge') ? span : span.find('.motopress-clmn-edge').first();
                        return edgeSpan.length ? edgeSpan : null;
                    },
                    getEdgeRow: function getEdgeRow(row) {
                        var edgeRow = row.hasClass('motopress-row-edge') ? row : row.find('.motopress-row-edge').first();
                        return edgeRow.length ? edgeRow : null;
                    },
                    detectSpanNestingLvl: function detectSpanNestingLvl(span) {
                        return span.closest('.motopress-row').parent('.motopress-content-wrapper').length ? 1 : 2;
                    },
                    detectRowNestingLvl: function detectRowNestingLvl(row) {
                        return row.parent('.motopress-content-wrapper').length ? 1 : 2;
                    },
                    setup: function setup() {
                        var userAgent = navigator.userAgent.toLowerCase();
                        this.Browser = {
                            Version: (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
                            Chrome: /chrome/.test(userAgent),
                            Safari: /webkit/.test(userAgent),
                            Opera: /opera/.test(userAgent),
                            IE: /msie/.test(userAgent) && !/opera/.test(userAgent),
                            Mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
                        };
                    },
                    getEditorActiveMode: function getEditorActiveMode(classes) {
                        var expr = new RegExp('^(tmce|html)-active$', 'i');
                        var activeMode = null;
                        for (var i = 0; i < classes.length; i++) {
                            if (expr.test(classes[i])) {
                                activeMode = classes[i];
                            }
                        }
                        return activeMode;
                    },
                    addWindowFix: function addWindowFix() {
                        if (typeof tb_show === 'function' && typeof tb_remove === 'function') {
                            this.tbStyle = $('<style />', {
                                type: 'text/css',
                                text: '#TB_overlay {z-index: 1051;} #TB_window {z-index: 1052;}'
                            }).appendTo('head');    
                        }    
                    },
                    removeWindowFix: function removeWindowFix() {
                        if (this.tbStyle !== null) {
                            this.tbStyle.remove();
                        }    
                    },
                    base64_decode: function base64_decode(data) {
                        var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
                        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, ac = 0, dec = '', tmp_arr = [];
                        if (!data) {
                            return data;
                        }
                        data += '';
                        do {
                            h1 = b64.indexOf(data.charAt(i++));
                            h2 = b64.indexOf(data.charAt(i++));
                            h3 = b64.indexOf(data.charAt(i++));
                            h4 = b64.indexOf(data.charAt(i++));
                            bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
                            o1 = bits >> 16 & 255;
                            o2 = bits >> 8 & 255;
                            o3 = bits & 255;
                            if (h3 == 64) {
                                tmp_arr[ac++] = String.fromCharCode(o1);
                            } else if (h4 == 64) {
                                tmp_arr[ac++] = String.fromCharCode(o1, o2);
                            } else {
                                tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
                            }
                        } while (i < data.length);
                        dec = tmp_arr.join('');
                        dec = this.utf8_decode(dec);
                        return dec;
                    },
                    base64_encode: function base64_encode(data) {
                        var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
                        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, ac = 0, enc = '', tmp_arr = [];
                        if (!data) {
                            return data;
                        }
                        data = this.utf8_encode(data + '');
                        do {
                            o1 = data.charCodeAt(i++);
                            o2 = data.charCodeAt(i++);
                            o3 = data.charCodeAt(i++);
                            bits = o1 << 16 | o2 << 8 | o3;
                            h1 = bits >> 18 & 63;
                            h2 = bits >> 12 & 63;
                            h3 = bits >> 6 & 63;
                            h4 = bits & 63;
                            tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
                        } while (i < data.length);
                        enc = tmp_arr.join('');
                        var r = data.length % 3;
                        return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
                    },
                    utf8_decode: function utf8_decode(str_data) {
                        var tmp_arr = [], i = 0, ac = 0, c1 = 0, c2 = 0, c3 = 0;
                        str_data += '';
                        while (i < str_data.length) {
                            c1 = str_data.charCodeAt(i);
                            if (c1 < 128) {
                                tmp_arr[ac++] = String.fromCharCode(c1);
                                i++;
                            } else if (c1 > 191 && c1 < 224) {
                                c2 = str_data.charCodeAt(i + 1);
                                tmp_arr[ac++] = String.fromCharCode((c1 & 31) << 6 | c2 & 63);
                                i += 2;
                            } else {
                                c2 = str_data.charCodeAt(i + 1);
                                c3 = str_data.charCodeAt(i + 2);
                                tmp_arr[ac++] = String.fromCharCode((c1 & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                                i += 3;
                            }
                        }
                        return tmp_arr.join('');
                    },
                    utf8_encode: function utf8_encode(argString) {
                        if (argString === null || typeof argString === 'undefined') {
                            return '';
                        }
                        var string = argString + '';
                        var utftext = '', start, end, stringl = 0;
                        start = end = 0;
                        stringl = string.length;
                        for (var n = 0; n < stringl; n++) {
                            var c1 = string.charCodeAt(n);
                            var enc = null;
                            if (c1 < 128) {
                                end++;
                            } else if (c1 > 127 && c1 < 2048) {
                                enc = String.fromCharCode(c1 >> 6 | 192) + String.fromCharCode(c1 & 63 | 128);
                            } else {
                                enc = String.fromCharCode(c1 >> 12 | 224) + String.fromCharCode(c1 >> 6 & 63 | 128) + String.fromCharCode(c1 & 63 | 128);
                            }
                            if (enc !== null) {
                                if (end > start) {
                                    utftext += string.slice(start, end);
                                }
                                utftext += enc;
                                start = end = n + 1;
                            }
                        }
                        if (end > start) {
                            utftext += string.slice(start, stringl);
                        }
                        return utftext;
                    },
                    nl2br: function nl2br(str, is_xhtml) {
                        var breakTag = is_xhtml || typeof is_xhtml === 'undefined' ? '<br ' + '/>' : '<br>';
                        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
                    },
                    br2nl: function br2nl(str) {
                        return str.replace(/<br[^>]*>\s*\r*\n*/g, '\n');
                    },
                    addWpFrontEndEditorFix: function addWpFrontEndEditorFix() {
                        var events = $('#post-preview').data('events');
                        if (typeof events !== 'undefined' && events.hasOwnProperty('click')) {
                            var clickEvents = events.click.slice();
                            $.each(clickEvents, function (index, value) {
                                if (value.handler.toString().indexOf('wp_fee_redirect') !== -1) {
                                    MP.Utils.wpFrontEndEditorEvents.push(value);
                                    $('#post-preview').data('events').click.splice($('#post-preview').data('events').click.indexOf(value), 1);
                                }
                            });
                        }
                    },
                    removeWpFrontEndEditorFix: function removeWpFrontEndEditorFix() {
                        if (this.wpFrontEndEditorEvents.length) {
                            $.merge($('#post-preview').data('events').click, this.wpFrontEndEditorEvents);
                            this.wpFrontEndEditorEvents.length = 0;
                        }
                    },
                    getTinyMCEVersion: function getTinyMCEVersion() {
                        return typeof tinyMCE !== 'undefined' ? tinyMCE.majorVersion + '.' + tinyMCE.minorVersion : false;
                    },
                    fixTabsBaseTagConflict: function fixTabsBaseTagConflict(tabsEl, doc, loc) {
                        doc = $(typeof doc === 'undefined' ? document : doc);
                        loc = typeof loc === 'undefined' ? location : loc;
                        if (doc.find('base').length) {
                            tabsEl.find('ul li a').each(function () {
                                $(this).attr('href', loc.href.toString() + $(this).attr('href'));
                            });
                        }
                    },
                    getObjectChanges: function (_getObjectChanges) {
                        function getObjectChanges(_x, _x2) {
                            return _getObjectChanges.apply(this, arguments);
                        }
                        getObjectChanges.toString = function () {
                            return _getObjectChanges.toString();
                        };
                        return getObjectChanges;
                    }(function (prev, now) {
                        var changes = {};
                        for (var prop in now) {
                            if (!prev || prev[prop] !== now[prop]) {
                                if ($.isPlainObject(now[prop])) {
                                    var c = getObjectChanges(prev[prop], now[prop]);
                                    if (!$.isEmptyObject(c))
                                        changes[prop] = c;
                                } else {
                                    changes[prop] = now[prop];
                                }
                            }
                        }
                        return changes;
                    }),
                    moveArrayElement: function moveArrayElement(arr, from, to) {
                        arr.splice(to, 0, arr.splice(from, 1)[0]);
                    },
                    getEntityTypeByElement: function getEntityTypeByElement($element) {
                        return MP.EntityType.initByEntity($element);
                    },
                    isSpanWrapper: function isSpanWrapper(span) {
                        var spanEdge = parent.MP.Utils.getEdgeSpan(span);
                        return spanEdge.children('.motopress-row').length !== 0;
                    },
                    convertClassesToSelector: function convertClassesToSelector(classes) {
                        return classes.split(/\s+/).map(function (className) {
                            return '.' + className;
                        }).join('');
                    },
                    generateUuid: function generateUuid() {
                        return mpceGenerateUUID4();
                    },
                    replaceClassInString: function replaceClassInString(from, to, classes) {
                        var regex = new RegExp('(^|\\s)(' + from + ')(\\s|$)', 'i');
                        return classes.replace(regex, '$1' + to + '$3');
                    },
                    validateCSSClass: function validateCSSClass(className) {
                        var classNameRegex = new RegExp('^[a-z0-9][-_a-z0-9]*$');
                        return classNameRegex.test(className);
                    },
                    getObjectTag: function getObjectTag(el) {
                        return el.is('[data-motopress-shortcode]') ? el : el.closest('[data-motopress-shortcode]');
                    },
                    findRows: function findRows(wrapper) {
                        if (typeof wrapper === 'undefined') {
                            wrapper = IframeCE.Scene.myThis.contentWrapper;
                        }
                        return wrapper.find('.motopress-row').not('[data-motopress-group]:not([data-motopress-group="mp_grid"]) .motopress-row');
                    }
                }, 
                {});
            }(jQuery));
            (function (f) {
                if ((typeof exports === 'undefined' ? 'undefined' : _typeof(exports)) === 'object' && typeof module !== 'undefined') {
                    module.exports = f();
                } else if (typeof define === 'function' && define.amd) {
                    define([], f);
                } else {
                    var g;
                    if (typeof window !== 'undefined') {
                        g = window;
                    } else if (typeof global !== 'undefined') {
                        g = global;
                    } else if (typeof self !== 'undefined') {
                        g = self;
                    } else {
                        g = this;
                    }
                    g.mpceGenerateUUID4 = f();
                }
            }(function () {
                var define, module, exports;
                return function () {
                    function r(e, n, t) {
                        function o(i, f) {
                            if (!n[i]) {
                                if (!e[i]) {
                                    var c = 'function' == typeof require && require;
                                    if (!f && c)
                                        return c(i, !0);
                                    if (u)
                                        return u(i, !0);
                                    var a = new Error('Cannot find module \'' + i + '\'');
                                    throw a.code = 'MODULE_NOT_FOUND', a;
                                }
                                var p = n[i] = { exports: {} };
                                e[i][0].call(p.exports, function (r) {
                                    var n = e[i][1][r];
                                    return o(n || r);
                                }, p, p.exports, r, e, n, t);
                            }
                            return n[i].exports;
                        }
                        for (var u = 'function' == typeof require && require, i = 0; i < t.length; i++) {
                            o(t[i]);
                        }
                        return o;
                    }
                    return r;
                }()({
                    1: [
                        function (require, module, exports) {
                            var byteToHex = [];
                            for (var i = 0; i < 256; ++i) {
                                byteToHex[i] = (i + 256).toString(16).substr(1);
                            }
                            function bytesToUuid(buf, offset) {
                                var i = offset || 0;
                                var bth = byteToHex;
                                return [
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    '-',
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    '-',
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    '-',
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    '-',
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    bth[buf[i++]],
                                    bth[buf[i++]]
                                ].join('');
                            }
                            module.exports = bytesToUuid;
                        },
                        {}
                    ],
                    2: [
                        function (require, module, exports) {
                            var getRandomValues = typeof crypto != 'undefined' && crypto.getRandomValues && crypto.getRandomValues.bind(crypto) || typeof msCrypto != 'undefined' && typeof window.msCrypto.getRandomValues == 'function' && msCrypto.getRandomValues.bind(msCrypto);
                            if (getRandomValues) {
                                var rnds8 = new Uint8Array(16);
                                module.exports = function whatwgRNG() {
                                    getRandomValues(rnds8);
                                    return rnds8;
                                };
                            } else {
                                var rnds = new Array(16);
                                module.exports = function mathRNG() {
                                    for (var i = 0, r; i < 16; i++) {
                                        if ((i & 3) === 0)
                                            r = Math.random() * 4294967296;
                                        rnds[i] = r >>> ((i & 3) << 3) & 255;
                                    }
                                    return rnds;
                                };
                            }
                        },
                        {}
                    ],
                    3: [
                        function (require, module, exports) {
                            var rng = require('./lib/rng');
                            var bytesToUuid = require('./lib/bytesToUuid');
                            function v4(options, buf, offset) {
                                var i = buf && offset || 0;
                                if (typeof options == 'string') {
                                    buf = options === 'binary' ? new Array(16) : null;
                                    options = null;
                                }
                                options = options || {};
                                var rnds = options.random || (options.rng || rng)();
                                rnds[6] = rnds[6] & 15 | 64;
                                rnds[8] = rnds[8] & 63 | 128;
                                if (buf) {
                                    for (var ii = 0; ii < 16; ++ii) {
                                        buf[i + ii] = rnds[ii];
                                    }
                                }
                                return buf || bytesToUuid(rnds);
                            }
                            module.exports = v4;
                        },
                        {
                            './lib/bytesToUuid': 1,
                            './lib/rng': 2
                        }
                    ]
                }, {}, [3])(3);
            }));
            (function ($) {
                MP.EntityType = can.Construct(
                {
                    initByEntity: function initByEntity(entity) {
                        var entityType;
                        if (entity.length > 1 && entity.hasClass('motopress-row')) {
                            entityType = new this(IframeCE.Grid.ENTITIES.PAGE);
                        } else if (entity.hasClass('motopress-row')) {
                            entityType = new this(IframeCE.Grid.ENTITIES.ROW);
                        } else if (entity.hasClass('motopress-clmn')) {
                            entityType = new this(IframeCE.Grid.ENTITIES.COLUMN);
                        } else {
                            entityType = new this(IframeCE.Grid.ENTITIES.WIDGET);
                        }
                        return entityType;
                    }
                }, 
                {
                    is_row: false,
                    is_clmn: false,
                    is_widget: false,
                    name: '',
                    init: function init(type) {
                        switch (type) {
                        case IframeCE.Grid.ENTITIES.WIDGET:
                            this.is_widget = true;
                            break;
                        case IframeCE.Grid.ENTITIES.COLUMN:
                            this.is_clmn = true;
                            break;
                        case IframeCE.Grid.ENTITIES.ROW:
                            this.is_row = true;
                            break;
                        case IframeCE.Grid.ENTITIES.PAGE:
                            this.is_row = true;
                            break;
                        }
                        this.name = type;
                    }
                });
            }(jQuery));
            (function ($) {
                $(document).on({
                    'heartbeat-send': function heartbeatSend(event, data) {
                        data.mpcePostLock = motopressCE.postID;
                    },
                    'heartbeat-tick': function heartbeatTick(event, response) {
                        if (response.lockingUser) {
                            showLockedMessage(response.lockingUser);
                        }
                    }
                });
                $(window).one('MPCE-EditorLoad', function () {
                    if (motopressCE.settings.lockingUser) {
                        showLockedMessage(motopressCE.settings.lockingUser);
                    }
                });
                function showLockedMessage(userName) {
                    var message = MP.Utils.strtr(motopressCE.settings.translations.CEPostLockedTakeOver, {
                        '%userName%': userName,
                        '%sendback%': motopressCE.settings.sendbackText
                    });
                    if (confirm(message)) {
                        wp.heartbeat.enqueue('mpceForcePostLock', true);
                        wp.heartbeat.connectNow();
                    } else {
                        MP.Editor.myThis.redirect(motopressCE.settings.sendback);
                    }
                }
            }(jQuery));
            (function ($) {
                CE.ShortcodeAtts = can.Construct.extend({
                    getAttrsFromElement: function getAttrsFromElement(sourceEl) {
                        var atts = {
                            'closeType': sourceEl.attr('data-motopress-close-type'),
                            'id': sourceEl.attr('data-motopress-shortcode'),
                            'group': sourceEl.attr('data-motopress-group'),
                            'resize': sourceEl.attr('data-motopress-resize')
                        };
                        var parameters = sourceEl.attr('data-motopress-parameters');
                        if (typeof parameters !== 'undefined') {
                            atts.parameters = parameters;
                        }
                        var styles = sourceEl.attr('data-motopress-styles');
                        if (typeof styles !== 'undefined') {
                            atts.styles = styles;
                        }
                        var content = sourceEl.attr('data-motopress-content');
                        if (typeof content !== 'undefined') {
                            atts.content = content;
                        }
                        return new this(atts);
                    },
                    setAttsToEl: function setAttsToEl(el, atts) {
                        el.attr({
                            'data-motopress-close-type': atts.closeType,
                            'data-motopress-shortcode': atts.id,
                            'data-motopress-group': atts.group,
                            'data-motopress-resize': atts.resize
                        });
                        if (atts.hasOwnProperty('parameters')) {
                            el.attr('data-motopress-parameters', atts.parameters);
                        }
                        if (atts.hasOwnProperty('styles')) {
                            el.attr('data-motopress-styles', atts.styles);
                        }
                        if (atts.hasOwnProperty('content')) {
                            el.attr('data-motopress-content', atts.content);
                        }
                    }
                }, {
                    id: null,
                    group: null,
                    closeType: null,
                    parameters: {},
                    styles: {},
                    resize: null,
                    content: '',
                    init: function init(args) {
                        this.id = args.id;
                        var prototype = CE.WidgetsLibrary.myThis.getObjectById(this.id);
                        if (prototype) {
                            this.group = prototype.groupId;
                            this.closeType = prototype.closeType;
                            this.resize = prototype.resize;
                        }
                        if (args.hasOwnProperty('parameters')) {
                            this.parameters = args.parameters;
                        }
                        if (args.hasOwnProperty('styles')) {
                            this.styles = args.styles;
                        }
                        if (args.hasOwnProperty('content')) {
                            this.content = args.content;
                        }
                    },
                    isGrid: function isGrid() {
                        return CE.Shortcode.isGrid(this.group);
                    }
                });
            }(jQuery));
            (function ($) {
                var firstInit = true;
                var dependentCallbacks = [];
                can.Control('CE.Iframe', 
                {
                    myThis: null,
                    window: null,
                    $window: null,
                    $: null,
                    contents: null,
                    runAndSetAsDependent: function runAndSetAsDependent(callback) {
                        this.runDependentCallback(callback);
                        dependentCallbacks.push(callback);
                    },
                    runDependentCallback: function runDependentCallback(callback) {
                        callback(this);
                    },
                    runDependentCallbacks: function runDependentCallbacks() {
                        if (firstInit)
                            return;
                        for (var i = 0; i < dependentCallbacks.length; i++) {
                            this.runDependentCallback(dependentCallbacks[i]);
                        }
                    }
                }, 
                {
                    minWidth: null,
                    gridObj: null,
                    grid: $('<div />', {
                        id: 'motopress-ce-grid',
                        'class': 'mp-container'
                    }),
                    divDisablePlugin: $('<div />', { 'class': 'motopress-disable-plugin' }),
                    isHeadwayTheme: null,
                    $topBody: $('body'),
                    body: null,
                    sceneContent: null,
                    sceneContainer: null,
                    rowExample: null,
                    wpAttachmentDetails: [],
                    $form: $('form#mpce-form'),
                    init: function init(el) {
                        CE.Iframe.myThis = this;
                        CE.Iframe.window = this.element[0].contentWindow;
                        if (firstInit) {
                            el.addClass('motopress-tmp-iframe-width');
                        }
                        CE.Iframe.myThis.gridObj = parent.motopressCE.settings.library.grid;
                        switch (this.detectSceneStatus()) {
                        case 'pending':
                            this.$topBody.one('MPCESceneDocError', el, CE.Iframe.myThis.proxy(function (e, errorType) {
                                this.showErrorMessage(errorType);
                            }));
                            this.$topBody.one('MPCESceneDocReady', el, CE.Iframe.myThis.proxy(function () {
                                this.onReady();
                            }));
                            break;
                        case 'ready':
                            this.onReady();
                            break;
                        case 'error':
                            this.showErrorMessage(CE.Iframe.window.MPCESceneStatus.errorType);
                            break;
                        }
                    },
                    detectSceneStatus: function detectSceneStatus() {
                        var status = 'pending';
                        if (typeof CE.Iframe.window.MPCESceneStatus !== 'undefined' && CE.Iframe.window.MPCESceneStatus.hasOwnProperty('status')) {
                            status = CE.Iframe.window.MPCESceneStatus.status;
                        }
                        return status;
                    },
                    destroy: function destroy() {
                        firstInit = false;
                        try {
                            $(CE.Iframe.window).off('beforeunload.CE.Iframe');
                            $(parent.window).off('beforeunload.CE.Iframe');
                            this.deleteEventListeners();
                            IframeCE.Resizer.myThis._destroy();
                            IframeCE.InlineEditor._destroy();
                            CE.Panels.SettingsDialog.myThis._destroy();
                            CE.Panels.Navbar.myThis._destroy();
                        } catch (e) {
                            if (MP.Settings.debug) {
                                console.error(e);
                            }
                        }
                        this._super();
                    },
                    showErrorMessage: function showErrorMessage(e, errorType) {
                        var msg = {};
                        switch (errorType) {
                        case 'nonce':
                            msg = {
                                name: localStorage.getItem('editorNonceErrorName'),
                                message: localStorage.getItem('editorNonceErrorMessage')
                            };
                            break;
                        case 'access':
                        default:
                            msg = {
                                name: localStorage.getItem('editorAccessErrorName'),
                                message: localStorage.getItem('editorAccessErrorMessage')
                            };
                            break;
                        }
                        MP.Error.log(msg);
                    },
                    onReady: function onReady() {
                        CE.Iframe.contents = this.element.contents();
                        CE.Iframe.$ = CE.Iframe.window.jQuery;
                        CE.Iframe.$window = CE.Iframe.$(CE.Iframe.window);
                        this.body = CE.Iframe.contents.find('body');
                        this.$entryContent = this.body.find('#mpce-editable-content-marker').parent();
                        CE.Iframe.$(this.$entryContent).html(decodeURIComponent(this.body.find('#mpce-post-content-template').html() + ''));
                        CE.Iframe.contents.find('link[href*="js_composer"]').remove();
                        MP.Utils.addWpFrontEndEditorFix();
                        CE.Iframe.myThis.wpAttachmentDetails = CE.Iframe.window.mpce_wp_attachment_details;
                        this.sceneContent = this.body.find('.motopress-content-wrapper');
                        this.contentSectionExists = this.sceneContent.length;
                        CE.Panels.Navbar.myThis.onIframeLoad(this.element);
                        this.preventUnload();
                        this.body.find('#wp-motopress-tmp-editor-wrap').hide();
                        if (!this.sceneContent.length) {
                            this.body.append(this.divDisablePlugin);
                            if (this.isHeadwayTheme) {
                                MP.Flash.setFlash(localStorage.getItem('CENeedHeadwayThemeGrid'), 'error');
                            } else {
                                MP.Flash.setFlash(localStorage.getItem('CENeedContentOutput'), 'error');
                            }
                        }
                        this.element.removeClass('motopress-tmp-iframe-width');
                        var sceneContentWidth = this.sceneContent.width();
                        this.body.addClass('motopress-body');
                        this.sceneContent.find('script').remove();
                        this.sceneContent.wrap('<div class="' + CE.Iframe.myThis.gridObj.span.fullclass + '" />').parent().wrap('<div class="' + CE.Iframe.myThis.gridObj.row['class'] + ' ' + CE.Iframe.myThis.gridObj.row.edgeclass + ' motopress-row" />').parent().wrap('<div id="motopress-container" class="mp-container" />');
                        this.sceneContainer = this.sceneContent.closest('#motopress-container');
                        this.rowExample = this.sceneContainer.children('.motopress-row');
                        var wrappers = this.sceneContainer.parents();
                        var lastIndex = wrappers.length - 3;
                        wrappers.each(function (i) {
                            $(this).addClass('motopress-overflow-visible');
                            return i < lastIndex;
                        });
                        var rowExampleMarginLeft = parseFloat(this.rowExample.css('margin-left'));
                        this.rowExample.attr('data-margin-left', rowExampleMarginLeft);
                        this.sceneContent.find('.' + CE.Iframe.myThis.gridObj.span.minclass + '.motopress-clmn').addClass('motopress-clmn-min');
                        this.setSceneWidth(sceneContentWidth);
                        this.unwrapGrid();
                        this.body.prepend(this.grid);
                        this.$topBody.hide(0, this.proxy(function () {
                            this.$topBody.show();
                        }));
                        CE.Iframe.window.mpceRunScene();
                        MP.Editor.triggerIfr('ParentEditorReady');
                    },
                    setSceneWidth: function setSceneWidth(sceneContentWidth) {
                        this.sceneContainer.css('max-width', '');
                        sceneContentWidth = typeof sceneContentWidth !== 'undefined' ? sceneContentWidth : this.sceneContent.width();
                        var docWidth = CE.Iframe.contents.width(), rowExampleMarginLeft = parseFloat(this.rowExample.css('margin-left')), rowExampleMarginRight = parseFloat(this.rowExample.css('margin-right'));
                        sceneContentWidth = sceneContentWidth - rowExampleMarginLeft - rowExampleMarginRight;
                        if (sceneContentWidth > docWidth)
                            sceneContentWidth = docWidth + (rowExampleMarginLeft + rowExampleMarginRight);
                        this.sceneContainer.css('max-width', sceneContentWidth);
                        this.grid.css('max-width', sceneContentWidth);
                    },
                    unwrapGrid: function unwrapGrid(startFrom) {
                        if (!startFrom || !startFrom.length) {
                            startFrom = this.body;
                        }
                        var gridWrapperSelector = 'div[data-motopress-group="mp_grid"]:not(.ce_controls,.motopress-empty)';
                        startFrom.find(gridWrapperSelector).addBack(gridWrapperSelector).each(function () {
                            var $this = $(this);
                            var group = $this.attr('data-motopress-group');
                            var name = $this.attr('data-motopress-shortcode');
                            var attrs = $this.attr('data-motopress-parameters');
                            var styles = $this.attr('data-motopress-styles');
                            var child = $this.children('div');
                            child.attr('data-motopress-group', group);
                            child.attr('data-motopress-shortcode', name);
                            if (attrs.length)
                                child.attr('data-motopress-parameters', attrs);
                            if (styles.length)
                                child.attr('data-motopress-styles', styles);
                            child.unwrap();
                        });
                    },
                    appendScript: function appendScript(head, script) {
                        head.appendChild(script);
                    },
                    prependScript: function prependScript(parent, script) {
                        parent.insertBefore(script, parent.firstChild);
                    },
                    wpLinkCloseCallback: function wpLinkCloseCallback(callback) {
                        $(document).on('wplink-close.motopress', function (e) {
                            $(document).off('wplink-close.motopress');
                            callback();
                        });
                    },
                    preventUnload: function preventUnload() {
                        this.body.find('a').on('click', function (e) {
                            e.preventDefault();
                        });
                        this.body.find('form').on('submit', function (e) {
                            e.preventDefault();
                        });
                        var isParentReload = false;
                        $(window).on('beforeunload.CE.Iframe', function () {
                            isParentReload = true;
                        });
                        $(CE.Iframe.window).on('beforeunload.CE.Iframe', function () {
                            if (!isParentReload)
                                return localStorage.getItem('postSaveAlert');
                            isParentReload = false;
                        });
                    },
                    resizeWindow: function resizeWindow() {
                        IframeCE.Utils.triggerWindowEvent('resize');
                    },
                    setMinWidth: function setMinWidth() {
                        this.element.css('min-width', this.minWidth);
                    },
                    unsetMinWidth: function unsetMinWidth() {
                        this.element.css('min-width', '');
                    },
                    getRowSlug: function getRowSlug(level) {
                        return parent.CE.Iframe.myThis.gridObj.row[level === 1 ? 'shortcode' : 'inner'];
                    },
                    getClmnSlug: function getClmnSlug(level, clmnSize) {
                        var mpSpan, mpSpanAttr = '';
                        if (parent.CE.Iframe.myThis.gridObj.span.type && parent.CE.Iframe.myThis.gridObj.span.type === 'multiple') {
                            mpSpan = parent.CE.Iframe.myThis.gridObj.span[level === 1 ? 'shortcode' : 'inner'][clmnSize - 1];
                        } else {
                            mpSpanAttr = ' ' + parent.CE.Iframe.myThis.gridObj.span.attr + '="' + clmnSize + '"';
                            mpSpan = parent.CE.Iframe.myThis.gridObj.span[level === 1 ? 'shortcode' : 'inner'];
                        }
                        return {
                            slug: mpSpan,
                            attr: mpSpanAttr
                        };
                    },
                    deleteEventListeners: function deleteEventListeners() {
                        var subscribers = CE.IframeEventStorage.getSubscribers();
                        var listeners = CE.IframeEventStorage.getListeners();
                        var i;
                        for (i = 0; i < subscribers.length; i++) {
                            CE.EventDispatcher.Dispatcher.deleteSubscriber(subscribers[i]);
                        }
                        for (i = 0; i < listeners.length; i++) {
                            CE.EventDispatcher.Dispatcher.deleteListener(listeners[i]);
                        }
                    },
                    getContentRootElement: function getContentRootElement() {
                        return CE.Iframe.contents.find('body .motopress-content-wrapper:eq(0)');
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Libraries = {};
            }(jQuery));
            (function ($) {
                CE.Libraries.Events = {};
                CE.Libraries.Events.WidgetTemplateSaved = parent.CE.EventDispatcher.Event.extend({ NAME: 'libraries.widget_template_saved' }, {
                    widgetTemplate: null,
                    init: function init(widgetTemplate) {
                        this.widgetTemplate = widgetTemplate;
                    },
                    getWidgetTemplate: function getWidgetTemplate() {
                        return this.widgetTemplate;
                    }
                });
                CE.Libraries.Events.RowTemplateSaved = parent.CE.EventDispatcher.Event.extend({ NAME: 'libraries.row_template_saved' }, {
                    rowTemplate: null,
                    init: function init(rowTemplate) {
                        this.rowTemplate = rowTemplate;
                    },
                    getRowTemplate: function getRowTemplate() {
                        return this.rowTemplate;
                    }
                });
                CE.Libraries.Events.PageTemplateSaved = parent.CE.EventDispatcher.Event.extend({ NAME: 'libraries.page_template_saved' }, {
                    pageTemplate: null,
                    init: function init(pageTemplate) {
                        this.pageTemplate = pageTemplate;
                    },
                    getPageTemplate: function getPageTemplate() {
                        return this.pageTemplate;
                    }
                });
            }(jQuery));
            (function ($) {
                can.Construct('CE.WidgetsLibrary', 
                { myThis: null }, 
                {
                    library: null,
                    grid: null,
                    init: function init(args) {
                        CE.WidgetsLibrary.myThis = this;
                        var library = args.library;
                        this.initWidgets(library.groups);
                        this.grid = $.extend(true, {}, library.grid);
                        this.initLibraryStyles();
                        CE.Iframe.runAndSetAsDependent(this.proxy(function (iframeStatic) {
                            if ($.isPlainObject(library.globalPredefinedClasses) && !$.isEmptyObject(library.globalPredefinedClasses)) {
                                IframeCE.Style.globalPredefinedClasses = library.globalPredefinedClasses;
                            }
                            if ($.isArray(library.tinyMCEStyleFormats) && !$.isEmptyObject(library.tinyMCEStyleFormats)) {
                                IframeCE.InlineEditor.styleFormats = library.tinyMCEStyleFormats;
                            }
                        }));
                    },
                    initWidgets: function initWidgets(groups) {
                        this.library = $.extend(true, {}, groups);
                        $.each(this.library, function (groupName, groupDetails) {
                            if (groupDetails.hasOwnProperty('objects')) {
                                $.each(groupDetails.objects, function (objName, objDetails) {
                                    objDetails.groupId = groupDetails.id;
                                });
                            }
                        });
                    },
                    initLibraryStyles: function initLibraryStyles() {
                        var $this = this;
                        var library = motopressCE.settings.library, limitations;
                        $.each(library.groups, function (groupName, groupDetails) {
                            if (groupDetails.hasOwnProperty('objects')) {
                                $.each(groupDetails.objects, function (objName, objDetails) {
                                    $this.library[groupName].objects[objName].styles = $.extend(true, {}, IframeCE.Style.props, $this.library[groupName].objects[objName].styles);
                                    if (IframeCE.Shortcode.isGrid(groupName)) {
                                        $this.library[groupName].objects[objName].styles.margin.sides.splice(2);
                                        $this.library[groupName].objects[objName].styles.margin['default'].splice(2);
                                    } else {
                                        $this.library[groupName].objects[objName].styles.margin.regExp = new RegExp('^' + $this.library[groupName].objects[objName].styles.margin.classPrefix + '(?:|(' + $this.library[groupName].objects[objName].styles.margin.sides.join('|') + ')-)(' + $this.library[groupName].objects[objName].styles.margin.values.slice(1).join('|') + ')$', 'i');
                                    }
                                    if (objDetails.styles.hasOwnProperty('mp_custom_style') && objDetails.styles.mp_custom_style.hasOwnProperty('limitation')) {
                                        limitations = objDetails.styles.mp_custom_style.limitation;
                                        $.each(limitations, function (key, limitation) {
                                            if ($this.library[groupName].objects[objName].styles.mp_custom_style.parameters.hasOwnProperty(limitation)) {
                                                delete $this.library[groupName].objects[objName].styles.mp_custom_style.parameters[limitation];
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    },
                    getLibrary: function getLibrary() {
                        return this.library;
                    },
                    getGrid: function getGrid() {
                        return this.grid;
                    },
                    getObject: function getObject(group, name) {
                        var object;
                        var group = this.getGroup(group);
                        if (group && group.objects.hasOwnProperty(name)) {
                            object = group.objects[name];
                        }
                        return object;
                    },
                    getObjectById: function getObjectById(id) {
                        var object;
                        $.each(this.library, function (groupId, group) {
                            $.each(group.objects, function (objectId, objectData) {
                                if (objectData.id == id) {
                                    object = objectData;
                                    return false;
                                }
                            });
                            if (object) {
                                return false;
                            }
                        });
                        return object;
                    },
                    getGroup: function getGroup(name) {
                        var group;
                        if (this.library.hasOwnProperty(name)) {
                            group = this.library[name];
                        }
                        return group;
                    },
                    getStyleAttrs: function getStyleAttrs(groupName, shortcodeName) {
                        return $.extend(true, {}, this.library[groupName].objects[shortcodeName].styles);
                    },
                    getPrivateStyleAttrs: function getPrivateStyleAttrs(groupName, shortcodeName) {
                        var styles = this.getStyleAttrs(groupName, shortcodeName);
                        return styles['mp_custom_style'].parameters;
                    },
                    getParametersAttrs: function getParametersAttrs(groupName, shortcodeName) {
                        return $.extend(true, {}, this.library[groupName].objects[shortcodeName].parameters);
                    },
                    getShortcodeLabel: function getShortcodeLabel(groupName, shortcodeName) {
                        return this.library[groupName].objects[shortcodeName].name;
                    },
                    setAttrs: function setAttrs(object, group, atts, clearParams) {
                        if (typeof clearParams === 'undefined') {
                            clearParams = true;
                        }
                        object.attr({
                            'data-motopress-close-type': atts['closeType'],
                            'data-motopress-shortcode': atts['id'],
                            'data-motopress-group': group,
                            'data-motopress-resize': atts['resize']
                        });
                        if (atts.hasOwnProperty('content')) {
                            object.attr('data-motopress-content', atts['content']);
                        }
                        if (clearParams) {
                            var parameters = atts['parameters'];
                            if (!$.isEmptyObject(parameters)) {
                                var parametersObj = {};
                                $.each(parameters, function (key) {
                                    parametersObj[key] = {};
                                });
                                object.attr('data-motopress-parameters', JSON.stringify(parametersObj));
                            }
                            var styles = this.getStyleAttrs(group, atts['id']);
                            $.extend(true, styles, atts['styles'], styles);
                            if (!$.isEmptyObject(styles)) {
                                var stylesObj = {};
                                $.each(styles, function (key) {
                                    stylesObj[key] = {};
                                });
                                object.attr('data-motopress-styles', JSON.stringify(stylesObj));
                            }
                        } else {
                            object.attr('data-motopress-parameters', JSON.stringify(atts.parameters));
                            object.attr('data-motopress-styles', JSON.stringify(atts.styles));
                        }
                    },
                    getAttrs: function getAttrs(groupId, objectId) {
                        var object = this.getObject(groupId, objectId);
                        var attrs = {
                            'closeType': object.closeType,
                            'id': object.id,
                            'group': groupId,
                            'resize': object.resize
                        };
                        var parameters = object.parameters;
                        if (!$.isEmptyObject(parameters)) {
                            var parametersObj = {};
                            $.each(parameters, function (key) {
                                parametersObj[key] = {};
                            });
                            attrs['parameters'] = JSON.stringify(parametersObj);
                        }
                        var styles = this.getStyleAttrs(groupId, attrs['id']);
                        $.extend(true, styles, attrs['styles'], styles);
                        if (!$.isEmptyObject(styles)) {
                            var stylesObj = {};
                            $.each(styles, function (key) {
                                stylesObj[key] = {};
                            });
                            attrs['styles'] = JSON.stringify(stylesObj);
                        }
                        return attrs;
                    },
                    getActualAtts: function getActualAtts(obj) {
                        var atts = {
                            closeType: obj.attr('data-motopress-close-type'),
                            group: obj.attr('data-motopress-group'),
                            id: obj.attr('data-motopress-shortcode'),
                            resize: obj.attr('data-motopress-resize'),
                            wrapRender: null,
                            parameters: null,
                            styles: null,
                            content: null
                        };
                        if (typeof obj.attr('data-motopress-wrap-render') !== 'undefined') {
                            atts.wrapRender = obj.attr('data-motopress-wrap-render');
                        }
                        if (typeof obj.attr('data-motopress-parameters') !== 'undefined') {
                            var parameters = obj.attr('data-motopress-parameters');
                            atts.parameters = JSON.parse(parameters);
                        }
                        if (typeof obj.attr('data-motopress-styles') !== 'undefined') {
                            var styles = obj.attr('data-motopress-styles');
                            atts.styles = JSON.parse(styles);
                        }
                        if (typeof obj.attr('data-motopress-content') !== 'undefined') {
                            atts.content = obj.attr('data-motopress-content').replace(/\[\]/g, '[');
                        }
                        return atts;
                    }
                });
            }(jQuery));
            (function ($) {
                can.Construct('CE.ObjectTemplatesLibrary', 
                { myThis: null }, 
                {
                    library: {
                        widgets: {
                            list: new can.List(),
                            categories: []
                        },
                        rows: {
                            list: [],
                            categories: []
                        },
                        pages: {
                            list: [],
                            categories: []
                        }
                    },
                    init: function init(library) {
                        CE.ObjectTemplatesLibrary.myThis = this;
                        this.initLibrary(library);
                    },
                    saveWidget: function saveWidget(widget) {
                        widget.setId(this.library.widgets.list.length);
                        this.library.widgets.list.push(widget);
                        parent.CE.EventDispatcher.Dispatcher.dispatch(CE.Libraries.Events.WidgetTemplateSaved.NAME, new CE.Libraries.Events.WidgetTemplateSaved(widget));
                        return this._save();
                    },
                    saveRow: function saveRow(row) {
                        row.setId(this.library.rows.list.length);
                        this.library.rows.list.push(row);
                        parent.CE.EventDispatcher.Dispatcher.dispatch(CE.Libraries.Events.RowTemplateSaved.NAME, new CE.Libraries.Events.RowTemplateSaved(row));
                        return this._save();
                    },
                    savePage: function savePage(page) {
                        page.setId(this.library.pages.list.length);
                        this.library.pages.list.push(page);
                        parent.CE.EventDispatcher.Dispatcher.dispatch(CE.Libraries.Events.PageTemplateSaved.NAME, new CE.Libraries.Events.PageTemplateSaved(page));
                        return this._save();
                    },
                    addWidgetCategory: function addWidgetCategory(category) {
                        return this.library.widgets.categories.push(category) - 1;
                    },
                    getWidgets: function getWidgets() {
                        return this.library.widgets.list;
                    },
                    getWidgetCategories: function getWidgetCategories() {
                        return this.library.widgets.categories;
                    },
                    getWidgetById: function getWidgetById(id) {
                        return this.library.widgets.list.attr(id);
                    },
                    addRowCategory: function addRowCategory(category) {
                        return this.library.rows.categories.push(category) - 1;
                    },
                    getRowCategories: function getRowCategories() {
                        return this.library.rows.categories;
                    },
                    getRows: function getRows() {
                        return this.library.rows.list;
                    },
                    getRowById: function getRowById(id) {
                        return this.library.rows.list[id];
                    },
                    getPages: function getPages() {
                        return this.library.pages.list;
                    },
                    getPageById: function getPageById(id) {
                        return this.library.pages.list[id];
                    },
                    addPageCategory: function addPageCategory(category) {
                        return this.library.pages.categories.push(category) - 1;
                    },
                    getPageCategories: function getPageCategories() {
                        return this.library.pages.categories;
                    },
                    initLibrary: function initLibrary(rawLibrary) {
                        $.each(rawLibrary.widgets.list, this.proxy(function (index, widgetAtts) {
                            widgetAtts.id = index;
                            var prototype = CE.WidgetsLibrary.myThis.getObjectById(widgetAtts.shortcodeId);
                            if (!prototype) {
                                return true;    
                            }
                            widgetAtts.atts = new CE.ShortcodeAtts({
                                id: prototype.id,
                                group: prototype.group,
                                content: widgetAtts.atts.content,
                                parameters: widgetAtts.atts.parameters,
                                styles: widgetAtts.atts.styles,
                                closeType: prototype.closeType,
                                resize: prototype.resize
                            });
                            this.library.widgets.list.push(new CE.ObjectTemplatesLibrary.WidgetTemplate(widgetAtts));
                        }));
                        $.each(rawLibrary.rows.list, this.proxy(function (index, rowAtts) {
                            rowAtts.id = index;
                            this.library.rows.list.push(new CE.ObjectTemplatesLibrary.RowTemplate(rowAtts));
                        }));
                        $.each(rawLibrary.pages.list, this.proxy(function (index, pageAtts) {
                            pageAtts.id = index;
                            this.library.pages.list.push(new CE.ObjectTemplatesLibrary.PageTemplate(pageAtts));
                        }));
                        this.library.widgets.categories = rawLibrary.widgets.categories;
                        this.library.rows.categories = rawLibrary.rows.categories;
                        this.library.pages.categories = rawLibrary.pages.categories;
                    },
                    prepareLibraryToSave: function prepareLibraryToSave() {
                        var preparedLibrary = {
                            widgets: {
                                list: $.map(this.library.widgets.list.attr(), function (widget) {
                                    return widget.prepareToSave();
                                }),
                                categories: this.library.widgets.categories
                            },
                            rows: {
                                list: $.map(this.library.rows.list, function (row) {
                                    return row.prepareToSave();
                                }),
                                categories: this.library.rows.categories
                            },
                            pages: {
                                list: $.map(this.library.pages.list, function (page) {
                                    return page.prepareToSave();
                                }),
                                categories: this.library.pages.categories
                            }
                        };
                        return preparedLibrary;
                    },
                    _save: function _save() {
                        var defer = new $.Deferred();
                        $.ajax({
                            url: parent.motopress.ajaxUrl,
                            type: 'POST',
                            dataType: 'html',
                            data: {
                                action: 'motopress_ce_save_object_templates',
                                nonce: parent.motopressCE.nonces.motopress_ce_save_object_templates,
                                postID: parent.motopressCE.postID,
                                library: this.prepareLibraryToSave()
                            },
                            success: function success() {
                                defer.resolve();
                            },
                            error: function error() {
                                defer.reject();
                            }
                        });
                        return defer.promise();
                    }
                });
            }(jQuery));
            (function ($) {
                can.Construct('CE.ObjectTemplatesLibrary.WidgetTemplate', 
                {}, 
                {
                    id: '',
                    name: '',
                    category: '',
                    content: '',
                    shortcodeId: '',
                    groupId: '',
                    icon: '',
                    atts: '',
                    init: function init(args) {
                        if (args.hasOwnProperty('id')) {
                            this.id = args.id;
                        }
                        this.name = args.name;
                        this.category = args.category;
                        this.shortcodeId = args.shortcodeId;
                        this.atts = args.atts;
                        var prototypeObject = CE.WidgetsLibrary.myThis.getObjectById(this.shortcodeId);
                        if (prototypeObject) {
                            this.groupId = prototypeObject.groupId;
                            this.icon = prototypeObject.icon;
                        }
                        this.content = args.content;
                    },
                    prepareToSave: function prepareToSave() {
                        return {
                            name: this.name,
                            category: this.category,
                            shortcodeId: this.shortcodeId,
                            content: this.content,
                            atts: {
                                content: this.atts.content,
                                parameters: this.atts.parameters,
                                styles: this.atts.styles
                            }
                        };
                    },
                    setId: function setId(id) {
                        this.id = id;
                    }
                });
            }(jQuery));
            (function ($) {
                can.Construct('CE.ObjectTemplatesLibrary.RowTemplate', 
                {}, 
                {
                    id: '',
                    name: '',
                    category: '',
                    content: '',
                    icon: '',
                    styles: {},
                    init: function init(args) {
                        if (args.hasOwnProperty('id')) {
                            this.id = args.id;
                        }
                        this.name = args.name;
                        this.category = args.category;
                        var rowShortcodeId = parent.CE.WidgetsLibrary.myThis.getGrid().row.shortcode;
                        var prototypeObject = CE.WidgetsLibrary.myThis.getObjectById(rowShortcodeId);
                        this.icon = prototypeObject.icon;
                        this.styles = args.styles;
                        this.content = args.content;
                    },
                    prepareToSave: function prepareToSave() {
                        return {
                            name: this.name,
                            category: this.category,
                            content: this.content,
                            styles: this.styles
                        };
                    },
                    setId: function setId(id) {
                        this.id = id;
                    }
                });
            }(jQuery));
            (function ($) {
                can.Construct('CE.ObjectTemplatesLibrary.PageTemplate', 
                {}, 
                {
                    id: '',
                    name: '',
                    category: '',
                    content: '',
                    icon: '',
                    styles: {},
                    init: function init(args) {
                        if (args.hasOwnProperty('id')) {
                            this.id = args.id;
                        }
                        this.name = args.name;
                        this.category = args.category;
                        this.icon = '';
                        this.styles = args.styles;
                        this.content = args.content;
                    },
                    prepareToSave: function prepareToSave() {
                        return {
                            name: this.name,
                            category: this.category,
                            content: this.content,
                            styles: this.styles
                        };
                    },
                    setId: function setId(id) {
                        this.id = id;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Panels = {};
                can.Control.extend('CE.Panels.DialogsManager', { myThis: null }, {
                    dialogs: [],
                    init: function init(el, args) {
                        CE.Panels.DialogsManager.myThis = this;
                        CE.Iframe.runAndSetAsDependent(this.proxy(function (iframeStatic) {
                            iframeStatic.$window.on('click', this.proxy('clickHandler'));
                            iframeStatic.$window.on('keydown', this.proxy('keydownHandler'));
                        }));
                        CE.EventDispatcher.Dispatcher.addListener(CE.PanelEvents.DialogOpen.NAME, this.proxy(this.onDialogOpen));
                    },
                    onDialogOpen: function onDialogOpen(event) {
                        var dialog = event.getDialog();
                        var dialogName = dialog.getName();
                        var activeDialog = this.getActiveDialog();
                        if ($.inArray(dialogName, [
                                CE.Panels.WidgetsDialog.NAME,
                                CE.Panels.LayoutChooserDialog.NAME,
                                CE.Panels.PageDialog.NAME
                            ]) > -1) {
                            IframeCE.Selectable.myThis.unselect();
                            if (activeDialog && activeDialog != dialog) {
                                if (activeDialog.getName() !== CE.Panels.HistoryDialog.NAME) {
                                    activeDialog.close();
                                }
                            }
                        }
                    },
                    registerDialog: function registerDialog(panel) {
                        this.dialogs.push(panel);
                    },
                    getActiveDialog: function getActiveDialog() {
                        return this.dialogs.find(function (dialog) {
                            return dialog.isOpen();
                        });
                    },
                    clickHandler: function clickHandler(e) {
                        if (!IframeCE.Selectable.myThis.isHandleForSelected($(e.target)) && !$(e.target).hasClass('mpce-panel-btn') && !$(e.target).closest(parent.MP.Utils.convertClassesToSelector(parent.CE.Panels.SettingsDialog.myThis.dialogClass)).length && !IframeCE.InlineEditor.isTinymce(e)) {
                            var activeDialog = CE.Panels.DialogsManager.myThis.getActiveDialog();
                            if (!activeDialog) {
                                return;
                            }
                            var dialogName = activeDialog.getName();
                            if (dialogName === CE.Panels.SettingsDialog.NAME) {
                                IframeCE.Selectable.myThis.unselect();
                            } else {
                                if (dialogName !== CE.Panels.HistoryDialog.NAME) {
                                    activeDialog.close();
                                }
                            }
                        }
                    },
                    keydownHandler: function keydownHandler(e) {
                    }
                });
            }(jQuery));
            (function ($) {
                CE.PanelEvents = {};
                CE.PanelEvents.Event = parent.CE.EventDispatcher.Event.extend({}, {});
                CE.PanelEvents.PanelEvent = CE.PanelEvents.Event.extend({}, {
                    dialog: null,
                    init: function init(dialog) {
                        this.dialog = dialog;
                    },
                    getDialog: function getDialog() {
                        return this.dialog;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.PanelEvents.DialogOpen = CE.PanelEvents.PanelEvent.extend({ NAME: 'panels.dialog.open' }, {});
                CE.PanelEvents.DialogClose = CE.PanelEvents.PanelEvent.extend({ NAME: 'panels.dialog.close' }, {});
            }(jQuery));
            (function ($) {
                can.Control.extend('CE.Panels.AbstractDialog', {
                    myThis: null,
                    NAME: null
                }, {
                    name: null,
                    openDeferred: null,
                    init: function init(el, args) {
                        CE.Panels.DialogsManager.myThis.registerDialog(this);
                    },
                    open: function open() {
                        if (this.openDeferred && this.openDeferred.state() == 'pending') {
                            this.openDeferred.reject();
                        }
                        this.openDeferred = new $.Deferred();
                        CE.EventDispatcher.Dispatcher.dispatch(CE.PanelEvents.DialogOpen.NAME, new CE.PanelEvents.DialogOpen(this));
                        this.element.dialog('open');
                        return this.openDeferred.promise();
                    },
                    close: function close() {
                        if (this.openDeferred && this.openDeferred.state() == 'pending') {
                            this.openDeferred.reject();
                        }
                        if (this.isOpen()) {
                            this.element.dialog('close');
                        }
                    },
                    toggle: function toggle() {
                        this.isOpen() ? this.close() : this.open();
                    },
                    isOpen: function isOpen() {
                        return this.element.dialog('isOpen');
                    },
                    getName: function getName() {
                        return this.constructor.NAME;
                    }
                });
            }(jQuery));
            (function ($) {
                can.Control('CE.Panels.Navbar', 
                { myThis: null }, 
                {
                    editorEl: $('#motopress-content-editor'),
                    editorWrapperEl: $('#motopress-content-editor-scene-wrapper'),
                    postTypeLabel: null,
                    tutorialsCounter: 0,
                    init: function init(el, options) {
                        CE.Panels.Navbar.myThis = this;
                        CE.Iframe.myThis = null;
                        this.postTypeLabel = motopressCE.postData.postTypeLabel;
                        this.$savingBtns = el.find('.motopress-content-editor-saving-btn');
                        this.showContentEditor();
                        this.fixEditorWrapperHeight();
                        this.updateSaveButtons();
                        MP.Preloader.myThis.load(CE.Panels.Navbar.shortName);
                        MP.Editor.on('BeforeSave', this.proxy('beforeSave'));
                        MP.Editor.on('AfterUpdate', this.proxy('successSave'));
                        MP.Editor.on('SaveComplete', this.proxy('completedSave'));
                    },
                    fixEditorWrapperHeight: function fixEditorWrapperHeight() {
                        if (this.editorWrapperEl.height() === 0) {
                            var h1 = this.editorWrapperEl.parent().height() - 53;
                            if (h1 > 0)
                                this.editorWrapperEl.height(h1);
                            var h2 = this.editorEl.parent().height() - 32;
                            if (h2 > 0)
                                this.editorEl.height(h2);
                            $(window).resize(function () {
                                var h1 = CE.Panels.Navbar.myThis.editorWrapperEl.parent().height() - 53;
                                if (h1 > 0)
                                    CE.Panels.Navbar.myThis.editorWrapperEl.height(h1);
                                var h2 = CE.Panels.Navbar.myThis.editorEl.parent().height() - 32;
                                if (h2 > 0)
                                    CE.Panels.Navbar.myThis.editorEl.height(h2);
                            });
                        }
                    },
                    onIframeLoad: function onIframeLoad(iframe) {
                        var minWidth = MP.Utils.getScrollbarWidth() + 724 + 80;
                        CE.Iframe.myThis.minWidth = minWidth;
                        CE.Iframe.myThis.setMinWidth();    
                    },
                    updateSaveButtons: function updateSaveButtons() {
                        var publishBtn = this.element.find('#motopress-content-editor-publish');
                        if (this.options.PageSettings.status == 'publish') {
                            publishBtn.closest('li').hide();
                        } else {
                            publishBtn.closest('li').show();
                        }
                    },
                    '#motopress-content-editor-tutorials click': function motopressContentEditorTutorialsClick(e) {
                        var modalData = jQuery('#motopress-tutorials-modal'), bodyBG = jQuery('body');
                        modalData.mpmodal('toggle');
                        if (this.tutorialsCounter === 0) {
                            jQuery.ajax({
                                type: 'post',
                                dataType: 'html',
                                url: motopress.ajaxUrl,
                                data: { action: 'motopress_tutorials' },
                                success: function success(response) {
                                    if (response != 0 && response != 'nothing') {
                                        modalData.find('.modal-body').html(response);
                                    } else {
                                        modalData.find('.modal-body').html('<h1>Error: can\'t load tutorials.<h1>');
                                    }
                                }
                            });
                        }
                        this.tutorialsCounter++;
                    },
                    beforeSave: function beforeSave() {
                        this.$savingBtns.addClass('mpce-disabled-btn mpce-in-progress');
                    },
                    completedSave: function completedSave() {
                        this.$savingBtns.removeClass('mpce-disabled-btn mpce-in-progress');
                    },
                    successSave: function successSave() {
                        var link;
                        if (CE.Settings.Page.attr('status') === 'draft') {
                            link = motopressCE.postData.previewUrl;
                        } else {
                            link = motopressCE.postData.viewUrl;
                        }
                        MP.Flash.setFlash(MP.Utils.strtr(localStorage.getItem('CEPostSaved'), {
                            '%postType%': this.postTypeLabel,
                            '%link%': link,
                            '%preview%': localStorage.getItem('CEPreviewBtnText')
                        }), 'success', 'success-save');
                        MP.Flash.showMessage();
                    },
                    save: function save(status) {
                        CE.Save.saveAJAX(status);
                    },
                    '#motopress-content-editor-publish:not(.mpce-disabled-btn) click': function motopressContentEditorPublishNotMpceDisabledBtnClick() {
                        CE.Saving.save('publish');
                    },
                    '#motopress-content-editor-update:not(.mpce-disabled-btn) click': function motopressContentEditorUpdateNotMpceDisabledBtnClick() {
                        CE.Saving.save();
                    },
                    '#motopress-content-editor-page-settings click': function motopressContentEditorPageSettingsClick() {
                        CE.Panels.PageDialog.myThis.toggle();
                    },
                    '#motopress-content-editor-history click': function motopressContentEditorHistoryClick() {
                        CE.Panels.HistoryDialog.myThis.toggle();
                    },
                    '#motopress-content-editor-save-page-object-template click': function motopressContentEditorSavePageObjectTemplateClick(el, e) {
                        var categories = CE.ObjectTemplatesLibrary.myThis.getPageCategories();
                        var saveObjectPromise = CE.SaveObjectModal.myThis.showModal({ categories: categories });
                        saveObjectPromise.done(function (objectTemplateAtts) {
                            var category = objectTemplateAtts.category;
                            if (!objectTemplateAtts.name) {
                                objectTemplateAtts.name = 'Page';
                            }
                            if (objectTemplateAtts.hasOwnProperty('newCategoryTitle')) {
                                category = objectTemplateAtts.newCategoryTitle ? CE.ObjectTemplatesLibrary.myThis.addPageCategory(objectTemplateAtts.newCategoryTitle) : 0;
                            }
                            var contentParser = new CE.ContentTemplateParser(CE.Iframe.myThis.getContentRootElement());
                            var page = new CE.ObjectTemplatesLibrary.PageTemplate({
                                name: objectTemplateAtts.name,
                                category: category,
                                content: contentParser.content,
                                styles: contentParser.replaceableStyles
                            });
                            CE.ObjectTemplatesLibrary.myThis.savePage(page);
                        });
                    },
                    '#motopress-content-editor-close click': function motopressContentEditorCloseClick() {
                        MP.Editor.myThis.close();
                    },
                    '{PageSettings} status': function PageSettingsStatus() {
                        this.updateSaveButtons();
                    },
                    hide: function hide() {
                        this.abortShow();
                        this.element.addClass('motopress-ce-navbar-hide').one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function (el, e) {
                            CE.Panels.Navbar.myThis.element.addClass('motopress-hide');
                            CE.Panels.Navbar.myThis.element.removeClass('motopress-ce-navbar-hide');
                        });
                    },
                    show: function show() {
                        this.abortHide();
                        this.element.addClass('motopress-ce-navbar-show').removeClass('motopress-hide').one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function () {
                            CE.Panels.Navbar.myThis.element.removeClass('motopress-ce-navbar-show');
                        });
                    },
                    abortShow: function abortShow() {
                        this.element.removeClass('motopress-ce-navbar-show').off('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd');
                    },
                    abortHide: function abortHide() {
                        this.element.removeClass('motopress-ce-navbar-hide').off('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd');
                    },
                    hideContentEditor: function hideContentEditor() {
                        MP.Error.terminate();
                    },
                    showContentEditor: function showContentEditor() {
                        $('html').css({
                            overflow: 'hidden',
                            paddingTop: 0
                        });
                        this.editorEl.siblings(':not("#' + MP.Preloader.myThis.element.attr('id') + ', script, link")').each(function () {
                            if (!$(this).is(':hidden')) {
                                $(this).addClass('motopress-hide');
                            }
                        });
                        $('#wpwrap').height('100%').children('#wpcontent').children('#wpadminbar').prependTo('body');
                        this.editorEl.show();
                    }
                });
            }(jQuery));
            can.view.stache('widgetsDialogCommonTab', '<div id="mpce-widgets-common-panel">\n\t\t<div id="mpce-widgets-filter-wrapper">\n\t\t\t<input type="text" class="mpce-widgets-search" placeholder="{{translations.search}}"/>\n\t\t\t<select class="mpce-widgets-category-filter">\n\t\t\t\t<option value="">{{translations.all}}</option>\n\t\t\t\t{{#each library}}\n\t\t\t\t\t<option value="{{id}}">{{name}}</option>\n\t\t\t\t{{/each}}\n\t\t\t</select>\n\t\t</div>\n\t\t<div id="mpce-widgets-wrapper">\n\t\t\t<div id="mpce-recent-widgets"></div>\n\t\t\t<div id="mpce-regular-widgets">\n\t\t\t\t<ul class="mpce-widgets-list mpce-regular-widgets-list">\n\t\t\t\t\t{{#each library}}\n\t\t\t\t\t\t<li class="mpce-widget-group-wrapper" data-id="{{id}}">\n\t\t\t\t\t\t\t<ul>\n\t\t\t\t\t\t\t\t{{#each objects}}\n\t\t\t\t\t\t\t\t\t<li class="mpce-widget-group-{{groupId}} mpce-widget-item-wrapper mpce-widget-id-{{id}}">\n\t\t\t\t\t\t\t\t\t\t<div class="mpce-widget-item"\n\t\t\t\t\t\t\t\t\t\t\t data-widget-id="{{id}}" data-widget-group-id="{{groupId}}" data-widget-title="{{name}}">\n\t\t\t\t\t\t\t\t\t\t\t<span class="mpce-widget-icon" style="background-image: url(\'{{icon}}\');"></span>\n\t\t\t\t\t\t\t\t\t\t\t<span class="mpce-widget-name motopress-default motopress-no-color-text">{{name}}</span>\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t</li>\n\t\t\t\t\t\t\t\t{{/each}}\n\t\t\t\t\t\t\t</ul>\n\t\t\t\t\t\t</li>\n\t\t\t\t\t{{/each}}\n\t\t\t\t</ul>\n\t\t\t</div>\n\t\t</div>\n\t</div>');
            can.view.stache('widgetsDialogLibraryTab', '<div id="mpce-widgets-library-panel">\n\t\t<div id="mpce-widgets-filter-wrapper">\n\t\t\t<input type="text" class="mpce-widgets-search" placeholder="{{translations.search}}"/>\n\t\t\t<select class="mpce-widgets-category-filter">\n\t\t\t\t<option value="">{{translations.all}}</option>\n\t\t\t\t{{#each library}}\n\t\t\t\t\t<option value="{{id}}">{{name}}</option>\n\t\t\t\t{{/each}}\n\t\t\t</select>\n\t\t</div>\n\t\t<div id="mpce-widgets-wrapper">\n\t\t\t<div id="mpce-library-widgets">\n\t\t\t\t{{#templatesExists}}\n\t\t\t\t\t<ul class="mpce-widgets-list mpce-library-widgets-list">\n\t\t\t\t\t\t{{#each library}}\n\t\t\t\t\t\t\t<li class="mpce-widget-group-wrapper" data-id="{{id}}">\n\t\t\t\t\t\t\t\t<ul>\n\t\t\t\t\t\t\t\t\t{{#each objects}}\n\t\t\t\t\t\t\t\t\t\t<li class="mpce-widget-group-{{groupId}} mpce-widget-item-wrapper mpce-widget-id-{{id}}">\n\t\t\t\t\t\t\t\t\t\t\t<div class="mpce-widget-item"\n\t\t\t\t\t\t\t\t\t\t\t data-widget-template-id="{{id}}" data-widget-category-id="{{category}}" data-widget-group-id="{{groupId}}" data-widget-title="{{name}}">\n\t\t\t\t\t\t\t\t\t\t\t\t<span class="mpce-widget-icon" style="background-image: url(\'{{icon}}\');"></span>\n\t\t\t\t\t\t\t\t\t\t\t\t<span class="mpce-widget-name motopress-default motopress-no-color-text">{{name}}</span>\n\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t</li>\n\t\t\t\t\t\t\t\t\t{{/each}}\n\t\t\t\t\t\t\t\t</ul>\n\t\t\t\t\t\t\t</li>\n\t\t\t\t\t\t{{/each}}\n\t\t\t\t\t</ul>\n\t\t\t\t{{/templatesExists}}\n\t\t\t\t{{^templatesExists}}\n\t\t\t\t\t<p><em>{{translations.noTemplates}}</em></p>\n\t\t\t\t{{/templatesExists}}\n\t\t\t</div>\n\t\t</div>\n\t</div>');
            can.view.stache('widgetsDialog', '<div class="motopress-dialog-tabs" id="mpce-widgets-tab">' + '<ul>' + '{{#each tabs}}' + '<li>' + '<a href="#{{id}}" class="motopress-text-no-color-text">' + '{{title}}' + '</a>' + '</li>' + '{{/each}}' + '</ul>' + '{{#each tabs}}' + '<div id="{{id}}">' + '{{content}}' + '</div>' + '{{/each}}' + '</div>');
            can.view.stache('recentWidgets', '{{#recentWidgets.length}}\n\t\t<p>{{translations.recentWidgetsTitle}}</p>\n\t\t<ul class="mpce-widgets-list mpce-recent-widgets-list">\n\t\t\t{{#recentWidgets}}\n\t\t\t\t<li class="mpce-widget-group-{{groupId}} mpce-widget-item-wrapper mpce-widget-id-{{id}}">\n\t\t\t\t\t<div class="mpce-widget-item" data-widget-id="{{id}}" data-widget-group-id="{{groupId}}" data-widget-title="{{name}}">\n\t\t\t\t\t\t<span class="mpce-widget-icon" style="background-image: url(\'{{icon}}\');"></span>\n\t\t\t\t\t\t<span class="mpce-widget-name motopress-default motopress-no-color-text">{{name}}</span>\n\t\t\t\t\t</div>\n\t\t\t\t</li>\n\t\t\t{{/recentWidgets}}\n\t\t</ul>\n\t{{/recentWidgets.length}}');
            (function ($) {
                CE.Panels.AbstractDialog.extend('CE.Panels.WidgetsDialog', {
                    myThis: null,
                    NAME: 'widgets'
                }, {
                    spanToInsert: null,
                    library: {},
                    recentWidgets: null,
                    tabs: {
                        common: null,
                        library: null
                    },
                    tabsEl: null,
                    init: function init(el, args) {
                        this._super();
                        CE.Panels.WidgetsDialog.myThis = this;
                        this.prepareWidgets(args.library, args.grid);
                        this.recentWidgets = new CE.RecentWidgets([]);
                        this.buildDialogElement();
                        el.dialog({
                            autoOpen: false,
                            resizable: false,
                            dialogClass: 'motopress-dialog',
                            title: 'Insert Widget',
                            width: 600 + 32,
                            height: 400
                        });
                        this.tabsEl = el.find('#mpce-widgets-tab');
                        this.tabsEl.tabs();
                        this.tabs = {
                            common: new CE.Panels.WidgetsDialog.CommonTab(this.element.find('#mpce-widgets-common-tab'), { parentDialog: this }),
                            library: new CE.Panels.WidgetsDialog.LibraryTab(this.element.find('#mpce-widgets-library-tab'), { parentDialog: this })
                        };
                    },
                    prepareWidgets: function prepareWidgets(library, gridLibrary) {
                        var $this = this;
                        this.library = {};
                        var innerSpanSlug = gridLibrary.span.inner;
                        $.each(library, function (groupName, groupDetails) {
                            var isGridGroup = IframeCE.Shortcode.isGrid(groupDetails.id);
                            if (!groupDetails.show) {
                                if (!isGridGroup) {
                                    return true;
                                }
                            }
                            $this.library[groupName] = $.extend({}, groupDetails);
                            $this.library[groupName]['objects'] = {};
                            $.each(groupDetails.objects, function (objectKey, objectAtts) {
                                if (!objectAtts.show) {
                                    return true;
                                }
                                if (isGridGroup && objectKey !== innerSpanSlug) {
                                    return true;
                                }
                                $this.library[groupName]['objects'][objectKey] = $.extend({ groupId: groupDetails['id'] }, objectAtts);
                            });
                        });
                        if ($this.library.mp_grid.objects.hasOwnProperty(innerSpanSlug)) {
                            $this.library.mp_grid.objects[innerSpanSlug].name = 'Column';
                        }
                    },
                    buildDialogElement: function buildDialogElement() {
                        var viewData = new can.Map({
                            tabs: [
                                {
                                    id: 'mpce-widgets-common-tab',
                                    title: 'Widgets',
                                    content: ''
                                },
                                {
                                    id: 'mpce-widgets-library-tab',
                                    title: 'Library',
                                    content: ''
                                }
                            ]
                        });
                        this.element.html(can.view('widgetsDialog', viewData));
                    },
                    open: function open(spanToInsert, position) {
                        this.spanToInsert = spanToInsert;
                        this.reset();
                        this.element.dialog('option', 'position', {
                            my: 'center',
                            at: 'center',
                            of: window.top
                        });
                        this._super();
                        this.resetAfterOpen();
                    },
                    preparePositionNearSpan: function preparePositionNearSpan() {
                        var dialogVerticalOffset = 42 - CE.Iframe.$window.scrollTop();
                        var position = {
                            my: 'center bottom',
                            at: 'center top' + (dialogVerticalOffset > 0 ? '+' : '') + dialogVerticalOffset,
                            of: this.spanToInsert,
                            within: window
                        };
                        if (this.isOpen()) {
                            position.using = function (positionHash, positionDetails) {
                                positionDetails.element.element.animate(positionHash);
                            };
                        }
                        return position;
                    },
                    close: function close() {
                        this.element.dialog('close');
                        this.spanToInsert = null;
                    },
                    reset: function reset() {
                        $.each(this.tabs, function (index, tab) {
                            tab.reset();
                        });    
                    },
                    resetAfterOpen: function resetAfterOpen() {
                        $.each(this.tabs, function (index, tab) {
                            tab.resetAfterOpen();
                        });
                    }
                });
            }(jQuery));
            (function ($) {
                can.Control.extend('CE.Panels.WidgetsDialog.BaseTab', {}, {
                    parentDialog: null,
                    widgetsList: null,
                    searchInput: null,
                    init: function init(el, args) {
                        this.parentDialog = args.parentDialog;
                        this._updateView();
                    },
                    _updateView: function _updateView() {
                        this._initElements();
                    },
                    _initElements: function _initElements() {
                        this.widgetsList = this._findWidgetList();
                        this.searchInput = this.element.find('.mpce-widgets-search');
                    },
                    _findWidgetList: function _findWidgetList() {
                        return this.element;
                    },
                    _getTitlePart: function _getTitlePart() {
                        return this.searchInput.val().toUpperCase();
                    },
                    hideWidget: function hideWidget($widget) {
                        $widget.addClass('mpce-widget-item-wrapper-hidden');
                    },
                    showWidget: function showWidget($widget) {
                        $widget.removeClass('mpce-widget-item-wrapper-hidden');
                    },
                    reset: function reset() {
                        this.searchInput.val('');
                        this.filterWidgets();
                    },
                    resetAfterOpen: function resetAfterOpen() {
                        this.element.scrollTop(0);
                    },
                    filterWidgets: function filterWidgets() {
                        var allWidgets = this.widgetsList.find('>li>ul>li');
                        var groupsLi = this.widgetsList.children('li');
                        this.hideWidget(allWidgets);
                        this.showWidget(allWidgets.filter(this.proxy(function (index, widgetLi) {
                            var widgetEl = $(widgetLi).children();
                            return this._isShowWidget(widgetEl);
                        })));
                        var hiddenGroups = groupsLi.filter(function (index, li) {
                            return !$(li).find('>ul>li:not(.mpce-widget-item-wrapper-hidden)').length;
                        });
                        hiddenGroups.hide();
                        groupsLi.not(hiddenGroups).show();
                    },
                    _isShowWidget: function _isShowWidget(widgetEl) {
                        var titlePart = this._getTitlePart();
                        return !titlePart || widgetEl.attr('data-widget-title').toUpperCase().indexOf(titlePart) !== -1;
                    },
                    '.mpce-widgets-search input': function mpceWidgetsSearchInput(el, e) {
                        this.filterWidgets();
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Panels.WidgetsDialog.BaseTab('CE.Panels.WidgetsDialog.CommonTab', {}, {
                    categorySelect: null,
                    searchInput: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.updateRecentWidgets();
                    },
                    _initElements: function _initElements() {
                        this._super();
                        this.recentWidgetsHolder = this.element.find('#mpce-recent-widgets');
                        this.categorySelect = this.element.find('.mpce-widgets-category-filter');
                    },
                    _updateView: function _updateView() {
                        this.element.html(can.view('widgetsDialogCommonTab', new can.Map({
                            library: this.parentDialog.library,
                            recentWidgets: this.parentDialog.recentWidgets.getList(),
                            translations: {
                                all: 'All Categories',
                                search: 'Search'
                            }
                        })));
                        this._super();
                    },
                    _findWidgetList: function _findWidgetList() {
                        return this.element.find('.mpce-regular-widgets-list');
                    },
                    getRecentWidgetList: function getRecentWidgetList() {
                        return this.element.find('.mpce-recent-widgets-list');
                    },
                    findVisibleWidgets: function findVisibleWidgets($container) {
                        return $container.find('.mpce-widget-item-wrapper:not(.mpce-widget-item-wrapper-hidden)');
                    },
                    reset: function reset() {
                        this.categorySelect.val('');
                        this._super();
                    },
                    resetAfterOpen: function resetAfterOpen() {
                        this._super();
                        this.searchInput.focus();
                    },
                    filterWidgets: function filterWidgets() {
                        this._super();
                        var $recentGrid = this.getRecentWidgetList().find('.mpce-widget-group-mp_grid');
                        var spanNestingLvl = MP.Utils.detectSpanNestingLvl(this.parentDialog.spanToInsert);
                        if (spanNestingLvl > 1) {
                            this.hideWidget($recentGrid);
                        } else {
                            this.showWidget($recentGrid);
                        }
                        var visibleRecentCount = this.findVisibleWidgets(this.getRecentWidgetList()).length;
                        if (this._getTitlePart() || this._getCategory() || !visibleRecentCount) {
                            this.recentWidgetsHolder.hide();
                        } else {
                            this.recentWidgetsHolder.show();
                        }
                    },
                    _isShowWidget: function _isShowWidget(widgetEl) {
                        var isShow = this._super(widgetEl);
                        if (isShow) {
                            var category = this._getCategory();
                            var groupId = widgetEl.attr('data-widget-group-id');
                            isShow = '' === category || groupId === category;
                            if (MP.Utils.detectSpanNestingLvl(this.parentDialog.spanToInsert) > 1 && groupId === 'mp_grid') {
                                isShow = false;
                            }
                        }
                        return isShow;
                    },
                    _getCategory: function _getCategory() {
                        return this.categorySelect.val();
                    },
                    updateRecentWidgets: function updateRecentWidgets() {
                        this.recentWidgetsHolder.html(can.view('recentWidgets', {
                            recentWidgets: this.parentDialog.recentWidgets.getList(),
                            translations: { recentWidgetsTitle: 'Recent Widgets' }
                        }));
                    },
                    insertInnerRow: function insertInnerRow() {
                        IframeCE.LayoutManager.insertRow([
                            6,
                            6
                        ], this.parentDialog.spanToInsert, 'in', 2);
                    },
                    '.mpce-widgets-category-filter change': function mpceWidgetsCategoryFilterChange(el, e) {
                        this.filterWidgets();
                    },
                    '#mpce-regular-widgets .mpce-widget-item click': function mpceRegularWidgetsMpceWidgetItemClick(el, e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var id = el.attr('data-widget-id');
                        var groupId = el.attr('data-widget-group-id');
                        if (IframeCE.Shortcode.isGrid(groupId)) {
                            this.insertInnerRow();
                        } else {
                            var block = CE.Iframe.$('<div />');
                            CE.WidgetsLibrary.myThis.setAttrs(block, groupId, CE.WidgetsLibrary.myThis.getObject(groupId, id));
                            IframeCE.LayoutManager.insertWidgetToClmn(block, this.parentDialog.spanToInsert, true, true);
                            IframeCE.Selectable.myThis.showWidgetMainEditTool(block);
                        }
                        this.parentDialog.close();
                        this.parentDialog.recentWidgets.add($.extend({ groupId: groupId }, this.parentDialog.library[groupId].objects[id]));
                        this.updateRecentWidgets();
                    },
                    '#mpce-recent-widgets .mpce-widget-item click': function mpceRecentWidgetsMpceWidgetItemClick(el, e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var id = el.attr('data-widget-id');
                        var groupId = el.attr('data-widget-group-id');
                        if (IframeCE.Shortcode.isGrid(groupId)) {
                            this.insertInnerRow();
                        } else {
                            var block = CE.Iframe.$('<div />');
                            CE.WidgetsLibrary.myThis.setAttrs(block, groupId, CE.WidgetsLibrary.myThis.getObject(groupId, id));
                            IframeCE.LayoutManager.insertWidgetToClmn(block, this.parentDialog.spanToInsert, true, true);
                        }
                        this.parentDialog.close();
                    }
                });
            }(jQuery));
            (function ($) {
                var _CE$Panels$WidgetsDia;
                CE.Panels.WidgetsDialog.BaseTab('CE.Panels.WidgetsDialog.LibraryTab', {}, (_CE$Panels$WidgetsDia = {
                    categorySelect: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        parent.CE.EventDispatcher.Dispatcher.addListener(CE.Libraries.Events.WidgetTemplateSaved.NAME, this.proxy('_updateView'));
                    },
                    _initElements: function _initElements() {
                        this._super();
                        this.categorySelect = this.element.find('.mpce-widgets-category-filter');
                    },
                    _updateView: function _updateView() {
                        this.element.html(can.view('widgetsDialogLibraryTab', new can.Map({
                            library: this._prepareLibrary(),
                            templatesExists: CE.ObjectTemplatesLibrary.myThis.getWidgets().length !== 0,
                            translations: {
                                all: 'All Categories',
                                uncategorized: 'Uncategorized',
                                search: 'Search',
                                noTemplates: 'There are no saved templates yet.'
                            }
                        })));
                        this._super();
                    },
                    _prepareLibrary: function _prepareLibrary() {
                        var library = {};
                        $.each(CE.ObjectTemplatesLibrary.myThis.getWidgetCategories(), function (index, category) {
                            library[index] = {
                                id: index,
                                name: category,
                                objects: []
                            };
                        });
                        $.each(CE.ObjectTemplatesLibrary.myThis.getWidgets().attr(), function (index, widget) {
                            var catId = widget['category'];
                            var widgetCategory = library[catId] ? library[catId] : library[0];
                            widgetCategory.objects.push(widget);
                        });
                        return library;
                    },
                    _findWidgetList: function _findWidgetList() {
                        return this.element.find('.mpce-library-widgets-list');
                    },
                    _isShowWidget: function _isShowWidget(widgetEl) {
                        var isShow = this._super(widgetEl);
                        return isShow;
                    },
                    reset: function reset() {
                        this.categorySelect.val('');
                        this._super();
                    },
                    _getCategory: function _getCategory() {
                        return this.categorySelect.val();
                    }
                }, _defineProperty(_CE$Panels$WidgetsDia, '_isShowWidget', function _isShowWidget(widgetEl) {
                    var isShow = this._super(widgetEl);
                    if (isShow) {
                        var selectedCategory = this._getCategory();
                        var categoryId = widgetEl.attr('data-widget-category-id');
                        isShow = '' === selectedCategory || categoryId === selectedCategory;
                        if (MP.Utils.detectSpanNestingLvl(this.parentDialog.spanToInsert) > 1 && categoryId === 'mp_grid') {
                            isShow = false;
                        }
                    }
                    return isShow;
                }), _defineProperty(_CE$Panels$WidgetsDia, '.mpce-widgets-category-filter change', function mpceWidgetsCategoryFilterChange(el, e) {
                    this.filterWidgets();
                }), _defineProperty(_CE$Panels$WidgetsDia, '#mpce-library-widgets .mpce-widget-item click', function mpceLibraryWidgetsMpceWidgetItemClick(el, e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var id = el.attr('data-widget-template-id');
                    var widgetTemplate = CE.ObjectTemplatesLibrary.myThis.getWidgetById(id);
                    var clmn = this.parentDialog.spanToInsert;
                    var clmnEdge = parent.MP.Utils.getEdgeSpan(clmn);
                    clmnEdge.children('.motopress-block-content').addClass(IframeCE.Shortcode.preloaderClass).children('.motopress-filler-content').addClass('motopress-hide');
                    IframeCE.LayoutManager.insertShortcodeString(widgetTemplate.content, new MP.EntityType(IframeCE.Grid.ENTITIES.WIDGET), clmn, 'in').done(this.proxy(function (spanEl) {
                        IframeCE.Selectable.myThis.showWidgetMainEditTool(spanEl);
                        var widgetEl = clmn.children('.motopress-block-content').children();
                        widgetEl.trigger('render');
                    })).always(this.proxy(function () {
                        clmnEdge.children('.motopress-block-content').removeClass(IframeCE.Shortcode.preloaderClass);
                    }));
                    this.parentDialog.close();
                }), _CE$Panels$WidgetsDia));
            }(jQuery));
            (function ($) {
                can.Construct('CE.RecentWidgets', 
                {}, 
                {
                    maxSize: 5,
                    storage: [],
                    init: function init(items, maxSize) {
                        if (typeof maxSize !== 'undefined') {
                            this.maxSize = maxSize;
                        }
                        if (items.length > this.maxSize) {
                            items.splice(0, items.length - this.maxSize);
                        }
                        this.storage = items;
                    },
                    add: function add(widget) {
                        var widgetString = JSON.stringify(widget);
                        this.storage = this.storage.filter(function (storedWidget) {
                            return JSON.stringify(storedWidget) !== widgetString;
                        });
                        this.storage.unshift(widget);
                        if (this.storage.length > this.maxSize) {
                            this.storage.pop();
                        }
                    },
                    getList: function getList() {
                        return this.storage;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Ctrl = can.Control.extend({
                    bodyEl: $('body'),
                    processValue: function processValue(value, defaultValue, isNew) {
                        value = typeof isNew !== 'undefined' && isNew && typeof value === 'undefined' ? defaultValue : value;
                        if (!value)
                            value = '';
                        return String(value);
                    }
                }, {
                    name: null,
                    propLabel: null,
                    dependency: false,
                    disabled: false,
                    hided: false,
                    isPermanentHided: false,
                    customized: false,
                    shortcode: null,
                    shortcodeName: null,
                    shortcodeCtrl: null,
                    formCtrl: null,
                    init: function init(el, args) {
                        this.customized = false;
                        this.name = args.name;
                        this.propLabel = this.filterPropLabel(args.propLabel);
                        this.dependency = args.dependency;
                        this.disabled = args.disabled;
                        this.isPermanentHided = args.hasOwnProperty('isPermanentHided') && args.isPermanentHided;
                        this.formCtrl = args.formCtrl;
                        if (args.hasOwnProperty('innerForm')) {
                            this.innerForm = args.innerForm;
                        }
                        this.shortcode = args.shortcode;
                        this.shortcodeName = IframeCE.Shortcode.getShortcodeName(this.shortcode);
                        this.shortcodeGroup = IframeCE.Shortcode.getShortcodeGroup(this.shortcode);
                        this.shortcodeCtrl = args.shortcode.control(IframeCE.Controls);
                    },
                    afterInit: function afterInit() {
                        if (this.isPermanentHided) {
                            this.hide();
                        }
                    },
                    get: function get() {
                    },
                    set: function set() {
                    },
                    filterPropLabel: function filterPropLabel(label) {
                        label = $.trim(label);
                        if (label.lastIndexOf(':') === label.length - 1) {
                            label = label.substring(0, label.length - 1);
                        }
                        return label;
                    },
                    hide: function hide() {
                        this.element.closest('[data-motopress-parameter]').addClass('motopress-hide');
                        this.hided = true;
                    },
                    show: function show() {
                        if (!this.isPermanentHided) {
                            this.element.closest('[data-motopress-parameter]').removeClass('motopress-hide');
                            this.hided = false;
                        }
                    },
                    isHided: function isHided() {
                        return this.hided;
                    },
                    'change': function change(el, e, doSave, cbBefore) {
                        e.stopPropagation();
                        if (typeof doSave === 'undefined')
                            doSave = true;
                        CE.EventDispatcher.Dispatcher.clearPrevents();
                        if (can.isFunction(cbBefore)) {
                            this.proxy(cbBefore)();
                        }
                        this.hideDependencedControls();
                        if (doSave) {
                            this.formCtrl.changeProperty(this);
                        }
                    },
                    ' customize': function customize(el, e) {
                        if (this.customized) {
                            return false;
                        } else {
                            this.customized = true;
                        }
                    },
                    hideDependencedControls: function hideDependencedControls() {
                        var $this = this;
                        var dependencedCtrl;
                        $this.formCtrl.form.find('> [data-motopress-parameter] > .motopress-controls:not(".select2-container"), > [data-motopress-parameter] > :not(".motopress-property-legend, .motopress-property-label, .motopress-property-description, hr") > .motopress-controls').each(function () {
                            dependencedCtrl = $(this).control(CE.Ctrl);
                            if (dependencedCtrl.dependency) {
                                if ($this.name === dependencedCtrl.dependency.parameter) {
                                    if (dependencedCtrl.isShouldBeHiddenByDependency()) {
                                        dependencedCtrl.hide();
                                    } else {
                                        dependencedCtrl.show();
                                    }
                                    dependencedCtrl.hideDependencedControls();
                                }
                            }
                        });
                    },
                    isShouldBeHiddenByDependency: function isShouldBeHiddenByDependency() {
                        var isHide = false;
                        if (this.hasOwnProperty('dependency') && this.dependency.hasOwnProperty('parameter')) {
                            var dependencyCtrl = this.formCtrl.getCtrlByName(this.dependency.parameter);
                            var isValueCorrect = this.dependency.hasOwnProperty('value') ? $.isArray(this.dependency.value) ? $.inArray(dependencyCtrl.get(), this.dependency.value) !== -1 : dependencyCtrl.get() == this.dependency.value : true;
                            var isValueExcepted = this.dependency.hasOwnProperty('except') ? $.isArray(this.dependency.except) ? $.inArray(dependencyCtrl.get(), this.dependency.except) !== -1 : dependencyCtrl.get() == this.dependency.except : false;
                            if (!isValueCorrect || isValueExcepted) {
                                isHide = true;
                            } else if (dependencyCtrl.dependency) {
                                isHide = dependencyCtrl.isShouldBeHiddenByDependency();
                            }
                        }
                        return isHide;
                    }
                });
                CE.Ctrl('CE.CtrlInput', {}, {
                    get: function get() {
                        return this.element.val();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.element.val(value);
                    }
                });
                CE.Ctrl('CE.CtrlSpinner', { listensTo: ['customize'] }, {
                    spinning: false,
                    min: null,
                    max: null,
                    step: 1,
                    init: function init(el, args) {
                        this._super(el, args);
                        if (args.hasOwnProperty('min'))
                            this.min = args.min;
                        if (args.hasOwnProperty('max'))
                            this.max = args.max;
                        if (args.hasOwnProperty('step'))
                            this.step = args.step;
                    },
                    ' customize': function customize(el, e) {
                        if (this.customized)
                            return false;
                        else
                            this.customized = true;
                        var $this = this;
                        el.spinner({
                            disabled: this.disabled,
                            min: this.min,
                            max: this.max,
                            step: this.step,
                            stop: function stop(event, ui) {
                                if ($this.spinning) {
                                    el.trigger('change', $this.oldValue);
                                    $this.spinning = false;
                                    $this.oldValue = null;
                                }
                            },
                            spin: function spin(event, ui) {
                                if (!$this.spinning) {
                                    $this.spinning = true;
                                    $this.oldValue = $this.get();
                                }
                            }
                        });
                    },
                    'change': function change(el, e, oldValue) {
                        var value = this.get();
                        if (value !== null && oldValue !== value && value >= this.min && value <= this.max && (value / this.step).toFixed(12) % 1 === 0) {
                            CE.Ctrl.prototype.change.apply(this, [
                                el,
                                e
                            ]);
                        }
                    },
                    get: function get() {
                        return this.element.spinner('value');
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        if (typeof this.element.data('uiSpinner') !== 'undefined') {
                            this.element.spinner('value', value);
                        } else {
                            this.element.val(value);
                        }
                    }
                });
                CE.Ctrl('CE.CtrlDateTimePicker', { listensTo: ['customize'] }, {
                    returnMode: null,
                    displayMode: null,
                    input: null,
                    button: null,
                    changeTimeout: null,
                    showedValue: null,
                    dpChangePrevented: false,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.returnMode = args.returnMode;
                        this.displayMode = args.displayMode;
                        this.input = el.find('.motopress-property-datetime-picker-input');
                        this.wrapper = el.find('.motopress-property-datetime-picker-wrapper');
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.input.val(value);
                    },
                    get: function get() {
                        return this.input.val();
                    },
                    ' customize': function customize(el, e) {
                        if (this.customized) {
                            return false;
                        } else {
                            this.customized = true;
                        }
                        this._super(el, e);
                        var self = this;
                        this.input.on('change', this.proxy(function (e) {
                            this.dpChangePrevented = true;
                            this.element.trigger('change');
                        }));
                        this.wrapper.datetimepicker({
                            'format': this.returnMode,
                            'debug': false,
                            'icons': {
                                time: 'fa fa-clock-o',
                                date: 'fa fa-calendar',
                                up: 'fa fa-chevron-up',
                                down: 'fa fa-chevron-down',
                                previous: 'fa fa-chevron-left',
                                next: 'fa fa-chevron-right',
                                today: 'glyphicon glyphicon-screenshot',
                                clear: 'fa fa-trash-o'
                            }
                        }).on('dp.show', function (e) {
                            self.dpChangePrevented = false;
                            self.showedValue = self.get();
                        }).on('dp.hide', function (e) {
                            if (!self.dpChangePrevented && self.showedValue != self.get()) {
                                clearTimeout(self.changeTimeout);
                                self.element.trigger('change');
                            }
                        }).on('dp.change', function (e) {
                            if (self.dpChangePrevented)
                                return;
                            clearTimeout(self.changeTimeout);
                            self.changeTimeout = setTimeout(function () {
                                CE.Ctrl.prototype.change.apply(self, [
                                    self.element,
                                    e,
                                    true,
                                    self.preventDispatcher
                                ]);
                            }, 500);
                        });
                    },
                    preventDispatcher: function preventDispatcher() {
                        CE.EventDispatcher.Dispatcher.prevent(IframeCE.SceneEvents.EntitySettingsChanged.NAME);
                    }
                });
                CE.Ctrl('CE.CtrlLink', {}, {
                    get: function get() {
                        return $('.motopress-property-link-input', this.element).val();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        $('.motopress-property-link-input', this.element).val(value);
                    },
                    '.motopress-property-button-default click': function motopressPropertyButtonDefaultClick(el) {
                        var input = $('.motopress-property-link-input', this.element);
                        IframeCE.Link.myThis.open(input, {
                            title: false,
                            target: false
                        }, function (atts) {
                            if (input.val == atts.href)
                                return;
                            input.val(atts.href);
                            input.trigger('change');
                        }, IframeCE.Link.myThis.wpCancelButtonHandler);
                        return false;
                    }
                });
                CE.Ctrl('CE.CtrlTextarea', {}, {
                    get: function get() {
                        return this.element.val();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.element.val(value);
                    }
                });
                CE.Ctrl('CE.CtrlTextarea64', {}, {
                    get: function get() {
                        return MP.Utils.base64_encode(this.element.val());
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.element.val(MP.Utils.base64_decode(value));
                    }
                });
                CE.Ctrl('CE.CtrlTextareaTable', {}, {
                    get: function get() {
                        var value = MP.Utils.nl2br(this.element.val());
                        return value;
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        value = value.replace(new RegExp('^<p>'), '').replace(new RegExp('</p>$'), '');
                        value = MP.Utils.br2nl(value);
                        this.element.val(value);
                    }
                });
                CE.Ctrl('CE.CtrlTextareaTinymce', {}, {
                    textarea: null,
                    currentShortcode: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.textarea = el.children('.motopress-property-textarea');
                        this.currentShortcode = args.currentShortcode;
                    },
                    get: function get() {
                        return this.textarea.val();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.textarea.val(value);
                    },
                    '.motopress-property-button-default click': function motopressPropertyButtonDefaultClick(el, e) {
                        var editor = CE.CodeModal.myThis.editor;
                        CE.CodeModal.currentShortcode = this.currentShortcode;
                        CE.CodeModal.currentTextareaTinymce = this.element;
                        var content = this.get();
                        if (content.length) {
                            if (editor !== null)
                                editor.setContent(content, { format: 'html' });
                            CE.CodeModal.myThis.content.val(content);
                        }
                        CE.CodeModal.myThis.saveHandler = this.saveHandler;
                        CE.CodeModal.myThis.element.data('modal').closeDialog = false;
                        CE.CodeModal.myThis.element.mpmodal('show');
                    },
                    saveHandler: function saveHandler(e) {
                        var $this = CE.CodeModal.myThis;
                        $this.switchVisual();
                        var controller = CE.CodeModal.currentTextareaTinymce.control(CE.CtrlTextareaTinymce);
                        var content = $this.editor.getContent({ format: 'html' });
                        if (content.length) {
                            controller.set(content);
                            CE.CodeModal.currentTextareaTinymce.trigger('change');
                        }
                        $this.element.mpmodal('hide');
                    }
                });
                CE.Ctrl('CE.CtrlAudio', {}, {
                    init: function init(el, args) {
                        this._super(el, args);
                        $('.motopress-property-audio-title', this.element).val(args.audioTitle).attr('disabled', 'disabled');
                    },
                    get: function get() {
                        var audioTitle = $('.motopress-property-audio-id', this.element).val();
                        return audioTitle;
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        $('.motopress-property-audio-id', this.element).val(value);
                    },
                    '.motopress-property-button-default click': function motopressPropertyButtonDefaultClick(el) {
                        CE.WPAudio.myThis.frame.open(this);
                    }
                });
                CE.Ctrl('CE.CtrlMediaVideo', {}, {
                    init: function init(el, args) {
                        this._super(el, args);
                    },
                    get: function get() {
                        var videoUrl = $('.motopress-property-video-url', this.element).val();
                        return videoUrl;
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        $('.motopress-property-video-url', this.element).val(value);
                    },
                    '.motopress-property-button-default click': function motopressPropertyButtonDefaultClick(el) {
                        this.element.attr('data-motopress-open-video-lib', 1);
                        CE.WPVideo.myThis.frame.open(this);
                    }
                });
                CE.Ctrl('CE.CtrlMedia', { listensTo: ['dialogOpen'] }, {
                    init: function init(el, args) {
                        this._super(el, args);
                        if (args.returnMode == 'id') {
                            var massOfDetails = CE.Iframe.myThis.wpAttachmentDetails;
                            for (var key in massOfDetails) {
                                if (args.value == key) {
                                    $('.motopress-property-media-id', this.element).val(key);
                                    $('.motopress-property-media', this.element).val(massOfDetails[key]).attr('disabled', 'disabled');
                                }
                            }
                        } else {
                            $('.motopress-property-media', this.element).val(args.value);
                        }
                    },
                    get: function get() {
                        if (this.options.returnMode == 'id') {
                            var mediaId = $('.motopress-property-media-id', this.element).val();
                            return mediaId;
                        } else {
                            var mediaSrc = $('.motopress-property-media', this.element).val();
                            return mediaSrc;
                        }
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        if (this.options.returnMode == 'id') {
                            $('.motopress-property-media-id', this.element).val(value);
                        } else {
                            $('.motopress-property-media', this.element).val(value);
                        }
                    },
                    '.motopress-property-button-default click': function motopressPropertyButtonDefaultClick(el) {
                        this.element.attr('data-motopress-open-media-lib', 1);
                        CE.WPMedia.myThis.frame.open(this);
                    }
                });
                CE.Ctrl('CE.CtrlImageGeneral', { listensTo: ['dialogOpen'] }, {
                    autoOpen: false,
                    init: function init(el, args) {
                        this._super(el, args);
                        if (args.isNew && args.autoOpen === 'true')
                            this.autoOpen = true;
                    }
                });
                CE.CtrlImageGeneral('CE.CtrlImage', {
                    thumbnail: motopress.pluginDirUrl + 'images/ce/imageThumbnail.png' + motopress.pluginVersionParam,
                    storedThumbs: {}
                }, {
                    returnMode: 'id',
                    pseudoRender: false,
                    value: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.pseudoRender = args.pseudoRender;
                        if (args.hasOwnProperty('returnMode')) {
                            this.returnMode = args.returnMode;
                        }
                    },
                    ' dialogOpen': function dialogOpen(el) {
                        if (this.autoOpen) {
                            el.find('.motopress-thumbnail-crop').trigger('click');
                            this.autoOpen = false;
                        }
                    },
                    get: function get() {
                        return this.value !== null ? this.value : '';
                    },
                    set: function set(value, defaultValue, isNew) {
                        if ($.isPlainObject(value) && value.hasOwnProperty('id') && value.hasOwnProperty('src') && value.hasOwnProperty('full')) {
                            this.value = this.returnMode === 'id' ? value.id : value.full;
                            this.setThumbnail(value.src, defaultValue, isNew);
                            if (this.pseudoRender) {
                                this.setFullSrc(value.full);
                                this.shortcode.css('background-image', 'url(\'' + value.full + '\')');
                            }
                        } else {
                            if (this.returnMode === 'id') {
                                if ($.isNumeric(value)) {
                                    var thumbID = value;
                                    var thumbSrc = this.getThumb(thumbID);
                                    this.value = value;
                                    if (thumbSrc) {
                                        this.setThumbnail(thumbSrc, defaultValue, isNew);
                                    } else {
                                        var ctrl = this;
                                        this.showPreloader();
                                        $.ajax({
                                            url: motopress.ajaxUrl,
                                            type: 'POST',
                                            dataType: 'text',
                                            data: {
                                                action: 'motopress_ce_get_attachment_thumbnail',
                                                nonce: motopressCE.nonces.motopress_ce_get_attachment_thumbnail,
                                                postID: motopressCE.postID,
                                                id: thumbID
                                            },
                                            success: function success(data) {
                                                data = $.parseJSON(data);
                                                ctrl.storeThumb(thumbID, data.medium);
                                                ctrl.setThumbnail(data.medium, defaultValue, isNew);
                                                ctrl.setFullSrc(data.full);
                                                ctrl.hidePreloader();
                                            },
                                            error: function error(jqXHR) {
                                                var error = $.parseJSON(jqXHR.responseText);
                                                if (error.debug) {
                                                    console.log(error.message);
                                                } else {
                                                    MP.Flash.setFlash(error.message, 'error');
                                                    MP.Flash.showMessage();
                                                }
                                                ctrl.removeValue();
                                                ctrl.hidePreloader();
                                            }
                                        });
                                    }
                                } else {
                                    this.removeValue();
                                }
                            }
                            if (this.returnMode === 'src' && typeof value === 'string') {
                                if (value === '') {
                                    this.removeValue();
                                } else {
                                    this.value = value;
                                    this.setThumbnail(this.value, defaultValue, isNew);
                                    this.setFullSrc(this.value);
                                    this.showTools();
                                }
                            }
                        }
                    },
                    storeThumb: function storeThumb(id, src) {
                        return this.constructor.storedThumbs[id] = src;
                    },
                    getThumb: function getThumb(id) {
                        return this.constructor.storedThumbs[id];
                    },
                    setThumbnail: function setThumbnail(value, defaultValue, isNew) {
                        var val = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.element.find('.motopress-thumbnail').attr('src', val);
                        this.showTools();
                    },
                    setFullSrc: function setFullSrc(value) {
                        this.element.find('.motopress-thumbnail').attr('data-full-src', value);
                    },
                    '.motopress-thumbnail-crop click': function motopressThumbnailCropClick(el, e) {
                        if (!el.hasClass(IframeCE.Shortcode.preloaderClass)) {
                            this.element.attr('data-motopress-open-img-lib', 1);
                            CE.ImageLibrary.myThis.frame.open();
                        }
                    },
                    '.motopress-icon-trash click': function motopressIconTrashClick() {
                        this.removeValue();
                        this.element.trigger('change');
                    },
                    removeValue: function removeValue() {
                        this.value = null;
                        this.element.find('.motopress-thumbnail').attr('src', this.constructor.thumbnail);
                        this.hideTools();
                        if (this.pseudoRender) {
                            this.shortcode.css('background-image', '');
                            this.element.find('.motopress-thumbnail').removeAttr('data-full-src');
                        }
                    },
                    showTools: function showTools() {
                        this.element.children('.motopress-image-tools').show();
                    },
                    hideTools: function hideTools() {
                        this.element.children('.motopress-image-tools').hide();
                    },
                    showPreloader: function showPreloader() {
                        this.element.children('.motopress-thumbnail-crop').addClass(IframeCE.Shortcode.preloaderClass).children('.motopress-thumbnail').css('visibility', 'hidden');
                        this.hideTools();
                    },
                    hidePreloader: function hidePreloader() {
                        var thumbnailCrop = this.element.children('.motopress-thumbnail-crop');
                        var thumbnail = thumbnailCrop.children('.motopress-thumbnail');
                        thumbnailCrop.removeClass(IframeCE.Shortcode.preloaderClass);
                        thumbnail.css('visibility', 'visible');
                        if (thumbnail.attr('src') !== this.constructor.thumbnail) {
                            this.showTools();
                        }
                    }
                });
                CE.CtrlImageGeneral('CE.CtrlImageSlider', {}, {
                    ids: null,
                    init: function init(el, args) {
                        this._super(el, args);
                    },
                    ' dialogOpen': function dialogOpen(el) {
                        if (this.autoOpen) {
                            el.trigger('click');
                            this.autoOpen = false;
                        }
                    },
                    get: function get() {
                        return this.ids !== null ? this.ids : '';
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        var valueType = _typeof(value);
                        if (valueType === 'object') {
                            var idsStr = '';
                            var idsLen = value.length;
                            for (var i = 0; i < idsLen; i++) {
                                if (i < idsLen - 1)
                                    idsStr += value[i] + ',';
                                else
                                    idsStr += value[i];
                            }
                            this.ids = idsStr;
                        } else if (valueType === 'number' || valueType === 'string') {
                            this.ids = $.trim(value);
                        }
                    },
                    getArray: function getArray() {
                        var idsArr = [];
                        if (this.ids) {
                            idsArr = this.ids.split(',');
                            var id;
                            for (var i = 0; i < idsArr.length; i++) {
                                id = parseInt(idsArr[i], 10);
                                if (id)
                                    idsArr[i] = id;
                                else
                                    delete idsArr[i];
                            }
                        }
                        return idsArr;
                    },
                    'click': function click(el) {
                        if (!el.hasClass(IframeCE.Shortcode.preloaderClass)) {
                            CE.WPGallery.myThis.open(this);
                        }
                    }
                });
                CE.Ctrl('CE.CtrlVideo', {}, {
                    init: function init(el, args) {
                        this._super(el, args);
                        this.element.on('paste', function () {
                            var t = setTimeout(function () {
                                el.blur();
                                clearTimeout(t);
                            }, 0);
                        });
                    },
                    get: function get() {
                        return this.element.val();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.element.val(value);
                    }
                });
                CE.Ctrl('CE.CtrlCheckbox', {}, {
                    get: function get() {
                        return this.element.is(':checked').toString();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        value === 'true' ? this.element.attr('checked', 'checked') : this.element.removeAttr('checked');
                    }
                });
                CE.CtrlCheckbox('CE.CtrlGroupCheckbox', {}, {
                    'change': function change(el, e) {
                        if (el.is(':checked')) {
                            el.closest('.motopress-property-group-accordion-item').siblings().find('.motopress-property-group-accordion-item-content > [data-motopress-parameter="' + this.name + '"] > .motopress-controls').each(function () {
                                var ctrl = $(this).control(CE.Ctrl);
                                if (ctrl.get() === 'true') {
                                    ctrl.set('false');
                                    CE.Ctrl.prototype.change.apply(ctrl, [
                                        $(this),
                                        e
                                    ]);
                                }
                            });
                        }
                        CE.Ctrl.prototype.change.apply(this, [
                            el,
                            e
                        ]);
                    }
                });
                CE.Ctrl('CE.CtrlSelect', {
                    listensTo: ['customize'],
                    init: function init() {
                        CE.Ctrl.bodyEl.on('click', '.motopress-property-select:not(.open) > .dropdown-toggle', function () {
                            var menu = $(this).next();
                            var lastIndex = menu.find('ul > li').length;
                            lastIndex = lastIndex >= 6 ? 5 : lastIndex - 1;
                            menu.find('li:eq(' + lastIndex + ') > a').focus();
                            menu.find('ul > li > a.motopress-dropdown-selected').focus();
                        });
                    },
                    setSelected: function setSelected(el) {
                        var options = el.find('option');
                        var selectedIndex = options.index(options.filter(':selected'));
                        var customOptions = el.next().find('.dropdown-menu > ul > li');
                        customOptions.children('a.motopress-dropdown-selected').removeClass('motopress-dropdown-selected');
                        customOptions.filter('[rel="' + selectedIndex + '"]').children('a').addClass('motopress-dropdown-selected');
                    }
                }, {
                    get: function get() {
                        return this.element.val();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.element.find('[value="' + value + '"]').attr('selected', 'selected');
                    },
                    'change': function change(el, e) {
                        this._super(el, e);
                        CE.CtrlSelect.setSelected(el);
                    },
                    refresh: function refresh() {
                        this.element.selectpicker('refresh');
                        this.clearSelectpicker();
                        CE.CtrlSelect.setSelected(this.element);
                    },
                    clearSelectpicker: function clearSelectpicker() {
                        var customSelect = this.element.next();
                        customSelect.removeClass('ce_ctrl_select motopress-controls');
                        var dropdownToogle = customSelect.children('.dropdown-toggle');
                        dropdownToogle.html(dropdownToogle.html().replace(/&nbsp;/g, ''));
                    },
                    ' customize': function customize(el) {
                        if (typeof el.data('selectpicker') !== 'undefined') {
                            el.next().remove();
                            $.removeData(el[0], 'selectpicker');
                        }
                        el.selectpicker({ size: 6 });
                        this.clearSelectpicker();
                        CE.CtrlSelect.setSelected(el);
                    },
                    updateOptions: function updateOptions(list, selected) {
                        var options = CE.CtrlTemplates.generateOptions(list, selected);
                        this.element.html(options);
                        this.refresh();
                    }
                });
                CE.Ctrl('CE.CtrlSelectMultiple', { listensTo: ['customize'] }, {
                    init: function init(el, args) {
                        this._super(el, args);
                    },
                    get: function get() {
                        var data = this.element.select2('data');
                        var result = '';
                        data.forEach(function (el, index) {
                            result += result !== '' ? ',' + el.id : el.id;
                        });
                        return result;
                    },
                    set: function set(value, defaultValue, isNew) {
                        var self = this;
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        var values = value.split(',');
                        this.element.find('option').removeAttr('selected');
                        $.each(values, function (index, value) {
                            self.element.find('option[value="' + value + '"]').attr('selected', 'selected');
                        });
                    },
                    ' customize': function customize(el, args) {
                        el.select2({
                            separator: ',',
                            closeOnSelect: false,
                            'containerCssClass': 'motopress-select2',
                            'dropdownCssClass': 'motopress-select2-dropdown motopress-multiple-select-control-dropdown',
                            'adaptContainerCssClass': function adaptContainerCssClass(clazz) {
                                if (clazz !== 'motopress-controls')
                                    return clazz;
                            }
                        });
                    }
                });
                CE.Ctrl('CE.CtrlSelect2', { listensTo: ['customize'] }, {
                    isPreview: false,
                    previewClass: null,
                    unsetClasses: [],
                    data: null,
                    select2DropdownClass: 'motopress-property-select2-custom-class',
                    uniqueDropdownClass: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        var object = CE.WidgetsLibrary.myThis.getObject(this.options.formCtrl.shortcodeGroup, this.options.formCtrl.shortcodeName);
                        this.options.style = object.styles['mp_style_classes'];
                        this.uniqueDropdownClass = MP.Utils.uniqid('motopress-select2-dropdown-');
                    },
                    get: function get() {
                        var self = this;
                        var data = this.element.select2('data');
                        var result = '';
                        if (this.isPreview) {
                            data = data.filter(function (el) {
                                return $.inArray(el.id, self.unsetClasses) === -1;
                            });
                        }
                        data.forEach(function (el, index) {
                            if (!el.hasOwnProperty('locked') && el.locked !== true) {
                                result += result !== '' ? ' ' + el.id : el.id;
                            }
                        });
                        if (this.isPreview) {
                            result += result !== '' ? ' ' + this.previewClass : this.previewClass;
                        }
                        return result;
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        if (!$.isEmptyObject(this.options.style.basic)) {
                            var basicClasses = this.getBasicClassesString();
                            value = value ? basicClasses + ' ' + value : basicClasses;
                        }
                        this.options.value = value;
                        this.element.val(value);
                    },
                    ' customize': function customize(el, args) {
                        if (this.customized) {
                            return false;
                        } else {
                            this.customized = true;
                        }
                        var self = this;
                        this.data = this.getClassesList();
                        el.select2({
                            'multiple': true,
                            separator: ' ',
                            closeOnSelect: false,
                            createSearchChoice: function createSearchChoice(term, data) {
                                var allowedSymbolsRegExp = /^[-_A-Za-z0-9]+$/;
                                if (allowedSymbolsRegExp.test(term)) {
                                    if ($(data).filter(function () {
                                            return !this.hasOwnProperty('id') ? $(this.children).filter(function () {
                                                return this.text.toUpperCase().localeCompare(term.toUpperCase()) === 0 || this.id.toUpperCase().localeCompare(term.toUpperCase()) === 0;
                                            }).length !== 0 : this.text.toUpperCase().localeCompare(term.toUpperCase()) === 0 || this.id.toUpperCase().localeCompare(term.toUpperCase()) === 0;
                                        }).length === 0) {
                                        return {
                                            id: term.toLowerCase(),
                                            text: term,
                                            custom: true
                                        };
                                    }
                                }
                            },
                            initSelection: function initSelection(element, callback) {
                                callback(self.valueToData(self.options.value.split(' ')));
                            },
                            'query': function query(_query) {
                                self.options.value = self.element.val();
                                var data = {};
                                var result;
                                data.results = [];
                                $.each(self.data, function (key, val) {
                                    if (val.hasOwnProperty('children')) {
                                        result = {
                                            text: val.text,
                                            children: []
                                        };
                                        result.children = $.makeArray(val.children.filter(function (el) {
                                            return (el.id.toUpperCase().indexOf(_query.term.toUpperCase()) >= 0 || el.text.toUpperCase().indexOf(_query.term.toUpperCase()) >= 0) && $.inArray(el.id, self.options.value.split(' ') === -1);
                                        }));
                                        if (result.children.length) {
                                            data.results.push(result);
                                        }
                                    } else {
                                        if ((val.id.toUpperCase().indexOf(_query.term.toUpperCase()) >= 0 || val.text.toUpperCase().indexOf(_query.term.toUpperCase()) >= 0) && $.inArray(val.id, self.options.value.split(' ')) === -1) {
                                            data.results.push(val);
                                        }
                                    }
                                });
                                _query.callback(data);
                            },
                            'formatNoMatches': function formatNoMatches() {
                                return localStorage.getItem('CEStyleClassesFormatNoMatches');
                            },
                            'formatResult': function formatResult(state, container) {
                                if (state.hasOwnProperty('children')) {
                                    return state.text;
                                } else {
                                    if (state.hasOwnProperty('disabled') && state.disabled) {
                                        container.attr('title', localStorage.getItem('CELiteTooltipText'));
                                    }
                                    var dataExternal = state.hasOwnProperty('external') && typeof state.external !== 'undefined' ? ' data-external="' + state.external + '"' : '';
                                    return '<i class="select2-preview-icon"' + dataExternal + ' data-value="' + state.id + '"></i>' + state.text;
                                }
                            },
                            'containerCssClass': 'motopress-select2',
                            'dropdownCssClass': 'motopress-select2-dropdown select2-control-dropdown ' + self.select2DropdownClass + ' ' + self.uniqueDropdownClass
                        });
                        el.on('select2-highlight', function (e) {
                            self.unsetClasses = self.getUnsetClasses(e.val);
                        });
                        $('.' + self.uniqueDropdownClass).on('mouseover', '.select2-preview-icon', function () {
                            var external = $(this).attr('data-external');
                            if (typeof external !== 'undefined' && $('[href="' + external + '"]').length === 0) {
                                var cssLink = $('<link />', {
                                    'rel': 'stylesheet',
                                    'type': 'text/css',
                                    'href': external
                                });
                                CE.Iframe.$('head').append(cssLink);
                            }
                            var val = $(this).attr('data-value');
                            self.unsetClasses = self.getUnsetClasses(val);
                            self.isPreview = true;
                            self.setPreviewClass(val);
                            CE.Ctrl.bodyEl.trigger('MPCEObjectStylePreviewOver', {
                                'objElement': self.shortcode,
                                'objName': self.shortcodeName
                            });
                        });
                        $('.' + self.uniqueDropdownClass).on('mouseleave', '.select2-preview-icon', function () {
                            self.isPreview = false;
                            self.unsetClasses = [];
                            self.setPreviewClass();
                            CE.Ctrl.bodyEl.trigger('MPCEObjectStylePreviewOut', {
                                'objElement': self.shortcode,
                                'objName': self.shortcodeName
                            });
                        });
                        el.on('select2-blur', function (e) {
                            self.isPreview = false;
                            self.unsetClasses = [];
                            self.setPreviewClass();
                        });
                        el.on('select2-close', function (e) {
                            self.isPreview = false;
                            self.unsetClasses = [];
                            self.setPreviewClass();
                        });
                        el.on('select2-selecting', function (e) {
                            self.isPreview = false;
                            if (e.object.hasOwnProperty('external') && $('[href="' + e.object.external + '"]').length === 0) {
                                var cssLink = $('<link />', {
                                    'rel': 'stylesheet',
                                    'type': 'text/css',
                                    'href': e.object.external
                                });
                                CE.Iframe.$('head').append(cssLink);
                            }
                            var oldValue = $(self.element).select2('val');
                            var value = self.excludeGroupValues(oldValue);
                            if (e.object.hasOwnProperty('custom') && e.object.custom === true) {
                                self.data.push({
                                    'id': e.object.id,
                                    'text': e.object.text,
                                    'custom': e.object.custom
                                });
                            }
                            self.element.select2('data', self.valueToData(value));
                        });
                        el.on('select2-loaded', function (e, items) {
                            $('.' + self.uniqueDropdownClass + '>.select2-results>li.select2-result.select2-selected').each(function () {
                                var $this = $(this);
                                if ($this.find('.select2-disabled:not(.select2-selected)').length) {
                                    $this.removeClass('select2-selected');
                                }
                            });
                        });
                        el.on('change', this.proxy(function () {
                            var shortcodeLabel = this.shortcode.control(IframeCE.Shortcode).shortcodeLabel;
                            CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.EntityStylesChanged.NAME, new IframeCE.SceneEvents.EntityStylesChanged(shortcodeLabel, '[Styles]'));
                        }));
                    },
                    'valueToData': function valueToData(value) {
                        var self = this;
                        var data = [];
                        $.each(value, function (index, val) {
                            $.each(self.data, function (el) {
                                if (self.data[el].hasOwnProperty('children')) {
                                    $.each(self.data[el].children, function (el2) {
                                        if (self.data[el].children[el2].id === val) {
                                            data.push(self.data[el].children[el2]);
                                        }
                                    });
                                } else {
                                    if (self.data[el].id === val) {
                                        data.push(self.data[el]);
                                    }
                                }
                            });
                        });
                        return data;
                    },
                    'excludeGroupValues': function excludeGroupValues(value) {
                        var self = this;
                        value = value.filter(function (el) {
                            return $.inArray(el, self.unsetClasses) === -1;
                        });
                        return value;
                    },
                    'setPreviewClass': function setPreviewClass(cls) {
                        this.previewClass = cls;
                        this.options.formCtrl.changeProperty(this);
                    },
                    'getUnsetClasses': function getUnsetClasses(clsName) {
                        var unsetClasses = [];
                        $.each(this.data, function (key, value) {
                            var flag = false;
                            if (value.hasOwnProperty('children') && (value.allowMultiple === undefined || value.allowMultiple === false)) {
                                $.each(value.children, function (key, value) {
                                    if (value.id === clsName) {
                                        flag = true;
                                    } else {
                                        unsetClasses.push(value.id);
                                    }
                                });
                                if (flag) {
                                    return false;
                                } else {
                                    unsetClasses = [];
                                }
                            }
                        });
                        return unsetClasses;
                    },
                    'getClassesList': function getClassesList() {
                        var predefinedClasses = this.getPredefinedClasses();
                        var selectedClasses = this.options.value !== '' ? this.options.value.split(' ') : [];
                        var globalPredefinedClasses = this.getGlobalPredefinedClasses();
                        var basicClasses = this.getBasicClasses();
                        var classes = basicClasses.concat(globalPredefinedClasses, predefinedClasses);
                        selectedClasses.forEach(function (el, index) {
                            if ($(classes).filter(function () {
                                    var res = false;
                                    if (this.hasOwnProperty('children')) {
                                        res = $(this.children).filter(function () {
                                            return this.hasOwnProperty('id') ? this.id.localeCompare(el) === 0 : false;
                                        }).length !== 0;
                                    } else {
                                        res = this.hasOwnProperty('id') ? this.id.localeCompare(el) === 0 : false;
                                    }
                                    return res;
                                }).length === 0) {
                                classes.push({
                                    id: el,
                                    text: el
                                });
                            }
                        });
                        return classes;
                    },
                    'getBasicClasses': function getBasicClasses() {
                        var basicClasses = [];
                        if (!$.isEmptyObject(this.options.style.basic)) {
                            if (this.options.style.basic.hasOwnProperty('class')) {
                                basicClasses.push({
                                    id: this.options.style.basic['class'],
                                    text: this.options.style.basic.label,
                                    locked: true
                                });
                            } else {
                                $.each(this.options.style.basic, function (key, value) {
                                    basicClasses.push({
                                        id: value['class'],
                                        text: value.label,
                                        locked: true
                                    });
                                });
                            }
                        }
                        return basicClasses;
                    },
                    'getBasicClassesString': function getBasicClassesString() {
                        var basicClasses = this.getBasicClasses();
                        var result = [];
                        $.each(basicClasses, function (key, val) {
                            result.push(val.id);
                        });
                        return result.join(' ');
                    },
                    'getPredefinedClasses': function getPredefinedClasses() {
                        var result = [];
                        if (!$.isEmptyObject(this.options.style.predefined)) {
                            var data = this.options.style.predefined;
                            for (var group in data) {
                                if (data[group].hasOwnProperty('values')) {
                                    var children = [];
                                    for (var el in data[group]['values']) {
                                        children.push({
                                            id: data[group]['values'][el]['class'],
                                            text: data[group]['values'][el].label,
                                            disabled: data[group]['values'][el].hasOwnProperty('disabled') && data[group]['values'][el]['disabled'],
                                            external: data[group]['values'][el]['external']
                                        });
                                    }
                                    result.push({
                                        text: data[group].label,
                                        children: children,
                                        allowMultiple: data[group].allowMultiple
                                    });
                                } else {
                                    result.push({
                                        id: data[group]['class'],
                                        text: data[group]['label'],
                                        disabled: data[group].hasOwnProperty('disabled') && data[group]['disabled'],
                                        external: data[group]['external']
                                    });
                                }
                            }
                        }
                        return result;
                    },
                    getGlobalPredefinedClasses: function getGlobalPredefinedClasses() {
                        var result = [];
                        $.each(IframeCE.Style.globalPredefinedClasses, function (key, value) {
                            if (value.hasOwnProperty('values')) {
                                var children = [];
                                $.each(value.values, function (k, val) {
                                    children.push({
                                        id: val['class'],
                                        text: val.label,
                                        'disabled': val.hasOwnProperty('disabled') && val['disabled']
                                    });
                                });
                                result.push({
                                    text: value.label,
                                    children: children,
                                    allowMultiple: value.allowMultiple
                                });
                            } else {
                                result.push({
                                    id: value['class'],
                                    text: value.label,
                                    'disabled': value.hasOwnProperty('disabled') && value['disabled']
                                });
                            }
                        });
                        return result;
                    }
                });
                CE.Ctrl('CE.CtrlStyleEditor', { listensTo: ['customize'] }, {
                    privateStyleObj: null,
                    previewState: null,
                    select2DropdownClass: 'motopress-property-select2-style-editor',
                    isPreview: false,
                    previewClass: null,
                    unsetClasses: [],
                    uniqueDropdownClass: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.uniqueDropdownClass = MP.Utils.uniqid('motopress-select2-dropdown-');
                    },
                    get: function get() {
                        var self = this;
                        var data = this.element.select2('data');
                        var result = '';
                        if (!this.privateStyleObj.isEmpty()) {
                            result += this.privateStyleObj.getId();
                        }
                        if (this.isPreview) {
                            data = data.filter(function (el) {
                                return $.inArray(el.id, self.unsetClasses) === -1;
                            });
                        }
                        data.forEach(function (el, index) {
                            if (!el.hasOwnProperty('locked') && el.locked !== true) {
                                if (IframeCE.StyleEditor.myThis.isExistsPreset(el.id)) {
                                    result += result !== '' ? ' ' + el.id : el.id;
                                }
                            }
                        });
                        if (this.isPreview) {
                            result += result !== '' ? ' ' + this.previewClass : this.previewClass;
                        }
                        return result;
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        var newValue = '';
                        if (!this.privateStyleObj) {
                            this.privateStyleObj = IframeCE.StyleEditor.myThis.getPrivateStyleInstance(value, this.shortcodeName);
                        }
                        newValue += this.privateStyleObj.getId();
                        var presetClass = IframeCE.StyleEditor.myThis.retrievePresetClass(value);
                        if (!IframeCE.StyleEditor.myThis.isExistsPreset(presetClass)) {
                            presetClass = '';
                        } else {
                            newValue += ' ' + presetClass;
                        }
                        this.element.val(newValue);
                    },
                    selectPreset: function selectPreset(preset) {
                        var presetObj = IframeCE.StyleEditor.myThis.getPresetStyleInstance(preset);
                        this.element.select2('data', [
                            this.getPrivateBtn(),
                            {
                                id: presetObj.getId(),
                                text: presetObj.getLabel()
                            }
                        ]);
                        this.privateStyleFormCtrl.setPresetStyleObj(presetObj);
                    },
                    ' customize': function customize() {
                        if (this.customized) {
                            return false;
                        } else {
                            this.customized = true;
                        }
                        this.privateStyleForm = $('<div />', { 'class': 'motopress-style-editor-forms motopress-style-editor-private-forms' });
                        this.presetStyleForm = $('<div />', { 'class': 'motopress-style-editor-forms motopress-style-editor-preset-forms' });
                        var opts = {
                            'formsMainCtrl': this.formCtrl.formsMainCtrl,
                            'styleEditorCtrl': this,
                            'switcher': CE.StyleModeSwitcher
                        };
                        this.privateStyleFormCtrl = new CE.PrivateStyleControlsForm(this.privateStyleForm, $.extend({ 'editedStyleObj': this.privateStyleObj }, opts));
                        this.presetStyleFormCtrl = new CE.PresetStyleControlsForm(this.presetStyleForm, opts);
                        this.customizeSelect();
                    },
                    refresh: function refresh() {
                        var data = this.element.select2('data');
                        $(data).filter(function (index, details) {
                            var isPrivateStyle = details.hasOwnProperty('locked') && details.locked;
                            if (isPrivateStyle) {
                                return true;
                            } else if (IframeCE.StyleEditor.myThis.isExistsPreset(details.id)) {
                                var presetObj = IframeCE.StyleEditor.myThis.getPresetStyleInstance(details.id);
                                details.text = presetObj.getLabel();
                                return true;
                            }
                            return false;
                        });
                        this.element.select2('data', data);
                        this.element.trigger('change');
                    },
                    setPreviewClass: function setPreviewClass(cls) {
                        this.previewClass = cls;
                        this.formCtrl.changeProperty(this);
                    },
                    getPrivateBtn: function getPrivateBtn() {
                        return {
                            id: this.privateStyleObj.getId(),
                            text: 'Edit Element Style',
                            locked: true
                        };
                    },
                    customizeSelect: function customizeSelect() {
                        var self = this;
                        self.data = IframeCE.StyleEditor.myThis.getPresetsList(true);
                        this.element.select2({
                            'multiple': true,
                            separator: ' ',
                            closeOnSelect: true,
                            initSelection: function initSelection(element, callback) {
                                callback(self.valueToData(self.element.val().split(' ')));
                            },
                            'query': function query(_query2) {
                                var data = { results: [] };
                                $.each(IframeCE.StyleEditor.myThis.getPresetsListSelect2(), function (key, val) {
                                    if (val.text.toUpperCase().indexOf(_query2.term.toUpperCase()) >= 0) {
                                        data.results.push(val);
                                    }
                                });
                                _query2.callback(data);
                            },
                            'formatNoMatches': function formatNoMatches() {
                                return localStorage.getItem('CEStylePresetsFormatNoMatches');
                            },
                            'formatResult': function formatResult(state, container) {
                                return '<i class="select2-preview-icon" data-value="' + state.id + '"></i>' + state.text;
                            },
                            'containerCssClass': 'motopress-select2',
                            'dropdownCssClass': 'motopress-select2-dropdown select2-control-dropdown ' + self.select2DropdownClass + ' ' + this.uniqueDropdownClass
                        });
                        this.element.select2('container').on('click', '.select2-search-choice', function (e) {
                            var data = $(this).data('select2Data');
                            if (data.hasOwnProperty('locked') && data.locked) {
                                self.privateStyleFormCtrl.setPresetStyleObj(self.getSelectedPresetInstance());
                                self.privateStyleFormCtrl.display(true);
                            } else {
                                var presetStyleObj = self.getSelectedPresetInstance();
                                if (presetStyleObj !== null) {
                                    self.presetStyleFormCtrl.setEditedStyleObj(presetStyleObj);
                                    self.presetStyleFormCtrl.display(true);
                                }
                            }
                        });
                        this.element.on('select2-highlight', function (e) {
                            self.unsetClasses = self.getUnsetClasses(e.val);
                        });
                        $('.' + self.uniqueDropdownClass).on('mouseover', '.select2-preview-icon', function () {
                            var val = $(this).attr('data-value');
                            self.setPreview(val);
                            CE.Ctrl.bodyEl.trigger('MPCEObjectStylePreviewOver', {
                                'objElement': self.shortcode,
                                'objName': self.shortcodeName
                            });
                        });
                        $('.' + self.uniqueDropdownClass).on('mouseleave', '.select2-preview-icon', function () {
                            self.isPreview = false;
                            self.unsetClasses = [];
                            self.setPreviewClass();
                            CE.Ctrl.bodyEl.trigger('MPCEObjectStylePreviewOut', {
                                'objElement': self.shortcode,
                                'objName': self.shortcodeName
                            });
                        });
                        this.element.on('select2-blur select2-close', function (e) {
                            self.isPreview = false;
                            self.unsetClasses = [];
                            self.setPreviewClass();
                        });
                        this.element.on('select2-selecting', function (e) {
                            self.isPreview = false;
                            var oldValue = self.element.select2('val');
                            var value = self.excludeValues(oldValue);
                            self.element.select2('data', self.valueToData(value));
                        });
                        this.element.on('change', this.proxy(function (e) {
                            if (e.hasOwnProperty('val') || e.hasOwnProperty('removed')) {
                                var shortcodeLabel = this.shortcode.control(IframeCE.Shortcode).shortcodeLabel;
                                CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.EntityStylesChanged.NAME, new IframeCE.SceneEvents.EntityStylesChanged(shortcodeLabel, '[Styles]'));
                            }
                        }));
                    },
                    setPreview: function setPreview(clsName) {
                        this.unsetClasses = this.getUnsetClasses(clsName);
                        this.isPreview = true;
                        this.setPreviewClass(clsName);
                    },
                    unsetPreview: function unsetPreview() {
                        this.isPreview = false;
                        this.unsetClasses = [];
                        this.setPreviewClass();
                    },
                    'valueToData': function valueToData(value) {
                        var self = this;
                        var data = [];
                        $.each(value, function (index, val) {
                            if (IframeCE.StyleEditor.myThis.isExistsPreset(val)) {
                                var presetCtrl = IframeCE.StyleEditor.myThis.getPresetStyleInstance(val);
                                data.push({
                                    id: presetCtrl.getId(),
                                    text: presetCtrl.getLabel()
                                });
                            } else if (IframeCE.StyleEditor.myThis.retrievePrivateClass(val)) {
                                data.push(self.getPrivateBtn());
                            }
                        });
                        return data;
                    },
                    getUnsetClasses: function getUnsetClasses(clsName) {
                        var presets = IframeCE.StyleEditor.myThis.getPresetsList();
                        delete presets[clsName];
                        return $.map(presets, function (element, index) {
                            return index;
                        });
                    },
                    excludeValues: function excludeValues(value) {
                        var self = this;
                        value = value.filter(function (el) {
                            return $.inArray(el, self.unsetClasses) === -1;
                        });
                        return value;
                    },
                    getSelectedPreset: function getSelectedPreset() {
                        var value = this.get();
                        return IframeCE.StyleEditor.myThis.retrievePresetClass(value);
                    },
                    getSelectedPresetInstance: function getSelectedPresetInstance() {
                        var presetClass = this.getSelectedPreset();
                        return IframeCE.StyleEditor.myThis.getPresetStyleInstance(presetClass);
                    },
                    setStatePreview: function setStatePreview(state) {
                        this.unsetStatePreview();
                        var previewStates = [];
                        switch (state) {
                        case 'up':
                            previewStates.push('up');
                            break;
                        case 'hover':
                            previewStates.push('up');
                            previewStates.push('hover');
                            break;
                        case 'tablet':
                            previewStates.push('up');
                            previewStates.push('tablet');
                            break;
                        case 'mobile':
                            previewStates.push('up');
                            previewStates.push('tablet');
                            previewStates.push('mobile');
                            break;
                        }
                        var previewStateClasses = previewStates.map(function (val) {
                            return IframeCE.ShortcodeStyle.previewStateClassPrefix + val;
                        }).join(' ');
                        var targetElement = this.formCtrl.getTargetElement(this);
                        if (targetElement !== null) {
                            targetElement.addClass(previewStateClasses);
                        }
                    },
                    unsetStatePreview: function unsetStatePreview() {
                        var allPreviewStateClasses = [
                            'up',
                            'hover',
                            'mobile',
                            'tablet'
                        ].map(function (val) {
                            return IframeCE.ShortcodeStyle.previewStateClassPrefix + val;
                        }).join(' ');
                        var targetElement = this.formCtrl.getTargetElement(this);
                        if (targetElement !== null) {
                            targetElement.removeClass(allPreviewStateClasses);
                        }
                    }
                });
                CE.Ctrl('CE.CtrlComplex', { listensTo: ['customize'] }, {
                    innerForm: null,
                    innerFormCtrl: null,
                    parameters: {},
                    init: function init(el, args) {
                        this._super(el, args);
                        this.innerForm = this.element.children('.motopress-property-inner-form');
                        this.innerFormCtrl = new CE.ControlsSubForm(this.innerForm, {
                            'formsMainCtrl': this.formCtrl.formsMainCtrl,
                            'parentPropertyCtrl': this
                        });
                        this.innerFormCtrl.display(true);
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.innerFormCtrl.set(this.convertValueToFormData(value));
                    },
                    get: function get() {
                        var formData = this.innerFormCtrl.get();
                        return this.convertFormDataToValue(formData);
                    },
                    convertValueToFormData: function convertValueToFormData() {
                        return {};
                    },
                    convertFormDataToValue: function convertFormDataToValue() {
                        return '';
                    },
                    getParameters: function getParameters() {
                        return this.parameters;
                    }
                });
                CE.CtrlComplex('CE.CtrlGradient', {
                    valueSeparator: ' , ',
                    parseGradientStr: function parseGradientStr(str) {
                        var gradientParameters = false;
                        if (str !== '') {
                            var valuesArr = str.split(CE.CtrlGradient.valueSeparator);
                            if (valuesArr.length === 3) {
                                gradientParameters = {
                                    'angle': String(parseInt(valuesArr[0])),
                                    'initial-color': valuesArr[1],
                                    'final-color': valuesArr[2]
                                };
                            }
                        }
                        return gradientParameters;
                    }
                }, {
                    parameters: {
                        'angle': {
                            'type': 'spinner',
                            'label': 'Gradient Angle',
                            'min': 0,
                            'max': 360,
                            'step': 1,
                            'default': '0'
                        },
                        'final-color': {
                            'type': 'color-picker',
                            'label': 'Gradient Initial Color',
                            'default': ''
                        },
                        'initial-color': {
                            'type': 'color-picker',
                            'label': 'Gradient Final Color',
                            'default': ''
                        }
                    },
                    convertValueToFormData: function convertValueToFormData(value) {
                        var formData = CE.CtrlGradient.parseGradientStr(value);
                        if (!formData) {
                            formData = {
                                'angle': this.parameters['angle']['default'],
                                'initial-color': this.parameters['initial-color']['default'],
                                'final-color': this.parameters['final-color']['default']
                            };
                        }
                        return formData;
                    },
                    convertFormDataToValue: function convertFormDataToValue(formData) {
                        if (formData.hasOwnProperty('angle') && !isNaN(parseInt(formData.angle))) {
                            formData['angle'] = String(parseInt(formData.angle));
                        } else {
                            formData['angle'] = '0';
                        }
                        if (!formData.hasOwnProperty('initial-color') || formData['initial-color'] === '') {
                            formData['initial-color'] = '';
                        }
                        if (!formData.hasOwnProperty('final-color') || formData['final-color'] === '') {
                            formData['final-color'] = '';
                        }
                        var value = formData['angle'] + CE.CtrlGradient.valueSeparator + formData['initial-color'] + CE.CtrlGradient.valueSeparator + formData['final-color'];
                        return value;
                    }
                });
                CE.Ctrl('CE.CtrlEditorButton', {}, {
                    'click': function click() {
                        var ctrl = this.shortcode.control(IframeCE.CodeEditor, IframeCE.InlineEditor);
                        if (ctrl) {
                            ctrl.open();
                        }
                    }
                });
                CE.Ctrl('CE.CtrlColorPicker', {
                    listensTo: [
                        'customize',
                        'dialogOpen'
                    ],
                    'paletteAddBtn': $('<span />', { 'class': 'palette-add' })
                }, {
                    lastTimerId: null,
                    input: null,
                    valueObserver: null,
                    dispatchChangeFlag: false,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.input = el.children('.motopress-property-input');
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        if (this.input.data('spectrum.id') !== undefined) {
                            this.input.spectrum('set', value);
                        } else {
                            this.input.val(value);
                        }
                    },
                    get: function get() {
                        return this.input.val();
                    },
                    'paletteAdd': function paletteAdd() {
                        var curColor = this.get();
                        if (curColor !== '') {
                            var newPalettes = MP.Settings.palettes;
                            newPalettes.pop();
                            newPalettes.unshift(curColor);
                            $.ajax({
                                url: motopress.ajaxUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'motopress_ce_colorpicker_update_palettes',
                                    nonce: motopressCE.nonces.motopress_ce_colorpicker_update_palettes,
                                    postID: motopressCE.postID,
                                    palettes: newPalettes
                                },
                                success: function success(data) {
                                    MP.Settings.palettes = data.data.palettes;
                                    $('.motopress-property-color-picker>.motopress-property-input').each(function () {
                                        $(this).spectrum('option', 'palette', MP.Settings.palettes);
                                    });
                                },
                                error: function error(jqXHR) {
                                    var error = $.parseJSON(jqXHR.responseText);
                                    if (error.debug) {
                                        console.log(error.message);
                                    } else {
                                        MP.Flash.setFlash(error.message, 'error');
                                        MP.Flash.showMessage();
                                    }
                                }
                            });
                        }
                    },
                    'move.spectrum': function moveSpectrum(el, e, color) {
                        var self = this;
                        if (color === null) {
                            color = '';
                        }
                        clearTimeout(this.lastTimerId);
                        this.lastTimerId = setTimeout(function () {
                            self.input.val(color);
                            self.valueObserver(self.input.val());
                        }, 500);
                    },
                    'change.spectrum': function changeSpectrum() {
                        this.valueObserver(this.input.val());
                    },
                    ' customize': function customize(el, e) {
                        if (this.customized)
                            return false;
                        else
                            this.customized = true;
                        this.input.spectrum({
                            allowEmpty: true,
                            showAlpha: true,
                            showInput: true,
                            showInitial: true,
                            showPalette: true,
                            showSelectionPalette: false,
                            showButtons: false,
                            appendTo: this.element,
                            palette: MP.Settings.palettes,
                            preferredFormat: 'rgb',
                            containerClassName: 'motopress-property-colorpicker mpce-spectrum-theme',
                            replacerClassName: 'mpce-spectrum-theme'
                        });
                        this.replacer = this.element.find('.sp-replacer');
                        this.container = this.element.find('.sp-container');
                        this.initPaletteAddBtn();
                        this.valueObserver = can.compute(this.input.val());
                        this.valueObserver.bind('change', this.proxy(this.dispatchChange));
                    },
                    'change': function change(el, e) {
                        if (this.canDispatchChange()) {
                            this._super(el, e);
                        }
                        this.resetChangeDispatcher();
                    },
                    dispatchChange: function dispatchChange() {
                        this.dispatchChangeFlag = true;
                        this.element.trigger('change');
                    },
                    canDispatchChange: function canDispatchChange() {
                        return this.dispatchChangeFlag;
                    },
                    resetChangeDispatcher: function resetChangeDispatcher() {
                        this.dispatchChangeFlag = false;
                    },
                    initPaletteAddBtn: function initPaletteAddBtn() {
                        var self = this, paletteAddBtn = CE.CtrlColorPicker.paletteAddBtn.clone();
                        paletteAddBtn.on('click', function () {
                            self.paletteAdd();
                        });
                        this.container.prepend(paletteAddBtn);
                    }
                });
                CE.Ctrl('CE.CtrlSlider', {}, {
                    slider: null,
                    span: null,
                    init: function init(el, args) {
                        var self = this;
                        this._super(el, args);
                        this.slider = el.children('.motopress-property-slider');
                        this.span = el.children('.motopress-property-slider-value');
                        this.slider.slider({
                            range: 'min',
                            min: parseInt(args.min),
                            max: parseInt(args.max),
                            step: parseInt(args.step),
                            value: parseInt(args.value),
                            slide: function slide(event, ui) {
                                self.span.html(ui.value);
                            },
                            change: function change(event, ui) {
                                if (typeof event.handleObj !== 'undefined') {
                                    el.trigger('change');
                                }
                            },
                            disabled: args.disabled == 'true'
                        });
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.span.text(value);
                        this.slider.slider('value', value);
                    },
                    get: function get() {
                        return this.slider.slider('option', 'value');
                    }
                });
                CE.Ctrl('CE.CtrlRadioButtons', {}, {
                    init: function init(el, args) {
                        this._super(el, args);
                        this.element.buttonset();
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = typeof value !== 'undefined' ? value : defaultValue;
                        if (typeof value !== 'undefined') {
                            this.element.find(':radio[value="' + value + '"]').attr('checked', true);
                        } else {
                            this.element.find(':radio').removeAttr('checked');
                        }
                        this.element.buttonset('refresh');
                    },
                    get: function get() {
                        return this.element.find(':radio:checked').val();
                    }
                });
                CE.Ctrl('CE.CtrlGroup', { listensTo: ['render'] }, {
                    currentSpan: null,
                    group: null,
                    label: null,
                    accordion: null,
                    button: null,
                    oldItemIndex: null,
                    childFormCtrls: null,
                    activeParameter: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.currentSpan = args.currentSpan;
                        this.shortcodeCtrl = args.shortcode.control(IframeCE.Controls);
                        this.label = args.label;
                        this.rules = args.rules;
                        this.events = args.events;
                        this.hasRules = !!(this.rules && this.rules.hasOwnProperty('rootSelector') && this.rules.rootSelector);
                        this.hasActiveRules = !!(this.hasRules && this.rules.hasOwnProperty('activeSelector') && this.rules.hasOwnProperty('activeClass') && this.rules.activeClass);
                        this.hasEvents = !!(this.events && (this.events.hasOwnProperty('onActive') || this.events.hasOwnProperty('onInactive')));
                        this.activeParameter = args.activeParameter;
                        this.childFormCtrls = [];
                        this.accordion = el.children('.motopress-property-group-accordion');
                        this.button = el.find('> .motopress-property-button-wrapper > .motopress-property-button-default');
                        this.initChildForms();
                    },
                    initChildForms: function initChildForms() {
                        this.shortcode.find('[data-motopress-shortcode]').each(this.proxy(function (index, innerShortcode) {
                            var item = CE.CtrlTemplates.createItem();
                            this.addChildFormCtrl(new CE.SettingsControlsChildForm(item, {
                                'parentFormCtrl': this.formCtrl,
                                'formsMainCtrl': this.shortcodeCtrl,
                                'shortcode': $(innerShortcode),
                                'groupCtrl': this
                            }));
                            this.accordion.append(item);
                        }));
                    },
                    addChildFormCtrl: function addChildFormCtrl(childFormCtrl) {
                        this.childFormCtrls.push(childFormCtrl);
                    },
                    reassignShortcodes: function reassignShortcodes() {
                        var shortcode;
                        var $this = this;
                        $.each(this.childFormCtrls, function (index, childFormCtrl) {
                            shortcode = $this.formCtrl.shortcode.find('[data-motopress-shortcode]:eq(' + index + ')');
                            childFormCtrl.setShortcode(shortcode);
                        });
                    },
                    ' render': function render(el, e, isNew) {
                        this.updateLabel();
                        var $this = this;
                        var active = this.shortcode.attr('data-motopress-active-item');
                        active = isNew && typeof active !== 'undefined' ? parseInt(active) : false;
                        var accordionSettings = {
                            active: active,
                            header: '> div > h3',
                            heightStyle: 'content',
                            collapsible: true,
                            create: function create(event, ui) {
                                var active = $this.accordion.accordion('option', 'active');
                                if (active !== false) {
                                    $this.accordion.find('.motopress-property-group-accordion-item:eq(' + active + ') .motopress-controls').trigger('customize');
                                }
                            },
                            beforeActivate: function beforeActivate(event, ui) {
                                if (event.hasOwnProperty('originalEvent') && event.originalEvent.hasOwnProperty('target') && ($(event.originalEvent.target).hasClass('motopress-property-group-accordion-item-remove') || $(event.originalEvent.target).hasClass('motopress-property-group-accordion-item-duplicate') || $(event.originalEvent.target).closest('.motopress-property-group-accordion-item-duplicate').length)) {
                                    event.preventDefault();    
                                }
                            },
                            activate: function activate(event, ui) {
                                var oldIndex = -1, newIndex = -1;
                                if (typeof ui.oldHeader[0] !== 'undefined') {
                                    oldIndex = ui.oldHeader.parent('.motopress-property-group-accordion-item').index();
                                }
                                if (typeof ui.newHeader[0] !== 'undefined') {
                                    newIndex = ui.newHeader.parent('.motopress-property-group-accordion-item').index();
                                }
                                var childName = $this.shortcodeCtrl.childName;
                                if (oldIndex >= 0) {
                                    $this.interact('deactivate', oldIndex);
                                    $this.shortcode.removeAttr('data-motopress-active-item');
                                }
                                if (newIndex >= 0) {
                                    $this.interact('activate', newIndex);
                                    $this.shortcode.attr('data-motopress-active-item', newIndex);
                                    ui.newHeader.parent('.motopress-property-group-accordion-item').find('.motopress-controls').trigger('customize');
                                }
                                if (childName && $this.hasRules && $this.hasEvents) {
                                    var children = $this.shortcode.find($this.rules.rootSelector);
                                    if (children.length) {
                                        var activeChild, activeTagItem;
                                        if (oldIndex >= 0 && $this.events.hasOwnProperty('onInactive')) {
                                            if (children.eq(0).closest('[data-motopress-shortcode="' + childName + '"]').length) {
                                                activeChild = children.closest('[data-motopress-shortcode="' + childName + '"]').eq(oldIndex).find($this.rules.rootSelector);
                                            } else {
                                                activeChild = children.eq(oldIndex);
                                            }
                                            activeTagItem = $this.hasActiveRules && $this.rules.activeSelector ? activeChild.find($this.rules.activeSelector) : activeChild;
                                            if (activeChild.length && (!$this.hasActiveRules || $this.hasActiveRules && activeTagItem.hasClass($this.rules.activeClass))) {
                                                if ($this.events.onInactive.selector) {
                                                    activeChild.find($this.events.onInactive.selector).triggerHandler($this.events.onInactive.event);
                                                } else {
                                                    activeChild.triggerHandler($this.events.onInactive.event);
                                                }
                                                if ($this.hasActiveRules) {
                                                    activeTagItem.removeClass($this.rules.activeClass);
                                                }
                                            }
                                        }
                                        if (newIndex >= 0 && $this.events.hasOwnProperty('onActive')) {
                                            if (children.eq(0).closest('[data-motopress-shortcode="' + childName + '"]').length) {
                                                activeChild = children.closest('[data-motopress-shortcode="' + childName + '"]').eq(newIndex).find($this.rules.rootSelector);
                                            } else {
                                                activeChild = children.eq(newIndex);
                                            }
                                            activeTagItem = $this.hasActiveRules && $this.rules.activeSelector ? activeChild.find($this.rules.activeSelector) : activeChild;
                                            if (activeChild.length && (!$this.hasActiveRules || $this.hasActiveRules && !activeTagItem.hasClass($this.rules.activeClass))) {
                                                if ($this.events.onActive.selector) {
                                                    activeChild.find($this.events.onActive.selector).triggerHandler($this.events.onActive.event);
                                                } else {
                                                    activeChild.triggerHandler($this.events.onActive.event);
                                                }
                                                if ($this.hasActiveRules) {
                                                    if ($this.rules.activeSelector) {
                                                        children.find($this.rules.activeSelector).removeClass($this.rules.activeClass);
                                                    } else {
                                                        children.removeClass($this.rules.activeClass);
                                                    }
                                                    activeTagItem.addClass($this.rules.activeClass);
                                                }
                                            }
                                        }
                                    }
                                }    
                            }
                        };
                        if (this.accordion.accordion('instance')) {
                            this.accordion.accordion('refresh');
                        } else {
                            this.accordion.accordion(accordionSettings).sortable({
                                axis: 'y',
                                handle: 'h3 > .ui-accordion-header-icon',
                                update: function update(e, ui) {
                                    var newItemIndex = ui.item.closest('.motopress-property-group-accordion-item').index();
                                    var tabs = $this.shortcode.children('div').find('[data-motopress-shortcode]');
                                    if ($this.oldItemIndex < newItemIndex) {
                                        tabs.eq($this.oldItemIndex).insertAfter(tabs.eq(newItemIndex));
                                        MP.Utils.moveArrayElement($this.childFormCtrls, $this.oldItemIndex, newItemIndex);
                                    } else {
                                        tabs.eq($this.oldItemIndex).insertBefore(tabs.eq(newItemIndex));
                                        MP.Utils.moveArrayElement($this.childFormCtrls, $this.oldItemIndex, newItemIndex);
                                    }
                                    $this.accordion.accordion('refresh');
                                    $this.shortcode.attr('data-motopress-active-item', $this.accordion.accordion('option', 'active'));
                                    el.trigger('change');
                                },
                                start: function start(e, ui) {
                                    $this.oldItemIndex = ui.item.closest('.motopress-property-group-accordion-item').index();
                                }
                            });
                        }
                        if (this.disabled) {
                            if (!isNew || isNew && this.accordion.children('.motopress-property-group-accordion-item').length) {
                                this.button.attr('disabled', true);
                            }
                        }
                    },
                    'change': function change(el, e, isNew) {
                        this.updateLabel();
                        var content = '';
                        $.each(this.childFormCtrls, this.proxy(function (index, childFormCtrl) {
                            if (isNew && childFormCtrl.shortcode.attr('data-motopress-new') === 'true') {
                                var parameters = typeof childFormCtrl.shortcode.attr('data-motopress-parameters') !== 'undefined' ? childFormCtrl.shortcode.attr('data-motopress-parameters') : null;
                                childFormCtrl.setDefaultAttrs(parameters);
                                $(this).removeAttr('data-motopress-new');
                            }
                            var parser = new CE.ContentParser(childFormCtrl.shortcode);
                            content += parser.content;
                            if (index !== this.childFormCtrls.length) {
                                content += '\n\n';
                            }
                        }));
                        this.shortcode.attr('data-motopress-content', content);
                        CE.Ctrl.prototype.change.apply(this, [
                            el,
                            e
                        ]);
                    },
                    '> .motopress-property-button-wrapper > .motopress-property-button-default click': function motopressPropertyButtonWrapperMotopressPropertyButtonDefaultClick(el, e, count) {
                        e.stopPropagation();
                        if (!this.disabled || typeof count !== 'undefined') {
                            var newItem = $('<div />', { 'data-motopress-new': 'true' });
                            var contains = this.shortcodeCtrl.childName;
                            var attrs = CE.WidgetsLibrary.myThis.getObject(this.shortcodeGroup, contains);
                            if (attrs !== null)
                                CE.WidgetsLibrary.myThis.setAttrs(newItem, this.shortcodeGroup, attrs);
                            if (typeof count !== 'undefined') {
                                var newItems = $();
                                for (var i = 0; i < count; i++) {
                                    newItems = newItems.add(newItem.clone());
                                }
                                newItem = newItems;
                            }
                            this.shortcodeCtrl.child.append(newItem);
                            if (typeof count === 'undefined') {
                                var active = this.shortcodeCtrl.child.find('[data-motopress-shortcode]').index($(newItem).eq(-1));
                                this.shortcode.attr('data-motopress-active-item', active);
                            }
                            this.formCtrl.regenerateGroupControl(this.name);
                            this.formCtrl.display(true, this.name, true);
                        }
                    },
                    '.motopress-property-group-accordion-item-duplicate click': function motopressPropertyGroupAccordionItemDuplicateClick(el, e) {
                        e.stopImmediatePropagation();
                        var index = el.closest('.motopress-property-group-accordion-item').index();
                        var newItem = $('<div />', { 'data-motopress-new': 'true' });
                        var prototypeShortcode = this.shortcode.children('div').find('[data-motopress-shortcode]:eq(' + index + ')');
                        var attrs = CE.WidgetsLibrary.myThis.getActualAtts(prototypeShortcode);
                        var preventDuplicateParameters = [];
                        var defaultAttrs = CE.WidgetsLibrary.myThis.getObject(this.shortcodeGroup, this.shortcodeCtrl.childName);
                        $.each(defaultAttrs.parameters, function (name, parameterAtts) {
                            if (parameterAtts.hasOwnProperty('unique') && parameterAtts.unique) {
                                preventDuplicateParameters.push(name);
                            }
                            if (parameterAtts.hasOwnProperty('saveInContent') && parameterAtts.saveInContent) {
                                delete attrs.parameters[name];
                            }
                        });
                        if (this.activeParameter && attrs.parameters.hasOwnProperty(this.activeParameter) && attrs.parameters[this.activeParameter].hasOwnProperty('value')) {
                            preventDuplicateParameters.push(this.activeParameter);
                        }
                        $.each(preventDuplicateParameters, function (index, param) {
                            if (attrs.parameters.hasOwnProperty(param)) {
                                attrs.parameters[param] = {};
                            }
                        });
                        if (attrs !== null) {
                            CE.WidgetsLibrary.myThis.setAttrs(newItem, this.shortcodeGroup, attrs, false);
                        }
                        this.shortcodeCtrl.child.append(newItem);
                        var active = this.shortcodeCtrl.child.find('[data-motopress-shortcode]').index($(newItem).eq(-1));
                        this.shortcode.attr('data-motopress-active-item', active);
                        this.formCtrl.regenerateGroupControl(this.name);
                        this.formCtrl.display(true, this.name, true);
                    },
                    '.motopress-property-group-accordion-item-remove click': function motopressPropertyGroupAccordionItemRemoveClick(el, e) {
                        e.stopPropagation();
                        var index = el.closest('.motopress-property-group-accordion-item').index();
                        this.shortcode.children('div').find('[data-motopress-shortcode]:eq(' + index + ')').remove();
                        this.shortcode.removeAttr('data-motopress-active-item');
                        this.formCtrl.regenerateGroupControl(this.name);
                        this.formCtrl.display(false, this.name, true);
                    },
                    updateLabel: function updateLabel() {
                        if (this.label.hasOwnProperty('parameter')) {
                            var $this = this;
                            this.accordion.find('.motopress-property-group-accordion-item-label-text').each(function () {
                                var formCtrl = $(this).closest('.motopress-property-group-accordion-item').control(CE.ControlsForm);
                                var ctrl = formCtrl.getCtrlByName($this.label.parameter);
                                if (ctrl) {
                                    $(this).text(ctrl.get());
                                }
                            });
                        }
                    },
                    interact: function interact(action, index) {
                        if (index !== false && index >= 0) {
                            var childObjEl = this.shortcode.find('[data-motopress-shortcode]').eq(index);
                            if (childObjEl.length) {
                                CE.Ctrl.bodyEl.trigger('MPCEObjectInteraction', {
                                    'action': action,
                                    'interacted': 'child',
                                    'objElement': this.shortcode,
                                    'objName': this.shortcodeName,
                                    'objData': this.shortcode.data('motopress-parameters'),
                                    'childObjElement': childObjEl,
                                    'childObjName': this.shortcodeCtrl.childName,
                                    'childObjData': childObjEl.data('motopress-parameters')
                                });
                            }
                        }
                    }
                });
                CE.Ctrl('CE.CtrlMargin', {}, {
                    margin: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.margin = args.margin;
                    },
                    get: function get() {
                        var margin = [];
                        var $this = this;
                        var allNone = true;
                        $.each($this.margin.sides, function (i, side) {
                            var val = $this.element.find('[data-motopress-margin-side="' + side + '"] .' + $this.margin.classPrefix + 'value').text();
                            margin.push(val);
                            if (val !== $this.margin.values[0])
                                allNone = false;
                        });
                        return allNone ? '' : margin.join(',');
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        if (typeof value === 'string') {
                            if (!value.length)
                                return;
                            value = value.split(',');
                        }
                        var $this = this;
                        $.each(value, function (i, val) {
                            var intVal = parseInt(val);
                            if (!isNaN(intVal))
                                val = intVal;
                            var tr = $this.element.find('[data-motopress-margin-side="' + $this.margin.sides[i] + '"]');
                            var icon = tr.find('.' + $this.margin.classPrefix + 'icon');
                            var valueEl = tr.find('.' + $this.margin.classPrefix + 'value');
                            var values = tr.find('.' + $this.margin.classPrefix + 'values');
                            valueEl.text(val);
                            values.removeAttr('data-motopress-margin-disabled');
                            values.filter('[data-motopress-margin-value="' + val + '"]').attr('data-motopress-margin-disabled', '');
                            if (val !== $this.margin['default'][i]) {
                                icon.attr('data-motopress-margin-active', '');
                                valueEl.attr('data-motopress-margin-active', '');
                            } else {
                                icon.removeAttr('data-motopress-margin-active');
                                valueEl.removeAttr('data-motopress-margin-active');
                            }
                        });
                    },
                    '.motopress-margin-values click': function motopressMarginValuesClick(el) {
                        if (typeof el.attr('data-motopress-margin-disabled') === 'undefined') {
                            var tr = el.closest('[data-motopress-margin-side]');
                            var index = tr.index();
                            var icon = tr.find('.' + this.margin.classPrefix + 'icon');
                            var valueEl = tr.find('.' + this.margin.classPrefix + 'value');
                            var values = tr.find('.' + this.margin.classPrefix + 'values');
                            var val = el.text();
                            var intVal = parseInt(val);
                            if (!isNaN(intVal))
                                val = intVal;
                            valueEl.text(val);
                            values.removeAttr('data-motopress-margin-disabled');
                            el.attr('data-motopress-margin-disabled', '');
                            if (val !== this.margin['default'][index]) {
                                icon.attr('data-motopress-margin-active', '');
                                valueEl.attr('data-motopress-margin-active', '');
                            } else {
                                icon.removeAttr('data-motopress-margin-active');
                                valueEl.removeAttr('data-motopress-margin-active');
                            }
                            this.element.trigger('change');
                            var shortcodeLabel = this.shortcode.control(IframeCE.Shortcode).shortcodeLabel;
                            CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.EntityStylesChanged.NAME, new IframeCE.SceneEvents.EntityStylesChanged(shortcodeLabel, '[Styles]'));
                        }
                    },
                    getClasses: function getClasses(value) {
                        var marginClasses = [];
                        if (typeof value !== 'undefined' && value.length) {
                            if (typeof value === 'string')
                                value = value.split(',');
                            var uniqueValues = [];
                            $.each(value, function (i, val) {
                                if ($.inArray(val, uniqueValues) === -1) {
                                    uniqueValues.push(val);
                                }
                            });
                            var isSame = uniqueValues.length === 1;
                            if (value.length === 4 && isSame && uniqueValues[0] !== this.margin.values[0]) {
                                marginClasses.push(this.margin.classPrefix + uniqueValues[0]);
                            } else {
                                var $this = this;
                                $.each(value, function (i, val) {
                                    if (val !== $this.margin['default'][i]) {
                                        marginClasses.push($this.margin.classPrefix + $this.margin.sides[i] + '-' + val);
                                    }
                                });
                            }
                        }
                        return marginClasses.join(' ');
                    }
                });
                CE.Ctrl('CE.CtrlIconPicker', { listensTo: ['customize'] }, {
                    emptyIcon: false,
                    emptyIconValue: null,
                    firstChanged: true,
                    oldValue: '',
                    get: function get() {
                        return this.element.find('option:selected').attr('data-value');
                    },
                    set: function set(value, defaultValue, isNew) {
                        value = CE.Ctrl.processValue(value, defaultValue, isNew);
                        this.element.find('[data-value="' + value + '"]').attr('selected', 'selected');
                    },
                    'change': function change(el, e) {
                        if (this.firstChange) {
                            this.firstChange = false;
                            this.oldValue = this.get();
                            return false;
                        } else {
                            if (this.oldValue != this.get()) {
                                this._super(el, e);
                                CE.CtrlSelect.setSelected(el);
                                this.oldValue = this.get();
                            } else {
                                return false;
                            }
                        }
                    },
                    init: function init(el, args) {
                        this._super(el, args);
                        this.firstChange = true;
                        this.oldValue = this.get();
                        if (typeof args.emptyValue !== 'undefined') {
                            this.emptyIcon = true;
                            this.emptyIconValue = typeof args.emptyValue !== 'undefined' ? args.emptyValue : '';
                        }
                    },
                    ' customize': function customize(el) {
                        if (this.customized)
                            return false;
                        else
                            this.customized = true;
                        el.fontIconPicker({
                            emptyIcon: this.emptyIcon,
                            emptyIconValue: this.emptyIconValue,
                            theme: 'fip-mpce',
                            iconsPerPage: 1000    
                        });
                        var classes = el.get(0).className;
                        el.siblings('.icons-selector').addClass(classes).removeClass('ce_ctrl_icon_picker motopress-controls');
                    }
                });
            }(jQuery));
            (function ($) {
                CE.CtrlTemplates = can.Construct({
                    createLegend: function createLegend(text) {
                        if (typeof text !== 'undefined' && text.length) {
                            var legend = $('<div />', {
                                'class': 'motopress-property-legend',
                                html: text
                            });
                            return $.merge(legend, $('<hr />'));
                        }
                        return null;
                    },
                    createLabel: function createLabel(text) {
                        var label = $('<label />', {
                            'class': 'motopress-property-label',
                            text: text
                        });
                        return label;
                    },
                    createDescription: function createDescription(text) {
                        var description = $('<div />', {
                            'class': 'motopress-property-description',
                            html: text
                        });
                        return $.merge(description, $('<hr />'));
                    },
                    createInput: function createInput(attrs, name, type, className) {
                        if (typeof name === 'undefined')
                            name = null;
                        if (typeof type === 'undefined')
                            type = 'text';
                        if (typeof className === 'undefined')
                            className = 'input';
                        var accept = {};
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled)
                                accept.disabled = disabled;
                        }
                        var text = $('<input />', {
                            'class': 'motopress-property-' + className,
                            type: type,
                            name: name
                        });
                        this.setAttrs(text, attrs, accept);
                        return text;
                    },
                    createLink: function createLink(attrs, name) {
                        var accept = {};
                        var linkWrapper = $('<div />', { 'class': 'motopress-property-link' });
                        var text = this.createInput(attrs, name, undefined, 'link-input');
                        var linkSelector = this.createButton(attrs);
                        linkWrapper.append(text, linkSelector);
                        this.setAttrs(linkWrapper, attrs, accept);
                        return linkWrapper;
                    },
                    createMedia: function createMedia(attrs, name) {
                        if (typeof name === 'undefined')
                            name = null;
                        var textInputClass = '';
                        var accept = {};
                        var linkWrapper = $('<div />', { 'class': 'motopress-property-link' });
                        switch (attrs.type) {
                        case 'audio':
                            var hidden = this.createInput(attrs, name, 'hidden', 'audio-id').appendTo(linkWrapper);
                            textInputClass = 'audio-title';
                            break;
                        case 'media-video':
                            textInputClass = 'video-url';
                            break;
                        case 'media':
                            this.createInput(attrs, name, 'hidden', 'media-id').appendTo(linkWrapper);
                            textInputClass = 'media';
                            break;
                        }
                        var text = this.createInput(attrs, name, 'text', textInputClass).appendTo(linkWrapper);
                        var linkSelector = this.createButton(attrs, name).appendTo(linkWrapper);
                        this.setAttrs(text, attrs, accept);
                        this.setAttrs(linkSelector, attrs, accept);
                        return linkWrapper;
                    },
                    createTextarea: function createTextarea(attrs) {
                        var accept = {
                            rows: 5,
                            cols: 10
                        };
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled)
                                accept.disabled = disabled;
                        }
                        var textarea = $('<textarea />', { 'class': 'motopress-property-textarea' });
                        this.setAttrs(textarea, attrs, accept);
                        return textarea;
                    },
                    createTextareaTinyMCE: function createTextareaTinyMCE(attrs) {
                        var textarea = this.createTextarea(attrs);
                        var button = this.createButton(attrs);
                        var wrapper = $('<div />', { 'class': 'motopress-property-textarea-tinymce' }).append(textarea, button);
                        return wrapper;
                    },
                    createImage: function createImage(attrs) {
                        var accept = {};
                        var image = $('<div />', { 'class': 'motopress-property-image' });
                        var tools = $('<div />', { 'class': 'motopress-image-tools' });
                        $('<div />', { 'class': 'motopress-default motopress-icon-trash motopress-icon-white' }).appendTo(tools);
                        if (!attrs.hasOwnProperty('value') || !attrs.value) {
                            tools.hide();
                        }
                        var thumbnailWrapper = $('<div />', { 'class': 'motopress-thumbnail-crop' });
                        var thumbnail = $('<img />', {
                            src: CE.CtrlImage.thumbnail,
                            'class': 'motopress-thumbnail'
                        }).appendTo(thumbnailWrapper);
                        image.append(thumbnailWrapper, tools);
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled) {
                                image.append($('<div />', { 'class': 'motopress-image-disabled' }));
                            }
                        }
                        this.setAttrs(thumbnail, attrs, accept);
                        return image;
                    },
                    createVideo: function createVideo(attrs, name) {
                        if (typeof name === 'undefined')
                            name = null;
                        var accept = {};
                        var video = this.createInput(attrs, name, 'text', 'video');
                        this.setAttrs(video, attrs, accept);
                        return video;
                    },
                    createCheckbox: function createCheckbox(attrs, name) {
                        if (typeof name === 'undefined')
                            name = null;
                        var accept = {};
                        var checkbox = this.createInput(attrs, name, 'checkbox', 'checkbox-input motopress-property-input');
                        this.setAttrs(checkbox, attrs, accept);
                        return checkbox;
                    },
                    _createSelect: function _createSelect(attrs, name, type) {
                        if (typeof name === 'undefined')
                            name = null;
                        var isMultiple = false;
                        var suffix;
                        switch (type) {
                        case 'select':
                            suffix = 'select';
                            break;
                        case 'select-multiple':
                            suffix = 'select-multiple';
                            isMultiple = true;
                            break;
                        }
                        var accept = {};
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled)
                                accept.disabled = disabled;
                        }
                        var select = $('<select />', {
                            'class': 'motopress-property-' + suffix + ' motopress-bootstrap-dropdown motopress-dropdown-select',
                            'multiple': isMultiple ? 'multiple' : false,
                            name: name
                        });
                        var options = CE.CtrlTemplates.generateOptions(attrs.list);
                        select.append(options);
                        this.setAttrs(select, attrs, accept);
                        return select;
                    },
                    generateOptions: function generateOptions(list, selected) {
                        var options = [];
                        var optionAttrs;
                        $.each(list, function (value, label) {
                            optionAttrs = {
                                value: value,
                                text: label
                            };
                            if (typeof selected !== 'undefined' && selected === value) {
                                optionAttrs['selected'] = 'selected';
                            }
                            options.push($('<option />', optionAttrs));
                        });
                        return options;
                    },
                    createSelect: function createSelect(attrs, name) {
                        return this._createSelect(attrs, name, 'select');
                    },
                    createSelectMultiple: function createSelectMultiple(attrs, name) {
                        return this._createSelect(attrs, name, 'select-multiple');
                    },
                    createColorSelect: function createColorSelect(attrs, name) {
                        if (typeof name === 'undefined')
                            name = null;
                        var accept = {};
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled)
                                accept.disabled = disabled;
                        }
                        var select = $('<select />', {
                            'class': 'motopress-property-select motopress-bootstrap-dropdown color-select motopress-dropdown-select',
                            name: name
                        });
                        var classPrefix = typeof attrs['class-prefix'] === 'undefined' ? '' : attrs['class-prefix'];
                        $.each(attrs.list, function (name, value) {
                            var colorClass = typeof name == 'string' ? name.toLowerCase() : '';
                            select.append($('<option />', {
                                value: name,
                                text: value
                            }).attr('data-content', '<span class="color ' + classPrefix + colorClass + '"></span><span>' + value + '</span>'));
                        });
                        this.setAttrs(select, attrs, accept);
                        return select;
                    },
                    createButton: function createButton(attrs, name, type) {
                        if (typeof name === 'undefined')
                            name = null;
                        if (typeof type === 'undefined' || !type)
                            type = 'default';
                        var accept = {};
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled)
                                accept.disabled = disabled;
                        }
                        var addinitionalClasses = attrs.hasOwnProperty('class') ? ' ' + attrs['class'] : '';
                        var button = $('<button />', {
                            'class': 'motopress-property-button-' + type + addinitionalClasses,
                            text: attrs.text,
                            name: name
                        });
                        this.setAttrs(button, attrs, accept);
                        return button;
                    },
                    createColorPicker: function createColorPicker(attrs, name) {
                        var accept = {};
                        var input = this.createInput(attrs, name, 'hidden');
                        var wrapper = $('<div />', { 'class': 'motopress-property-color-picker' }).append(input);
                        this.setAttrs(wrapper, attrs, accept);
                        return wrapper;
                    },
                    createSlider: function createSlider(attrs, name) {
                        var slider = $('<div>', { 'class': 'motopress-property-slider' });
                        var span = $('<span>', {
                            type: 'text',
                            'class': 'motopress-property-slider-value',
                            text: attrs['default']
                        });
                        var wrapper = $('<div>', { 'class': 'motopress-property-slider' });
                        slider.appendTo(wrapper);
                        span.appendTo(wrapper);
                        return wrapper;
                    },
                    createRadioButtons: function createRadioButtons(attrs) {
                        var self = this;
                        var accept = {};
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled)
                                accept.disabled = disabled;
                        }
                        var group = $('<div />', { 'class': 'motopress-property-radio-buttons' });
                        var id = parent.MP.Utils.uniqid();
                        var unique = 0;
                        $.each(attrs.list, function (key, value) {
                            var radioButton = $('<input />', {
                                type: 'radio',
                                id: id + unique,
                                name: id,
                                value: key
                            });
                            self.setAttrs(radioButton, attrs, accept);
                            var label = $('<label />', {
                                'for': id + unique,
                                text: value
                            });
                            group.append(radioButton, label);
                            unique++;
                        });
                        this.setAttrs(group, attrs, accept);
                        return group;
                    },
                    createGroup: function createGroup(attrs) {
                        var accept = {};
                        var wrapper = $('<div />', { 'class': 'motopress-property-group' });
                        var accordion = $('<div />', { 'class': 'motopress-property-group-accordion' }).appendTo(wrapper);
                        var buttonWrapper = $('<div />', { 'class': 'motopress-property-button-wrapper' }).appendTo(wrapper);
                        var buttonAttrs = $.extend({}, attrs);
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled) {
                                delete buttonAttrs.disabled;
                                var disabledDiv = $('<div />', { 'class': 'motopress-property-disabled' }).appendTo(buttonWrapper);
                            }
                        }
                        var button = this.createButton(buttonAttrs).appendTo(buttonWrapper);
                        this.setAttrs(wrapper, attrs, accept);
                        return wrapper;
                    },
                    createItem: function createItem() {
                        var labelText = $('<span />', { 'class': 'motopress-property-group-accordion-item-label-text' });
                        var btnDuplicate = $('<span />', {
                            'class': 'motopress-property-group-accordion-item-btn motopress-property-group-accordion-item-duplicate',
                            'title': 'Duplicate',
                            html: $('<i />', { 'class': 'fa fa-clone' })
                        });
                        var btnRemove = $('<span />', {
                            'class': 'motopress-property-group-accordion-item-btn motopress-property-group-accordion-item-remove',
                            'title': 'Delete',
                            html: $('<i />', { 'class': 'fa fa-trash-o' })
                        });
                        var label = $('<h3 />', { 'class': 'motopress-property-group-accordion-item-label' }).append(labelText, btnDuplicate, btnRemove);
                        var content = $('<div />', { 'class': 'motopress-property-group-accordion-item-content' });
                        var wrapper = $('<div />', { 'class': 'motopress-property-group-accordion-item' }).append(label, content);
                        return wrapper;
                    },
                    createMargin: function createMargin(props) {
                        var table = $('<table />', { 'class': 'motopress-property-margin' });
                        $.each(props.sides, function (i, side) {
                            var tr = $('<tr />', { 'data-motopress-margin-side': side }).appendTo(table);
                            $('<td />').append($('<i />', { 'class': props.classPrefix + 'icon ' + props.classPrefix + 'icon-' + side })).appendTo(tr);
                            $('<td />').append($('<div />', {
                                'class': props.classPrefix + 'value',
                                text: props['default'][i]
                            })).appendTo(tr);
                            $.each(props.values, function (i, val) {
                                $('<td />').append($('<div />', {
                                    'class': props.classPrefix + 'values',
                                    'data-motopress-margin-value': val,
                                    text: val
                                })).appendTo(tr);
                            });
                        });
                        return table;
                    },
                    createIconPicker: function createIconPicker(attrs, name) {
                        if (typeof name === 'undefined')
                            name = null;
                        var accept = {};
                        if (attrs.hasOwnProperty('disabled')) {
                            var disabled = attrs.disabled === 'true';
                            if (disabled)
                                accept.disabled = disabled;
                        }
                        var select = $('<select />', {
                            'class': 'motopress-property-icon-picker',
                            name: name
                        });
                        var options = '';
                        for (var name in attrs.list) {
                            var value = attrs.list[name];
                            options += '<option value="' + value['class'] + '" data-value="' + name + '" >' + value.label + '</option>';
                        }
                        select.get(0).innerHTML = options;
                        this.setAttrs(select, attrs, accept);
                        return select;
                    },
                    createDateTimePicker: function createDateTimePicker(attrs, name) {
                        var accept = {};
                        var datepickerWrapper = $('<div />', { 'class': 'motopress-property-datetime-picker' });
                        var divForm = $('<div />', { 'class': 'form-group' });
                        var divWrapper = $('<div />', { 'class': 'input-group date motopress-property-datetime-picker-wrapper ' });
                        attrs.readonly = 'readonly';
                        var input = this.createInput(attrs, name, undefined, 'datetime-picker-input form-control');
                        var buttonSelector = $('<span />', { 'class': 'input-group-addon motopress-property-datetime-picker-button' });
                        var iconButton = $('<span />', { 'class': 'motopress-calendar-icon fa fa-calendar' });
                        var button = buttonSelector.append(iconButton);
                        divForm.append(divWrapper.append(input, button));
                        datepickerWrapper.append(divForm);
                        this.setAttrs(datepickerWrapper, attrs, accept);
                        return datepickerWrapper;
                    },
                    createStyleEditor: function createStyleEditor(attrs, name) {
                        var accept = {};
                        var styleEditorWrapper = $('<div />', { 'class': 'motopress-property-style-editor-wrapper' });
                        var presetSelectInput = this.createInput({});
                        styleEditorWrapper.append(presetSelectInput);
                        this.setAttrs(styleEditorWrapper, attrs, accept);
                        return styleEditorWrapper;
                    },
                    createComplexCtrl: function createComplexCtrl(attrs, name) {
                        var accept = {};
                        var wrapper = $('<div />', { 'class': 'motopress-property-complex' });
                        var innerForm = $('<div />', { 'class': 'motopress-property-inner-form' });
                        wrapper.append(innerForm);
                        this.setAttrs(wrapper, attrs, accept);
                        return wrapper;
                    },
                    setAttrs: function setAttrs(form, attrs, accept) {
                        if (_typeof(attrs) !== 'object')
                            attrs = {};
                        $.each(accept, function (name, value) {
                            if (typeof attrs[name] !== 'undefined') {
                                form.attr(name, attrs[name]);
                            } else {
                                if (value !== null) {
                                    form.attr(name, value);
                                }
                            }
                        });
                    }
                }, {});
            }(jQuery));
            (function ($) {
                CE.ControlsForm = can.Control.extend({}, {
                    form: null,
                    init: function init(el, args) {
                        this.beforeInit();
                        this.setupDeps(args);
                        this.generate();
                    },
                    restoreClone: function restoreClone(args) {
                        this.setupDeps(args);
                        if (args.formsMainCtrl.groupItemName) {
                            this.regenerateGroupControl(args.formsMainCtrl.groupItemName);
                        }
                    },
                    setupDeps: function setupDeps(args) {
                        this.formsMainCtrl = args.formsMainCtrl;
                        this.detectForm();
                        this.detectShortcode(args.hasOwnProperty('shortcode') ? args.shortcode : false);
                    },
                    beforeInit: function beforeInit() {
                    },
                    detectForm: function detectForm() {
                        this.form = this.element;
                    },
                    detectShortcode: function detectShortcode(customShortcode) {
                        var shortcode = customShortcode === false ? this.formsMainCtrl.shortcode : customShortcode;
                        this.setShortcode(shortcode);
                        this.shortcodeGroup = IframeCE.Shortcode.getShortcodeGroup(this.shortcode);
                        this.shortcodeName = IframeCE.Shortcode.getShortcodeName(this.shortcode);
                    },
                    setShortcode: function setShortcode(shortcode) {
                        this.shortcode = shortcode;
                    },
                    generate: function generate() {
                        var attrs = this.getAttrs();
                        var generalAttrs = this.getGeneralAttrs();
                        if (generalAttrs) {
                            var propertiesControls = this.generatePropertiesControls(generalAttrs, attrs);
                            this.form.append(propertiesControls);
                        }
                    },
                    changeProperty: function changeProperty(ctrl) {
                        throw new Error('Must be implemented by subclass!');
                    },
                    getGeneralAttrs: function getGeneralAttrs() {
                        throw new Error('Must be implemented by subclass!');
                    },
                    getAttrs: function getAttrs() {
                        throw new Error('Must be implemented by subclass!');
                    },
                    save: function save() {
                        throw new Error('Must be implemented by subclass!');
                    },
                    display: function display(isNew) {
                        throw new Error('Must be implemented by subclass!');
                    },
                    generatePropertiesControls: function generatePropertiesControls(properties, curAttrs) {
                        var $this = this;
                        var id, form, legend, label, description, descriptionStr;
                        var propertiesControls = [];
                        $.each(properties, function (name, props) {
                            id = parent.MP.Utils.uniqid();
                            props.value = curAttrs.hasOwnProperty(name) && curAttrs[name].hasOwnProperty('value') ? curAttrs[name].value : props['default'];
                            legend = CE.CtrlTemplates.createLegend(props.legend);
                            label = CE.CtrlTemplates.createLabel(props.label);
                            descriptionStr = props.description;
                            if (props.hasOwnProperty('additional_description')) {
                                descriptionStr += ' ' + props.additional_description;
                            }
                            description = CE.CtrlTemplates.createDescription(descriptionStr);
                            var wrapper = $('<div />', {
                                'data-motopress-parameter': name,
                                'data-motopress-disabled': props.disabled
                            });
                            var controlSettings = {
                                name: name,
                                propLabel: props.label,
                                dependency: props.hasOwnProperty('dependency') ? props.dependency : false,
                                disabled: props.disabled === 'true',
                                isNew: $this.formsMainCtrl.isNew,
                                shortcode: $this.formsMainCtrl.shortcode,
                                formCtrl: $this
                            };
                            switch (props.type) {
                            case 'text':
                                form = CE.CtrlTemplates.createInput(props, name);
                                form.ce_ctrl_input($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'text-hidden':
                                form = CE.CtrlTemplates.createInput(props, name, 'hidden');
                                form.ce_ctrl_input($.extend(controlSettings, {}));
                                wrapper.append(form);
                                break;
                            case 'link':
                                form = CE.CtrlTemplates.createLink(props, name);
                                form.ce_ctrl_link($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'spinner':
                                form = CE.CtrlTemplates.createInput(props, name, null, 'spinner');
                                form.ce_ctrl_spinner($.extend(controlSettings, {
                                    min: props.min,
                                    max: props.max,
                                    step: props.step
                                }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'longtext':
                                form = CE.CtrlTemplates.createTextarea(props);
                                form.ce_ctrl_textarea($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'longtext64':
                                form = CE.CtrlTemplates.createTextarea(props);
                                form.ce_ctrl_textarea64($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'longtext-table':
                                form = CE.CtrlTemplates.createTextarea(props);
                                form.ce_ctrl_textarea_table($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'longtext-tinymce':
                                form = CE.CtrlTemplates.createTextareaTinyMCE(props);
                                form.ce_ctrl_textarea_tinymce($.extend(controlSettings, { currentShortcode: $this.formsMainCtrl.element }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'image':
                                props.src = props.value;
                                form = CE.CtrlTemplates.createImage(props);
                                form.ce_ctrl_image($.extend(controlSettings, {
                                    autoOpen: props.autoOpen,
                                    pseudoRender: name === 'parallax_image' && ($this.shortcodeName === 'mp_row' || $this.shortcodeName === 'mp_row_inner') ? true : false
                                }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'image-src':
                                props.src = props.value;
                                form = CE.CtrlTemplates.createImage(props);
                                form.ce_ctrl_image($.extend(controlSettings, {
                                    returnMode: 'src',
                                    autoOpen: props.autoOpen,
                                    pseudoRender: name === 'parallax_image' && ($this.shortcodeName === 'mp_row' || $this.shortcodeName === 'mp_row_inner') ? true : false
                                }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'audio':
                                form = CE.CtrlTemplates.createMedia(props);
                                var thisTitle = $this.formsMainCtrl.child.attr('data-audio-title');
                                form.ce_ctrl_audio($.extend(controlSettings, {
                                    audioTitle: thisTitle    
                                }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'media':
                                var mediaReturnMode = typeof props.returnMode === 'undefined' || $.inArray(props.returnMode, [
                                    'id',
                                    'url'
                                ]) === -1 ? 'url' : props.returnMode;
                                var value = props.value;
                                form = CE.CtrlTemplates.createMedia(props);
                                form.ce_ctrl_media($.extend(controlSettings, {
                                    returnMode: mediaReturnMode,
                                    value: value
                                }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'media-video':
                                form = CE.CtrlTemplates.createMedia(props);
                                form.ce_ctrl_media_video($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'multi-images':
                                form = CE.CtrlTemplates.createButton(props, null, 'default');
                                form.ce_ctrl_image_slider($.extend(controlSettings, { autoOpen: props.autoOpen }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'video':
                                form = CE.CtrlTemplates.createVideo(props, name);
                                form.ce_ctrl_video($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'checkbox':
                                form = CE.CtrlTemplates.createCheckbox(props);
                                form.ce_ctrl_checkbox($.extend(controlSettings, {}));
                                label.addClass('motopress-property-checkbox-label');
                                wrapper.append(legend, form, label, description);
                                break;
                            case 'group-checkbox':
                                form = CE.CtrlTemplates.createCheckbox(props);
                                form.ce_ctrl_group_checkbox($.extend(controlSettings, {}));
                                label.addClass('motopress-property-checkbox-label');
                                wrapper.append(legend, form, label, description);
                                break;
                            case 'select':
                                form = CE.CtrlTemplates.createSelect(props);
                                form.ce_ctrl_select($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'select-multiple':
                                form = CE.CtrlTemplates.createSelectMultiple(props);
                                form.ce_ctrl_select_multiple($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'color-select':
                                form = CE.CtrlTemplates.createColorSelect(props);
                                form.ce_ctrl_select($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'editor-button':
                                form = CE.CtrlTemplates.createButton(props, null, 'default');
                                form.ce_ctrl_editor_button($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'color-picker':
                                form = CE.CtrlTemplates.createColorPicker(props, name);
                                form.ce_ctrl_color_picker($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'group':
                                var shortcodeCtrl = $this.formsMainCtrl.shortcode.control(IframeCE.Controls), childName = props.contains;
                                shortcodeCtrl.setGroupItemName(name);
                                shortcodeCtrl.setChildName(childName);
                                if (props.hasOwnProperty('activeParameter') && props.activeParameter)
                                    shortcodeCtrl.setActiveParameter(props.activeParameter);
                                form = CE.CtrlTemplates.createGroup(props);
                                var accordion = form.children('.motopress-property-group-accordion');
                                form.ce_ctrl_group($.extend(controlSettings, {
                                    currentSpan: $this.formsMainCtrl.element,
                                    label: props.hasOwnProperty('items') ? props.items.label : null,
                                    rules: props.hasOwnProperty('rules') ? props.rules : null,
                                    events: props.hasOwnProperty('events') ? props.events : null,
                                    activeParameter: props.hasOwnProperty('activeParameter') ? props.activeParameter : null
                                }));
                                $this.formsMainCtrl.shortcode.attr('data-motopress-wrap-render', 'true');
                                wrapper.attr('data-motopress-grouped', 'true');
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'slider':
                                form = CE.CtrlTemplates.createSlider(props);
                                form.ce_ctrl_slider($.extend(controlSettings, {
                                    min: typeof props.min !== 'undefined' && !isNaN(props.min) ? props.min : 0,
                                    max: typeof props.max !== 'undefined' && !isNaN(props.max) ? props.max : 100,
                                    step: typeof props.step !== 'undefined' && !isNaN(props.step) ? props.step : 1,
                                    value: props.value
                                }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'radio-buttons':
                                form = CE.CtrlTemplates.createRadioButtons(props);
                                form.ce_ctrl_radio_buttons($.extend(controlSettings, { value: props.value }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'icon-picker':
                                form = CE.CtrlTemplates.createIconPicker(props);
                                form.ce_ctrl_icon_picker($.extend(controlSettings, { value: props.value }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'datetime-picker':
                                var returnMode = typeof props.returnMode === 'undefined' ? 'YYYY-MM-DD H:m:s' : props.returnMode;
                                var displayMode = typeof props.displayMode === 'undefined' || $.inArray(props.displayMode, [
                                    'date',
                                    'datetime'
                                ]) === -1 ? 'datetime' : props.displayMode;
                                form = CE.CtrlTemplates.createDateTimePicker(props);
                                form.ce_ctrl_date_time_picker($.extend(controlSettings, {
                                    value: props.value,
                                    returnMode: returnMode,
                                    displayMode: displayMode
                                }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'margin':
                                form = CE.CtrlTemplates.createMargin(props);
                                form.ce_ctrl_margin($.extend(controlSettings, { margin: props }));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'select2':
                                form = CE.CtrlTemplates.createInput(props, name);
                                form.ce_ctrl_select2($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'style_editor':
                                form = CE.CtrlTemplates.createInput(props, name);
                                form.ce_ctrl_style_editor($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;
                            case 'gradient-picker':
                                form = CE.CtrlTemplates.createComplexCtrl(props, name);
                                form.ce_ctrl_gradient($.extend(controlSettings, {}));
                                wrapper.append(legend, label, form, description);
                                break;    
                            }
                            var formCtrl = form.control(CE.Ctrl);
                            formCtrl.afterInit();
                            propertiesControls.push(wrapper);
                            if (props.hasOwnProperty('disabled')) {
                                var disabled = props.disabled === 'true';
                                if (disabled) {
                                    var disabledDiv = null;
                                    if (props.type === 'group') {
                                        disabledDiv = form.find('> .motopress-property-button-wrapper > .motopress-property-disabled');
                                    } else {
                                        disabledDiv = $('<div />', { 'class': 'motopress-property-disabled' }).appendTo(wrapper);
                                    }
                                    if (!disabledDiv.data('popover')) {
                                        disabledDiv.mppopover({
                                            'placement': 'top',
                                            'trigger': 'manual',
                                            'container': wrapper,
                                            'content': localStorage.getItem('CELiteTooltipText')
                                        });
                                    }
                                    disabledDiv.on('click', function () {
                                        $(this).mppopover('show');
                                        var t = setTimeout(function () {
                                            disabledDiv.mppopover('hide');
                                            clearTimeout(t);
                                        }, 2000);
                                    });
                                }
                            }
                            form.addClass('motopress-controls');
                            form.attr('id', id);
                            label.attr('for', id);
                        });
                        return propertiesControls;
                    },
                    resolveDependencies: function resolveDependencies() {
                        var $this = this;
                        var ctrl, dependencyCtrl;
                        $.each(this.getGeneralAttrs(), function (name, props) {
                            ctrl = $this.getCtrlByName(name);
                            if (ctrl.dependency) {
                                dependencyCtrl = $this.getCtrlByName(ctrl.dependency.parameter);
                                if (dependencyCtrl.name === ctrl.dependency.parameter) {
                                    if (ctrl.isShouldBeHiddenByDependency()) {
                                        ctrl.hide();
                                    } else {
                                        ctrl.show();
                                    }
                                }
                            }
                        });
                    },
                    getParameterElByName: function getParameterElByName(name) {
                        var el = this.form.find('> [data-motopress-parameter="' + name + '"] > .motopress-controls, > [data-motopress-parameter="' + name + '"] > :not(".motopress-property-legend, .motopress-property-label, .motopress-property-description, hr") > .motopress-controls');
                        return el.length ? el : false;
                    },
                    getCtrlByName: function getCtrlByName(name) {
                        var el = this.getParameterElByName(name);
                        return el ? el.control(CE.Ctrl) : false;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.ControlsForm('CE.SettingsControlsForm', {}, {
                    init: function init(el, args) {
                        this._super(el, args);
                    },
                    restoreClone: function restoreClone(args) {
                        this._super(args);
                    },
                    getGeneralAttrs: function getGeneralAttrs() {
                        return CE.WidgetsLibrary.myThis.getParametersAttrs(this.shortcodeGroup, this.shortcodeName);
                    },
                    getAttrs: function getAttrs() {
                        var attrs = this.shortcode.attr('data-motopress-parameters');
                        return attrs ? $.parseJSON(attrs) : false;
                    },
                    setAttrs: function setAttrs(attrs) {
                        this.shortcode.attr('data-motopress-parameters', JSON.stringify(attrs));
                    },
                    setDefaultAttrs: function setDefaultAttrs(attrs) {
                        if (null !== attrs) {
                            var JSONParameters = $.parseJSON(attrs);
                            var generalAttrs = this.getGeneralAttrs();
                            var $this = this;
                            $.each(JSONParameters, function (name, values) {
                                if (typeof values.value === 'undefined' || !values.value) {
                                    if (generalAttrs[name].hasOwnProperty('saveInContent') && generalAttrs[name].saveInContent === 'true') {
                                        $this.shortcode.attr('data-motopress-content', generalAttrs[name]['default']);
                                    } else {
                                        values.value = generalAttrs[name]['default'];
                                    }
                                }
                            });
                            this.setAttrs(JSONParameters);
                            return JSON.stringify(JSONParameters);
                        } else {
                            return attrs;
                        }
                    },
                    regenerateGroupControl: function regenerateGroupControl(propertyName) {
                        var attrs = this.getAttrs();
                        if (attrs) {
                            var generalAttrs = this.getGeneralAttrs();
                            var obj = {};
                            obj[propertyName] = generalAttrs[propertyName];
                            generalAttrs = obj;
                            var settingsPropertiesControls = this.generatePropertiesControls(generalAttrs, attrs);
                            this.form.append(settingsPropertiesControls);
                        }
                        var oldWrapper = this.form.children('[data-motopress-parameter="' + propertyName + '"]:first');
                        var newWrapper = this.form.children('[data-motopress-parameter="' + propertyName + '"]:last');
                        oldWrapper.replaceWith(newWrapper);
                    },
                    changeProperty: function changeProperty(ctrl) {
                        this.save(ctrl);
                    },
                    save: function save(ctrl) {
                        if (typeof ctrl !== 'undefined' && typeof ctrl.name !== 'undefined') {
                            var $this = this;
                            var attrs = this.getAttrs();
                            if (attrs) {
                                var generalAttrs = this.getGeneralAttrs();
                                var props = generalAttrs[ctrl.name];
                                var value = ctrl.get();
                                if (props.hasOwnProperty('saveInContent') && props.saveInContent === 'true') {
                                    this.shortcode.attr('data-motopress-content', value);
                                } else {
                                    attrs[ctrl.name].value = value !== undefined && typeof value === 'string' ? value.replace(new RegExp(/"/g), '\'').replace(new RegExp(/[\[\]]/g), '') : value;
                                }
                                this.setAttrs(attrs);
                                this.formsMainCtrl.renderShortcode();
                                if (this.shortcodeName === 'mp_row' || this.shortcodeName === 'mp_row_inner') {
                                    switch (ctrl.name) {
                                    case 'bg_media_type':
                                        this.setRowMediaBG(value);
                                        break;
                                    case 'parallax_image':
                                    case 'parallax_bg_size':
                                        this.setRowParallaxBGSize();
                                        break;
                                    case 'bg_video_youtube':
                                    case 'bg_video_youtube_cover':
                                        $this.clearRowMediaBG(this.shortcode);
                                        $this.renderYoutubeBG();
                                        break;
                                    case 'bg_video_webm':
                                    case 'bg_video_mp4':
                                    case 'bg_video_ogg':
                                    case 'bg_video_cover':
                                        $this.clearRowMediaBG(this.shortcode);
                                        $this.renderHTML5VideoBG();
                                        break;
                                    case 'stretch':
                                        var stretchClass = $this.getStretchClassByValue(value);
                                        if (stretchClass) {
                                            $this.setRowWidth(stretchClass);
                                        } else {
                                            $this.clearRowWidth();
                                        }
                                        parent.MP.Editor.triggerIfr('Resize');
                                        break;
                                    case 'width_content':
                                        var rowWidth = this.getContentWidthClassByValue(value);
                                        this.setRowWidth(rowWidth);
                                        parent.MP.Editor.triggerIfr('Resize');
                                        break;
                                    case 'full_height':
                                        var fullHeightClass = 'mp-row-fullheight';
                                        if (value === 'true') {
                                            this.shortcode.addClass(fullHeightClass);
                                        } else {
                                            this.shortcode.removeClass(fullHeightClass);
                                        }
                                        parent.MP.Editor.triggerIfr('Resize');
                                        break;    
                                    }
                                    CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.EntitySettingsChanged.NAME, new IframeCE.SceneEvents.EntitySettingsChanged(IframeCE.Grid.ENTITIES.ROW, ctrl.propLabel));
                                }
                            }
                        }
                    },
                    getStretchClassByValue: function getStretchClassByValue(value) {
                        var result = '';
                        switch (value) {
                        case '':
                            break;
                        case 'full':
                            var contentWidth = this.getCtrlByName('width_content').get();
                            result = this.getContentWidthClassByValue(contentWidth);
                            break;
                        case 'fixed':
                            result = 'mp-row-fixed-width';
                            break;
                        }
                        return result;
                    },
                    getContentWidthClassByValue: function getContentWidthClassByValue(value) {
                        var result = '';
                        switch (value) {
                        case '':
                        default:
                            result = 'mp-row-fullwidth';
                            break;
                        case 'full':
                            result = 'mp-row-fullwidth-content';
                            break;
                        case 'fixed':
                            result = 'mp-row-fixed-width-content';
                            break;
                        }
                        return result;
                    },
                    display: function display(isNew, property, change) {
                        if (typeof property === 'undefined') {
                            CE.Panels.SettingsDialog.myThis.settingsTab.children('.motopress-settings-forms').detach();
                            CE.Panels.SettingsDialog.myThis.settingsTab.html(this.form);
                        }
                        var shortcodeAttrs = this.getAttrs();
                        if (shortcodeAttrs) {
                            var $this = this;
                            var el = null;
                            var value = null;
                            var generalAttrs = this.getGeneralAttrs();
                            if (typeof property !== 'undefined') {
                                var obj = {};
                                obj[property] = generalAttrs[property];
                                generalAttrs = obj;
                            }
                            var defaultValue = null;
                            var ctrl = null;
                            $.each(generalAttrs, function (name, props) {
                                el = $this.getParameterElByName(name);
                                if (el.hasClass('motopress-property-group')) {
                                    if (change)
                                        el.trigger('change', isNew);
                                    el.find('.motopress-property-group-accordion-item').each(function () {
                                        var childFormCtrl = $(this).control(CE.ControlsForm);
                                        childFormCtrl.display(isNew);
                                    });
                                    el.trigger('render', isNew);
                                }
                                ctrl = el.control(CE.Ctrl);
                                if (props.hasOwnProperty('saveInContent') && props.saveInContent === 'true') {
                                    if (typeof $this.shortcode.attr('data-motopress-content') !== 'undefined') {
                                        value = $this.shortcode.attr('data-motopress-content').replace(/\[\]/g, '[');
                                    }
                                } else {
                                    value = shortcodeAttrs[ctrl.name].value;
                                }
                                defaultValue = generalAttrs[ctrl.name]['default'];
                                ctrl.set(value, defaultValue, isNew);
                                el.trigger('customize');
                            });
                            this.resolveDependencies();
                        }
                    },
                    renderYoutubeBG: function renderYoutubeBG() {
                        var $this = this;
                        var parameters = typeof this.shortcode.attr('data-motopress-parameters') !== 'undefined' ? this.shortcode.attr('data-motopress-parameters') : null;
                        parameters = $.parseJSON(parameters);
                        $.ajax({
                            url: parent.motopress.ajaxUrl,
                            type: 'POST',
                            dataType: 'text',
                            data: {
                                action: 'motopress_ce_render_youtube_bg',
                                nonce: motopressCE.nonces.motopress_ce_render_youtube_bg,
                                postID: motopressCE.postID,
                                bg_video_youtube: parameters['bg_video_youtube']['value'],
                                bg_video_youtube_cover: parameters['bg_video_youtube_cover']['value'],
                                bg_video_youtube_repeat: parameters['bg_video_youtube_repeat']['value'],
                                bg_video_youtube_mute: parameters['bg_video_youtube_mute']['value']
                            },
                            success: function success(data) {
                                $this.shortcode.addClass('mp-row-video').children('.mpce-row-tools-wrapper').before(data);
                                parent.MP.Editor.triggerIfr('RenderRowYoutubeBG', $this.shortcode);
                            },
                            error: function error(jqXHR) {
                                console.log(jqXHR);
                            }
                        });
                    },
                    renderHTML5VideoBG: function renderHTML5VideoBG() {
                        var $this = this;
                        var parameters = typeof this.shortcode.attr('data-motopress-parameters') !== 'undefined' ? this.shortcode.attr('data-motopress-parameters') : null;
                        parameters = $.parseJSON(parameters);
                        $.ajax({
                            url: parent.motopress.ajaxUrl,
                            type: 'POST',
                            dataType: 'text',
                            data: {
                                action: 'motopress_ce_render_video_bg',
                                nonce: motopressCE.nonces.motopress_ce_render_video_bg,
                                postID: motopressCE.postID,
                                bg_video_mp4: parameters['bg_video_mp4']['value'],
                                bg_video_webm: parameters['bg_video_webm']['value'],
                                bg_video_ogg: parameters['bg_video_ogg']['value'],
                                bg_video_cover: parameters['bg_video_cover']['value'],
                                bg_video_mute: parameters['bg_video_mute']['value'],
                                bg_video_repeat: parameters['bg_video_repeat']['value']
                            },
                            success: function success(data) {
                                $this.shortcode.addClass('mp-row-video').children('.mpce-row-tools-wrapper').before(data);
                                parent.MP.Editor.triggerIfr('RenderRowVideoBG', $this.shortcode);
                            },
                            error: function error(jqXHR) {
                                console.log(jqXHR);
                            }
                        });
                    },
                    setRowWidth: function setRowWidth(widthClass) {
                        this.clearRowWidth();
                        this.shortcode.addClass(widthClass);
                    },
                    clearRowWidth: function clearRowWidth() {
                        var allWidthClasses = 'mp-row-fullwidth mp-row-fullwidth-content mp-row-fixed-width mp-row-fixed-width-content';
                        this.shortcode.removeClass(allWidthClasses);
                        this.shortcode.css({
                            'width': '',
                            'padding-left': '',
                            'margin-left': '',
                            'padding-right': '',
                            'margin-right': ''
                        });
                    },
                    setRowMediaBG: function setRowMediaBG(type) {
                        this.clearRowMediaBG(this.shortcode);
                        switch (type) {
                        case 'disabled':
                            break;
                        case 'video':
                            this.renderHTML5VideoBG();
                            break;
                        case 'youtube':
                            this.renderYoutubeBG();
                            break;
                        case 'parallax':
                            var img = this.form.find('[data-motopress-parameter="parallax_image"] [data-full-src]').length ? this.form.find('[data-motopress-parameter="parallax_image"] [data-full-src]').attr('data-full-src') : null;
                            this.shortcode.addClass('motopress-row-parallax').attr('data-stellar-background-ratio', 0.5);
                            if (img) {
                                this.shortcode.css('background-image', 'url(\'' + img + '\')');
                            }
                            this.setRowParallaxBGSize();
                            CE.Iframe.$.stellar('refresh');
                            break;
                        }
                    },
                    clearRowMediaBG: function clearRowMediaBG(element) {
                        element.children('.mp-video-container').remove();
                        element.removeClass('mp-row-video motopress-row-parallax').css('background-image', '').css('background-position', '').css('background-size', '');
                        element.removeAttr('data-stellar-background-ratio').removeData('stellarBackgroundIsActive stellarBackgroundRatio stellarBackgroundStartingLeft stellarBackgroundStartingTop');
                        CE.Iframe.$.stellar('refresh');
                    },
                    setRowParallaxBGSize: function setRowParallaxBGSize() {
                        var value = this.getCtrlByName('parallax_bg_size').get();
                        value = value === undefined || value === 'normal' ? '' : value;
                        this.shortcode.css('background-size', value);
                    }
                });
            }(jQuery));
            (function ($) {
                CE.SettingsControlsForm('CE.SettingsControlsChildForm', {}, {
                    init: function init(el, args) {
                        this._super(el, args);
                        this.parentFormCtrl = args.parentFormCtrl;
                        this.groupCtrl = args.groupCtrl;
                    },
                    restoreClone: function restoreClone(args) {
                        this._super(args);
                        this.parentFormCtrl = args.parentFormCtrl;
                        this.groupCtrl = args.groupCtrl;
                    },
                    changeProperty: function changeProperty(ctrl) {
                        this.save(ctrl);
                        this.groupCtrl.element.trigger('change');
                    },
                    setDefaultAttrs: function setDefaultAttrs(parameters) {
                        if (null !== parameters) {
                            var JSONParameters = $.parseJSON(parameters);
                            var generalAttrs = this.getGeneralAttrs();
                            var $this = this;
                            $.each(JSONParameters, function (name, values) {
                                if (typeof values.value === 'undefined' || !values.value) {
                                    if ($this.shortcodeName === 'mp_tab' && name === 'id') {
                                        values.value = parent.MP.Utils.uniqid();
                                    } else if (generalAttrs[name].hasOwnProperty('saveInContent') && generalAttrs[name].saveInContent === 'true') {
                                        $this.shortcode.attr('data-motopress-content', generalAttrs[name]['default']);
                                    } else {
                                        values.value = generalAttrs[name]['default'];
                                    }
                                }
                            });
                            this.setAttrs(JSONParameters);
                            return JSON.stringify(JSONParameters);
                        } else {
                            return parameters;
                        }
                    },
                    detectForm: function detectForm() {
                        this.form = this.element.children('.motopress-property-group-accordion-item-content');
                    },
                    save: function save(ctrl) {
                        if (typeof ctrl !== 'undefined' && typeof ctrl.name !== 'undefined') {
                            var shortcodeAttrs = this.getAttrs();
                            if (shortcodeAttrs) {
                                var generalAttrs = this.getGeneralAttrs();
                                var props = generalAttrs[ctrl.name];
                                var value = ctrl.get();
                                if (props.hasOwnProperty('saveInContent') && props.saveInContent === 'true') {
                                    this.shortcode.attr('data-motopress-content', value);
                                } else {
                                    shortcodeAttrs[ctrl.name].value = value !== undefined && typeof value === 'string' ? value.replace(new RegExp(/"/g), '\'').replace(new RegExp(/[\[\]]/g), '') : value;
                                }
                                this.setAttrs(shortcodeAttrs);
                            }
                        }
                    },
                    display: function display(isNew, property) {
                        var shortcodeAttrs = this.getAttrs();
                        if (shortcodeAttrs) {
                            var $this = this;
                            var value = null;
                            var generalAttrs = this.getGeneralAttrs();
                            if (typeof property !== 'undefined') {
                                var obj = {};
                                obj[property] = generalAttrs[property];
                                generalAttrs = obj;
                            }
                            var defaultValue = null;
                            var ctrl = null;
                            $.each(generalAttrs, function (name, props) {
                                ctrl = $this.getCtrlByName(name);
                                if (props.hasOwnProperty('saveInContent') && props.saveInContent === 'true') {
                                    if (typeof $this.shortcode.attr('data-motopress-content') !== 'undefined') {
                                        value = $this.shortcode.attr('data-motopress-content').replace(/\[\]/g, '[');
                                    }
                                } else {
                                    value = shortcodeAttrs[ctrl.name].value;
                                }
                                if (name === 'id' && $this.shortcodeName === 'mp_accordion_item' && typeof value === 'undefined') {
                                    value = $this.shortcode.find('.motopress-accordion-item').attr('id');
                                }
                                defaultValue = generalAttrs[ctrl.name]['default'];
                                ctrl.set(value, defaultValue, isNew);
                            });
                            this.resolveDependencies();
                        }
                    }
                });
            }(jQuery));
            (function ($) {
                CE.ControlsForm('CE.StyleControlsForm', {}, {
                    init: function init(el, args) {
                        this._super(el, args);
                    },
                    restoreClone: function restoreClone(args) {
                        this._super(args);
                    },
                    changeProperty: function changeProperty(ctrl) {
                        this.save(ctrl);
                        $('body').trigger('MPCEObjectStyleChanged', {
                            'objElement': this.shortcode,
                            'objName': this.shortcodeName
                        });
                    },
                    getGeneralAttrs: function getGeneralAttrs() {
                        return CE.WidgetsLibrary.myThis.getStyleAttrs(this.shortcodeGroup, this.shortcodeName);
                    },
                    getAttrs: function getAttrs() {
                        var shortcodeStyle = this.shortcode.attr('data-motopress-styles');
                        if (shortcodeStyle) {
                            shortcodeStyle = $.parseJSON(shortcodeStyle);
                            if (!this.formsMainCtrl.isGrid && shortcodeStyle.hasOwnProperty('margin') && $.isEmptyObject(shortcodeStyle.margin)) {
                                shortcodeStyle = this.fixMarginValue(shortcodeStyle);
                            }
                            return shortcodeStyle;
                        } else {
                            return false;
                        }
                    },
                    fixMarginValue: function fixMarginValue(attrs) {
                        if (this.formsMainCtrl.child.length) {
                            var generalAttrs = this.getGeneralAttrs();
                            var margin = generalAttrs.margin['default'].slice();
                            var classes = this.formsMainCtrl.child.prop('class').split(' ');
                            var marginClasses = classes.filter(function (str) {
                                return generalAttrs.margin.regExp.test(str);
                            });
                            if (marginClasses.length) {
                                $.each(marginClasses, function (i, className) {
                                    var matches = className.match(generalAttrs.margin.regExp);
                                    var side = matches[1];
                                    var value = parseInt(matches[2]);
                                    if (typeof side === 'undefined') {
                                        $.each(margin, function (j, val) {
                                            if (value !== generalAttrs.margin['default'][j]) {
                                                margin[j] = value;
                                            }
                                        });
                                    } else {
                                        var index = $.inArray(side, generalAttrs.margin.sides);
                                        if (index !== -1) {
                                            margin[index] = value;
                                        }
                                    }
                                });
                                attrs.margin.value = margin.join(',');
                                this.setAttrs(attrs);
                                attrs = this.getAttrs();
                            }
                        }
                        return attrs;
                    },
                    setAttrs: function setAttrs(shortcodeStyle) {
                        this.shortcode.attr('data-motopress-styles', JSON.stringify(shortcodeStyle));
                    },
                    setDefaultAttrs: function setDefaultAttrs(styleJSON) {
                        var style = typeof styleJSON === 'undefined' ? this.getAttrs() : $.parseJSON(styleJSON);
                        var styleAttrs = CE.WidgetsLibrary.myThis.getStyleAttrs(this.shortcodeGroup, this.shortcodeName);
                        $.each(style, function (name, values) {
                            if (typeof values.value === 'undefined' || !values.value) {
                                var defaultVal = styleAttrs[name]['default'];
                                switch (styleAttrs[name].type) {
                                case 'margin':
                                    var allNone = true;
                                    $.each(styleAttrs[name]['default'], function (i, val) {
                                        if (val !== styleAttrs[name].values[0])
                                            allNone = false;
                                    });
                                    defaultVal = allNone ? '' : defaultVal.join(',');
                                    break;
                                case 'select2':
                                    var defaultClassesList = [];
                                    if (styleAttrs['mp_style_classes'].hasOwnProperty('default')) {
                                        $.each(styleAttrs['mp_style_classes']['default'], function (i, v) {
                                            defaultClassesList.push(v);
                                        });
                                    }
                                    defaultVal = defaultClassesList.join(' ');
                                    break;
                                }
                                values.value = defaultVal;
                            }
                        });
                        this.setAttrs(style);
                        return JSON.stringify(style);
                    },
                    display: function display(isNew) {
                        CE.Panels.SettingsDialog.myThis.styleTab.children('.motopress-style-forms').detach();
                        CE.Panels.SettingsDialog.myThis.styleTab.html(this.form);
                        this._setValuesToCtrls(isNew);
                    },
                    _setValuesToCtrls: function _setValuesToCtrls(isNew) {
                        var shortcodeStyle = this.getAttrs();
                        if (shortcodeStyle) {
                            var $this = this;
                            var ctrl = null;
                            var el = null;
                            var value = null;
                            var defaultValue = null;
                            var styles = this.getGeneralAttrs();
                            $.each(shortcodeStyle, function (name, props) {
                                el = $this.getParameterElByName(name);
                                ctrl = el.control(CE.Ctrl);
                                value = shortcodeStyle[ctrl.name].value;
                                defaultValue = styles[ctrl.name]['default'];
                                ctrl.set(value, defaultValue, isNew);
                                el.trigger('customize');
                            });
                            this.resolveDependencies();
                        }
                    },
                    save: function save(ctrl) {
                        var shortcodeStyle = this.getAttrs();
                        if (shortcodeStyle) {
                            if (typeof ctrl === 'undefined' && this.formsMainCtrl.isGrid) {
                                var $this = this;
                                $.each(shortcodeStyle, function (name) {
                                    ctrl = $this.getCtrlByName(name);
                                    $this._save(shortcodeStyle, ctrl, false);
                                });
                            } else {
                                this._save(shortcodeStyle, ctrl);
                                IframeCE.Resizer.myThis.updateAllHandles();
                                $(window).trigger('resize');
                            }
                        }
                    },
                    getTargetElement: function getTargetElement(ctrl) {
                        var selectorEl = null;
                        if (this.formsMainCtrl.isGrid) {
                            selectorEl = this.shortcode;
                        } else if (this.formsMainCtrl.child.length) {
                            selectorEl = this.formsMainCtrl.child;
                        }
                        if ((ctrl.name === 'mp_style_classes' || ctrl.name === 'mp_custom_style') && CE.WidgetsLibrary.myThis.getObject(this.shortcodeGroup, this.shortcodeName).styles[ctrl.name].hasOwnProperty('selector')) {
                            var selector = CE.WidgetsLibrary.myThis.getObject(this.shortcodeGroup, this.shortcodeName).styles[ctrl.name].selector;
                            if (selector === false) {
                                selectorEl = null;
                            } else if (selector.length) {
                                selectorEl = selectorEl.find(selector);
                            }
                        }
                        return selectorEl;
                    },
                    _save: function _save(shortcodeStyle, ctrl, change) {
                        if (typeof change === 'undefined')
                            change = true;
                        var oldValue = shortcodeStyle[ctrl.name].value;
                        var value = change ? ctrl.get() : oldValue;
                        if (change) {
                            shortcodeStyle[ctrl.name].value = value;
                            this.setAttrs(shortcodeStyle);
                        }
                        var selectorEl = this.getTargetElement(ctrl);
                        switch (ctrl.name) {
                        case 'margin':
                            var oldMarginClasses = ctrl.getClasses(oldValue);
                            if (oldMarginClasses.length)
                                oldValue = oldMarginClasses;
                            var marginClasses = ctrl.getClasses(value);
                            if (marginClasses.length)
                                value = marginClasses;
                            break;
                        case 'mp_style_classes':
                            if (!change) {
                                if (!$.isEmptyObject(ctrl.options.style.basic)) {
                                    var basic = '';
                                    if ($.isArray(ctrl.options.style.basic)) {
                                        $.each(ctrl.options.style.basic, function (i, val) {
                                            basic += val['class'] + ' ';
                                        });
                                    } else {
                                        basic = ctrl.options.style.basic['class'] + ' ';
                                    }
                                    value = basic + value;
                                }
                            }
                            break;    
                        }
                        if (selectorEl !== null && selectorEl.length) {
                            if (change)
                                selectorEl.removeClass(oldValue);
                            selectorEl.addClass(value);
                        }
                    }
                });
            }(jQuery));
            (function ($) {
                CE.ControlsForm('CE.StyleEditorControlsForm', {}, {
                    emptyStyleValue: '',
                    editedStyleObj: null,
                    loadedAttrs: {},
                    styleEditorCtrl: null,
                    propertiesWrapper: null,
                    statesWrapper: null,
                    actionsWrapper: null,
                    state: null,
                    displayed: false,
                    switcher: false,
                    stateClsName: 'motopress-ce-style-editor-state-active',
                    subStateClsName: 'motopress-ce-style-editor-sub-state-active',
                    beforeInit: function beforeInit() {
                        this._super();
                        this.generateStatesWrapper();
                        this.generatePropertiesWrapper();
                        this.generateActionsWrapper();
                    },
                    init: function init(el, args) {
                        this.styleEditorCtrl = args.styleEditorCtrl;
                        this.switcher = args.switcher;
                        this._super(el, args);
                    },
                    generateStatesWrapper: function generateStatesWrapper() {
                        var $this = this;
                        this.statesWrapper = $('<div />', { 'class': 'motopress-ce-style-editor-states-wrapper' });
                        this.statesWrapper.append($('<a />', {
                            'href': '#',
                            'data-state': 'up',
                            'title': localStorage.getItem('CEDesktopStyle')
                        }), $('<a />', {
                            'href': '#',
                            'data-state': 'tablet',
                            'title': localStorage.getItem('CETabletStyle')
                        }), $('<a />', {
                            'href': '#',
                            'data-state': 'mobile',
                            'title': localStorage.getItem('CEMobileStyle')
                        }), $('<a />', {
                            'href': '#',
                            'data-state': 'hover',
                            'title': localStorage.getItem('CEHoverStyle')
                        }));
                        this.statesWrapper.children('a').on('click', function (e) {
                            e.preventDefault();
                            var state = $(this).attr('data-state');
                            if (state === 'hover') {
                                $this.setState(state);
                            } else {
                                $this.switcher.setMode($this.getStyleModeByState(state));
                            }
                        });
                        this.element.append(this.statesWrapper);
                    },
                    getStyleModeByState: function getStyleModeByState(state) {
                        return state === 'up' ? this.switcher.DESKTOP : state;
                    },
                    getStateByStyleMode: function getStateByStyleMode(mode) {
                        return mode === this.switcher.DESKTOP ? 'up' : mode;
                    },
                    generatePropertiesWrapper: function generatePropertiesWrapper() {
                        this.propertiesWrapper = $('<div />', { 'class': 'motopress-ce-style-editor-properties-wrapper' });
                        this.element.append(this.propertiesWrapper);
                    },
                    generateActionsWrapper: function generateActionsWrapper() {
                        var $this = this;
                        this.actionsWrapper = $('<div />', { 'class': 'motopress-ce-style-editor-actions-wrapper' });
                        var applyBtn = $('<button />', {
                            'class': 'motopress-ce-style-editor-apply-button btn',
                            'text': localStorage.getItem('CEApply')
                        }).on('click', function (e) {
                            e.preventDefault();
                            $this.confirm();
                        });
                        var actionsMenu = $('<ul />', { 'class': 'dropdown-menu' });
                        $.each(this.generateActionButtons(), function (index, $btn) {
                            var $li = $('<li />');
                            $li.append($btn).appendTo(actionsMenu);
                        });
                        var dropdownBtnWrapper = $('<div />', { 'class': 'btn-group motopress-bootstrap-dropdown motopress-dropdown-button dropup' }).append(applyBtn, '<button class="dropdown-toggle btn" data-toggle="dropdown"><span class="caret"></span></button>', actionsMenu);
                        this.actionsWrapper.append(dropdownBtnWrapper);
                        this.element.append(this.actionsWrapper);
                        dropdownBtnWrapper.on('keydown', function (e) {
                            e.stopPropagation();
                        });
                    },
                    generateActionButtons: function generateActionButtons() {
                        var $this = this;
                        return [$('<a />', {
                                'href': '#',
                                'class': 'motopress-ce-style-editor-cancel-button',
                                'text': localStorage.getItem('CECancel')
                            }).on('click', function (e) {
                                e.preventDefault();
                                $this.cancel();
                            })];
                    },
                    generate: function generate() {
                        var generalAttrs = this.getGeneralAttrs();
                        var propertiesControls = this.generatePropertiesControls(generalAttrs, {});
                        this.form.append(propertiesControls);
                    },
                    detectForm: function detectForm() {
                        this.form = this.propertiesWrapper;
                    },
                    display: function display(isNew) {
                        if (!this.displayed) {
                            this.displayed = true;
                            this.listenToStyleSwitcher();
                        }
                        var curState = this.getStateByStyleMode(this.switcher.getCurMode());
                        this.attachForm();
                        curState = this.highlightState(curState);
                        this.rememberSettings();
                        var stateAttrs = this.getStateAttrs(curState);
                        this.fillForm(stateAttrs, 'customize');
                        this.resolveDependencies();
                        this.show();
                    },
                    listenToStyleSwitcher: function listenToStyleSwitcher() {
                        CE.EventDispatcher.Dispatcher.addListener(CE.StyleModeEvents.Switched.NAME, this.proxy('styleModeSwitched'));
                    },
                    styleModeSwitched: function styleModeSwitched(event) {
                        var switcher = event.getSwitcher();
                        var state = this.getStateByStyleMode(switcher.getCurMode());
                        this.setState(state);
                    },
                    attachForm: function attachForm() {
                        CE.Panels.SettingsDialog.myThis.styleEditorContainer.children().detach();
                        CE.Panels.SettingsDialog.myThis.styleEditorContainer.html(this.element);
                    },
                    getState: function getState() {
                        return this.state;
                    },
                    setState: function setState(state) {
                        state = this.highlightState(state);
                        this.fillForm(this.getStateAttrs(state), false);
                    },
                    highlightState: function highlightState(state) {
                        if (state === 'hover') {
                            this.highlightHoverState(state);
                        } else {
                            this.highlightNormalState(state);
                        }
                        return this.getState();
                    },
                    highlightNormalState: function highlightNormalState(state) {
                        var $states = this.statesWrapper.children('a');
                        $states.removeClass(this.stateClsName).removeClass(this.subStateClsName);
                        $states.filter('[data-state="' + state + '"]').addClass(this.stateClsName);
                        this.state = state;
                        this.styleEditorCtrl.unsetStatePreview();
                    },
                    highlightHoverState: function highlightHoverState(state) {
                        var $state = this.statesWrapper.children('a[data-state="hover"]');
                        if ($state.hasClass(this.subStateClsName)) {
                            this.highlightNormalState(this.getStateByStyleMode(this.switcher.getCurMode()));
                        }    
                        else {
                            $state.addClass(this.subStateClsName);
                            this.state = state;
                            this.styleEditorCtrl.setStatePreview(state);
                        }
                    },
                    setEditedStyleObj: function setEditedStyleObj(editedStyleObj) {
                        this.editedStyleObj = editedStyleObj;
                    },
                    rememberSettings: function rememberSettings() {
                        this.loadedAttrs = this.editedStyleObj.getSettings();
                    },
                    getFormValues: function getFormValues() {
                        var ctrl, value;
                        var values = {};
                        var $this = this;
                        var generalAttrs = this.getGeneralAttrs();
                        $.each(generalAttrs, function (name, details) {
                            ctrl = $this.getCtrlByName(name);
                            if (!ctrl.isHided()) {
                                value = ctrl.get();
                                value = $this.filterValue(name, value);
                                if (value !== $this.emptyStyleValue) {
                                    values[name] = value;
                                }
                            }
                        });
                        if (values.hasOwnProperty('background-image-type') && values['background-image-type'] !== 'none') {
                            if (!values.hasOwnProperty('background-gradient') && !values.hasOwnProperty('background-image')) {
                                delete values['background-image-type'];
                            }
                        }
                        return values;
                    },
                    getEmptyValues: function getEmptyValues() {
                        var generalAttrs = this.getGeneralAttrs();
                        var $this = this;
                        return $.map(generalAttrs, function () {
                            return $this.emptyStyleValue;
                        });
                    },
                    getCleanFormValues: function getCleanFormValues(state) {
                        var formValues = this.getFormValues();
                        var inheritValues = this.getInheritValues(state);
                        var originalValues = parent.MP.Utils.getObjectChanges(inheritValues, formValues);
                        var $this = this;
                        $.each(originalValues, function (name, value) {
                            $this.preventLossDependencyValues(name, originalValues, formValues);
                        });
                        return originalValues;
                    },
                    preventLossDependencyValues: function preventLossDependencyValues(name, originalValues, formValues) {
                        var ctrl = this.getCtrlByName(name);
                        if (ctrl.dependency && ctrl.dependency.hasOwnProperty('needDependenceValue') && ctrl.dependency.needDependenceValue && !originalValues.hasOwnProperty(ctrl.dependency.parameter)) {
                            originalValues[ctrl.dependency.parameter] = formValues[ctrl.dependency.parameter];
                            this.preventLossDependencyValues(ctrl.dependency.parameter, originalValues, formValues);
                        }
                    },
                    filterValue: function filterValue(name, value) {
                        switch (name) {
                        case 'padding-top':
                        case 'padding-bottom':
                        case 'padding-left':
                        case 'padding-right':
                        case 'border-top-width':
                        case 'border-bottom-width':
                        case 'border-left-width':
                        case 'border-right-width':
                        case 'border-top-left-radius':
                        case 'border-top-right-radius':
                        case 'border-bottom-left-radius':
                        case 'border-bottom-right-radius':
                            value = value.replace(/[^0-9]/gi, '');
                            break;
                        case 'margin-top':
                        case 'margin-bottom':
                        case 'margin-left':
                        case 'margin-right':
                            value = value.replace(/[^0-9-]/gi, '');
                            break;
                        case 'background-position-x':
                        case 'background-position-y':
                            if (value === null) {
                                value = '';
                            }
                            break;
                        }
                        return value;
                    },
                    fillForm: function fillForm(attrs, event) {
                        var $this = this;
                        var el, ctrl, value, defaultValue;
                        var generalAttrs = this.getGeneralAttrs();
                        $.each(generalAttrs, function (name, props) {
                            el = $this.getParameterElByName(name);
                            ctrl = el.control(CE.Ctrl);
                            defaultValue = generalAttrs[ctrl.name].hasOwnProperty('default') ? generalAttrs[ctrl.name]['default'] : $this.emptyStyleValue;
                            value = attrs.hasOwnProperty(name) ? attrs[name] : defaultValue;
                            ctrl.set(value, defaultValue, false);
                            if (event) {
                                el.trigger(event);
                            }
                        });
                        this.resolveDependencies();
                    },
                    changeProperty: function changeProperty(ctrl) {
                        this.save();
                        this.styleEditorCtrl.element.trigger('change');
                    },
                    save: function save() {
                        var state = this.getState();
                        var stateSettings = this.getCleanFormValues(state);
                        this.editedStyleObj.updateState(state, stateSettings);
                    },
                    getTitle: function getTitle() {
                        return '';
                    },
                    show: function show() {
                        CE.Panels.SettingsDialog.myThis.setMode('style-editor');
                        this.propertiesWrapper.scrollTop(0);
                    },
                    hide: function hide() {
                        CE.Panels.SettingsDialog.myThis.setMode('tabs');
                        this.styleEditorCtrl.unsetStatePreview();
                    },
                    cancel: function cancel() {
                        this.editedStyleObj.update(this.loadedAttrs);
                        this.styleEditorCtrl.element.trigger('change');
                        this.hide();
                    },
                    confirm: function confirm() {
                        this.styleEditorCtrl.element.trigger('change');
                        this.hide();
                        var settingsChanged = !can.Object.same(this.loadedAttrs, this.editedStyleObj.getSettings());
                        if (settingsChanged) {
                            this.confirmed();
                        }
                    },
                    confirmed: function confirmed() {
                    }
                });
            }(jQuery));
            (function ($) {
                CE.StyleEditorControlsForm('CE.PrivateStyleControlsForm', {}, {
                    presetStyleObj: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        this.editedStyleObj = args.editedStyleObj;
                    },
                    setPresetStyleObj: function setPresetStyleObj(presetStyleObj) {
                        this.presetStyleObj = presetStyleObj;
                    },
                    generateActionButtons: function generateActionButtons() {
                        var $this = this;
                        var inheritBtns = this._super();
                        var btns = [
                            $('<a />', {
                                'href': '#',
                                'class': 'motopress-ce-style-editor-save-button',
                                'text': localStorage.getItem('CESaveAs')
                            }).on('click', function (e) {
                                e.preventDefault();
                                $this.saveAsPreset();
                            }),
                            $('<a />', {
                                'href': '#',
                                'class': 'motopress-ce-style-editor-clear-button',
                                'text': localStorage.getItem('CEClear')
                            }).on('click', function (e) {
                                e.preventDefault();
                                $this.clear();
                            })
                        ];
                        return $.merge(btns, inheritBtns);
                    },
                    getGeneralAttrs: function getGeneralAttrs() {
                        return parent.CE.WidgetsLibrary.myThis.getPrivateStyleAttrs(this.shortcodeGroup, this.shortcodeName);
                    },
                    getPrivateStateAttrs: function getPrivateStateAttrs(state) {
                        return this.editedStyleObj.getStateSettings(state);
                    },
                    getPresetStateAttrs: function getPresetStateAttrs(state) {
                        return this.presetStyleObj !== null ? this.presetStyleObj.getStateSettings(state) : {};
                    },
                    getStateAttrs: function getStateAttrs(state) {
                        if (typeof state === 'undefined') {
                            state = this.getState();
                        }
                        var attrs = {};
                        switch (state) {
                        case 'up':
                            $.extend(attrs, this.getPresetStateAttrs('up'), this.getPrivateStateAttrs('up'));
                            break;
                        case 'hover':
                            $.extend(attrs, this.getPresetStateAttrs('up'), this.getPresetStateAttrs('hover'), this.getPrivateStateAttrs('up'), this.getPrivateStateAttrs('hover'));
                            break;
                        case 'tablet':
                            $.extend(attrs, this.getPresetStateAttrs('up'), this.getPresetStateAttrs('tablet'), this.getPrivateStateAttrs('up'), this.getPrivateStateAttrs('tablet'));
                            break;
                        case 'mobile':
                            $.extend(attrs, this.getPresetStateAttrs('up'), this.getPresetStateAttrs('tablet'), this.getPresetStateAttrs('mobile'), this.getPrivateStateAttrs('up'), this.getPrivateStateAttrs('tablet'), this.getPrivateStateAttrs('mobile'));
                            break;
                        }
                        return attrs;
                    },
                    resetForm: function resetForm() {
                        this.fillForm(this.getStateAttrs(), false);
                        this.styleEditorCtrl.element.trigger('change');
                    },
                    getInheritValues: function getInheritValues(state) {
                        var inheritValues = {};
                        var emptyValues = this.getEmptyValues();
                        switch (state) {
                        case 'up':
                            $.extend(inheritValues, emptyValues, this.getPresetStateAttrs('up'));
                            break;
                        case 'hover':
                            $.extend(inheritValues, emptyValues, this.getPresetStateAttrs('up'), this.getPresetStateAttrs('hover'), this.getPrivateStateAttrs('up'));
                            break;
                        case 'tablet':
                            $.extend(inheritValues, emptyValues, this.getPresetStateAttrs('up'), this.getPresetStateAttrs('tablet'), this.getPrivateStateAttrs('up'));
                            break;
                        case 'mobile':
                            $.extend(inheritValues, emptyValues, this.getPresetStateAttrs('up'), this.getPresetStateAttrs('tablet'), this.getPresetStateAttrs('mobile'), this.getPrivateStateAttrs('tablet'), this.getPrivateStateAttrs('up'));
                            break;
                        }
                        return inheritValues;
                    },
                    saveAsPreset: function saveAsPreset(el, e) {
                        var $this = this;
                        $.when(IframeCE.StyleEditor.myThis.presetSaveModal.showModal(this.editedStyleObj, this.presetStyleObj)).then(
                        function (presetId) {
                            $this.styleEditorCtrl.selectPreset(presetId);
                            $this.clear();
                            $this.rememberSettings();
                            $this.styleEditorCtrl.refresh();
                            parent.CE.Panels.SettingsDialog.myThis.updateTitle();
                        });
                    },
                    clear: function clear() {
                        this.editedStyleObj.clear();
                        this.resetForm();
                    },
                    getTitle: function getTitle() {
                        var title = 'Element Style';
                        if (this.presetStyleObj !== null) {
                            title += ' (inherit from ' + this.presetStyleObj.getLabel() + ')';
                        }
                        return title;
                    },
                    show: function show() {
                        this._super();
                    },
                    hide: function hide() {
                        this._super();
                    },
                    confirmed: function confirmed() {
                        var shortcodeLabel = this.shortcode.control(IframeCE.Shortcode).shortcodeLabel;
                        CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.EntityStylesChanged.NAME, new IframeCE.SceneEvents.EntityStylesChanged(shortcodeLabel, '[Styles]'));
                    }
                });
            }(jQuery));
            (function ($) {
                CE.StyleEditorControlsForm('CE.PresetStyleControlsForm', {}, {
                    generateActionButtons: function generateActionButtons() {
                        var $this = this;
                        var inheritBtns = this._super();
                        var btns = [
                            $('<a />', {
                                'href': '#',
                                'class': 'motopress-ce-style-editor-rename-button',
                                'text': localStorage.getItem('CERename')
                            }).on('click', function (e) {
                                e.preventDefault();
                                $this.renamePreset();
                            }),
                            $('<a />', {
                                'href': '#',
                                'class': 'motopress-ce-style-editor-delete-button',
                                'text': localStorage.getItem('CEDelete')
                            }).on('click', function (e) {
                                e.preventDefault();
                                $this.deletePreset();
                            })
                        ];
                        return $.merge(btns, inheritBtns);
                    },
                    getGeneralAttrs: function getGeneralAttrs() {
                        return IframeCE.Style.getStyleEditorProps();
                    },
                    getAttrs: function getAttrs() {
                        return this.editedStyleObj.getSettings();
                    },
                    getStateAttrs: function getStateAttrs(state) {
                        var attrs = {};
                        switch (state) {
                        case 'up':
                            $.extend(attrs, this.editedStyleObj.getStateSettings('up'));
                            break;
                        case 'hover':
                            $.extend(attrs, this.editedStyleObj.getStateSettings('up'), this.editedStyleObj.getStateSettings('hover'));
                            break;
                        case 'tablet':
                            $.extend(attrs, this.editedStyleObj.getStateSettings('up'), this.editedStyleObj.getStateSettings('tablet'));
                            break;
                        case 'mobile':
                            $.extend(attrs, this.editedStyleObj.getStateSettings('up'), this.editedStyleObj.getStateSettings('tablet'), this.editedStyleObj.getStateSettings('mobile'));
                            break;
                        }
                        return attrs;
                    },
                    getInheritValues: function getInheritValues(state) {
                        var inheritValues = {};
                        var emptyValues = this.getEmptyValues();
                        switch (state) {
                        case 'up':
                            $.extend(inheritValues, emptyValues);
                            break;
                        case 'hover':
                            $.extend(inheritValues, emptyValues, this.editedStyleObj.getStateSettings('up'));
                            break;
                        case 'tablet':
                            $.extend(inheritValues, emptyValues, this.editedStyleObj.getStateSettings('up'));
                            break;
                        case 'mobile':
                            $.extend(inheritValues, emptyValues, this.editedStyleObj.getStateSettings('up'), this.editedStyleObj.getStateSettings('tablet'));
                            break;
                        }
                        return inheritValues;
                    },
                    renamePreset: function renamePreset(el, e) {
                        var presetLabel = this.editedStyleObj.getLabel();
                        var renamePresetPromptText = localStorage.getItem('CERenamePresetPrompt').replace('%presetLabel%', presetLabel);
                        var newLabel = prompt(renamePresetPromptText, presetLabel);
                        if (newLabel !== null) {
                            newLabel = newLabel.trim();
                            if (newLabel !== '') {
                                this.editedStyleObj.setLabel(newLabel);
                                parent.CE.Panels.SettingsDialog.myThis.updateTitle();
                                this.styleEditorCtrl.refresh();
                                CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.StylePresetRenamed.NAME, new IframeCE.SceneEvents.StylePresetRenamed(this.editedStyleObj));
                            } else {
                                parent.MP.Flash.setFlash(localStorage.getItem('CERenamePresetEmptyError'), 'error');
                                parent.MP.Flash.showMessage();
                            }
                        }
                    },
                    deletePreset: function deletePreset(el, e) {
                        var presetLabel = this.editedStyleObj.getLabel();
                        var deletePresetConfirmText = localStorage.getItem('CEDeletePresetConfirm').replace('%presetLabel%', presetLabel);
                        var isDelete = confirm(deletePresetConfirmText);
                        if (isDelete) {
                            IframeCE.StyleEditor.myThis.deletePreset(this.editedStyleObj.getId());
                            this.styleEditorCtrl.refresh();
                            this.hide();
                            CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.StylePresetDeleted.NAME, new IframeCE.SceneEvents.StylePresetDeleted(this.editedStyleObj));
                        }
                    },
                    getTitle: function getTitle() {
                        var title = 'Preset "' + this.editedStyleObj.getLabel() + '"';
                        return title;
                    },
                    confirmed: function confirmed() {
                        CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.StylePresetChanged.NAME, new IframeCE.SceneEvents.StylePresetChanged(this.editedStyleObj));
                    }
                });
            }(jQuery));
            (function ($) {
                CE.ControlsForm('CE.ControlsSubForm', {}, {
                    parentPropertyCtrl: null,
                    init: function init(el, args) {
                        this.parentPropertyCtrl = args.parentPropertyCtrl;
                        this._super(el, args);
                    },
                    getGeneralAttrs: function getGeneralAttrs() {
                        return this.parentPropertyCtrl.getParameters();
                    },
                    getAttrs: function getAttrs() {
                        return {};
                    },
                    save: function save() {
                        this.parentPropertyCtrl.element.trigger('change');
                    },
                    display: function display(isNew) {
                        var $this = this;
                        var generalAttrs = this.getGeneralAttrs();
                        var el;
                        $.each(generalAttrs, function (name, props) {
                            el = $this.getParameterElByName(name);
                            el.trigger('customize');
                        });
                    },
                    changeProperty: function changeProperty(ctrl) {
                        this.save();
                    },
                    set: function set(formData) {
                        var generalAttrs = this.getGeneralAttrs();
                        var ctrl, value;
                        var $this = this;
                        $.each(generalAttrs, function (name, details) {
                            ctrl = $this.getCtrlByName(name);
                            value = formData.hasOwnProperty(name) ? formData[name] : '';
                            ctrl.set(value);
                        });
                        this.resolveDependencies();
                    },
                    get: function get() {
                        var ctrl, value;
                        var values = {};
                        var $this = this;
                        var generalAttrs = this.getGeneralAttrs();
                        $.each(generalAttrs, function (name, details) {
                            ctrl = $this.getCtrlByName(name);
                            if (!ctrl.isHided()) {
                                value = ctrl.get();
                                values[name] = value;
                            }
                        });
                        return values;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Panels.AbstractDialog.extend('CE.Panels.SettingsDialog', 
                {
                    myThis: null,
                    NAME: 'settings'
                }, 
                {
                    dialogClass: 'motopress-dialog',
                    wrapper: null,
                    shortcode: null,
                    oldShortcode: null,
                    dialog: null,
                    minimizeRestoreBtn: $('<button />', { 'class': 'ui-dialog-titlebar-minimize' }),
                    state: {
                        minimized: 'minimized',
                        restored: 'restored'
                    },
                    tabs: $('<div />', { 'class': 'motopress-dialog-tabs' }),
                    settingsTitle: $('<li />'),
                    styleTitle: $('<li />'),
                    settingsTab: $('<div />', { id: 'motopress-dialog-settings-tab' }),
                    styleTab: $('<div />', { id: 'motopress-dialog-style-tab' }),
                    styleEditorContainer: $('<div />', {
                        id: 'motopress-style-editor-container',
                        'class': 'motopress-hide'
                    }),
                    mode: 'tabs',
                    setup: function setup(el, args) {
                        this._super(el, args);
                        var ul = $('<ul />');
                        $('<a />', {
                            href: '#' + this.settingsTab.attr('id'),
                            'class': 'motopress-text-no-color-text',
                            text: localStorage.getItem('CESettings')
                        }).append($('<i />', { 'class': 'motopress-settings-icon' })).appendTo(this.settingsTitle);
                        $('<a />', {
                            href: '#' + this.styleTab.attr('id'),
                            'class': 'motopress-text-no-color-text',
                            text: localStorage.getItem('CEStyle')
                        }).append($('<i />', { 'class': 'motopress-style-icon' })).appendTo(this.styleTitle);
                        ul.append(this.settingsTitle, this.styleTitle);
                        this.tabs.append(ul, this.settingsTab, this.styleTab).appendTo(this.element);
                        this.styleEditorContainer.appendTo(this.element);
                    },
                    'setMode': function setMode(mode) {
                        switch (mode) {
                        case 'tabs':
                            this.styleEditorContainer.addClass('motopress-hide');
                            this.tabs.removeClass('motopress-hide');
                            break;
                        case 'style-editor':
                            this.tabs.addClass('motopress-hide');
                            this.styleEditorContainer.removeClass('motopress-hide');
                            break;
                        }
                        this.mode = mode;
                        this.updateTitle();
                    },
                    updateTitle: function updateTitle() {
                        var shortcodeCtrl = this.shortcode.control(IframeCE.Controls);
                        switch (this.getMode()) {
                        case 'tabs':
                            this.setTitle(shortcodeCtrl.shortcodeLabel);
                            break;
                        case 'style-editor':
                            var styleEditorCtrl = this.styleEditorContainer.children().control(CE.StyleEditorControlsForm);
                            this.setTitle(shortcodeCtrl.shortcodeLabel + ' - ' + styleEditorCtrl.getTitle());
                            break;
                        }
                    },
                    getMode: function getMode() {
                        return this.mode;
                    },
                    init: function init(el, args) {
                        this._super(el, args);
                        CE.Panels.SettingsDialog.myThis = this;
                        var dialog = null;
                        var isHandleN = false;
                        var scrollTop = 0;
                        var jqVersion = parent.MP.Utils.version_compare($.fn.jquery, '1.9.0', '<');
                        var position = {
                            my: 'right-20 top+20',
                            at: 'right top',
                            of: window
                        };
                        var width = sessionStorage.getItem('motopressDialogWidth');
                        if (width === null)
                            width = 300;
                        var height = sessionStorage.getItem('motopressDialogHeight');
                        if (height === null)
                            height = 400;
                        $.ui.dialog.prototype._focusTabbable = function () {
                        };
                        this.element.dialog({
                            autoOpen: false,
                            closeOnEscape: true,
                            closeText: '',
                            dialogClass: this.dialogClass,
                            draggable: true,
                            modal: false,
                            position: position,
                            resizable: true,
                            minWidth: 280,
                            width: width,
                            minHeight: 280,
                            height: height,
                            maxHeight: $(window).height(),
                            create: function create() {
                                CE.Panels.SettingsDialog.myThis.dialog = CE.Panels.SettingsDialog.myThis.element.dialog('widget');
                                CE.Panels.SettingsDialog.myThis.wrapper = CE.Panels.SettingsDialog.myThis.element.closest(MP.Utils.convertClassesToSelector(CE.Panels.SettingsDialog.myThis.dialogClass));
                                dialog = CE.Panels.SettingsDialog.myThis.dialog;
                                dialog.on('resizestart', function (e, ui) {
                                    isHandleN = $(e.originalEvent.target).hasClass('ui-resizable-n');
                                    scrollTop = $(document).scrollTop();
                                    if (jqVersion) {
                                        if (!isHandleN)
                                            $(this).css('top', parseInt($(this).css('top')) - scrollTop);
                                    }
                                });
                                dialog.on('resize', function (e, ui) {
                                    if (jqVersion && isHandleN)
                                        $(this).css('top', parseInt($(this).css('top')) - scrollTop);
                                });
                                dialog.on('resizestop', function (e, ui) {
                                    CE.Panels.SettingsDialog.myThis.savePosition(ui);
                                    CE.Panels.SettingsDialog.myThis.saveSize(ui);
                                });
                                $(this).prev('.ui-dialog-titlebar').find('.ui-dialog-titlebar-close').before(CE.Panels.SettingsDialog.myThis.minimizeRestoreBtn);
                                var draggableCancelOption = dialog.draggable('option', 'cancel') + ', .ui-dialog-titlebar-minimize, .ui-dialog-titlebar-restore';
                                dialog.draggable('option', 'cancel', draggableCancelOption);
                                CE.Panels.SettingsDialog.myThis.minimizeRestoreBtn.on('click', function () {
                                    if ($(this).hasClass('ui-dialog-titlebar-minimize')) {
                                        CE.Panels.SettingsDialog.myThis.minimize();
                                    } else {
                                        CE.Panels.SettingsDialog.myThis.restore();
                                    }
                                });
                            },
                            dragStart: function dragStart(e, ui) {
                                IframeCE.LeftBar.myThis.disable();
                            },
                            dragStop: function dragStop(e, ui) {
                                CE.Panels.SettingsDialog.myThis.savePosition(ui);
                                IframeCE.LeftBar.myThis.enable();
                            },
                            close: function close() {
                                if (CE.Panels.SettingsDialog.myThis.getMode() === 'style-editor') {
                                    var formStyleEditorCtrl = CE.Panels.SettingsDialog.myThis.styleEditorContainer.children().control(CE.StyleEditorControlsForm);
                                    formStyleEditorCtrl.confirm();
                                }
                                CE.Panels.SettingsDialog.myThis.setTitle();
                                IframeCE.Selectable.focusWithoutScroll(IframeCE.Selectable.getFocusAreaBySelected(CE.Panels.SettingsDialog.myThis.shortcode));
                            },
                            open: function open() {
                                IframeCE.Selectable.focusWithoutScroll(IframeCE.Selectable.getFocusAreaBySelected(CE.Panels.SettingsDialog.myThis.shortcode));
                                if (sessionStorage.getItem('motopressDialogState') === 'minimized') {
                                    dialog.css('left', 'auto');
                                } else {
                                    var dLeft = parseInt(sessionStorage.getItem('motopressDialogLeft'));
                                    var dTop = parseInt(sessionStorage.getItem('motopressDialogTop'));
                                    var dWidth = dialog.width(), dHeight = dialog.height();
                                    var wWidth = $(window).width(), wHeight = $(window).height();
                                    if (dHeight > wHeight) {
                                        dHeight = wHeight;
                                        dialog.height(dHeight);
                                    }
                                    if (dTop < 0)
                                        dialog.css('top', 0);
                                    else if (dHeight < wHeight && dTop + dHeight > wHeight)
                                        dialog.css('top', wHeight - dHeight);
                                    else if (dTop + dHeight > wHeight)
                                        dialog.css('top', wHeight - dHeight);
                                    else
                                        dialog.css('top', dTop);
                                    if (dLeft < 0)
                                        dialog.css('left', 0);
                                    else if (dWidth < wWidth && dLeft + dWidth > wWidth)
                                        dialog.css('left', wWidth - dWidth);
                                    else
                                        dialog.css('left', dLeft);
                                }
                            },
                            resizeStart: function resizeStart() {
                                IframeCE.LeftBar.myThis.disable();
                            },
                            resizeStop: function resizeStop() {
                                IframeCE.LeftBar.myThis.enable();
                            }
                        });
                        $(window).on('resize.CE.Panels.SettingsDialog', this.proxy('onResize'));
                        MP.Utils.fixTabsBaseTagConflict(this.tabs, document, location);
                        this.tabs.tabs({
                            activate: function activate(e, ui) {
                                CE.Panels.SettingsDialog.myThis.shortcode.attr('data-motopress-dialog-tab', ui.newTab.index());
                            }
                        });
                    },
                    _destroy: function _destroy() {
                        $(window).off('resize.CE.Panels.SettingsDialog');
                    },
                    onResize: function onResize(e) {
                        if (!$(e.target).is(this.element.dialog('widget'))) {
                            this.element.dialog('option', 'maxHeight', $(window).height());
                        }
                    },
                    open: function open(selectHandle) {
                        this.oldShortcode = this.shortcode;
                        this.shortcode = IframeCE.Selectable.myThis.getShortcodeByHandle(selectHandle);
                        var sameDialog = MP.Utils.isTheSameElement(this.shortcode, this.oldShortcode);
                        var ctrl = this.shortcode.control(IframeCE.Controls);
                        if (CE.WidgetsLibrary.myThis.getLibrary() !== null && typeof ctrl.group !== 'undefined' && typeof ctrl.shortcodeName !== 'undefined') {
                            var object = CE.WidgetsLibrary.myThis.getObject(ctrl.group, ctrl.shortcodeName);
                            if (object) {
                                var isEmptyParameters = $.isEmptyObject(object.parameters);
                                var styleProps = this.isGrid ? 'gridProps' : 'props';
                                if (!isEmptyParameters || !$.isEmptyObject(IframeCE.Style[styleProps])) {
                                    this.setTitle(ctrl.shortcodeLabel);
                                    var isNew = false;
                                    if (this.shortcode.hasClass('motopress-new-object')) {
                                        isNew = true;
                                        this.shortcode.removeClass('motopress-new-object');
                                    }
                                    if (!sameDialog) {
                                        ctrl.display(isNew);
                                    }
                                    if (sessionStorage.getItem('motopressDialogState') === this.state.minimized) {
                                        this.minimize();
                                    }
                                    var activeTab = !isEmptyParameters ? this.settingsTitle.index() : this.styleTitle.index();
                                    var shortcodeTab = this.shortcode.attr('data-motopress-dialog-tab');
                                    if (typeof shortcodeTab !== 'undefined')
                                        activeTab = shortcodeTab;
                                    if (!isEmptyParameters) {
                                        if (this.tabs.tabs('option', 'disabled') !== false)
                                            this.tabs.tabs('enable', this.settingsTitle.index());
                                    } else {
                                        this.tabs.tabs('disable', this.settingsTitle.index());
                                    }
                                    this.tabs.tabs('option', 'active', activeTab);
                                    this._super();
                                    this.element.find('.motopress-controls').trigger('dialogOpen');
                                }
                            }
                        }
                    },
                    setTitle: function setTitle(title) {
                        title = typeof title !== 'undefined' ? title : '';
                        if (typeof title === 'string') {
                            this.element.dialog('option', 'title', title);
                            var widget = this.element.dialog('widget');
                            widget.find('.ui-dialog-title').attr('title', title);
                        }
                    },
                    savePosition: function savePosition(ui) {
                        sessionStorage.setItem('motopressDialogLeft', this.dialog.css('left'));
                        sessionStorage.setItem('motopressDialogTop', this.dialog.css('top'));
                    },
                    saveSize: function saveSize(ui) {
                        sessionStorage.setItem('motopressDialogWidth', ui.size.width);
                        sessionStorage.setItem('motopressDialogHeight', ui.size.height);
                    },
                    minimize: function minimize() {
                        this.minimizeRestoreBtn.removeClass('ui-dialog-titlebar-minimize').addClass('ui-dialog-titlebar-restore');
                        sessionStorage.setItem('motopressDialogState', this.state.minimized);
                        this.dialog.removeClass('motopress-dialog-' + this.state.restored).addClass('motopress-dialog-' + this.state.minimized);
                        var dLeft = $(window).width() - this.dialog.width();
                        this.dialog.css('left', dLeft);
                        sessionStorage.setItem('motopressDialogLeft', this.dialog.css('left'));
                        this.element.dialog('option', 'draggable', false);
                    },
                    restore: function restore() {
                        this.minimizeRestoreBtn.removeClass('ui-dialog-titlebar-restore').addClass('ui-dialog-titlebar-minimize');
                        sessionStorage.setItem('motopressDialogState', this.state.restored);
                        this.element.nextAll().add(this.element).removeClass('motopress-hide');
                        this.dialog.removeClass('motopress-dialog-' + this.state.minimized).addClass('motopress-dialog-' + this.state.restored);
                        this.element.dialog('option', 'height', 400);
                        this.element.dialog({
                            position: {
                                my: 'center',
                                at: 'center',
                                of: window
                            }
                        });
                        sessionStorage.setItem('motopressDialogLeft', this.dialog.css('left'));
                        sessionStorage.setItem('motopressDialogTop', this.dialog.css('top'));
                        this.element.dialog('option', 'draggable', true);
                    }
                });
            }(jQuery));
            (function ($) {
                CE.PreviewDevice = can.Control.extend(
                { myThis: null }, 
                {
                    mode: null,
                    previewClassPrefix: 'motopress-ce-device-mode-',
                    init: function init(el) {
                        CE.PreviewDevice.myThis = this;
                    },
                    hide: function hide() {
                        this.abortShow();
                        this.element.addClass('motopress-content-editor-preview-device-panel-hide').one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function (el, e) {
                            CE.PreviewDevice.myThis.element.addClass('motopress-hide').removeClass('motopress-content-editor-preview-device-panel-hide');
                        });
                    },
                    isHiding: function isHiding() {
                        return this.element.hasClass('motopress-content-editor-preview-device-panel-hide');
                    },
                    show: function show() {
                        this.abortHide();
                        this.element.addClass('motopress-content-editor-preview-device-panel-show').removeClass('motopress-hide').one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function (el, e) {
                            CE.PreviewDevice.myThis.element.removeClass('motopress-content-editor-preview-device-panel-show');
                        });
                    },
                    abortShow: function abortShow() {
                        this.element.removeClass('motopress-content-editor-preview-device-panel-show').off('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd');
                    },
                    abortHide: function abortHide() {
                        this.element.removeClass('motopress-content-editor-preview-device-panel-hide').off('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd');
                    },
                    '.motopress-content-editor-preview-mode-btn click': function motopressContentEditorPreviewModeBtnClick(el, e) {
                        if (this.isHiding()) {
                            this.show();
                            CE.Panels.Navbar.myThis.hide();
                        }
                        var mode = $(el).attr('data-mode');
                        this.preview(mode);
                    },
                    '.motopress-content-editor-preview-edit click': function motopressContentEditorPreviewEditClick(el, e) {
                        this.unsetPreview();
                        this.hide();
                        CE.Panels.Navbar.myThis.show();
                    },
                    preview: function preview(mode) {
                        if (this.mode === null) {
                            IframeCE.Selectable.myThis.unselect();
                            IframeCE.StyleEditor.myThis.unsetEmulateCSSMode();
                            IframeCE.InlineEditor.destroyAll();
                            IframeCE.Utils.addSceneAction('device-preview');
                            CE.Iframe.myThis.unsetMinWidth();
                        }
                        if (mode !== this.mode) {
                            var oldModeClass = this.mode !== null ? this.previewClassPrefix + this.mode : '';
                            var modeClass = this.previewClassPrefix + mode;
                            CE.Iframe.myThis.element.removeClass(oldModeClass).addClass(modeClass);
                            CE.Panels.Navbar.myThis.editorWrapperEl.addClass('motopress-ce-full-height');
                            CE.Iframe.myThis.body.removeClass(oldModeClass).addClass(modeClass);
                            IframeCE.Utils.triggerWindowEvent('resize');
                            this.mode = mode;
                        }
                    },
                    unsetPreview: function unsetPreview() {
                        var previewClass = this.previewClassPrefix + this.mode;
                        CE.Iframe.myThis.element.removeClass(previewClass);
                        IframeCE.StyleEditor.myThis.setEmulateCSSMode();
                        IframeCE.InlineEditor.reinitAll();
                        CE.Panels.Navbar.myThis.editorWrapperEl.removeClass('motopress-ce-full-height');
                        CE.Iframe.myThis.body.removeClass(previewClass);
                        CE.Iframe.myThis.setMinWidth();
                        IframeCE.Utils.removeSceneAction('device-preview');
                        this.mode = null;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.StyleModeEvents = {};
                CE.StyleModeEvents.Switched = CE.EventDispatcher.Event.extend({ NAME: 'style_mode.switched' }, {
                    switcher: null,
                    init: function init(switcher) {
                        this.switcher = switcher;
                    },
                    getSwitcher: function getSwitcher() {
                        return this.switcher;
                    }
                });
            }(jQuery));
            (function ($) {
                var curMode = null;
                var prevMode = null;
                CE.StyleModeSwitcher = can.Construct.extend({
                    DESKTOP: 'desktop',
                    TABLET: 'tablet',
                    MOBILE: 'mobile',
                    setMode: function setMode(mode) {
                        prevMode = curMode;
                        curMode = mode;
                        CE.EventDispatcher.Dispatcher.dispatch(CE.StyleModeEvents.Switched.NAME, new CE.StyleModeEvents.Switched(this));
                    },
                    getCurMode: function getCurMode() {
                        return curMode;
                    },
                    getPrevMode: function getPrevMode() {
                        return prevMode;
                    },
                    getModeList: function getModeList() {
                        return [
                            this.DESKTOP,
                            this.TABLET,
                            this.MOBILE
                        ];
                    }
                }, {
                    init: function init(mode) {
                        curMode = mode;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.ResponsiveSwitcher = can.Control.extend({}, {
                    switcher: null,
                    modeClassPrefix: 'mpce-device-mode-',
                    init: function init(element, options) {
                        this.switcher = options.switcher;
                        this.displayMode(this.switcher.getCurMode());
                        CE.EventDispatcher.Dispatcher.addListener(CE.StyleModeEvents.Switched.NAME, this.proxy('modeSwitched'));
                    },
                    'change': function change($el, e) {
                        this.switcher.setMode($el.val());
                    },
                    displayMode: function displayMode(mode) {
                        this.element.find('[value="' + mode + '"]').attr('selected', 'selected');
                    },
                    modeSwitched: function modeSwitched(event) {
                        var prevMode = this.switcher.getPrevMode();
                        var curMode = this.switcher.getCurMode();
                        if (curMode === this.switcher.DESKTOP) {
                            CE.Iframe.myThis.setMinWidth();
                        } else {
                            CE.Iframe.myThis.unsetMinWidth();
                        }
                        var prevModeClass = this.modeClassPrefix + prevMode;
                        var modeClass = this.modeClassPrefix + curMode;
                        CE.Iframe.myThis.element.removeClass(prevModeClass).addClass(modeClass);
                        CE.Iframe.myThis.body.removeClass(prevModeClass).addClass(modeClass);
                        IframeCE.Utils.triggerWindowEvent('resize');
                        this.displayMode(curMode);
                    }
                });
            }(jQuery));
            (function ($) {
                var AbstractSaving = can.Construct({}, {
                    canSave: function canSave() {
                        throw new Error('Must be implemented in sub-class!');
                    },
                    save: function save() {
                        throw new Error('Must be implemented in sub-class!');
                    }
                });
                var NavbarSaving = AbstractSaving.extend({}, {
                    navbar: null,
                    init: function init() {
                        MP.Editor.one('SceneInited', this.proxy(function () {
                            this.navbar = CE.Panels.Navbar.myThis;
                        }));
                    },
                    canSave: function canSave() {
                        return true;
                    },
                    save: function save() {
                        this.navbar.save.apply(this.navbar, arguments);
                    }
                });
                var WpRevisionSaving = AbstractSaving.extend({}, {
                    revision: null,
                    init: function init() {
                        MP.Editor.one('SceneInited', this.proxy(function () {
                            MP.Editor.oneIfr('WPRevision.Init', this.proxy(function (e, data) {
                                this.revision = data.control;
                            }));
                        }));
                    },
                    canSave: function canSave() {
                        return this.revision.isPreview();
                    },
                    save: function save() {
                        this.revision.save();
                    }
                });
                CE.Saving = can.Construct({
                    strategies: [],
                    init: function init() {
                        this.strategies = new can.List([
                            new WpRevisionSaving(),
                            new NavbarSaving()    
                        ]);
                    },
                    save: function save() {
                        var args = arguments;
                        this.strategies.each(
                        function (strategy) {
                            if (strategy.canSave()) {
                                strategy.save.apply(strategy, args);
                                return false;
                            }
                        });
                    }
                }, {});
            }(jQuery));
            (function ($) {
                CE.ContentParser = can.Construct({}, {
                    content: '',
                    init: function init(element) {
                        var content = this._getSources(element);
                        this.content = content.replace(/\n\n$/, '');
                    },
                    _getSources: function _getSources(dom, level) {
                        var src = '';
                        if (typeof level === 'undefined')
                            level = 1;
                        dom.each(this.proxy(function (index, el) {
                            var $el = $(el);
                            if ($el.hasClass('motopress-content-wrapper')) {
                                src += this._getSources($el.children('.motopress-row, .mpce-wp-more-tag'), level);
                            }    
                            else if ($el.hasClass('motopress-row')) {
                                var rowSlug = parent.CE.Iframe.myThis.getRowSlug(level);
                                var $rowEdge = parent.MP.Utils.getEdgeRow($el);
                                src += '[' + rowSlug + this._getAttributes($el) + ']\n\n';
                                src += this._getSources($rowEdge.children('.motopress-clmn'), level);
                                src += '[/' + rowSlug + ']\n\n';
                            }    
                            else if ($el.hasClass('motopress-clmn')) {
                                var $spanEdge = MP.Utils.getEdgeSpan($el), spanClass = MP.Utils.getSpanClass($el.prop('class').split(' ')), col = MP.Utils.getSpanNumber(spanClass), style = '', clmnData = parent.CE.Iframe.myThis.getClmnSlug(level, col), _level = MP.Utils.isSpanWrapper($el) ? level + 1 : level;
                                var minHeight = $el.get(0).style.minHeight;
                                if (minHeight.length) {
                                    var minHeightInt = parseInt(minHeight);
                                    if (!isNaN(minHeightInt) && minHeightInt !== IframeCE.Resizer.myThis.minHeight && minHeightInt !== IframeCE.Resizer.myThis.spaceMinHeight) {
                                        style = ' style="min-height: ' + minHeight + ';"';
                                    }
                                }
                                src += '[' + clmnData.slug + clmnData.attr + style + this._getAttributes($el) + ']\n\n';
                                src += this._getSources($spanEdge.find('> .motopress-row, > .motopress-block-content > [data-motopress-shortcode]'), _level);
                                src += '[/' + clmnData.slug + ']\n\n';
                            }    
                            else if ($el.is('[data-motopress-shortcode]')) {
                                src += this._getShortcode($el);
                            }    
                            else if (level === 1 && $el.hasClass('mpce-wp-more-tag')) {
                                src += '<!--more-->';
                            }
                        }));
                        return src;
                    },
                    _getShortcode: function _getShortcode(shortcode) {
                        var src = '';
                        if (shortcode.length) {
                            var shortcodeSrc = '';
                            var name = shortcode.attr('data-motopress-shortcode');
                            var closeType = shortcode.attr('data-motopress-close-type');
                            var unwrap = typeof shortcode.attr('data-motopress-unwrap') !== 'undefined';
                            var start = '';
                            var end = '';
                            if (closeType === 'enclosed') {
                                var content = shortcode.attr('data-motopress-content');
                                content = !content ? '' : content.replace(/\[\]/g, '[');
                                shortcodeSrc += content + '\n\n';
                                end = '[/' + name + ']\n\n';
                            }
                            if (!unwrap || !this._isEmptyStyles(shortcode)) {
                                start = '[' + name + this._getAttributes(shortcode) + ']\n\n';
                                shortcodeSrc = start + shortcodeSrc + end;
                            }
                            src += shortcodeSrc;
                        }
                        return src;
                    },
                    _getAttributes: function _getAttributes(obj) {
                        var attributes = {};
                        var parameters = obj.attr('data-motopress-parameters') ? JSON.parse(obj.attr('data-motopress-parameters')) : {};
                        var styles = obj.attr('data-motopress-styles') ? JSON.parse(obj.attr('data-motopress-styles')) : {};
                        $.extend(attributes, parameters, styles);
                        var result = '';
                        if (attributes) {
                            var shortcodeGroup = obj.attr('data-motopress-group'), shortcodeName = obj.attr('data-motopress-shortcode');
                            var shortcodeParameters = CE.WidgetsLibrary.myThis.getObject(shortcodeGroup, shortcodeName).parameters;
                            if (shortcodeName === parent.CE.Iframe.myThis.gridObj.span.shortcode || shortcodeName === parent.CE.Iframe.myThis.gridObj.span.inner) {
                                if (obj.hasClass('motopress-empty')) {
                                    if (!attributes.hasOwnProperty(parent.CE.Iframe.myThis.gridObj.span.custom_class_attr)) {
                                        attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr] = {};
                                    }
                                    if (!attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].hasOwnProperty('value')) {
                                        attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].value = '';
                                    }
                                    attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].value = attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].value + ' motopress-empty mp-hidden-phone';
                                }
                                if (obj.hasClass('motopress-space')) {
                                    if (!attributes.hasOwnProperty(parent.CE.Iframe.myThis.gridObj.span.custom_class_attr)) {
                                        attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr] = {};
                                    }
                                    if (!attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].hasOwnProperty('value')) {
                                        attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].value = '';
                                    }
                                    attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].value = attributes[parent.CE.Iframe.myThis.gridObj.span.custom_class_attr].value + ' motopress-space';
                                }
                            }
                            $.each(attributes, this.proxy(function (attName, attrs) {
                                attrs = this._filterShortcodeAttribute(attrs, attName, {
                                    name: shortcodeName,
                                    parameters: shortcodeParameters,
                                    attributes: attributes
                                });
                                if (typeof attrs.value !== 'undefined' && attrs.value !== '') {
                                    result += ' ' + attName + '="' + attrs.value + '"';
                                }
                            }));
                        }
                        return result;
                    },
                    _filterShortcodeAttribute: function _filterShortcodeAttribute(attDetails, attName, shortcodeAtts) {
                        if (shortcodeAtts.parameters.hasOwnProperty(attName) && shortcodeAtts.parameters[attName].hasOwnProperty('saveInContent') && shortcodeAtts.parameters[attName].saveInContent == 'true') {
                            attDetails.value = '';
                        }
                        if ($.inArray(shortcodeAtts.name, [
                                'mp_row',
                                'mp_row_inner'
                            ]) !== -1) {
                            if (shortcodeAtts.parameters.hasOwnProperty(attName) && typeof shortcodeAtts.parameters[attName].disabled !== 'undefined' && shortcodeAtts.parameters[attName].disabled === 'true' && $.inArray(attName, [
                                    'bg_video_youtube',
                                    'bg_video_youtube_cover',
                                    'bg_video_webm',
                                    'bg_video_mp4',
                                    'bg_video_ogg',
                                    'bg_video_cover',
                                    'parallax_image',
                                    'parallax_bg_size'
                                ]) !== -1) {
                                attDetails.value = '';
                                return attDetails;
                            }
                            if (attName === 'full_height' && shortcodeAtts.parameters.hasOwnProperty(attName) && shortcodeAtts.parameters[attName]['default'] === attDetails.value) {
                                attDetails.value = '';
                                return attDetails;
                            }
                            if (shortcodeAtts.parameters.hasOwnProperty(attName) && shortcodeAtts.parameters[attName].dependency && shortcodeAtts.attributes[shortcodeAtts.parameters[attName].dependency.parameter].value !== shortcodeAtts.parameters[attName].dependency.value && (typeof shortcodeAtts.parameters[attName].disabled === 'undefined' || shortcodeAtts.parameters[attName].disabled === 'false')) {
                                attDetails.value = '';
                                return attDetails;
                            }
                        }
                        return attDetails;
                    },
                    _isEmptyStyles: function _isEmptyStyles(shortcodeObj) {
                        var isEmpty = true;
                        var styles = shortcodeObj.attr('data-motopress-styles') ? JSON.parse(shortcodeObj.attr('data-motopress-styles')) : {};
                        $.each(styles, function (name, attrs) {
                            if (typeof attrs.value !== 'undefined' && attrs.value !== '') {
                                isEmpty = false;
                                return;
                            }
                        });
                        return isEmpty;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.ContentTemplateParser = CE.ContentParser({}, {
                    replaceableStyles: [],
                    _filterShortcodeAttribute: function _filterShortcodeAttribute(attDetails, attName, shortcodeAtts) {
                        attDetails = this._super(attDetails, attName, shortcodeAtts);
                        if (attName == 'mp_custom_style' && attDetails.hasOwnProperty('value')) {
                            var privateClass = IframeCE.StyleEditor.myThis.retrievePrivateClass(attDetails.value);
                            if (privateClass) {
                                var privateClassInstance = IframeCE.StyleEditor.myThis.getPrivateStyleInstance(privateClass, shortcodeAtts.name);
                                var replaceableClassPrefix = 'mpce-replaceable-prvt-';
                                var newClass = replaceableClassPrefix + this.replaceableStyles.length;
                                this.replaceableStyles.push({
                                    name: newClass,
                                    settings: privateClassInstance.getSettings()
                                });
                                attDetails.value = attDetails.value.replace(privateClass, newClass);
                            }
                        }
                        return attDetails;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Save = can.Construct({
                    postChanged: false,
                    saveAJAX: function saveAJAX(status) {
                        this.triggerBeforeSave();
                        var content = this.getContent();
                        if (status) {
                            CE.Settings.Page.attr('status', status);
                        }
                        var data = {
                            action: 'motopress_ce_save_post',
                            nonce: motopressCE.nonces.motopress_ce_save_post,
                            postID: motopressCE.postID,
                            content: content,
                            motopresscecontent: content,
                            title: CE.Settings.Page.attr('title'),
                            hideTitle: CE.Settings.Page.attr('hideTitle'),
                            template: CE.Settings.Page.attr('template'),
                            status: CE.Settings.Page.attr('status'),
                            'motopress-ce-edited-post': motopressCE.postID,
                            'motopress-ce-preset-styles-last-id': IframeCE.StyleEditor.myThis.getPresetsLastId(),
                            'motopress-ce-preset-styles': IframeCE.StyleEditor.myThis.getPresetsString(),
                            '_motopress-ce-private-styles': IframeCE.StyleEditor.myThis.getPrivateStylesString()
                        };
                        return $.ajax({
                            url: parent.motopress.ajaxUrl,
                            type: 'POST',
                            dataType: 'json',
                            data: data,
                            success: this.proxy(function (response) {
                                if ($.isArray(response.data) && !response.data.length) {
                                    response.data = {};
                                }
                                if (response.data.hasOwnProperty('post_status')) {
                                    CE.Settings.Page.attr('status', response.data.post_status);
                                }
                                MP.Editor.triggerEverywhere('AfterUpdate', response.data);    
                            }),
                            error: function error(jqXHR) {
                                console.log(jqXHR);
                                MP.Editor.triggerEverywhere('SaveError', jqXHR);
                            },
                            complete: this.proxy(function (jqXHR) {
                                MP.Editor.triggerEverywhere('SaveComplete', jqXHR);
                            })
                        }).done(this.makeContentNonDirty);
                    },
                    triggerBeforeSave: function triggerBeforeSave() {
                        MP.Editor.triggerEverywhere('BeforeSave');
                    },
                    makeContentNonDirty: function makeContentNonDirty() {
                    },
                    getContent: function getContent() {
                        var content = '';
                        var rootElement = CE.Iframe.myThis.getContentRootElement();
                        if (rootElement.length) {
                            var parser = new CE.ContentParser(rootElement);
                            content = parser.content;
                        }
                        return content;
                    },
                    changeContent: function changeContent(action) {
                        this.postChanged = true;
                        MP.Editor.triggerEverywhere('ContentChange', action);
                    },
                    setContentNotChanged: function setContentNotChanged() {
                        this.postChanged = false;
                    },
                    isContentChanged: function isContentChanged() {
                        return this.postChanged;
                    },
                    isEmptyStyles: function isEmptyStyles(shortcodeObj) {
                        var isEmpty = true;
                        var styles = shortcodeObj.attr('data-motopress-styles') ? JSON.parse(shortcodeObj.attr('data-motopress-styles')) : {};
                        $.each(styles, function (name, attrs) {
                            if (typeof attrs.value !== 'undefined' && attrs.value !== '') {
                                isEmpty = false;
                                return;
                            }
                        });
                        return isEmpty;
                    }
                }, {});
            }(jQuery));
            (function ($) {
                MP.Settings = can.Construct(
                {
                    siteUrl: null,
                    debug: null,
                    adminUrl: null,
                    pluginRootUrl: null,
                    pluginName: null,
                    pluginDirUrl: null,
                    palettes: null,
                    lang: null,
                    langName: null,
                    loadScriptsUrl: null,
                    spellcheck: null,
                    removeWpPanels: function removeWpPanels() {
                        $('#footer, #adminmenuwrap, #adminmenuback, .update-nag').remove();
                        $('#wpcontent').css('margin-left', 0);
                        $('#wpbody-content').css('padding-bottom', 0);
                        $('#wpfooter').remove();
                    },
                    getSiteUrl: function getSiteUrl() {
                        var href = window.location.href;
                        var hrefLen = href.indexOf('/wp-admin/');
                        this.siteUrl = href.substr(0, hrefLen);
                    },
                    getWpSettings: function getWpSettings() {
                        var data = motopressCE.settings.wp;
                        MP.Settings.debug = data.debug;
                        MP.Settings.adminUrl = data.admin_url;
                        MP.Settings.pluginRootUrl = data.plugin_root_url;
                        MP.Settings.pluginName = data.plugin_name;
                        MP.Settings.pluginDirUrl = data.plugin_dir_url;
                        MP.Settings.palettes = data.palettes;
                        MP.Settings.licenseType = data.license_type;
                        MP.Settings.lang = data.lang;
                        MP.Settings.loadScriptsUrl = data.load_scripts_url;
                        MP.Settings.spellcheck = data.spellcheck == '1';
                        if (MP.Utils.Browser.IE || MP.Utils.Browser.Opera) {
                            window.location = MP.Settings.adminUrl + '?page=' + MP.Settings.pluginName;
                        }
                    }
                }, 
                {
                    setup: function setup() {
                        if (typeof CE === 'undefined')
                            this.removeWpPanels();
                        MP.Settings.getSiteUrl();
                        MP.Settings.getWpSettings();
                    }
                });
            }(jQuery));
            (function ($) {
                can.Construct('MP.Language', {}, {
                    init: function init() {
                        var restoreVars = {};
                        for (var storedVar in localStorage) {
                            if (storedVar.indexOf('WP_DATA_USER_') == 0) {
                                restoreVars[storedVar] = localStorage.getItem(storedVar);
                            }
                        }
                        localStorage.clear();
                        for (var restoreVar in restoreVars) {
                            localStorage.setItem(restoreVar, restoreVars[restoreVar]);
                        }
                        $.each(motopressCE.settings.translations, function (key, value) {
                            localStorage.setItem(key, value);
                        });
                        MP.Preloader.myThis.load(MP.Language.shortName);
                    }
                });
            }(jQuery));
            (function ($) {
                CE.ImageLibrary = can.Construct(
                { myThis: null }, 
                {
                    frame: null,
                    propertyImage: null,
                    init: function init() {
                        CE.ImageLibrary.myThis = this;
                        this.frame = wp.media({
                            id: 'motopress-image-library',
                            multiple: false,
                            describe: false,
                            toolbar: 'select',
                            sidebar: 'settings',
                            content: 'upload',
                            router: 'browse',
                            menu: 'default',
                            searchable: true,
                            filterable: false,
                            sortable: false,
                            title: localStorage.getItem('CEImageLibraryText'),
                            button: { text: localStorage.getItem('CEImageLibraryText') },
                            library: { type: 'image' },
                            contentUserSetting: true,
                            syncSelection: true
                        });
                        this.frame.on('open', this.proxy('onOpen'));
                        this.frame.on('select', this.proxy('setImage'));
                        this.frame.on('close', this.proxy('onClose'));
                    },
                    onOpen: function onOpen() {
                        var frame = this.frame;
                        frame.reset();
                        this.propertyImage = CE.Panels.SettingsDialog.myThis.element.find('[data-motopress-open-img-lib="1"]');
                        var imgCtrl = this.propertyImage.control(CE.Ctrl);
                        var ids = imgCtrl.get();
                        if (ids !== null && ids !== '') {
                            if (!$.isArray(ids))
                                ids = [ids];
                            ids.forEach(function (id) {
                                if (!jQuery.isNumeric(id))
                                    return;
                                var attachment = wp.media.attachment(id);
                                attachment.fetch();
                                frame.state().get('selection').add(attachment);
                            });
                        }
                    },
                    setImage: function setImage() {
                        var attributes = this.frame.state().get('selection').models[0].attributes;
                        var size = 'full';
                        if (attributes.sizes.hasOwnProperty('medium')) {
                            size = 'medium';
                        } else if (attributes.sizes.hasOwnProperty('thumbnail')) {
                            size = 'thumbnail';
                        }
                        var src = attributes.sizes[size].url;
                        var imgCtrl = this.propertyImage.control(CE.Ctrl);
                        var props = {
                            id: attributes.id,
                            src: src,
                            full: attributes.sizes.full.url
                        };
                        imgCtrl.set(props);
                        imgCtrl.showTools();
                        imgCtrl.element.trigger('change');
                        this.propertyImage.removeAttr('data-motopress-open-img-lib');
                    },
                    onClose: function onClose() {
                        CE.Panels.SettingsDialog.myThis.wrapper.focus();
                        var HtmlBody = $(document).find('html, body');
                        HtmlBody.addClass('motopress-ce-jumping-fix');
                        var tJumpFix = setTimeout(function () {
                            HtmlBody.removeClass('motopress-ce-jumping-fix');
                            clearTimeout(tJumpFix);
                        }, 0);
                    }
                });
            }(jQuery));
            (function ($) {
                CE.WPGallery = can.Construct({ myThis: null }, {
                    frame: null,
                    ctrl: null,
                    init: function init() {
                        CE.WPGallery.myThis = this;
                        var media = wp.media, Attachment = media.model.Attachment;
                        media.controller.CEGallery = media.controller.FeaturedImage.extend({
                            defaults: parent._.defaults({
                                id: 'motopress-media-library-gallery',
                                title: localStorage.getItem('CEWpGalleryText'),
                                toolbar: 'main-insert',
                                filterable: 'uploaded',
                                library: media.query({ type: 'image' }),
                                multiple: 'add',
                                editable: true,
                                priority: 60,
                                syncSelection: false
                            }, media.controller.Library.prototype.defaults),
                            updateSelection: function updateSelection() {
                                var selection = this.get('selection'), ids = CE.WPGallery.myThis.ctrl.get(), attachments;
                                if ('' !== ids && -1 !== ids) {
                                    attachments = parent._.map(ids.split(/,/), function (id) {
                                        return Attachment.get(id);
                                    });
                                }
                                selection.reset(attachments);
                            }
                        });
                        media.view.MediaFrame.CEGallery = media.view.MediaFrame.Post.extend({
                            createStates: function createStates() {
                                var options = this.options;
                                this.states.add([
                                    new media.controller.CEGallery()]);
                            },
                            bindHandlers: function bindHandlers() {
                                media.view.MediaFrame.Select.prototype.bindHandlers.apply(this, arguments);
                                this.on('toolbar:create:main-insert', this.createToolbar, this);
                                var handlers = {
                                    content: {
                                        'embed': 'embedContent',
                                        'edit-selection': 'editSelectionContent'
                                    },
                                    toolbar: { 'main-insert': 'mainInsertToolbar' }
                                };
                                parent._.each(handlers, function (regionHandlers, region) {
                                    parent._.each(regionHandlers, function (callback, handler) {
                                        this.on(region + ':render:' + handler, this[callback], this);
                                    }, this);
                                }, this);
                            },
                            mainInsertToolbar: function mainInsertToolbar(view) {
                                var controller = this;
                                this.selectionStatusToolbar(view);
                                view.set('insert', {
                                    style: 'primary',
                                    priority: 80,
                                    text: localStorage.getItem('CEWpGalleryText'),
                                    requires: { selection: true },
                                    click: function click() {
                                        var state = controller.state(), selection = state.get('selection');
                                        controller.close();
                                        state.trigger('insert', selection).reset();
                                    }
                                });
                            }
                        });
                        this.frame = new media.view.MediaFrame.CEGallery(parent._.defaults({}, {
                            state: 'motopress-media-library-gallery',
                            title: localStorage.getItem('CEWpGalleryText'),
                            library: { type: 'image' },
                            multiple: true
                        }));
                        this.frame.on('open', this.proxy('onOpen'));
                        this.frame.on('close', this.proxy('onClose'));
                        this.frame.on('insert', this.proxy('setImage'));
                    },
                    open: function open(ctrl) {
                        this.ctrl = ctrl;
                        this.frame.open();
                    },
                    onOpen: function onOpen() {
                        var frame = this.frame;
                        frame.reset();
                        var ids = this.ctrl.getArray();
                        if (ids !== null) {
                            var attachment = null;
                            ids.forEach(function (id) {
                                attachment = wp.media.attachment(id);
                                attachment.fetch();
                                frame.state().get('selection').add(attachment);
                            });
                        }
                    },
                    onClose: function onClose() {
                        CE.Panels.SettingsDialog.myThis.wrapper.focus();
                        var HtmlBody = $(document).find('html, body');
                        HtmlBody.addClass('motopress-ce-jumping-fix');
                        var tJumpFix = setTimeout(function () {
                            HtmlBody.removeClass('motopress-ce-jumping-fix');
                            clearTimeout(tJumpFix);
                        }, 0);
                    },
                    setImage: function setImage() {
                        var ids = [];
                        var models = this.frame.state().get('selection').models;
                        $.each(models, function (key, model) {
                            var attributes = model.attributes;
                            ids.push(attributes.id);
                        });
                        this.ctrl.set(ids);
                        this.ctrl.element.trigger('change');
                    }
                });
            }(jQuery));
            (function ($) {
                CE.WPMedia = can.Construct({ myThis: null }, {
                    frame: null,
                    propertyMedia: null,
                    init: function init() {
                        CE.WPMedia.myThis = this;
                        this.frame = wp.media({
                            id: 'motopress-media-library',
                            multiple: false,
                            describe: false,
                            toolbar: 'select',
                            sidebar: 'settings',
                            content: 'upload',
                            router: 'browse',
                            menu: 'default',
                            searchable: true,
                            filterable: false,
                            sortable: false,
                            title: localStorage.getItem('CEMediaLibraryText'),
                            button: { text: localStorage.getItem('CEMediaLibraryText') },
                            contentUserSetting: true,
                            syncSelection: true
                        });
                        this.frame.on('open', this.proxy('onOpen'));
                        this.frame.on('select', this.proxy('setMedia'));
                        this.frame.on('close', this.proxy('onClose'));
                    },
                    onOpen: function onOpen() {
                        var attachment, dataMediaId;
                        this.propertyMedia = CE.Panels.SettingsDialog.myThis.element.find('[data-motopress-open-media-lib="1"]');
                        this.propertyMediaID = this.propertyMedia.find('.motopress-property-media-id');
                        this.propertyMediaSrc = this.propertyMedia.find('.motopress-property-media');
                        dataMediaId = this.propertyMediaID.val();
                        if (dataMediaId !== null) {
                            attachment = wp.media.attachment(dataMediaId);
                        } else {
                            attachment = wp.media.attachment();
                        }
                        attachment.fetch();
                        this.frame.state().get('selection').add(attachment);
                    },
                    setMedia: function setMedia() {
                        var attributes = this.frame.state().get('selection').models[0].attributes;
                        var mediaCtrl = this.propertyMedia.control(CE.Ctrl);
                        var id = attributes.id;
                        var url = attributes.url;
                        this.propertyMediaID.val(id);
                        this.propertyMediaSrc.val(url);
                        mediaCtrl.element.removeAttr('data-motopress-open-media-lib');
                        mediaCtrl.element.trigger('change');
                    },
                    onClose: function onClose() {
                        CE.Panels.SettingsDialog.myThis.wrapper.focus();
                        this.propertyMedia.removeAttr('data-motopress-open-media-lib');
                        var HtmlBody = $(document).find('html, body');
                        HtmlBody.addClass('motopress-ce-jumping-fix');
                        var tJumpFix = setTimeout(function () {
                            HtmlBody.removeClass('motopress-ce-jumping-fix');
                            clearTimeout(tJumpFix);
                        }, 0);
                    }
                });
            }(jQuery));
            (function ($) {
                CE.WPAudio = can.Construct({ myThis: null }, {
                    frame: null,
                    propertyAudio: null,
                    propertyAudioTitle: null,
                    init: function init() {
                        CE.WPAudio.myThis = this;
                        this.frame = wp.media({
                            id: 'motopress-audio-library',
                            multiple: false,
                            describe: false,
                            toolbar: 'select',
                            sidebar: 'settings',
                            content: 'upload',
                            router: 'browse',
                            menu: 'default',
                            searchable: true,
                            filterable: false,
                            sortable: false,
                            title: 'Set audio file',
                            button: { text: 'Set audio file' },
                            library: { type: 'audio' },
                            contentUserSetting: true,
                            syncSelection: true
                        });
                        this.frame.on('open', this.proxy('onOpen'));
                        this.frame.on('select', this.proxy('setAudio'));
                        this.frame.on('close', this.proxy('onClose'));
                    },
                    onOpen: function onOpen() {
                        var attachment, dataAudioId;
                        this.propertyAudio = CE.Panels.SettingsDialog.myThis.element.find('.motopress-property-audio-id');
                        this.propertyAudioTitle = CE.Panels.SettingsDialog.myThis.element.find('.motopress-property-audio-title');
                        dataAudioId = this.propertyAudio.val();
                        if (dataAudioId !== null) {
                            attachment = wp.media.attachment(dataAudioId);
                        } else {
                            attachment = wp.media.attachment();
                        }
                        attachment.fetch();
                        this.frame.state().get('selection').add(attachment);
                    },
                    setAudio: function setAudio() {
                        var attributes = this.frame.state().get('selection').models[0].attributes;
                        this.propertyAudio.val(attributes.id);
                        this.propertyAudioTitle.val(attributes.title);
                        this.propertyAudio.trigger('change');
                    },
                    onClose: function onClose() {
                        CE.Panels.SettingsDialog.myThis.wrapper.focus();
                        var HtmlBody = $(document).find('html, body');
                        HtmlBody.addClass('motopress-ce-jumping-fix');
                        var tJumpFix = setTimeout(function () {
                            HtmlBody.removeClass('motopress-ce-jumping-fix');
                            clearTimeout(tJumpFix);
                        }, 0);
                    }
                });
            }(jQuery));
            (function ($) {
                CE.WPVideo = can.Construct({ myThis: null }, {
                    frame: null,
                    propertyVideo: null,
                    init: function init() {
                        CE.WPVideo.myThis = this;
                        this.frame = wp.media({
                            id: 'motopress-video-library',
                            multiple: false,
                            describe: false,
                            toolbar: 'select',
                            sidebar: 'settings',
                            content: 'upload',
                            router: 'browse',
                            menu: 'default',
                            searchable: true,
                            filterable: false,
                            sortable: false,
                            title: localStorage.getItem('CEVideoLibraryText'),
                            button: { text: localStorage.getItem('CEVideoLibraryText') },
                            library: { type: 'video' },
                            contentUserSetting: true,
                            syncSelection: true
                        });
                        this.frame.on('open', this.proxy('onOpen'));
                        this.frame.on('select', this.proxy('setVideo'));
                        this.frame.on('close', this.proxy('onClose'));
                    },
                    onOpen: function onOpen() {
                        this.propertyVideo = CE.Panels.SettingsDialog.myThis.element.find('[data-motopress-open-video-lib=1]');
                    },
                    setVideo: function setVideo() {
                        var attributes = this.frame.state().get('selection').models[0].attributes;
                        var url = attributes.url;
                        var videoCtrl = this.propertyVideo.control(CE.Ctrl);
                        videoCtrl.set(url);
                        videoCtrl.element.removeAttr('data-motopress-open-video-lib');
                        videoCtrl.element.trigger('change');
                    },
                    onClose: function onClose() {
                        CE.Panels.SettingsDialog.myThis.wrapper.focus();
                        this.propertyVideo.removeAttr('data-motopress-open-video-lib');
                        var HtmlBody = $(document).find('html, body');
                        HtmlBody.addClass('motopress-ce-jumping-fix');
                        var tJumpFix = setTimeout(function () {
                            HtmlBody.removeClass('motopress-ce-jumping-fix');
                            clearTimeout(tJumpFix);
                        }, 0);
                    }
                });
            }(jQuery));
            can.view.stache('saveObjectModal', '<div class="modal-header">\n\t\t<p id="codeModalLabel" class="modal-header-label">\n\t\t\t{{translations.modalTitle}}\n\t\t</p>\n\t</div>\n\t<div class="modal-body">\n\t\t<div class="motopress-dialog-content">\n\t\t\t<div class="motopress-ce-object-template-name-wrapper motopress-ce-modal-control-wrapper">\n\t\t\t\t<label for="motopress-ce-object-template-name" class="motopress-property-label">\n\t\t\t\t\t{{translations.templateName}}\n\t\t\t\t</label>\n\t\t\t\t<input type="text" id="motopress-ce-object-template-name" name="object-template-name"\n\t\t\t\t\tclass="motopress-property-input" />\n\t\t\t\t<p class="description motopress-property-description">\n\t\t\t\t\t{{translations.leaveNameEmpty}}\n\t\t\t\t</p>\n\t\t\t</div>\n\t\t\t<div class="motopress-ce-object-template-category-select-wrapper motopress-ce-modal-control-wrapper">\n\t\t\t\t<label for="motopress-ce-save-template-select" class="motopress-property-label">\n\t\t\t\t\t{{translations.chooseAction}}\n\t\t\t\t</label>\n\t\t\t\t<select id="motopress-ce-object-template-category-select" \n\t\t\t\t\tclass="motopress-property-select motopress-bootstrap-dropdown motopress-dropdown-select" >\n\t\t\t\t\t<option class="motopress-ce-create-new-category" value="">\n\t\t\t\t\t\t<i>{{translations.createNewCategory}}</i>\n\t\t\t\t\t</option>\n\t\t\t\t\t<optgroup label="{{translations.existingCategories}}" >\n\t\t\t\t\t\t{{#each categories}}\n\t\t\t\t\t\t\t<option value="{{@index}}">{{.}}</option>\n\t\t\t\t\t\t{{/each}}\n\t\t\t\t\t</optgroup>\n\t\t\t\t</select>\n\t\t\t</div>\n\t\t\t<div class="motopress-ce-object-template-category-name-wrapper motopress-ce-modal-control-wrapper">\n\t\t\t\t<label for="motopress-ce-object-template-category-name" class="motopress-property-label">\n\t\t\t\t\t{{translations.categoryName}}\n\t\t\t\t</label>\n\t\t\t\t<input type="text" id="motopress-ce-object-template-category-name" name="category-name"\n\t\t\t\t\tclass="motopress-property-input"/>\n\t\t\t\t<p class="description motopress-property-description">\n\t\t\t\t\t{{translations.leaveCategoryNameBlank}}\n\t\t\t\t</p>\n\t\t\t</div>\n\t\t</div>\n\t</div>\n\t<div class="modal-footer">\n\t\t<button id="motopress-ce-create-object-template" class="motopress-btn-blue">\n\t\t\t{{translations.createTemplate}}\n\t\t</button>\n\t\t<button class="motopress-btn-default" data-dismiss="modal" aria-hidden="true">\n\t\t\t{{translations.close}}\n\t\t</button>\n\t</div>\n');
            (function ($) {
                CE.SaveObjectModal = can.Control.extend(
                {
                    myThis: null,
                    listensTo: [
                        'show',
                        'shown',
                        'hide'
                    ]
                }, 
                {
                    defer: null,
                    categories: [],
                    createBtn: null,
                    categorySelect: null,
                    categorySelectWrapper: null,
                    categoryNameInput: null,
                    categoryNameInputWrapper: null,
                    templateNameInput: null,
                    init: function init() {
                        CE.SaveObjectModal.myThis = this;
                        this.element.mpmodal({
                            'backdrop': 'static',
                            'show': false
                        });
                    },
                    _updateView: function _updateView() {
                        this.element.html(can.view('saveObjectModal', {
                            translations: {
                                modalTitle: 'Save Template',
                                templateName: 'Template Name:',
                                leaveNameEmpty: 'Leave this field blank to generate name automatically.',
                                chooseAction: 'Category:',
                                createNewCategory: 'Create New Category',
                                existingCategories: 'Existing Categories',
                                uncategorized: 'Uncategorized',
                                categoryName: 'Category Name:',
                                leaveCategoryNameBlank: 'Leave this field blank for uncategorized.',
                                createTemplate: 'Create Template',
                                close: 'Close'
                            },
                            categories: this.categories
                        }));
                        this.initForm();
                    },
                    initForm: function initForm() {
                        this.templateNameInput = this.element.find('#motopress-ce-object-template-name');
                        this.categorySelect = this.element.find('#motopress-ce-object-template-category-select');
                        this.categorySelectWrapper = this.element.find('.motopress-ce-object-template-category-select-wrapper');
                        this.categoryNameInput = this.element.find('#motopress-ce-object-template-category-name');
                        this.categoryNameInputWrapper = this.element.find('.motopress-ce-object-template-category-name-wrapper');
                        this.createBtn = this.element.find('#motopress-ce-create-object-template');
                    },
                    ' show': function show(el, e) {
                        this._updateView();
                        this.formReset();
                    },
                    ' shown': function shown() {
                        this.templateNameInput.focus();
                    },
                    ' hide': function hide(el, e) {
                        this.defer.reject();
                    },
                    '#motopress-ce-object-template-category-select change': function motopressCeObjectTemplateCategorySelectChange() {
                        var category = this.categorySelect.val();
                        if (!category && this.categorySelect.children(':selected').hasClass('motopress-ce-create-new-category')) {
                            this.categoryNameInputWrapper.removeClass('motopress-hide');
                        } else {
                            this.categoryNameInputWrapper.addClass('motopress-hide');
                        }
                    },
                    'keydown': function keydown(el, e) {
                        if (e.which === $.ui.keyCode.ENTER) {
                            this.createBtn.click();
                        }
                    },
                    formReset: function formReset() {
                        this.templateNameInput.val('');
                        this.categorySelect.val('');
                        this.categoryNameInput.val('');
                    },
                    showModal: function showModal(atts) {
                        this.categories = atts.categories;
                        this.element.mpmodal('show');
                        this.defer = $.Deferred();
                        return this.defer.promise();
                    },
                    hideModal: function hideModal() {
                        this.element.mpmodal('hide');
                    },
                    '#motopress-ce-create-object-template click': function motopressCeCreateObjectTemplateClick() {
                        var category = this.categorySelect.val();
                        var atts = {
                            name: $.trim(this.templateNameInput.val()),
                            category: category
                        };
                        if (!category && this.categorySelect.children(':selected').hasClass('motopress-ce-create-new-category')) {
                            var categoryTitle = $.trim(this.categoryNameInput.val());
                            atts.newCategoryTitle = categoryTitle;
                        }
                        this.defer.resolve(atts);
                        this.hideModal();
                    }
                });
            }(jQuery));
            (function ($) {
                CE.PresetSaveModal = can.Control.extend(
                {
                    myThis: null,
                    listensTo: [
                        'show',
                        'shown',
                        'hide'
                    ]
                }, 
                {
                    saveBtn: null,
                    updateBtn: null,
                    privateStyleObj: null,
                    presetStyleObj: null,
                    presetSelect: null,
                    presetSelectWrapper: null,
                    presetNameInput: null,
                    presetNameInputWrapper: null,
                    presetInheritanceDesc: null,
                    presetInheritanceName: null,
                    init: function init() {
                        CE.PresetSaveModal.myThis = this;
                        this.initButtons();
                        this.initForm();
                        this.element.mpmodal({
                            'backdrop': 'static',
                            'show': false
                        });
                    },
                    initButtons: function initButtons() {
                        this.createBtn = this.element.find('#motopress-ce-create-preset');
                        this.updateBtn = this.element.find('#motopress-ce-update-preset');
                    },
                    initForm: function initForm() {
                        this.presetSelect = this.element.find('#motopress-ce-save-preset-select');
                        this.presetSelectWrapper = this.element.find('.motopress-ce-save-preset-select-wrapper');
                        this.presetNameInput = this.element.find('#motopress-ce-save-preset-name');
                        this.presetNameInputWrapper = this.element.find('.motopress-ce-save-preset-name-wrapper');
                        this.presetInheritanceDesc = this.element.find('.motopress-ce-preset-inheritance');
                        this.presetInheritanceName = this.presetInheritanceDesc.find('.motopress-ce-preset-inheritance-name');
                    },
                    ' show': function show(el, e) {
                        this.updatePresetsList();
                        this.formReset();
                    },
                    ' shown': function shown() {
                        this.presetNameInput.focus();
                    },
                    ' hide': function hide(el, e) {
                        this.defer.reject();
                    },
                    updatePresetsList: function updatePresetsList() {
                        var list = IframeCE.StyleEditor.myThis.getPresetsList();
                        var options = [];
                        $.each(list, function (name, label) {
                            options.push($('<option />', {
                                value: name,
                                text: label
                            }));
                        });
                        this.presetSelect.find('optgroup').html(options);
                        if (!options.length) {
                            this.presetSelect.find('optgroup').addClass('motopress-hide');
                        } else {
                            this.presetSelect.find('optgroup').removeClass('motopress-hide');
                        }
                    },
                    '#motopress-ce-save-preset-select change': function motopressCeSavePresetSelectChange() {
                        this.updateControlsVisibility();
                    },
                    'keydown': function keydown(el, e) {
                        if (e.which === $.ui.keyCode.ENTER) {
                            $(this.createBtn, this.updateBtn).filter(':not(.motopress-hide)').click();
                        }
                    },
                    updateControlsVisibility: function updateControlsVisibility() {
                        var selectedPreset = this.presetSelect.val();
                        if (selectedPreset === '') {
                            this.presetNameInputWrapper.removeClass('motopress-hide');
                            this.updateBtn.addClass('motopress-hide');
                            this.createBtn.removeClass('motopress-hide');
                        } else {
                            this.presetNameInputWrapper.addClass('motopress-hide');
                            this.updateBtn.removeClass('motopress-hide');
                            this.createBtn.addClass('motopress-hide');
                        }
                    },
                    formReset: function formReset() {
                        this.presetSelect.val('');
                        this.presetNameInput.val('');
                        if (this.presetStyleObj !== null) {
                            this.presetInheritanceName.text(this.presetStyleObj.getLabel());
                            this.presetInheritanceDesc.removeClass('motopress-hide');
                        } else {
                            this.presetInheritanceName.text('');
                            this.presetInheritanceDesc.addClass('motopress-hide');
                        }
                        this.updateControlsVisibility();
                    },
                    showModal: function showModal(privateStyleObj, presetStyleObj) {
                        this.privateStyleObj = privateStyleObj;
                        this.presetStyleObj = presetStyleObj;
                        this.element.mpmodal('show');
                        this.defer = $.Deferred();
                        return this.defer.promise();
                    },
                    '#motopress-ce-create-preset click': function motopressCeCreatePresetClick() {
                        var presetSettings = this.presetStyleObj !== null ? this.presetStyleObj.getSettings() : {};
                        $.extend(true, presetSettings, this.privateStyleObj.getSettings());
                        var presetOptions = {
                            label: $.trim(this.presetNameInput.val()),
                            objectType: this.privateStyleObj.getObjectType()
                        };
                        var newPresetObj = IframeCE.StyleEditor.myThis.createPreset(presetSettings, presetOptions);
                        this.defer.resolve(newPresetObj.getId());
                        this.element.mpmodal('hide');
                        CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.StylePresetCreated.NAME, new IframeCE.SceneEvents.StylePresetCreated(newPresetObj));
                    },
                    '#motopress-ce-update-preset click': function motopressCeUpdatePresetClick() {
                        var presetClass = this.presetSelect.val();
                        var selectedPresetObj = IframeCE.StyleEditor.myThis.getPresetStyleInstance(presetClass);
                        var presetSettings = this.presetStyleObj !== null ? this.presetStyleObj.getSettings() : {};
                        $.extend(true, presetSettings, this.privateStyleObj.getSettings());
                        selectedPresetObj.update(presetSettings);
                        this.defer.resolve(selectedPresetObj.getId());
                        this.element.mpmodal('hide');
                        CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.StylePresetChanged.NAME, new IframeCE.SceneEvents.StylePresetChanged(selectedPresetObj));
                    }
                });
            }(jQuery));
            (function ($) {
                CE.CodeModal = can.Control.extend(
                {
                    myThis: null,
                    currentShortcode: null,
                    currentTextareaTinymce: null,
                    listensTo: [
                        'show',
                        'shown',
                        'hide'
                    ]
                }, 
                {
                    iframeBody: null,
                    content: null,
                    editor: null,
                    saveBtn: null,
                    saveHandler: null,
                    isHiding: false,
                    editorId: 'motopresscodecontent',
                    init: function init() {
                        CE.CodeModal.myThis = this;
                        this.container = this.element.find('#wp-' + this.editorId + '-editor-container');
                        this.content = this.element.find('#' + this.editorId + '');
                        if (typeof tinyMCE !== 'undefined' && typeof tinyMCE.get(this.editorId) !== 'undefined') {
                            var self = this;
                            $.when(motopressCE.tinyMCEInited[this.editorId]).done(function (editor) {
                                self.editor = editor;
                            });
                        }
                        this.saveBtn = this.element.find('#motopress-save-code-content');
                        var tools = this.element.find('#wp-' + this.editorId + '-editor-tools');
                        if (tools.height() === 0) {
                            tools.height(33);
                        }
                        this.element.mpmodal({
                            'backdrop': 'static',
                            'show': false
                        });
                        $(window).on('resize', this.proxy('resize'));
                        MP.Utils.addWindowFix();
                        if (parent.MP.Utils.version_compare(parent.motopressCE.settings.wp.wordpress_version, '4.0.0', '>=')) {
                            this.element.find('.wp-switch-editor.switch-html, .wp-switch-editor.switch-tmce').on('click', function () {
                                CE.CodeModal.myThis.recalcTabSizeWP4();
                            });
                        }
                    },
                    setSize: function setSize(isHidden) {
                        var footer = this.element.find('.modal-footer');
                        var footerWidth = this.element.width() - (parseFloat(footer.css('padding-left')) + parseFloat(footer.css('padding-right')));
                        footer.width(footerWidth);
                        var body = this.element.find('.modal-body');
                        var bodyHeight = this.element.height() - this.element.find('.modal-header').outerHeight() - parseFloat(body.css('margin-top')) - parseFloat(body.css('margin-bottom')) - footer.outerHeight();
                        body.height(bodyHeight);
                        if (isHidden) {
                            this.element.css({
                                display: 'block',
                                visibility: 'hidden'
                            });
                        }
                        var magicPixels = parent.MP.Utils.version_compare(parent.motopressCE.settings.wp.wordpress_version, '4.1.0', '>=') ? 2 : 0;
                        var containerHeight = this.element.find('#wp-' + this.editorId + '-wrap').height() - this.element.find('#wp-' + this.editorId + '-editor-tools').outerHeight() - parseFloat(this.container.css('border-top-width')) - parseFloat(this.container.css('border-bottom-width')) - magicPixels;
                        this.container.height(containerHeight);
                        if (parent.MP.Utils.version_compare(parent.motopressCE.settings.wp.wordpress_version, '4.0.0', '<')) {
                            this.recalcTabSizeWPOld();
                        } else {
                            this.recalcTabSizeWP4();
                        }
                        if (isHidden) {
                            this.element.css({
                                display: '',
                                visibility: ''
                            });
                            this.content.removeAttr('rows');
                        }
                    },
                    recalcTabSizeWPOld: function recalcTabSizeWPOld() {
                        var contentHeight = this.container.height() - parseFloat(this.container.css('border-top-width')) - parseFloat(this.container.css('border-bottom-width')) - this.element.find('#qt_' + this.editorId + '_toolbar').outerHeight() - parseFloat(this.content.css('padding-top')) - parseFloat(this.content.css('padding-bottom'));
                        this.content.height(contentHeight);
                        var contentIfr = this.element.find('#' + this.editorId + '_ifr');
                        var mceFirst = this.container.find('.mce-first, tr.mceFirst').first();
                        var mceLast = this.container.find('.mce-statusbar, tr.mceLast').last();
                        var contentIfrHeight = this.container.height() - parseFloat(this.container.css('border-top-width')) - parseFloat(this.container.css('border-bottom-width')) - mceFirst.outerHeight() - mceLast.outerHeight();
                        contentIfr.height(contentIfrHeight);
                    },
                    recalcTabSizeWP4: function recalcTabSizeWP4() {
                        var activeMode = MP.Utils.getEditorActiveMode(this.element.find('#wp-' + this.editorId + '-wrap').prop('class').split(' '));
                        switch (activeMode) {
                        case 'html-active':
                            var contentHeight = this.container.height() - this.element.find('#qt_' + this.editorId + '_toolbar').outerHeight() - parseFloat(this.content.css('padding-top')) - parseFloat(this.content.css('padding-bottom'));
                            this.content.height(contentHeight);
                            break;
                        case 'tmce-active':
                            var editAreaHeight = this.container.height() - parseFloat(this.container.find('.mce-toolbar-grp').outerHeight()) - parseFloat(this.container.find('.mce-statusbar').outerHeight());
                            this.container.find('.mce-edit-area').height(editAreaHeight);
                            break;
                        }
                    },
                    resize: function resize() {
                        if (this.element.data('modal').isShown) {
                            this.setSize(false);
                        }
                    },
                    ' show': function show(el, e) {
                        this.isHiding = false;
                        this.switchVisual();
                        this.setSize(true);
                        if (this.saveHandler !== null && typeof this.saveHandler === 'function') {
                            this.saveBtn.off('click').on('click', this.saveHandler);
                        }
                        if (this.iframeBody === null) {
                            this.iframeBody = this.element.find('#' + this.editorId + '_ifr').contents().find('.' + this.editorId + '');
                            this.iframeBody.css('max-width', 'none');
                        }
                        if (!el.data('modal').hasOwnProperty('closeDialog')) {
                            CE.Panels.SettingsDialog.myThis.close();
                        }
                    },
                    ' shown': function shown() {
                        if (this.editor !== null) {
                            this.editor.execCommand('mceFocus', false);
                        } else {
                            this.content.focus();
                        }
                        $('body').hide(0, function () {
                            $('body').show();
                        });
                    },
                    ' hide': function hide(el, e) {
                        if (!this.isHiding) {
                            this.isHiding = true;
                            var handle = CE.CodeModal.currentShortcode.closest('.motopress-block-content').find('>.mpce-widget-tools-wrapper>.mpce-object-panel>.mpce-panel-btn-settings');
                            IframeCE.Selectable.getFocusAreaBySelected(handle).focus();
                            this.content.val('');
                            if (this.editor !== null)
                                this.editor.setContent('');
                            this.saveHandler = null;
                            if (el.data('modal').hasOwnProperty('closeDialog')) {
                                delete el.data('modal').closeDialog;
                            }
                            if (!CE.Panels.SettingsDialog.myThis.element.dialog('isOpen')) {
                                CE.Panels.SettingsDialog.myThis.open(handle);
                            }
                            var t = setTimeout(function () {
                                CE.CodeModal.currentShortcode = null;
                                CE.CodeModal.currentTextareaTinymce = null;
                                clearTimeout(t);
                            }, 0);
                        }
                    },
                    switchVisual: function switchVisual() {
                        var activeMode = MP.Utils.getEditorActiveMode(this.element.find('#wp-' + this.editorId + '-wrap').prop('class').split(' '));
                        if (typeof tinyMCE !== 'undefined' && activeMode === 'html-active') {
                            if (switchEditors.hasOwnProperty('switchto')) {
                                switchEditors.switchto(this.element.find('#' + this.editorId + '-tmce')[0]);
                            } else {
                                switchEditors.go(this.editorId, 'tmce');
                            }
                            if (this.editor === null) {
                                this.editor = tinyMCE.get(this.editorId);
                            }
                        }
                    }
                });
            }(jQuery));
            (function ($) {
                CE.Panels.AbstractDialog.extend('CE.Panels.LayoutChooserDialog', {
                    myThis: null,
                    NAME: 'layout_chooser'
                }, {
                    layoutTypes: [],
                    editedRow: null,
                    positionTarget: null,
                    $marker: null,
                    insertMarker: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        CE.Panels.LayoutChooserDialog.myThis = this;
                        this.layoutTypes = args.layouts;
                        this.createPositionMarker();
                        el.dialog({
                            autoOpen: false,
                            resizable: false,
                            dialogClass: 'motopress-dialog',
                            title: 'Choose Layout',
                            width: 320 + 32,
                            height: 200 + 32
                        });
                    },
                    createPositionMarker: function createPositionMarker() {
                        this.$marker = $('<div />', { 'class': 'mpce-layout-chooser-marker' }).css({
                            position: 'absolute',
                            opacity: 0
                        });
                        CE.Panels.Navbar.myThis.editorWrapperEl.before(this.$marker);
                    },
                    updatePositionMarker: function updatePositionMarker($reference) {
                        var _doc = $reference[0].ownerDocument;
                        var _win = _doc.defaultView || _doc.parentWindow;
                        var offset = $reference.offset();
                        this.$marker.css({
                            width: $reference.outerWidth(),
                            height: $reference.outerHeight(),
                            left: offset.left + CE.Iframe.myThis.element.offset().left,
                            top: offset.top - $(_win).scrollTop() + CE.Iframe.myThis.element.offset().top
                        });
                    },
                    open: function open(atts) {
                        atts = $.extend({
                            positionTarget: null,
                            insertMarker: null,
                            editedRow: null
                        }, atts);
                        this.editedRow = atts.editedRow;
                        if (this.editedRow) {
                            this.nestingLevel = MP.Utils.detectRowNestingLvl(this.editedRow);
                            IframeCE.Selectable.myThis.selectShortcode(this.editedRow);
                        } else {
                            this.nestingLevel = 1;
                        }
                        this.insertMarker = atts.insertMarker;
                        this.positionTarget = atts.positionTarget;
                        this.updateView();
                        this.element.dialog('option', 'position', this.preparePositionNearEl(atts.positionTarget));
                        return this._super();
                    },
                    updateView: function updateView() {
                        var layoutTypes = {};
                        $.each(this.layoutTypes, function (index, layoutArr) {
                            layoutTypes[layoutArr.join('-')] = layoutArr;
                        });
                        this.element.html(can.view('layoutChooserDialog', {
                            layouts: layoutTypes,
                            activeLayout: this.editedRow ? IframeCE.LayoutManager.detectRowLayout(this.editedRow).join('-') : '',
                            translations: {}
                        }));
                    },
                    close: function close() {
                        this.element.dialog('close');
                    },
                    preparePositionNearEl: function preparePositionNearEl(el) {
                        this.updatePositionMarker(el);
                        var position = {
                            my: 'center bottom',
                            at: 'center top',
                            of: this.$marker,
                            within: window
                        };
                        if (this.isOpen()) {
                            position['using'] = function (positionHash, positionDetails) {
                                positionDetails.element.element.animate(positionHash);
                            };
                        }
                        return position;
                    },
                    '.mpce-layout-type click': function mpceLayoutTypeClick(el, e) {
                        var layout = el.attr('data-layout-type').split('-');
                        if (this.editedRow) {
                            IframeCE.LayoutManager.editLayout(this.editedRow, layout);
                        } else {
                            IframeCE.LayoutManager.insertRow(layout, this.insertMarker, 'before', this.nestingLevel);
                        }
                        this.openDeferred.resolve();
                        this.close();
                    }
                });
            }(jQuery));
            can.view.stache('layoutChooserDialog', '<ul class="mpce-layout-types-wrapper">\n\t\t{{#each layouts}}\n\t\t\t<li class="mpce-layout-type mpce-layout-type-{{@key}}{{#is activeLayout @key}} mpce-active-layout-type{{/is}}" \n\t\t\t\tdata-layout-type="{{@key}}">\n\t\t\t\t{{#each .}}\n\t\t\t\t\t<span class="mpce-layout-type-clmn mpce-layout-type-clmn-{{.}}"><span>{{.}}</span></span>\n\t\t\t\t{{/each}}\n\t\t\t</li>\n\t\t{{/each}}\n\t</ul>');
            (function ($) {
                CE.Panels.AbstractDialog.extend('CE.Panels.TemplateChooserDialog', {
                    myThis: null,
                    NAME: 'template_chooser'
                }, {
                    positionTarget: null,
                    $marker: null,
                    insertMarker: null,
                    init: function init(el, args) {
                        this._super(el, args);
                        CE.Panels.TemplateChooserDialog.myThis = this;
                        this.createPositionMarker();
                        el.dialog({
                            autoOpen: false,
                            resizable: false,
                            dialogClass: 'motopress-dialog',
                            title: 'Choose Template',
                            width: 360 + 32,
                            height: 300 + 32
                        });
                    },
                    createPositionMarker: function createPositionMarker() {
                        this.$marker = $('<div />', { 'class': 'mpce-template-chooser-marker' }).css({
                            position: 'absolute',
                            opacity: 0
                        });
                        CE.Panels.Navbar.myThis.editorWrapperEl.before(this.$marker);
                    },
                    updatePositionMarker: function updatePositionMarker($reference) {
                        var _doc = $reference[0].ownerDocument;
                        var _win = _doc.defaultView || _doc.parentWindow;
                        var offset = $reference.offset();
                        this.$marker.css({
                            width: $reference.outerWidth(),
                            height: $reference.outerHeight(),
                            left: offset.left + CE.Iframe.myThis.element.offset().left,
                            top: offset.top - $(_win).scrollTop() + CE.Iframe.myThis.element.offset().top
                        });
                    },
                    open: function open(atts) {
                        atts = $.extend({
                            positionTarget: null,
                            insertMarker: null,
                            editedRow: null
                        }, atts);
                        this.insertMarker = atts.insertMarker;
                        this.positionTarget = atts.positionTarget;
                        this.updateView();
                        this.element.dialog('option', 'position', this.preparePositionNearEl(atts.positionTarget));
                        return this._super();
                    },
                    updateView: function updateView() {
                        this.element.html(can.view('templateChooserDialog', {
                            rows: CE.ObjectTemplatesLibrary.myThis.getRows(),
                            pages: CE.ObjectTemplatesLibrary.myThis.getPages(),
                            translations: {
                                all: 'All',
                                search: 'Search',
                                noTemplate: 'There are no saved templates yet. '
                            }
                        }));
                    },
                    close: function close() {
                        this.element.dialog('close');
                    },
                    preparePositionNearEl: function preparePositionNearEl(el) {
                        this.updatePositionMarker(el);
                        var position = {
                            my: 'center bottom',
                            at: 'center top',
                            of: this.$marker,
                            within: window
                        };
                        if (this.isOpen()) {
                            position['using'] = function (positionHash, positionDetails) {
                                positionDetails.element.element.animate(positionHash);
                            };
                        }
                        return position;
                    },
                    '.mpce-section-item click': function mpceSectionItemClick(el, e) {
                        var id = el.attr('data-section-template-id');
                        var category = el.attr('data-section-category-id');
                        var template;
                        if (el.hasClass('mpce-row-item')) {
                            template = CE.ObjectTemplatesLibrary.myThis.getRowById(id);
                        } else {
                            template = CE.ObjectTemplatesLibrary.myThis.getPageById(id);
                        }
                        var content = template.content;
                        var replaceRules = {};
                        $.each(template.styles, function (index, styles) {
                            styles = $.extend({
                                settings: {},
                                objectType: ''
                            }, styles);
                            var privateStyle = IframeCE.StyleEditor.myThis.createPrivateStyle(styles.settings, styles.objectType);
                            replaceRules[styles.name] = privateStyle.getId();
                        });
                        if (!$.isEmptyObject(replaceRules)) {
                            var replaceableClassesRegexp = new RegExp('(\\W)(' + Object.keys(replaceRules).join('|') + ')(\\W)', 'ig');
                            content = content.replace(replaceableClassesRegexp, function (match, before, matchedClass, after) {
                                return before + replaceRules[matchedClass] + after;
                            });
                        }
                        var entityType = new MP.EntityType(IframeCE.Grid.ENTITIES.PAGE);
                        IframeCE.LayoutManager.insertShortcodeString(content, entityType, this.insertMarker, 'before').done(this.proxy(function () {
                            this.openDeferred.resolve();
                        }));
                        this.close();
                    }
                });
            }(jQuery));
            can.view.stache('templateChooserDialog', '<div id="mpce-sections-panel">\n\t\t<!--<div id="mpce-sections-filter-wrapper">-->\n\t\t\t<!--<input type="text" class="mpce-sections-search" placeholder="{{translations.search}}"/>-->\n\t\t\t<!--<select class="mpce-sections-category-filter">-->\n\t\t\t\t<!--<option value="">{{translations.all}}</option>-->\n\t\t\t\t<!--{{#each categories}}-->\n\t\t\t\t\t<!--<option value="{{@index}}">{{.}}</option>-->\n\t\t\t\t<!--{{/each}}-->\n\t\t\t<!--</select>-->\n\t\t<!--</div>-->\n\t\t<div id="mpce-sections-wrapper">\n\t\t\t<div id="mpce-library-sections">\n\t\t\t\t<p>Sections</p>\t\t\t\t\n\t\t\t\t{{#rows.length}}\n\t\t\t\t\t<ul class="mpce-sections-list mpce-library-rows-list">\n\t\t\t\t\t\t{{#each rows}}\n\t\t\t\t\t\t\t<li class="mpce-section-item-wrapper mpce-section-id-{{@index}}">\n\t\t\t\t\t\t\t\t<div class="mpce-section-item mpce-row-item" data-section-template-id="{{@index}}" data-section-category-id="{{category}}" \n\t\t\t\t\t\t\t\t\tdata-section-title="{{name}}">\n\t\t\t\t\t\t\t\t<span class="mpce-section-icon" style="background-image: url(\'{{icon}}\');"></span>\n\t\t\t\t\t\t\t\t\t<span class="mpce-section-name motopress-default motopress-no-color-text">{{name}}</span>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</li>\n\t\t\t\t\t\t{{/each}}\n\t\t\t\t\t</ul>\n\t\t\t\t{{/rows.length}}\n\t\t\t\t{{^rows.length}}\n\t\t\t\t\t<p><em>{{translations.noTemplate}}</em></p>\n\t\t\t\t{{/rows.length}}\n\t\t\t\t<p>Pages</p>\n\t\t\t\t{{#pages.length}}\n\t\t\t\t<ul class="mpce-sections-list mpce-library-pages-list">\n\t\t\t\t\t{{#each pages}}\n\t\t\t\t\t\t<li class="mpce-section-item-wrapper mpce-section-id-{{@index}}">\n\t\t\t\t\t\t\t<div class="mpce-section-item mpce-page-item" data-section-template-id="{{@index}}" data-section-category-id="{{category}}" \n\t\t\t\t\t\t\t\tdata-section-title="{{name}}">\n\t\t\t\t\t\t\t<span class="mpce-section-icon"><i class="fa fa-file-o fa-lg"></i></span>\n\t\t\t\t\t\t\t\t<span class="mpce-section-name motopress-default motopress-no-color-text">{{name}}</span>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</li>\n\t\t\t\t\t{{/each}}\n\t\t\t\t</ul>\n\t\t\t\t{{/pages.length}}\n\t\t\t\t{{^pages.length}}\n\t\t\t\t\t<p><em>{{translations.noTemplate}}</em></p>\n\t\t\t\t{{/pages.length}}\n\t\t\t</div>\n\t\t</div>\n\t</div>');
            (function ($) {
                CE.PageSettingsEvents = {};
                CE.PageSettingsEvents.Event = parent.CE.EventDispatcher.Event.extend({}, {});
                CE.PageSettingsEvents.SettingsEvent = CE.PageSettingsEvents.Event.extend({}, {
                    init: function init(attr, newVal, oldVal) {
                        this.attr = attr;
                        this.newVal = newVal;
                        this.oldVal = oldVal;
                    },
                    getAttr: function getAttr() {
                        return this.attr;
                    }
                });
            }(jQuery));
            (function ($) {
                CE.PageSettingsEvents.SettingsChanged = CE.PageSettingsEvents.SettingsEvent.extend({ NAME: 'page_settings.settings.changed' }, {});
            }(jQuery));
            can.view.stache('pageDialogSettings', '<page-settings>\n\n\t<div class="">\t\n\t\t<div>\t\n\t\t\t<label class=\'motopress-property-label\' for=\'mpce-page-settings-title\'>\n\t\t\t\t{{translations.title}}\n\t\t\t</label>\n\t\t\t<input class=\'motopress-property-input\' type=\'text\' value=\'{{Settings.title}}\' id=\'mpce-page-settings-title\'  \n\t\t\tplaceholder=\'Title\' ($blur)=\'updateTitle($element.val)\'  ($change)=\'updateTitle($element.val)\' />\n\t\t\t<hr/>\n\t\t</div>\n\t\t<div>\n\t\t\t<input type=\'checkbox\' class="motopress-property-checkbox-input motopress-property-input"  id=\'mpce-page-settings-hide-title\' \n\t\t\t\t($change)=\'updateTitleVisibility\' {{#if Settings.hideTitle}}checked{{/if}} />\n\t\t\t<label class="motopress-property-label motopress-property-checkbox-label" for=\'mpce-page-settings-hide-title\'> \n\t\t\t\t{{translations.hideTitle}}\n\t\t\t</label>\n\t\t\t<hr/>\n\t\t</div>\n\t\t<div>\n\t\t\t<label class=\'motopress-property-label\' for=\'mpce-page-settings-template\'>\n\t\t\t\t{{translations.template}} \n\t\t\t</label>\n\t\t\t<select ($change)=\'updateTemplate\' id=\'mpce-page-settings-template\' class="motopress-property-select motopress-bootstrap-dropdown motopress-dropdown-select">\n\t\t\t\t{{#each displayTemplateList}}\n\t\t\t\t\t<option value=\'{{value}}\' {{#is value Settings.template }}selected{{/is}}>{{name}}</option>\n\t\t\t\t{{/each}}\n\t\t\t</select>\n\t\t\t<hr/>\n\t\t</div>\n\t\t<div>\n\t\t\t<label class=\'motopress-property-label\' for=\'mpce-page-settings-status\'>\n\t\t\t\t{{translations.status}}\n\t\t\t</label>\n\t\t\t<select ($change)=\'updateStatus\' id=\'mpce-page-settings-status\' class="motopress-property-select motopress-bootstrap-dropdown motopress-dropdown-select">\n\t\t\t\t{{#each displayStatusList}}\n\t\t\t\t<option value=\'{{@key}}\' {{#is @key Settings.status }}selected{{/is}}>{{.}}</option>\n\t\t\t\t{{/each}}\n\t\t\t</select>\n\t\t</div>\t\t\t\t\t\t\n\t</div>\n\n</page-settings>\n');
            (function ($) {
                var $postTitle, $postDiv;
                can.Component.extend({
                    tag: 'page-settings',
                    viewModel: {
                        Settings: CE.Settings.Page,
                        init: function init() {
                            CE.Iframe.runAndSetAsDependent(this.proxy(function (iframeStatic) {
                                $postTitle = iframeStatic.$('h1.entry-title');
                                $postDiv = iframeStatic.$('.mpce-post-div');
                            }));
                        },
                        displayTemplateList: function displayTemplateList() {
                            return motopressCE.postData.templateList;
                        },
                        displayStatusList: function displayStatusList() {
                            return motopressCE.postData.statusList;
                        },
                        updateTitle: function updateTitle(value) {
                            value = $.trim(value);
                            this.Settings.attr('title', value);
                        },
                        updateTitleVisibility: function updateTitleVisibility(Settings, $el, event) {
                            this.Settings.attr('hideTitle', $el.is(':checked') ? 1 : 0);
                        },
                        updateTemplate: function updateTemplate(Settings, $el, event) {
                            this.Settings.attr('template', $el.val());
                        },
                        updateStatus: function updateStatus(Settings, $el, event) {
                            this.Settings.attr('status', $el.val());
                        }
                    },
                    events: {
                        '{Settings} change': function SettingsChange(Settings, event, attr, how, newVal, oldVal) {
                            var def = can.Deferred();
                            switch (attr) {
                            case 'title':
                                $postTitle.html(newVal);
                                def.resolve();
                                break;
                            case 'hideTitle':
                                if (newVal) {
                                    $postDiv.addClass('mpce-hide-post-title');
                                } else {
                                    $postDiv.removeClass('mpce-hide-post-title');
                                }
                                def.resolve();
                                break;
                            case 'template':
                                MP.Preloader.myThis.reopen();
                                MP.Preloader.myThis.show();
                                CE.Save.saveAJAX().success(function (response) {
                                    MP.Editor.myThis.reloadScene(response.data.iframe_nonce);    
                                }).error(function () {
                                    MP.Preloader.myThis.hide();    
                                });
                                def.reject();
                                break;
                            case 'status':
                                def.resolve();
                                break;
                            }
                            def.done(function () {
                                CE.EventDispatcher.Dispatcher.dispatch(CE.PageSettingsEvents.SettingsChanged.NAME, new CE.PageSettingsEvents.SettingsChanged(attr, newVal, oldVal));
                            });
                        }
                    }
                });
            }(jQuery));
            can.view.stache('pageDialogDesign', '<page-design>' + '<b>page-design</b>' + '</page-design>');
            (function ($) {
                can.Component.extend({
                    tag: 'page-design',
                    viewModel: {
                        init: function init() {
                            this._foo();
                        },
                        _foo: function _foo() {
                        }
                    },
                    events: {}
                });
            }(jQuery));
            can.view.stache('pageDialogTabs', '<div class="motopress-dialog-tabs" id="mpce-page-dialog-tabs">' + '<ul>' + '{{#each tabs}}' + '<li>' + '<a href="#{{id}}" class="motopress-text-no-color-text">' + '{{title}}<i class="{{icon_class}}"></i>' + '</a>' + '</li>' + '{{/each}}' + '</ul>' + '{{#each tabs}}' + '<div id="{{id}}">' + '{{content}}' + '</div>' + '{{/each}}' + '</div>');
            (function ($) {
                CE.Panels.AbstractDialog.extend('CE.Panels.PageDialog', {
                    myThis: null,
                    NAME: 'page'
                }, {
                    setup: function setup(el, args) {
                        this._super(el, args);
                        this.buildDialogElement();
                    },
                    init: function init(el, args) {
                        this._super(el, args);
                        this.constructor.myThis = this;
                        this.initDialog();
                        this.initTabs();
                    },
                    buildDialogElement: function buildDialogElement() {
                        var tabsViewData = new can.Map({
                            tabs: [{
                                    id: 'mpce-page-dialog-settings-tab',
                                    title: 'Settings',
                                    icon_class: 'motopress-settings-icon',
                                    content: can.view('pageDialogSettings', {
                                        translations: {
                                            title: 'Title',
                                            hideTitle: 'Hide Title',
                                            template: 'Template',
                                            status: 'Status'
                                        }
                                    })
                                }    ]
                        });
                        this.element.append(can.view('pageDialogTabs', tabsViewData));
                        this.element.find('.motopress-property-select').selectpicker({ size: 6 });
                    },
                    initDialog: function initDialog() {
                        this.element.dialog({
                            autoOpen: false,
                            resizable: false,
                            dialogClass: 'motopress-dialog',
                            title: 'Page Settings',
                            width: 300 + 32,
                            height: 400,
                            open: this.proxy(function () {
                            })
                        });
                    },
                    initTabs: function initTabs() {
                        this.element.find('#mpce-page-dialog-tabs').tabs({});
                    },
                    open: function open() {
                        this._super();
                    },
                    close: function close() {
                        this.element.dialog('close');
                    }
                });
            }(jQuery));
            (function ($) {
                CE.LocalRevision = {};
            }(jQuery));
            (function ($) {
                CE.LocalRevision.Events = {};
                CE.LocalRevision.Events.RevisionPicked = CE.EventDispatcher.Event.extend({ NAME: 'local_revision.revision.picked' }, {
                    revision: null,
                    silent: null,
                    init: function init(revision, silent) {
                        this.revision = revision;
                        this.silent = silent;
                    },
                    getRevision: function getRevision() {
                        return this.revision;
                    },
                    getSilent: function getSilent() {
                        return this.silent;
                    }
                });
            }(jQuery));
            (function ($) {
                can.Map.extend('CE.LocalRevision.Item', {}, {
                    selected: false,
                    type: null,
                    event: null,
                    snapshot: null,
                    entity: null,
                    action: null,
                    prop: null,
                    init: function init(event, snapshot) {
                        if (!(event instanceof IframeCE.SceneEvents.Event)) {
                            throw new Error();
                        }
                        this.event = event;
                        this.snapshot = snapshot;
                        this.attr('selected', false);
                        this.detectType();
                        this.initData();
                    },
                    detectType: function detectType() {
                        switch (this.event.getEventName()) {
                        case IframeCE.SceneEvents.sceneInited.NAME:
                            this.attr('type', 'scene_init');
                            break;
                        case IframeCE.SceneEvents.StylePresetChanged.NAME:
                            this.attr('type', 'preset_change');
                            break;
                        default:
                            this.attr('type', 'entity_change');
                        }
                    },
                    initData: function initData() {
                        this.attr('action', getActionTitle(this.event.getEventName()));
                        switch (this.type) {
                        case 'scene_init':
                            break;
                        case 'preset_change':
                            this.prop = null;
                            this.attr('prop', this.event.getPresetLabel());
                            break;
                        case 'entity_change':
                            this.attr('entity', getEntityTitle(this.event.getEntityName()));
                            this.prop = null;
                            if (can.isFunction(this.event.getPropName)) {
                                this.attr('prop', this.event.getPropName());
                            }
                            break;
                        }
                    },
                    getEvent: function getEvent() {
                        return this.event;
                    },
                    getSnapshot: function getSnapshot() {
                        return this.snapshot;
                    },
                    setSnapshot: function setSnapshot(snapshot) {
                        this.snapshot = snapshot;
                    },
                    isType: function isType(type) {
                        return this.type === type;
                    },
                    isSelected: function isSelected() {
                        return this.selected;
                    }
                });
                var getEntityTitle = function getEntityTitle(block) {
                    return can.capitalize(block);
                };
                var getActionTitle = function getActionTitle(eventName) {
                    var events = IframeCE.SceneEvents;
                    switch (eventName) {
                    case events.sceneInited.NAME:
                        return 'Editing Started';
                    case events.EntityCreated.NAME:
                        return 'Created';
                    case events.EntityDeleted.NAME:
                        return 'Deleted';
                    case events.EntityDuplicated.NAME:
                        return 'Duplicated';
                    case events.EntityMoved.NAME:
                        return 'Moved';
                    case events.EntityResized.NAME:
                        return 'Resized';
                    case events.EntitySettingsChanged.NAME:
                    case events.EntityStylesChanged.NAME:
                        return 'Edited';
                    case events.StylePresetChanged.NAME:
                        return 'Preset Edited';
                    default:
                        return '';
                    }
                };    
            }(jQuery));
            (function ($) {
                var DIRECTION = {
                    FORWARD: -1,
                    BACKWARD: 1
                };
                CE.LocalRevision.Manager = can.Construct({
                    _instance: null,
                    getInstance: function getInstance() {
                        if (this._instance === null) {
                            this._instance = new CE.LocalRevision.Manager();
                        }
                        return this._instance;
                    }
                }, {
                    revisions: null,
                    current: null,
                    init: function init() {
                        this.revisions = new can.List();    
                    },
                    getList: function getList() {
                        return this.revisions;
                    },
                    getByIndex: function getByIndex(index) {
                        return this.revisions.attr(index);
                    },
                    undo: function undo() {
                        this.move(DIRECTION.BACKWARD);
                    },
                    redo: function redo() {
                        this.move(DIRECTION.FORWARD);
                    },
                    move: function move(direction) {
                        var curIndex = this.indexOf(this.current);
                        var revision = this.revisions.attr(curIndex + direction);
                        if (revision) {
                            this.pick(revision);
                        }
                    },
                    isOldest: function isOldest() {
                        var curIndex = this.indexOf(this.current);
                        var oldestIndex = this.revisions.length - 1;
                        return curIndex === oldestIndex;
                    },
                    isLatest: function isLatest() {
                        return this.indexOf(this.current) === 0;
                    },
                    pick: function pick(revision, silent) {
                        if (silent === undefined)
                            silent = false;
                        if (revision.isSelected())
                            return false;
                        if (this.current) {
                            this.current.attr('selected', false);
                        }
                        revision.attr('selected', true);
                        this.current = revision;
                        if (!silent) {
                            this.sceneManager.applySnapshot(revision.getSnapshot());
                        }
                        CE.EventDispatcher.Dispatcher.dispatch(CE.LocalRevision.Events.RevisionPicked.NAME, new CE.LocalRevision.Events.RevisionPicked(revision, silent));
                        return true;
                    },
                    isPicked: function isPicked(revision) {
                        return this.current == revision;
                    },
                    indexOf: function indexOf(revision) {
                        return this.revisions.indexOf(revision);
                    },
                    push: function push(revision) {
                        if (this.current) {
                            var selectedIndex = this.indexOf(this.current);
                            if (selectedIndex !== 0) {
                                this.revisions.splice(0, selectedIndex);
                            }
                        }
                        this.revisions.unshift(revision);
                    },
                    createItem: function createItem(event) {
                        return new CE.LocalRevision.Item(event, this.sceneManager.takeSnapshot());    
                    },
                    onContentChange: function onContentChange(event) {
                        this.push(this.createItem(event));
                    }
                });
                var ContentChangesSubscriber = CE.EventDispatcher.Subscriber.extend({}, {
                    cb: null,
                    init: function init(cb) {
                        this.cb = cb;
                    },
                    getSubscribedEvents: function getSubscribedEvents() {
                        var se = IframeCE.SceneEvents;
                        return [[
                                [
                                    se.EntityCreated.NAME,
                                    se.EntityDeleted.NAME,
                                    se.EntityDuplicated.NAME,
                                    se.EntityResized.NAME,
                                    se.EntityMoved.NAME,
                                    se.EntitySettingsChanged.NAME,
                                    se.EntityStylesChanged.NAME,
                                    se.StylePresetDeleted.NAME,
                                    se.StylePresetRenamed.NAME,
                                    se.StylePresetCreated.NAME,
                                    se.StylePresetChanged.NAME
                                ],
                                this.cb
                            ]];
                    }
                });
            }(jQuery));
            var sceneInitView = '<b>{{action}}</b>';
            var entityChangeView = '<b>{{entity}}</b>&nbsp;' + '{{#if prop}}' + '{{prop}}&nbsp;' + '{{/if}}' + '<i>{{action}}</i>&nbsp;';
            var presetChangeView = '{{#if prop}}' + '{{prop}}&nbsp;' + '{{/if}}' + '<i>{{action}}</i>&nbsp;';
            can.view.stache('localRevisionList', '<local-revision-list>' + '<button class=\'motopress-btn-default\' ($click)=\'move("undo")\' {{^if buttons.undo}}disabled=\'disabled\'{{/if}}>&lsh; Undo</button>&nbsp;' + '<button class=\'motopress-btn-default\' ($click)=\'move("redo")\' {{^if buttons.redo}}disabled=\'disabled\'{{/if}}>&rsh; Redo</button>' + '<ul class=\'mpce-revision-list\'>' + '{{#each displayList}}' + '<li ($click)=\'apply(., @element, @event, @index)\' class=\'mpce-revision-item {{#if selected}}selected{{/if}}\'>' + '{{#if isType(\'scene_init\')}}' + sceneInitView + '{{/else}}' + '{{#if isType(\'preset_change\')}}' + presetChangeView + '{{/else}}' + entityChangeView + '{{/if}}' + '{{/if}}' + '</li>' + '{{/each}}' + '</ul>' + '</local-revision-list>');
            (function ($) {
                var _$;
                CE.LocalRevision.SceneManager = can.Construct({}, {
                    $sceneContent: null,
                    $container: null,
                    $stylesWrapper: null,
                    $currentContent: null,
                    $originalStyles: null,
                    controlsSelector: null,
                    init: function init() {
                        CE.Iframe.runAndSetAsDependent(this.proxy(function (iframeStatic) {
                            _$ = CE.Iframe.$;
                            this.$sceneContent = _$(CE.Iframe.myThis.sceneContent);
                            this.$container = this.$sceneContent.parent();
                            this.$stylesWrapper = IframeCE.ShortcodePrivateStyle.stylesWrapper;
                        }));
                    },
                    takeSnapshot: function takeSnapshot() {
                        CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.SnapshotTakenBefore.NAME, new IframeCE.SceneEvents.SnapshotTakenBefore());
                        var snapshot = {
                            content: this.cloneContent(this.$sceneContent.children()),
                            styles: { privates: IframeCE.StyleEditor.myThis.clonePrivateStyles() }
                        };
                        return snapshot;
                    },
                    applySnapshot: function applySnapshot(snapshot) {
                        var snapshotClone = {
                            content: null,
                            styles: null
                        };
                        this.$sceneContent.children().detach();
                        snapshotClone.content = this.cloneContent(snapshot.content);
                        snapshotClone.styles = { privates: IframeCE.StyleEditor.myThis.clonePrivateStyles(snapshot.styles.privates) };
                        var $content = this.$sceneContent.append(snapshotClone.content);
                        IframeCE.StyleEditor.myThis.applyPrivateStyles(snapshotClone.styles.privates);
                        this.eachContentClone($content, this.proxy(function ($entityClone) {
                            this.eachElementControls($entityClone, function (control) {
                                if (can.isFunction(control.restoreClone)) {
                                    control.restoreClone($entityClone);
                                }
                            });
                        }), this.proxy(function (cunstruct) {
                            if (can.isFunction(cunstruct.restoreClone)) {
                                cunstruct.restoreClone($content);
                            }
                        }));
                        CE.EventDispatcher.Dispatcher.dispatch(IframeCE.SceneEvents.SnapshotApplied.NAME, new IframeCE.SceneEvents.SnapshotApplied(snapshotClone));
                    },
                    cloneContent: function cloneContent($content) {
                        var $sceneClone = $content.clone(true, true);
                        this.eachContentClone($sceneClone, this.proxy(function ($entityClone) {
                            var clonedControls = [];
                            this.eachElementControls($entityClone, function (control) {
                                if (can.isFunction(control.clone)) {
                                    clonedControls.push(control.clone($entityClone));
                                } else {
                                    clonedControls.push(control);
                                }
                            });
                            _$.removeData($entityClone, 'controls');
                            $entityClone.data('controls', clonedControls);
                        }), this.proxy(function (cunstruct) {
                            if (can.isFunction(cunstruct.clone)) {
                                cunstruct.clone($sceneClone);
                            }
                        }));
                        return $sceneClone;
                    },
                    eachElementControls: function eachElementControls($element, callback) {
                        var controls = $element.controls();
                        for (var i = 0; i < controls.length; i++) {
                            var control = controls[i];
                            callback(control);
                        }
                    },
                    eachContentClone: function eachContentClone($content, controlsCallback, constructCallback) {
                        var selector = this.getControlsSelector();
                        $content.find(selector).addBack().each(function () {
                            controlsCallback(_$(this));
                        });
                        $.each([IframeCE.Resizer.myThis], function () {
                            constructCallback(this);
                        });
                    },
                    getControlsSelector: function getControlsSelector() {
                        if (this.controlsSelector === null) {
                            var Controls = [
                                IframeCE.Controls,
                                IframeCE.DroppableZone,
                                IframeCE.Panels.WidgetHelper,
                                IframeCE.Panels.RowHelper,
                                IframeCE.Panels.AddSectionPanel
                            ];
                            var selector = $.map(Controls, function (Control) {
                                return Control._fullName;
                            }).join(',.');
                            this.controlsSelector = '.' + selector;
                        }
                        return this.controlsSelector;
                    }
                });
            }(jQuery));
            (function ($) {
                var revisionManager;
                can.Component.extend({
                    tag: 'local-revision-list',
                    viewModel: {
                        revisions: undefined,
                        selected: null,
                        buttons: {
                            undo: false,
                            redo: false
                        },
                        init: function init() {
                            revisionManager = CE.LocalRevision.Manager.getInstance();
                            this.revisions = revisionManager.getList();
                            this._initialize();
                            this._registerRevisionEvents();
                            this._registerKeypressEvents();
                        },
                        displayList: function displayList() {
                            return this.revisions;
                        },
                        apply: function apply(revision, $el, e, index) {
                            this.select(revision);
                        },
                        move: function move(action) {
                            switch (action) {
                            case 'undo':
                                revisionManager.undo();
                                break;
                            case 'redo':
                                revisionManager.redo();
                                break;
                            }
                        },
                        updateButtonsState: function updateButtonsState() {
                            this.attr('buttons.undo', !revisionManager.isOldest());
                            this.attr('buttons.redo', !revisionManager.isLatest());
                        },
                        select: function select(revision, silent) {
                            var picked = revisionManager.pick(revision, silent);
                            if (picked) {
                                this.attr('selected', revision);
                            }
                        },
                        _initialize: function _initialize() {
                            var lastRevision = revisionManager.getByIndex(0);
                            if (lastRevision && !revisionManager.isPicked(lastRevision)) {
                                this.select(lastRevision, true);    
                            }
                        },
                        _registerRevisionEvents: function _registerRevisionEvents() {
                            CE.EventDispatcher.Dispatcher.addListener(CE.LocalRevision.Events.RevisionPicked.NAME, this.proxy(this.updateButtonsState));
                        },
                        _registerKeypressEvents: function _registerKeypressEvents() {
                            $(document).keydown(this.proxy(function (e) {
                                if (e.which === 90 && e.ctrlKey && e.shiftKey) {
                                    revisionManager.redo();
                                }    
                                else if (e.which === 90 && e.ctrlKey) {
                                    revisionManager.undo();
                                }
                            }));
                        }
                    },
                    events: {
                        '{revisions} add': function revisionsAdd(e, action, revisions) {
                            revisions.forEach(this.proxy(function (revision) {
                                this.viewModel.select(revision, true);
                            }));
                        }
                    }
                });
            }(jQuery));
            (function ($) {
                CE.WPRevision = {};
            }(jQuery));
            (function ($) {
                CE.WPRevision.Events = {};
                CE.WPRevision.Events.RevisionPicked = CE.EventDispatcher.Event.extend({ NAME: 'wp_revision.revision.picked' }, {});
            }(jQuery));
            can.view.stache('wpRevisionList', '<wp-revision-list>' + '<button class=\'motopress-btn-default\' ($click)=\'discard\' id=\'mpce-revision-discard\' disabled>Discard</button>&nbsp;' + '<button class=\'motopress-btn-blue\' ($click)=\'apply\' id=\'mpce-revision-apply\' disabled>Apply</button>' + '<ul class=\'mpce-revision-list\'>' + '{{#each displayList}}' + '<li ($click)=\'preview\' class=\'mpce-revision-item\'>' + '<button ($click)=\'remove(., @element, @event, @index)\' class=\'mpce-revision-delete motopress-btn-default\'>x</button>' + '<b>{{date}}</b><br/><i>{{type}} by {{author}}</i>' + '</li>' + '{{/each}}' + '</ul>' + '</wp-revision-list>');
            (function ($) {
                var _$;
                CE.WPRevision.SceneManager = can.Construct({}, {
                    $sceneContent: null,
                    $container: null,
                    $currentContent: null,
                    $originalStyles: null,
                    init: function init() {
                        CE.Iframe.runAndSetAsDependent(this.proxy(function (iframeStatic) {
                            _$ = CE.Iframe.$;
                            this.$sceneContent = _$(CE.Iframe.myThis.sceneContent);
                            this.$container = this.$sceneContent.parent();
                        }));    
                    },
                    requestRevision: function requestRevision(id, contentType) {
                        if (contentType === undefined)
                            contentType = 'raw';
                        return $.ajax({
                            type: 'post',
                            dataType: 'json',
                            url: motopress.ajaxUrl,
                            data: {
                                action: 'motopress_ce_get_revision_data',
                                nonce: motopressCE.nonces.motopress_ce_get_revision_data,
                                id: id,
                                content_type: contentType
                            },
                            success: function success(response) {
                            },
                            error: function error(xhr) {
                                MP.Flash.setFlash(xhr.responseJSON.message, 'error');
                                MP.Flash.showMessage();
                            }
                        });
                    },
                    requestDeleteRevision: function requestDeleteRevision(id) {
                        return $.ajax({
                            type: 'post',
                            dataType: 'json',
                            url: motopress.ajaxUrl,
                            data: {
                                action: 'motopress_ce_delete_revision',
                                nonce: motopressCE.nonces.motopress_ce_delete_revision,
                                id: id
                            },
                            success: function success(response) {
                            },
                            error: function error(xhr) {
                                MP.Flash.setFlash(xhr.responseJSON.message, 'error');
                                MP.Flash.showMessage();
                            }
                        });
                    },
                    setPreview: function setPreview(data) {
                        this.hideOriginalContent();
                        this.removeOriginalStyles();
                        this.removePreview();
                        if (data.styles) {
                            _$('body').append(_$(data.styles));
                        }
                        this.$container.append(data.content);
                    },
                    removePreview: function removePreview() {
                        _$('.motopress-content-wrapper-preview').remove();
                        _$('.motopress-ce-private-styles-wrapper-preview').remove();
                    },
                    hideOriginalContent: function hideOriginalContent() {
                        this.$sceneContent.hide();
                    },
                    showOriginalContent: function showOriginalContent() {
                        this.$sceneContent.show();
                    },
                    removeOriginalStyles: function removeOriginalStyles() {
                        if (this.$originalStyles === null) {
                            this.$originalStyles = IframeCE.ShortcodePrivateStyle.stylesWrapper.children().detach();
                        }
                    },
                    returnOriginalStyles: function returnOriginalStyles() {
                        IframeCE.ShortcodePrivateStyle.stylesWrapper.append(this.$originalStyles);
                        this.resetOriginalStyles();
                    },
                    resetOriginalStyles: function resetOriginalStyles() {
                        this.$originalStyles = null;
                    },
                    removeOriginalContent: function removeOriginalContent() {
                        IframeCE.Scene.myThis.removeContent();
                        this.resetOriginalStyles();
                    },
                    insertOriginalContent: function insertOriginalContent(data) {
                        this.removeOriginalContent();
                        IframeCE.ShortcodePrivateStyle.addRenderedStyleTags(_$(data.other_styles).filter('style'));
                        IframeCE.StyleEditor.myThis.initPrivateStyles(data.post_styles);
                        if (data.content) {
                            IframeCE.Scene.myThis.insertContent(data.content);
                        }
                        CE.EventDispatcher.Dispatcher.dispatch(CE.WPRevision.Events.RevisionPicked.NAME, new CE.WPRevision.Events.RevisionPicked());
                        CE.Save.changeContent();
                    }
                });
            }(jQuery));
            (function ($) {
                can.Component.extend({
                    tag: 'wp-revision-list',
                    viewModel: {
                        revisions: new can.List(motopressCE.revisions),
                        sceneManager: null,
                        saveManager: null,
                        selectedItem: null,
                        $selectedEl: null,
                        init: function init() {
                            this.sceneManager = new CE.WPRevision.SceneManager();
                            this.saveManager = CE.Save;
                            MP.Editor.on('AfterUpdate', this.proxy('onContentSaved'));
                        },
                        onContentSaved: function onContentSaved(e, data) {
                            if (data.last_revision) {
                                this._addRevisionItem(data.last_revision);
                            }
                            if ($.isArray(data.revisions_ids)) {
                                this.revisions.replace(this.revisions.filter(function (item) {
                                    return data.revisions_ids.indexOf(item.id) !== -1;
                                }));
                            }
                        },
                        displayList: function displayList() {
                            return this.revisions;
                        },
                        preview: function preview(item, $el, e) {
                            if (this._isSelectedByElement($el)) {
                                return;
                            }
                            var request = this.sceneManager.requestRevision(item.id, 'raw');
                            request.success(this.proxy(function (response) {
                                this.sceneManager.setPreview(response.data);
                                this._select(item, $el);
                                this._enableControls();
                            }));
                            request.error(this.proxy(function (xhr) {
                            }));
                            request.complete(this.proxy(function (xhr) {
                            }));
                        },
                        apply: function apply(self, $el, e) {
                            CE.Saving.save();
                        },
                        discard: function discard(self, $el, e) {
                            this._discard();
                            this._resetSelected();
                        },
                        remove: function remove(item, $el, e, index) {
                            e.stopPropagation();
                            if (confirm('Are you sure ?')) {
                                var request = this.sceneManager.requestDeleteRevision(item.id);
                                request.success(this.proxy(function () {
                                    if (this._isSelectedByItem(item)) {
                                        this._discard();
                                        this._resetSelected();
                                    }
                                    this._deleteRevisionItem(index);
                                }));
                            }
                        },
                        _enableControls: function _enableControls() {
                            $('#mpce-revision-apply').removeAttr('disabled');
                            $('#mpce-revision-discard').removeAttr('disabled');
                        },
                        _disableControls: function _disableControls() {
                            $('#mpce-revision-apply').attr('disabled', '');
                            $('#mpce-revision-discard').attr('disabled', '');
                        },
                        _discard: function _discard() {
                            this.sceneManager.removePreview();
                            this.sceneManager.returnOriginalStyles();
                            this.sceneManager.showOriginalContent();
                        },
                        _resetSelected: function _resetSelected() {
                            this._disableControls();
                            if (this.$selectedEl !== null) {
                                this.$selectedEl.removeClass('selected');
                            }
                            this.selectedItem = null;
                            this.$selectedEl = null;
                        },
                        _isSelectedByElement: function _isSelectedByElement($el) {
                            return $el.hasClass('selected');
                        },
                        _isSelectedByItem: function _isSelectedByItem(item) {
                            return !!(this.selectedItem && this.selectedItem.id === item.id);
                        },
                        _select: function _select(item, $el) {
                            if (this.$selectedEl !== null) {
                                this.$selectedEl.removeClass('selected');
                            }
                            this.selectedItem = item;
                            this.$selectedEl = $el;
                            $el.addClass('selected');
                        },
                        _isPreview: function _isPreview() {
                            return this.selectedItem !== null;
                        },
                        _addRevisionItem: function _addRevisionItem(revisionData) {
                            var unshift = true;
                            this.revisions.forEach(function (item) {
                                if (item.id === revisionData.id) {
                                    unshift = false;
                                    return;
                                }
                            });
                            if (unshift) {
                                this.revisions.unshift(revisionData);
                            }
                        },
                        _deleteRevisionItem: function _deleteRevisionItem(index) {
                            this.revisions.splice(index, 1);
                        },
                        _save: function _save() {
                            var revID = this.selectedItem.id;
                            if (IframeCE.SceneState.isContentChanged()) {
                                this.saveManager.saveAJAX().success(this.proxy(this._applyRevision, revID));
                            } else {
                                this._applyRevision(revID);
                            }
                            this._resetSelected();
                        },
                        _applyRevision: function _applyRevision(revID) {
                            var request = this.sceneManager.requestRevision(revID, 'editor');
                            request.success(this.proxy(function (response) {
                                this.sceneManager.removePreview();
                                this.sceneManager.resetOriginalStyles();
                                this.sceneManager.insertOriginalContent(response.data);
                                this.sceneManager.showOriginalContent();
                                this.saveManager.saveAJAX();
                            }));
                            request.error(this.proxy(function (xhr) {
                            }));
                            request.complete(this.proxy(function (xhr) {
                            }));
                            return request;
                        }
                    },
                    events: {
                        'inserted': function inserted() {
                            var scope = this.viewModel;
                            MP.Editor.triggerIfr('WPRevision.Init', {
                                control: function () {
                                    return {
                                        save: scope.proxy('_save'),
                                        isPreview: scope.proxy('_isPreview')
                                    };
                                }()
                            });
                        }
                    }
                });
            }(jQuery));
            can.view.stache('historyDialogTabs', '<div class="motopress-dialog-tabs" id="mpce-history-dialog-tabs">' + '<ul>' + '{{#each tabs}}' + '<li>' + '<a href="#{{id}}" class="motopress-text-no-color-text">' + '{{title}}<i class="{{icon_class}}"></i>' + '</a>' + '</li>' + '{{/each}}' + '</ul>' + '{{#each tabs}}' + '<div id="{{id}}"></div>' + '{{/each}}' + '</div>');
            (function ($) {
                CE.Panels.AbstractDialog.extend('CE.Panels.HistoryDialog', {
                    myThis: null,
                    NAME: 'history'
                }, {
                    dialog: null,
                    wpRevisionsRenderer: can.view('wpRevisionList'),
                    localRevisionsRenderer: can.view('localRevisionList'),
                    tabsRenderer: can.view('historyDialogTabs'),
                    tabsViewData: new can.Map({
                        tabs: [
                            {
                                id: 'mpce-history-dialog-wp-tab',
                                title: 'Revisions',
                                icon_class: ''
                            }]
                    }),
                    setup: function setup(el, args) {
                        this._super(el, args);
                        this.buildDialogElement();
                    },
                    init: function init(el, args) {
                        this._super(el, args);
                        this.constructor.myThis = this;
                        this.initDialog();
                        this.initTabs();
                    },
                    buildDialogElement: function buildDialogElement() {
                        this.element.append(this.tabsRenderer(this.tabsViewData));
                        this.element.find('#mpce-history-dialog-local-tab').html(this.localRevisionsRenderer());
                        this.element.find('#mpce-history-dialog-wp-tab').html(this.wpRevisionsRenderer());
                        this.element.appendTo('body');
                    },
                    initDialog: function initDialog() {
                        this.element.dialog({
                            position: {
                                my: 'left top',
                                at: 'left+10 top+50',
                                of: window.top
                            },
                            autoOpen: false,
                            closeOnEscape: true,
                            closeText: '',
                            title: 'History',
                            dialogClass: 'motopress-dialog',
                            draggable: true,
                            modal: false,
                            resizable: true,
                            width: 300 + 32,
                            height: 400,
                            create: this.proxy(function (e) {
                            }),
                            close: function close(e) {
                            },
                            beforeClose: function beforeClose(e, ui) {
                            },
                            open: function open(e) {
                            }
                        });
                    },
                    initTabs: function initTabs() {
                        this.element.find('#mpce-history-dialog-tabs').tabs({});
                    }
                });
            }(jQuery));
            (function ($) {
                if ($.hasOwnProperty('fn') && $.fn.hasOwnProperty('button') && $.fn.button.hasOwnProperty('noConflict')) {
                    $.fn.btn = $.fn.button.noConflict();
                }
                new MP.Editor();
            }(jQuery));
        }());
    } catch (e) {
        MP.Error.log(e);
    }
}