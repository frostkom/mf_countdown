jQuery(function($)
{
	$(".widget.widget_countdown.loading").each(function()
	{
		var dom_obj = $(this),
			now_date = new Date();

		var widget_countdown_date = dom_obj.data('countdown_date'),
			widget_countdown_date_info = dom_obj.data('countdown_date_info'),
			widget_countdown_date_encrypted = dom_obj.data('countdown_date_encrypted'),
			widget_countdown_text = dom_obj.data('countdown_text'),
			widget_countdown_link_encrypted = dom_obj.data('countdown_link_encrypted'),
			widget_countdown_html_encrypted = dom_obj.data('countdown_html_encrypted'),
			widget_countdown_countup = dom_obj.data('countdown_countup'),
			widget_countdown_countup_info = dom_obj.data('countdown_countup_info');

		dom_obj.addClass('playing');

		var timer = setInterval(function()
		{
			now_date = new Date();

			if(typeof widget_countdown_date != 'undefined')
			{
				var countdown_date = new Date(widget_countdown_date),
					countdown_date_left = (countdown_date - now_date);
			}

			else
			{
				var countdown_date_left = 0;
			}

			if(typeof widget_countdown_countup != 'undefined')
			{
				var countdown_countup = new Date(widget_countdown_countup),
					countdown_countup_since = (now_date - countdown_countup);
			}

			else
			{
				var countdown_countup_since = 0;
			}

			if(countdown_countup_since <= 0 && countdown_date_left <= 0)
			{
				clearInterval(timer);

				dom_obj.removeClass('playing pausing');

				if(widget_countdown_link_encrypted != '' || widget_countdown_html_encrypted != '')
				{
					dom_obj.html("<p>" + script_countdown.loading_animation + "</p>");

					$.ajax(
					{
						url: script_countdown.ajax_url,
						type: 'post',
						dataType: 'json',
						data: {
							action: 'api_countdown_validate',
							countdown_date_encrypted: widget_countdown_date_encrypted,
							countdown_text: widget_countdown_text,
							countdown_link_encrypted: widget_countdown_link_encrypted,
							countdown_html_encrypted: widget_countdown_html_encrypted,
						},
						success: function(data)
						{
							dom_obj.html('').append(data.html);
						}
					});
				}

				else
				{
					dom_obj.html(widget_countdown_text);
				}

				return;
			}

			else if(dom_obj.hasClass('playing'))
			{
				if(countdown_date_left > 0)
				{
					var days = Math.floor(countdown_date_left / (1000 * 60 * 60 * 24)),
						hours = Math.floor((countdown_date_left % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
						minutes = Math.floor((countdown_date_left % (1000 * 60 * 60)) / (1000 * 60)),
						seconds = Math.floor((countdown_date_left % (1000 * 60)) / 1000);
				}

				else
				{
					var days = Math.floor(countdown_countup_since / (1000 * 60 * 60 * 24)),
						hours = Math.floor((countdown_countup_since % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
						minutes = Math.floor((countdown_countup_since % (1000 * 60 * 60)) / (1000 * 60)),
						seconds = Math.floor((countdown_countup_since % (1000 * 60)) / 1000);
				}

				var display = "<div>";

					if(days > 0)
					{
						display += "<div><div>" + days + "</div><div>" + (days != 1 ? script_countdown.days_label : script_countdown.day_label) + "</div></div>";
					}

					if(days > 0 || hours > 0)
					{
						display += "<div><div>" + hours + "</div><div>" + (hours != 1 ? script_countdown.hours_label : script_countdown.hour_label) + "</div></div>";
					}

					if(days > 0 || hours > 0 || minutes > 0)
					{
						display += "<div><div>" + minutes + "</div><div>" + (minutes != 1 ? script_countdown.minutes_label : script_countdown.minute_label) + "</div></div>";
					}

					display += "<div><div>" + seconds + "</div><div>" + (seconds != 1 ? script_countdown.seconds_label : script_countdown.second_label) + "</div></div>";

				display += "</div>";

				if(countdown_date_left > 0 && widget_countdown_date_info != '')
				{
					display += "<p>" + widget_countdown_date_info + "</p>";
				}

				else if(widget_countdown_countup_info != '')
				{
					display += "<p>" + widget_countdown_countup_info + "</p>";
				}

				dom_obj.html(display);
			}
		}, 500);

		dom_obj.on('click', function()
		{
			dom_obj.toggleClass('playing pausing');
		});
	});
});