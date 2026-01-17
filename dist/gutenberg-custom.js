/**
 * Edits and additions to the default Gutenberg setup
 */

// // ++ create custom button style names + remove included buttons styles
wp.blocks.registerBlockStyle( 'core/button', {
  name: 'standard',
  label: 'Standard Button'
} );
// wp.blocks.registerBlockStyle( 'core/button', {
//   name: 'border',
//   label: 'Border Button'
// } );

wp.blocks.registerBlockStyle(
  'core/paragraph',
  {
    name: 'secondaryfont',
    label: 'Secondary Font',
    
  }
);


wp.blocks.registerBlockStyle(
  'core/columns',
  {
    name: 'no-gutter',
    label: 'No Gutters'
  }
);


// some blocks are required by WP when doing other things, so keep an eye on the consol log, currently cover block causes errors if we remove it.
wp.domReady( () => {
  wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
  wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
  wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
  // Unregister unwanted blocks here
  wp.blocks.unregisterBlockType( 'core/preformatted' );
  wp.blocks.unregisterBlockType( 'core/verse' );
  wp.blocks.unregisterBlockType( 'core/pullquote' );
  wp.blocks.unregisterBlockType( 'core/media-text' );
  // wp.blocks.unregisterBlockType( 'core/cover' );
  wp.blocks.unregisterBlockType( 'core/more' );
  wp.blocks.unregisterBlockType( 'core/buttons' );
  wp.blocks.unregisterBlockType( 'core/site-logo' );
  wp.blocks.unregisterBlockType( 'core/site-tagline' );
  wp.blocks.unregisterBlockType( 'core/site-title' );
  wp.blocks.unregisterBlockType( 'core/calendar' );
  wp.blocks.unregisterBlockType( 'core/latest-comments' );
  wp.blocks.unregisterBlockType( 'core/tag-cloud' );
  wp.blocks.unregisterBlockType( 'core/search' );
  wp.blocks.unregisterBlockType( 'core/rss' );
  wp.blocks.unregisterBlockType( 'core/social-links' );
  wp.blocks.unregisterBlockType( 'core/latest-posts' );
  wp.blocks.unregisterBlockType( 'core/categories' );
  wp.blocks.unregisterBlockType( 'core/archives' );
  wp.blocks.unregisterBlockType( 'core/query-title' );
  // wp.blocks.unregisterBlockType( 'core/post-terms' );
  // wp.blocks.unregisterBlockType( 'core/coverImage' );

  // toolset customization
  // wp.blocks.unregisterBlockType( 'toolset-blocks/button' );

  wp.blocks.registerBlockStyle( 'generateblocks/text', [
  {
    name: 'h1-style',
    label: 'H1 Style',
  }, 
  {
    name: 'h2-style',
    label: 'H2 Style',
  },
  {
    name: 'h3-style',
    label: 'H3 Style',
  },
  {
    name: 'h4-style',
    label: 'H4 Style',
  },
  {
    name: 'h5-style',
    label: 'H5 Style',
  },
  {
    name: 'h6-style',
    label: 'H6 Style',
  },
  {
    name: 'eyebrow',
    label: 'Eyebrow Style',
  }

  ]);

  

  // Register styles for WordPress default heading block
wp.blocks.registerBlockStyle( 'core/heading', [
  {
    name: 'h1-style',
    label: 'H1 Style',
  },
  {
    name: 'h2-style',
    label: 'H2 Style',
  },
  {
    name: 'h3-style',
    label: 'H3 Style',
  },
  {
    name: 'h4-style',
    label: 'H4 Style',
  },
  {
    name: 'h5-style',
    label: 'H5 Style',
  },
  {
    name: 'h6-style',
    label: 'H6 Style',
  },
  {
    name: 'eyebrow',
    label: 'Eyebrow Style',
  }
]);

  // Register Arrowed list style for core/list block
  wp.blocks.registerBlockStyle( 'core/list', {
    name: 'arrowed-list',
    label: 'Arrowed List',
    isDefault: false
  });

   // Register checked list style for core/list block
  wp.blocks.registerBlockStyle( 'core/list', {
    name: 'check-list',
    label: 'Check List',
    isDefault: false
  });

    wp.blocks.registerBlockStyle( 'core/list', {
    name: 'check-list-nolink',
    label: 'Check List No Link',
    isDefault: false
  });

} );
