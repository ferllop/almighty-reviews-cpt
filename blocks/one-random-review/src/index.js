import { registerBlockType } from '@wordpress/blocks';
const { ServerSideRender } = wp.components;
import { __ } from '@wordpress/i18n';
import './style.scss';

import './editor.scss';

registerBlockType('almighty-reviews-cpt/one-random-review', {
	title: __('One Random Review', 'one-random-review'),
	description: __(
		'Block to render a random review.',
		'one-random-review'
	),
	category: 'widgets',
	icon: 'star-filled',
	supports: {
		// Removes support for an HTML mode.
		html: false,
	},
	edit: () => <ServerSideRender block="almighty-reviews-cpt/one-random-review" />,
	save: () => null
});
