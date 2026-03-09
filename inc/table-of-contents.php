<?php
/**
 * Table of Contents Module
 * Automatically generates TOC from H2 and H3 headings with proper schema markup
 * 
 * @package 2keller
 */

class TableOfContents {
    
    private $toc_items = [];
    private $content = '';
    private $include_h3 = true;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Add custom meta box for TOC settings
        add_action('add_meta_boxes', array($this, 'add_toc_meta_box'));
        add_action('save_post', array($this, 'save_toc_meta'));
    }
    
    /**
     * Add meta box for TOC settings
     */
    public function add_toc_meta_box() {
        add_meta_box(
            'toc_settings',
            'Table of Contents Settings',
            array($this, 'toc_meta_box_callback'),
            'post',
            'side',
            'default'
        );
    }
    
    /**
     * Meta box callback
     */
    public function toc_meta_box_callback($post) {
        wp_nonce_field('toc_meta_box', 'toc_meta_box_nonce');
        
        $hide_h3 = get_post_meta($post->ID, '_toc_hide_h3', true);
        
        echo '<label for="toc_hide_h3">';
        echo '<input type="checkbox" id="toc_hide_h3" name="toc_hide_h3" value="1" ' . checked($hide_h3, '1', false) . ' />';
        echo ' Hide H3 headings from Table of Contents';
        echo '</label>';
    }
    
    /**
     * Save meta box data
     */
    public function save_toc_meta($post_id) {
        if (!isset($_POST['toc_meta_box_nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['toc_meta_box_nonce'], 'toc_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $hide_h3 = isset($_POST['toc_hide_h3']) ? '1' : '';
        update_post_meta($post_id, '_toc_hide_h3', $hide_h3);
    }
    
    /**
     * Generate TOC from content
     */
    public function generate_toc($content, $post_id = null) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        $this->content = $content;
        $this->include_h3 = get_post_meta($post_id, '_toc_hide_h3', true) !== '1';
        $this->toc_items = [];
        
        // Parse headings and add anchors
        $content = $this->parse_headings($content);
        
        return $content;
    }
    
    /**
     * Parse headings and create anchor links
     */
    private function parse_headings($content) {
        $pattern = '/<(h[23])([^>]*)>(.*?)<\/h[23]>/i';
        
        $content = preg_replace_callback($pattern, array($this, 'process_heading'), $content);
        
        return $content;
    }
    
    /**
     * Process individual heading
     */
    private function process_heading($matches) {
        $tag = strtolower($matches[1]);
        $attributes = $matches[2];
        $heading_text = strip_tags($matches[3]);
        $level = ($tag === 'h2') ? 2 : 3;
        
        // Skip H3 if disabled
        if ($level === 3 && !$this->include_h3) {
            return $matches[0]; // Return original heading without anchor
        }
        
        // Generate anchor ID
        $anchor_id = $this->generate_anchor_id($heading_text);
        
        // Check if ID already exists in attributes
        if (!preg_match('/id\s*=/', $attributes)) {
            $attributes .= ' id="' . $anchor_id . '"';
        }
        
        // Add to TOC items
        $this->toc_items[] = array(
            'level' => $level,
            'anchor' => $anchor_id,
            'text' => $heading_text,
            'tag' => $tag
        );
        
        // Return heading with anchor
        return '<' . $tag . $attributes . '>' . $matches[3] . '</' . $tag . '>';
    }
    
    /**
     * Generate anchor ID from heading text
     */
    private function generate_anchor_id($text) {
        // Remove HTML entities and convert to lowercase
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = strtolower($text);
        
        // Replace non-alphanumeric characters with hyphens
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        
        // Remove leading/trailing hyphens
        $text = trim($text, '-');
        
        // Ensure uniqueness by adding counter if needed
        $original_text = $text;
        $counter = 1;
        $existing_anchors = wp_list_pluck($this->toc_items, 'anchor');
        
        while (in_array($text, $existing_anchors)) {
            $text = $original_text . '-' . $counter;
            $counter++;
        }
        
        return $text;
    }
    
    /**
     * Get TOC HTML
     */
    public function get_toc_html() {
        if (empty($this->toc_items)) {
            return '';
        }
        
        $schema_items = array();
        $toc_html = '<div class="c-table-of-contents" itemscope itemtype="https://schema.org/Table">';
        $toc_html .= '<div class="c-toc-header">';
        $toc_html .= '<h4 class="c-toc-title" itemprop="name">Table of Contents</h4>';
        $toc_html .= '<button class="c-toc-toggle" aria-expanded="true" aria-controls="toc-list">';
        $toc_html .= '<svg class="c-toc-chevron" width="13" height="8" viewBox="0 0 13 8" fill="none">';
        $toc_html .= '<path d="M1 1L6.5 6.5L12 1" stroke="#1c5195" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
        $toc_html .= '</svg>';
        $toc_html .= '</button>';
        $toc_html .= '</div>';
        
        $toc_html .= '<nav class="c-toc-nav" id="toc-list" role="navigation" aria-label="Table of Contents">';
        $toc_html .= '<ol class="c-toc-list" itemprop="hasPart">';
        
        foreach ($this->toc_items as $index => $item) {
            $schema_items[] = array(
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['text'],
                'url' => '#' . $item['anchor']
            );
            
            $toc_html .= '<li class="c-toc-item c-toc-item--level-' . $item['level'] . '" itemscope itemtype="https://schema.org/ListItem">';
            $toc_html .= '<meta itemprop="position" content="' . ($index + 1) . '">';
            $toc_html .= '<a href="#' . $item['anchor'] . '" class="c-toc-link" itemprop="url">';
            $toc_html .= '<span itemprop="name">' . esc_html($item['text']) . '</span>';
            $toc_html .= '</a>';
            $toc_html .= '</li>';
        }
        
        $toc_html .= '</ol>';
        $toc_html .= '</nav>';
        $toc_html .= '</div>';
        
        // Add structured data
        $structured_data = array(
            '@context' => 'https://schema.org',
            '@type' => 'Table',
            'name' => 'Table of Contents',
            'mainEntity' => array(
                '@type' => 'ItemList',
                'itemListElement' => $schema_items
            )
        );
        
        $toc_html .= '<script type="application/ld+json">' . wp_json_encode($structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        
        return $toc_html;
    }
    
    /**
     * Check if content has headings
     */
    public function has_headings($content = null) {
        if ($content === null) {
            $content = $this->content;
        }
        
        $pattern = '/<h[23][^>]*>.*?<\/h[23]>/i';
        return preg_match($pattern, $content);
    }
}

// Initialize the TOC class
global $table_of_contents;
$table_of_contents = new TableOfContents();

/**
 * Helper function to generate TOC
 */
function get_table_of_contents($content = null, $post_id = null) {
    global $table_of_contents;
    
    if ($content === null) {
        $content = get_the_content();
    }
    
    // Process content to add anchors
    $processed_content = $table_of_contents->generate_toc($content, $post_id);
    
    return array(
        'content' => $processed_content,
        'toc_html' => $table_of_contents->get_toc_html(),
        'has_toc' => $table_of_contents->has_headings($content)
    );
}

/**
 * Add smooth scrolling script
 */
function toc_smooth_scroll_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // TOC toggle functionality
        const tocToggle = document.querySelector('.c-toc-toggle');
        const tocList = document.querySelector('.c-toc-nav');
        const tocChevron = document.querySelector('.c-toc-chevron');
        
        if (tocToggle && tocList) {
            tocToggle.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                tocList.style.display = isExpanded ? 'none' : 'block';
                tocChevron.style.transform = isExpanded ? 'rotate(-90deg)' : 'rotate(0deg)';
            });
        }
        
        // Smooth scrolling for TOC links
        const tocLinks = document.querySelectorAll('.c-toc-link');
        tocLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const headerOffset = 100; // Adjust for sticky header
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Update active link
                    tocLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        // Highlight current section on scroll
        function updateActiveLink() {
            const scrollPosition = window.scrollY + 150;
            const headings = document.querySelectorAll('h2[id], h3[id]');
            let currentId = '';
            
            headings.forEach(function(heading) {
                if (heading.offsetTop <= scrollPosition) {
                    currentId = heading.id;
                }
            });
            
            tocLinks.forEach(function(link) {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + currentId) {
                    link.classList.add('active');
                }
            });
        }
        
        // Throttled scroll listener
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    updateActiveLink();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // Initialize active link
        updateActiveLink();
    });
    </script>
    <?php
}
add_action('wp_footer', 'toc_smooth_scroll_script');