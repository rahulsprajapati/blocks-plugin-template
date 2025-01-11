import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import classnames from 'classnames';

import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { title, content } = attributes;

	const blockProps = useBlockProps( {
		className: classnames( 'blocks-plugin-template', 'extra-css-class' ),
	} );

	return (
		<div { ...blockProps }>
			<RichText
				tagName="h2"
				value={ title }
				onChange={ ( value ) => setAttributes( { title: value } ) }
				placeholder={ __( 'Example Title here' ) }
				className="example-block__heading h4"
				keepPlaceholderOnFocus
			/>
			<RichText
				placeholder={ __( 'Example Content' ) }
				value={ content }
				onChange={ ( newText ) => {
					setAttributes( { content: newText } );
				} }
				className="example-block__input"
				allowFormats={ [
					'core/bold',
					'core/italics',
					'core/link',
					'core/underline',
				] }
			/>
		</div>
	);
}
