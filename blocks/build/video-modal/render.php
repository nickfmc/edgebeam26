<?php
/**
 * Server-side rendering of the video modal block
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the post content with video modal block added.
 */

// Get block attributes
$use_testimonial_fields = $attributes['useTestimonialFields'] ?? false;
$cover_image = $attributes['coverImage'] ?? null;
$title = $attributes['title'] ?? 'Watch Video';
$video_type = $attributes['videoType'] ?? 'youtube';
$youtube_video_id = $attributes['youtubeVideoId'] ?? '';
$local_video = $attributes['localVideo'] ?? null;
$transcript = $attributes['transcript'] ?? '';
$alignment = $attributes['alignment'] ?? 'center';
$use_alternate_style = $attributes['useAlternateStyle'] ?? false;

// Override with testimonial fields if enabled
if ( $use_testimonial_fields ) {
    // Get featured image as cover
    $featured_image_id = get_post_thumbnail_id();
    if ( $featured_image_id ) {
        $cover_image = array(
            'id' => $featured_image_id,
            'url' => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
            'alt' => get_post_meta( $featured_image_id, '_wp_attachment_image_alt', true )
        );
    }
    
    // Get video URL from ACF field
    $video_url = get_field( 'video_url' );
    if ( $video_url ) {
        // Extract YouTube ID from URL
        preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches );
        if ( isset( $matches[1] ) ) {
            $youtube_video_id = $matches[1];
        }
    }
    
    // Use post title as video title
    $title = get_the_title();
}

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes( array(
    'class' => sprintf( 'c-video-modal c-video-modal--%s%s', esc_attr( $alignment ), $use_alternate_style ? ' c-video-modal--alternate' : '' ),
) );

// Generate unique ID for this modal
$modal_id = 'video-modal-' . uniqid();

// Output the block
?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="c-video-modal__container">
        <?php 
        // Check if we have the necessary data to display video
        $has_video = false;
        if ( ! $use_testimonial_fields ) {
            $has_video = ( $video_type === 'youtube' && ! empty( $youtube_video_id ) ) || 
                        ( $video_type === 'local' && ! empty( $local_video ) );
        } else {
            $has_video = ! empty( $youtube_video_id );
        }
        
        if ( ! empty( $cover_image ) && $has_video ) : 
        ?>
            <div 
                class="c-video-modal__cover" 
                data-video-type="<?php echo esc_attr( $video_type ); ?>"
                data-video-id="<?php echo esc_attr( $youtube_video_id ); ?>"
                data-local-video="<?php echo esc_attr( ! empty( $local_video ) ? wp_json_encode( $local_video ) : '' ); ?>"
                data-transcript="<?php echo esc_attr( $transcript ); ?>"
                data-modal-id="<?php echo esc_attr( $modal_id ); ?>"
                data-use-testimonial-fields="<?php echo esc_attr( $use_testimonial_fields ? 'true' : 'false' ); ?>"
                data-use-alternate-style="<?php echo esc_attr( $use_alternate_style ? 'true' : 'false' ); ?>"
                style="cursor: pointer;"
            >
                <img 
                    src="<?php echo esc_url( $cover_image['url'] ); ?>" 
                    alt="<?php echo esc_attr( $cover_image['alt'] ?? $title ); ?>" 
                    class="c-video-modal__image"
                />
                <div class="c-video-modal__overlay<?php echo $use_alternate_style ? ' c-video-modal__overlay--alternate' : ''; ?>">
                    <div class="c-video-modal__overlay-content">
                        <div class="c-video-modal__play-button">
                            <?php if ( $use_alternate_style ) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                                    <rect width="100" height="100" rx="50" fill="#BCBACD"/>
                                    <path d="M46.2184 37.3223C43.4593 35.6991 39.9807 37.6885 39.9807 40.8897V59.1108C39.9807 62.3119 43.459 64.3013 46.2181 62.6784L61.7054 53.5687C64.426 51.9685 64.4261 48.0342 61.7057 46.4338L46.2184 37.3223Z" fill="white"/>
                                </svg>
                            <?php else : ?>
                                <svg width="45" height="45" viewBox="0 0 45 45" fill="none">
           ! empty( $cover_image ) && $has_video
                                    <path d="M30.5 21.634C31.1667 22.0189 31.1667 22.9811 30.5 23.366L18.5 30.2942C17.8333 30.6791 17 30.198 17 29.4282L17 15.5718C17 14.802 17.8333 14.3209 18.5 14.7058L30.5 21.634Z" fill="white"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Structure moved outside block wrapper for full page overlay -->
<?php if ( ( ! $use_testimonial_fields && ! empty( $cover_image ) && ! empty( $youtube_video_id ) ) || ( $use_testimonial_fields && ! empty( $cover_image ) && ! empty( $youtube_video_id ) ) ) : ?>
<div 
    class="c-popup-overlay c-video-modal-overlay" 
    id="<?php echo esc_attr( $modal_id ); ?>"
    role="dialog"
    aria-modal="true"
    aria-label="<?php echo esc_attr( $title ); ?>"
    aria-hidden="true"
>
    <div class="c-popup-form c-video-modal__popup">
        <button 
            class="c-popup-close" 
            type="button" 
            aria-label="<?php echo esc_attr__( 'Close Video Modal', 'gdt-theme' ); ?>"
        ></button>
        
        <!-- Video Section (Full Width) -->
        <div class="c-video-modal__video-section">
            <div class="c-video-modal__video-container">
                <div 
                    class="c-video-modal__video-wrapper" 
                    id="<?php echo esc_attr( $modal_id ); ?>-video"
                    role="img"
                    aria-label="<?php echo esc_attr( sprintf( __( 'Video: %s', 'gdt-theme' ), $title ) ); ?>"
                >
                    <!-- YouTube video will be loaded here by JavaScript -->
                </div>
            </div>
        </div>
        
        <!-- Transcript Section (Below Video) -->
        <?php if ( ! $use_testimonial_fields && ! empty( $transcript ) ) : ?>
            <div class="c-video-modal__transcript-section">
                <div class="c-video-modal__transcript-accordion">
                    <button 
                        class="c-video-modal__transcript-toggle" 
                        type="button"
                        aria-expanded="false"
                        aria-controls="<?php echo esc_attr( $modal_id ); ?>-transcript"
                        id="<?php echo esc_attr( $modal_id ); ?>-transcript-button"
                    >
                        <span class="c-video-modal__transcript-title"><?php echo esc_html__( 'Transcript', 'gdt-theme' ); ?></span>
                        <span class="c-video-modal__transcript-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>
                    
                    <div 
                        class="c-video-modal__transcript-content" 
                        id="<?php echo esc_attr( $modal_id ); ?>-transcript"
                        aria-labelledby="<?php echo esc_attr( $modal_id ); ?>-transcript-button"
                        role="region"
                        hidden
                    >
                        <div class="c-video-modal__transcript-text">
                            <?php echo wp_kses_post( wpautop( $transcript ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
