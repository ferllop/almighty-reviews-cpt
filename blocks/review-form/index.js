(function (wp) {

	const { registerBlockType } = wp.blocks;
	const { createElement: el } = wp.element;
	const { RangeControl, TextControl, TextareaControl } = wp.components;
	const { useSelect } = wp.data;
	const { useEntityProp } = wp.coreData;
	const { __ } = wp.i18n;

	registerBlockType('almighty-reviews-cpt/review-form', {
		title: __('Review form', 'review-form'),
		description: __('Block to deal with de reviews of reviews custom post type', 'review-form'),
		category: 'widgets',
		icon: 'star-filled',
		attributes: {},

		edit() {
			const postType = useSelect(
				select => select('core/editor').getCurrentPostType(),
				[]
			);
			const [meta, setMeta] = useEntityProp(
				'postType',
				postType,
				'meta'
			);


			let result = (
				el('div', {}, [
					renderReviewForm(),
					el('div', { className: 'review-preview' }, [
						el('h3', {}, 'Así se verá la review'),
						renderReview(meta)
					])
				])
			);

			function renderReview({ source, name, description, rating }) {
				function renderStarsWithRating(rating) {
					let stars = [];

					for (i = 1; i <= rating; i++) {
						stars.push(el('span', { className: 'star' }));
					}

					return stars;
				};

				return (
					el('div', {
						className: 'item-review',
						'data-review-source': source.toLowerCase(),
					}, [
						el('div', {
							className: 'item-review-name',
						}, name + ' - '),
						el('div', {
							className: 'item-review-rating'
						}, renderStarsWithRating(rating)),
						el('blockquote', { className: 'item-review-text' }, description)
					])
				);
			};

			function renderReviewForm() {
				return el('div', { className: 'review-form' }, [
					el(TextControl, {
						label: __('Name', 'review-form'),
						value: meta['name'],
						placeholder: 'Jeff Mills',
						onChange: name => {
							wp.data.dispatch('core/editor').editPost({ title: name })
							setMeta({ ...meta, name })
						}
					}),
					el(RangeControl, {
						label: __('Rating', 'review-form'),
						value: meta['rating'],
						min: 1,
						max: 5,
						initialPosition: 5,
						marks: true,
						onChange: rating => setMeta({ ...meta, rating })
					}),
					el(TextareaControl, {
						label: __('Description', 'review-form'),
						value: meta['description'],
						placeholder: 'Was great to work with Marian. I will repeat and blablabla...',
						onChange: description => setMeta({ ...meta, description })
					}),
					el(TextControl, {
						label: __('Source', 'review-form'),
						placeholder: 'Google Reviews',
						value: meta['source'],
						onChange: source => setMeta({ ...meta, source })
					})
				]);
			}

			return result;
		},

		save() { return null }

	});
}(
	window.wp
));

