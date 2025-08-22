<?php

class mf_countdown
{
	function __construct(){}

	function block_render_callback($attributes)
	{
		if(!isset($attributes['countdown_date'])){			$attributes['countdown_date'] = date("Y-m-d H:i:s", strtotime("-1 day"));}
		if(!isset($attributes['countdown_date_info'])){		$attributes['countdown_date_info'] = "";}
		if(!isset($attributes['countdown_text'])){			$attributes['countdown_text'] = __("Done!", 'lang_countdown');}
		if(!isset($attributes['countdown_link'])){			$attributes['countdown_link'] = "";}
		if(!isset($attributes['countdown_html'])){			$attributes['countdown_html'] = "";}
		if(!isset($attributes['countdown_countup'])){		$attributes['countdown_countup'] = "";}
		if(!isset($attributes['countdown_countup_info'])){	$attributes['countdown_countup_info'] = "";}

		$out = "";

		if($attributes['countdown_date'] < date("Y-m-d H:i:s") && ($attributes['countdown_countup'] == '' || $attributes['countdown_countup'] > date("Y-m-d H:i:s")))
		{
			$out .= "<div".parse_block_attributes(array('class' => "widget widget_countdown", 'attributes' => $attributes)).">";

				if($attributes['countdown_html'] != '')
				{
					$out .= $attributes['countdown_html'];
				}

				else
				{
					$out .= "<p>";

						if($attributes['countdown_link'] != '')
						{
							$out .= " <a href='".$attributes['countdown_link']."'>";
						}

							$out .= $attributes['countdown_text'];

						if($attributes['countdown_link'] != '')
						{
							$out .= "</a>";
						}

					$out .= "</p>";
				}

			$out .= "</div>";
		}

		else
		{
			$plugin_include_url = plugin_dir_url(__FILE__);

			mf_enqueue_style('style_countdown', $plugin_include_url."style.css");
			mf_enqueue_script('script_countdown', $plugin_include_url."script.js", array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'days_label' => __("Days", 'lang_countdown'),
				'day_label' => __("Day", 'lang_countdown'),
				'hours_label' => __("Hours", 'lang_countdown'),
				'hour_label' => __("Hour", 'lang_countdown'),
				'minutes_label' => __("Minutes", 'lang_countdown'),
				'minute_label' => __("Minute", 'lang_countdown'),
				'seconds_label' => __("Seconds", 'lang_countdown'),
				'second_label' => __("Second", 'lang_countdown'),
				'loading_animation' => apply_filters('get_loading_animation', ''),
			));

			$obj_encryption = new mf_encryption('countdown');

			if($attributes['countdown_date'] > DEFAULT_DATE)
			{
				$countdown_date_encrypted = $obj_encryption->encrypt($attributes['countdown_date']);
			}

			if($attributes['countdown_link'] != '')
			{
				$countdown_link_encrypted = $obj_encryption->encrypt($attributes['countdown_link']);
			}

			if($attributes['countdown_html'] != '')
			{
				$countdown_html_encrypted = $obj_encryption->encrypt($attributes['countdown_html']);
			}

			$out .= "<div"
				.parse_block_attributes(array('class' => "widget widget_countdown loading", 'attributes' => $attributes));

				if($attributes['countdown_date'] > DEFAULT_DATE)
				{
					$out .= " data-countdown_date='".$attributes['countdown_date']."'";
				}

				if($attributes['countdown_date_info'] != '')
				{
					$out .= " data-countdown_date_info='".$attributes['countdown_date_info']."'";
				}

				if($countdown_date_encrypted != '')
				{
					$out .= " data-countdown_date_encrypted='".$countdown_date_encrypted."'";
				}

				if($attributes['countdown_text'] != '')
				{
					$out .= " data-countdown_text='".htmlspecialchars($attributes['countdown_text'], ENT_QUOTES, 'UTF-8')."'";
				}

				if(isset($countdown_link_encrypted) && $countdown_link_encrypted != '')
				{
					$out .= " data-countdown_link_encrypted='".$countdown_link_encrypted."'";
				}

				if(isset($countdown_html_encrypted) && $countdown_html_encrypted != '')
				{
					$out .= " data-countdown_html_encrypted='".$countdown_html_encrypted."'";
				}

				if($attributes['countdown_countup'] > DEFAULT_DATE)
				{
					$out .= " data-countdown_countup='".$attributes['countdown_countup']."'";
				}

				if($attributes['countdown_countup_info'] != '')
				{
					$out .= " data-countdown_countup_info='".$attributes['countdown_countup_info']."'";
				}

			$out .= ">
				<p>".apply_filters('get_loading_animation', '', ['class' => "fa-3x"])."</p>
			</div>";
		}

		return $out;
	}

	function enqueue_block_editor_assets()
	{
		$plugin_include_url = plugin_dir_url(__FILE__);
		$plugin_version = get_plugin_version(__FILE__);

		wp_register_script('script_countdown_block_wp', $plugin_include_url."block/script_wp.js", array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-block-editor'), $plugin_version, true);

		wp_localize_script('script_countdown_block_wp', 'script_countdown_block_wp', array(
			'block_title' => __("Countdown", 'lang_countdown'),
			'block_description' => __("Display Countdown", 'lang_countdown'),
			'countdown_date_label' => __("Deadline", 'lang_countdown'),
			'countdown_date_info_label' => " - ".__("Explanation", 'lang_countdown'),
			'countdown_text_label' => __("Deadline Text", 'lang_countdown'),
			'countdown_link_label' => " - ".__("Link", 'lang_countdown'),
			'countdown_html_label' => " - ".__("HTML", 'lang_countdown'),
			'countdown_countup_label' => __("Countup", 'lang_countdown'),
			'countdown_countup_info_label' => " - ".__("Explanation", 'lang_countdown'),
		));
	}

	function init()
	{
		load_plugin_textdomain('lang_countdown', false, str_replace("/include", "", dirname(plugin_basename(__FILE__)))."/lang/");

		register_block_type('mf/countdown', array(
			'editor_script' => 'script_countdown_block_wp',
			'editor_style' => 'style_base_block_wp',
			'render_callback' => array($this, 'block_render_callback'),
		));
	}

	/*function get_loading_animation($html, $args = [])
	{
		if(!isset($args['class'])){		$args['class'] = "";}
		if(!isset($args['style'])){		$args['style'] = "";}

		if($html == '')
		{
			$html = "<style>
				.loading_color {
					display: block;
					margin: -3em auto auto;
					width: 16em;
					height: auto;
				}
				.loading_color line {
					animation-duration: 3s;
					animation-timing-function: ease-in-out;
					animation-iteration-count: infinite;
				}
				.loading_color__line1, .loading_color__line9 {
					animation-name: line1;
				}
				.loading_color__line2, .loading_color__line8 {
					animation-name: line2;
				}
				.loading_color__line3, .loading_color__line7 {
					animation-name: line3;
				}
				.loading_color__line4, .loading_color__line6 {
					animation-name: line4;
				}
				.loading_color__line5 {
					animation-name: line5;
				}

				@media (prefers-color-scheme: dark) {
					:root {
						--bg: hsl(var(--hue),90%,10%);
						--fg: hsl(var(--hue),90%,90%);
					}
				}

				@keyframes line1 {
					from,
					8% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					18% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					28% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					38% {
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					48% {
						opacity: 1;
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					53% {
						opacity: 0;
						stroke-dashoffset: 31.99;
						transform: translate(8px,16px);
					}
					56% {
						animation-timing-function: steps(1,start);
						opacity: 0;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					60% {
						animation-timing-function: ease-out;
						opacity: 1;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					70% {
						animation-timing-function: ease-in-out;
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					80% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					90% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					to {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
				}
				@keyframes line2 {
					from,
					6% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					16% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					26% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					36% {
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					46% {
						opacity: 1;
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					51% {
						opacity: 0;
						stroke-dashoffset: 31.99;
						transform: translate(8px,16px);
					}
					54% {
						animation-timing-function: steps(1,start);
						opacity: 0;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					58% {
						animation-timing-function: ease-out;
						opacity: 1;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					68% {
						animation-timing-function: ease-in-out;
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					78% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					88% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					98%,
					to {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
				}
				@keyframes line3 {
					from,
					4% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					14% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					24% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					34% {
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					44% {
						opacity: 1;
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					49% {
						opacity: 0;
						stroke-dashoffset: 31.99;
						transform: translate(8px,16px);
					}
					52% {
						animation-timing-function: steps(1,start);
						opacity: 0;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					56% {
						animation-timing-function: ease-out;
						opacity: 1;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					66% {
						animation-timing-function: ease-in-out;
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					76% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					86% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					96%,
					to {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
				}
				@keyframes line4 {
					from,
					2% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					12% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					22% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					32% {
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					42% {
						opacity: 1;
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					47% {
						opacity: 0;
						stroke-dashoffset: 31.99;
						transform: translate(8px,16px);
					}
					50% {
						animation-timing-function: steps(1,start);
						opacity: 0;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					54% {
						animation-timing-function: ease-out;
						opacity: 1;
						stroke-dashoffset: 32;
						transform: translate(0,16px);
					}
					64% {
						animation-timing-function: ease-in-out;
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					74% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					84% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					94%,
					to {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
				}
				@keyframes line5 {
					from {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					10% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					20% {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					30% {
						stroke-dashoffset: 0;
						transform: translate(0,0);
					}
					40% {
						stroke-dashoffset: -16;
						transform: translate(0,15px);
					}
					50% {
						stroke-dashoffset: -31;
						transform: translate(0,-48px);
					}
					58% {
						stroke-dashoffset: -31;
						transform: translate(0,8px);
					}
					65% {
						stroke-dashoffset: -31.99;
						transform: translate(0,-24px);
					}
					71.99% {
						animation-timing-function: steps(1);
						stroke-dashoffset: -31.99;
						transform: translate(0,-16px);
					}
					72% {
						animation-timing-function: ease-in-out;
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
					82% {
						stroke-dashoffset: 16;
						transform: translate(0,8px);
					}
					92%,
					to {
						stroke-dashoffset: 31.99;
						transform: translate(0,16px);
					}
				}
			</style>
			<svg class='loading_color loading_animation' viewBox='0 0 128 128'>
				<defs>
					<linearGradient id='loading_color-grad' x1='0' y1='0' x2='1' y2='1'>
						<stop offset='0%' stop-color='#000' />
						<stop offset='100%' stop-color='#fff' />
					</linearGradient>
					<mask id='loading_color-mask'>
						<rect x='0' y='0' width='128' height='128' fill='url(#loading_color-grad)' />
					</mask>
				</defs>
				<g stroke-linecap='round' stroke-width='8' stroke-dasharray='32 32'>
					<g stroke='hsl(193,90%,50%)'>
						<line class='loading_color__line1' x1='4' y1='48' x2='4' y2='80' />
						<line class='loading_color__line2' x1='19' y1='48' x2='19' y2='80' />
						<line class='loading_color__line3' x1='34' y1='48' x2='34' y2='80' />
						<line class='loading_color__line4' x1='49' y1='48' x2='49' y2='80' />
						<line class='loading_color__line5' x1='64' y1='48' x2='64' y2='80' />
						<g transform='rotate(180,79,64)'>
							<line class='loading_color__line6' x1='79' y1='48' x2='79' y2='80' />
						</g>
						<g transform='rotate(180,94,64)'>
							<line class='loading_color__line7' x1='94' y1='48' x2='94' y2='80' />
						</g>
						<g transform='rotate(180,109,64)'>
							<line class='loading_color__line8' x1='109' y1='48' x2='109' y2='80' />
						</g>
						<g transform='rotate(180,124,64)'>
							<line class='loading_color__line9' x1='124' y1='48' x2='124' y2='80' />
						</g>
					</g>
					<g stroke='hsl(283,90%,50%)' mask='url(#loading_color-mask)'>
						<line class='loading_color__line1' x1='4' y1='48' x2='4' y2='80' />
						<line class='loading_color__line2' x1='19' y1='48' x2='19' y2='80' />
						<line class='loading_color__line3' x1='34' y1='48' x2='34' y2='80' />
						<line class='loading_color__line4' x1='49' y1='48' x2='49' y2='80' />
						<line class='loading_color__line5' x1='64' y1='48' x2='64' y2='80' />
						<g transform='rotate(180,79,64)'>
							<line class='loading_color__line6' x1='79' y1='48' x2='79' y2='80' />
						</g>
						<g transform='rotate(180,94,64)'>
							<line class='loading_color__line7' x1='94' y1='48' x2='94' y2='80' />
						</g>
						<g transform='rotate(180,109,64)'>
							<line class='loading_color__line8' x1='109' y1='48' x2='109' y2='80' />
						</g>
						<g transform='rotate(180,124,64)'>
							<line class='loading_color__line9' x1='124' y1='48' x2='124' y2='80' />
						</g>
					</g>
				</g>
			</svg>";
		}

		return $html;
	}*/

	function api_countdown_validate()
	{
		$json_output = array(
			'success' => false,
		);

		$countdown_date_encrypted = check_var('countdown_date_encrypted');
		$countdown_text = check_var('countdown_text');
		$countdown_link_encrypted = check_var('countdown_link_encrypted');
		$countdown_html_encrypted = check_var('countdown_html_encrypted');

		$obj_encryption = new mf_encryption('countdown');
		$countdown_date = date("Y-m-d H:i:s", strtotime($obj_encryption->decrypt($countdown_date_encrypted)));
		$countdown_link = $obj_encryption->decrypt($countdown_link_encrypted);
		$countdown_html = $obj_encryption->decrypt($countdown_html_encrypted);

		$date_now = date("Y-m-d H:i:s");

		if($countdown_date <= $date_now)
		{
			$json_output['success'] = true;

			if($countdown_html != '')
			{
				$json_output['html'] = $countdown_html;
			}

			else
			{
				$json_output['html'] = "<p>";

					if($countdown_link != '')
					{
						$json_output['html'] .= " <a href='".$countdown_link."'>";
					}

						$json_output['html'] .= stripslashes(htmlspecialchars_decode(stripslashes($countdown_text)));

					if($countdown_link != '')
					{
						$json_output['html'] .= "</a>";
					}

				$json_output['html'] .= "</p>";
			}
		}

		else
		{
			$time_difference = time_between_dates(array('start' => $countdown_date, 'end' => $date_now, 'type' => 'floor', 'return' => 'seconds'));

			$json_output['html'] = sprintf(__("Your timing is off by %d seconds. Please reload the page and try again."), $time_difference);

			if(IS_SUPER_ADMIN)
			{
				$json_output['html'] .= " (".$countdown_date." < ".$date_now.")";
			}
		}

		header('Content-Type: application/json');
		echo json_encode($json_output);
		die();
	}
}