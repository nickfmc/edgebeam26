import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { 
    useBlockProps, 
    RichText, 
    InspectorControls,
    MediaUpload,
    MediaUploadCheck
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl, 
    TextControl,
    TextareaControl,
    Button,
    ResponsiveWrapper,
    CheckboxControl
} from '@wordpress/components';
import blockData from './block.json';

registerBlockType( blockData.name, {
    ...blockData,
    edit: function( { attributes, setAttributes } ) {
        const { useTestimonialFields, coverImage, title, youtubeVideoId, transcript, alignment, useAlternateStyle } = attributes;
        const blockProps = useBlockProps({
            className: `c-video-modal c-video-modal--${alignment}${useAlternateStyle ? ' c-video-modal--alternate' : ''}`,
        });

        // Function to extract YouTube video ID from URL
        const extractYouTubeId = (url) => {
            const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
            return match ? match[1] : url;
        };

        const onSelectImage = (media) => {
            setAttributes({
                coverImage: {
                    id: media.id,
                    url: media.url,
                    alt: media.alt,
                }
            });
        };

        const onRemoveImage = () => {
            setAttributes({
                coverImage: null
            });
        };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'Video Settings', 'gdt-theme' ) }>
                        <CheckboxControl
                            label={ __( 'Use Testimonial Fields', 'gdt-theme' ) }
                            checked={ useTestimonialFields }
                            onChange={ ( value ) => setAttributes( { useTestimonialFields: value } ) }
                            help={ __( 'When enabled, uses featured image and ACF video_url field from the current post.', 'gdt-theme' ) }
                        />
                        { ! useTestimonialFields && (
                            <TextControl
                                label={ __( 'YouTube Video URL or ID', 'gdt-theme' ) }
                                value={ youtubeVideoId }
                                onChange={ ( value ) => setAttributes( { youtubeVideoId: extractYouTubeId(value) } ) }
                                help={ __( 'Enter YouTube video URL or video ID', 'gdt-theme' ) }
                            />
                        ) }
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
                        <CheckboxControl
                            label={ __( 'Use Alternate Style', 'gdt-theme' ) }
                            checked={ useAlternateStyle }
                            onChange={ ( value ) => setAttributes( { useAlternateStyle: value } ) }
                            help={ __( 'Remove gradient and title, center play button on image.', 'gdt-theme' ) }
                        />
                    </PanelBody>
                    { ! useTestimonialFields && (
                        <PanelBody title={ __( 'Transcript', 'gdt-theme' ) } initialOpen={ false }>
                        <TextareaControl
                            label={ __( 'Video Transcript', 'gdt-theme' ) }
                            value={ transcript }
                            onChange={ ( value ) => setAttributes( { transcript: value } ) }
                            rows={ 8 }
                            help={ __( 'Add the video transcript text. You can use multiple paragraphs.', 'gdt-theme' ) }
                        />
                        </PanelBody>
                    ) }
                </InspectorControls>
                <div { ...blockProps }>
                    <div className="c-video-modal__container">
                        { useTestimonialFields ? (
                            <div className="c-video-modal__testimonial-preview">
                                <p><strong>{ __( 'Testimonial Video Modal', 'gdt-theme' ) }</strong></p>
                                <p>{ __( 'This block will use the featured image and ACF video_url field from the current post.', 'gdt-theme' ) }</p>
                                <div className="c-video-modal__placeholder">
                                    <div className="c-video-modal__play-button">
                                        <svg width="45" height="45" viewBox="0 0 45 45" fill="none">
                                            <rect x="1" y="1" width="43" height="43" rx="21.5" stroke="white" strokeWidth="2"/>
                                            <path d="M30.5 21.634C31.1667 22.0189 31.1667 22.9811 30.5 23.366L18.5 30.2942C17.8333 30.6791 17 30.198 17 29.4282L17 15.5718C17 14.802 17.8333 14.3209 18.5 14.7058L30.5 21.634Z" fill="white"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="c-video-modal__cover">
                                <MediaUploadCheck>
                                    <MediaUpload
                                        onSelect={ onSelectImage }
                                        allowedTypes={ ['image'] }
                                        value={ coverImage?.id }
                                        render={ ({ open }) => (
                                            <>
                                                { coverImage ? (
                                                    <div className="c-video-modal__image-wrapper">
                                                        <ResponsiveWrapper
                                                            naturalWidth={ 400 }
                                                            naturalHeight={ 300 }
                                                        >
                                                            <img 
                                                                src={ coverImage.url } 
                                                                alt={ coverImage.alt || __( 'Video cover image', 'gdt-theme' ) }
                                                            />
                                                        </ResponsiveWrapper>
                                                        <div className={`c-video-modal__overlay${useAlternateStyle ? ' c-video-modal__overlay--alternate' : ''}`}>
                                                            { ! useAlternateStyle && (
                                                                <RichText
                                                                    tagName="div"
                                                                    className="c-video-modal__title"
                                                                    value={ title }
                                                                    onChange={ ( value ) => setAttributes( { title: value } ) }
                                                                    placeholder={ __( 'Enter video title...', 'gdt-theme' ) }
                                                                />
                                                            ) }
                                                            <div className="c-video-modal__play-button">
                                                                <svg width="45" height="45" viewBox="0 0 45 45" fill="none">
                                                                    <rect x="1" y="1" width="43" height="43" rx="21.5" stroke="white" strokeWidth="2"/>
                                                                    <path d="M30.5 21.634C31.1667 22.0189 31.1667 22.9811 30.5 23.366L18.5 30.2942C17.8333 30.6791 17 30.198 17 29.4282L17 15.5718C17 14.802 17.8333 14.3209 18.5 14.7058L30.5 21.634Z" fill="white"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div className="c-video-modal__image-controls">
                                                            <Button 
                                                                onClick={ open }
                                                                className="c-video-modal__replace-image"
                                                                variant="secondary"
                                                            >
                                                                { __( 'Replace Image', 'gdt-theme' ) }
                                                            </Button>
                                                            <Button 
                                                                className="c-video-modal__remove-image"
                                                                onClick={ onRemoveImage }
                                                                isDestructive
                                                                variant="secondary"
                                                            >
                                                                { __( 'Remove Image', 'gdt-theme' ) }
                                                            </Button>
                                                        </div>
                                                    </div>
                                                ) : (
                                                    <Button 
                                                        onClick={ open }
                                                        className="c-video-modal__upload-button"
                                                        variant="secondary"
                                                    >
                                                        { __( 'Upload Cover Image', 'gdt-theme' ) }
                                                    </Button>
                                                ) }
                                            </>
                                        ) }
                                    />
                                </MediaUploadCheck>
                            </div>
                        )}
                        
                        { ! useTestimonialFields && youtubeVideoId && (
                            <div className="c-video-modal__preview-info">
                                <p><strong>{ __( 'YouTube Video ID:', 'gdt-theme' ) }</strong> { youtubeVideoId }</p>
                                { transcript && (
                                    <p><strong>{ __( 'Transcript:', 'gdt-theme' ) }</strong> { transcript.substring(0, 100) }{ transcript.length > 100 ? '...' : '' }</p>
                                )}
                            </div>
                        )}
                    </div>
                </div>
            </>
        );
    },
    save: function( { attributes } ) {
        const { useTestimonialFields, coverImage, title, youtubeVideoId, transcript, alignment, useAlternateStyle } = attributes;
        const blockProps = useBlockProps.save({
            className: `c-video-modal c-video-modal--${alignment}${useAlternateStyle ? ' c-video-modal--alternate' : ''}`,
        });

        return (
            <div { ...blockProps }>
                <div className="c-video-modal__container">
                    { ( ! useTestimonialFields && coverImage ) || useTestimonialFields ? (
                        <div 
                            className="c-video-modal__cover" 
                            data-video-id={ youtubeVideoId }
                            data-transcript={ transcript }
                            data-use-testimonial-fields={ useTestimonialFields }
                            data-use-alternate-style={ useAlternateStyle }
                        >
                            { ! useTestimonialFields && coverImage && (
                                <img 
                                    src={ coverImage.url } 
                                    alt={ coverImage.alt || title } 
                                    className="c-video-modal__image"
                                />
                            ) }
                            <div className={`c-video-modal__overlay${useAlternateStyle ? ' c-video-modal__overlay--alternate' : ''}`}>
                                { ! useAlternateStyle && ! useTestimonialFields && title && (
                                    <RichText.Content
                                        tagName="div"
                                        className="c-video-modal__title"
                                        value={ title }
                                    />
                                ) }
                                <div className="c-video-modal__play-button">
                                    <svg width="45" height="45" viewBox="0 0 45 45" fill="none">
                                        <rect x="1" y="1" width="43" height="43" rx="21.5" stroke="white" strokeWidth="2"/>
                                        <path d="M30.5 21.634C31.1667 22.0189 31.1667 22.9811 30.5 23.366L18.5 30.2942C17.8333 30.6791 17 30.198 17 29.4282L17 15.5718C17 14.802 17.8333 14.3209 18.5 14.7058L30.5 21.634Z" fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    ) : null }
                </div>
            </div>
        );
    },
} );
