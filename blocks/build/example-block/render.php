<?php
/**
 * Server-side rendering of the example block
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the post content with latest posts added.
 */

// Get block attributes
$title = $attributes['title'] ?? 'Example Block Title';
$content = $attributes['content'] ?? 'This is example content for the block.';
$alignment = $attributes['alignment'] ?? 'left';

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes( array(
    'class' => sprintf( 'c-example-block c-example-block--%s', esc_attr( $alignment ) ),
) );

// Output the block
?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="c-example-block__container">
        <?php if ( ! empty( $title ) ) : ?>
            <h2 class="c-example-block__title"><?php echo wp_kses_post( $title ); ?></h2>
        <?php endif; ?>
        
        <?php if ( ! empty( $content ) ) : ?>
            <div class="c-example-block__content"><?php echo wp_kses_post( $content ); ?></div>
        <?php endif; ?>
    </div>
</div>
