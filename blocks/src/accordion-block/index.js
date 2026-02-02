import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { 
    useBlockProps, 
    RichText, 
    InspectorControls,
    URLInput
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    RangeControl, 
    ToggleControl,
    TextControl,
    TextareaControl,
    SelectControl,
    Button,
    Card,
    CardBody,
    CardHeader,
    Icon,
    Popover
} from '@wordpress/components';
import { useState, useRef } from '@wordpress/element';
import blockData from './block.json';

registerBlockType( blockData.name, {
    ...blockData,
    edit: function( { attributes, setAttributes } ) {
        const { 
            title, 
            accordionItems, 
            itemsToShow, 
            showLoadMore, 
            loadMoreText, 
            columns,
            style,
            enableFaqSchema 
        } = attributes;
        
        const [editingIndex, setEditingIndex] = useState(null);
        const [linkPopoverIndex, setLinkPopoverIndex] = useState(null);
        const [linkUrl, setLinkUrl] = useState('');
        const [linkText, setLinkText] = useState('');
        const [linkNewTab, setLinkNewTab] = useState(false);
        const [cursorPosition, setCursorPosition] = useState({ start: 0, end: 0 });
        const textareaRefs = useRef({});
        
        const blockProps = useBlockProps({
            className: `c-accordion-block c-accordion-block--columns-${columns} c-accordion-block--${style}`,
        });

        const addAccordionItem = () => {
            const newItems = [...accordionItems, {
                title: __('New Accordion Item', 'gdt-theme'),
                content: __('Enter content for this accordion item...', 'gdt-theme'),
                isOpen: false
            }];
            setAttributes({ accordionItems: newItems });
        };

        const updateAccordionItem = (index, field, value) => {
            const newItems = [...accordionItems];
            newItems[index] = { ...newItems[index], [field]: value };
            setAttributes({ accordionItems: newItems });
        };

        const removeAccordionItem = (index) => {
            const newItems = accordionItems.filter((_, i) => i !== index);
            setAttributes({ accordionItems: newItems });
            setEditingIndex(null);
        };

        const toggleAccordionItem = (index) => {
            const newItems = [...accordionItems];
            newItems[index] = { ...newItems[index], isOpen: !newItems[index].isOpen };
            setAttributes({ accordionItems: newItems });
        };

        const moveItemUp = (index) => {
            if (index === 0) return;
            const newItems = [...accordionItems];
            [newItems[index - 1], newItems[index]] = [newItems[index], newItems[index - 1]];
            setAttributes({ accordionItems: newItems });
            
            // Update editing index if needed
            if (editingIndex === index) {
                setEditingIndex(index - 1);
            } else if (editingIndex === index - 1) {
                setEditingIndex(index);
            }
        };

        const moveItemDown = (index) => {
            if (index === accordionItems.length - 1) return;
            const newItems = [...accordionItems];
            [newItems[index], newItems[index + 1]] = [newItems[index + 1], newItems[index]];
            setAttributes({ accordionItems: newItems });
            
            // Update editing index if needed
            if (editingIndex === index) {
                setEditingIndex(index + 1);
            } else if (editingIndex === index + 1) {
                setEditingIndex(index);
            }
        };

        const parseBulkContent = (bulkText) => {
            if (!bulkText.trim()) return [];
            
            const lines = bulkText.split('\n').filter(line => line.trim());
            const items = [];
            
            lines.forEach(line => {
                const trimmedLine = line.trim();
                if (trimmedLine.includes(':')) {
                    const colonIndex = trimmedLine.indexOf(':');
                    const title = trimmedLine.substring(0, colonIndex).trim();
                    const content = trimmedLine.substring(colonIndex + 1).trim();
                    
                    if (title && content) {
                        items.push({
                            title: title,
                            content: content,
                            isOpen: false
                        });
                    }
                }
            });
            
            return items;
        };

        const handleBulkImport = (bulkText) => {
            const newItems = parseBulkContent(bulkText);
            if (newItems.length > 0) {
                setAttributes({ accordionItems: [...accordionItems, ...newItems] });
            }
        };

        const [bulkImportText, setBulkImportText] = useState('');

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'Accordion Settings', 'gdt-theme' ) }>
                        <SelectControl
                            label={ __( 'Style', 'gdt-theme' ) }
                            value={ style }
                            options={ [
                                { label: __( 'Default', 'gdt-theme' ), value: 'default' },
                                { label: __( 'FAQ', 'gdt-theme' ), value: 'faq' }
                            ] }
                            onChange={ ( value ) => setAttributes( { style: value } ) }
                        />
                        <ToggleControl
                            label={ __( 'Add FAQ Schema', 'gdt-theme' ) }
                            help={ __( 'Adds structured data markup for search engines to better understand your FAQ content.', 'gdt-theme' ) }
                            checked={ enableFaqSchema }
                            onChange={ ( value ) => setAttributes( { enableFaqSchema: value } ) }
                        />
                        <RangeControl
                            label={ __( 'Columns', 'gdt-theme' ) }
                            value={ columns }
                            onChange={ ( value ) => setAttributes( { columns: value } ) }
                            min={ 1 }
                            max={ 3 }
                        />
                        <SelectControl
                            label={ __( 'Items to Show Initially', 'gdt-theme' ) }
                            value={ itemsToShow === 0 ? 'all' : itemsToShow.toString() }
                            options={ [
                                { label: __( 'Show All', 'gdt-theme' ), value: 'all' },
                                ...Array.from({ length: 20 }, (_, i) => ({
                                    label: (i + 1).toString(),
                                    value: (i + 1).toString()
                                }))
                            ] }
                            onChange={ ( value ) => setAttributes( { itemsToShow: value === 'all' ? 0 : parseInt(value) } ) }
                        />
                        <ToggleControl
                            label={ __( 'Show Load More Button', 'gdt-theme' ) }
                            checked={ showLoadMore }
                            onChange={ ( value ) => setAttributes( { showLoadMore: value } ) }
                        />
                        { showLoadMore && (
                            <TextControl
                                label={ __( 'Load More Button Text', 'gdt-theme' ) }
                                value={ loadMoreText }
                                onChange={ ( value ) => setAttributes( { loadMoreText: value } ) }
                            />
                        ) }
                    </PanelBody>
                    <PanelBody title={ __( 'Accordion Items', 'gdt-theme' ) } initialOpen={ false }>
                        <Button 
                            isPrimary 
                            onClick={ addAccordionItem }
                            style={{ marginBottom: '16px' }}
                        >
                            { __( 'Add Accordion Item', 'gdt-theme' ) }
                        </Button>
                        
                        <Card style={{ marginBottom: '16px' }}>
                            <CardHeader>
                                <strong>{ __( 'Bulk Import', 'gdt-theme' ) }</strong>
                            </CardHeader>
                            <CardBody>
                                <TextareaControl
                                    label={ __( 'Paste Content', 'gdt-theme' ) }
                                    value={ bulkImportText }
                                    onChange={ setBulkImportText }
                                    rows={ 6 }
                                    help={ __( 'Paste content in format: "Title: Content description". Each line will create a new accordion item.', 'gdt-theme' ) }
                                    placeholder={ __( 'Left-Turn Accidents: The most common type, when drivers fail to see oncoming motorcycles.\nLane Change Accidents: When cars suddenly change lanes into motorcyclists.', 'gdt-theme' ) }
                                />
                                <div style={{ display: 'flex', gap: '8px', marginTop: '12px' }}>
                                    <Button 
                                        isSecondary 
                                        onClick={ () => handleBulkImport( bulkImportText ) }
                                        disabled={ !bulkImportText.trim() }
                                    >
                                        { __( 'Import Items', 'gdt-theme' ) }
                                    </Button>
                                    <Button 
                                        isDestructive
                                        isSecondary
                                        onClick={ () => setBulkImportText('') }
                                        disabled={ !bulkImportText.trim() }
                                    >
                                        { __( 'Clear', 'gdt-theme' ) }
                                    </Button>
                                </div>
                            </CardBody>
                        </Card>
                        { accordionItems.map( ( item, index ) => (
                            <Card key={ index } style={{ marginBottom: '16px' }}>
                                <CardHeader>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', width: '100%' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                            <div style={{ display: 'flex', flexDirection: 'column', gap: '2px' }}>
                                                <Button 
                                                    isSmall 
                                                    icon="arrow-up-alt2"
                                                    onClick={ () => moveItemUp( index ) }
                                                    disabled={ index === 0 }
                                                    title={ __( 'Move up', 'gdt-theme' ) }
                                                    style={{ minWidth: '24px', height: '20px', padding: '0' }}
                                                />
                                                <Button 
                                                    isSmall 
                                                    icon="arrow-down-alt2"
                                                    onClick={ () => moveItemDown( index ) }
                                                    disabled={ index === accordionItems.length - 1 }
                                                    title={ __( 'Move down', 'gdt-theme' ) }
                                                    style={{ minWidth: '24px', height: '20px', padding: '0' }}
                                                />
                                            </div>
                                            <strong>{ item.title || __( 'Untitled Item', 'gdt-theme' ) }</strong>
                                        </div>
                                        <div>
                                            <Button 
                                                isSmall 
                                                onClick={ () => setEditingIndex( editingIndex === index ? null : index ) }
                                                style={{ marginRight: '8px' }}
                                            >
                                                { editingIndex === index ? __( 'Close', 'gdt-theme' ) : __( 'Edit', 'gdt-theme' ) }
                                            </Button>
                                            <Button 
                                                isSmall 
                                                isDestructive 
                                                onClick={ () => removeAccordionItem( index ) }
                                            >
                                                { __( 'Remove', 'gdt-theme' ) }
                                            </Button>
                                        </div>
                                    </div>
                                </CardHeader>
                                { editingIndex === index && (
                                    <CardBody>
                                        <TextControl
                                            label={ __( 'Title', 'gdt-theme' ) }
                                            value={ item.title }
                                            onChange={ ( value ) => updateAccordionItem( index, 'title', value ) }
                                        />
                                        <div>
                                            <label 
                                                style={{ 
                                                    display: 'block', 
                                                    marginBottom: '8px',
                                                    fontSize: '11px',
                                                    fontWeight: '500',
                                                    textTransform: 'uppercase',
                                                    color: '#1e1e1e'
                                                }}
                                            >
                                                { __( 'Content', 'gdt-theme' ) }
                                            </label>
                                            <textarea
                                                ref={ (el) => textareaRefs.current[index] = el }
                                                value={ item.content }
                                                onChange={ ( e ) => updateAccordionItem( index, 'content', e.target.value ) }
                                                rows={ 4 }
                                                style={{
                                                    width: '100%',
                                                    padding: '8px',
                                                    fontSize: '13px',
                                                    lineHeight: '1.4',
                                                    border: '1px solid #ddd',
                                                    borderRadius: '2px'
                                                }}
                                            />
                                            <p 
                                                style={{ 
                                                    marginTop: '8px',
                                                    marginBottom: '0',
                                                    fontSize: '11px',
                                                    fontStyle: 'italic',
                                                    color: '#757575'
                                                }}
                                            >
                                                { __( 'Enter the content for this accordion item. HTML tags are supported.', 'gdt-theme' ) }
                                            </p>
                                        </div>
                                        <div style={{ marginTop: '12px' }}>
                                            <Button 
                                                isSecondary
                                                onClick={ () => {
                                                    const textarea = textareaRefs.current[index];
                                                    if ( textarea ) {
                                                        const start = textarea.selectionStart;
                                                        const end = textarea.selectionEnd;
                                                        const selectedText = item.content.substring(start, end);
                                                        
                                                        setCursorPosition({ start, end });
                                                        setLinkText( selectedText );
                                                        setLinkUrl( '' );
                                                        setLinkNewTab( false );
                                                        setLinkPopoverIndex( index );
                                                    }
                                                } }
                                            >
                                                { __( 'Insert Link', 'gdt-theme' ) }
                                            </Button>
                                            { linkPopoverIndex === index && (
                                                <Popover
                                                    position="bottom left"
                                                    onClose={ () => setLinkPopoverIndex( null ) }
                                                >
                                                    <div style={{ padding: '16px', width: '300px' }}>
                                                        <TextControl
                                                            label={ __( 'Link Text', 'gdt-theme' ) }
                                                            value={ linkText }
                                                            onChange={ setLinkText }
                                                            placeholder={ __( 'Enter the text to display', 'gdt-theme' ) }
                                                        />
                                                        <div style={{ marginTop: '12px' }}>
                                                            <label style={{ display: 'block', marginBottom: '4px', fontSize: '11px', fontWeight: '500' }}>
                                                                { __( 'URL', 'gdt-theme' ) }
                                                            </label>
                                                            <URLInput
                                                                value={ linkUrl }
                                                                onChange={ setLinkUrl }
                                                                placeholder={ __( 'Enter URL or search', 'gdt-theme' ) }
                                                            />
                                                        </div>
                                                        <div style={{ marginTop: '12px' }}>
                                                            <ToggleControl
                                                                label={ __( 'Open in new tab', 'gdt-theme' ) }
                                                                checked={ linkNewTab }
                                                                onChange={ setLinkNewTab }
                                                            />
                                                        </div>
                                                        <div style={{ marginTop: '12px', display: 'flex', gap: '8px' }}>
                                                            <Button 
                                                                isPrimary
                                                                onClick={ () => {
                                                                    if ( linkText && linkUrl ) {
                                                                        const targetAttr = linkNewTab ? ' target="_blank" rel="noopener noreferrer"' : '';
                                                                        const linkHtml = `<a href="${linkUrl}"${targetAttr}>${linkText}</a>`;
                                                                        const currentContent = item.content || '';
                                                                        
                                                                        // Insert at cursor position or replace selection
                                                                        const before = currentContent.substring(0, cursorPosition.start);
                                                                        const after = currentContent.substring(cursorPosition.end);
                                                                        const newContent = before + linkHtml + after;
                                                                        
                                                                        updateAccordionItem( index, 'content', newContent );
                                                                        setLinkPopoverIndex( null );
                                                                        setLinkUrl( '' );
                                                                        setLinkText( '' );
                                                                        setLinkNewTab( false );
                                                                    }
                                                                } }
                                                                disabled={ !linkText || !linkUrl }
                                                            >
                                                                { __( 'Insert', 'gdt-theme' ) }
                                                            </Button>
                                                            <Button 
                                                                isSecondary
                                                                onClick={ () => setLinkPopoverIndex( null ) }
                                                            >
                                                                { __( 'Cancel', 'gdt-theme' ) }
                                                            </Button>
                                                        </div>
                                                    </div>
                                                </Popover>
                                            ) }
                                        </div>
                                    </CardBody>
                                ) }
                            </Card>
                        ) ) }
                    </PanelBody>
                </InspectorControls>
                
                <div { ...blockProps }>
                    <div className="c-accordion-block__container">
                        { title && (
                            <RichText
                                tagName="h2"
                                className="c-accordion-block__title"
                                value={ title }
                                onChange={ ( value ) => setAttributes( { title: value } ) }
                                placeholder={ __( 'Enter accordion title...', 'gdt-theme' ) }
                            />
                        ) }
                        
                        <div className={`c-accordion-block__grid c-accordion-block__grid--columns-${columns}`}>
                            { accordionItems.map( ( item, index ) => (
                                <div 
                                    key={ index } 
                                    className={`c-accordion-block__item ${item.isOpen ? 'c-accordion-block__item--open' : ''}`}
                                >
                                    <button 
                                        className="c-accordion-block__toggle"
                                        onClick={ () => toggleAccordionItem( index ) }
                                        type="button"
                                    >
                                        <span className="c-accordion-block__toggle-title">
                                            { item.title }
                                        </span>
                                        <span className="c-accordion-block__toggle-icon">
                                            { item.isOpen ? '−' : '+' }
                                        </span>
                                    </button>
                                    { item.isOpen && (
                                        <div className="c-accordion-block__content">
                                            <div dangerouslySetInnerHTML={{ __html: item.content }} />
                                        </div>
                                    ) }
                                    <div className="c-accordion-block__divider"></div>
                                </div>
                            ) ) }
                        </div>
                        
                        { showLoadMore && accordionItems.length > itemsToShow && (
                            <div className="c-accordion-block__load-more">
                                <button className="c-accordion-block__load-more-btn" type="button">
                                    <span>{ loadMoreText }</span>
                                    <svg className="c-accordion-block__load-more-icon" viewBox="0 0 17 17" fill="none">
                                        <path d="M8.5 1V16" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                                        <path d="M16 8.80029L0.999999 8.80029" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                                    </svg>
                                </button>
                            </div>
                        ) }
                    </div>
                </div>
            </>
        );
    },
    save: function( { attributes } ) {
        const { 
            title, 
            accordionItems, 
            itemsToShow, 
            showLoadMore, 
            loadMoreText, 
            columns 
        } = attributes;
        
        const blockProps = useBlockProps.save({
            className: `c-accordion-block c-accordion-block--columns-${columns}`,
        });

        return (
            <div { ...blockProps }>
                <div className="c-accordion-block__container">
                    { title && (
                        <RichText.Content
                            tagName="h2"
                            className="c-accordion-block__title"
                            value={ title }
                        />
                    ) }
                    
                    <div 
                        className={`c-accordion-block__grid c-accordion-block__grid--columns-${columns}`}
                        data-items-to-show={ itemsToShow }
                        data-show-load-more={ showLoadMore }
                        data-load-more-text={ loadMoreText }
                        data-columns={ columns }
                    >
                        { accordionItems.map( ( item, index ) => (
                            <div 
                                key={ index } 
                                className="c-accordion-block__item"
                                data-index={ index }
                            >
                                <button 
                                    className="c-accordion-block__toggle"
                                    type="button"
                                    aria-expanded="false"
                                    aria-controls={ `accordion-content-${index}` }
                                    id={ `accordion-toggle-${index}` }
                                >
                                    <span className="c-accordion-block__toggle-title">
                                        { item.title }
                                    </span>
                                    <span className="c-accordion-block__toggle-icon" aria-hidden="true">
                                        <svg viewBox="0 0 17 17" fill="none">
                                            <path d="M8.5 1V16" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                                            <path d="M16 8.80029L0.999999 8.80029" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                                        </svg>
                                    </span>
                                </button>
                                <div 
                                    className="c-accordion-block__content"
                                    id={ `accordion-content-${index}` }
                                    aria-labelledby={ `accordion-toggle-${index}` }
                                    role="region"
                                    hidden
                                >
                                    <div className="c-accordion-block__content-inner">
                                        <div dangerouslySetInnerHTML={{ __html: item.content }} />
                                    </div>
                                </div>
                                <div className="c-accordion-block__divider"></div>
                            </div>
                        ) ) }
                    </div>
                    
                    { showLoadMore && accordionItems.length > itemsToShow && (
                        <div className="c-accordion-block__load-more">
                            <button 
                                className="c-accordion-block__load-more-btn" 
                                type="button"
                                aria-label={ `${loadMoreText}. Currently showing ${itemsToShow} of ${accordionItems.length} items.` }
                            >
                                <span>{ loadMoreText }</span>
                                <svg className="c-accordion-block__load-more-icon" viewBox="0 0 7 12" fill="none" aria-hidden="true">
                                    <path d="M1 11L6 6L1 1" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    ) }
                </div>
            </div>
        );
    },
} );
