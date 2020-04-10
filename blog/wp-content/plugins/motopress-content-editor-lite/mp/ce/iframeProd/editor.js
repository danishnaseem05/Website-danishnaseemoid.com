"use strict";

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function mpceRunScene() {
  try {
    (function ($) {
      MP.BootstrapSelect = can.Construct(
      {
        setSelected: function setSelected(option) {
          option.prop('selected', true);
          option.closest('select').next('.bootstrap-select').find('.filter-option').text(option.text());
        },
        setDisabled: function setDisabled(option, flag) {
          if (typeof flag == 'undefined') flag = true; 

          var select = option.closest('select');
          var index = option.prop('index');
          var a = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="' + index + '"] > a');

          if (flag) {
            a.attr('data-disabled', '');
          } else {
            a.removeAttr('data-disabled');
          }
        },
        appendOption: function appendOption(select, option) {
          var li = $('<li />', {
            rel: option.prop('index')
          });
          var a = $('<a />', {
            tabindex: '-1',
            href: '#',
            text: option.text()
          });
          if (typeof option.attr('data-disabled') != 'undefined') a.attr('data-disabled', '');
          a.appendTo(li);
          select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"]').append(li);
        },
        removeOption: function removeOption(option) {
          var select = option.closest('select');

          if (select.find('option').length > 1) {
            var index = option.prop('index');
            var li = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="' + index + '"]');
            li.nextAll('li').each(function () {
              $(this).attr('rel', parseInt($(this).attr('rel')) - 1);
            });
            li.remove();

            if (option.parent().is('optgroup') && !option.siblings('option').length) {
              option.parent('optgroup').remove();

              if (select.children('optgroup').length == 1) {
                var optIndex = select.find('optgroup:first option:last').prop('index');
                var optLi = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="' + optIndex + '"]');
                optLi.removeClass('optgroup-div');
              }
            } else {
              option.remove();
            }

            setSelected(select.find('option:first'));
          }
        },
        updateOptionText: function updateOptionText(option, text) {
          var select = option.closest('select');
          var index = option.prop('index');
          var a = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="' + index + '"] > a');
          option.text(text);
          a.text(text);
        }
      },
      {});
    })(jQuery);

    (function ($) {
      CE.Utils = can.Construct(
      {
        body: $('body'),
        addSceneAction: function addSceneAction(action) {
          this.body.addClass('motopress-' + action + '-action');
        },
        isSceneAction: function isSceneAction(action) {
          return this.body.hasClass('motopress-' + action + '-action');
        },
        removeSceneAction: function removeSceneAction(action) {
          this.body.removeClass('motopress-' + action + '-action');
        },

        triggerWindowEvent: function triggerWindowEvent(eventName) {
          $(window).trigger(eventName);
          CE.Utils.fixFlexslider();
        },
        fixFlexslider: function fixFlexslider() {
          $('.motopress-flexslider').each(function () {
            if ($(this).data('flexslider')) {
              $(this).data('flexslider').resize();
            }
          }); 
        },
        addSceneState: function addSceneState(state) {
          this.body.addClass('motopress-' + state + '-state');
        },
        removeSceneState: function removeSceneState(state) {
          this.body.removeClass('motopress-' + state + '-state');
        },
        isSceneState: function isSceneState(state) {
          return this.body.hasClass('motopress-' + state + '-state');
        }
      },
      {});
    })(jQuery);

    (function ($) {
      can.Control('CE.Scene', {
        markEdgeRow: function markEdgeRow(rows) {
          $.each(rows, function () {
            if ($(this).hasClass(parent.CE.Iframe.myThis.gridObj.row.edgeclass)) {
              $(this).addClass('motopress-row-edge');
            } else {
              $(this).find(parent.MP.Utils.convertClassesToSelector(parent.CE.Iframe.myThis.gridObj.row.edgeclass)).first().addClass('motopress-row-edge');
            }
          });
        },

        markEdgeSpan: function markEdgeSpan(spans, isFillerExpected, isRemoveFiller) {
          isFillerExpected = typeof isFillerExpected !== 'undefined' && isFillerExpected;
          isRemoveFiller = typeof isRemoveFiller !== 'undefined' && isRemoveFiller;
          var spanChildSelector = isFillerExpected ? '.motopress-filler-content' : '[data-motopress-shortcode]';
          var spanChild, spansParent;
          $.each(spans, function () {
            spanChild = $(this).find(spanChildSelector).first();
            spansParent = spanChild.parent();

            if (!spansParent.hasClass('motopress-block-content')) {
              spansParent.addClass('motopress-clmn-edge');

              if (isRemoveFiller) {
                spanChild.remove();
              }
            }
          });
        },
        myThis: null
      }, {
        container: null,
        contentWrapper: null,
        addSectionPanel: null,
        init: function init() {
          parent.window.IframeCE = window.CE;
          CE.Scene.myThis = this;
          this.container = $('#motopress-container');
          this.contentWrapper = $('.motopress-content-wrapper');
          new CE.SceneState();
          CE.WPMore.getInstance();
          new CE.StyleEditor(motopressCE.styleEditor);

          parent.window.MP.Editor.triggerEverywhere('SceneInited'); 

          var leftbarWrapper = $('<div />', {
            "class": 'mpce-leftbar-wrapper'
          });
          $('body').prepend(leftbarWrapper);
          new CE.LeftBar(leftbarWrapper);
          new CE.DragDrop();
          new CE.Selectable();
          new CE.Link();
          this.addSectionPanelEl = $('<section />', {
            "class": 'mpce-add-section-panel'
          });
          this.contentWrapper.append(this.addSectionPanelEl);
          new CE.Panels.AddSectionPanel(this.addSectionPanelEl, {
            layoutDialog: parent.CE.Panels.LayoutChooserDialog.myThis,
            templateDialog: parent.CE.Panels.TemplateChooserDialog.myThis,
            permanent: true
          }); 

          setTimeout(function () {
            parent.CE.EventDispatcher.Dispatcher.dispatchOnce(CE.SceneEvents.sceneInited.NAME, new CE.SceneEvents.sceneInited());
          }, 1500);
          parent.CE.Iframe.runDependentCallbacks();
          this.sceneEvents();
        },

        insertContent: function insertContent(content) {
          this.addSectionPanelEl.before(content);
          parent.CE.Iframe.myThis.unwrapGrid();
          CE.DragDrop.myThis.setEdges();
          CE.DragDrop.myThis.main();
          parent.CE.Iframe.$window.trigger('resize');
        },

        removeContent: function removeContent() {
          this.contentWrapper.children().not(this.addSectionPanelEl).remove();
        },
        sceneEvents: function sceneEvents() {
          this.container.off('hover').hover(function () {
            CE.Utils.addSceneAction('container-hover');
          }, function () {
            CE.Utils.removeSceneAction('container-hover');
          });
          parent.CE.EventDispatcher.Dispatcher.addListener(parent.CE.LocalRevision.Events.RevisionPicked.NAME, function () {
            $('body').trigger('MPCESceneRevisionPicked');
          });
          parent.CE.EventDispatcher.Dispatcher.addListener(parent.CE.WPRevision.Events.RevisionPicked.NAME, function () {
            $('body').trigger('MPCESceneRevisionPicked');
          });
        }
      });
    })(jQuery);

    (function ($) {
      CE.SceneEvents = {};

      CE.SceneEvents.Event = parent.CE.EventDispatcher.Event.extend({}, {});

      CE.SceneEvents.EntityEvent = CE.SceneEvents.Event.extend({}, {
        entityName: null,
        init: function init(entityName) {
          this.entityName = entityName ? entityName : 'Widget';
        },
        getEntityName: function getEntityName() {
          return this.entityName;
        }
      });

      CE.SceneEvents.EntitySettingsEvent = CE.SceneEvents.EntityEvent.extend({}, {
        propName: null,
        init: function init(entityName, propName) {
          this.entityName = entityName;
          this.propName = propName;
        },
        getPropName: function getPropName() {
          return this.propName;
        }
      });

      CE.SceneEvents.StylePresetEvent = CE.SceneEvents.Event.extend({}, {
        presetObj: null,

        init: function init(presetObj) {
          this.presetObj = presetObj || null;
        },

        getPreset: function getPreset() {
          return this.presetObj;
        },
        getPresetLabel: function getPresetLabel() {
          return this.presetObj ? this.presetObj.getLabel() : '';
        }
      });

      CE.SceneEvents.InteractionEvent = CE.SceneEvents.Event.extend({}, {});
    })(jQuery);

    (function ($) {
      CE.SceneEvents.EntityCreated = CE.SceneEvents.EntityEvent.extend({
        NAME: 'scene.entity.created'
      }, {});
      CE.SceneEvents.EntityDuplicated = CE.SceneEvents.EntityEvent.extend({
        NAME: 'scene.entity.duplicated'
      }, {});
      CE.SceneEvents.EntityDeleted = CE.SceneEvents.EntityEvent.extend({
        NAME: 'scene.entity.deleted'
      }, {});
      CE.SceneEvents.EntityMoved = CE.SceneEvents.EntityEvent.extend({
        NAME: 'scene.entity.moved'
      }, {});
      CE.SceneEvents.EntityResized = CE.SceneEvents.EntityEvent.extend({
        NAME: 'scene.entity.resized'
      }, {});
      CE.SceneEvents.EntitySettingsChanged = CE.SceneEvents.EntitySettingsEvent.extend({
        NAME: 'scene.entity_settings.changed'
      }, {});
      CE.SceneEvents.EntityStylesChanged = CE.SceneEvents.EntitySettingsEvent.extend({
        NAME: 'scene.entity_styles.changed'
      }, {});
      CE.SceneEvents.StylePresetDeleted = CE.SceneEvents.StylePresetEvent.extend({
        NAME: 'scene.style_preset.deleted'
      }, {});
      CE.SceneEvents.StylePresetRenamed = CE.SceneEvents.StylePresetEvent.extend({
        NAME: 'scene.style_preset.renamed'
      }, {});
      CE.SceneEvents.StylePresetClassChanged = CE.SceneEvents.StylePresetEvent.extend({
        NAME: 'scene.style_preset.class_changed'
      }, {
        oldClass: '',
        init: function init(preset, oldClass) {
          this._super(preset);

          this.oldClass = oldClass;
        },

        getOldClass: function getOldClass() {
          return this.oldClass;
        }
      });
      CE.SceneEvents.StylePresetCreated = CE.SceneEvents.StylePresetEvent.extend({
        NAME: 'scene.style_preset.created'
      }, {});
      CE.SceneEvents.StylePresetChanged = CE.SceneEvents.StylePresetEvent.extend({
        NAME: 'scene.style_preset.changed'
      }, {});
      CE.SceneEvents.InlineEditorOpened = CE.SceneEvents.InteractionEvent.extend({
        NAME: 'scene.inline_editor.opened'
      }, {});
      CE.SceneEvents.InlineEditorClosed = CE.SceneEvents.InteractionEvent.extend({
        NAME: 'scene.inline_editor.closed'
      }, {});
      CE.SceneEvents.sceneInited = CE.SceneEvents.Event.extend({
        NAME: 'scene.scene.inited'
      }, {});
      CE.SceneEvents.SnapshotApplied = CE.SceneEvents.Event.extend({
        NAME: 'scene.snapshot.applied'
      }, {
        snapshot: null,

        init: function init(snapshot) {
          this.snapshot = snapshot;
        },
        getSnapshot: function getSnapshot() {
          return this.snapshot;
        }
      });
      CE.SceneEvents.SnapshotTakenBefore = CE.SceneEvents.Event.extend({
        NAME: 'scene.snapshot.taken_before'
      }, {
        init: function init() {}
      });
    })(jQuery);

    (function ($) {
      CE.SceneEvents.ContentChanged = parent.CE.EventDispatcher.Event.extend({
        NAME: 'scene.content.changed'
      }, {
        event: null,

        init: function init(event) {
          this.event = event;
        },
        getConcreteEvent: function getConcreteEvent() {
          return this.event;
        }
      });
      var SceneEventSubscriber = parent.CE.EventDispatcher.Subscriber.extend({}, {
        getSubscribedEvents: function getSubscribedEvents() {
          return [[[
          CE.SceneEvents.EntityCreated.NAME, CE.SceneEvents.EntityDeleted.NAME, CE.SceneEvents.EntityDuplicated.NAME, CE.SceneEvents.EntityResized.NAME, CE.SceneEvents.EntityMoved.NAME, CE.SceneEvents.EntitySettingsChanged.NAME, CE.SceneEvents.EntityStylesChanged.NAME, CE.SceneEvents.StylePresetDeleted.NAME, CE.SceneEvents.StylePresetRenamed.NAME, CE.SceneEvents.StylePresetCreated.NAME, CE.SceneEvents.StylePresetChanged.NAME, CE.SceneEvents.SnapshotApplied.NAME], this.proxy(this.onSceneEvent)]];
        },
        onSceneEvent: function onSceneEvent(event) {
          parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.ContentChanged.NAME, new CE.SceneEvents.ContentChanged(event));
        }
      });
      var subscriber = new SceneEventSubscriber();
      parent.CE.EventDispatcher.Dispatcher.addSubscriber(subscriber);
      parent.CE.IframeEventStorage.storeSubscriber(subscriber);
    })(jQuery);

    (function ($) {
      var contentChanged = false;

      CE.SceneState = can.Construct({
        setContentChanged: function setContentChanged(event) {
          contentChanged = true;
        },
        setContentNotChanged: function setContentNotChanged() {
          contentChanged = false;
        },
        isContentChanged: function isContentChanged() {
          return contentChanged;
        }
      }, {
        init: function init() {
          parent.CE.EventDispatcher.Dispatcher.addListener(CE.SceneEvents.ContentChanged.NAME, this.constructor.proxy('setContentChanged'));
          parent.CE.EventDispatcher.Dispatcher.addListener(parent.CE.PageSettingsEvents.SettingsChanged.NAME, this.constructor.proxy('setContentChanged'));
          parent.MP.Editor.onIfr('AfterUpdate', this.constructor.proxy('setContentNotChanged'));
        }
      });
    })(jQuery);

    (function ($) {
      CE.WPMore = can.Construct(
      {
        _instance: null,
        getInstance: function getInstance() {
          if (this._instance === null) {
            this._instance = new CE.WPMore();
          }

          return this._instance;
        }
      },
      {
        $body: $('body'),
        $contentWrapper: parent.CE.Iframe.myThis.sceneContent,
        contentSectionExists: parent.CE.Iframe.myThis.contentSectionExists,
        $moreTag: $('<section class="mpce-wp-more-tag" />'),
        $style: $(),

        pointClass: '.mpce-wp-more-point',
        pointClassName: 'mpce-wp-more-point',
        tagClass: '.mpce-wp-more-tag',
        tagClassName: 'mpce-wp-more-tag',
        pointPosClassNamePrefix: 'mpce-wp-more-point-',
        pointPosClassPrefix: '.mpce-wp-more-point-',
        selectedState: 'wp-more-selected',
        init: function init() {
          this.$contentWrapper = parent.CE.Iframe.myThis.sceneContent;
          this.contentSectionExists = parent.CE.Iframe.myThis.contentSectionExists;
          this.initState();
          this.initStyle();
          this.initEvents();
        },
        initState: function initState() {
          if (this.contentSectionExists && this.$contentWrapper.find(this.tagClass).length) {
            this.$moreTag = this.$contentWrapper.find(this.tagClass);
            CE.Utils.addSceneState(this.selectedState);
            this.fixTagDOMPosition();
          }

          this.$moreTag.attr('title', localStorage.getItem('CEMoreHandlerTitle'));
        },
        initEvents: function initEvents() {
          this.$contentWrapper.on('click', '>' + this.pointClass + ', >.motopress-handle-middle-in>' + this.pointClass + '', this.proxy('onPointClick')).on('click', '>' + this.tagClass, this.proxy('onTagClick')); 

        },
        initStyle: function initStyle() {
          this.$style = $('<style type="text/css" id="mpce-wp-more-style" />');
          this.$body.append(this.$style);
        },
        onPointClick: function onPointClick(e, $el) {
          $el = $(e.target);

          switch (this.getPositionType($el)) {
            case 'first':
              this.$moreTag.prependTo(this.$contentWrapper);
              break;

            case 'last':
              this.$contentWrapper.find('>.motopress-row:last').after(this.$moreTag);
              break;

            case 'middle':
            default:
              $el.parent().before(this.$moreTag);
              break;
          }

          CE.Utils.addSceneState(this.selectedState);
        },
        onTagClick: function onTagClick() {
          CE.Utils.removeSceneState(this.selectedState);
          this.$moreTag.detach();
        },
        fixTagDOMPosition: function fixTagDOMPosition() {
          var moreTagWrapper = this.$moreTag.closest('.motopress-content-wrapper>.motopress-row');

          if (moreTagWrapper.length) {
            moreTagWrapper.after(this.$moreTag);
          }
        },
        getPointTemplate: function getPointTemplate(type) {
          return $('<div />', {
            'class': this.pointClassName + ' ' + this.getPointClassByType(type),
            'title': localStorage.getItem('CEWPMorePointTitle')
          });
        },
        pointAppendTo: function pointAppendTo($el, type) {
          if ($el.length && !this.isPointExists(type)) {
            $el.append(this.getPointTemplate(type));
          }
        },
        pointPrependTo: function pointPrependTo($el, type) {
          if ($el.length && !this.isPointExists(type)) {
            $el.prepend(this.getPointTemplate(type));
          }
        },
        isPointExists: function isPointExists(type) {
          return !!(typeof type !== 'undefined' && type && this.contentSectionExists && this.$contentWrapper.find(this.pointPosClassPrefix + type).length);
        },
        getPointClassByType: function getPointClassByType(type) {
          return typeof type !== 'undefined' && type ? this.pointPosClassNamePrefix + type : '';
        },
        getPositionType: function getPositionType($el) {
          if ($el.hasClass(this.getPointClassByType('first'))) {
            return 'first';
          } else if ($el.hasClass(this.getPointClassByType('last'))) {
            return 'last';
          } else {
            return 'middle';
          }
        }
      });
    })(jQuery);


    can.view.stache('leftbar', "<div id=\"motopress-content-editor-leftbar\" class=\"motopress-default\" tabindex=\"-1\">\n\t\t<div id=\"mpce-leftbar-widgets-wrapper\">\n\t\t\t<div class=\"mpce-navigation\">\n\t\t\t\t<ul>\n\t\t\t\t\t{{#each library}}\n\t\t\t\t\t\t<li class=\"menu-item motopress-leftbar-group motopress-default\">\n\t\t\t\t\t\t\t<div class=\"motopress-leftbar-group-inner motopress-default\">\n\t\t\t\t\t\t\t\t<div class=\"motopress-leftbar-group-icon\" style=\"background-image: url('{{icon}}');\">\n\t\t\t\t\t\t\t\t\t<div class=\"motopress-leftbar-group-active motopress-default active\"></div>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"sub-menu-outer-wrapper\">\n\t\t\t\t\t\t\t\t<div class=\"sub-menu-inner-wrapper\">\n\t\t\t\t\t\t\t\t\t<ul class=\"sub-menu right motopress-ce-object-container\">\n\t\t\t\t\t\t\t\t\t\t{{#each objects}}}\n\t\t\t\t\t\t\t\t\t\t<li class=\"menu-item\">\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t<section class=\"motopress-ce-object motopress-default-drag\" \n\t\t\t\t\t\t\t\t\t\t\t\tdata-motopress-close-type=\"{{closeType}}\" \n\t\t\t\t\t\t\t\t\t\t\t\tdata-motopress-shortcode=\"{{id}}\" \n\t\t\t\t\t\t\t\t\t\t\t\tdata-motopress-group=\"{{group}}\" \n\t\t\t\t\t\t\t\t\t\t\t\tdata-motopress-parameters=\"{{parameters}}\" \n\t\t\t\t\t\t\t\t\t\t\t\tdata-motopress-styles=\"{{styles}}\"\n\t\t\t\t\t\t\t\t\t\t\t>\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"motopress-ce-object-inner motopress-default\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"motopress-ce-object-dot motopress-default\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"motopress-ce-object-icon\" style=\"background-image: url('{{icon}}');\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"motopress-ce-object-name motopress-default motopress-no-color-text\">{{name}}</span>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</section>\n\t\t\t\t\t\t\t\t\t\t</li>\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t{{/each}}\n\t\t\t\t\t\t\t\t\t</ul>\n\t\t\t\t\t\t\t\t\t<p class=\"sub-menu-wrapper-description\">Drag to stage to insert</p>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</li>\n\t\t\t\t\t{{/each}}\n\t\t\t\t\t{{#templates.length}}\n\t\t\t\t\t\t<li class=\"menu-item motopress-leftbar-group motopress-leftbar-group-templates motopress-default\">\n\t\t\t\t\t\t<div class=\"motopress-leftbar-group-inner motopress-default\">\n\t\t\t\t\t\t\t\t<div class=\"motopress-leftbar-group-icon motopress-leftbar-group-templates-icon\">\n\t\t\t\t\t\t\t\t\t<div class=\"motopress-leftbar-group-active motopress-default active\"></div>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t<div class=\"sub-menu-outer-wrapper\">\n\t\t\t\t\t\t\t\t<div class=\"sub-menu-inner-wrapper\">\n\t\t\t\t\t\t\t\t\t<ul class=\"sub-menu right motopress-ce-object-container\">\n\t\t\t\t\t\t\t\t\t\t{{#each templates}}}\n\t\t\t\t\t\t\t\t\t\t<li class=\"menu-item\">\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t<section class=\"motopress-ce-object motopress-ce-widget-template motopress-default-drag\" data-id=\"{{id}}\">\n\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"motopress-ce-object-inner motopress-default\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"motopress-ce-object-dot motopress-default\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"motopress-ce-object-icon\" style=\"background-image: url('{{icon}}');\">\n\t\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<span class=\"motopress-ce-object-name motopress-default motopress-no-color-text\">{{name}}</span>\n\t\t\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t\t</section>\n\t\t\t\t\t\t\t\t\t\t</li>\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t{{/each}}\n\t\t\t\t\t\t\t\t\t</ul>\n\t\t\t\t\t\t\t\t\t<p class=\"sub-menu-wrapper-description\">Drag to stage to insert</p>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</li>\n\t\t\t\t\t{{/templates.length}}\n\t\t\t\t</ul>\n\t\t\t</div>\n\t\t</div>\n\t\t<div id=\"mpce-leftbar-custom-wrapper\">\n\t\t</div>\n\t\t<div class=\"motopress-leftbar-toggle\">\n\t\t\t<i aria-hidden=\"true\" class=\"motopress-leftbar-toggle-hide fa fa-chevron-circle-left\"></i>\n\t\t\t<i aria-hidden=\"true\" class=\"motopress-leftbar-toggle-show fa fa-chevron-circle-right\"></i>\n\t</div>\n\t</div>\n\t<div class=\"motopress-leftbar-overlap\"></div>");

    (function ($) {
      var leftBar;
      var leftBarOverlap;
      var $html;
      var bodyEl;

      can.Control('CE.LeftBar',
      {
        myThis: null
      },
      {
        width: 56,

        visibility: false,
        active: false,

        templates: null,

        leftbarWrapper: null,
        init: function init(el, atts) {
          $html = $('html');
          bodyEl = $('body');
          CE.LeftBar.myThis = this;

          this._setTemplates(parent.CE.ObjectTemplatesLibrary.myThis.getWidgets().attr());

          this.leftBarWrapper = el;
          this.renderView();
          parent.CE.ObjectTemplatesLibrary.myThis.getWidgets().bind('change', this.proxy(function (data) {
            this._updateTemplates(data.target.attr());
          }));
          this.initStyle();
        },
        makeObjectsDraggable: function makeObjectsDraggable() {
          $.each(leftBar.find('.motopress-ce-widget-template:not([data-motopress-shortcode])'), function (index, obj) {
            var widgetId = $(obj).attr('data-id');
            var savedWidget = parent.CE.ObjectTemplatesLibrary.myThis.getWidgetById(widgetId);
            var atts = savedWidget.atts;
            parent.CE.ShortcodeAtts.setAttsToEl($(obj), atts);
          }); 

          $.each(leftBar.find('.motopress-ce-object:not(.ce_draggable_new_block)'), function (index, obj) {
            new CE.DraggableNewBlock($(obj));
          });
        },
        renderView: function renderView() {
          this.leftBarWrapper.html(can.view('leftbar', {
            library: this._prepareLibrary(),
            templates: this.templates,
            test: window.asd
          }));
          leftBar = this.leftBarWrapper.children('#motopress-content-editor-leftbar');
          leftBarOverlap = this.leftBarWrapper.children('.motopress-leftbar-overlap');
          this.makeObjectsDraggable();
        },
        initStyle: function initStyle() {
          var offset = parseFloat($html.css('padding-left')) + this.width;
          this.$style = $('<style type="text/css" id="mpce-left-bar-style" />');
          this.$style.text('@media (min-width: 769px) { html.mpce-page-offset { padding-left: ' + offset + 'px !important; } }');
          bodyEl.append(this.$style);
        },
        _prepareLibrary: function _prepareLibrary() {
          var library = [];
          $.each(parent.CE.WidgetsLibrary.myThis.getLibrary(), function (groupId, group) {
            if (!group.show) {
              return;
            }

            var objects = [];
            $.each(group.objects, function (objId, objData) {
              if (!objData.show) {
                return;
              }

              var obj = {
                closeType: objData.closeType,
                id: objData.id,
                icon: objData.icon,
                group: objData.groupId,
                name: objData.name
              }; 

              var parametersObj = {};
              $.each(objData['parameters'], function (key) {
                parametersObj[key] = {};
              });
              obj['parameters'] = JSON.stringify(parametersObj); 

              var stylesObj = {};
              $.each(objData['styles'], function (key) {
                stylesObj[key] = {};
              });
              obj['styles'] = JSON.stringify(stylesObj);
              objects.push(obj);
            }); 

            if (!objects.length) {
              return;
            }

            library.push({
              id: group.id,
              name: group.name,
              icon: group.icon,
              objects: objects
            });
          });
          return library;
        },

        _setTemplates: function _setTemplates(templates) {
          return this.templates = new can.List(templates);
        },

        _updateTemplates: function _updateTemplates(templates) {
          this.templates.replace(templates);
          this.makeObjectsDraggable();
        },

        disable: function disable() {
          leftBarOverlap.show();
        },

        enable: function enable() {
          leftBarOverlap.hide();
        },
        setActive: function setActive() {
          this.active = true;
        },
        setInactive: function setInactive() {
          this.active = false;
        },
        isActive: function isActive() {
          return this.active;
        },
        setVisible: function setVisible() {
          this.visibility = true;
        },
        setInvisible: function setInvisible() {
          this.visibility = false;
        },
        isVisible: function isVisible() {
          return this.visibility;
        },
        hide: function hide(hard) {
          hard = typeof hard === 'undefined' ? false : hard;

          if (hard || this.isActive()) {
            this.active = false;
            this.setInvisible();
            leftBar.addClass('mpce-leftbar-hidden');
            leftBarOverlap.addClass('mpce-leftbar-overlap-hidden');
            $html.removeClass('mpce-page-offset');
            parent.CE.Iframe.myThis.setSceneWidth();
            CE.Resizer.myThis.updateAllHandles();
            parent.MP.Editor.triggerEverywhere('LeftBarHide');
          }
        },
        show: function show(hard) {
          hard = typeof hard === 'undefined' ? false : hard;

          if (hard || this.isActive()) {
            this.active = true;
            this.setVisible();
            leftBar.removeClass('mpce-leftbar-hidden');
            leftBarOverlap.removeClass('mpce-leftbar-overlap-hidden');
            $html.addClass('mpce-page-offset');
            parent.CE.Iframe.myThis.setSceneWidth();
            CE.Resizer.myThis.updateAllHandles();
            parent.MP.Editor.triggerEverywhere('LeftBarShow');
          }
        },
        getSpace: function getSpace() {
          return this.isVisible() ? this.width : 0;
        },
        getWidth: function getWidth() {
          return this.width;
        },
        '.motopress-leftbar-toggle click': function motopressLeftbarToggleClick(el, e) {
          var isLeftbarHidden = leftBar.hasClass('mpce-leftbar-hidden');

          if (isLeftbarHidden) {
            this.show(true);
          } else {
            this.hide(true);
          }
        }
      });
    })(jQuery);

    (function ($) {
      CE.Grid = can.Control.extend(
      {
        myThis: null,
        ENTITIES: {
          ROW: 'row',
          COLUMN: 'column',
          WIDGET: 'widget',
          PAGE: 'page'
        }
      },
      {
        columnMarginPiece: null,
        column: null,
        padding: null,
        columnCount: parent.CE.Iframe.myThis.gridObj.row.col,
        columnWidthStatus: null,
        colWidthByNumber: [],
        rowEl: $('<div />', {
          'class': parent.CE.Iframe.myThis.gridObj.row["class"]
        }),
        columnEl: $('<div />', {}),

        setup: function setup(el) {
          var col = null;
          el.append(this.rowEl);

          for (var i = 1; i <= this.columnCount; i++) {
            col = this.columnEl.clone().addClass(parent.CE.Iframe.myThis.gridObj.span["class"] + i).appendTo(this.rowEl);
            this.colWidthByNumber[i] = parseFloat(col.width());
          }

          this.columnEl.clone().addClass(parent.CE.Iframe.myThis.gridObj.span["class"] + 1).appendTo(this.rowEl);
        },
        init: function init(el) {
          CE.Grid.myThis = this;
          this.column = el.find('.' + parent.CE.Iframe.myThis.gridObj.span.minclass + ':last');
          this.setSize();
          parent.MP.Editor.onIfr('Resize', this.proxy('setSize'));
        },
        setSize: function setSize() {
          var columnWidthStatus = this.column.width();
          this.padding = parseFloat(this.column.css('padding-left'));
          this.columnMarginPiece = parseFloat(this.column.css('margin-left'));

          if (this.columnWidthStatus) {
            CE.Resizer.myThis.updateSplittableOptions(null, null, null, 'init');
            CE.Resizer.myThis.updateSplitterHeight();
          }

          this.columnWidthStatus = columnWidthStatus;
        }
      });
    })(jQuery);

    (function ($) {
      CE.ShortcodeStyle = can.Construct(
      {
        generateStateCSS: function generateStateCSS(settings) {
          var important = ' !important;';
          var styles = [];
          $.each(settings, function (name, value) {
            if (value !== '') {
              switch (name) {
                case 'padding-top':
                case 'padding-bottom':
                case 'padding-left':
                case 'padding-right':
                case 'margin-top':
                case 'margin-bottom':
                case 'margin-left':
                case 'margin-right':
                case 'border-top-width':
                case 'border-bottom-width':
                case 'border-left-width':
                case 'border-right-width':
                case 'border-top-left-radius':
                case 'border-top-right-radius':
                case 'border-bottom-left-radius':
                case 'border-bottom-right-radius':
                  if (!isNaN(value)) {
                    styles.push(CE.ShortcodeStyle.generateStyleRule(name, value + 'px' + important));
                  }

                  break;

                case 'background-position-x':
                case 'background-position-y':
                  if (!isNaN(value)) {
                    styles.push(CE.ShortcodeStyle.generateStyleRule(name, value + '%' + important));
                  }

                  break;

                case 'background-gradient':
                  if (value !== '') {
                    var gradientParameters = parent.CE.CtrlGradient.parseGradientStr(value);

                    if (gradientParameters && !(gradientParameters['initial-color'] === '' && gradientParameters['final-color'] === '')) {
                      var angle = gradientParameters['angle'] + 'deg';
                      var initialColor = gradientParameters['initial-color'] !== '' ? gradientParameters['initial-color'] : 'transparent';
                      var finalColor = gradientParameters['final-color'] !== '' ? gradientParameters['final-color'] : 'transparent';
                      value = angle + ',' + initialColor + ',' + finalColor;
                      var gradients = [];
                      gradients.push('-moz-linear-gradient(' + value + ')' + important);
                      gradients.push('-webkit-linear-gradient(' + value + ')' + important);
                      gradients.push('linear-gradient(' + value + ')' + important);
                      styles.push(CE.ShortcodeStyle.generateStyleRule('background-image', gradients));
                    }
                  }

                  break;

                case 'background-image-type':
                  if (value === 'none') {
                    styles.push(CE.ShortcodeStyle.generateStyleRule('background-image', 'none' + important));
                  }

                  break;

                case 'background-image':
                  styles.push(CE.ShortcodeStyle.generateStyleRule(name, 'url(' + value + ')' + important));
                  break;

                case 'background-color':
                case 'border-color':
                case 'color':
                  styles.push(CE.ShortcodeStyle.generateStyleRule(name, value + important));
                  break;

                case 'background-position':
                  if (value !== 'custom') {
                    styles.push(CE.ShortcodeStyle.generateStyleRule(name, value + important));
                  }

                  break;

                case 'border-radius':
                  styles.push(CE.ShortcodeStyle.generateStyleRule('-webkit-' + name, value + important));
                  styles.push(CE.ShortcodeStyle.generateStyleRule('-moz-' + name, value + important));
                  styles.push(CE.ShortcodeStyle.generateStyleRule(name, value + important));
                  break;

                default:
                  styles.push(CE.ShortcodeStyle.generateStyleRule(name, value + important));
                  break;
              }
            }
          });
          return styles;
        },

        generateCSS: function generateCSS(className, settings, emulateMode) {
          if (_typeof(emulateMode) === undefined) emulateMode = false;
          if (className === null) className = '%className%'; 

          var emulateHoverMode = emulateMode;
          emulateMode = false;
          var css = '';

          if (settings.hasOwnProperty('up')) {
            $.each(CE.ShortcodeStyle.generateStateCSS(settings.up), function (index, styleRule) {
              css += '.' + className + styleRule;

              if (emulateMode) {
                css += '.' + className + '.' + CE.ShortcodeStyle.previewStateClassPrefix + 'up' + styleRule;
              }
            });
          }

          if (settings.hasOwnProperty('hover')) {
            $.each(CE.ShortcodeStyle.generateStateCSS(settings.hover), function (index, styleRule) {
              if (emulateHoverMode) {
                css += '.' + className + '.' + CE.ShortcodeStyle.previewStateClassPrefix + 'hover' + styleRule;
                css += '.' + className + ':hover' + styleRule;
              } else {
                css += '.' + className + ':hover' + styleRule;
              }
            });
          }

          if (settings.hasOwnProperty('tablet')) {
            $.each(CE.ShortcodeStyle.generateStateCSS(settings.tablet), function (index, styleRule) {
              if (emulateMode) {
                css += '.' + className + '.' + CE.ShortcodeStyle.previewStateClassPrefix + 'tablet' + styleRule;
              } else {
                css += '@media (max-width: 768px) { .' + className + styleRule + '}';
              }
            });
          }

          if (settings.hasOwnProperty('mobile')) {
            $.each(CE.ShortcodeStyle.generateStateCSS(settings.mobile), function (index, styleRule) {
              if (emulateMode) {
                css += '.' + className + '.' + CE.ShortcodeStyle.previewStateClassPrefix + 'mobile' + styleRule;
              } else {
                css += '@media (max-width: 320px) { .' + className + styleRule + '}';
              }
            });
          }

          return css;
        },
        generateStyleRule: function generateStyleRule(name, value) {
          var styleRule = ':not(.' + motopressCE.styleEditor['const'].prefixRuleDisable + name + '){';

          if (!$.isArray(value)) {
            value = [value];
          }

          $.each(value, function () {
            styleRule += name + ':' + this;
          });
          styleRule += '}';
          return styleRule;
        },
        previewStateClassPrefix: 'motopress-ce-preview-state-'
      },
      {
        settings: null,
        css: null,
        objectType: '',
        emulateCSS: null,
        id: null,

        init: function init(id, settings, css, options) {
          options = $.extend({
            objectType: ''
          }, options);
          this.id = id;
          this.objectType = options.objectType;
          this.settings = can.isPlainObject(settings) ? settings : {};

          if (typeof css === 'string') {
            this.css = css;
            this.emulateCSS = CE.ShortcodeStyle.generateCSS(this.getClassName(), this.settings, true);
          } else {
            this.regenerateCSS();
          }

          this.detectStyleTag();
          this.updateStyleTag(); 
        },
        clone: function clone() {
          throw new Error('Must be implemented in sub-class!');
        },

        getId: function getId() {
          return this.id;
        },

        getClassName: function getClassName() {
          return this.id;
        },

        getCSS: function getCSS() {
          return this.css;
        },

        getEmulateCSS: function getEmulateCSS() {
          return this.emulateCSS;
        },

        getSettings: function getSettings() {
          return $.extend({}, this.settings);
        },

        getStateSettings: function getStateSettings(state) {
          return this.settings.hasOwnProperty(state) ? $.extend({}, this.settings[state]) : {};
        },

        getObjectType: function getObjectType() {
          return this.objectType;
        },

        isEmpty: function isEmpty() {
          var isEmpty = true;
          $.each(this.settings, function (state, details) {
            $.each(details, function (name, value) {
              if (value !== '') {
                isEmpty = false;
                return;
              }
            });
            if (!isEmpty) return;
          });
          return isEmpty;
        },
        regenerateCSS: function regenerateCSS() {
          this.css = CE.ShortcodeStyle.generateCSS(this.getClassName(), this.settings, false);
          this.emulateCSS = CE.ShortcodeStyle.generateCSS(this.getClassName(), this.settings, true);
        },
        'update': function update(settings) {
          this.settings = settings;
          this.regenerateCSS();
          this.updateStyleTag(); 
        },
        updateState: function updateState(state, settings) {
          this.settings[state] = settings;
          this.regenerateCSS();
          this.updateStyleTag(); 
        },
        'delete': function _delete() {
          this.styleTag.remove(); 
        },
        updateStyleTag: function updateStyleTag() {
          var css = CE.StyleEditor.myThis.isEmulateCSSMode() ? this.getEmulateCSS() : this.getCSS();
          this.styleTag.text(css);
        },
        detectStyleTag: function detectStyleTag() {
          var styleTag = this.stylesWrapper.children('#' + this.id);

          if (!styleTag.length) {
            styleTag = this.generateStyleTag();
          }

          this.styleTag = styleTag;
        },
        generateStyleTag: function generateStyleTag() {
          var styleTag = $('<style />', {
            'id': this.id,
            'text': this.getCSS()
          });
          this.stylesWrapper.append(styleTag);
          return styleTag;
        }
      });
    })(jQuery);

    (function ($) {
      CE.ShortcodeStyle('CE.ShortcodePrivateStyle',
      {
        stylesWrapper: $('#motopress-ce-private-styles-wrapper'),
        addRenderedStyleTags: function addRenderedStyleTags(styleTags) {
          styleTags.filter(function (index, privateStyleTag) {
            return CE.ShortcodePrivateStyle.stylesWrapper.children('#' + $(privateStyleTag).attr('id')).length === 0;
          });
          CE.ShortcodePrivateStyle.stylesWrapper.append(styleTags);
        }
      },
      {
        styleTag: null,
        stylesWrapper: null,
        clone: function clone() {
          return new CE.ShortcodePrivateStyle(this.className, $.extend(true, {}, this.settings), this.css, {
            objectType: this.objectType
          });
        },
        init: function init(name, settings, css, options) {
          this.stylesWrapper = CE.ShortcodePrivateStyle.stylesWrapper;

          this._super(name, settings, css, options);
        },
        clear: function clear() {
          this.update({});
          parent.CE.Save.changeContent();
        }
      });
    })(jQuery);

    (function ($) {
      CE.ShortcodeStyle('CE.ShortcodePresetStyle',
      {
        stylesWrapper: $('#motopress-ce-preset-styles-wrapper')
      },
      {
        styleTag: null,
        label: '',
        stylesWrapper: null,

        init: function init(className, settings, css, options) {
          options = $.extend({
            label: ''
          }, options);
          this.stylesWrapper = CE.ShortcodePresetStyle.stylesWrapper;
          this.label = options.label;

          this._super(className, settings, css, options);
        },
        getLabel: function getLabel() {
          return this.label;
        },

        setLabel: function setLabel(label) {
          this.label = label;
        }
      });
    })(jQuery);

    (function ($) {
      CE.StyleEditor = can.Construct(
      {
        myThis: null
      },
      {
        privateStyles: {},

        presetStyles: {},

        presetsLastId: null,
        presetDefaultLabel: null,
        prefixPresetClass: null,
        prefixPrivateClass: null,
        presetSaveModal: null,
        emulateCSSMode: true,

        init: function init(args) {
          CE.StyleEditor.myThis = this;
          this.presetSaveModal = parent.CE.PresetSaveModal.myThis;
          this.prefixPresetClass = args["const"].prefixPresetClass;
          this.prefixPrivateClass = args["const"].prefixPrivateClass;
          this.presetsLastId = args.presetsLastId;
          this.presetDefaultLabel = args["const"].presetDefaultLabel;
          this.initPrivateStyles(args["private"]);
          this.initPresetStyles(args.presets);
        },

        initPresetStyles: function initPresetStyles(presets) {
          var $this = this;
          $.each(presets, function (name, properties) {
            properties = $.extend({
              settings: {},
              css: '',
              objectType: ''
            }, properties);
            $this.presetStyles[name] = new CE.ShortcodePresetStyle(name, properties.settings, properties.css, properties);
          });
        },

        getPresetsLastId: function getPresetsLastId() {
          return this.presetsLastId;
        },
        incPresetsLastId: function incPresetsLastId() {
          this.presetsLastId++;
          return this.presetsLastId;
        },

        initPrivateStyles: function initPrivateStyles(privates) {
          var $this = this;
          this.privateStyles = {};
          $.each(privates, function (name, properties) {
            properties = $.extend({
              css: '',
              settings: {},
              objectType: ''
            }, properties);
            $this.privateStyles[name] = new CE.ShortcodePrivateStyle(name, properties.settings, null, properties.objectType);
          });
        },

        clonePrivateStyles: function clonePrivateStyles(privates) {
          var privatesClone = {};
          var privatesToClone = privates === undefined ? this.privateStyles : privates;
          $.each(privatesToClone, function (name, inst) {
            privatesClone[name] = inst.clone();
          });
          return privatesClone;
        },

        applyPrivateStyles: function applyPrivateStyles(privates) {
          this.privateStyles = privates;
        },
        getPrivateStylesString: function getPrivateStylesString() {
          var styles = {};
          $.each(this.privateStyles, function (className, obj) {
            if (!obj.isEmpty()) {
              styles[className] = {
                'settings': obj.getSettings(),
                'objectType': obj.getObjectType(),
                'css': obj.getCSS()
              };
            }
          });
          return JSON.stringify(styles);
        },
        getPrivateStyleInstance: function getPrivateStyleInstance(classes, objectType) {
          var privateClass = this.retrievePrivateClass(classes);

          if (privateClass !== '' && this.privateStyles.hasOwnProperty(privateClass)) {
            return this.privateStyles[privateClass];
          } else {
            return this.createPrivateStyle(null, null, objectType);
          }
        },

        getPresetStyleInstance: function getPresetStyleInstance(classes) {
          var presetClass = this.retrievePresetClass(classes);
          return this.isExistsPreset(presetClass) ? this.presetStyles[presetClass] : null;
        },

        createPrivateStyle: function createPrivateStyle(settings, name, objectType) {
          if (!name) {
            name = this.generatePrivateClass();
          }

          this.privateStyles[name] = new CE.ShortcodePrivateStyle(name, settings, null, {
            objectType: objectType
          });
          return this.privateStyles[name];
        },

        retrievePrivateClass: function retrievePrivateClass(classes) {
          var suffix = '[\\d]+-[A-F\\d]{13}';
          var privateClassRegExp = new RegExp('(?:^|\\s)+' + '(' + this.prefixPrivateClass + '(?:' + suffix + '))' + '(?:$|\\s)+', 'i');
          var privateClass = privateClassRegExp.exec(classes);
          return privateClass !== null && privateClass.length === 2 ? privateClass[1] : '';
        },

        generatePrivateClass: function generatePrivateClass() {
          var postId = parent.motopressCE.postID;
          var uniqueId = parent.MP.Utils.uniqid(this.prefixPrivateClass + postId + '-');
          return !this.privateStyles.hasOwnProperty(uniqueId) ? uniqueId : this.generatePrivateClass();
        },
        getPresetsString: function getPresetsString() {
          var styles = {};
          $.each(this.presetStyles, function (className, obj) {
            styles[className] = {
              'settings': obj.getSettings(),
              'objectType': obj.getObjectType(),
              'css': obj.getCSS(),
              'label': obj.getLabel()
            };
          });
          return JSON.stringify(styles);
        },
        getPresetsList: function getPresetsList(withNone) {
          var list = {};
          $.each(this.presetStyles, function (index, presetObj) {
            list[presetObj.getId()] = presetObj.getLabel();
          });

          if (withNone) {
            list = $.extend({
              '': localStorage.getItem('CENone')
            }, list);
          }

          return list;
        },
        getPresetsListSelect2: function getPresetsListSelect2() {
          var list = [];
          $.each(this.presetStyles, function (index, presetObj) {
            list.push({
              id: presetObj.getId(),
              text: presetObj.getLabel()
            });
          });
          return list;
        },
        generatePresetClassName: function generatePresetClassName() {
          return this.prefixPresetClass + this.incPresetsLastId();
        },

        createPreset: function createPreset(settings, options) {
          options = $.extend({
            label: '',
            objectType: ''
          }, options);
          var presetName = this.generatePresetClassName();

          if (!options.label.length) {
            options.label = this.presetDefaultLabel + this.getPresetsLastId();
          }

          this.presetStyles[presetName] = new CE.ShortcodePresetStyle(presetName, settings, undefined, options);
          parent.CE.Save.changeContent();
          return this.presetStyles[presetName];
        },
        deletePreset: function deletePreset(presetName) {
          if (this.isExistsPreset(presetName)) {
            this.presetStyles[presetName]["delete"]();
            delete this.presetStyles[presetName];
            parent.CE.Save.changeContent();
          }

          return true;
        },
        isExistsPreset: function isExistsPreset(presetClass) {
          return this.presetStyles.hasOwnProperty(presetClass);
        },

        retrievePresetClass: function retrievePresetClass(classes) {
          var suffix = '[\\d]+';
          var presetClassRegExp = new RegExp('(?:^|\\s)+' + '(' + this.prefixPresetClass + '(?:' + suffix + '))' + '(?:$|\\s)+', 'i');
          var presetClass = presetClassRegExp.exec(classes);
          return presetClass !== null && presetClass.length === 2 ? presetClass[1] : '';
        },

        changePrivateClassToDuplicated: function changePrivateClassToDuplicated(classStr) {
          if (classStr !== '') {
            var privateClass = CE.StyleEditor.myThis.retrievePrivateClass(classStr);

            if (privateClass !== '') {
              var newPrivateStyle = CE.StyleEditor.myThis.duplicatePrivateStyle(privateClass);
              classStr = parent.MP.Utils.replaceClassInString(privateClass, newPrivateStyle.getId(), classStr);
            }
          }

          return classStr;
        },

        duplicatePrivateStyle: function duplicatePrivateStyle(privateClass) {
          var newPrivateStyle;

          if (this.privateStyles.hasOwnProperty(privateClass)) {
            var protoStyle = this.privateStyles[privateClass];
            newPrivateStyle = this.createPrivateStyle(protoStyle.getSettings(), protoStyle.getObjectType());
          } else {
            newPrivateStyle = this.createPrivateStyle();
          }

          parent.CE.Save.changeContent();
          return newPrivateStyle;
        },
        setEmulateCSSMode: function setEmulateCSSMode() {
          this.emulateCSSMode = true;
          $.each(this.presetStyles, function () {
            this.updateStyleTag();
          });
          $.each(this.privateStyles, function () {
            this.updateStyleTag();
          });
        },
        unsetEmulateCSSMode: function unsetEmulateCSSMode() {
          this.emulateCSSMode = false;
          $.each(this.presetStyles, function () {
            this.updateStyleTag();
          });
          $.each(this.privateStyles, function () {
            this.updateStyleTag();
          });
        },
        isEmulateCSSMode: function isEmulateCSSMode() {
          return this.emulateCSSMode;
        },

        hasObjectPrivateStyle: function hasObjectPrivateStyle(obj) {
          var classes = this._retrieveCustomStyleClasses(obj);

          if (!this.retrievePrivateClass(classes)) {
            return false;
          }

          var privateStyleObj = this.getPrivateStyleInstance(classes);
          return !privateStyleObj.isEmpty();
        },

        mergeStylesToNewPreset: function mergeStylesToNewPreset(obj) {
          var ctrl = obj.control(CE.Controls); 

          ctrl.display();
          var customStyleCtrl = ctrl.stylesCtrl.getCtrlByName('mp_custom_style');
          var classes = customStyleCtrl.get();
          var privateStyleObj = this.getPrivateStyleInstance(classes, obj.attr('data-motopress-shortcode'));
          var presetStyleObj = this.getPresetStyleInstance(classes);
          var newPresetSettings = privateStyleObj.getSettings();

          if (presetStyleObj) {
            newPresetSettings = $.extend(true, {}, presetStyleObj.getSettings(), newPresetSettings);
          }

          var presetOptions = {
            objectType: privateStyleObj.getObjectType()
          };
          var newPreset = this.createPreset(newPresetSettings, presetOptions);
          privateStyleObj.clear();
          customStyleCtrl.selectPreset(newPreset.getId());
          ctrl.stylesCtrl.changeProperty(customStyleCtrl);
        },

        savePresets: function savePresets() {
          return $.ajax({
            url: parent.motopress.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
              action: 'motopress_ce_save_presets',
              nonce: parent.motopressCE.nonces.motopress_ce_save_presets,
              postID: parent.motopressCE.postID,
              presetsData: {
                'presetsLastId': this.getPresetsLastId(),
                'presets': this.getPresetsString()
              }
            }
          });
        },

        _retrieveCustomStyleClasses: function _retrieveCustomStyleClasses(obj) {
          var styleAtts = obj.attr('data-motopress-styles') ? JSON.parse(obj.attr('data-motopress-styles')) : {};
          return styleAtts.hasOwnProperty('mp_custom_style') ? styleAtts.mp_custom_style.value : '';
        }
      });
    })(jQuery);

    (function ($) {
      CE.Link = can.Construct(
      {
        myThis: null,
        wpLink: null,
        submitCallback: null,
        closeCallback: null,
        inputs: {}
      },
      {
        init: function init() {
          CE.Link.myThis = this;
          CE.Link.wpLink = parent.wpLink;
          CE.Link.inputs.submit = $('#wp-link-submit', parent.document);
          CE.Link.inputs.cancel = $('#wp-link-cancel', parent.document);

          if (parent.MP.Utils.version_compare(parent.motopressCE.settings.wp.wordpress_version, '4.2', '>=')) {
            CE.Link.inputs.url = $('#wp-link-url', parent.document);
            CE.Link.inputs.title = $('#wp-link-text', parent.document);
            CE.Link.inputs.openInNewTab = $('#wp-link-target', parent.document);
          } else {
            CE.Link.inputs.url = $('#url-field', parent.document);
            CE.Link.inputs.title = $('#link-title-field', parent.document);
            CE.Link.inputs.openInNewTab = $('#link-target-checkbox', parent.document);
          } 


          if (!$('#wp-link-classes', parent.document).length) {
            $('#link-options .link-target', parent.document).before('<div class="wp-link-classes-field">\n' + '<label><span>' + localStorage.getItem('CELinkClass') + '</span>\n' + '<input id="wp-link-classes" type="text"></label>\n' + '</div>');
          }

          CE.Link.inputs.classes = $('#wp-link-classes', parent.document);
          CE.Link.inputs.classes.closest('div').show();
        },
        open: function open(element, showObj, submitCallback, cancelCallback) {
          var show = {
            url: true,
            title: true,
            target: true
          };
          if (typeof showObj !== 'undefined') $.extend(show, showObj);
          if (typeof CE.Link.wpLink == 'undefined') return;
          CE.Link.submitCallback = submitCallback;
          CE.Link.inputs.submit.off('mousedown.motopress-wplink-submit').on('mousedown.motopress-wplink-submit', this.wpSubmitButtonHandler);
          CE.Link.inputs.cancel.off('click.motopress-wplink-cancel').on('click.motopress-wplink-cancel', cancelCallback);

          if (!parent.tinyMCE.activeEditor) {
            parent.CE.CodeModal.myThis.switchVisual();
            $.when(parent.motopressCE.tinyMCEInited['motopresscodecontent']).done(this.proxy(function () {
              this.open(element, showObj, submitCallback, cancelCallback);
            }));
            return;
          }

          parent.wpActiveEditor = parent.tinyMCE.activeEditor.id;
          var url = null;

          CE.Link.wpLink.setDefaultValues = function () {
            var openInNewTab = null,
                textarea = null;

            if ($.isPlainObject(element)) {
              url = element.href; 

              openInNewTab = element.target === '_blank' ? true : false;
              textarea = element.textarea;
            } else {
              url = element.val();
              textarea = element;
            }

            if (url === '' || url === '#') url = 'http://';
            CE.Link.inputs.url.val(url); 

            CE.Link.inputs.openInNewTab.prop('checked', openInNewTab);
            CE.Link.inputs.classes.val('');
            CE.Link.wpLink.textarea = textarea;
          };

          CE.Link.wpLink.open(); 

          if (!url) {
            if ($.isPlainObject(element)) {
              url = element.href;
            } else {
              url = element.val();
            }

            CE.Link.inputs.url.val(url);
          }

          if (typeof CE.Link.inputs.close === 'undefined') CE.Link.inputs.close = $('.ui-dialog-titlebar-close', parent.document);
          CE.Link.inputs.close.off('mousedown.motopress-wplink-close').on('mousedown.motopress-wplink-close', cancelCallback); 

          if (!show.url) CE.Link.inputs.url.closest('div').hide();
          if (!show.title) CE.Link.inputs.title.closest('div').hide();
          if (!show.target) CE.Link.inputs.openInNewTab.closest('div').hide();
          CE.Link.inputs.classes.closest('div').show(); 

          parent.CE.Iframe.myThis.wpLinkCloseCallback(this.onDialogClose);
        },
        wpCancelButtonHandler: function wpCancelButtonHandler(event) {
          CE.Link.wpLink.close();
          return false;
        },
        wpSubmitButtonHandler: function wpSubmitButtonHandler(event) {
          var attrs = CE.Link.wpLink.getAttrs();
          if (!attrs.href || attrs.href == 'http://') return;
          attrs.classes = CE.Link.inputs.classes.val(); 

          if (CE.Link.submitCallback) CE.Link.submitCallback(attrs);
          CE.Link.wpLink.close();
          return false;
        },
        onDialogClose: function onDialogClose() {
          CE.Link.inputs.submit.off('mousedown.motopress-wplink-submit'); 

          CE.Link.inputs.close.off('mousedown.motopress-wplink-close'); 

          CE.Link.inputs.url.closest('div').show();
          CE.Link.inputs.title.closest('div').show();
          CE.Link.inputs.openInNewTab.closest('div').show();
          CE.Link.inputs.classes.closest('div').hide();
        }
      });
    })(jQuery);

    (function ($) {
      CE.Style = can.Construct(
      {
        gridProps: null,
        props: {
          mp_custom_style: {
            type: 'style_editor',
            label: localStorage.getItem('CECustomStyle'),
            description: localStorage.getItem('CECustomStyleDesc'),
            parameters: {
              'background-color': {
                'type': 'color-picker',
                'label': localStorage.getItem('CEBgColor'),
                'default': ''
              },
              'color': {
                'type': 'color-picker',
                'label': 'Text Color',
                'default': ''
              },
              'background-image-type': {
                'type': 'radio-buttons',
                'label': 'Background Type',
                'default': 'image',
                'list': {
                  'image': 'Image',
                  'gradient': 'Gradient',
                  'none': 'None'
                }
              },
              'background-image': {
                'type': 'image-src',
                'label': localStorage.getItem('CEBgImage'),
                'default': '',
                'dependency': {
                  'parameter': 'background-image-type',
                  'value': 'image'
                }
              },
              'background-repeat': {
                'type': 'select',
                'label': localStorage.getItem('CEBgImageRepeat'),
                'default': '',
                'list': {
                  '': localStorage.getItem('CEDefault'),
                  'repeat': localStorage.getItem('CERepeat'),
                  'repeat-x': localStorage.getItem('CERepeatX'),
                  'repeat-y': localStorage.getItem('CERepeatY'),
                  'no-repeat': localStorage.getItem('CENoRepeat')
                },
                'dependency': {
                  'parameter': 'background-image',
                  'except': ''
                }
              },
              'background-size': {
                'type': 'select',
                'label': localStorage.getItem('CEBgImageSize'),
                'default': '',
                'list': {
                  '': localStorage.getItem('CEDefault'),
                  'cover': localStorage.getItem('CECover'),
                  'contain': localStorage.getItem('CEContain'),
                  'auto': localStorage.getItem('CEAuto')
                },
                'dependency': {
                  'parameter': 'background-image',
                  'except': ''
                }
              },
              'background-position': {
                'type': 'select',
                'label': localStorage.getItem('CEBgImagePosition'),
                'default': '',
                'list': {
                  '': localStorage.getItem('CEDefault'),
                  'center center': localStorage.getItem('CECenter') + ' ' + localStorage.getItem('CEMiddle'),
                  'center top': localStorage.getItem('CECenter') + ' ' + localStorage.getItem('CETop'),
                  'center bottom': localStorage.getItem('CECenter') + ' ' + localStorage.getItem('CEBottom'),
                  'left top': localStorage.getItem('CELeft') + ' ' + localStorage.getItem('CETop'),
                  'left center': localStorage.getItem('CELeft') + ' ' + localStorage.getItem('CEMiddle'),
                  'left bottom': localStorage.getItem('CELeft') + ' ' + localStorage.getItem('CEBottom'),
                  'right top': localStorage.getItem('CERight') + ' ' + localStorage.getItem('CETop'),
                  'right center': localStorage.getItem('CERight') + ' ' + localStorage.getItem('CECenter'),
                  'right bottom': localStorage.getItem('CERight') + ' ' + localStorage.getItem('CEBottom'),
                  'custom': localStorage.getItem('CECustom')
                },
                'dependency': {
                  'parameter': 'background-image',
                  'except': ''
                }
              },
              'background-position-x': {
                'type': 'spinner',
                'label': localStorage.getItem('CEBgImagePositionX'),
                'min': 0,
                'max': 100,
                'step': 1,
                'default': '50',
                'dependency': {
                  'parameter': 'background-position',
                  'value': 'custom'
                }
              },
              'background-position-y': {
                'type': 'spinner',
                'label': localStorage.getItem('CEBgImagePositionY'),
                'min': 0,
                'max': 100,
                'step': 1,
                'default': '50',
                'dependency': {
                  'parameter': 'background-position',
                  'value': 'custom'
                }
              },
              'background-attachment': {
                'type': 'select',
                'label': localStorage.getItem('CEBgImageAttachment'),
                'default': '',
                'list': {
                  '': localStorage.getItem('CEDefault'),
                  'fixed': localStorage.getItem('CEFixed'),
                  'scroll': localStorage.getItem('CEScroll')
                },
                'dependency': {
                  'parameter': 'background-image',
                  'except': ''
                }
              },
              'background-gradient': {
                'type': 'gradient-picker',
                'label': '',
                'default': '',
                'dependency': {
                  'parameter': 'background-image-type',
                  'value': 'gradient',
                  'needDependenceValue': true
                }
              },
              'padding-top': {
                'type': 'text',
                'label': localStorage.getItem('CEPaddingTop'),
                'default': ''
              },
              'padding-bottom': {
                'type': 'text',
                'label': localStorage.getItem('CEPaddingBottom'),
                'default': ''
              },
              'padding-left': {
                'type': 'text',
                'label': localStorage.getItem('CEPaddingLeft'),
                'default': ''
              },
              'padding-right': {
                'type': 'text',
                'label': localStorage.getItem('CEPaddingRight'),
                'default': ''
              },
              'margin-top': {
                'type': 'text',
                'label': localStorage.getItem('CEMarginTop'),
                'default': ''
              },
              'margin-bottom': {
                'type': 'text',
                'label': localStorage.getItem('CEMarginBottom'),
                'default': ''
              },
              'margin-left': {
                'type': 'text',
                'label': localStorage.getItem('CEMarginLeft'),
                'default': ''
              },
              'margin-right': {
                'type': 'text',
                'label': localStorage.getItem('CEMarginRight'),
                'default': ''
              },
              'border-style': {
                'type': 'select',
                'label': localStorage.getItem('CEBorderStyle'),
                'default': '',
                'list': {
                  '': localStorage.getItem('CEDefault'),
                  'none': localStorage.getItem('CENone'),
                  'solid': localStorage.getItem('CESolid'),
                  'dotted': localStorage.getItem('CEDotted'),
                  'dashed': localStorage.getItem('CEDashed'),
                  'double': localStorage.getItem('CEDouble'),
                  'grouve': localStorage.getItem('CEGrouve'),
                  'ridge': localStorage.getItem('CERidge'),
                  'inset': localStorage.getItem('CEInset'),
                  'outset': localStorage.getItem('CEOutset')
                }
              },
              'border-top-width': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderWidthTop'),
                'default': '',
                'dependency': {
                  'parameter': 'border-style',
                  'except': 'none'
                }
              },
              'border-bottom-width': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderWidthBottom'),
                'default': '',
                'dependency': {
                  'parameter': 'border-style',
                  'except': 'none'
                }
              },
              'border-left-width': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderWidthLeft'),
                'default': '',
                'dependency': {
                  'parameter': 'border-style',
                  'except': 'none'
                }
              },
              'border-right-width': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderWidthRight'),
                'default': '',
                'dependency': {
                  'parameter': 'border-style',
                  'except': 'none'
                }
              },
              'border-color': {
                'type': 'color-picker',
                'label': localStorage.getItem('CEBorderColor'),
                'default': '',
                'dependency': {
                  'parameter': 'border-style',
                  'except': 'none'
                }
              },
              'border-top-left-radius': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderRadiusTopLeft'),
                'default': ''
              },
              'border-top-right-radius': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderRadiusTopRight'),
                'default': ''
              },
              'border-bottom-left-radius': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderRadiusBottomLeft'),
                'default': ''
              },
              'border-bottom-right-radius': {
                'type': 'text',
                'label': localStorage.getItem('CEBorderRadiusBottomRight'),
                'default': ''
              }
            }
          },
          mp_style_classes: {
            type: 'select2',
            label: localStorage.getItem('CEStyleClassesLabel'),
            description: localStorage.getItem('CEStyleClassesLabelDesc')
          },
          margin: {
            type: 'margin',
            label: localStorage.getItem('CEMarginLabel'),
            sides: ['top', 'bottom', 'left', 'right'],
            values: ['none', 0, 10, 15, 20, 25, 50, 100],
            'default': ['none', 'none', 'none', 'none'],
            classPrefix: 'motopress-margin-',
            regExp: ''
          }
        },
        getStyleEditorProps: function getStyleEditorProps() {
          return $.extend({}, CE.Style.props['mp_custom_style'].parameters);
        },
        globalPredefinedClasses: {}
      },
      {});
    })(jQuery);

    (function ($) {
      CE.Shortcode = can.Control.extend({
        listensTo: ['render'],
        preloaderClass: 'motopress-small-preload',

        isGrid: function isGrid(groupName) {
          return groupName === parent.CE.WidgetsLibrary.myThis.getGroup('mp_grid').id;
        },
        getChild: function getChild(shortcode) {
          var child = shortcode.children('.motopress-ce-child-detector');

          if (!child.length) {
            child = shortcode.children('div'); 
          }

          return child;
        },

        getShortcodeName: function getShortcodeName(el) {
          return el.attr('data-motopress-shortcode');
        },

        getShortcodeGroup: function getShortcodeGroup(el) {
          return el.attr('data-motopress-group');
        }
      }, {
        group: null,
        isGrid: false,
        shortcodeName: null,
        shortcodeLabel: null,
        shortcode: null,
        child: null,
        groupItemName: null,
        childName: null,
        activeParameter: 'active',
        init: function init(el, args) {
          this.group = CE.Shortcode.getShortcodeGroup(el);
          this.isGrid = CE.Shortcode.isGrid(this.group);
          this.shortcodeName = CE.Shortcode.getShortcodeName(el);
          this.shortcodeLabel = parent.CE.WidgetsLibrary.myThis.getShortcodeLabel(this.group, this.shortcodeName);
          this.groupItemName = null;
          this.childName = null;
          this.activeParameter = 'active';
          this.shortcode = el;

          if (!this.isGrid) {
            this.child = CE.Shortcode.getChild(this.shortcode);
          }
        },
        restoreClone: function restoreClone($elementClone) {
          this.element = $elementClone;
          this.shortcode = this.element;

          if (!this.isGrid) {
            this.child = this.constructor.getChild(this.shortcode);
          }
        },
        setGroupItemName: function setGroupItemName(name) {
          this.groupItemName = name;
        },
        setChildName: function setChildName(name) {
          this.childName = name;
        },
        setActiveParameter: function setActiveParameter(name) {
          this.activeParameter = name;
        }
      });
    })(jQuery);


    (function ($) {
      CE.Shortcode('CE.Controls', {}, {
        settingsForms: null,
        styleForms: null,
        settingsCtrl: null,
        stylesCtrl: null,
        isNew: false,
        init: function init(el, args) {
          this._super(el, args);

          this.isNew = args.isNew;
          this.setupForms();
        },
        restoreClone: function restoreClone($elementClone) {
          this._super($elementClone);

          this.settingsCtrl.restoreClone({
            'formsMainCtrl': this
          });
          this.stylesCtrl.restoreClone({
            'formsMainCtrl': this
          });
        },
        setupForms: function setupForms() {
          this.settingsForms = $('<div />', {
            'class': 'motopress-settings-forms'
          });
          this.settingsCtrl = new parent.CE.SettingsControlsForm(this.settingsForms, {
            'formsMainCtrl': this
          });
          this.styleForms = $('<div />', {
            'class': 'motopress-style-forms'
          });
          this.stylesCtrl = new parent.CE.StyleControlsForm(this.styleForms, {
            'formsMainCtrl': this
          });
        },

        render: function render(flag) {
          if (!this.isGrid) {
            if (flag || typeof flag === 'undefined') {
              CE.Resizer.myThis.updateBottomInHandleMiddle();
              CE.Resizer.myThis.updateHandle();
              CE.Resizer.myThis.updateHandleMiddle();
            }

            CE.Resizer.myThis.updateSplitterHeight();
            this.shortcode.find('a').attr('tabindex', -1);
          }
        },

        renderShortcode: function renderShortcode(status, setDefaults) {
          if (typeof status === 'undefined') {
            status = 'updated';
          }

          if (typeof setDefaults === 'undefined') {
            setDefaults = status === 'created';
          }

          if (this.isGrid) {
            return;
          }

          var handle = this.element.closest('.motopress-block-content').find('>.mpce-widget-tools-wrapper>.mpce-object-panel>.mpce-panel-btn-settings');
          handle.addClass(CE.Shortcode.preloaderClass);
          var atts = parent.CE.ShortcodeAtts.getAttrsFromElement(this.element);
          var wrapRender = typeof this.element.attr('data-motopress-wrap-render') !== 'undefined' ? this.element.attr('data-motopress-wrap-render') : null; 

          if (setDefaults) {
            if (atts.parameters) {
              atts.parameters = this.settingsCtrl.setDefaultAttrs(atts.parameters); 

              var content = this.element.attr('data-motopress-content');
              atts.content = typeof content !== 'undefined' ? content : '';
            }

            if (atts.styles) {
              atts.styles = this.stylesCtrl.setDefaultAttrs(atts.styles);
            }
          } 


          if (typeof this.element.attr('data-motopress-active-item') !== 'undefined') {
            var JSONParameters = $.parseJSON(atts.parameters);
            JSONParameters[this.activeParameter] = {
              value: this.element.attr('data-motopress-active-item')
            };
            atts.parameters = JSON.stringify(JSONParameters);
          }

          $.ajax({
            url: parent.motopress.ajaxUrl,
            type: 'POST',
            dataType: 'html',
            data: {
              action: 'motopress_ce_render_shortcode',
              nonce: parent.motopressCE.nonces.motopress_ce_render_shortcode,
              postID: parent.motopressCE.postID,
              closeType: atts.closeType,
              shortcode: this.shortcodeName,
              wrapRender: wrapRender,
              parameters: atts.parameters,
              styles: atts.styles,
              content: atts.content.replace(/\[\]/g, '[')
            },
            success: this.proxy(function (dirtyData) {
              var privateStyles = $(dirtyData).find('.motopress-ce-private-styles-updates-wrapper').andSelf().filter('.motopress-ce-private-styles-updates-wrapper').children('style');
              CE.ShortcodePrivateStyle.addRenderedStyleTags(privateStyles);
              var data = $(dirtyData).find('.motopress-ce-rendered-content-wrapper').andSelf().filter('.motopress-ce-rendered-content-wrapper').children();
              var span = this.element.closest('.motopress-clmn');

              if (!span.closest('.motopress-row').parent('.motopress-content-wrapper').length) {
                var handleMiddleLast = span.closest('.motopress-row').nextAll('.motopress-handle-middle-in:last');
                var minHeight = parseInt(handleMiddleLast.css('min-height'));
                handleMiddleLast.height(minHeight);
              }

              CE.DragDrop.myThis.resetLastHandleMiddleHeight();
              this.element.html(data);
              this.child = CE.Shortcode.getChild(this.shortcode); 

              var images = this.shortcode.find('img');
              var imgCount = images.length,
                  count = 0;

              if (imgCount) {
                images.on('load', this.proxy(function () {
                  count++;
                  if (count === imgCount) this.element.trigger('render');
                }));
              }

              var parameters = parent.CE.WidgetsLibrary.myThis.getParametersAttrs(this.group, this.shortcodeName),
                  childParams,
                  groupControl;

              if (this.groupItemName) {
                groupControl = parent.jQuery(this.settingsForms[0]).find('.motopress-property-group').control(parent.CE.CtrlGroup);
                childParams = parameters[this.groupItemName];

                if (status === 'created') {
                  if (this.element.attr('data-need-detect-inner-ctrls')) {
                    groupControl.initChildForms();
                    this.element.removeAttr('data-need-detect-inner-ctrls');
                    this.settingsCtrl.display(true);
                  } else if (childParams.hasOwnProperty('items') && childParams.items.hasOwnProperty('count') && childParams.items.count > 0) {
                    parent.jQuery(this.settingsForms.find('> [data-motopress-parameter="' + this.groupItemName + '"] > .motopress-property-group > .motopress-property-button-wrapper > .motopress-property-button-default')).trigger('click', childParams.items.count);
                  } 


                  if (groupControl.dependency) {
                    this.settingsForms.find('[data-motopress-parameter="' + groupControl.dependency.parameter + '"] .motopress-controls').trigger('change', false);
                  }
                }

                groupControl.reassignShortcodes();
              }

              if (status === 'created') {
                $('body').trigger('MPCEObjectCreated', [this.element, this.shortcodeName]);
              } else {
                $('body').trigger('MPCEObjectUpdated', [this.element, this.shortcodeName]);
              } 


              this.element.trigger('render');
              handle.removeClass(CE.Shortcode.preloaderClass); 

              if (this.groupItemName) {
                if (groupControl.accordion.hasClass('ui-accordion')) {
                  var activeIndex = groupControl.accordion.accordion('option', 'active');
                  groupControl.interact('activate', activeIndex);
                }
              } 


              if (status === 'created') {
                parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntityCreated.NAME, new CE.SceneEvents.EntityCreated(this.shortcodeLabel)); 
              } else {
                parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntitySettingsChanged.NAME, new CE.SceneEvents.EntitySettingsChanged(this.shortcodeLabel, '[property]')); 
              }
            }),
            error: function error(jqXHR) {
              var error = $.parseJSON(jqXHR.responseText);

              if ($.isPlainObject(error) && error.hasOwnProperty('message')) {
                if (error.debug) {
                  console.log(error.message);
                } else {
                  parent.MP.Flash.setFlash(error.message, 'error');
                  parent.MP.Flash.showMessage();
                }
              }

              handle.removeClass(CE.Shortcode.preloaderClass);
            }
          });
        },
        display: function display(isNew) {
          this.settingsCtrl.display(isNew);
          this.stylesCtrl.display(isNew);
        }
      });
    })(jQuery);


    (function ($) {
      CE.InlineEditorImageLibrary = can.Construct(
      {
        myThis: null
      },
      {
        frame: null,
        propertyImage: null,
        init: function init() {
          CE.InlineEditorImageLibrary.myThis = this;
          this.frame = new parent.wp.media.view.MediaFrame.Post({
            id: 'motopress-inline-editor-image-library',
            multiple: false,
            library: {
              type: 'image',
              search: null
            },
            contentUserSetting: true,
            syncSelection: true
          }); 

          this.frame.on('attach', function () {
            parent.jQuery('#motopress-inline-editor-image-library').addClass('hide-menu');
          });
        },
        open: function open() {
          this.frame.open();
        },
        onSelect: function onSelect(callback) {
          this.frame.on('insert', this.proxy(function () {
            var state = this.frame.state();
            var attachment = state.get('selection').models[0];
            var display = state.display(attachment).toJSON();
            var result = parent.wp.media.editor.send.attachment(display, attachment.toJSON());
            callback(result);
          }));
        }
      });
    })(jQuery);

    (function ($) {
      CE.Controls('CE.InlineEditor', {
        media: null,
        curElement: null,
        styleFormats: false,
        init: function init() {
          CE.InlineEditor.media = new CE.InlineEditorImageLibrary();
          CE.InlineEditor.media.onSelect(function (attachmentSend) {
            var editor = CE.InlineEditor.curElement.control(CE.InlineEditor).editor;
            attachmentSend.done(function (html) {
              editor.insertContent(html);
              setTimeout(function () {
                editor.focus(); 

                editor.selection.select(editor.getBody(), true);
                editor.selection.collapse(false);
              }, 200);
            });
          });
          $(document).on('mousedown', '.mce-tinymce.mce-panel', function (event) {
            event.preventDefault();
          });
          $(document).on('keydown', '[data-motopress-shortcode="mp_text"], [data-motopress-shortcode="mp_heading"]', function (e) {
            if (e.which === $.ui.keyCode.TAB) {
              CE.Selectable.myThis.unselect();
            } else if (e.which === $.ui.keyCode.ESCAPE) {
              if (CE.InlineEditor.curElement) {
                CE.InlineEditor.curElement.control(CE.InlineEditor).close();
              }

              var handle = $(this).closest('.motopress-block-content').find('>.mpce-widget-tools-wrapper>.mpce-object-panel>.mpce-panel-btn-settings');
              CE.Selectable.focusWithoutScroll(CE.Selectable.getFocusAreaBySelected(CE.Selectable.myThis.getShortcodeByHandle(handle)));
            }
          });
        },
        isTinymce: function isTinymce(e) {
          var isTinymce = false;

          if (!$(e.target).hasClass('mpce-widget-select-handle')) {
            var clickedEl = $(e.currentTarget.activeElement);

            if (clickedEl.length && (clickedEl.closest('[data-motopress-shortcode]').length || clickedEl.closest('.mce-tinymce').length || clickedEl.closest('.mce-window').length || clickedEl.closest('.mce-popover').length)) {
              isTinymce = true;
            }
          }

          return isTinymce;
        },
        destroyAll: function destroyAll() {
          var ctrl;
          parent.CE.Iframe.myThis.sceneContent.find('[data-motopress-shortcode="mp_text"], [data-motopress-shortcode="mp_heading"]').each(function () {
            ctrl = $(this).control(CE.InlineEditor);
            ctrl.destroyEditor();
          });
        },
        reinitAll: function reinitAll() {
          var ctrl;
          parent.CE.Iframe.myThis.sceneContent.find('[data-motopress-shortcode="mp_text"], [data-motopress-shortcode="mp_heading"]').each(function () {
            ctrl = $(this).control(CE.InlineEditor);
            ctrl.initEditor();
          });
        }
      }, {
        id: null,
        isOpen: false,
        editor: null,
        blockContent: null,
        floatpanel: null,
        saved: false,

        contentObserver: null,

        editorLoadedDeferred: null,
        init: function init(el, args) {
          this._super(el, args);

          this.editorLoadedDeferred = $.Deferred();
          this.id = parent.MP.Utils.uniqid();
          this.blockContent = this.element.parent(parent.MP.Utils.convertClassesToSelector(CE.DragDrop.myThis.blockContent.attr('class')));
        },
        destroy: function destroy() {
          CE.InlineEditor.curElement = null;
          if (this.floatpanel) this.floatpanel.remove();

          this._super();
        },
        initEditor: function initEditor() {
          if (!this.editorLoadedDeferred) {
            this.editorLoadedDeferred = $.Deferred();
          }

          var $this = this;

          var _initEditor = function _initEditor() {
            var dialogCls = parent.CE.Panels.SettingsDialog.myThis.dialogClass;
            var dialogClsSelector = parent.MP.Utils.convertClassesToSelector(dialogCls);
            var customUiSelector = dialogClsSelector + ', .mce-toolbar-grp, .image-details, #wp-link-wrap';
            var toolbar = '';
            toolbar += ' mpce_image';
            toolbar += ' wp_img_edit | ';
            toolbar += 'bold italic underline | ';

            if (!$.isEmptyObject(CE.InlineEditor.styleFormats)) {
              toolbar += 'styleselect | ';
            }

            toolbar += 'formatselect | fontsizeselect forecolor | alignleft aligncenter alignright | numlist bullist outdent indent | link unlink blockquote hr removeformat'; 

            tinyMCE.PluginManager.add('wordpress', parent.tinyMCE.AddOnManager.PluginManager.get('wordpress'));
            tinyMCE.PluginManager.add('wpeditimage', parent.tinyMCE.AddOnManager.PluginManager.get('wpeditimage'));
            tinyMCE.init({
              selector: '#' + $this.id,
              inline: true,
              plugins: 'link hr textcolor lists wordpress wpeditimage image',
              visual: false,
              convert_urls: false,
              menubar: false,
              toolbar: toolbar,
              block_formats: 'Paragraph=p;Header 1=h1;Header 2=h2;Header 3=h3;Header 4=h4;Header 5=h5;Header 6=h6;Address=address;Pre=pre;',
              fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 18px 24px 30px 36px 42px 48px 60px 72px 96px',
              language: parent.MP.Settings.lang.tinymce,
              skin: 'motopresscontenteditor',
              browser_spellcheck: parent.MP.Settings.spellcheck,
              style_formats_merge: false,
              style_formats: CE.InlineEditor.styleFormats,
              custom_ui_selector: customUiSelector,
              setup: function setup(editor) {
                $this.editor = editor;
                editor.on('focus', function (e) {
                  $this.isOpen = true;
                  CE.InlineEditor.curElement = $this.element;
                  parent.CE.Panels.SettingsDialog.myThis.close();
                  CE.Selectable.myThis.unselect();
                  CE.DragDrop.myThis.resetLastHandleMiddleHeight();
                  $this.blockContent.addClass('motopress-overflow-visible-important');
                  parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.InlineEditorOpened.NAME, new CE.SceneEvents.InlineEditorOpened());
                });
                editor.on('blur', function (e) {
                  if ($this.isOpen) {
                    $this.isOpen = false;
                    $this.save();
                    $this.blockContent.removeClass('motopress-overflow-visible-important');
                    CE.Resizer.myThis.updateHandle();
                    CE.Selectable.myThis.unselect(); 

                    $this.afterBlur();
                    parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.InlineEditorClosed.NAME, new CE.SceneEvents.InlineEditorClosed());
                  }
                });
                editor.on('nodechange', function (e) {
                  if (e.target != tinyMCE.activeEditor) {
                    e.stopImmediatePropagation();
                  }
                });
                editor.on('execCommand', function (e) {
                  switch (e.command) {
                    case 'mceToggleEditor':
                      if (!$this.floatpanel) {
                        var floatpanel = $('body').children('.mce-floatpanel:last');

                        if (floatpanel.length) {
                          $this.floatpanel = floatpanel;

                          if (floatpanel.height() > 50) {
                            var fixedWidth = 723;
                            var fixedHeight = floatpanel.find('.mce-container-body.mce-flow-layout > [role="toolbar"]:first').outerHeight(true) - 2;
                            floatpanel.width(fixedWidth).height(fixedHeight);
                            var panel2 = floatpanel.children('.mce-container-body.mce-abs-layout').width(fixedWidth).height(fixedHeight);
                            var panel3 = panel2.children('.mce-container.mce-panel').width(fixedWidth).height(fixedHeight);
                            panel3.children('.mce-container-body.mce-stack-layout').width(fixedWidth).height(fixedHeight);
                          }
                        }
                      } 


                      var floatpanelPos = $this.floatpanel.offset().left + $this.floatpanel.width();

                      if (floatpanelPos > $(window).width()) {
                        var left = $this.element.offset().left + $this.element.width() - $this.floatpanel.width();
                        $this.floatpanel.css('left', left);
                      }

                      break;

                  }
                }); 

                editor.addButton('mpce_image', {
                  icon: 'image',
                  tooltip: localStorage.getItem('CEInsertEditImage'),
                  stateSelector: 'img:not([data-mce-object],[data-mce-placeholder]),figure.image',
                  onclick: function onclick(e) {
                    var isActive = this.active();

                    if (isActive) {
                      var $editBtn = this.$el.next();
                      $editBtn.trigger('click');
                    } else {
                      CE.InlineEditor.media.open();
                    }
                  },
                  onpostrender: function onpostrender() {
                    var $editBtn = this.$el.next();
                    $editBtn.hide();
                  }
                });
              },
              init_instance_callback: function init_instance_callback(editor) {
                $this.contentObserver = can.compute(editor.getContent({
                  format: 'html'
                }));
                $this.contentObserver.bind('change', $this.proxy('contentChanged')); 

                var menu = editor.menuItems.formats.menu;
                $.each(menu, function (key1, val1) {
                  if (val1.text === 'Blocks') {
                    $.each(val1.menu, function (key2, val2) {
                      if (val2.text === 'Div') delete val1.menu[key2];
                    });
                  }
                }); 

                $(editor.getBody()).on('blur', function () {
                  $this.save();
                  $this.afterBlur();
                }); 

                $(editor.getBody()).on('focus', function () {
                  $this.saved = false; 
                });
                $this.editorLoadedDeferred.resolve();
              }
            });
          }; 


          if (!parent.tinyMCE.activeEditor) {
            parent.CE.CodeModal.myThis.switchVisual();
            $.when(parent.motopressCE.tinyMCEInited['motopresscodecontent']).done(_initEditor);
          } else {
            var tinymceTimer = setTimeout(function () {
              _initEditor();

              clearTimeout(tinymceTimer);
            }, 0);
          }
        },
        destroyEditor: function destroyEditor() {
          if (this.editorLoadedDeferred && this.editorLoadedDeferred.state() === 'pending') {
            this.editorLoadedDeferred.reject();
          }

          this.editor.destroy();
          this.editor = null;
        },
        ' render': function render() {
          var editorArea = this.shortcode.children('div');
          editorArea.attr('id', this.id);
          this.initEditor();
        },
        open: function open() {
          if (!this.editor) {
            var $this = this;
            $.when(this.editorLoadedDeferred).done(function () {
              $this.open();
            });
          } else {
            this.editor.execCommand('mceToggleEditor', false);
            this.editor.focus();
          }
        },
        close: function close() {
          if (this.editor) {
            this.editor.getBody().blur();
          }
        },
        save: function save() {
          if (!this.saved) {
            var content = this.editor.getContent({
              format: 'html'
            });
            content = content.replace(/\r\n|\n\r|\r|\n/g, '');
            this.shortcode.attr('data-motopress-content', content);
            this.saved = true;
            CE.Resizer.myThis.updateBottomInHandleMiddle();
            CE.Resizer.myThis.updateSplitterHeight();
            CE.Resizer.myThis.updateHandleMiddle();
          }
        },
        afterBlur: function afterBlur() {
          this.contentObserver(this.editor.getContent({
            format: 'html'
          }));
        },
        contentChanged: function contentChanged() {
          parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntitySettingsChanged.NAME, new CE.SceneEvents.EntitySettingsChanged(this.shortcodeLabel, null));
        }
      });
    })(jQuery);

    (function ($) {
      CE.Controls('CE.CodeEditor', {},
      {
        openedContent: null,
        ' render': function render() {
          this.shortcode.find('.row').removeClass('row').addClass(parent.CE.Iframe.myThis.gridObj.row["class"]);
          this.shortcode.find('a').attr('tabindex', -1);
          CE.Resizer.myThis.updateAllHandles();
        },
        open: function open() {
          var editor = parent.CE.CodeModal.myThis.editor;
          parent.CE.CodeModal.currentShortcode = this.element;
          var content = this.shortcode.attr('data-motopress-content');

          if (typeof content !== 'undefined') {
            var expr = new RegExp('\\[\\]', 'ig');
            content = content.replace(expr, '[');
            if (editor !== null) editor.setContent(content, {
              format: 'html'
            });
            parent.CE.CodeModal.myThis.content.val(parent.switchEditors._wp_Nop(content));
          }

          this.openedContent = content;
          parent.CE.CodeModal.myThis.saveHandler = this.proxy('saveHandler');
          parent.CE.CodeModal.myThis.element.mpmodal('show');
        },
        saveHandler: function saveHandler(e) {
          var $this = parent.CE.CodeModal.myThis;
          $this.switchVisual();
          var controller = parent.CE.CodeModal.currentShortcode.control(CE.Controls);
          var content = $this.editor !== null ? $this.editor.getContent({
            format: 'html'
          }) : parent.switchEditors._wp_Autop($this.content.val());

          if (content.length) {
            controller.shortcode.attr('data-motopress-content', content);
          } else {
            controller.shortcode.removeAttr('data-motopress-content');
          }

          if (this.openedContent !== content) {
            controller.renderShortcode();
          }

          $this.element.mpmodal('hide');
        }
      });
    })(jQuery);


    (function ($) {
      CE.Tools = can.Construct(
      {
        myThis: null
      },
      {
        init: function init() {
          CE.Tools.myThis = this;
        },
        getEntityHandle: function getEntityHandle($entity, entityType) {
          var $handle;
          var $edgeEntity;

          if (entityType.is_row) {
            $edgeEntity = parent.MP.Utils.getEdgeRow($entity);
            $handle = $edgeEntity.find('> .mpce-row-tools-wrapper > .mpce-row-panel > .mpce-panel-btn-settings');
          } else if (entityType.is_clmn) {
            $edgeEntity = parent.MP.Utils.getEdgeSpan($entity);
            $handle = $edgeEntity.find('> .mpce-clmn-tools-wrapper > .mpce-clmn-panel > .mpce-panel-btn-settings');
          } else {
            var ctrl = $entity.control(CE.Controls);
            $edgeEntity = parent.MP.Utils.getEdgeSpan(ctrl.isGrid ? $entity : $entity.closest('.motopress-clmn'));
            $handle = $edgeEntity.find('> .motopress-block-content>.mpce-tools-wrapper>.mpce-object-panel>.mpce-panel-btn-settings');
          }

          return $handle;
        }
      });
    })(jQuery);

    (function ($) {
      CE.LayoutManager = can.Construct({
        detectRowLayout: function detectRowLayout(row) {
          var spans = parent.MP.Utils.getEdgeRow(row).children('.motopress-clmn').toArray();
          var layout = $.map(spans, function (span) {
            return parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(span.classList));
          });
          return layout;
        },

        rearrangeSpans: function rearrangeSpans(row, layout) {
          if (JSON.stringify(this.detectRowLayout(row)) == JSON.stringify(layout)) {
            return;
          }

          var spans = parent.MP.Utils.getEdgeRow(row).children('.motopress-clmn');

          if (!layout || spans.length !== layout.length) {
            layout = CE.DragDrop.myThis.getSpanSizeRules(spans.length);
          }

          $.each(spans, function (index, span) {
            CE.Resizer.myThis.setSpanSize($(span), layout[index]);
          });
        },

        moveExcessSpans: function moveExcessSpans(rowFrom, spans) {
          var nestingLvl = parent.MP.Utils.detectRowNestingLvl(rowFrom);
          var row = nestingLvl == 1 ? CE.DragDrop.myThis.rowHtml.clone() : CE.DragDrop.myThis.rowInnerHtml.clone();
          CE.DragDrop.myThis.makeRowEditable(row);
          row.find('.motopress-filler-content').replaceWith(spans);
          rowFrom.after(row);
          CE.DragDrop.myThis.addHandleMiddle(row);
          CE.LayoutManager.rearrangeSpans(row);
          CE.DragDrop.myThis.addHandleMiddle(row);
          CE.DragDrop.myThis.resizer.updateHandle();
          CE.DragDrop.myThis.makeDroppable();
          CE.Resizer.myThis.updateSplittableOptions(row.find('.motopress-clmn'), null, null, 'init');
          CE.Resizer.myThis.updateAllHandles();
        },

        editLayout: function editLayout(row, layout) {
          var currentLayout = CE.LayoutManager.detectRowLayout(row);

          if (currentLayout.length === layout.length) {
            CE.LayoutManager.rearrangeSpans(row, layout);
          } else if (currentLayout.length > layout.length) {
            var excessClmns = parent.MP.Utils.getEdgeRow(row).children('.motopress-clmn').slice(layout.length); 

            excessClmns.remove('.motopress-empty');
            excessClmns = excessClmns.not('.motopress-empty');

            if (excessClmns.length) {
              CE.LayoutManager.moveExcessSpans(row, excessClmns);
            }

            CE.LayoutManager.rearrangeSpans(row, layout);
          } else {
            var missingSpansCount = layout.length - currentLayout.length;
            var $rowPanel = parent.MP.Utils.getEdgeRow(row).children('.mpce-row-tools-wrapper');
            var nestingLevel = parent.MP.Utils.detectRowNestingLvl(row);

            for (var i = 0; i < missingSpansCount; i++) {
              var span = nestingLevel == 1 ? CE.DragDrop.myThis.spanHtml.clone() : CE.DragDrop.myThis.spanInnerHtml.clone();
              CE.DragDrop.myThis.makeEditable(span, null, true);
              $rowPanel.before(span);
            }

            CE.LayoutManager.rearrangeSpans(row, layout);
          }

          CE.DragDrop.myThis.resizer.updateHandle();
          CE.DragDrop.myThis.makeDroppable(); 

          CE.Resizer.myThis.updateSplittableOptions(row.find('.motopress-clmn'), null, null, 'init');
          CE.Resizer.myThis.updateAllHandles();
          parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntityResized.NAME, new CE.SceneEvents.EntityResized(CE.Grid.ENTITIES.ROW));
        },

        addColumnToRow: function addColumnToRow(row, clmn, position) {
          if (!clmn) {
            clmn = parent.MP.Utils.detectRowNestingLvl(row) == 1 ? CE.DragDrop.myThis.spanHtml.clone() : CE.DragDrop.myThis.spanInnerHtml.clone();
          }

          var rowEdge = parent.MP.Utils.getEdgeRow(row);

          if (typeof position == 'undefined') {
            position = rowEdge.children('.motopress-clmn').length + 1;
          }

          var layout = CE.LayoutManager.detectRowLayout(row);

          if (layout.length >= parent.CE.Iframe.myThis.gridObj.row.col) {
            return;
          }

          var newLayout = CE.DragDrop.myThis.getSpanSizeRules(layout.length + 1);

          if (position > 1) {
            rowEdge.children('.motopress-clmn').eq(position - 2).after(clmn);
          } else {
            rowEdge.children('.motopress-clmn').eq(1).before(clmn);
          }

          CE.DragDrop.myThis.makeEditable(clmn, null, true);
          CE.LayoutManager.rearrangeSpans(row, newLayout);
          CE.DragDrop.myThis.makeDroppable();
          CE.Resizer.myThis.updateSplittableOptions(clmn, null, row);
          CE.Resizer.myThis.updateAllHandles();
          $(window).trigger('resize');
        },

        insertRow: function insertRow(layout, $insertMarker, place, nestingLevel) {
          place = place || 'before';
          nestingLevel = nestingLevel || 1;
          var row = this.generateStructure(layout, nestingLevel);

          switch (place) {
            case 'in':
              $insertMarker.empty();
              $insertMarker.append(row);
              break;

            case 'before':
            default:
              $insertMarker.before(row);
              break;
          }

          var $parentClmn = row.closest('.motopress-clmn');

          if ($parentClmn.length) {
            var emptyClmnCtrl = $parentClmn.control(CE.Panels.EmptyColumnHelper);

            if (emptyClmnCtrl) {
              emptyClmnCtrl.destroy();
              $parentClmn.removeClass(CE.HelpersManager.myThis.emptyClmnClasses);
            }

            CE.HelpersManager.myThis.addHelpers($parentClmn);
          }

          CE.DragDrop.myThis.makeRowEditable(row, true);
          var t = setTimeout(function () {
            CE.DragDrop.myThis.addHandleMiddle(row);
            CE.DragDrop.myThis.resizer.updateHandle();
            CE.DragDrop.myThis.makeDroppable();
            CE.Resizer.myThis.updateSplittableOptions(row.find('.motopress-clmn'), null, null, 'init');
            CE.Resizer.myThis.updateAllHandles();
            parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntityCreated.NAME, new CE.SceneEvents.EntityCreated(CE.Grid.ENTITIES.ROW));
            clearTimeout(t);
          }, 0);
        },

        removeBlock: function removeBlock(block) {
          CE.Selectable.myThis.unselect();
          var blockSiblings;
          var entityType = parent.MP.Utils.getEntityTypeByElement(block);

          switch (entityType.name) {
            case CE.Grid.ENTITIES.ROW:
              blockSiblings = block.closest('.motopress-clmn').closest('.motopress-clmn').siblings('.motopress-clmn');

              this._removeRow(block);

              break;

            case CE.Grid.ENTITIES.COLUMN:
              blockSiblings = block.siblings('.motopress-clmn');

              this._removeColumn(block);

              break;

            case CE.Grid.ENTITIES.WIDGET:
              blockSiblings = block.closest('.motopress-clmn').siblings('.motopress-clmn');

              this._removeWidget(block);

              break;
          }

          CE.Resizer.myThis.updateAllHandles();
          $(window).trigger('resize'); 

          var shortcodeName;
          var apiParams = {
            involvedElements: {}
          };

          if (blockSiblings !== null && blockSiblings.length) {
            var shortcodes = blockSiblings.find('.motopress-block-content > [data-motopress-shortcode]');
            shortcodes.each(function () {
              if (!$(this).length) return;
              shortcodeName = $(this).attr('data-motopress-shortcode');

              if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) {
                apiParams.involvedElements[shortcodeName] = [];
              }

              apiParams.involvedElements[shortcodeName].push(this);
            });
          }

          $('body').trigger('MPCEObjectRemove', apiParams);
          parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntityDeleted.NAME, new CE.SceneEvents.EntityDeleted(entityType.name));
        },

        _removeRow: function _removeRow(row) {
          var parentRowFrom = row.parents('.motopress-row').first();

          if (!parentRowFrom.closest('.motopress-content-wrapper').length) {
            parentRowFrom = null;
          }

          row.find('[data-motopress-shortcode].ce_inline_editor').addBack().filter('.ce_inline_editor').each(function () {
            var ctrl = $(this).control(CE.InlineEditor);
            var id = ctrl.child.attr('id');

            if (typeof id !== 'undefined') {
              var editor = tinymce.get(id);

              if (typeof editor !== 'undefined') {
                editor.destroy();
              }
            }
          });
          row.children().remove();
          CE.DragDrop.myThis.clearIfEmpty(row, 'remove');

          if (CE.DragDrop.myThis.isEmptyScene()) {
            $('.motopress-handle-middle-in').remove();
            CE.Utils.addSceneState('empty-scene');
          }

          var rowHasSiblings = row.siblings('.motopress-row').length;

          if (parentRowFrom !== null && !rowHasSiblings) {
            CE.LayoutManager.rearrangeSpans(parentRowFrom);
          }
        },

        _removeColumn: function _removeColumn(clmn) {
          var rowFrom = clmn.closest('.motopress-row');
          clmn.find('[data-motopress-shortcode].ce_inline_editor').addBack().filter('.ce_inline_editor').each(function () {
            var ctrl = $(this).control(CE.InlineEditor);
            var id = ctrl.child.attr('id');

            if (typeof id !== 'undefined') {
              var editor = tinymce.get(id);

              if (typeof editor !== 'undefined') {
                editor.destroy();
              }
            }
          });
          clmn.remove();
          var resObj = CE.DragDrop.myThis.clearIfEmpty(rowFrom, 'remove');
          var isUnwrapped = resObj && resObj.hasOwnProperty('row');

          if (isUnwrapped) {
            rowFrom = resObj.row;
          } else {
            var newSizes = CE.DragDrop.myThis.recalcRowSizes(rowFrom);
            CE.LayoutManager.rearrangeSpans(rowFrom, newSizes);
          }

          if (CE.DragDrop.myThis.isEmptyScene()) {
            $('.motopress-handle-middle-in').remove();
            CE.Utils.addSceneState('empty-scene');
          }

          CE.Resizer.myThis.updateSplittableOptions(null, rowFrom, null);
        },

        _removeWidget: function _removeWidget(widget) {
          var spanRemovable = widget.closest('.motopress-clmn');
          var $entity = spanRemovable.find('.motopress-block-content > [data-motopress-shortcode]').first();
          var rowFrom = spanRemovable.closest('.motopress-row');
          $entity.find('[data-motopress-shortcode].ce_inline_editor').addBack().filter('.ce_inline_editor').each(function () {
            var ctrl = $(this).control(CE.InlineEditor);
            var id = ctrl.child.attr('id');

            if (typeof id !== 'undefined') {
              var editor = tinymce.get(id);

              if (typeof editor !== 'undefined') {
                editor.destroy();
              }
            }
          });

          if ($entity.attr('data-motopress-shortcode') === 'mp_space') {
            this._fixExSpacerSpan(spanRemovable);
          }

          var nestingLevel = parent.MP.Utils.detectSpanNestingLvl(spanRemovable);
          var $spanHtml = nestingLevel === 1 ? CE.DragDrop.myThis.spanHtml.clone() : CE.DragDrop.myThis.spanInnerHtml.clone();
          var $filterContent = $spanHtml.find('.motopress-filler-content');
          CE.HelpersManager.myThis.destroySpanHelper(spanRemovable);
          spanRemovable.html($filterContent);
          CE.HelpersManager.myThis.addHelpers(spanRemovable);
          CE.DragDrop.myThis.makeDroppable();
          CE.Resizer.myThis.updateSplittableOptions(null, rowFrom, null);
        },

        insertWidgetToClmn: function insertWidgetToClmn(widget, clmn, render, isNew) {
          isNew = typeof isNew !== 'undefined' ? isNew : false;
          render = typeof render !== 'undefined' ? render : false;
          clmn.children('.motopress-block-content').children('.motopress-filler-content, [data-motopress-shortcode]').replaceWith(widget);

          if (widget.attr('data-motopress-shortcode') === 'mp_space') {
            this._fixSpacerSpan(clmn);
          }

          CE.DragDrop.myThis.initShortcodeController(widget, isNew);
          var ctrl = widget.control(CE.Controls);

          if (render && ctrl) {
            ctrl.renderShortcode('created');
          }

          var emptyClmnCtrl = clmn.control(CE.Panels.EmptyColumnHelper);

          if (emptyClmnCtrl) {
            emptyClmnCtrl.destroy();
            clmn.removeClass(CE.HelpersManager.myThis.emptyClmnClasses);

            var paramStr = clmn.attr('data-motopress-parameters');

            if (paramStr) {
              var parameters = JSON.parse(paramStr);
              var classAttr = parent.CE.Iframe.myThis.gridObj.span.custom_class_attr;

              if (parameters.hasOwnProperty(classAttr) && parameters[classAttr].hasOwnProperty('value')) {
                var classesArr = $.trim(parameters[classAttr].value).split(' ');
                classesArr = $.grep(classesArr, function (cls) {
                  return $.inArray(cls, CE.HelpersManager.myThis.emptyClmnClasses.split(' ')) == -1;
                });
                parameters[classAttr].value = classesArr.join(' ');
                clmn.attr('data-motopress-parameters', JSON.stringify(parameters));
              }
            }
          }

          new CE.Panels.ColumnHelper(clmn);
          CE.DragDrop.myThis.makeDroppable();
          CE.Resizer.myThis.updateSplittableOptions(clmn);
          CE.Resizer.myThis.updateAllHandles();
          return widget;
        },

        generateStructure: function generateStructure(layout, nestingLevel) {
          var row = nestingLevel == 1 ? CE.DragDrop.myThis.rowHtml.clone() : CE.DragDrop.myThis.rowInnerHtml.clone();
          var spans = [];
          var protoSpan, spanAtts;

          if (nestingLevel == 1) {
            protoSpan = CE.DragDrop.myThis.spanHtml;
            spanAtts = parent.CE.WidgetsLibrary.myThis.getAttrs('mp_grid', parent.CE.WidgetsLibrary.myThis.getGrid().span.shortcode);
          } else {
            protoSpan = CE.DragDrop.myThis.spanInnerHtml;
            spanAtts = parent.CE.WidgetsLibrary.myThis.getAttrs('mp_grid', parent.CE.WidgetsLibrary.myThis.getGrid().span.inner);
          }

          $.each(layout, function (index, size) {
            var span = protoSpan.clone();
            CE.Resizer.myThis.setSpanSize(span, size);
            CE.DragDrop.myThis.makeEditable(span, null, true, spanAtts);
            spans.push(span);
          });
          CE.DragDrop.myThis.makeRowEditable(row);
          row.find('.motopress-filler-content').replaceWith(spans);
          return row;
        },

        duplicateBlock: function duplicateBlock(block) {
          CE.Selectable.myThis.unselect();
          var $entity;
          var entityLevel;
          var $prototype;
          var entityType = parent.MP.Utils.getEntityTypeByElement(block);
          var $entityHandle = null;

          if (entityType.is_row) {
            $entity = block;
            entityLevel = parent.MP.Utils.detectRowNestingLvl($entity);
            $prototype = $entity.clone();
          } else if (entityType.is_clmn) {
            $entity = block;
            entityLevel = parent.MP.Utils.detectSpanNestingLvl($entity);
            $prototype = $entity.clone();
          } else {
            var $widgetClmn = block.closest('.motopress-clmn');
            $entity = $widgetClmn.find('.motopress-block-content > [data-motopress-shortcode]').first();
            entityLevel = parent.MP.Utils.detectSpanNestingLvl($widgetClmn);
            $prototype = $entity.clone();
          } 


          if (entityType.is_widget) {
            $entityHandle = CE.Tools.myThis.getEntityHandle($entity, entityType);
            $entityHandle.addClass(CE.Shortcode.preloaderClass);
          } 


          $prototype.find('[data-motopress-group="mp_grid"], .motopress-block-content > [data-motopress-shortcode]').addBack().each(function () {
            var styles = $.parseJSON($(this).attr('data-motopress-styles'));

            if (styles.hasOwnProperty('mp_custom_style') && styles.mp_custom_style.hasOwnProperty('value')) {
              styles.mp_custom_style.value = CE.StyleEditor.myThis.changePrivateClassToDuplicated(styles.mp_custom_style.value);
              $(this).attr('data-motopress-styles', JSON.stringify(styles));
            }
          }); 

          var parser = new parent.CE.ContentParser($prototype, entityLevel);
          var src = parser.content;
          var insertPromise = this.insertShortcodeString(src, entityType, $entity);
          insertPromise.done(function ($newBlock) {
            var $body = $('body');
            $newBlock.find('.motopress-block-content > [data-motopress-shortcode]').each(function () {
              var $el = $(this);
              var $clmn = $el.closest('.motopress-clmn');
              var $shortcode, shortcodeName;
              var apiParams = {
                involvedElements: {},
                duplicatedElement: $el
              };
              $clmn.siblings('.motopress-clmn').each(function () {
                $shortcode = $(this).find('.motopress-block-content > [data-motopress-shortcode]').first();
                shortcodeName = $shortcode.attr('data-motopress-shortcode');

                if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) {
                  apiParams.involvedElements[shortcodeName] = [];
                }

                apiParams.involvedElements[shortcodeName].push($shortcode[0]);
              });
              $body.trigger('MPCEObjectDuplicated', apiParams);
            });
          }).always(function () {
            if ($entityHandle !== null) {
              $entityHandle.removeClass(CE.Shortcode.preloaderClass);
            }

            parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntityDuplicated.NAME, new CE.SceneEvents.EntityDuplicated(entityType.name));
          });
        },

        insertShortcodeString: function insertShortcodeString(src, entityType, $insertMarker, position) {
          position = position ? position : 'after';
          var nestingLevel = 1;

          if (entityType.is_clmn) {
            nestingLevel = parent.MP.Utils.detectSpanNestingLvl($insertMarker);
          } else if (entityType.is_widget) {
            nestingLevel = parent.MP.Utils.detectSpanNestingLvl($insertMarker.closest('.motopress-clmn'));
          }

          var $newBlock;
          var deferred = $.Deferred();
          this.renderShortcodeString(src, entityType, nestingLevel).done(this.proxy(function (data) {
            CE.ShortcodePrivateStyle.addRenderedStyleTags(data.privateStyleTags);

            switch (entityType.name) {
              case CE.Grid.ENTITIES.WIDGET:
                $newBlock = this._insertRenderedWidget(data, $insertMarker, position);
                break;

              case CE.Grid.ENTITIES.COLUMN:
                $newBlock = this._insertRenderedColumn(data, $insertMarker, position);
                break;

              case CE.Grid.ENTITIES.ROW:
              case CE.Grid.ENTITIES.PAGE:
                $newBlock = this._insertRenderedRows(data, $insertMarker, position);
                break;
            }

            $(window).trigger('resize');
            var $body = $('body');
            $newBlock.find('.motopress-block-content > [data-motopress-shortcode]').each(function () {
              var $el = $(this);
              $body.trigger('MPCEObjectCreated', [$el, $el.attr('data-motopress-shortcode')]);
            });
            deferred.resolve($newBlock);
          })).fail(function () {
            deferred.reject();
          });
          return deferred.promise();
        },

        _insertRenderedWidget: function _insertRenderedWidget(data, $insertMarker, position) {
          var $entityClmn = $insertMarker.closest('.motopress-clmn');
          var $rowTo = $entityClmn.closest('.motopress-row');
          var $rowToEdge = parent.MP.Utils.getEdgeRow($rowTo);
          var $newBlock;

          if (position == 'in') {
            this.insertWidgetToClmn(data.content, $insertMarker);
            $newBlock = $insertMarker;
          } else {
            var insertPos = $rowToEdge.children('.motopress-clmn').index($entityClmn);

            if (position == 'before') {
              insertPos--;
            }

            var newSizes = CE.DragDrop.myThis.recalcRowSizes($rowTo, {
              insertPosition: insertPos
            });

            if (newSizes.length) {
              $newBlock = this._insertNewBlock(data.wrappedContent.find('.motopress-clmn:first'), $entityClmn, position);
              CE.LayoutManager.rearrangeSpans($rowTo, newSizes);
            } else {
              $newBlock = this._insertNewBlock(data.wrappedContent, $rowTo, position);
              CE.LayoutManager.rearrangeSpans($newBlock);
            }
          } 


          CE.DragDrop.myThis.main($newBlock); 

          var $widget = $newBlock.find('.motopress-block-content > [data-motopress-shortcode]').first();

          if ($widget.attr('data-motopress-shortcode') === 'mp_space') {
            this._fixSpacerSpan($newBlock, $entityClmn);
          }

          return $newBlock;
        },

        _insertRenderedColumn: function _insertRenderedColumn(data, $insertMarker, position) {
          var $rowTo = $insertMarker.closest('.motopress-row');
          var $rowToEdge = parent.MP.Utils.getEdgeRow($rowTo);
          var insertPos = $rowToEdge.children('.motopress-clmn').index($insertMarker);

          if (position == 'before') {
            insertPos--;
          }

          var $newBlock;
          var newSizes = CE.DragDrop.myThis.recalcRowSizes($rowTo, {
            insertPosition: insertPos
          });

          if (newSizes.length) {
            $newBlock = this._insertNewBlock(data.content, $insertMarker, position);
            CE.LayoutManager.rearrangeSpans($rowTo, newSizes);
          } else {
            $newBlock = this._insertNewBlock(data.wrappedContent, $rowTo, position);
            CE.LayoutManager.rearrangeSpans($newBlock);
          } 


          CE.DragDrop.myThis.main($newBlock);
          return $newBlock;
        },

        _insertRenderedRows: function _insertRenderedRows(data, $insertMarker, position) {
          var $newBlock = this._insertNewBlock(data.content, $insertMarker, position); 


          CE.DragDrop.myThis.main($newBlock);
          return $newBlock;
        },

        _normalizeShortcodesStructure: function _normalizeShortcodesStructure(src, entityType, nestingLevel) {
          if (entityType.is_clmn) {
            var rowSlug = parent.CE.Iframe.myThis.getRowSlug(nestingLevel);
            src = '[' + rowSlug + ']' + src + '[/' + rowSlug + ']';
          } else if (entityType.is_widget) {
            var rowSlug = parent.CE.Iframe.myThis.getRowSlug(nestingLevel);
            var clmnData = parent.CE.Iframe.myThis.getClmnSlug(nestingLevel, 1);
            src = '[' + rowSlug + ']' + '[' + clmnData.slug + clmnData.attr + ']' + src + '[/' + clmnData.slug + ']' + '[/' + rowSlug + ']';
          }

          return src;
        },

        renderShortcodeString: function renderShortcodeString(src, entityType, nestingLevel) {
          src = this._normalizeShortcodesStructure(src, entityType, nestingLevel);
          var deferred = new $.Deferred();
          $.ajax({
            url: parent.motopress.ajaxUrl,
            type: 'POST',
            dataType: 'html',
            async: true,
            data: {
              action: 'motopress_ce_render_shortcodes_string',
              nonce: parent.motopressCE.nonces.motopress_ce_render_shortcodes_string,
              content: src,
              postID: parent.motopressCE.postID,
              type: entityType.name
            },
            success: function success(dirtyData) {
              var $dirtyData = $(dirtyData);
              parent.CE.Iframe.myThis.unwrapGrid($dirtyData);
              var wrappedContent = $dirtyData.filter('.motopress-ce-rendered-content-wrapper').children();
              var content = wrappedContent;

              if (entityType.is_clmn) {
                content = wrappedContent.find('.motopress-clmn:first');
              } else if (entityType.is_widget) {
                var clmn = wrappedContent.find('.motopress-clmn:first');
                content = clmn.find('[data-motopress-shortcode]:first');
              }

              var styles = $dirtyData.filter('.motopress-ce-private-styles-updates-wrapper');
              deferred.resolve({
                content: content,
                wrappedContent: wrappedContent,
                privateStyleTags: styles
              });
            },
            error: function error(jqXHR) {
              var error = $.parseJSON(jqXHR.responseText);

              if ($.isPlainObject(error) && error.hasOwnProperty('message')) {
                if (error.debug) {
                  console.log(error.message);
                } else {
                  parent.MP.Flash.setFlash(error.message, 'error');
                  parent.MP.Flash.showMessage();
                }
              }

              deferred.reject();
            }
          });
          return deferred.promise();
        },

        _insertNewBlock: function _insertNewBlock(html, $insertMarker, position) {
          html = $(html);
          position = position ? position : 'after';

          switch (position) {
            case 'before':
              $insertMarker.before(html);
              break;

            case 'in':
              var entityType = parent.MP.EntityType.initByEntity($insertMarker);

              if (entityType.is_clmn) {
                $insertMarker = parent.MP.Utils.getEdgeSpan($insertMarker);
              } else if (entityType.is_row) {
                $insertMarker = parent.MP.Utils.getEdgeRow($insertMarker);
              }

              $insertMarker.html(html);
              break;

            default:
            case 'after':
              $insertMarker.after(html);
              break;
          }

          CE.DragDrop.myThis.setEdges();
          return html;
        },

        insertWidgetToPosition: function insertWidgetToPosition(span, insertMarker, handleName, isNewBlock) {
          var rowTo = insertMarker.closest('.motopress-row');
          var newSpanAttrs = null;

          if (!CE.DragDrop.myThis.canBeInserted(insertMarker, handleName)) {
            return false;
          }

          if (!isNewBlock) {
            var $fillerContent = CE.DragDrop.myThis.spanHtml.clone().find('.motopress-filler-content'); 

            var newSpan = CE.DragDrop.myThis.spanHtml.clone();
            newSpan.find('.motopress-filler-content').replaceWith(span.find('.motopress-block-content').children()); 

            CE.DragDrop.myThis.resizer.setSpanSize(newSpan, parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(span.prop('class').split(' ')))); 
          } else {
            var newSpan = span;
          }

          switch (handleName) {
            case 'top-in':
              var draggableSpanInRow = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(newSpan).end();
              var wrapperSpan = CE.DragDrop.myThis.spanHtml.clone().addClass(parent.MP.Utils.getSpanClass(insertMarker.prop('class').split(' ')));
              insertMarker.before(wrapperSpan);
              var innerRow = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(insertMarker).end();
              wrapperSpan.find('.motopress-filler-content').replaceWith([draggableSpanInRow, innerRow]);
              CE.DragDrop.myThis.makeRowEditable(draggableSpanInRow.add(innerRow), isNewBlock);
              CE.DragDrop.myThis.makeEditable(wrapperSpan, parent.CE.ShortcodeAtts.getAttrsFromElement(newSpan), true);
              var t = setTimeout(function () {
                CE.DragDrop.myThis.addHandleMiddle(innerRow);
                CE.LayoutManager.rearrangeSpans(innerRow);
                CE.LayoutManager.rearrangeSpans(draggableSpanInRow);
                clearTimeout(t);
              }, 0);
              break;

            case 'bottom-in':
              var draggableSpanInRow = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(newSpan).end();
              var wrapperSpan = CE.DragDrop.myThis.spanHtml.clone().addClass(parent.MP.Utils.getSpanClass(insertMarker.prop('class').split(' ')));
              insertMarker.before(wrapperSpan);
              var innerRow = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(insertMarker).end();
              wrapperSpan.find('.motopress-filler-content').replaceWith([innerRow, draggableSpanInRow]);
              CE.DragDrop.myThis.makeRowEditable(draggableSpanInRow.add(innerRow), isNewBlock);
              CE.DragDrop.myThis.makeEditable(wrapperSpan, parent.CE.ShortcodeAtts.getAttrsFromElement(span), true);
              var t = setTimeout(function () {
                CE.DragDrop.myThis.addHandleMiddle(innerRow);
                CE.LayoutManager.rearrangeSpans(innerRow);
                CE.LayoutManager.rearrangeSpans(draggableSpanInRow);
                clearTimeout(t);
              }, 0);
              break;

            case 'left-out':
            case 'left':
            case 'intermediate':
              var rowToSpans = parent.MP.Utils.getEdgeRow(rowTo).children('.motopress-clmn');
              var toPos = handleName === 'left' ? 0 : rowToSpans.index(insertMarker);
              toPos = toPos < 0 ? 0 : toPos;
              var newSizes = CE.DragDrop.myThis.recalcRowSizes(rowTo, {
                insertPosition: toPos
              });

              if (newSizes.length) {
                if (insertMarker.parent().length) {
                  insertMarker.before(newSpan);
                }

                var t = setTimeout(function () {
                  CE.LayoutManager.rearrangeSpans(rowTo, newSizes);
                  clearTimeout(t);
                }, 0);
              }

              break;

            case 'right-out':
            case 'right':
              var rowToSpans = parent.MP.Utils.getEdgeRow(rowTo).children('.motopress-clmn');
              var toPos = handleName === 'right' ? rowToSpans.length : rowToSpans.index(insertMarker) + 1;
              var newSizes = CE.DragDrop.myThis.recalcRowSizes(rowTo, {
                insertPosition: toPos
              });

              if (newSizes.length) {
                if (insertMarker.parent().length) {
                  insertMarker.after(newSpan);
                }

                var t = setTimeout(function () {
                  CE.LayoutManager.rearrangeSpans(rowTo, newSizes);
                  clearTimeout(t);
                }, 0);
              }

              break;

            case 'left-in':
              var rowToSpans = parent.MP.Utils.getEdgeRow(rowTo).children('.motopress-clmn');
              var newSizes = CE.DragDrop.myThis.recalcRowSizes(rowTo, {
                wrapperPosition: rowToSpans.index(insertMarker)
              });

              if (newSizes.length) {
                var wrapperSpan = CE.DragDrop.myThis.spanHtml.clone().addClass(parent.MP.Utils.getSpanClass(insertMarker.prop('class').split(' ')));
                insertMarker.before(wrapperSpan);
                var innerRow = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(insertMarker).end();
                wrapperSpan.find('.motopress-filler-content').replaceWith(innerRow);
                insertMarker.before(newSpan);
                CE.DragDrop.myThis.makeRowEditable(innerRow, isNewBlock);
                CE.DragDrop.myThis.makeEditable(wrapperSpan, parent.CE.ShortcodeAtts.getAttrsFromElement(CE.DragDrop.myThis.draggingElement), true);
                var t = setTimeout(function () {
                  CE.DragDrop.myThis.addHandleMiddle(innerRow);
                  CE.LayoutManager.rearrangeSpans(rowTo, newSizes);
                  CE.LayoutManager.rearrangeSpans(innerRow);
                  clearTimeout(t);
                }, 0);
              }

              break;

            case 'right-in':
              var rowToSpans = parent.MP.Utils.getEdgeRow(rowTo).children('.motopress-clmn');
              var newSizes = CE.DragDrop.myThis.recalcRowSizes(rowTo, {
                wrapperPosition: rowToSpans.index(insertMarker)
              });

              if (newSizes.length) {
                var wrapperSpan = CE.DragDrop.myThis.spanHtml.clone().addClass(parent.MP.Utils.getSpanClass(insertMarker.prop('class').split(' ')));
                insertMarker.before(wrapperSpan);
                var innerRow = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(insertMarker).end();
                wrapperSpan.find('.motopress-filler-content').replaceWith(innerRow);
                insertMarker.after(newSpan);
                CE.DragDrop.myThis.makeRowEditable(innerRow, isNewBlock);
                CE.DragDrop.myThis.makeEditable(wrapperSpan, parent.CE.ShortcodeAtts.getAttrsFromElement(CE.DragDrop.myThis.draggingElement), true);
                var t = setTimeout(function () {
                  CE.DragDrop.myThis.addHandleMiddle(innerRow);
                  CE.LayoutManager.rearrangeSpans(rowTo, newSizes);
                  CE.LayoutManager.rearrangeSpans(innerRow);
                  clearTimeout(t);
                }, 0);
              }

              break;

            case 'middle-in':
              var minHeight = parseInt(insertMarker.css('min-height'));
              insertMarker.siblings('.motopress-handle-middle-in').add(this).each(function () {
                insertMarker.height(minHeight);
              });
              CE.DragDrop.myThis.resetLastHandleMiddleHeight();

              if (insertMarker.parent('.motopress-content-wrapper').length) {
                rowTo = CE.DragDrop.myThis.rowHtml.clone().find('.motopress-filler-content').replaceWith(newSpan).end();
              } else {
                rowTo = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(newSpan).end();
              }

              insertMarker.after(rowTo);
              CE.DragDrop.myThis.makeRowEditable(rowTo, isNewBlock);
              var t = setTimeout(function () {
                CE.LayoutManager.rearrangeSpans(rowTo);
                CE.DragDrop.myThis.addHandleMiddle(rowTo);
                clearTimeout(t);
              }, 0);
              break;

            case 'before':
              CE.DragDrop.myThis.resetLastHandleMiddleHeight();

              if (insertMarker.parent('.motopress-content-wrapper').length) {
                rowTo = CE.DragDrop.myThis.rowHtml.clone().find('.motopress-filler-content').replaceWith(newSpan).end();
              } else {
                rowTo = CE.DragDrop.myThis.rowInnerHtml.clone().find('.motopress-filler-content').replaceWith(newSpan).end();
              }

              insertMarker.before(rowTo);
              CE.DragDrop.myThis.makeRowEditable(rowTo, isNewBlock);
              var t = setTimeout(function () {
                CE.LayoutManager.rearrangeSpans(rowTo);
                CE.DragDrop.myThis.addHandleMiddle(rowTo);
                clearTimeout(t);
              }, 0);
              break;

            case 'insert':
              var dropSpanSize = parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(insertMarker.attr('class').split(' ')));
              newSpanAttrs = parent.CE.ShortcodeAtts.getAttrsFromElement(span); 

              if (insertMarker.parent().length) {
                insertMarker.replaceWith(newSpan);
              }

              CE.DragDrop.myThis.resizer.setSpanSize(newSpan, dropSpanSize);
              break;
          }

          if (newSpan.find('[data-motopress-shortcode]').attr('data-motopress-shortcode') === 'mp_space') {
            this._fixSpacerSpan(newSpan, span);
          } 


          CE.DragDrop.myThis.makeEditable(newSpan, null, true, newSpanAttrs);

          if (!isNewBlock) {
            CE.HelpersManager.myThis.destroySpanHelper(span);

            if (newSpan.find('[data-motopress-shortcode]').attr('data-motopress-shortcode') === 'mp_space') {
              this._fixExSpacerSpan(span);
            }

            span.html($fillerContent);
            CE.HelpersManager.myThis.addHelpers(span); 
          } 


          CE.DragDrop.myThis.$droppedSpan = newSpan;
          var droppableTimeout = setTimeout(this.proxy(function () {
            var rowFrom = span.closest('.motopress-row');
            CE.DragDrop.myThis.makeDroppable();
            CE.Resizer.myThis.updateSplittableOptions(newSpan, rowFrom, rowTo);
            CE.Resizer.myThis.updateAllHandles();
            $(window).trigger('resize');

            this._apiTriggerDrop(newSpan, rowFrom, rowTo);

            this._dispatchMovedEvent(newSpan);

            clearTimeout(droppableTimeout);
          }, 0));
          return true;
        },

        _fixSpacerSpan: function _fixSpacerSpan(span, sourceSpan) {
          span.addClass(CE.DragDrop.myThis.spaceClass); 

          if (sourceSpan && sourceSpan.attr('style') && sourceSpan.attr('style').match(/min-height:\s(\d+px)/)) {
            span.css('min-height', sourceSpan.attr('style').match(/min-height:\s(\d+px)/)[1]);
          }
        },

        _fixExSpacerSpan: function _fixExSpacerSpan(span) {
          span.removeClass(CE.DragDrop.myThis.spaceClass); 

          if (span.attr('style')) {
            span.attr('style', span.attr('style').replace(/min-height:\s(\d+px)[;]?/, ''));
          }
        },

        _apiTriggerDrop: function _apiTriggerDrop(block, rowFrom, rowTo) {
          var shortcodes,
              shortcodeName,
              apiParams = {};
          apiParams.involvedElements = {};
          apiParams.droppedElement = block;

          if (rowFrom && rowFrom.length) {
            shortcodes = rowFrom.find('.motopress-block-content > [data-motopress-shortcode]');

            if (shortcodes.length) {
              shortcodes.each(function () {
                shortcodeName = $(this).attr('data-motopress-shortcode');
                if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];

                if ($.inArray(this, apiParams.involvedElements[shortcodeName]) === -1) {
                  apiParams.involvedElements[shortcodeName].push(this);
                }
              });
            }
          }

          if (rowTo && rowTo.length) {
            shortcodes = rowTo.find('.motopress-block-content > [data-motopress-shortcode]');

            if (shortcodes.length) {
              shortcodes.each(function () {
                shortcodeName = $(this).attr('data-motopress-shortcode');
                if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];

                if ($.inArray(this, apiParams.involvedElements[shortcodeName]) === -1) {
                  apiParams.involvedElements[shortcodeName].push(this);
                }
              });
            }
          }

          CE.DragDrop.myThis.bodyEl.trigger('MPCEObjectDrop', apiParams);
        },

        _dispatchMovedEvent: function _dispatchMovedEvent($clmn) {
          var $shortcode = $clmn.find('.motopress-block-content > [data-motopress-shortcode]');
          var shortcodeControl = $shortcode.control(CE.Shortcode);
          var shortcodeLabel = shortcodeControl ? shortcodeControl.shortcodeLabel : null;
          parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntityMoved.NAME, new CE.SceneEvents.EntityMoved(shortcodeLabel));
        }
      }, {});
    })(jQuery);

    (function ($) {
      var FULL_WIDTH_OUTER_HELPER_WIDTH = 25;

      CE.Resizer = can.Construct(
      {
        myThis: null
      },
      {
        minWidth: 8,
        handle: null,
        emptySpan: null,
        gridColumnSizeClassesString: '',
        splitter: $('<div />', {
          'class': 'motopress-splitter'
        }),
        emptySpanNumber: 0,
        resizeWindowTimer: null,
        bodyEl: $('body'),
        setup: function setup() {
          this.setGridColumnSizeClassesString();
          this.setEmptySpan();
        },
        init: function init() {
          CE.Resizer.myThis = this;
          this.events();
        },
        _destroy: function _destroy() {
          $(window).off('resize.CE.Resizer');
        },
        clone: function clone($clonedScene) {
          var $this = this;
          var $splitters = $clonedScene.find('.motopress-splitter');
          $splitters.each(function () {
            var $newSplitter = $this.splitter.clone();
            var style = $(this).attr('style');
            $(this).replaceWith($newSplitter);
            $newSplitter.attr('style', style);
          });
        },
        restoreClone: function restoreClone($clonedScene) {
          var $this = this;
          var $splitters = $clonedScene.find('.motopress-splitter');
          $splitters.each(function () {
            $(this).removeData('uiDraggable');
            $this.makeSplittable($(this));
          });
          this.updateAllHandles();
        },
        events: function events() {
          $(window).on('resize.CE.Resizer', function (e) {
            parent.CE.Iframe.myThis.setSceneWidth();

            if (e.target === this) {
              CE.Resizer.myThis.proxy('updateHandle');
            }

            if (CE.Resizer.myThis.resizeWindowTimer) {
              clearTimeout(CE.Resizer.myThis.resizeWindowTimer);
            }

            CE.Resizer.myThis.resizeWindowTimer = setTimeout(function () {
              parent.MP.Editor.triggerIfr('Resize');
            }, 500);
          });
          parent.MP.Editor.onIfr('Resize', function (e) {
            CE.Resizer.myThis.updateAllHandles();
          });
          parent.CE.EventDispatcher.Dispatcher.addListener(CE.SceneEvents.InlineEditorOpened.NAME, this.hideSplitters);
          parent.CE.EventDispatcher.Dispatcher.addListener(CE.SceneEvents.InlineEditorClosed.NAME, this.showSplitters);
        },
        setGridColumnSizeClassesString: function setGridColumnSizeClassesString() {
          var gridColumnSizeClasses = [];

          for (var i = 1; i <= parent.CE.Iframe.myThis.gridObj.row.col; i++) {
            gridColumnSizeClasses.push(parent.CE.Iframe.myThis.gridObj.span["class"] + i);
          }

          this.gridColumnSizeClassesString = gridColumnSizeClasses.join(' ');
        },
        setEmptySpan: function setEmptySpan() {
          var emptySpan = $(parent.motopressCE.rendered_shortcodes.empty[parent.CE.Iframe.myThis.gridObj.span.shortcode]);
          CE.Scene.markEdgeSpan(emptySpan, true); 

          emptySpan.removeClass(this.gridColumnSizeClassesString); 

          var shortcodeName = parent.CE.Iframe.myThis.gridObj.span.shortcode;
          parent.CE.WidgetsLibrary.myThis.setAttrs(emptySpan, parent.CE.WidgetsLibrary.myThis.getGroup('mp_grid').id, parent.CE.WidgetsLibrary.myThis.getObject('mp_grid', shortcodeName));
          this.emptySpan = emptySpan;
        },

        getMinChildColumn: function getMinChildColumn(wrapper) {
          var minColNumber = CE.Grid.myThis.columnCount;
          wrapper.find('.motopress-clmn').each(function () {
            var colNumber = parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass($(this).prop('class').split(' ')));
            if (colNumber < minColNumber) minColNumber = colNumber;
          });
          return minColNumber;
        },

        isAllowedColSize: function isAllowedColSize(colNumber, wrapperWidth) {
          var colWidth = wrapperWidth / 100 * CE.Grid.myThis.colWidthByNumber[colNumber]; 

          return colWidth - CE.Grid.myThis.padding * 2 >= 0;
        },

        makeSplittable: function makeSplittable(obj) {
          if (!obj.length) return false;
          var $this = this;
          var splitter = obj.hasClass('motopress-splitter') ? obj : obj.find('.motopress-splitter');
          var oldUIPosLeft, removableBlock, triggerStop; 

          if (splitter.hasClass('ui-draggable')) {
            return;
          }

          splitter.draggable({
            axis: 'x',
            cursor: 'col-resize',
            grid: [1, 0],
            helper: 'clone',
            zIndex: 1,
            start: function start(e, ui) {
              CE.Utils.addSceneAction('split');
              ui.helper.hide();
              $this.hideSplitters($(this)); 

              CE.DragDrop.myThis.resetLastHandleMiddleHeight();
              oldUIPosLeft = null;
              triggerStop = false;
              CE.LeftBar.myThis.disable();
              $(this).addClass('motopress-splitter-hover');
              parent.CE.Panels.SettingsDialog.myThis.close();
              var row = ui.helper.closest('.motopress-row');
              var rowWidthPiece = parent.MP.Utils.getEdgeRow(row).width() / 100;
              var currentBlock = $(this).closest('.motopress-clmn');
              var nextBlock = currentBlock.prev('.motopress-clmn');
              var curBlockNumber = parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(currentBlock.prop('class').split(' '))); 

              var nextBlockNumber = parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(nextBlock.prop('class').split(' ')));
              var nextBlockLeft = nextBlock.offset().left;

              if (nextBlock.is('[data-motopress-wrapper-id]')) {
                var nextBlockMinChildCol = $this.getMinChildColumn(nextBlock);
              }

              if (currentBlock.is('[data-motopress-wrapper-id]')) {
                var curBlockMinChildCol = $this.getMinChildColumn(currentBlock);
              }

              var maxSpanNumber = curBlockNumber + nextBlockNumber - 1;
              var snapGird = [];

              for (var i = 1; i <= maxSpanNumber; i++) {
                snapGird[i] = rowWidthPiece * CE.Grid.myThis.colWidthByNumber[i];
              }

              var _snapGird = snapGird.slice();

              var curColWidth, factor, i, j;

              for (i = 1; i < nextBlockNumber; i++) {
                if (snapGird[i] - CE.Grid.myThis.padding * 2 < 0) {
                  snapGird[i] = (nextBlockLeft + snapGird[i]) * -1;
                } else {
                  factor = 1;
                  curColWidth = Math.abs(snapGird[i]);

                  if (snapGird[i] > 0 && nextBlock.is('[data-motopress-wrapper-id]')) {
                    if (!$this.isAllowedColSize(nextBlockMinChildCol, curColWidth)) factor = -1;
                  }

                  snapGird[i] = (nextBlockLeft + snapGird[i]) * factor;
                }
              }

              j = nextBlockNumber - 1;

              for (i = curBlockNumber; i >= 1; i--) {
                j++;

                if (_snapGird[i] - CE.Grid.myThis.padding * 2 < 0) {
                  snapGird[j] = (nextBlockLeft + snapGird[j]) * -1;
                } else {
                  factor = 1;
                  curColWidth = _snapGird[i];

                  if (_snapGird[i] > 0 && currentBlock.is('[data-motopress-wrapper-id]')) {
                    if (!$this.isAllowedColSize(curBlockMinChildCol, curColWidth)) factor = -1;
                  }

                  snapGird[j] = (nextBlockLeft + snapGird[j]) * factor;
                }
              }

              $(this).data('mp-snap-grid', snapGird);
              $this.currentI = nextBlockNumber;
              $this.curSplitterArea = ''; 

              var shortcodes,
                  shortcodeName,
                  apiParams = {};
              apiParams.involvedElements = {};

              if (currentBlock.length) {
                shortcodes = currentBlock.find('.motopress-block-content > [data-motopress-shortcode]');

                if (shortcodes.length) {
                  shortcodes.each(function () {
                    shortcodeName = $(this).attr('data-motopress-shortcode');
                    if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];
                    apiParams.involvedElements[shortcodeName].push(this);
                  });
                }
              }

              if (nextBlock.length) {
                shortcodes = nextBlock.find('.motopress-block-content > [data-motopress-shortcode]');

                if (shortcodes.length) {
                  shortcodes.each(function () {
                    shortcodeName = $(this).attr('data-motopress-shortcode');
                    if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];
                    apiParams.involvedElements[shortcodeName].push(this);
                  });
                }
              }

              $('body').trigger('MPCEObjectSplitStart', apiParams);
            },
            stop: function stop(e, ui) {
              CE.Utils.removeSceneAction('split');
              CE.LeftBar.myThis.enable();
              $this.showSplitters();
              $(this).removeClass('motopress-splitter-hover');
              var currentBlock = $(this).closest('.motopress-clmn');
              var nextBlock = currentBlock.prev('.motopress-clmn');
              CE.Resizer.myThis.updateSplittableOptions(currentBlock, null, null, 'split');
              var handlesUpdating = CE.Resizer.myThis.updateAllHandles();
              if (triggerStop && removableBlock.length) removableBlock.remove(); 

              handlesUpdating.done(function () {
                parent.CE.EventDispatcher.Dispatcher.dispatch(CE.SceneEvents.EntityResized.NAME, new CE.SceneEvents.EntityResized(CE.Grid.ENTITIES.COLUMN)); 

                var shortcodes,
                    shortcodeName,
                    apiParams = {};
                apiParams.involvedElements = {};

                if (currentBlock.length) {
                  shortcodes = currentBlock.find('.motopress-block-content > [data-motopress-shortcode]');

                  if (shortcodes.length) {
                    shortcodes.each(function () {
                      shortcodeName = $(this).attr('data-motopress-shortcode');
                      if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];
                      apiParams.involvedElements[shortcodeName].push(this);
                    });
                  }
                }

                if (nextBlock.length) {
                  shortcodes = nextBlock.find('.motopress-block-content > [data-motopress-shortcode]');

                  if (shortcodes.length) {
                    shortcodes.each(function () {
                      shortcodeName = $(this).attr('data-motopress-shortcode');
                      if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];
                      apiParams.involvedElements[shortcodeName].push(this);
                    });
                  }
                }

                $('body').trigger('MPCEObjectSplitStop', apiParams);
              });
            },
            drag: function drag(e, ui) {
              var resizeDone = false;
              var curUIPosLeft = ui.offset.left - CE.Grid.myThis.padding / 2;
              if (oldUIPosLeft === null) oldUIPosLeft = curUIPosLeft;
              if (curUIPosLeft === oldUIPosLeft) return;
              var currentBlock = $(this).closest('.motopress-clmn');
              var nextBlock = null;
              var parentRowWidth = currentBlock.closest('.motopress-row-edge').width();
              var half1, half2;
              var snapGird = $(this).data('mp-snap-grid');
              var maxCols = snapGird.length - 1;
              var barOffset = CE.LeftBar.myThis.getSpace();

              if (maxCols > 1) {
                half1 = Math.abs(snapGird[2]) - Math.abs(snapGird[1]);
                half2 = Math.abs(snapGird[maxCols]) - Math.abs(snapGird[maxCols - 1]);
              } else {
                half1 = half2 = parentRowWidth / 100 * CE.Grid.myThis.colWidthByNumber[1];
              }

              if (half1 > CE.Grid.myThis.padding) half1 -= CE.Grid.myThis.padding;
              if (half1 > CE.Grid.myThis.padding) half1 -= CE.Grid.myThis.padding;
              if (half2 > CE.Grid.myThis.padding) half2 -= CE.Grid.myThis.padding;
              var cond1 = e.pageX <= barOffset + 2 || curUIPosLeft <= Math.abs(snapGird[1]) - half1;
              var cond2 = e.pageX >= $(document).width() - 2 || curUIPosLeft >= Math.abs(snapGird[maxCols]) + half2 * 1.5;

              if (cond1 || cond2) {
                if (cond1 && $this.curSplitterArea === 'left') return;
                if (cond2 && $this.curSplitterArea === 'right') return;
                nextBlock = currentBlock.prev('.motopress-clmn');
                var curBlockOldSpan = parent.MP.Utils.getSpanClass(currentBlock.prop('class').split(' '));
                var nextBlockOldSpan = parent.MP.Utils.getSpanClass(nextBlock.prop('class').split(' '));
                var curBlockOldNumber = parent.MP.Utils.getSpanNumber(curBlockOldSpan);
                var nextBlockOldNumber = parent.MP.Utils.getSpanNumber(nextBlockOldSpan);

                if (cond1) {
                  $this.curSplitterArea = 'left';
                  var minCol = 0;

                  for (var i = 1; i <= maxCols; i++) {
                    if (snapGird[i] > 0) {
                      minCol = i;
                      break;
                    }
                  }

                  if (minCol) {
                    CE.Resizer.myThis.setSpanSize(currentBlock, maxCols + 1 - minCol);
                    CE.Resizer.myThis.setSpanSize(nextBlock, minCol);
                    $this.currentI = minCol;
                    $(window).trigger('resize');
                    resizeDone = true;
                  }
                } else {
                  $this.curSplitterArea = 'right';
                  var maxCol = 0;

                  for (var i = maxCols; i >= 1; i--) {
                    if (snapGird[i] > 0) {
                      maxCol = i;
                      break;
                    }
                  }

                  if (maxCol) {
                    CE.Resizer.myThis.setSpanSize(currentBlock, maxCols + 1 - maxCol);
                    CE.Resizer.myThis.setSpanSize(nextBlock, maxCol);
                    $this.currentI = maxCol;
                    $(window).trigger('resize');
                    resizeDone = true;
                  }
                }
              } else {
                $this.curSplitterArea = 'center';
                var direction1 = curUIPosLeft < oldUIPosLeft ? -1 : 1;
                var half = false;

                for (var i = 1; i <= maxCols; i++) {
                  if (snapGird[i] <= 0) continue;
                  half = false;

                  if (maxCols > 1) {
                    if (i === 1) {
                      half = Math.abs(snapGird[i + 1]) - Math.abs(snapGird[i]);
                    } else if (i == maxCols) {
                      half = Math.abs(snapGird[i]) - Math.abs(snapGird[i - 1]);
                    } else {
                      if (direction1 < 0) {
                        half = Math.abs(snapGird[i]) - Math.abs(snapGird[i - 1]);
                      } else {
                        half = Math.abs(snapGird[i + 1]) - Math.abs(snapGird[i]);
                      }
                    }
                  } else {
                    half = parentRowWidth / 100 * CE.Grid.myThis.colWidthByNumber[1];
                  }

                  if (!half) continue;
                  half = Math.abs(half) / 2;

                  if (curUIPosLeft >= snapGird[i] - half / 2 && curUIPosLeft <= snapGird[i] + half) {
                    var diff = i - $this.currentI;
                    triggerStop = false;
                    if (diff === 0) continue;
                    var empty = null,
                        direction2;
                    currentBlock = $(this).closest('.motopress-clmn');
                    nextBlock = currentBlock.prev('.motopress-clmn');
                    var curBlockOldSpan = parent.MP.Utils.getSpanClass(currentBlock.prop('class').split(' '));
                    var nextBlockOldSpan = parent.MP.Utils.getSpanClass(nextBlock.prop('class').split(' '));
                    var nextBlockOldSpan = parent.MP.Utils.getSpanClass(nextBlock.prop('class').split(' '));
                    var curBlockOldNumber = parent.MP.Utils.getSpanNumber(curBlockOldSpan);
                    var nextBlockOldNumber = parent.MP.Utils.getSpanNumber(nextBlockOldSpan);
                    var curBlockNewNumber = curBlockOldNumber - diff;
                    var nextBlockNewNumber = nextBlockOldNumber + diff;
                    CE.Resizer.myThis.setSpanSize(currentBlock, curBlockNewNumber);
                    CE.Resizer.myThis.setSpanSize(nextBlock, nextBlockNewNumber);
                    $this.currentI = i;
                    $(window).trigger('resize');
                    resizeDone = true;
                    break;
                  }
                }
              }

              if (currentBlock && currentBlock.length) {
                var top = parseInt(currentBlock.css('margin-top')) + parseInt(currentBlock.css('border-top-width'));
                var rowHeight = $(this).closest('.motopress-row').height();
                $(this).css({
                  top: -top,
                  height: rowHeight
                });
                if (triggerStop) e.preventDefault();
              } 


              if (resizeDone) {
                CE.Resizer.myThis.updateSplitterHeight();
                var shortcodes,
                    shortcodeName,
                    apiParams = {};
                apiParams.involvedElements = {};

                if (currentBlock && currentBlock.length) {
                  shortcodes = currentBlock.find('.motopress-block-content > [data-motopress-shortcode]');

                  if (shortcodes.length) {
                    shortcodes.each(function () {
                      shortcodeName = $(this).attr('data-motopress-shortcode');
                      if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];
                      apiParams.involvedElements[shortcodeName].push(this);
                    });
                  }
                }

                if (nextBlock && nextBlock.length) {
                  shortcodes = nextBlock.find('.motopress-block-content > [data-motopress-shortcode]');

                  if (shortcodes.length) {
                    shortcodes.each(function () {
                      shortcodeName = $(this).attr('data-motopress-shortcode');
                      if (!apiParams.involvedElements.hasOwnProperty(shortcodeName)) apiParams.involvedElements[shortcodeName] = [];
                      apiParams.involvedElements[shortcodeName].push(this);
                    });
                  }
                }

                $('body').trigger('MPCEObjectSplit', apiParams);
              }
            }
          });
        },

        calcSplitterOptions: function calcSplitterOptions(obj, type) {
          var elements = null;
          var rowWidth = null;
          var splitter = null;

          if (type === 'column') {
            rowWidth = obj.parent('.motopress-row-edge').width();
            elements = obj;
          } else if (type === 'row') {
            var rowEdge = parent.MP.Utils.getEdgeRow(obj);
            rowWidth = rowEdge.width();
            elements = rowEdge.children('.motopress-clmn');
          } else {
            return false;
          }

          elements.each(function () {
            splitter = parent.MP.Utils.getEdgeSpan($(this)).children('.motopress-splitter');
            CE.Resizer.myThis.makeSplittable(splitter);
          });

          if (!CE.Grid.myThis.padding) {
            var spanMargin = rowWidth / 100 * CE.Grid.myThis.columnMarginPiece;
            var splitterWidth = 10;
            var splitterMargin = -(splitterWidth + spanMargin) / 2;
            elements.each(function () {
              splitter = parent.MP.Utils.getEdgeSpan($(this)).children('.motopress-splitter');
              splitter.css('margin-left', splitterMargin - parseInt($(this).css('border-left-width')));
            });
          }
        },

        updateSplittableOptions: function updateSplittableOptions(block, rowFrom, rowTo, action) {
          if (typeof action === 'undefined') action = 'default';

          if (action === 'init' || action === 'split') {
            var rows = null;

            if (action === 'init') {
              rows = parent.MP.Utils.findRows();
            } else if (action === 'split') {
              var necessaryRow = block.parents('.motopress-row, .motopress-content-wrapper').eq(-2);

              if (typeof necessaryRow !== 'undefined') {
                rows = parent.MP.Utils.findRows(necessaryRow).add(necessaryRow);
              }
            }

            if (rows && typeof rows !== 'undefined') {
              $.each(rows, function () {
                CE.Resizer.myThis.calcSplitterOptions($(this), 'row');
              });
            }
          } else {
            if (block) this.calcSplitterOptions(block, 'column');

            if (rowFrom) {
              $.each(parent.MP.Utils.findRows(rowFrom).add(rowFrom), function () {
                CE.Resizer.myThis.calcSplitterOptions($(this), 'row');
              });
            }

            if (rowTo) {
              $.each(parent.MP.Utils.findRows(rowTo).add(rowTo), function () {
                CE.Resizer.myThis.calcSplitterOptions($(this), 'row');
              });
            }
          }
        },

        getSpanIndentForRow: function getSpanIndentForRow(row) {
          var rowEdge = parent.MP.Utils.getEdgeRow(row);
          var spanIndent;

          if (CE.Grid.myThis.padding) {
            spanIndent = CE.Grid.myThis.padding * 2;
          } else {
            spanIndent = rowEdge.width() / 100 * CE.Grid.myThis.columnMarginPiece;
          }

          return spanIndent;
        },

        updateSplitterHeight: function updateSplitterHeight() {
          var def = can.Deferred();
          var t = setTimeout(this.proxy(function () {
            $.each(parent.MP.Utils.findRows(), this.proxy(function (index, row) {
              var row = $(row);
              var rowEdge = parent.MP.Utils.getEdgeRow(row);
              var spanIndent = this.getSpanIndentForRow(row);
              var rowEdgeHeight = rowEdge.outerHeight();
              rowEdge.children('.motopress-clmn').each(function () {
                var span = $(this);
                var spanEdge = parent.MP.Utils.getEdgeSpan(span);
                var handleIntermediateLeft = CE.Grid.myThis.padding && span.is('[data-motopress-wrapper-id]') ? spanIndent / 2 : spanIndent;
                var spanEdgeTopGap = spanEdge.offset().top - rowEdge.offset().top;
                var top = spanEdgeTopGap + parseFloat(spanEdge.css('border-top-width'));
                span.find('.motopress-splitter').css({
                  height: rowEdgeHeight,
                  top: -top
                });
                span.find('.motopress-handle-intermediate').css({
                  width: spanIndent,
                  height: rowEdgeHeight,
                  top: -top,
                  left: -handleIntermediateLeft - parseInt(span.css('border-left-width'))
                });
              });
            }));
            def.resolve();
            clearTimeout(t);
          }), 50);
          return def;
        },
        hideSplitters: function hideSplitters(exclude) {
          exclude = typeof exclude === 'undefined' ? exclude = false : exclude;
          var splitters = $('.motopress-content-wrapper .motopress-clmn .motopress-splitter');
          if (!exclude) splitters.addClass('motopress-hide');else splitters.not(exclude).addClass('motopress-hide');
        },
        showSplitters: function showSplitters() {
          $('.motopress-content-wrapper .motopress-clmn .motopress-splitter').removeClass('motopress-hide');
        },

        calculateRowGap: function calculateRowGap(row, side) {
          var gap = 0;
          var rowEdge = parent.MP.Utils.getEdgeRow(row);

          if (!row.is(rowEdge)) {
            switch (side) {
              case 'top':
                gap += rowEdge.offset().top - row.offset().top;
                break;

              case 'bottom':
                var rowBottom = row.offset().top + row.outerHeight();
                var rowEdgeBottom = rowEdge.offset().top + rowEdge.outerHeight();
                gap += rowBottom - rowEdgeBottom;
                break;

              case 'left':
                gap += rowEdge.offset().left - row.offset().left;
                break;

              case 'right':
                var rowRight = row.offset().left + row.outerWidth();
                var rowEdgeRight = rowEdge.offset().left + rowEdge.outerWidth();
                gap += rowRight - rowEdgeRight;
                break;
            }
          }

          gap += parseFloat(rowEdge.css('padding-' + side));
          gap += parseFloat(rowEdge.css('border-' + side + '-width'));
          return gap;
        },

        updateHandle: function updateHandle() {
          var def = can.Deferred();
          var t = setTimeout(function () {
            if (!CE.DragDrop.myThis.isEmptyScene()) {
              var rowFirst = $('.motopress-content-wrapper > .motopress-row:first');
              var rowFirstOffset = rowFirst.offset();
              var rowFirstMarginLeft = parseFloat(rowFirst.css('margin-left'));
              var rowLast = $('.motopress-content-wrapper > .motopress-row:last');
              var leftBarWidth = CE.LeftBar.myThis.getSpace();
              var handleOffset = rowFirstOffset.left - leftBarWidth; 

              $('.motopress-content-wrapper > .motopress-row').each(function () {
                var row = $(this);
                var rowHeight = row.outerHeight();
                parent.MP.Utils.getEdgeRow(row).children('.motopress-clmn:first, .motopress-clmn:last').each(function () {
                  var span = $(this);
                  var spanEdge = parent.MP.Utils.getEdgeSpan(span);
                  var spanEdgeTopGap = spanEdge.offset().top - row.offset().top;
                  var top = spanEdgeTopGap + parseFloat(spanEdge.css('border-top-width'));
                  span.children('.motopress-wrapper-helper, .motopress-overlay').each(function () {
                    var isWrapper = $(this).hasClass('motopress-wrapper-helper');
                    $(this).find('.motopress-handle-left, .motopress-handle-right').each(function () {
                      var side = $(this).hasClass('motopress-handle-left') ? 'left' : 'right';
                      var spanEdgeBorder = parseFloat(spanEdge.css('border-' + side + '-width'));

                      if (side === 'left') {
                        var spanEdgeOffset = spanEdge.offset().left - leftBarWidth;
                        ;
                      } else {
                        var spanEdgeOffset = $(window).width() - spanEdge.offset().left - spanEdge.outerWidth();
                      }

                      var width = spanEdgeOffset + spanEdgeBorder + CE.Grid.myThis.padding; 

                      if (Math.floor(width) <= 0) {
                        width = FULL_WIDTH_OUTER_HELPER_WIDTH; 

                        spanEdgeOffset = 0;
                      }

                      var properties = {
                        top: -top,
                        width: width,
                        height: rowHeight
                      };
                      var sidePosition = isWrapper ? spanEdgeOffset + spanEdgeBorder : spanEdgeOffset + spanEdgeBorder + CE.Grid.myThis.padding;
                      properties[side] = -sidePosition;
                      $(this).css(properties);
                    });
                  });
                });
              }); 

              var container = $('html'),
                  doc = $(document);
              var handleMiddleWidth = doc.width() - leftBarWidth;
              var handleMiddleFirst = $('.motopress-content-wrapper > .motopress-handle-middle-in:first');
              var handleMiddleLast = $('.motopress-content-wrapper > .motopress-handle-middle-in:last');
              var handleMiddlePrevLast = handleMiddleLast.prevAll('.motopress-handle-middle-in:first');

              if (handleMiddlePrevLast[0] !== handleMiddleFirst[0]) {
                handleMiddlePrevLast.css({
                  width: '',
                  left: '',
                  height: '',
                  'margin-top': ''
                });
              }

              var htmlHeight = doc.height();
              var containerTop = parseInt(container.css('top'));
              var handleMiddleLastHeight = htmlHeight - handleMiddleLast.offset().top;

              if (htmlHeight < containerTop + doc.outerHeight(true)) {
                handleMiddleLastHeight += containerTop;
              }

              var rowLastMarginBottom = parseInt(rowLast.css('margin-bottom'));

              if (Math.abs(parseInt(handleMiddleLast.css('margin-top'))) !== rowLastMarginBottom) {
                handleMiddleLastHeight += rowLastMarginBottom;
              }

              var rowFirstMarginTop = parseInt(rowFirst.css('margin-top'));
              handleMiddleFirst.css({
                width: handleMiddleWidth,
                height: rowFirstOffset.top,
                top: rowFirstMarginTop - rowFirstOffset.top,
                left: -handleOffset + rowFirstMarginLeft,
                'margin-top': ''
              });
              handleMiddleLast.css({
                width: handleMiddleWidth,
                left: -handleOffset + rowFirstMarginLeft,
                height: handleMiddleLastHeight,
                'margin-top': -rowLastMarginBottom
              });
            } 


            def.resolve();
            clearTimeout(t);
          }, 50);
          return def;
        },

        updateBottomInHandleMiddle: function updateBottomInHandleMiddle() {
          var def = can.Deferred(); 

          var t = setTimeout(function () {
            if (!CE.DragDrop.myThis.isEmptyScene()) {
              $('.motopress-content-wrapper > .motopress-row').each(function () {
                CE.Resizer.myThis.setHandleHeight($(this));
              });
            } 


            def.resolve();
            clearTimeout(t);
          }, 50);
          return def;
        },

        setHandleHeight: function setHandleHeight(row) {
          var minHeight = parseInt(row.find('.motopress-handle-middle-in:last').css('min-height'));
          row.find('.motopress-handle-middle-in').each(function () {
            $(this).height(minHeight);
          });
          var rowEdge = parent.MP.Utils.getEdgeRow(row);
          rowEdge.children('.motopress-clmn').each(function () {
            var span = $(this);
            var spanEdge = parent.MP.Utils.getEdgeSpan(span);
            var spanEdgeWidth = spanEdge.width();
            var childRow = spanEdge.children('.motopress-row');
            var bottom = row.is(row.parent().children('.motopress-row:last')) ? 0 : 5;
            var top = row.is(row.parent().children('.motopress-row:first')) ? 0 : 5;
            var spanEdgeBottom = spanEdge.offset().top + spanEdge.outerHeight();
            var rowBottom = row.offset().top + row.outerHeight(); 

            if (childRow.length) {
              var handleMiddleLast = spanEdge.children('.motopress-handle-middle-in:last');
              var bottomGapBetweenSpanRow = rowBottom - spanEdgeBottom;
              var rowPrev = handleMiddleLast.prev('.motopress-row');
              var rowPrevBottom = rowPrev.offset().top + rowPrev.outerHeight();
              var handleMiddleLastMinHeight = parseFloat(handleMiddleLast.css('min-height'));
              handleMiddleLast.css({
                'bottom': -bottomGapBetweenSpanRow + handleMiddleLastMinHeight,
                'height': rowBottom - rowPrevBottom,
                'width': spanEdgeWidth
              }); 

              var handleMiddleFirst = spanEdge.children('.motopress-handle-middle-in:first');
              var spanPaddingTop = parseFloat(spanEdge.css('padding-top'));
              var spanBorderTop = parseFloat(spanEdge.css('border-top'));
              var topGapBetweenSpanRow = spanEdge.offset().top - row.offset().top;
              var handleMiddleFirstMinHeight = parseFloat(handleMiddleFirst.css('min-height'));
              handleMiddleFirst.css({
                'top': -topGapBetweenSpanRow - spanBorderTop,
                'height': topGapBetweenSpanRow + spanBorderTop + spanPaddingTop + handleMiddleFirstMinHeight,
                'width': spanEdgeWidth
              });
              childRow.each(function () {
                CE.Resizer.myThis.setHandleHeight($(this));
              });
            } else {
              var bottomIn = span.find('.motopress-handle-bottom-in');
              var bottomInMinHeight = parseFloat(bottomIn.css('min-height'));
              var spanEdgeBottom = spanEdge.offset().top + spanEdge.outerHeight();
              var rowBottom = row.offset().top + row.outerHeight();
              var gapBetweenSpanRowBottom = spanEdgeBottom - rowBottom;
              bottomIn.css({
                bottom: bottom + gapBetweenSpanRowBottom,
                height: bottomInMinHeight - gapBetweenSpanRowBottom + parseFloat(spanEdge.css('border-bottom-width'))
              });
              var gapBetweenSpanRowTop = spanEdge.offset().top - row.offset().top;
              var topIn = span.find('.motopress-handle-top-in');
              var topMinHeight = parseInt(topIn.css('min-height'));
              topIn.css({
                top: top - gapBetweenSpanRowTop,
                height: topMinHeight + gapBetweenSpanRowTop + parseFloat(spanEdge.css('border-top-width'))
              });
            } 

          }); 
        },

        updateHandleMiddle: function updateHandleMiddle() {
          var def = can.Deferred();
          var t = setTimeout(function () {
            var elements = $('.motopress-content-wrapper > .motopress-handle-middle-in:not(":first, :last"), ' + '.motopress-content-wrapper .motopress-row-edge > .motopress-clmn .motopress-handle-middle-in:not(":first, :last")');
            elements.each(function () {
              var handleMiddle = $(this);
              var prevRow = handleMiddle.prev('.mpce-wp-more-tag').length ? handleMiddle.prev('.mpce-wp-more-tag').prev('.motopress-row') : handleMiddle.prev('.motopress-row');
              var nextRow = handleMiddle.next('.motopress-row');
              var prevRowMarginBottom = prevRow.length ? parseInt(prevRow.css('margin-bottom')) : 0;
              var nextRowMarginTop = nextRow.length ? parseInt(nextRow.css('margin-top')) : 0;
              var marginTop = -prevRowMarginBottom - 5;
              var height = Math.max(prevRowMarginBottom, nextRowMarginTop) + 10;
              var parameters = {};
              parameters['width'] = handleMiddle.parent('.motopress-clmn-edge, .motopress-content-wrapper').width();

              if (height > 0) {
                parameters['margin-top'] = marginTop;
                parameters['height'] = height;
              } else {
                parameters['margin-top'] = '';
                parameters['height'] = '';
              }

              parameters['bottom'] = ''; 

              handleMiddle.css(parameters);
            });
            $('.motopress-content-wrapper > .motopress-handle-middle-in:first, .motopress-content-wrapper > .motopress-handle-middle-in:last').css({
              'margin-left': '',
              'margin-right': ''
            }); 

            def.resolve();
            clearTimeout(t);
          }, 70);
          return def;
        },

        changeSpanClass: function changeSpanClass(obj, newClass) {
          if (parent.CE.Iframe.myThis.gridObj.span.type && parent.CE.Iframe.myThis.gridObj.span.type === 'multiple') {
            var shortcode = obj.attr('data-motopress-shortcode');

            if (typeof shortcode !== 'undefined') {
              var spanNumber = parent.MP.Utils.getSpanNumber(newClass);
              obj.attr('data-motopress-shortcode', parent.CE.Iframe.myThis.gridObj.span.shortcode[spanNumber - 1]);
            }
          }

          if (newClass === parent.CE.Iframe.myThis.gridObj.span.minclass) {
            newClass += ' motopress-clmn-min';
          }

          obj.removeClass(this.gridColumnSizeClassesString + ' motopress-clmn-min').addClass(newClass);
        },

        setSpanSize: function setSpanSize(span, size) {
          var newClass = size ? parent.CE.Iframe.myThis.gridObj.span["class"] + size : '';
          this.changeSpanClass(span, newClass);
        },
        updateAllHandles: function updateAllHandles() {
          return can.when(CE.Resizer.myThis.updateBottomInHandleMiddle(), CE.Resizer.myThis.updateSplitterHeight(), CE.Resizer.myThis.updateHandle(), CE.Resizer.myThis.updateHandleMiddle());
        }
      });
    })(jQuery);

    (function ($) {
      can.Construct.extend('CE.HelpersManager', {
        myThis: null
      }, {
        emptyClmnClasses: 'motopress-empty mp-hidden-phone',
        init: function init() {
          CE.HelpersManager.myThis = this;
        },

        addHelpers: function addHelpers(spans) {
          var $this = this;
          spans.each(function () {
            var span = $(this);
            var spanEdge = parent.MP.Utils.getEdgeSpan(span);

            if (spanEdge.children('.motopress-filler-content').length) {
              span.addClass($this.emptyClmnClasses);
            }

            $this.wrapIntoBlockContent(span);

            if ($this.hasHelpers(span)) {
              return true;
            }

            if (parent.MP.Utils.isSpanWrapper(span)) {
              span.attr('data-motopress-wrapper-id', CE.Panels.WrapperColumnHelper.getNextWrapperId());
              CE.DragDrop.myThis.setCalcWrapperWidth(spanEdge.children('.motopress-handle-middle-in'));
              new CE.Panels.WrapperColumnHelper(span);
            } else if (span.hasClass('motopress-empty')) {
              new CE.Panels.EmptyColumnHelper(span);
            } else {
              new CE.Panels.ColumnHelper(span);
            }
          });
        },
        destroySpanHelper: function destroySpanHelper($spans) {
          $spans.each(function () {
            var $span = $(this);
            var ctrl = $span.control(CE.Panels.BaseColumnHelper);

            if (ctrl) {
              ctrl.destroy();
            }
          });
        },

        addHelpersToRow: function addHelpersToRow(row) {
          new CE.Panels.RowHelper(row);
        },

        hasHelpers: function hasHelpers(obj) {
          if (obj.hasClass('motopress-row')) {
            return this.hasRowHelpers(obj);
          } else {
            return this.hasSpanHelpers(obj);
          }
        },
        hasRowHelpers: function hasRowHelpers(row) {
          var rowEdge = parent.MP.Utils.getEdgeRow(row);
          return rowEdge.children('.mpce-row-tools-wrapper').length;
        },
        hasSpanHelpers: function hasSpanHelpers(span) {
          var spanEdge = parent.MP.Utils.getEdgeSpan(span);

          return spanEdge.children('.motopress-splitter').length !== 0;
        },

        wrapIntoBlockContent: function wrapIntoBlockContent(span) {
          var spanEdge = parent.MP.Utils.getEdgeSpan(span); 

          if (parent.MP.Utils.isSpanWrapper(span)) {
            return;
          } 


          if (spanEdge.children('.motopress-block-content').length) {
            return;
          } 


          spanEdge.find('script').remove().end().wrapInner(CE.DragDrop.myThis.blockContent.clone());
          var widgetWrapper = spanEdge.find('.motopress-block-content');
          new CE.Panels.WidgetHelper(widgetWrapper);

          if (!widgetWrapper.children(':not(.mpce-widget-tools-wrapper)').length) {
            widgetWrapper.append('<div />');
          }
        }
      });
    })(jQuery);

    can.view.stache('rowPanel', "<ul class=\"mpce-object-panel mpce-row-panel\">\n<li class=\"mpce-panel-btn-settings mpce-panel-btn\"><i class=\"fa fa-cog\"></i><span class=\"mpce-panel-btn-tooltip\">Section Settings</span></li>\n<li class=\"mpce-panel-btn-columns mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-columns\"></i><span class=\"mpce-panel-btn-tooltip\">Manage Columns</span></li>\n<li class=\"mpce-panel-btn-add mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-plus\"></i><span class=\"mpce-panel-btn-tooltip\">Add Section Before</span></li>\n<li class=\"mpce-panel-btn-duplicate mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-clone\"></i><span class=\"mpce-panel-btn-tooltip\">Duplicate Section</span></li>\n<li class=\"mpce-panel-btn-delete mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-trash\"></i><span class=\"mpce-panel-btn-tooltip\">Delete Section</span></li>\n<li class=\"mpce-panel-btn-save mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-save\"></i><span class=\"mpce-panel-btn-tooltip\">Save to Library</span></li>\n</ul>");
    can.view.stache('columnPanel', "<ul class=\"mpce-object-panel mpce-clmn-panel\">\n<li class=\"mpce-panel-btn-duplicate mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-clone\"></i><span class=\"mpce-panel-btn-tooltip\">Duplicate Column</span></li>\n<li class=\"mpce-panel-btn-delete mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-trash\"></i><span class=\"mpce-panel-btn-tooltip\">Delete Column</span></li>\n<li class=\"mpce-panel-btn-add mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-plus\"></i><span class=\"mpce-panel-btn-tooltip\">Add Column</span></li>\n<li class=\"mpce-panel-btn-settings mpce-panel-btn\"><i class=\"fa fa-columns\"></i><span class=\"mpce-panel-btn-tooltip\">Column Settings</span></li>\n</ul>");
    can.view.stache('widgetPanel', "<ul class=\"mpce-object-panel mpce-widget-panel\">\n<li class=\"mpce-panel-btn-settings mpce-panel-btn\"><i class=\"fa fa-edit\"></i><span class=\"mpce-panel-btn-tooltip\">Widget Settings</span></li>\n<li class=\"mpce-panel-btn-drag mpce-drag-handle mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-arrows\"></i><span class=\"mpce-panel-btn-tooltip\">Drag and Drop</span></li>\n<li class=\"mpce-panel-btn-duplicate mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-clone\"></i><span class=\"mpce-panel-btn-tooltip\">Duplicate Widget</span></li>\n<li class=\"mpce-panel-btn-delete mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-trash\"></i><span class=\"mpce-panel-btn-tooltip\">Delete Widget</span></li>\n<li class=\"mpce-panel-btn-save mpce-panel-btn mpce-panel-btn-secondary\"><i class=\"fa fa-save\"></i><span class=\"mpce-panel-btn-tooltip\">Save to Libary</span></li>\n</ul>");

    (function ($) {
      can.Control.extend('CE.Panels.BaseObjectHelper', {
        retrieveHelpersContainer: function retrieveHelpersContainer(el) {
          return el;
        }
      }, {
        helpersContainer: null,
        helpers: null,
        toolsWrapper: null,
        toolsWrapperTag: 'span',
        setup: function setup(el, args) {
          if (!args) {
            args = {};
          }

          args.helpersContainer = this.constructor.retrieveHelpersContainer(el);
          args = $.extend({
            toolsWrapperTag: 'span'
          }, args);
          return this._super(el, args);
        },
        init: function init(el, args) {
          this.helpersContainer = args.helpersContainer;
          this.initHelpers();
          this.insertHelpers();
        },
        restoreClone: function restoreClone($elementClone) {
          this.element = $elementClone; 
        },
        initHelpers: function initHelpers() {
          this.initToolsWrapper();
          this.helpers = [this.toolsWrapper];
        },

        initToolsWrapper: function initToolsWrapper() {
          this.toolsWrapper = $('<' + this.options.toolsWrapperTag + '/>', {
            'class': 'mpce-tools-wrapper'
          });
        },
        insertHelpers: function insertHelpers() {
          this.helpersContainer.append(this.helpers);
        },
        removeHelpers: function removeHelpers() {
          $.map(this.helpers, function (helper, index) {
            helper.remove();
          });
        },
        destroy: function destroy() {
          this.removeHelpers();

          this._super();
        },
        '{helpersContainer} >.mpce-tools-wrapper mouseenter': function helpersContainerMpceToolsWrapperMouseenter(el, e) {
          if ($('body').is('.motopress-drag-action')) {
            return;
          }

          this.element.addClass('motopress-selected');
        },
        '{helpersContainer} >.mpce-tools-wrapper mouseleave': function helpersContainerMpceToolsWrapperMouseleave(el, e) {
          this.element.removeClass('motopress-selected');
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-delete click': function helpersContainerMpceToolsWrapperMpcePanelBtnDeleteClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          CE.LayoutManager.removeBlock(this.element);
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-duplicate click': function helpersContainerMpceToolsWrapperMpcePanelBtnDuplicateClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          CE.LayoutManager.duplicateBlock(this.element);
        }
      });
    })(jQuery);

    (function ($) {
      CE.Panels.BaseObjectHelper('CE.Panels.BaseColumnHelper', {
        retrieveHelpersContainer: function retrieveHelpersContainer(el) {
          return parent.MP.Utils.getEdgeSpan(el);
        }
      }, {
        init: function init(el, args) {
          this._super(el, args);

          CE.DragDrop.myThis.resizer.makeSplittable(this.element);
        },
        initToolsWrapper: function initToolsWrapper() {
          this._super();

          this.toolsWrapper.addClass('mpce-clmn-tools-wrapper').append(CE.DragDrop.myThis.focusArea.clone(), can.view('columnPanel')());
          CE.DragDrop.myThis.setCalcWrapperWidth(this.toolsWrapper);
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-settings click': function helpersContainerMpceToolsWrapperMpcePanelBtnSettingsClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          CE.Selectable.myThis.select(el); 
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-add click': function helpersContainerMpceToolsWrapperMpcePanelBtnAddClick(el, e) {
          var row = this.element.closest('.motopress-row');
          var positionOfThisClmn = row.children('.motopress-clmn').index(this.element) + 1;
          CE.LayoutManager.addColumnToRow(row, null, positionOfThisClmn + 1);
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-move-right click': function helpersContainerMpceToolsWrapperMpcePanelBtnMoveRightClick(el, e) {
          var nextClmn = this.element.next('.motopress-clmn');

          if (nextClmn.length) {
            nextClmn.after(this.element);
          }
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-move-left click': function helpersContainerMpceToolsWrapperMpcePanelBtnMoveLeftClick(el, e) {
          var prevClmn = this.element.prev('.motopress-clmn');

          if (prevClmn.length) {
            prevClmn.before(this.element);
          }
        }
      });
    })(jQuery);

    (function ($) {
      CE.Panels.BaseColumnHelper('CE.Panels.SimpleColumnHelper', {}, {
        initToolsWrapper: function initToolsWrapper() {
          this._super();

          this.toolsWrapper.prepend($('<div />', {
            "class": 'mpce-btn-add-row',
            html: '+',
            title: 'Add Column'
          }));
        },
        '{helpersContainer} > .mpce-tools-wrapper .mpce-btn-add-row click': function helpersContainerMpceToolsWrapperMpceBtnAddRowClick(el, e) {
          var span = CE.DragDrop.myThis.spanInnerHtml.clone();
          CE.LayoutManager.insertWidgetToPosition(span, this.element, 'bottom-in', true);
        }
      });
    })(jQuery);

    (function ($) {
      CE.Panels.SimpleColumnHelper('CE.Panels.EmptyColumnHelper', {}, {
        overlay: null,
        splitter: null,
        initHelpers: function initHelpers() {
          this._super();

          this.initOverlay();
          this.initSplitter();
          this.helpers.push(this.overlay, this.splitter);
        },
        initSplitter: function initSplitter() {
          this.splitter = CE.Resizer.myThis.splitter.clone();
        },
        initOverlay: function initOverlay() {
          this.overlay = $('<div />', {
            'class': 'motopress-overlay'
          });
          this.overlay.append(CE.DragDrop.myThis.handles.intermediate.clone(), CE.DragDrop.myThis.handles.left.clone(), CE.DragDrop.myThis.handles.right.clone(), CE.DragDrop.myThis.handles.insert.clone());
          CE.DragDrop.myThis.setCalcWrapperWidth(this.overlay);

          if (CE.Grid.myThis.padding) {
            this.overlay.css('left', CE.Grid.myThis.padding);
          }
        },
        '.motopress-filler-content click': function motopressFillerContentClick(el, e) {
          if (this.element) {
            e.preventDefault();
            e.stopPropagation();
            parent.CE.Panels.WidgetsDialog.myThis.open(this.element);
          }
        }
      });
    })(jQuery);

    (function ($) {
      CE.Panels.SimpleColumnHelper('CE.Panels.ColumnHelper', {}, {
        overlay: null,
        splitter: null,
        initHelpers: function initHelpers() {
          this._super();

          this.initOverlay();
          this.initSplitter();
          this.helpers.push(this.overlay, this.splitter);
        },
        initOverlay: function initOverlay() {
          this.overlay = $('<div />', {
            "class": 'motopress-overlay'
          });
          this.overlay.append(CE.DragDrop.myThis.handles.intermediate.clone(), CE.DragDrop.myThis.handles.left.clone(), CE.DragDrop.myThis.handles.topIn.clone(), CE.DragDrop.myThis.handles.bottomIn.clone(), CE.DragDrop.myThis.handles.leftOut.clone(), CE.DragDrop.myThis.handles.rightOut.clone(), CE.DragDrop.myThis.handles.leftIn.clone(), CE.DragDrop.myThis.handles.rightIn.clone(), CE.DragDrop.myThis.handles.right.clone());
          CE.DragDrop.myThis.setCalcWrapperWidth(this.overlay);

          if (CE.Grid.myThis.padding) {
            this.overlay.css('left', CE.Grid.myThis.padding);
          }
        },
        initSplitter: function initSplitter() {
          this.splitter = CE.Resizer.myThis.splitter.clone();
        },
        removeWidget: function removeWidget() {
          var fillerContent = $('<div />', {
            "class": 'motopress-filler-content',
            html: '<i class="fa fa-plus"></i>'
          });
          this.element.children('.motopress-block-content').html(fillerContent);
        }
      });
    })(jQuery);

    (function ($) {
      CE.Panels.BaseColumnHelper('CE.Panels.WrapperColumnHelper', {
        wrapperId: 0,

        getNextWrapperId: function getNextWrapperId() {
          CE.Panels.WrapperColumnHelper.wrapperId++;
          return CE.Panels.WrapperColumnHelper.wrapperId;
        }
      }, {
        wrapperHelper: null,
        setup: function setup(el, options) {
          if (!options) {
            options = {};
          }

          options = $.extend({
            toolsWrapperTag: 'section'
          }, options);
          return this._super(el, options);
        },
        initHelpers: function initHelpers() {
          this._super();

          this.helpers.push(CE.Resizer.myThis.splitter.clone());
        },
        initToolsWrapper: function initToolsWrapper() {
          this._super();

          this.toolsWrapper.addClass('mpce-clmn-wrapper-tools-wrapper motopress-wrapper-helper').append(CE.DragDrop.myThis.focusArea.clone(), CE.DragDrop.myThis.handles.intermediate.clone(), CE.DragDrop.myThis.handles.left.clone(), CE.DragDrop.myThis.handles.right.clone());
        }
      });
    })(jQuery);

    (function ($) {
      CE.Panels.BaseObjectHelper('CE.Panels.RowHelper', {
        retrieveHelpersContainer: function retrieveHelpersContainer(el) {
          return parent.MP.Utils.getEdgeRow(el);
        }
      }, {
        initToolsWrapper: function initToolsWrapper() {
          this._super();

          this.toolsWrapper.addClass('mpce-row-tools-wrapper').append(CE.DragDrop.myThis.focusArea.clone(), $('<div />', {
            "class": 'mpce-btn-add-row',
            html: '+',
            title: 'Add Column'
          }), can.view('rowPanel')());
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-settings click': function helpersContainerMpceToolsWrapperMpcePanelBtnSettingsClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          CE.Selectable.myThis.select(el); 
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-columns click': function helpersContainerMpceToolsWrapperMpcePanelBtnColumnsClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          parent.CE.Panels.LayoutChooserDialog.myThis.open({
            positionTarget: el,
            editedRow: this.element
          });
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-save click': function helpersContainerMpceToolsWrapperMpcePanelBtnSaveClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          var saveObjectPromise = parent.CE.SaveObjectModal.myThis.showModal({
            categories: parent.CE.ObjectTemplatesLibrary.myThis.getRowCategories()
          });
          saveObjectPromise.done(this.proxy(function (objectTemplateAtts) {
            var category = objectTemplateAtts.category;

            if (!objectTemplateAtts.name) {
              objectTemplateAtts.name = 'Row';
            }

            if (objectTemplateAtts.hasOwnProperty('newCategoryTitle')) {
              category = objectTemplateAtts.newCategoryTitle ? parent.CE.ObjectTemplatesLibrary.myThis.addRowCategory(objectTemplateAtts.newCategoryTitle) : 0;
            }

            var contentParser = new parent.CE.ContentTemplateParser(this.element);
            var row = new parent.CE.ObjectTemplatesLibrary.RowTemplate({
              name: objectTemplateAtts.name,
              category: category,
              content: contentParser.content,
              styles: contentParser.replaceableStyles
            });
            parent.CE.ObjectTemplatesLibrary.myThis.saveRow(row);
          }));
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-add click': function helpersContainerMpceToolsWrapperMpcePanelBtnAddClick(el, e) {
          if (this.element.prev('.mpce-add-section-panel').length) {
            return;
          }

          var addSectionPanelEl = $('<section />', {
            "class": 'mpce-add-section-panel'
          });
          this.element.before(addSectionPanelEl);
          var nestingLvl = parent.MP.Utils.detectRowNestingLvl(this.element);
          var addSectionParameters = {
            layoutDialog: parent.CE.Panels.LayoutChooserDialog.myThis
          };

          if (nestingLvl == 1) {
            addSectionParameters['templateDialog'] = parent.CE.Panels.TemplateChooserDialog.myThis;
          }

          new CE.Panels.AddSectionPanel(addSectionPanelEl, addSectionParameters);
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-btn-add-row click': function helpersContainerMpceToolsWrapperMpceBtnAddRowClick(el, e) {
          var span = CE.DragDrop.myThis.spanInnerHtml.clone();
          var insertMarker = this.element.next('.motopress-handle-middle-in');
          CE.LayoutManager.insertWidgetToPosition(span, insertMarker, 'middle-in', true);
        }
      });
    })(jQuery);

    (function ($) {
      CE.Panels.BaseObjectHelper('CE.Panels.WidgetHelper', {}, {
        initToolsWrapper: function initToolsWrapper() {
          this._super();

          this.toolsWrapper.addClass('mpce-widget-tools-wrapper').append( 
          can.view('widgetPanel')());
        },

        getShortcodeEl: function getShortcodeEl() {
          return this.element.children('[data-motopress-shortcode]');
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-settings click': function helpersContainerMpceToolsWrapperMpcePanelBtnSettingsClick(el, e) {
          CE.Selectable.myThis.selectWidget(this.getShortcodeEl());
        },
        '{helpersContainer} >.mpce-tools-wrapper .mpce-panel-btn-save click': function helpersContainerMpceToolsWrapperMpcePanelBtnSaveClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          var saveObjectPromise = parent.CE.SaveObjectModal.myThis.showModal({
            categories: parent.CE.ObjectTemplatesLibrary.myThis.getWidgetCategories()
          });
          saveObjectPromise.done(this.proxy(function (objectTemplateAtts) {
            var category = objectTemplateAtts.category;

            if (objectTemplateAtts.hasOwnProperty('newCategoryTitle')) {
              category = objectTemplateAtts.newCategoryTitle ? parent.CE.ObjectTemplatesLibrary.myThis.addWidgetCategory(objectTemplateAtts.newCategoryTitle) : 0;
            }

            if (CE.StyleEditor.myThis.hasObjectPrivateStyle(this.getShortcodeEl())) {
              CE.StyleEditor.myThis.mergeStylesToNewPreset(this.getShortcodeEl());
              CE.StyleEditor.myThis.savePresets();
            }

            var shortcodeId = this.getShortcodeEl().attr('data-motopress-shortcode');

            if (!objectTemplateAtts.name) {
              var widgetObject = parent.CE.WidgetsLibrary.myThis.getObjectById(shortcodeId);
              objectTemplateAtts.name = widgetObject.name;
            }

            var parser = new parent.CE.ContentParser(this.getShortcodeEl());
            var widget = new parent.CE.ObjectTemplatesLibrary.WidgetTemplate({
              name: objectTemplateAtts.name,
              content: parser.content,
              shortcodeId: shortcodeId,
              category: category,
              atts: parent.CE.ShortcodeAtts.getAttrsFromElement(this.getShortcodeEl())
            });
            parent.CE.ObjectTemplatesLibrary.myThis.saveWidget(widget);
          }));
        }
      });
    })(jQuery);

    (function ($) {
      can.Control('CE.DraggableObject', {}, {
        init: function init(el, args) {
          this.makeDraggable(el);
        },
        restoreClone: function restoreClone($elementClone) {
          this.element = $elementClone;
          this.element.removeData('uiDraggable');
          this.makeDraggable(this.element);
        },
        makeDraggable: function makeDraggable(el) {
          el.draggable({
            cursor: 'move',
            distance: 5,
            helper: 'clone',
            handle: '.mpce-drag-handle',
            opacity: '0',
            zIndex: 1,
            appendTo: '.motopress-content-wrapper:first',
            start: this.proxy('onDragStart'),
            stop: this.proxy('onDragStop'),
            drag: this.proxy('onDrag')
          }) 
          .removeClass('ui-draggable');
        },

        onDragStart: function onDragStart(e, ui) {
          CE.DragDrop.myThis.draggingElement = ui.helper.prevObject;
          CE.DragDrop.myThis.$droppedSpan = null;
          CE.Utils.addSceneAction('drag');
          CE.LeftBar.myThis.disable();
          CE.DragDrop.myThis.showOuterHandles();
          CE.DragDrop.myThis.canDrop = true;
          CE.Selectable.myThis.unselect();
          $('.motopress-splitter').addClass('motopress-hide');
          this.element.css('opacity', 0.3);
        },

        onDragStop: function onDragStop(e, ui) {
          CE.Utils.removeSceneAction('drag');
          CE.LeftBar.myThis.enable();
          CE.DragDrop.myThis.hideOuterHandles();
          $('.motopress-splitter').removeClass('motopress-hide');
          this.element.css('opacity', '');
          CE.DragDrop.myThis.helperContainer.children().hide();
        },

        onDrag: function onDrag(e, ui) {
          CE.DragDrop.myThis.onDrag();
        }
      });
    })(jQuery);

    (function ($) {
      can.Control('CE.DraggableNewBlock', {}, {
        contentWrapperTop: 0,
        cursorAt: {
          top: -10,
          left: -10
        },

        init: function init(el, args) {
          var contentWrapper = $('.motopress-content-wrapper:first');
          this.contentWrapperTop = contentWrapper.length ? contentWrapper.offset().top : 0;
          el.draggable({
            cursor: 'move',
            cursorAt: this.cursorAt,
            helper: 'clone',
            zIndex: 10002,
            appendTo: '.motopress-content-wrapper:first',
            drag: this.proxy('onDrag'),
            start: this.proxy('onDragStart'),
            stop: this.proxy('onDragStop')
          });
        },

        onDrag: function onDrag(e, ui) {
          ui.position.top = e.pageY - this.contentWrapperTop - this.cursorAt.top;
          CE.DragDrop.myThis.onDrag();
        },

        onDragStart: function onDragStart(e, ui) {
          CE.DragDrop.myThis.draggingElement = ui.helper;
          CE.DragDrop.myThis.$droppedSpan = null;
          CE.Utils.addSceneAction('drag');
          CE.LeftBar.myThis.disable();
          CE.DragDrop.myThis.showOuterHandles();
          CE.DragDrop.myThis.canDrop = true;
          CE.Selectable.myThis.unselect();
          CE.DragDrop.myThis.draggingElement.data('dropped', false);
        },

        onDragStop: function onDragStop(e, ui) {
          CE.Utils.removeSceneAction('drag');
          CE.LeftBar.myThis.enable();
          CE.DragDrop.myThis.hideOuterHandles();
          CE.DragDrop.myThis.helperContainer.children().hide(); 

          if (CE.DragDrop.myThis.draggingElement.data('dropped') === false) {
            var dropped = CE.DragDrop.myThis.emulateDropLastMiddleIn();
            if (dropped) parent.CE.Save.changeContent();
          }
        }
      });
    })(jQuery);

    (function ($) {
      can.Control('CE.DroppableZone', {}, {
        handleName: '',
        init: function init(el, args) {
          this.makeDroppable(el);
          el.addClass('motopress-handle-inited');
          this.handleName = el.attr('data-motopress-position');
        },
        restoreClone: function restoreClone($elementClone) {
          this.element = $elementClone;
          this.element.removeData('uiDroppable');
          this.makeDroppable(this.element);
        },
        makeDroppable: function makeDroppable($element) {
          $element.droppable({
            accept: '.motopress-clmn, .motopress-ce-object',
            tolerance: 'pointer',
            hoverClass: 'motopress-droppable-hover',
            drop: this.proxy('onDrop'),
            over: this.proxy('onOver'),
            out: this.proxy('onOut')
          });
        },

        onDrop: function onDrop(e, ui) {
          if (typeof CE.DragDrop.myThis.hoveredHandles !== 'undefined' && CE.DragDrop.myThis.hoveredHandles.length > 1 && CE.DragDrop.myThis.hoveredHandles.filter('.motopress-handle-left, .motopress-handle-right').length) {
            if ($.inArray(this.handleName, ['left', 'right']) > -1) {
              CE.DragDrop.myThis.canDrop = true;
            } else {
              return false;
            }
          }

          if (!CE.DragDrop.myThis.canDrop) {
            return false;
          }

          CE.DragDrop.myThis.canDrop = false;
          CE.DragDrop.myThis.draggingElement.data('dropped', true);
          var insertMarker = this.handleName === 'middle-in' ? this.element : this.element.closest('.motopress-clmn');
          var isNewBlock = CE.DragDrop.myThis.draggingElement.hasClass('motopress-ce-object');
          var block = CE.DragDrop.myThis.draggingElement;

          if (isNewBlock) {
            if (CE.DragDrop.myThis.draggingElement.hasClass('motopress-ce-widget-template')) {
              var widgetId = CE.DragDrop.myThis.draggingElement.attr('data-id');
              var savedWidget = parent.CE.ObjectTemplatesLibrary.myThis.getWidgetById(widgetId);
              block = CE.DragDrop.myThis.createNewBlock(savedWidget.atts, true);
            } else {
              block = CE.DragDrop.myThis.createNewBlock(parent.CE.ShortcodeAtts.getAttrsFromElement(block));
            }
          }

          var inserted = CE.LayoutManager.insertWidgetToPosition(block, insertMarker, this.handleName, isNewBlock);

          if (!inserted) {
            parent.MP.Flash.setFlash(localStorage.getItem('blocksOverflow'), 'error');
            parent.MP.Flash.showMessage();
          } else if (isNewBlock) {
            var droppableTimeout = setTimeout(function () {
              CE.Selectable.myThis.showWidgetMainEditTool(block.find('[data-motopress-shortcode]:first'));
              clearTimeout(droppableTimeout);
            }, 0);
          }
        },
        onOver: function onOver(e, ui) {
          var handleEl = this.element;
          CE.DragDrop.myThis.hoveredHandles = CE.Scene.myThis.container.find('.motopress-droppable-hover');
          var span = this.handleName !== 'middle-in' ? handleEl.closest('.motopress-clmn') : handleEl;
          CE.DragDrop.myThis.showLineTextHelper(span, this.handleName);
        },
        onOut: function onOut(e, ui) {
          CE.DragDrop.myThis.hoveredHandles = CE.Scene.myThis.container.find('.motopress-droppable-hover');
          CE.DragDrop.myThis.onDrag();
        }
      });
    })(jQuery);

    (function ($) {
      can.Construct('CE.DragDrop',
      {
        myThis: null
      },
      {
        blockContent: $('<div />', {
          'class': 'motopress-block-content'
        }),
        handles: {
          topIn: $('<div />', {
            'class': 'motopress-handle-top-in',
            'data-motopress-position': 'top-in'
          }),
          bottomIn: $('<div />', {
            'class': 'motopress-handle-bottom-in',
            'data-motopress-position': 'bottom-in'
          }),
          intermediate: $('<div />', {
            'class': 'motopress-handle-intermediate',
            'data-motopress-position': 'intermediate'
          }),
          leftOut: $('<div />', {
            'class': 'motopress-handle-left-out',
            'data-motopress-position': 'left-out'
          }),
          rightOut: $('<div />', {
            'class': 'motopress-handle-right-out',
            'data-motopress-position': 'right-out'
          }),
          leftIn: $('<div />', {
            'class': 'motopress-handle-left-in',
            'data-motopress-position': 'left-in'
          }),
          rightIn: $('<div />', {
            'class': 'motopress-handle-right-in',
            'data-motopress-position': 'right-in'
          }),
          left: $('<div />', {
            'class': 'motopress-outer-handle motopress-handle-left motopress-outer-handle-hidden',
            'data-motopress-position': 'left'
          }),
          right: $('<div />', {
            'class': 'motopress-outer-handle motopress-handle-right motopress-outer-handle-hidden',
            'data-motopress-position': 'right'
          }),
          insert: $('<div />', {
            'class': 'motopress-handle-insert',
            'data-motopress-position': 'insert'
          }),
          middleIn: $('<span />', {
            'class': 'motopress-outer-handle motopress-handle-middle-in motopress-outer-handle-hidden',
            'data-motopress-position': 'middle-in'
          })
        },
        focusArea: $('<div />', {
          'class': 'motopress-focus-area',
          tabindex: -1
        }),
        lineHelpers: {
          intermediate: $('<div />', {
            'class': 'motopress-line-helper-intermediate'
          }),
          leftOut: $('<div />', {
            'class': 'motopress-line-helper-left-out'
          }),
          rightOut: $('<div />', {
            'class': 'motopress-line-helper-right-out'
          }),
          left: $('<div />', {
            'class': 'motopress-line-helper-left'
          }),
          right: $('<div />', {
            'class': 'motopress-line-helper-right'
          }),
          leftIn: $('<div />', {
            'class': 'motopress-line-helper-left-in'
          }),
          rightIn: $('<div />', {
            'class': 'motopress-line-helper-right-in'
          }),
          topIn: $('<div />', {
            'class': 'motopress-line-helper-top-in'
          }),
          bottomIn: $('<div />', {
            'class': 'motopress-line-helper-bottom-in'
          }),
          handleMiddle: $('<div />', {
            'class': 'motopress-line-helper-middle-in'
          }),
          insert: $('<div />', {
            'class': 'motopress-line-helper-insert'
          })
        },
        textHelpers: {
          intermediate: $('<div />', {
            'class': 'motopress-text-helper-intermediate',
            text: localStorage.getItem('helperNewColumn')
          }),
          leftOut: $('<div />', {
            'class': 'motopress-text-helper-left-out',
            text: localStorage.getItem('helperNewColumn')
          }),
          rightOut: $('<div />', {
            'class': 'motopress-text-helper-right-out',
            text: localStorage.getItem('helperNewColumn')
          }),
          left: $('<div />', {
            'class': 'motopress-text-helper-left',
            text: localStorage.getItem('helperNewColumn')
          }),
          right: $('<div />', {
            'class': 'motopress-text-helper-right',
            text: localStorage.getItem('helperNewColumn')
          }),
          leftIn: $('<div />', {
            'class': 'motopress-text-helper-left-in',
            text: localStorage.getItem('helperInsert')
          }),
          rightIn: $('<div />', {
            'class': 'motopress-text-helper-right-in',
            text: localStorage.getItem('helperInsert')
          }),
          topIn: $('<div />', {
            'class': 'motopress-text-helper-top-in',
            text: localStorage.getItem('helperInsert')
          }),
          bottomIn: $('<div />', {
            'class': 'motopress-text-helper-bottom-in',
            text: localStorage.getItem('helperInsert')
          }),
          handleMiddle: $('<div />', {
            'class': 'motopress-text-helper-middle-in',
            text: localStorage.getItem('helperMiddle')
          }),
          insert: $('<div />', {
            'class': 'motopress-text-helper-insert',
            text: localStorage.getItem('helperInsert')
          })
        },
        textHelperHalfSize: null,
        lineHelperThickness: null,
        lineHelperHalfThickness: null,
        handleMiddleHalfThickness: null,
        helperContainer: $('<div />', {
          'class': 'motopress-helper-container'
        }).appendTo('body'),
        newBlock: null,
        spaceClass: 'motopress-space',
        resizer: null,
        tools: null,
        canDrop: false,
        spanSizeRules: null,
        rowHtml: null,
        rowInnerHtml: null,
        spanHtml: null,
        spanInnerHtml: null,
        bodyEl: $('body'),
        draggingElement: null,

        $droppedSpan: null,
        init: function init() {
          CE.DragDrop.myThis = this;
          this.setEdges();
          new CE.Grid($('#motopress-ce-grid'));
          this.setupNewBlock();
          this.resizer = new CE.Resizer();
          this.tools = new CE.Tools();
          new CE.HelpersManager();
          this.generateSpanSizeRules();
          this.fixHelpersSize();
          this.helperContainer.append(Object.values(this.lineHelpers), Object.values(this.textHelpers));
          this.setupDefaultGridHtml();
          this.main();
          parent.MP.Preloader.myThis.load(CE.DragDrop.shortName);
          parent.MP.Preloader.myThis.hide();
        },
        fixHelpersSize: function fixHelpersSize() {
          var calcT = setTimeout(function () {
            CE.DragDrop.myThis.textHelperHalfSize = Math.round(CE.DragDrop.myThis.textHelpers.handleMiddle.height() / 2);
            CE.DragDrop.myThis.lineHelperThickness = CE.DragDrop.myThis.lineHelpers.leftIn.outerWidth();
            CE.DragDrop.myThis.lineHelperHalfThickness = Math.round(CE.DragDrop.myThis.lineHelpers.handleMiddle.height() / 2);
            CE.DragDrop.myThis.handleMiddleHalfThickness = Math.round(6 / 2);
            clearTimeout(calcT);
          }, 0);
        },
        setEdges: function setEdges() {
          var editableRows = $('.motopress-content-wrapper .motopress-row').not('.motopress-content-wrapper [data-motopress-group]:not([data-motopress-group="mp_grid"]) .motopress-row');
          CE.Scene.markEdgeRow(editableRows); 

          var editableSpans = $('.motopress-content-wrapper .motopress-clmn').not('.motopress-content-wrapper [data-motopress-group]:not([data-motopress-group="mp_grid"]) .motopress-clmn');
          CE.Scene.markEdgeSpan(editableSpans.not('.motopress-empty'));
          CE.Scene.markEdgeSpan(editableSpans.filter('.motopress-empty'), true);
        },
        setupNewBlock: function setupNewBlock() {
          var newBlock = $(parent.motopressCE.rendered_shortcodes.grid[parent.CE.Iframe.myThis.gridObj.span.shortcode]);
          newBlock.addClass(parent.CE.Iframe.myThis.gridObj.span.minclass);
          CE.Scene.markEdgeSpan(newBlock, true, true);
          this.newBlock = newBlock;
        },

        createNewBlock: function createNewBlock(atts, isTemplate) {
          isTemplate = typeof isTemplate !== 'undefined' ? isTemplate : false;
          var block = this.newBlock.clone();

          if (atts.id === 'mp_space') {
            block.addClass(this.spaceClass);
          }

          this.makeEditable(block, atts, true, null, isTemplate);

          if (isTemplate) {
            block.find('[data-motopress-shortcode]:first').attr('data-need-detect-inner-ctrls', true);
          }

          return block;
        },
        setupDefaultGridHtml: function setupDefaultGridHtml() {
          this.spanHtml = $(parent.motopressCE.rendered_shortcodes.grid[parent.CE.Iframe.myThis.gridObj.span.shortcode]);
          CE.Scene.markEdgeSpan(this.spanHtml, true);
          CE.Resizer.myThis.setSpanSize(this.spanHtml, '');
          this.spanInnerHtml = $(parent.motopressCE.rendered_shortcodes.grid[parent.CE.Iframe.myThis.gridObj.span.inner]);
          CE.Scene.markEdgeSpan(this.spanInnerHtml, true);
          CE.Resizer.myThis.setSpanSize(this.spanInnerHtml, '');
          this.rowHtml = $(parent.motopressCE.rendered_shortcodes.grid[parent.CE.Iframe.myThis.gridObj.row.shortcode]);
          CE.Scene.markEdgeRow(this.rowHtml);
          this.rowInnerHtml = $(parent.motopressCE.rendered_shortcodes.grid[parent.CE.Iframe.myThis.gridObj.row.inner]);
          CE.Scene.markEdgeRow(this.rowInnerHtml);
        },

        main: function main($startFrom) {
          $startFrom = $startFrom !== undefined ? $startFrom : $('.motopress-content-wrapper'); 

          var editableRows = $('.motopress-content-wrapper .motopress-row').not('.motopress-content-wrapper [data-motopress-group]:not([data-motopress-group="mp_grid"]) .motopress-row'); 

          var editableSpans;

          if ($startFrom.hasClass('motopress-clmn')) {
            editableSpans = $startFrom.find('.motopress-clmn').addBack();
          } else {
            editableSpans = $startFrom.find('.motopress-clmn');
          }

          editableSpans = editableSpans.not('.motopress-content-wrapper [data-motopress-group]:not([data-motopress-group="mp_grid"]) .motopress-clmn'); 

          this.recursiveAddHandleMiddle($('.motopress-content-wrapper'));
          this.makeEditable(editableSpans);
          this.makeRowEditable(editableRows);
          this.resizer.updateSplittableOptions(null, null, null, 'init');
          this.resizer.updateAllHandles();

          if (this.isEmptyScene()) {
            CE.Utils.addSceneState('empty-scene');
          }
        },
        isEmptyScene: function isEmptyScene() {
          return !$('.motopress-content-wrapper>.motopress-row').length;
        },

        makeRowEditable: function makeRowEditable(rows, isNew) {
          isNew = typeof isNew !== 'undefined' && isNew;
          rows.each(function () {
            var $row = $(this);

            if (!CE.HelpersManager.myThis.hasHelpers($row)) {
              CE.HelpersManager.myThis.addHelpersToRow($row);
            }

            var shortcode = $row.attr('data-motopress-shortcode');

            if (isNew || !shortcode) {
              CE.DragDrop.myThis.setAttrs($row);
            }

            var ctrl = $row.control(CE.Controls);

            if (typeof ctrl === 'undefined') {
              $row.ce_controls({
                isNew: isNew
              });
              ctrl = $row.control(CE.Controls);
            }

            if ((isNew || !shortcode) && ctrl) {
              ctrl.stylesCtrl.setDefaultAttrs();
              var parameters = typeof $row.attr('data-motopress-parameters') !== 'undefined' ? $row.attr('data-motopress-parameters') : null;
              ctrl.settingsCtrl.setDefaultAttrs(parameters); 

              ctrl.stylesCtrl.save();
            }
          });
        },

        makeEditable: function makeEditable(obj, sourceAttrs, isNew, spanAttrs, preventSetDefaults) {
          isNew = typeof isNew !== 'undefined' && isNew;
          preventSetDefaults = typeof preventSetDefaults !== 'undefined' ? preventSetDefaults : false;
          obj.each(this.proxy(function (index, span) {
            span = $(span); 

            if (span.closest('.motopress-block-content').length) {
              return true;
            }

            CE.Scene.markEdgeSpan(span);
            var spanEdge = parent.MP.Utils.getEdgeSpan(span);
            CE.HelpersManager.myThis.addHelpers(span);
            this.makeDraggable(span);
            this.makeDroppable();

            if (isNew) {
              span.addClass('motopress-new-object');
              this.setAttrs(span, spanAttrs);
            } 


            if (sourceAttrs) {
              var shortcode = span.find('> .motopress-block-content > div');

              if (shortcode.length) {
                this.setAttrs(shortcode, sourceAttrs);
              }
            } 


            span.ce_controls({
              isNew: isNew
            });
            var spanCtrl = span.control(CE.Controls);

            if (isNew && spanCtrl) {
              spanCtrl.stylesCtrl.setDefaultAttrs();
              var parameters = typeof span.attr('data-motopress-parameters') !== 'undefined' ? span.attr('data-motopress-parameters') : null;
              spanCtrl.settingsCtrl.setDefaultAttrs(parameters); 

              spanCtrl.stylesCtrl.save();
            }

            var blockContent = spanEdge && spanEdge.children('.motopress-block-content');

            if (blockContent && blockContent.length) {
              var widget = blockContent.children('[data-motopress-shortcode]');
              var widgetControl = this.retrieveWidgetControl(widget);

              if (!widgetControl) {
                var ctrl = this.initShortcodeController(widget, isNew && !preventSetDefaults);

                if (ctrl) {
                  isNew ? ctrl.renderShortcode('created', !preventSetDefaults) : ctrl.element.trigger('render');
                }
              }
            }
          }));
        },

        initShortcodeController: function initShortcodeController(shortcode, isNew) {
          var scName = shortcode.attr('data-motopress-shortcode');
          if (isNew) shortcode.addClass('motopress-new-object');

          switch (scName) {
            case 'mp_text':
            case 'mp_heading':
              shortcode.ce_inline_editor({
                isNew: isNew
              });
              break;

            case 'mp_code':
              shortcode.ce_code_editor({
                isNew: isNew
              });
              break;

            default:
              shortcode.ce_controls({
                isNew: isNew
              });
          }

          return shortcode.control(CE.Controls);
        },
        retrieveWidgetControl: function retrieveWidgetControl(shortcode) {
          return shortcode.control(CE.Controls);
        },

        setAttrs: function setAttrs(el, source) {
          if (typeof source === 'undefined' || source === null) {
            var shortcodeName = null;

            if (el.hasClass('motopress-row')) {
              shortcodeName = el.parent('.motopress-content-wrapper').length ? parent.CE.Iframe.myThis.gridObj.row.shortcode : parent.CE.Iframe.myThis.gridObj.row.inner;
            } else {
              if (parent.CE.Iframe.myThis.gridObj.span.type === 'multiple') {
                var spanClass = parent.MP.Utils.getSpanClass(el.prop('class').split(' '));
                var spanNumber = parent.MP.Utils.getSpanNumber(spanClass);
                shortcodeName = parent.CE.Iframe.myThis.gridObj.span.shortcode[spanNumber - 1];
              } else {
                shortcodeName = parent.CE.Iframe.myThis.gridObj.span.shortcode;
              }
            }

            parent.CE.WidgetsLibrary.myThis.setAttrs(el, parent.CE.WidgetsLibrary.myThis.getGroup('mp_grid').id, parent.CE.WidgetsLibrary.myThis.getObject('mp_grid', shortcodeName));
          } else {
            parent.CE.ShortcodeAtts.setAttsToEl(el, source);
          }
        },
        makeDraggable: function makeDraggable(obj) {
          if (!obj.control(CE.DraggableObject)) {
            new CE.DraggableObject(obj);
          }
        },

        emulateDropLastMiddleIn: function emulateDropLastMiddleIn() {
          var isNewBlock = this.draggingElement.hasClass('motopress-ce-object');
          var lastMiddleIn = $('.motopress-content-wrapper').find('.motopress-handle-middle-in:last');

          if (isNewBlock) {
            var block;

            if (this.draggingElement.hasClass('motopress-ce-widget-template')) {
              var widgetId = this.draggingElement.attr('data-id');
              var savedWidget = parent.CE.ObjectTemplatesLibrary.myThis.getWidgetById(widgetId);
              block = this.createNewBlock(savedWidget.atts, true);
            } else {
              block = this.createNewBlock(parent.CE.ShortcodeAtts.getAttrsFromElement(this.draggingElement));
            }

            if (lastMiddleIn.length) {
              CE.LayoutManager.insertWidgetToPosition(block, lastMiddleIn, 'middle-in', true);
            } else {
              CE.LayoutManager.insertWidgetToPosition(block, CE.Scene.myThis.addSectionPanelEl, 'before', true);
            }

            var droppableTimeout = setTimeout(function () {
              CE.Selectable.myThis.showWidgetMainEditTool(block.find('[data-motopress-shortcode]:first'));
              clearTimeout(droppableTimeout);
            }, 0);
            return true;
          }

          return false;
        },
        makeDroppable: function makeDroppable() {
          var handlesSelector = '.motopress-handle-intermediate, ' + '.motopress-handle-left, ' + '.motopress-handle-top-in, ' + '.motopress-handle-bottom-in, ' + '.motopress-handle-left-out, ' + '.motopress-handle-right-out, ' + '.motopress-handle-left-in, ' + '.motopress-handle-right-in, ' + '.motopress-handle-middle-in, ' + '.motopress-handle-insert, ' + '.motopress-handle-right';
          var handles = $('.motopress-content-wrapper').find(handlesSelector).filter(':not(.motopress-handle-inited)');
          $.each(handles, function () {
            new CE.DroppableZone($(this));
          });
        },
        showLineTextHelper: function showLineTextHelper(span, handle) {
          var spanOffset = span.offset(),
              spanLeft = spanOffset.left,
              spanTop = spanOffset.top,
              row = span.closest('.motopress-row'),
              rowEdge = parent.MP.Utils.getEdgeRow(row),
              rowEdgeHeight = rowEdge.height(),
              rowEdgeTop = rowEdge.offset().top,
              rowEdgeBorderTop = parseFloat(rowEdge.css('border-top-width')),
              rowEdgePaddingTop = parseFloat(rowEdge.css('padding-top')),
              linePiece,
              lineWidth,
              lineLeft,
              textLeft,
              width,
              lineBorder = 1,
              lineHelper,
              textHelper;

          if ($.inArray(handle, ['intermediate', 'left-out', 'right-out', 'left', 'right']) >= 0) {
            var handleIntermediate = null;

            if (parent.MP.Utils.isSpanWrapper(span)) {
              handleIntermediate = span.children('.motopress-wrapper-helper').find('.motopress-handle-intermediate');
            } else {
              handleIntermediate = span.children('.motopress-overlay').find('.motopress-handle-intermediate');
            }

            width = Math.floor(handleIntermediate.width());

            if (width > 4) {
              linePiece = 2;
              lineWidth = width - 4;
              lineLeft = width - linePiece;
            } else {
              linePiece = 1;
              lineWidth = lineLeft = width;
            }

            var leftShift = 0;

            if (!CE.Grid.myThis.padding) {
              leftShift = linePiece / 2;
            }
          } 


          switch (handle) {
            case 'intermediate':
              this.lineHelpers.intermediate.css({
                width: lineWidth,
                height: rowEdgeHeight,
                top: rowEdgeTop + rowEdgePaddingTop + rowEdgeBorderTop,
                left: Math.ceil(spanLeft + CE.Grid.myThis.padding - lineLeft - leftShift) 

              }).show();
              this.textHelpers.intermediate.css({
                top: this.lineHelpers.intermediate.offset().top,
                left: this.lineHelpers.intermediate.offset().left + lineWidth
              }).show();
              break;

            case 'left':
            case 'left-out':
              if (handle === 'left') {
                lineHelper = this.lineHelpers.left;
                textHelper = this.textHelpers.left;
              } else {
                lineHelper = this.lineHelpers.leftOut;
                textHelper = this.textHelpers.leftOut;
              }

              lineLeft = spanLeft + CE.Grid.myThis.padding + lineBorder;
              lineWidth = 0;
              lineHelper.css({
                width: lineWidth,
                height: rowEdgeHeight,
                top: rowEdgeTop + rowEdgePaddingTop + rowEdgeBorderTop,
                left: lineLeft
              }).show();
              textHelper.css({
                top: lineHelper.offset().top,
                left: lineLeft + lineWidth
              }).show();
              break;

            case 'right':
            case 'right-out':
              if (handle === 'right') {
                lineHelper = this.lineHelpers.right;
                textHelper = this.textHelpers.right;
              } else {
                lineHelper = this.lineHelpers.rightOut;
                textHelper = this.textHelpers.rightOut;
              }

              var docWidth = $(document).width();
              lineLeft = spanLeft + span.outerWidth() - CE.Grid.myThis.padding;
              if (lineLeft >= docWidth) lineLeft = docWidth - lineBorder;
              lineWidth = 0;
              textLeft = lineLeft - textHelper.outerWidth();
              lineHelper.css({
                width: lineWidth,
                height: rowEdgeHeight,
                top: rowEdgeTop + rowEdgePaddingTop + rowEdgeBorderTop,
                left: lineLeft
              }).show();
              textHelper.css({
                top: lineHelper.offset().top,
                left: textLeft
              }).show();
              break;

            case 'left-in':
              this.lineHelpers.leftIn.css({
                height: span.outerHeight(),
                top: spanTop,
                left: spanLeft + CE.Grid.myThis.padding
              }).show();
              this.textHelpers.leftIn.css({
                top: this.lineHelpers.leftIn.offset().top,
                left: this.lineHelpers.leftIn.offset().left + this.lineHelperThickness
              }).show();
              break;

            case 'right-in':
              this.lineHelpers.rightIn.css({
                height: span.outerHeight(),
                top: spanTop,
                left: spanLeft + span.outerWidth() - this.lineHelperThickness - CE.Grid.myThis.padding
              }).show();
              this.textHelpers.rightIn.css({
                top: this.lineHelpers.rightIn.offset().top,
                left: this.lineHelpers.rightIn.offset().left - this.textHelpers.rightIn.outerWidth()
              }).show();
              break;

            case 'top-in':
              this.lineHelpers.topIn.css({
                width: span.outerWidth(),
                top: spanTop,
                left: spanLeft
              }).show();
              this.textHelpers.topIn.css({
                top: spanTop,
                left: spanLeft
              }).show();
              break;

            case 'bottom-in':
              this.lineHelpers.bottomIn.css({
                width: span.outerWidth(),
                top: spanTop + span.outerHeight() - this.lineHelperThickness,
                left: spanLeft
              }).show();
              this.textHelpers.bottomIn.css({
                top: this.lineHelpers.bottomIn.offset().top,
                left: this.lineHelpers.bottomIn.offset().left
              }).show();
              break;

            case 'middle-in':
              var width = span.outerWidth();
              var height = span.height() - parseInt(this.lineHelpers.handleMiddle.css('outline-width')) * 2;
              var top = spanTop + parseInt(this.lineHelpers.handleMiddle.css('outline-width'));
              var left = spanLeft - CE.Grid.myThis.padding;
              var handleMiddleFirst = $('.motopress-content-wrapper > .motopress-handle-middle-in:first');

              if (span[0] === handleMiddleFirst[0] || span[0] === $('.motopress-content-wrapper > .motopress-handle-middle-in:last')[0]) {
                var contentWrapper = $('.motopress-content-wrapper');
                var contentWrapperOffset = contentWrapper.offset();
                width = contentWrapper.width();
                height = 20;

                if (span[0] === handleMiddleFirst[0]) {
                  top = contentWrapperOffset.top - height;
                }

                left = contentWrapperOffset.left - CE.Grid.myThis.padding;
              }

              this.lineHelpers.handleMiddle.css({
                width: width + CE.Grid.myThis.padding * 2,
                height: height,
                top: top,
                left: left
              }).show();
              var lineHelperOffset = this.lineHelpers.handleMiddle.offset();
              this.textHelpers.handleMiddle.css({
                top: lineHelperOffset.top,
                left: lineHelperOffset.left
              }).show();
              break;

            case 'insert':
              lineLeft = spanLeft + CE.Grid.myThis.padding;
              textLeft = lineLeft - this.textHelpers.insert.outerWidth();
              if (textLeft < 0) textLeft = lineLeft + span.width() + lineBorder;
              this.lineHelpers.insert.css({
                width: span.width(),
                height: span.outerHeight(),
                left: lineLeft,
                top: spanTop
              }).show();
              this.textHelpers.insert.css({
                top: this.lineHelpers.insert.offset().top,
                left: textLeft
              }).show();
              break;
          }
        },
        removeEmptyBlocks: function removeEmptyBlocks(block, spanClassDraggable) {
          var prevEmpty = block.prev('.motopress-empty');
          var nextEmpty = block.next('.motopress-empty');
          var prevEmptySpanNumber = 0;
          var nextEmptySpanNumber = 0;
          if (typeof prevEmpty[0] != 'undefined') prevEmptySpanNumber = parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(prevEmpty.prop('class').split(' ')));
          if (typeof nextEmpty[0] != 'undefined') nextEmptySpanNumber = parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(nextEmpty.prop('class').split(' ')));

          if (typeof prevEmpty[0] != 'undefined' || typeof nextEmpty[0] != 'undefined') {
            block.removeClass(spanClassDraggable);
            spanClassDraggable = parent.CE.Iframe.myThis.gridObj.span["class"] + (parent.MP.Utils.getSpanNumber(spanClassDraggable) + prevEmptySpanNumber + nextEmptySpanNumber);
            block.addClass(spanClassDraggable);
            prevEmpty.remove();
            nextEmpty.remove();
          }

          return spanClassDraggable;
        },

        clearIfEmpty: function clearIfEmpty(row, action) {
          if (typeof action === 'undefined') action = 'default';

          if (row !== null) {
            var rowEdge = parent.MP.Utils.getEdgeRow(row);
            var rowChildren = rowEdge.children('.motopress-clmn');

            if (!rowChildren.length) {
              var i = 0;

              while (row.parent('.motopress-content-wrapper').length === 0) {
                if (!row.siblings('.motopress-row, .motopress-clmn').length && !row.parent('.motopress-content-wrapper').length) {
                  row.siblings('.motopress-handle-middle-in, .motopress-wrapper-helper, .mpce-row-tools-wrapper').remove().end().unwrap();
                } else {
                  break;
                }

                i++;

                if (i === 100) {
                  console.log('LOOPED IN `clearIfEmpty()`');
                  break;
                }
              }

              var flag = false;
              var newRow = null;

              if (!row.siblings('.motopress-row').length) {
                flag = true;

                if (row.parent(':not(.motopress-content-wrapper)').length) {
                  newRow = row.parent();
                } else if (row.parent('.motopress-row').length) {
                  newRow = row.parent('.motopress-row');
                }
              }

              if (row.prev('.mpce-add-section-panel').length) {
                row.prev('.mpce-add-section-panel').remove();
              }

              row.prev('.motopress-handle-middle-in').remove().end().remove(); 

              var parentSpan = row.parent();

              if (typeof parentSpan !== 'undefined' && parentSpan !== null && !parentSpan.hasClass('motopress-content-wrapper')) {
                var parentSpanInnerRow = parentSpan.children('.motopress-row');

                if (parentSpanInnerRow.length === 1) {
                  var parentSpanInnerRowEdge = parent.MP.Utils.getEdgeRow(parentSpanInnerRow);
                  var parentSpanInnerSpan = parentSpanInnerRowEdge.children('.motopress-clmn');

                  if (parentSpanInnerSpan.length === 1) {
                    var sClass1 = parent.MP.Utils.getSpanClass(parentSpanInnerSpan.prop('class').split(' '));
                    var sClass2 = parent.MP.Utils.getSpanClass(parentSpan.prop('class').split(' '));
                    CE.Resizer.myThis.setSpanSize(parentSpanInnerSpan, sClass2);
                    parentSpan.replaceWith(parentSpanInnerSpan);
                  }
                }
              }

              return flag ? newRow : row; 
            } else if (rowChildren.length === 1 && !row.parent('.motopress-content-wrapper').length) {
              var children,
                  siblings,
                  parentWrapper = null;
              rowChildren.parentsUntil('.motopress-content-wrapper', '.motopress-clmn').each(function () {
                children = $(this).children('.motopress-row');
                siblings = $(this).siblings('.motopress-clmn');
                parentWrapper = $(this).closest('.motopress-row').parent('.motopress-content-wrapper');

                if (children.length === 1 && (siblings.length || parentWrapper.length)) {
                  var replacementSpan = $(this);
                  var replacementSpanSize = parent.MP.Utils.getSpanNumber(parent.MP.Utils.getSpanClass(replacementSpan.prop('class').split(' ')));
                  CE.Resizer.myThis.setSpanSize(rowChildren, replacementSpanSize);
                  replacementSpan.replaceWith(rowChildren);
                  row = action === 'remove' ? {
                    row: replacementSpan.parent()
                  } : null;
                }
              });
            }
          }

          return row;
        },

        recursiveAddHandleMiddle: function recursiveAddHandleMiddle(rowWrapper) {
          rowWrapper.children('.motopress-row').each(function (index) {
            var handleMiddle = CE.DragDrop.myThis.handles.middleIn;
            var handleMiddleCls = '.motopress-outer-handle';

            if (index == 0) {
              if (!$(this).prevAll(handleMiddleCls).length) {
                $(this).before(handleMiddle.clone());
              }
            }

            var $moreTag = $(this).next('.mpce-wp-more-tag');

            if ($moreTag.length) {
              if (!$moreTag.next(handleMiddleCls).length) {
                $moreTag.after(handleMiddle.clone());
              }
            } else {
              if (!$(this).next(handleMiddleCls).length) {
                $(this).after(handleMiddle.clone());
              }
            }

            var rowEdge = parent.MP.Utils.getEdgeRow($(this));
            rowEdge.children('.motopress-clmn').each(function () {
              if (parent.MP.Utils.isSpanWrapper($(this))) {
                CE.DragDrop.myThis.recursiveAddHandleMiddle(parent.MP.Utils.getEdgeSpan($(this)));
              }
            });
          });
        },

        addHandleMiddle: function addHandleMiddle(row) {
          row.parent('.motopress-clmn, .motopress-content-wrapper').children('.motopress-row').each(function (index) {
            var handleMiddle = CE.DragDrop.myThis.handles.middleIn;

            if (index == 0) {
              var prev = $(this).prevAll('.motopress-handle-middle-in');

              if (!prev.length) {
                $(this).before(handleMiddle.clone());

                if ($(this).parent().is('[data-motopress-wrapper-id]')) {
                  CE.DragDrop.myThis.setCalcWrapperWidth($(this).prev());
                }
              } else {
                prev.each(function (i, el) {
                  if (i != 0) {
                    $(el).remove();
                  }
                });
              }
            }

            var handleMiddleClone = handleMiddle.clone();
            var next = $(this).nextUntil('.motopress-row', '.motopress-handle-middle-in');

            if (next.length == 0) {
              if ($(this).next('.mpce-wp-more-tag').length) {
                $(this).next('.mpce-wp-more-tag').after(handleMiddleClone);
              } else {
                $(this).after(handleMiddleClone);
              }

              if ($(this).parent().is('[data-motopress-wrapper-id]')) {
                CE.DragDrop.myThis.setCalcWrapperWidth(handleMiddleClone);
              }
            } else {
              next.each(function (i, el) {
                if (i != 0) {
                  $(el).remove();
                }
              });
            }
          });
        },
        resetLastHandleMiddleHeight: function resetLastHandleMiddleHeight() {
          $('.motopress-content-wrapper > .motopress-handle-middle-in:last').height('');
        },

        getMinAllowedSpanSize: function getMinAllowedSpanSize(rowWidth, isWrapper, minChild) {
          var minSize = 1;
          var wrapperWidth;

          for (var size = 1; size <= parent.CE.Iframe.myThis.gridObj.row.col; size++) {
            minSize = size;

            if (isWrapper) {
              wrapperWidth = rowWidth / 100 * CE.Grid.myThis.colWidthByNumber[size];
              if (this.resizer.isAllowedColSize(minChild, wrapperWidth)) break;
            } else {
              if (this.resizer.isAllowedColSize(size, rowWidth)) break;
            }
          }

          return minSize;
        },

        recalcRowSizes: function recalcRowSizes(row, atts) {
          atts = typeof atts !== 'undefined' ? atts : {};
          atts = $.extend({
            insertPosition: null,
            wrapperPosition: null
          }, atts);
          var rowEdge = parent.MP.Utils.getEdgeRow(row);
          var rowWidth = parseFloat(rowEdge.css('width'));
          var spans = rowEdge.children('.motopress-clmn');

          if (!spans.length) {
            return [];
          }

          var spanCount = atts.insertPosition !== null ? spans.length + 1 : spans.length;

          if (spanCount > parent.CE.Iframe.myThis.gridObj.row.col) {
            return [];
          }

          var defaultSizes = this.getSpanSizeRules(spanCount);
          var minSize = this.getMinAllowedSpanSize(rowWidth, false);
          var sizeItems = [];
          var allowSpanWidth = true;
          var item;
          var newItem = {
            'index': atts.insertPosition,
            'isWrapper': false,
            'size': defaultSizes[atts.insertPosition],
            'minSize': minSize,
            'isAllowed': defaultSizes[atts.insertPosition] >= minSize
          };
          var realIndex = -1;
          spans.each(this.proxy(function (index, span) {
            realIndex++;

            if (atts.insertPosition !== null && atts.insertPosition === index) {
              item = newItem;
              realIndex++;
            } else {
              item = {};
              item.index = realIndex;
              item.isWrapper = $(span).is('[data-motopress-wrapper-id]');
              item.size = defaultSizes[realIndex];

              if (item.isWrapper) {
                item.wrapperWidth = rowWidth / 100 * CE.Grid.myThis.colWidthByNumber[defaultSizes[realIndex]];
                item.minSpan = this.resizer.getMinChildColumn($(span));
                item.minSize = this.getMinAllowedSpanSize(rowWidth, true, item.minSpan);
                item.isAllowed = this.resizer.isAllowedColSize(item.minSpan, item.wrapperWidth);
              } else {
                item.minSize = minSize; 

                if (atts.wrapperPosition === realIndex && item.minSize <= 1) {
                  item.minSize = 2;
                }

                item.isAllowed = this.resizer.isAllowedColSize(defaultSizes[realIndex], rowWidth);
              }
            }

            sizeItems.push(item);
          }));

          if (atts.insertPosition === spans.length) {
            sizeItems.push(newItem);
          }

          var needToShare = 0;
          var canToShare = 0;
          var hasNotAllowed = false;
          $.each(sizeItems, function (index, item) {
            if (item.size < item.minSize) {
              needToShare += item.minSize - item.size;
            } else {
              canToShare += item.size - item.minSize;
            }

            if (!hasNotAllowed && !item.isAllowed) {
              hasNotAllowed = true;
            }
          });

          if (hasNotAllowed) {
            if (needToShare > canToShare) {
              allowSpanWidth = false;
            } else {
              var shared = 0;
              var accepted = 0;
              var loopCounter = 0;

              while (needToShare * 2 !== shared + accepted) {
                for (var i = 0; i < sizeItems.length; i++) {
                  if (sizeItems[i].isAllowed && sizeItems[i].size > sizeItems[i].minSize && shared < needToShare) {
                    sizeItems[i].size--;
                    shared++;
                  } else if (!sizeItems[i].isAllowed && sizeItems[i].size < sizeItems[i].minSize) {
                    sizeItems[i].size++;
                    accepted++;
                  }
                }

                if (++loopCounter >= 50) {
                  allowSpanWidth = false;
                  break;
                }
              }
            }
          }

          var sizes = [];

          for (var i = 0; i < sizeItems.length; i++) {
            sizes.push(sizeItems[i].size);
          }

          return allowSpanWidth ? sizes : [];
        },

        canBeInserted: function canBeInserted(insertMarker, handleType) {
          if (handleType === 'before') {
            return true;
          }

          if ($.inArray(handleType, ['top-in', 'bottom-in', 'insert', 'middle-in']) > -1) {
            return true;
          }

          if ($.inArray(handleType, ['left-in', 'right-in']) > -1) {
            var rowToWidth = parseFloat(insertMarker.css('width'));
            var sizes = this.getSpanSizeRules(2);
            var minSize;
            var allowSpanWidth = true;

            for (var i = 0; i < sizes.length; i++) {
              minSize = this.getMinAllowedSpanSize(rowToWidth, false, sizes[i]);

              if (sizes[i] < minSize) {
                allowSpanWidth = false;
                break;
              }
            }

            if (!allowSpanWidth) {
              return false;
            }
          }

          var rowToSpans = insertMarker.closest('.motopress-row-edge').children('.motopress-clmn');
          var spanCount = rowToSpans.length;
          var spanLimit = parent.CE.Iframe.myThis.gridObj.row.col;

          if (spanCount > spanLimit) {
            return false;
          }

          var newRowSizeAtts = {};

          if ($.inArray(handleType, ['left-out', 'left', 'intermediate']) > -1) {
            newRowSizeAtts.insertPosition = rowToSpans.index(insertMarker);
          }

          if ($.inArray(handleType, ['right-out', 'right']) > -1) {
            newRowSizeAtts.insertPosition = rowToSpans.index(insertMarker) + 1;
          }

          if ($.inArray(handleType, ['left-in', 'right-in']) > -1) {
            newRowSizeAtts.wrapperPosition = handleType == 'left-in' ? rowToSpans.index(insertMarker) : rowToSpans.index(insertMarker) + 1;
          }

          var sizes = this.recalcRowSizes(insertMarker.closest('.motopress-row'), newRowSizeAtts);
          return sizes.length > 0;
        },

        resize: function resize(rowFrom, block, type, newSizes) {
          newSizes = _typeof(newSizes) === 'object' ? newSizes : {}; 

          if (rowFrom !== null && typeof rowFrom !== 'undefined') {
            CE.LayoutManager.rearrangeSpans(rowFrom, newSizes.from);
          } 


          if (type !== 'insert') {
            var rowTo = block.parent();
            CE.LayoutManager.rearrangeSpans(rowTo, newSizes.to);
          }
        },

        getSpanSizeRules: function getSpanSizeRules(spanNumber) {
          return this.spanSizeRules.hasOwnProperty(spanNumber) ? this.spanSizeRules[spanNumber] : [];
        },

        generateSpanSizeRules: function generateSpanSizeRules() {
          var col = parent.CE.Iframe.myThis.gridObj.row.col;
          var rules = [];

          for (var step = 1; step <= col; step++) {
            rules[step] = [];
            var spanNumber = Math.floor(col / step);
            var spanAddition = col % step;

            for (var i = 1; i <= step; i++) {
              rules[step].push(i <= spanAddition ? spanNumber + 1 : spanNumber);
            }
          }

          this.spanSizeRules = rules;
        },

        setCalcWrapperWidth: function setCalcWrapperWidth(obj) {
          if (CE.Grid.myThis.padding) {
            var paddings = CE.Grid.myThis.padding * 2;
            var prefix = '';

            if (parent.MP.Utils.Browser.Mozilla) {
              prefix = '-moz-';
            } else if (parent.MP.Utils.Browser.Chrome) {
              prefix = '-webkit-';
            }

            obj.css('width', prefix + 'calc(100% - ' + paddings + 'px)');
          }
        },
        hideOuterHandles: function hideOuterHandles() {
          $('.motopress-outer-handle').addClass('motopress-outer-handle-hidden');
        },
        showOuterHandles: function showOuterHandles() {
          $('.motopress-outer-handle').removeClass('motopress-outer-handle-hidden');
        },
        onDrag: function onDrag() {
          var handleEl = CE.Scene.myThis.container.find('.motopress-droppable-hover');

          if (handleEl.length) {
            var curHandle = handleEl.attr('data-motopress-position');

            if (curHandle !== null) {
              var helpers = '.motopress-line-helper-' + curHandle + ', .motopress-text-helper-' + curHandle;
              var children = this.helperContainer.children();
              children.not(helpers).hide();
              children.filter(helpers).show();
            }
          } else {
            this.helperContainer.children().hide();
          }
        }
      });
    })(jQuery);

    (function ($) {
      CE.Selectable = can.Construct(
      {
        myThis: null,
        scrollY: 0,
        focusFlag: false,
        blurFlag: false,
        setScrollY: function setScrollY(y) {
          CE.Selectable.scrollY = y;
        },
        focusWithoutScroll: function focusWithoutScroll(el) {
          if (!CE.Selectable.focusFlag) {
            CE.Selectable.focusFlag = true;
            el.focus();
            window.scrollTo(0, CE.Selectable.scrollY);
            var t = setTimeout(function () {
              CE.Selectable.focusFlag = false;
              clearTimeout(t);
            }, 0);
          }

          return el;
        },
        blurWithoutScroll: function blurWithoutScroll(el) {
          if (!CE.Selectable.blurFlag) {
            CE.Selectable.blurFlag = true;
            el.blur();
            window.scrollTo(0, CE.Selectable.scrollY);
            var t = setTimeout(function () {
              CE.Selectable.blurFlag = false;
              clearTimeout(t);
            }, 0);
          }

          return el;
        },

        getFocusAreaBySelected: function getFocusAreaBySelected($selected) {
          var $focusArea;
          var entityType = parent.MP.Utils.getEntityTypeByElement($selected);

          if (entityType.is_row) {
            var $edgeRow = parent.MP.Utils.getEdgeRow($selected);
            $focusArea = $edgeRow.find('> .mpce-row-tools-wrapper > .motopress-focus-area');
          } else if (entityType.is_clmn) {
            var $edgeSpan = parent.MP.Utils.getEdgeSpan($selected);
            $focusArea = $edgeSpan.find('> .motopress-focus-area');
          } else {
            $focusArea = $selected.siblings('.motopress-focus-area');
          }

          return $focusArea;
        }
      },
      {
        selectedClass: 'motopress-selected',
        init: function init() {
          CE.Selectable.myThis = this;
          $(document).mousedown(function () {
            CE.Selectable.setScrollY(window.scrollY);
          });
          $(document).mouseup(function () {
            CE.Selectable.setScrollY(window.scrollY);
          });
          this.makeSelectable();
        },

        isHandleForSelected: function isHandleForSelected(handle) {
          if (handle.closest('.mpce-panel-btn-settings').length) {
            handle = handle.closest('.mpce-panel-btn-settings');
          } 


          if (!handle.hasClass('mpce-panel-btn-settings')) {
            return false;
          }

          var selected = this.getSelected();

          if (!selected.length) {
            return false;
          }

          return selected.is(this.getShortcodeByHandle(handle));
        },
        makeSelectable: function makeSelectable() {
          var $this = this;
          parent.CE.EventDispatcher.Dispatcher.addListener(CE.SceneEvents.SnapshotApplied.NAME, this.proxy(CE.Selectable.myThis.unselect));
        },

        detectSelectHandleType: function detectSelectHandleType(selectHandle) {
          var type = '';

          if (selectHandle.closest('.mpce-object-panel.mpce-row-panel').length) {
            type = 'row';
          } else if (selectHandle.closest('.mpce-object-panel.mpce-clmn-panel').length) {
            type = 'span';
          } else {
            type = 'widget';
          }

          return type;
        },

        selectShortcode: function selectShortcode(shortcode) {
          var handle;

          if (shortcode.hasClass('motopress-row')) {
            var rowEdge = parent.MP.Utils.getEdgeRow(shortcode);
            handle = rowEdge.find('>.mpce-row-tools-wrapper>.mpce-row-panel>.mpce-panel-btn-settings');
          } else if (shortcode.hasClass('motopress-clmn')) {
            var spanEdge = parent.MP.Utils.getEdgeSpan(shortcode);
            handle = spanEdge.find('>.mpce-clmn-tools-wrapper>.mpce-clmn-panel>.mpce-panel-btn-settins');
          } else {
            handle = shortcode.siblings('.mpce-widget-tools-wrapper').find('>.mpce-widget-panel>.mpce-panel-btn-settings');
          }

          this.select(handle);
        },

        select: function select(selectHandle) {
          this.unselect();
          var container = null;
          CE.Selectable.focusWithoutScroll(selectHandle.siblings('.motopress-focus-area'));
          var selectHandleType = this.detectSelectHandleType(selectHandle);

          if (selectHandleType === 'row') {
            selectHandle.closest('.motopress-row').addClass(this.selectedClass);
            container = selectHandle.closest('.motopress-row');
          } else if (selectHandleType === 'span') {
            selectHandle.closest('.motopress-clmn').addClass(this.selectedClass);
            container = selectHandle.closest('.motopress-clmn');
          } else {
            selectHandle.closest('.motopress-clmn').children('.motopress-block-content').addClass(this.selectedClass);
            container = selectHandle.closest('.motopress-clmn');
          }

          CE.Utils.addSceneAction('select');

          if ((container.hasClass('ce_controls') || container.hasClass('ce_inline_editor') || container.hasClass('ce_code_editor') || container.hasClass('motopress-row')) && !parent.CE.Panels.SettingsDialog.myThis.element.dialog('isOpen')) {
            parent.CE.Panels.SettingsDialog.myThis.open(selectHandle);
          }
        },

        selectWidgetOfSpan: function selectWidgetOfSpan(span) {
          var spanEdge = parent.MP.Utils.getEdgeSpan(span);
          this.select(spanEdge.find('>.motopress-block-content>.mpce-tools-wrapper>.mpce-object-panel>.mpce-panel-btn-settings'));
        },

        selectWidget: function selectWidget(widget) {
          var span = widget.closest('.motopress-clmn');
          this.selectWidgetOfSpan(span);
        },

        showWidgetMainEditTool: function showWidgetMainEditTool(widget) {
          var widgetCtrl = widget.control(CE.Controls);

          if (widgetCtrl instanceof CE.InlineEditor || widgetCtrl instanceof CE.CodeEditor) {
            widgetCtrl.open();
          } else {
            this.selectWidget(widget);
          }
        },
        unselect: function unselect() {
          parent.CE.Panels.SettingsDialog.myThis.close(); 

          var selected = $('.motopress-content-wrapper .' + this.selectedClass);

          if (selected.length) {
            selected.each(function () {
              $(this).removeClass(CE.Selectable.myThis.selectedClass);
            });
            CE.Selectable.blurWithoutScroll(CE.Selectable.getFocusAreaBySelected(selected));
          }

          CE.Utils.removeSceneAction('select');
        },

        getSelected: function getSelected() {
          var selectedEl = $(parent.MP.Utils.convertClassesToSelector(this.selectedClass));
          var entityType = parent.MP.Utils.getEntityTypeByElement(selectedEl);

          if (entityType.is_widget) {
            selectedEl = selectedEl.children('[data-motopress-shortcode]');
          }

          return selectedEl;
        },

        getShortcodeByHandle: function getShortcodeByHandle(handle) {
          var shortcode;
          var handleType = this.detectSelectHandleType(handle);

          if (handleType == 'row') {
            shortcode = handle.closest('.motopress-row');
          } else if (handleType == 'span') {
            shortcode = handle.closest('.motopress-clmn');
          } else {
            shortcode = handle.closest('.motopress-clmn').find('.motopress-block-content').first().children('[data-motopress-shortcode]');
          }

          return shortcode;
        }
      });
    })(jQuery);

    (function ($) {
      can.Control.extend('CE.Panels.AddSectionPanel', {}, {
        layoutDialog: null,
        templateDialog: null,
        permanent: false,

        init: function init(el, args) {
          if (args.layoutDialog) {
            el.append($('<button />', {
              "class": 'mpce-add-section-btn',
              text: 'Add Section'
            }));
            this.layoutDialog = args.layoutDialog;
          }

          if (args.templateDialog) {
            el.append($('<button />', {
              "class": 'mpce-add-template-btn',
              text: 'Add Template'
            }));
            this.templateDialog = args.templateDialog;
          }

          if (args.permanent) {
            this.permanent = true;
            this.element.addClass('mpce-permanent-add-section-panel');
          } else {
            el.append($('<span />', {
              "class": 'mpce-close fa fa-close'
            }));
          }
        },
        restoreClone: function restoreClone($elementClone) {
          this.element = $elementClone;
        },
        hide: function hide() {
          this.element.hide();
        },
        show: function show() {
          this.element.show();
        },
        '.mpce-add-section-btn click': function mpceAddSectionBtnClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          this.layoutDialog.open({
            positionTarget: this.element,
            insertMarker: this.element
          }).then(this.proxy(function () {
            if (!this.permanent) {
              this.element.remove();
            }
          }));
        },
        '.mpce-add-template-btn click': function mpceAddTemplateBtnClick(el, e) {
          e.preventDefault();
          e.stopPropagation();
          this.templateDialog.open({
            positionTarget: this.element,
            insertMarker: this.element
          }).then(this.proxy(function () {
            if (!this.permanent) {
              this.element.remove();
            }
          }));
        },
        '.mpce-close click': function mpceCloseClick() {
          this.element.remove();
        }
      });
    })(jQuery);

    (function ($) {
      if ($.hasOwnProperty('stellar')) {
        $.stellar('refresh');
      }

      if ($.hasOwnProperty('fn') && $.fn.hasOwnProperty('button') && $.fn.button.hasOwnProperty('noConflict')) {
        $.fn.btn = $.fn.button.noConflict();
      }

      new CE.Utils();
      new CE.Scene();
      parent.MP.Editor.myThis.load();
    })(jQuery);

    jQuery(document).ready(function ($) {
      parent.CE.EventDispatcher.Dispatcher.addListener(CE.SceneEvents.SnapshotTakenBefore.NAME, function (event) {
        var $tabs = $('.motopress-tabs-obj');
        $tabs.each(function () {
          try {
            if ($(this).data('uiTabs')) {
              var options = $(this).tabs('option');
              $(this).data('mpceUiOptions', options);
              $(this).tabs('destroy');
            }
          } catch (e) {
            handleBeforeSnapshotError(e);
          }
        }); 

        var $flexsliders = $('.motopress-flexslider');
        $flexsliders.each(function () {
          try {
            var flexsliderInst = $(this).data('flexslider');

            if (flexsliderInst) {
              var options = $.extend({}, flexsliderInst.vars, true);
              $(this).data('mpceFlexSliderOptions', options);
              $(this).flexslider('destroy');
            }
          } catch (e) {
            handleBeforeSnapshotError(e);
          }
        }); 
      });
      parent.CE.EventDispatcher.Dispatcher.addListener(parent.CE.LocalRevision.Events.RevisionPicked.NAME, function (event) {
        var $tabs = $('.motopress-tabs-obj');
        $tabs.each(function () {
          try {
            var options = $(this).data('mpceUiOptions');

            if (can.isPlainObject(options)) {
              $(this).tabs(options);
            }
          } catch (e) {
            handleRevisionPickedError(e);
          }
        }); 

        var $flexsliders = $('.motopress-flexslider');
        $flexsliders.each(function () {
          try {
            var options = $(this).data('mpceFlexSliderOptions');

            if (can.isPlainObject(options)) {
              $(this).flexslider(options);
            }
          } catch (e) {
            handleRevisionPickedError(e);
          }
        }); 
      });

      function handleBeforeSnapshotError(e) {}

      function handleRevisionPickedError(e) {}
    });
  } catch (e) {
    parent.MP.Error.log(e, true);
  }
}

;