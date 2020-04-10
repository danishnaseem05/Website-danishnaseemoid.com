<?php

class MPCEGutenbergSupport
{
    protected $isEditing = false;

    public function __construct()
    {
        add_action('init', array($this, 'addActions'));
    }

    public function addActions()
    {
        if (function_exists('register_block_type')) {
            add_action('rest_api_init', array($this, 'registerRestField'));
            add_action('enqueue_block_editor_assets', array($this, 'enqueueAssets'));
            add_action('admin_footer', array($this, 'printTemplates'));
        }
    }

    public function registerRestField()
    {
        register_rest_field(
            get_post_types(),
            'mpce_gutenberg_mode',
            array(
                'update_callback' => function ($requestValue, $post) {
                    $access = MPCEAccess::getInstance();

                    if ($access->hasAccess($post->ID)) {
                        $contentManager = MPCEContentManager::getInstance();
                        $contentManager->setPostEditorStatus($post->ID, $requestValue);

                        return true;
                    } else {
                        return false;
                    }
                }
            )
        );
    }

    public function enqueueAssets()
    {
        $access = MPCEAccess::getInstance();
        $this->isEditing = $access->hasAccess();

        if ($this->isEditing) {
            $suffix = mpceSettings()['script_suffix'];

            $pluginUrl = mpceSettings()['plugin_dir_url'];
            $pluginVersion = mpceSettings()['plugin_version'];

            wp_enqueue_script('mpce-gutenberg', $pluginUrl . 'mp/ce/gutenberg' . $suffix . '.js', array('jquery'), $pluginVersion, true);
            wp_enqueue_style('mpce-gutenberg', $pluginUrl . 'mp/ce/css/gutenberg.css', array(), $pluginVersion);
        }
    }

    public function printTemplates()
    {
        if (!$this->isEditing) {
            return;
        }

        ?>
        <script id="mpce-switch-mode-html" type="text/html">
            <div id="mpce-switch-mode">
                <button id="mpce-switch-mode-button" type="button" class="button button-primary button-large">
                    <span class="mpce-switch-mode-on"><?php _e('&#8592; Back to WordPress Editor', 'motopress-content-editor-lite'); ?></span>
                    <span class="mpce-switch-mode-off"><?php _e('Edit with Content Editor', 'motopress-content-editor-lite'); ?></span>
                </button>
            </div>
        </script>

        <script id="mpce-editor-placeholder-html" type="text/html">
            <div id="mpce-editor-placeholder">
                <a id="mpce-go-to-editor-link" href="#">
                    <span id="mpce-go-to-editor-button" class="button button-primary button-hero">
                        <?php _e('Edit with Content Editor', 'motopress-content-editor-lite'); ?>
                    </span>
                </a>
            </div>
        </script>
        <?php
    }
}
