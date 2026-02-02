import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import blockData from './block.json';

registerBlockType( blockData.name, {
    ...blockData,
    edit: function( { attributes, setAttributes } ) {
        const { title, content, alignment } = attributes;
        const blockProps = useBlockProps({
            className: `c-example-block c-example-block--${alignment}`,
        });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'Block Settings', 'gdt-theme' ) }>
                        <SelectControl
                            label={ __( 'Alignment', 'gdt-theme' ) }
                            value={ alignment }
                            options={ [
                                { label: __( 'Left', 'gdt-theme' ), value: 'left' },
                                { label: __( 'Center', 'gdt-theme' ), value: 'center' },
                                { label: __( 'Right', 'gdt-theme' ), value: 'right' },
                            ] }
                            onChange={ ( value ) => setAttributes( { alignment: value } ) }
                        />
                    </PanelBody>
                </InspectorControls>
                <div { ...blockProps }>
                    <div className="c-example-block__container">
                        <RichText
                            tagName="h2"
                            className="c-example-block__title"
                            value={ title }
                            onChange={ ( value ) => setAttributes( { title: value } ) }
                            placeholder={ __( 'Enter block title...', 'gdt-theme' ) }
                        />
                        <RichText
                            tagName="div"
                            className="c-example-block__content"
                            value={ content }
                            onChange={ ( value ) => setAttributes( { content: value } ) }
                            placeholder={ __( 'Enter block content...', 'gdt-theme' ) }
                        />
                    </div>
                </div>
            </>
        );
    },
    save: function( { attributes } ) {
        const { title, content, alignment } = attributes;
        const blockProps = useBlockProps.save({
            className: `c-example-block c-example-block--${alignment}`,
        });

        return (
            <div { ...blockProps }>
                <div className="c-example-block__container">
                    { title && (
                        <RichText.Content
                            tagName="h2"
                            className="c-example-block__title"
                            value={ title }
                        />
                    ) }
                    { content && (
                        <RichText.Content
                            tagName="div"
                            className="c-example-block__content"
                            value={ content }
                        />
                    ) }
                </div>
            </div>
        );
    },
} );
