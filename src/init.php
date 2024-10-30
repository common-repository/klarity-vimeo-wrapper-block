<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package Klarity
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function klarity_vimeo_wrapper_cgb_block_assets() { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'klarity_vimeo_wrapper-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', __DIR__), // Block style CSS.
		array( 'wp-editor' ), // Dependency to include the CSS after it.
    	filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'klarity_vimeo_wrapper_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function klarity_vimeo_wrapper_cgb_editor_assets() { // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'klarity_vimeo_wrapper-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', __DIR__), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
    	filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'klarity_vimeo_wrapper-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', __DIR__), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
    	filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'klarity_vimeo_wrapper_cgb_editor_assets' );
register_block_type('klarity/klarity-vimeo-wrapper', [
    'render_callback' => 'render_video_thumbnail',
    'attributes' => [
        'link' => [
            'type' => 'string',
            'default' => '',
        ],
        'videoThumbnail' => [
            'type' => 'string',
            'default' => '',
        ],
        'isThumbnailFullWidth' => [
          'type' => 'boolean',
          'default' => false,
      ]
    ]
]);

function render_video_thumbnail( $attributes ) {
    $link = $attributes['link'] ?? '';
    $videoThumbnail = $attributes['videoThumbnail'] ?? '';
    $fullWidthClass = $attributes['isThumbnailFullWidth'] ? 'full-width' : '';

    $videoDuration = $attributes['videoDuration'] ?? null;
    $videoContent = is_null($videoDuration)
      ? ''
      : "<div class='video-timestamp'>$videoDuration</div>";

    wp_enqueue_script(
        'header_video-handler-js',
        plugins_url('/src/block/show-video.js', __DIR__),
        [],
        filemtime( plugin_dir_path( __DIR__ ) . 'src/block/show-video.js' ) // Version: File modification time.
    );
    return "
      <div class='video-container $fullWidthClass' onclick='showVimeoWrapperVideo(this, \"$link\")'>
        <div class='thumbnail-container $fullWidthClass' style='background-image:url(\"$videoThumbnail\")'>
          <img class='play-icon' alt='Play' src='".plugin_dir_url( __DIR__ )."images/play_button.svg' />
          $videoContent
        </div>
	    </div>";
}
