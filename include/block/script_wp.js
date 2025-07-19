(function()
{
	var el = wp.element.createElement,
		registerBlockType = wp.blocks.registerBlockType,
		SelectControl = wp.components.SelectControl,
		TextControl = wp.components.TextControl,
		TextareaControl = wp.components.TextareaControl,
		InspectorControls = wp.blockEditor.InspectorControls;

	registerBlockType('mf/countdown',
	{
		title: script_countdown_block_wp.block_title,
		description: script_countdown_block_wp.block_description,
		icon: 'backup',
		category: 'widgets',
		'attributes':
		{
			'align':
			{
				'type': 'string',
				'default': ''
			},
			'countdown_date':
			{
                'type': 'string',
                'default': ''
            },
			'countdown_date_info':
			{
                'type': 'string',
                'default': ''
            },
			'countdown_text':
			{
                'type': 'string',
                'default': ''
            },
			'countdown_link':
			{
                'type': 'string',
                'default': ''
            },
			'countdown_html':
			{
                'type': 'string',
                'default': ''
            },
			'countdown_countup':
			{
                'type': 'string',
                'default': ''
            },
			'countdown_countup_info':
			{
                'type': 'string',
                'default': ''
            },
		},
		'supports':
		{
			'html': false,
			'multiple': true,
			'align': true,
			'spacing':
			{
				'margin': true,
				'padding': true
			},
			'color':
			{
				'background': true,
				'gradients': false,
				'text': true
			},
			'defaultStylePicker': true,
			'typography':
			{
				'fontSize': true,
				'lineHeight': true
			},
			"__experimentalBorder":
			{
				"radius": true
			}
		},
		edit: function(props)
		{
			return el(
				'div',
				{className: 'wp_mf_block_container'},
				[
					el(
						InspectorControls,
						'div',
						el(
							TextControl,
							{
								label: script_countdown_block_wp.countdown_date_label,
								type: 'datetime-local',
								value: props.attributes.countdown_date,
								onChange: function(value)
								{
									props.setAttributes({countdown_date: value});
								}
							}
						)
					),
					el(
						InspectorControls,
						'div',
						el(
							TextControl,
							{
								label: script_countdown_block_wp.countdown_date_info_label,
								type: 'text',
								value: props.attributes.countdown_date_info,
								onChange: function(value)
								{
									props.setAttributes({countdown_date_info: value});
								}
							}
						)
					),
					el(
						InspectorControls,
						'div',
						el(
							TextControl,
							{
								label: script_countdown_block_wp.countdown_text_label,
								type: 'text',
								value: props.attributes.countdown_text,
								onChange: function(value)
								{
									props.setAttributes({countdown_text: value});
								}
							}
						)
					),
					el(
						InspectorControls,
						'div',
						el(
							TextControl,
							{
								label: script_countdown_block_wp.countdown_link_label,
								type: 'url',
								value: props.attributes.countdown_link,
								onChange: function(value)
								{
									props.setAttributes({countdown_link: value});
								}
							}
						)
					),
					el(
						InspectorControls,
						null,
						el(
							TextareaControl,
							{
								label: script_countdown_block_wp.countdown_html_label,
								value: props.attributes.countdown_html,
								onChange: function(value)
								{
									props.setAttributes({countdown_html: value});
								}
							}
						)
					),
					el(
						InspectorControls,
						'div',
						el(
							TextControl,
							{
								label: script_countdown_block_wp.countdown_countup_label,
								type: 'datetime-local',
								value: props.attributes.countdown_countup,
								onChange: function(value)
								{
									props.setAttributes({countdown_countup: value});
								}
							}
						)
					),
					el(
						InspectorControls,
						'div',
						el(
							TextControl,
							{
								label: script_countdown_block_wp.countdown_countup_info_label,
								type: 'text',
								value: props.attributes.countdown_countup_info,
								onChange: function(value)
								{
									props.setAttributes({countdown_countup_info: value});
								}
							}
						)
					),
					el(
						'strong',
						{className: props.className},
						script_countdown_block_wp.block_title
					)
				]
			);
		},
		save: function()
		{
			return null;
		}
	});
})();