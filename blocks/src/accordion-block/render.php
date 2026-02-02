<?php
/**
 * Server-side rendering of the accordion block
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the accordion block HTML.
 */

// Get block attributes
$title = $attributes['title'] ?? '';
$accordion_items = $attributes['accordionItems'] ?? [];
$items_to_show = $attributes['itemsToShow'] ?? 6;
$show_load_more = $attributes['showLoadMore'] ?? true;
$load_more_text = $attributes['loadMoreText'] ?? __('Load More', 'gdt-theme');
$columns = $attributes['columns'] ?? 2;
$style = $attributes['style'] ?? 'default';
$enable_faq_schema = $attributes['enableFaqSchema'] ?? false;

// Generate unique ID for this block instance
$block_id = wp_unique_id('accordion-block-');

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes( array(
    'class' => sprintf( 'c-accordion-block c-accordion-block--columns-%d c-accordion-block--%s', esc_attr( $columns ), esc_attr( $style ) ),
    'id' => $block_id,
) );

// If no accordion items, return early
if ( empty( $accordion_items ) ) {
    return '<div ' . $wrapper_attributes . '><p>' . __('No accordion items to display.', 'gdt-theme') . '</p></div>';
}

// Generate FAQ Schema if enabled
$faq_schema_output = '';
if ( $enable_faq_schema && ! empty( $accordion_items ) ) {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array()
    );
    
    foreach ( $accordion_items as $item ) {
        $question_title = wp_strip_all_tags( $item['title'] ?? '' );
        $answer_content = wp_strip_all_tags( $item['content'] ?? '' );
        
        if ( ! empty( $question_title ) && ! empty( $answer_content ) ) {
            $schema['mainEntity'][] = array(
                '@type' => 'Question',
                'name' => $question_title,
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => $answer_content
                )
            );
        }
    }
    
    if ( ! empty( $schema['mainEntity'] ) ) {
        $faq_schema_output = '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>';
    }
}

// Output the block
?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="c-accordion-block__container">
        <?php if ( ! empty( $title ) ) : ?>
            <h2 class="c-accordion-block__title"><?php echo wp_kses_post( $title ); ?></h2>
        <?php endif; ?>
        
        <div 
            class="c-accordion-block__grid c-accordion-block__grid--columns-<?php echo esc_attr( $columns ); ?>"
            data-items-to-show="<?php echo esc_attr( $items_to_show ); ?>"
            data-show-load-more="<?php echo esc_attr( $show_load_more ? 'true' : 'false' ); ?>"
            data-load-more-text="<?php echo esc_attr( $load_more_text ); ?>"
            data-total-items="<?php echo esc_attr( count( $accordion_items ) ); ?>"
            data-columns="<?php echo esc_attr( $columns ); ?>"
        >
            <?php foreach ( $accordion_items as $index => $item ) : 
                $item_title = $item['title'] ?? '';
                $item_content = $item['content'] ?? '';
                $item_id = $block_id . '-item-' . $index;
                $is_initially_hidden = ($items_to_show > 0) && ($index >= $items_to_show);
            ?>
                <div 
                    class="c-accordion-block__item<?php echo $is_initially_hidden ? ' c-accordion-block__item--hidden' : ''; ?>"
                    data-index="<?php echo esc_attr( $index ); ?>"
                >
                    <button 
                        class="c-accordion-block__toggle"
                        type="button"
                        aria-expanded="false"
                        aria-controls="<?php echo esc_attr( $item_id ); ?>-content"
                        id="<?php echo esc_attr( $item_id ); ?>-toggle"
                    >
                        <span class="c-accordion-block__toggle-title">
                            <?php echo esc_html( $item_title ); ?>
                        </span>
                        <span class="c-accordion-block__toggle-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M12 8V16M8 12H16M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#0047BB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                        </span>
                    </button>
                    <div 
                        class="c-accordion-block__content"
                        id="<?php echo esc_attr( $item_id ); ?>-content"
                        aria-labelledby="<?php echo esc_attr( $item_id ); ?>-toggle"
                        role="region"
                        hidden
                    >
                        <div class="c-accordion-block__content-inner">
                            <?php echo wp_kses_post( $item_content ); ?>
                        </div>
                    </div>
                    <div class="c-accordion-block__divider"></div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ( $show_load_more && count( $accordion_items ) > $items_to_show ) : ?>
            <div class="c-accordion-block__load-more">
                <button 
                    class="c-accordion-block__load-more-btn" 
                    type="button"
                    aria-label="<?php echo esc_attr( sprintf( __('%s. Currently showing %d of %d items.', 'gdt-theme'), $load_more_text, $items_to_show, count( $accordion_items ) ) ); ?>"
                >
                    <span><?php echo esc_html( $load_more_text ); ?></span>
                    <svg class="c-accordion-block__load-more-icon" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M1 11L6 6L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Output FAQ Schema if enabled
if ( ! empty( $faq_schema_output ) ) {
    echo $faq_schema_output;
}
?>
