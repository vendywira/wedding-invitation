@php
	$brideName = $template->getSetting('bride_name', 'Isabel');
	$groomName = $template->getSetting('groom_name', 'Jefry');
	$coupleName = $brideName . ' & ' . $groomName;
	$parts = explode("&", $coupleName);
	$brideFirstName = trim($parts[0] ?? $brideName);
	$groomFirstName = trim(str_replace("&", "", $parts[1] ?? $groomName));
	$eventDateFormatted = isset($event->event_date) ? \Carbon\Carbon::parse($event->event_date)->format("d . m . Y") : "24 . 05 . 2026";
	$eventDayName = isset($event->event_date) ? \Carbon\Carbon::parse($event->event_date)->translatedFormat("l") : "Minggu";
	$eventDateFull = isset($event->event_date) ? \Carbon\Carbon::parse($event->event_date)->translatedFormat("d F Y") : "24 Agustus 2026";
	$countdownDate = isset($event->event_date) ? \Carbon\Carbon::parse($event->event_date)->format("M d Y 10:00:00") : "Oct 28 2026 10:00:00";
	$brideFullName = $template->getSetting('bride_full_name', $brideName);
	$groomFullName = $template->getSetting('groom_full_name', $groomName);
	$brideFather = $template->getSetting('bride_father', '');
	$brideMother = $template->getSetting('bride_mother', '');
	$groomFather = $template->getSetting('groom_father', '');
	$groomMother = $template->getSetting('groom_mother', '');
	$bridePhotoUrl = $template->getAssetUrl('bride_photo', 'assets/vintage/vendor/cewek.jpg');
	$groomPhotoUrl = $template->getAssetUrl('groom_photo', 'assets/vintage/vendor/cowok.jpg');
	// The hero ("a journey of love begins") has its own upload slot; until one is
	// uploaded it mirrors the bride photo, which is what the template shipped with.
	$heroPhotoUrl = $template->getAssetUrl('hero_photo') ?: $bridePhotoUrl;
	// Backsound diatur dari Settings → Musik: musik bawaan template, file yang
	// diupload admin, atau tanpa musik. Kalau mode "custom" dipilih tapi filenya
	// belum ada, undangan tetap memakai musik bawaan daripada jadi sunyi.
	$backsoundMode = $template->getSetting('backsound', 'default');
	$backsoundUrl = $template->getAssetUrl('backsound_file');

	if ($backsoundMode === 'none') {
		$backsoundUrl = null;
	} elseif ($backsoundMode !== 'custom' || ! $backsoundUrl) {
		$backsoundUrl = asset('assets/vintage/vendor/Brisia-Jodie-Fabio-Asher-Aku-Memilihmu-Official-Lyric-Video-128-kbps-1.mp3');
	}

	$galleryImages = $template->getGalleryImages();
	// Gallery photos are shown twice — as a swipeable slide and as a clickable
	// grid (with lightbox). Build the list once so both views stay in sync.
	$galleryItems = [];
	foreach ($galleryImages as $galleryImage) {
		$galleryItemUrl = $template->getGalleryImageUrl($galleryImage);
		if ($galleryItemUrl) {
			$galleryItems[] = ['url' => $galleryItemUrl, 'caption' => $galleryImage['caption'] ?? ''];
		}
	}
	$showBridePhoto = $template->getSetting('show_bride_photo', '1');
	$showGroomPhoto = $template->getSetting('show_groom_photo', '1');
	$showStory = $template->getSetting('show_story', '1');
	$showStoryImage = $template->getSetting('show_story_image', '1');
	$showGallery = $template->getSetting('show_gallery', '1');
	$showCountdown = $template->getSetting('show_countdown', '1');
	$showGift = $template->getSetting('show_gift', '1');
	$showLive = $template->getSetting('show_live', '1');
	$showDresscode = $template->getSetting('show_dresscode', '1');
	$showBestWishes = $template->getSetting('show_best_wishes', '1');
	$showFooter = $template->getSetting('show_footer', '1');
	// Daftar hadiah (bank / e-wallet / alamat kado) diatur dari Settings → Hadiah.
	$giftAccounts = $template->getGifts();
@endphp
<!DOCTYPE html>
<html lang="en-US" prefix="og: https://ogp.me/ns#">

<head>
	<meta charset="UTF-8">
	<style type="text/css">
		.cui-comment-text img {
			max-width: 100% !important;
		}
	</style>

	<!-- Search Engine Optimization by Rank Math - https://rankmath.com/ -->
	<title>{{ $metaData['title'] ?? $coupleName . ' - Wedding Invitation' }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="description" content="{{ $metaData['description'] ?? 'Undangan Pernikahan ' . $coupleName }}" />
	<meta name="robots" content="{{ $metaData['robots_meta'] ?? 'nofollow, noindex' }}" />
	<meta property="og:locale" content="id_ID" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="{{ $metaData['og_title'] ?? $coupleName . ' - Wedding Invitation' }}" />
	<meta property="og:description" content="{{ $metaData['og_description'] ?? 'Undangan Pernikahan ' . $coupleName }}" />
	<meta property="og:url" content="{{ $metaData['og_url'] ?? url()->current() }}" />
	<meta property="og:site_name" content="{{ config('app.name', 'Wedding Invitation') }}" />
	<meta property="og:image" content="{{ $metaData['og_image'] ?? asset($template->getAssetUrl('cover_photo', 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg')) }}" />
	<meta property="og:image:width" content="1200" />
	<meta property="og:image:height" content="630" />
	<meta property="og:image:alt" content="{{ $coupleName }}" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="{{ $metaData['og_title'] ?? $coupleName . ' - Wedding Invitation' }}" />
	<meta name="twitter:description" content="{{ $metaData['og_description'] ?? 'Undangan Pernikahan ' . $coupleName }}" />
	<meta name="twitter:image" content="{{ $metaData['og_image'] ?? asset($template->getAssetUrl('cover_photo', 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg')) }}" />
	<meta name="twitter:label1" content="Time to read" />
	<meta name="twitter:data1" content="2 minutes" />
	<!-- /Rank Math WordPress SEO plugin -->

	<link rel='dns-prefetch' href='//unpkg.com' />
	<link rel='dns-prefetch' href='//cdnjs.cloudflare.com' />
	<style id="wp-img-auto-sizes-contain-inline-css">
		img:is([sizes=auto i], [sizes^="auto," i]) {
			contain-intrinsic-size: 3000px 1500px
		}

		/*# sourceURL=wp-img-auto-sizes-contain-inline-css */
	</style>
	<link rel='stylesheet' id='bdt-uikit-css' href='/assets/vintage/vendor/bdt-uikit.css?ver=3.15.1' media='all' />
	<link rel='stylesheet' id='ep-helper-css' href='/assets/vintage/vendor/ep-helper.css?ver=3.1.12' media='all' />
	<style id="wp-emoji-styles-inline-css">
		img.wp-smiley,
		img.emoji {
			display: inline !important;
			border: none !important;
			box-shadow: none !important;
			height: 1em !important;
			width: 1em !important;
			margin: 0 0.07em !important;
			vertical-align: -0.1em !important;
			background: none !important;
			padding: 0 !important;
		}

		/*# sourceURL=wp-emoji-styles-inline-css */
	</style>
	<style id="wp-block-library-inline-css">
		:root {
			--wp-block-synced-color: #7a00df;
			--wp-block-synced-color--rgb: 122, 0, 223;
			--wp-bound-block-color: var(--wp-block-synced-color);
			--wp-editor-canvas-background: #ddd;
			--wp-admin-theme-color: #007cba;
			--wp-admin-theme-color--rgb: 0, 124, 186;
			--wp-admin-theme-color-darker-10: #006ba1;
			--wp-admin-theme-color-darker-10--rgb: 0, 107, 160.5;
			--wp-admin-theme-color-darker-20: #005a87;
			--wp-admin-theme-color-darker-20--rgb: 0, 90, 135;
			--wp-admin-border-width-focus: 2px
		}

		@media (min-resolution:192dpi) {
			:root {
				--wp-admin-border-width-focus: 1.5px
			}
		}

		.wp-element-button {
			cursor: pointer
		}

		:root .has-very-light-gray-background-color {
			background-color: #eee
		}

		:root .has-very-dark-gray-background-color {
			background-color: #313131
		}

		:root .has-very-light-gray-color {
			color: #eee
		}

		:root .has-very-dark-gray-color {
			color: #313131
		}

		:root .has-vivid-green-cyan-to-vivid-cyan-blue-gradient-background {
			background: linear-gradient(135deg, #00d084, #0693e3)
		}

		:root .has-purple-crush-gradient-background {
			background: linear-gradient(135deg, #34e2e4, #4721fb 50%, #ab1dfe)
		}

		:root .has-hazy-dawn-gradient-background {
			background: linear-gradient(135deg, #faaca8, #dad0ec)
		}

		:root .has-subdued-olive-gradient-background {
			background: linear-gradient(135deg, #fafae1, #67a671)
		}

		:root .has-atomic-cream-gradient-background {
			background: linear-gradient(135deg, #fdd79a, #004a59)
		}

		:root .has-nightshade-gradient-background {
			background: linear-gradient(135deg, #330968, #31cdcf)
		}

		:root .has-midnight-gradient-background {
			background: linear-gradient(135deg, #020381, #2874fc)
		}

		:root {
			--wp--preset--font-size--normal: 16px;
			--wp--preset--font-size--huge: 42px
		}

		.has-regular-font-size {
			font-size: 1em
		}

		.has-larger-font-size {
			font-size: 2.625em
		}

		.has-normal-font-size {
			font-size: var(--wp--preset--font-size--normal)
		}

		.has-huge-font-size {
			font-size: var(--wp--preset--font-size--huge)
		}

		:root .has-text-align-center {
			text-align: center
		}

		:root .has-text-align-left {
			text-align: left
		}

		:root .has-text-align-right {
			text-align: right
		}

		.has-fit-text {
			white-space: nowrap !important
		}

		#end-resizable-editor-section {
			display: none
		}

		.aligncenter {
			clear: both
		}

		.items-justified-left {
			justify-content: flex-start
		}

		.items-justified-center {
			justify-content: center
		}

		.items-justified-right {
			justify-content: flex-end
		}

		.items-justified-space-between {
			justify-content: space-between
		}

		.screen-reader-text {
			word-wrap: normal !important;
			border: 0;
			clip-path: inset(50%);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
			word-break: normal !important
		}

		.screen-reader-text:focus {
			background-color: #ddd;
			clip-path: none;
			color: #444;
			display: block;
			font-size: 1em;
			height: auto;
			left: 5px;
			line-height: normal;
			padding: 15px 23px 14px;
			text-decoration: none;
			top: 5px;
			width: auto;
			z-index: 100000
		}

		html :where(.has-border-color) {
			border-style: solid
		}

		html :where([style^=border-color], [style*=";border-color"], [style*="; border-color"]) {
			border-style: solid
		}

		html :where([style^=border-top-color], [style*=";border-top-color"], [style*="; border-top-color"]) {
			border-top-style: solid
		}

		html :where([style^=border-right-color], [style*=";border-right-color"], [style*="; border-right-color"]) {
			border-right-style: solid
		}

		html :where([style^=border-bottom-color], [style*=";border-bottom-color"], [style*="; border-bottom-color"]) {
			border-bottom-style: solid
		}

		html :where([style^=border-left-color], [style*=";border-left-color"], [style*="; border-left-color"]) {
			border-left-style: solid
		}

		html :where([style^=border-width], [style*=";border-width"], [style*="; border-width"]) {
			border-style: solid
		}

		html :where([style^=border-top-width], [style*=";border-top-width"], [style*="; border-top-width"]) {
			border-top-style: solid
		}

		html :where([style^=border-right-width], [style*=";border-right-width"], [style*="; border-right-width"]) {
			border-right-style: solid
		}

		html :where([style^=border-bottom-width], [style*=";border-bottom-width"], [style*="; border-bottom-width"]) {
			border-bottom-style: solid
		}

		html :where([style^=border-left-width], [style*=";border-left-width"], [style*="; border-left-width"]) {
			border-left-style: solid
		}

		html :where(img[class*=wp-image-]) {
			height: auto;
			max-width: 100%
		}

		:where(figure) {
			margin: 0 0 1em
		}

		html :where(.is-position-sticky) {
			--wp-admin--admin-bar--position-offset: var(--wp-admin--admin-bar--height, 0px)
		}

		@media screen and (max-width:600px) {
			html :where(.is-position-sticky) {
				--wp-admin--admin-bar--position-offset: 0px
			}
		}

		/*# sourceURL=/wp-includes/css/dist/block-library/common.min.css */
	</style>

	<style id="global-styles-inline-css">
		:root {
			--wp--preset--aspect-ratio--square: 1;
			--wp--preset--aspect-ratio--4-3: 4/3;
			--wp--preset--aspect-ratio--3-4: 3/4;
			--wp--preset--aspect-ratio--3-2: 3/2;
			--wp--preset--aspect-ratio--2-3: 2/3;
			--wp--preset--aspect-ratio--16-9: 16/9;
			--wp--preset--aspect-ratio--9-16: 9/16;
			--wp--preset--color--black: #000000;
			--wp--preset--color--cyan-bluish-gray: #abb8c3;
			--wp--preset--color--white: #ffffff;
			--wp--preset--color--pale-pink: #f78da7;
			--wp--preset--color--vivid-red: #cf2e2e;
			--wp--preset--color--luminous-vivid-orange: #ff6900;
			--wp--preset--color--luminous-vivid-amber: #fcb900;
			--wp--preset--color--light-green-cyan: #7bdcb5;
			--wp--preset--color--vivid-green-cyan: #00d084;
			--wp--preset--color--pale-cyan-blue: #8ed1fc;
			--wp--preset--color--vivid-cyan-blue: #0693e3;
			--wp--preset--color--vivid-purple: #9b51e0;
			--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg, rgb(6, 147, 227) 0%, rgb(155, 81, 224) 100%);
			--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg, rgb(122, 220, 180) 0%, rgb(0, 208, 130) 100%);
			--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg, rgb(252, 185, 0) 0%, rgb(255, 105, 0) 100%);
			--wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg, rgb(255, 105, 0) 0%, rgb(207, 46, 46) 100%);
			--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg, rgb(238, 238, 238) 0%, rgb(169, 184, 195) 100%);
			--wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg, rgb(74, 234, 220) 0%, rgb(151, 120, 209) 20%, rgb(207, 42, 186) 40%, rgb(238, 44, 130) 60%, rgb(251, 105, 98) 80%, rgb(254, 248, 76) 100%);
			--wp--preset--gradient--blush-light-purple: linear-gradient(135deg, rgb(255, 206, 236) 0%, rgb(152, 150, 240) 100%);
			--wp--preset--gradient--blush-bordeaux: linear-gradient(135deg, rgb(254, 205, 165) 0%, rgb(254, 45, 45) 50%, rgb(107, 0, 62) 100%);
			--wp--preset--gradient--luminous-dusk: linear-gradient(135deg, rgb(255, 203, 112) 0%, rgb(199, 81, 192) 50%, rgb(65, 88, 208) 100%);
			--wp--preset--gradient--pale-ocean: linear-gradient(135deg, rgb(255, 245, 203) 0%, rgb(182, 227, 212) 50%, rgb(51, 167, 181) 100%);
			--wp--preset--gradient--electric-grass: linear-gradient(135deg, rgb(202, 248, 128) 0%, rgb(113, 206, 126) 100%);
			--wp--preset--gradient--midnight: linear-gradient(135deg, rgb(2, 3, 129) 0%, rgb(40, 116, 252) 100%);
			--wp--preset--font-size--small: 13px;
			--wp--preset--font-size--medium: 20px;
			--wp--preset--font-size--large: 36px;
			--wp--preset--font-size--x-large: 42px;
			--wp--preset--spacing--20: 0.44rem;
			--wp--preset--spacing--30: 0.67rem;
			--wp--preset--spacing--40: 1rem;
			--wp--preset--spacing--50: 1.5rem;
			--wp--preset--spacing--60: 2.25rem;
			--wp--preset--spacing--70: 3.38rem;
			--wp--preset--spacing--80: 5.06rem;
			--wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);
			--wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);
			--wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);
			--wp--preset--shadow--outlined: 6px 6px 0px -3px rgb(255, 255, 255), 6px 6px rgb(0, 0, 0);
			--wp--preset--shadow--crisp: 6px 6px 0px rgb(0, 0, 0);
		}

		.wp-block-button {
			--wp--preset--dimension--25: 25%;
			--wp--preset--dimension--50: 50%;
			--wp--preset--dimension--75: 75%;
			--wp--preset--dimension--100: 100%;
		}

		:root {
			--wp--style--global--content-size: 800px;
			--wp--style--global--wide-size: 1200px;
		}

		:where(body) {
			margin: 0;
		}

		.wp-site-blocks>.alignleft {
			float: left;
			margin-right: 2em;
		}

		.wp-site-blocks>.alignright {
			float: right;
			margin-left: 2em;
		}

		.wp-site-blocks>.aligncenter {
			justify-content: center;
			margin-left: auto;
			margin-right: auto;
		}

		:where(.wp-site-blocks)>* {
			margin-block-start: 24px;
			margin-block-end: 0;
		}

		:where(.wp-site-blocks)> :first-child {
			margin-block-start: 0;
		}

		:where(.wp-site-blocks)> :last-child {
			margin-block-end: 0;
		}

		:root {
			--wp--style--block-gap: 24px;
		}

		:root :where(.is-layout-flow)> :first-child {
			margin-block-start: 0;
		}

		:root :where(.is-layout-flow)> :last-child {
			margin-block-end: 0;
		}

		:root :where(.is-layout-flow)>* {
			margin-block-start: 24px;
			margin-block-end: 0;
		}

		:root :where(.is-layout-constrained)> :first-child {
			margin-block-start: 0;
		}

		:root :where(.is-layout-constrained)> :last-child {
			margin-block-end: 0;
		}

		:root :where(.is-layout-constrained)>* {
			margin-block-start: 24px;
			margin-block-end: 0;
		}

		:root :where(.is-layout-flex) {
			gap: 24px;
		}

		:root :where(.is-layout-grid) {
			gap: 24px;
		}

		.is-layout-flow>.alignleft {
			float: left;
			margin-inline-start: 0;
			margin-inline-end: 2em;
		}

		.is-layout-flow>.alignright {
			float: right;
			margin-inline-start: 2em;
			margin-inline-end: 0;
		}

		.is-layout-flow>.aligncenter {
			margin-left: auto !important;
			margin-right: auto !important;
		}

		.is-layout-constrained>.alignleft {
			float: left;
			margin-inline-start: 0;
			margin-inline-end: 2em;
		}

		.is-layout-constrained>.alignright {
			float: right;
			margin-inline-start: 2em;
			margin-inline-end: 0;
		}

		.is-layout-constrained>.aligncenter {
			margin-left: auto !important;
			margin-right: auto !important;
		}

		.is-layout-constrained> :where(:not(.alignleft):not(.alignright):not(.alignfull)) {
			max-width: var(--wp--style--global--content-size);
			margin-left: auto !important;
			margin-right: auto !important;
		}

		.is-layout-constrained>.alignwide {
			max-width: var(--wp--style--global--wide-size);
		}

		body .is-layout-flex {
			display: flex;
		}

		.is-layout-flex {
			flex-wrap: wrap;
			align-items: center;
		}

		.is-layout-flex> :is(*, div) {
			margin: 0;
		}

		body .is-layout-grid {
			display: grid;
		}

		.is-layout-grid> :is(*, div) {
			margin: 0;
		}

		body {
			padding-top: 0px;
			padding-right: 0px;
			padding-bottom: 0px;
			padding-left: 0px;
		}

		:root :where(.wp-element-button, .wp-block-button__link) {
			background-color: #32373c;
			border-width: 0;
			color: #fff;
			font-family: inherit;
			font-size: inherit;
			font-style: inherit;
			font-weight: inherit;
			letter-spacing: inherit;
			line-height: inherit;
			padding-top: calc(0.667em + 2px);
			padding-right: calc(1.333em + 2px);
			padding-bottom: calc(0.667em + 2px);
			padding-left: calc(1.333em + 2px);
			text-decoration: none;
			text-transform: inherit;
		}

		.has-black-color {
			color: var(--wp--preset--color--black) !important;
		}

		.has-cyan-bluish-gray-color {
			color: var(--wp--preset--color--cyan-bluish-gray) !important;
		}

		.has-white-color {
			color: var(--wp--preset--color--white) !important;
		}

		.has-pale-pink-color {
			color: var(--wp--preset--color--pale-pink) !important;
		}

		.has-vivid-red-color {
			color: var(--wp--preset--color--vivid-red) !important;
		}

		.has-luminous-vivid-orange-color {
			color: var(--wp--preset--color--luminous-vivid-orange) !important;
		}

		.has-luminous-vivid-amber-color {
			color: var(--wp--preset--color--luminous-vivid-amber) !important;
		}

		.has-light-green-cyan-color {
			color: var(--wp--preset--color--light-green-cyan) !important;
		}

		.has-vivid-green-cyan-color {
			color: var(--wp--preset--color--vivid-green-cyan) !important;
		}

		.has-pale-cyan-blue-color {
			color: var(--wp--preset--color--pale-cyan-blue) !important;
		}

		.has-vivid-cyan-blue-color {
			color: var(--wp--preset--color--vivid-cyan-blue) !important;
		}

		.has-vivid-purple-color {
			color: var(--wp--preset--color--vivid-purple) !important;
		}

		.has-black-background-color {
			background-color: var(--wp--preset--color--black) !important;
		}

		.has-cyan-bluish-gray-background-color {
			background-color: var(--wp--preset--color--cyan-bluish-gray) !important;
		}

		.has-white-background-color {
			background-color: var(--wp--preset--color--white) !important;
		}

		.has-pale-pink-background-color {
			background-color: var(--wp--preset--color--pale-pink) !important;
		}

		.has-vivid-red-background-color {
			background-color: var(--wp--preset--color--vivid-red) !important;
		}

		.has-luminous-vivid-orange-background-color {
			background-color: var(--wp--preset--color--luminous-vivid-orange) !important;
		}

		.has-luminous-vivid-amber-background-color {
			background-color: var(--wp--preset--color--luminous-vivid-amber) !important;
		}

		.has-light-green-cyan-background-color {
			background-color: var(--wp--preset--color--light-green-cyan) !important;
		}

		.has-vivid-green-cyan-background-color {
			background-color: var(--wp--preset--color--vivid-green-cyan) !important;
		}

		.has-pale-cyan-blue-background-color {
			background-color: var(--wp--preset--color--pale-cyan-blue) !important;
		}

		.has-vivid-cyan-blue-background-color {
			background-color: var(--wp--preset--color--vivid-cyan-blue) !important;
		}

		.has-vivid-purple-background-color {
			background-color: var(--wp--preset--color--vivid-purple) !important;
		}

		.has-black-border-color {
			border-color: var(--wp--preset--color--black) !important;
		}

		.has-cyan-bluish-gray-border-color {
			border-color: var(--wp--preset--color--cyan-bluish-gray) !important;
		}

		.has-white-border-color {
			border-color: var(--wp--preset--color--white) !important;
		}

		.has-pale-pink-border-color {
			border-color: var(--wp--preset--color--pale-pink) !important;
		}

		.has-vivid-red-border-color {
			border-color: var(--wp--preset--color--vivid-red) !important;
		}

		.has-luminous-vivid-orange-border-color {
			border-color: var(--wp--preset--color--luminous-vivid-orange) !important;
		}

		.has-luminous-vivid-amber-border-color {
			border-color: var(--wp--preset--color--luminous-vivid-amber) !important;
		}

		.has-light-green-cyan-border-color {
			border-color: var(--wp--preset--color--light-green-cyan) !important;
		}

		.has-vivid-green-cyan-border-color {
			border-color: var(--wp--preset--color--vivid-green-cyan) !important;
		}

		.has-pale-cyan-blue-border-color {
			border-color: var(--wp--preset--color--pale-cyan-blue) !important;
		}

		.has-vivid-cyan-blue-border-color {
			border-color: var(--wp--preset--color--vivid-cyan-blue) !important;
		}

		.has-vivid-purple-border-color {
			border-color: var(--wp--preset--color--vivid-purple) !important;
		}

		.has-vivid-cyan-blue-to-vivid-purple-gradient-background {
			background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;
		}

		.has-light-green-cyan-to-vivid-green-cyan-gradient-background {
			background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;
		}

		.has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background {
			background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;
		}

		.has-luminous-vivid-orange-to-vivid-red-gradient-background {
			background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;
		}

		.has-very-light-gray-to-cyan-bluish-gray-gradient-background {
			background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;
		}

		.has-cool-to-warm-spectrum-gradient-background {
			background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;
		}

		.has-blush-light-purple-gradient-background {
			background: var(--wp--preset--gradient--blush-light-purple) !important;
		}

		.has-blush-bordeaux-gradient-background {
			background: var(--wp--preset--gradient--blush-bordeaux) !important;
		}

		.has-luminous-dusk-gradient-background {
			background: var(--wp--preset--gradient--luminous-dusk) !important;
		}

		.has-pale-ocean-gradient-background {
			background: var(--wp--preset--gradient--pale-ocean) !important;
		}

		.has-electric-grass-gradient-background {
			background: var(--wp--preset--gradient--electric-grass) !important;
		}

		.has-midnight-gradient-background {
			background: var(--wp--preset--gradient--midnight) !important;
		}

		.has-small-font-size {
			font-size: var(--wp--preset--font-size--small) !important;
		}

		.has-medium-font-size {
			font-size: var(--wp--preset--font-size--medium) !important;
		}

		.has-large-font-size {
			font-size: var(--wp--preset--font-size--large) !important;
		}

		.has-x-large-font-size {
			font-size: var(--wp--preset--font-size--x-large) !important;
		}

		/*# sourceURL=global-styles-inline-css */
	</style>

	<link rel='stylesheet' id='exad-main-style-css' href='/assets/vintage/vendor/exad-styles.min.css?ver=7.1'
		media='all' />
	<link rel='stylesheet' id='cui_style-css' href='/assets/vintage/vendor/cui_style.css?ver=1.0.0' media='screen' />
	<style id="cui_style-inline-css">
		.cui-wrapper {
			font-size: 14px
		}

		.cui-post-author {
			color: white !important;
			background: #777 !important;
		}

		.cui-wrapper ul.cui-container-comments li.cui-item-comment .cui-comment-avatar img {
			max-width: 28px;
			max-height: 28px;
		}

		.cui-wrapper ul.cui-container-comments li.cui-item-comment .cui-comment-content {
			margin-left: 38px;
		}

		.cui-wrapper ul.cui-container-comments li.cui-item-comment ul .cui-comment-avatar img {
			max-width: 24px;
			max-height: 24px;
		}

		.cui-wrapper ul.cui-container-comments li.cui-item-comment ul ul .cui-comment-avatar img {
			max-width: 21px;
			max-height: 21px;
		}

		.cui_comment_count_card.cui_card-tidak_hadir {
			background-color: #d90a11;
		}

		.cui_comment_count_card.cui_card-hadir {
			background-color: #3D9A62;
		}

		.cui_comment_count_card.cui_card-masih_ragu {
			background-color: #d7a916;
		}

		/*# sourceURL=cui_style-inline-css */
	</style>
	<link rel='stylesheet' id='hello-elementor-css' href='/assets/vintage/vendor/reset.css?ver=3.5.1' media='all' />
	<link rel='stylesheet' id='hello-elementor-theme-style-css' href='/assets/vintage/vendor/theme.css?ver=3.5.1'
		media='all' />
	<link rel='stylesheet' id='hello-elementor-header-footer-css'
		href='/assets/vintage/vendor/header-footer.css?ver=3.5.1' media='all' />
	<link rel='stylesheet' id='elementor-frontend-css' href='/assets/vintage/vendor/frontend.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='elementor-post-7-css' href='/assets/vintage/vendor/post-7.css?ver=1786338438'
		media='all' />
	<link rel='stylesheet' id='weddingpress-wdp-css' href='/assets/vintage/vendor/wdp.css?ver=3.1.12' media='all' />
	<link rel='stylesheet' id='kirim-kit-css' href='/assets/vintage/vendor/guest-book.css?ver=3.1.12' media='all' />
	<link rel='stylesheet' id='widget-heading-css' href='/assets/vintage/vendor/widget-heading.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='e-sticky-css' href='/assets/vintage/vendor/sticky.min.css?ver=3.29.2' media='all' />
	<link rel='stylesheet' id='widget-spacer-css' href='/assets/vintage/vendor/widget-spacer.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='widget-lottie-css' href='/assets/vintage/vendor/widget-lottie.min.css?ver=3.29.2'
		media='all' />
	<link rel='stylesheet' id='e-animation-zoomIn-css' href='/assets/vintage/vendor/zoomIn.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='e-animation-fadeInDown-css' href='/assets/vintage/vendor/fadeInDown.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='widget-image-css' href='/assets/vintage/vendor/widget-image.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='widget-social-icons-css'
		href='/assets/vintage/vendor/widget-social-icons.min.css?ver=3.30.3' media='all' />
	<link rel='stylesheet' id='e-apple-webkit-css' href='/assets/vintage/vendor/apple-webkit.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='swiper-css' href='/assets/vintage/vendor/swiper.min.css?ver=8.4.5' media='all' />
	<link rel='stylesheet' id='e-swiper-css' href='/assets/vintage/vendor/e-swiper.min.css?ver=3.30.3' media='all' />
	<link rel='stylesheet' id='e-animation-fadeInUp-css' href='/assets/vintage/vendor/fadeInUp.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='e-animation-fadeInLeft-css' href='/assets/vintage/vendor/fadeInLeft.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='e-animation-fadeInRight-css' href='/assets/vintage/vendor/fadeInRight.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='widget-divider-css' href='/assets/vintage/vendor/widget-divider.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='widget-video-css' href='/assets/vintage/vendor/widget-video.min.css?ver=3.30.3'
		media='all' />
	<link rel='stylesheet' id='widget-image-carousel-css'
		href='/assets/vintage/vendor/widget-image-carousel.min.css?ver=3.30.3' media='all' />
	<link rel='stylesheet' id='widget-gallery-css' href='/assets/vintage/vendor/widget-gallery.min.css?ver=3.29.2'
		media='all' />
	<link rel='stylesheet' id='elementor-gallery-css' href='/assets/vintage/vendor/e-gallery.min.css?ver=1.2.0'
		media='all' />
	<link rel='stylesheet' id='e-transitions-css' href='/assets/vintage/vendor/transitions.min.css?ver=3.29.2'
		media='all' />
	<link rel='stylesheet' id='widget-nested-accordion-css'
		href='/assets/vintage/vendor/widget-nested-accordion.min.css?ver=3.30.3' media='all' />
	<link rel='stylesheet' id='elementor-post-31261-css' href='/assets/vintage/vendor/post-31261.css?ver=1786348977'
		media='all' />
	<link rel='stylesheet' id='master-addons-main-style-css'
		href='/assets/vintage/vendor/master-addons-styles.css?ver=7.1' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-roboto-css' href='/assets/vintage/vendor/roboto.css?ver=1766104684'
		media='all' />
	<link rel='stylesheet' id='elementor-gf-local-robotoslab-css'
		href='/assets/vintage/vendor/robotoslab.css?ver=1766105970' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-caudex-css' href='/assets/vintage/vendor/caudex.css?ver=1766104746'
		media='all' />
	<link rel='stylesheet' id='elementor-gf-local-cormorantgaramond-css'
		href='/assets/vintage/vendor/cormorantgaramond.css?ver=1766104723' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-librecaslondisplay-css'
		href='/assets/vintage/vendor/librecaslondisplay.css?ver=1766104747' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-ebgaramond-css'
		href='/assets/vintage/vendor/ebgaramond.css?ver=1766157374' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-montecarlo-css'
		href='/assets/vintage/vendor/montecarlo.css?ver=1777812950' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-playfairdisplay-css'
		href='/assets/vintage/vendor/playfairdisplay.css?ver=1769409548' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-aboreto-css' href='/assets/vintage/vendor/aboreto.css?ver=1766104750'
		media='all' />
	<link rel='stylesheet' id='elementor-gf-local-monsieurladoulaise-css'
		href='/assets/vintage/vendor/monsieurladoulaise.css?ver=1777812876' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-cormorantinfant-css'
		href='/assets/vintage/vendor/cormorantinfant.css?ver=1766115803' media='all' />
	<link rel='stylesheet' id='elementor-gf-local-catamaran-css'
		href='/assets/vintage/vendor/catamaran.css?ver=1766104750' media='all' />
	<link rel='stylesheet' id='elementor-icons-shared-1-css'
		href='/assets/vintage/vendor/iconic-font.min.css?ver=2.0.7.5' media='all' />
	<link rel='stylesheet' id='elementor-icons-iconic-fonts-css'
		href='/assets/vintage/vendor/iconic-font.min.css?ver=2.0.7.5' media='all' />
	<script id="jquery-core-js-before">
		/* < ![CDATA[ */
		function jltmaNS(n) { for (var e = n.split("."), a = window, i = "", r = e.length, t = 0; r > t; t++)"window" != e[t] && (i = e[t], a[i] = a[i] || {}, a = a[i]); return a; }
		/* ]]> */
		//# sourceURL=jquery-core-js-before
	</script>
	<script id="jquery-core-js" src="/assets/vintage/vendor/jquery.min.js?ver=3.7.1"></script>
	<script id="jquery-migrate-js" src="/assets/vintage/vendor/jquery-migrate.min.js?ver=3.4.1"></script>
	<link rel="alternate" title="JSON" type="application/json"
		href="https://ourinvidigi.com/wp-json/wp/v2/pages/31261" />
	<link rel='shortlink' href='https://ourinvidigi.com/?p=31261' />
	<meta name="format-detection" content="telephone=no">
	<meta name="google" content="notranslate" />
	<meta name="generator"
		content="Elementor 3.30.3; features: e_font_icon_svg, additional_custom_breakpoints; settings: css_print_method-external, google_font-enabled, font_display-swap">
	<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
	<link rel="icon" href="/assets/vintage/vendor/invidigi-logo-150x150.png" sizes="32x32" />
	<link rel="icon" href="/assets/vintage/vendor/invidigi-logo-300x300.png" sizes="192x192" />
	<link rel="apple-touch-icon" href="/assets/vintage/vendor/invidigi-logo-300x300.png" />
	<meta name="msapplication-TileImage" content="/assets/vintage/vendor/invidigi-logo-300x300.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
	<style id="custom-vintage-fixes">
		@if(!$backsoundUrl)
		/* Backsound dimatikan dari Settings → Musik. Tombol speakernya ikut
		   disembunyikan; elemen <audio>-nya sendiri dibiarkan ada (tanpa
		   <source>) supaya skrip bawaan yang mencari `#song` tetap jalan. */
		.elementor-element-810bc20 {
			display: none !important;
		}
		@endif

		/* Desktop Split Layout: Left 66% Fixed, Right 33% Scrollable */
		@media (min-width: 768px) {
			body.wp-singular {
				overflow-x: hidden !important;
			}

			.elementor-element-6cb022cb {
				min-height: 100vh !important;
			}

			.elementor-element-6cb022cb>.elementor-container {
				align-items: flex-start !important;
			}

			.elementor-element-620cb027 {
				position: fixed !important;
				top: 0 !important;
				left: 0 !important;
				width: 66.666% !important;
				height: 100vh !important;
				z-index: 10 !important;
				overflow: hidden !important;
			}

			.elementor-element-4f93adc7 {
				margin-left: 66.666% !important;
				width: 33.334% !important;
				position: relative !important;
				z-index: 20 !important;
			}
		}

		/* Ensure background slideshow in countdown section is visible */
		.elementor-element-79047782 {
			background-image: url('{{ $template->getAssetUrl('bg_slide_1', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_35_26-AM.jpg') }}') !important;
			background-size: cover !important;
			background-position: center top !important;
			position: relative !important;
		}

		.elementor-element-79047782::before {
			content: '';
			position: absolute;
			inset: 0;
			background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.85) 100%) !important;
			pointer-events: none;
			z-index: 0;
		}

		.elementor-element-79047782>* {
			position: relative;
			z-index: 1;
		}

		/* Ensure Best Wishes / Comments Form is visible */
		.cui-wrap-comments,
		#cui-wrap-commnent-31261 {
			display: block !important;
			opacity: 1 !important;
			visibility: visible !important;
		}

		.cui-wrap-form {
			display: block !important;
		}

		/* Plugin menampilkan daftar ucapan lewat AJAX WordPress (dan bisa
		   menyembunyikannya lagi). Di sini daftarnya dirender langsung dari
		   database, jadi dipastikan selalu tampil. */
		.cui-wrapper ul.cui-container-comments {
			display: block !important;
		}

		/* `max-height:30vh` + `overflow-y:scroll` bawaan plugin memotong daftar
		   ucapan (dan menampilkan track scrollbar kosong), sedangkan tinggi
		   daftar sudah diatur `.wdp-wishes__scroll`. */
		.cui-box {
			max-height: none !important;
			overflow: visible !important;
		}

		/* Tautan "N Ucapan" bawaan plugin: kliknya memakai `slideToggle` ke
		   `#cui-wrap-commnent-*`, jadi satu klik bisa melipat seluruh form
		   konfirmasi + daftar ucapan. Angkanya sudah ditampilkan lewat
		   `.wdp-wishes__head`, jadi tautan ini disembunyikan. */
		.cui-wrapper .cui-wrap-link {
			display: none;
		}

		/* Tanpa avatar, indentasi 38px milik plugin membuat ucapan menjorok. */
		.cui-box ul.cui-container-comments li.cui-item-comment .cui-comment-content {
			margin-left: 0;
			padding-bottom: .9em;
		}

		/* Plugin hanya menata `a.cui-commenter-name`; di sini namanya <span>
		   (tanpa link ke situs tamu), jadi gaya yang sama ditulis ulang supaya
		   tetap senada emas template. Rantai selectornya disamakan dengan
		   bawaan plugin agar benar-benar menang, bukan sekadar ditimpa. */
		.cui-box ul.cui-container-comments li.cui-item-comment .cui-comment-content .cui-comment-info .cui-commenter-name {
			color: #DABE81;
			font-family: 'Catamaran', sans-serif;
			font-weight: 500;
			font-size: 14px;
		}

		.cui-box ul.cui-container-comments li.cui-item-comment .cui-comment-content .cui-comment-info .cui-comment-time {
			font-size: .72em;
			color: #B0B0B0;
			padding-left: 0;
		}

		/* Fallback visibility for entrance animation elements */
		.elementor-invisible.is-visible,
		.animated {
			visibility: visible !important;
		}

		/* Details Accordion Styles */
		.e-n-accordion details summary {
			cursor: pointer;
			user-select: none;
		}

		/* Configurable section backgrounds (defaults keep the original artwork) */
		.elementor-element-14b9e42d,
		.elementor-element-3bf6678f,
		.elementor-element-3cda51a5 {
			background-image: url('{{ $template->getAssetUrl('bg_cover', 'assets/vintage/vendor/BG-COVER-VIN-2-FIX.jpg') }}') !important;
		}

		/* Front cover shown on desktop (the fixed left column, "THE WEDDING OF") */
		.elementor-element-3c9f4603 {
			background-image: url('{{ $template->getAssetUrl('desktop_cover', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_40_16-AM.jpg') }}') !important;
			background-size: cover !important;
			background-position: center center !important;
		}

		.elementor-element-239ae875,
		.elementor-element-4a08c6be,
		.elementor-element-6a4cb199,
		.elementor-element-9d26140 {
			background-image: url('{{ $template->getAssetUrl('bg_paper', 'assets/vintage/vendor/PAPER-BG-FLORAL-Q.jpg') }}') !important;
		}

		.elementor-element-26965d50,
		.elementor-element-501b5057,
		.elementor-element-54904115,
		.elementor-element-68e309a1 {
			background-image: url('{{ $template->getAssetUrl('bg_section', 'assets/vintage/vendor/BG-VIIN-2.jpg') }}') !important;
		}

		.elementor-element-62e96db2 {
			background-image: url('{{ $template->getAssetUrl('gift_card_bg', 'assets/vintage/vendor/ATC-CARD-1.jpg') }}') !important;
		}

		.elementor-element-3ca3dd75 .overlayy {
			background-image: url('{{ $template->getAssetUrl('modal_overlay', 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg') }}') !important;
		}

		/* Foto sampul pembuka ("BUKA UNDANGAN"). Script bawaan template mengisi
   data-sampul yang kosong, jadi background-nya ditetapkan di sini. */
		.elementor-element-3ca3dd75 .modalx {
			background-image: url('{{ $template->getAssetUrl('cover_photo', 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg') }}') !important;
			background-size: cover !important;
			background-position: center center !important;
			background-repeat: no-repeat !important;
		}

		@if(!empty($template->assets_config['cover_photo'] ?? null))
			/* Foto sampul ada: lapisan atas dibuat hanya peredup supaya fotonya terlihat */
			.elementor-element-3ca3dd75 .overlayy {
				background-image: none !important;
				opacity: .35 !important;
			}

		@endif .elementor-element-79bcddc0::before {
			background-image: url('{{ $template->getAssetUrl('bg_all', 'assets/vintage/vendor/BG-ALL-VIN-2.jpg') }}') !important;
		}

		.elementor-element-4f93adc7>.elementor-widget-wrap {
			background-image: url('{{ $template->getAssetUrl('bg_plain_paper', 'assets/vintage/vendor/paper-plos-p-1.jpg') }}') !important;
		}

		/* ============ Gallery: Slide / Grid + Lightbox ============ */
		.wdp-gallery-switch {
			display: flex;
			justify-content: center;
			align-items: center;
			gap: 8px;
			margin: 16px auto 10px;
			position: relative;
			z-index: 5;
		}

		.wdp-gallery-switch button {
			border: 1px solid #AF976A;
			background: transparent;
			color: #AF976A;
			border-radius: 999px;
			padding: 8px 24px;
			font-size: 13px;
			letter-spacing: .6px;
			line-height: 1.4;
			cursor: pointer;
			opacity: .7;
			transition: opacity .25s ease, background-color .25s ease;
		}

		.wdp-gallery-switch button:hover {
			opacity: 1;
			background: rgba(175, 151, 106, .1);
		}

		.wdp-gallery-switch button.is-active {
			opacity: 1;
			font-weight: 600;
			background: #AF976A;
			color: #fff;
		}

		/* Panel Grid harus selebar section supaya fotonya tidak menyusut. */
		.wdp-gallery-panel {
			width: 100% !important;
			max-width: 100% !important;
		}

		.wdp-gallery-panel[hidden] {
			display: none !important;
		}

		/* Panah navigasi mode Slide — dipakai untuk pindah foto tanpa membuka
		   lightbox. Diposisikan relatif ke panel Slide (bukan ke `.swiper`)
		   supaya tidak ikut terpotong `overflow` bawaan carousel. */
		.wdp-gallery-panel[data-gallery-panel="slide"] {
			position: relative;
		}

		.wdp-gallery-nav {
			position: absolute;
			/* Panel ini juga memuat titik pagination di bawah, jadi titik
			   tengahnya digeser sedikit ke atas agar pas di tengah foto. */
			top: calc(50% - 12px);
			transform: translateY(-50%);
			z-index: 6;
			width: 34px;
			height: 34px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 0;
			border: 1px solid rgba(255, 241, 212, .5);
			border-radius: 50%;
			background: rgba(0, 0, 0, .35);
			color: #FFF1D4;
			font-size: 1.3rem;
			line-height: 1;
			cursor: pointer;
			transition: background .25s ease, border-color .25s ease;
		}

		.wdp-gallery-nav:hover,
		.wdp-gallery-nav:focus-visible {
			background: rgba(0, 0, 0, .6);
			border-color: #FFF1D4;
		}

		.wdp-gallery-nav--prev {
			left: 8px;
		}

		.wdp-gallery-nav--next {
			right: 8px;
		}

		@media (max-width: 767px) {
			.wdp-gallery-nav {
				width: 30px;
				height: 30px;
				font-size: 1.15rem;
			}
		}

		/* Tetap 3 kolom, tapi lebar section dipakai penuh (tanpa padding samping
		   dan gap sekecil mungkin) supaya tiap foto dapat lebar maksimal. */
		.wdp-gallery-grid {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 6px;
			width: 100%;
			box-sizing: border-box;
			padding: 0;
		}

		/* Foto di galeri ini portrait, jadi rasionya dibuat 3:4 (bukan 1:1).
		   Dengan crop portrait, tinggi foto di 3 kolom ikut bertambah sehingga
		   isinya terlihat besar, bukan kotak kecil. */
		.wdp-gallery-thumb {
			padding: 0;
			margin: 0;
			border: 0;
			display: block;
			width: 100%;
			aspect-ratio: 3 / 4;
			border-radius: 10px;
			overflow: hidden;
			background: rgba(0, 0, 0, .08);
			cursor: zoom-in;
			position: relative;
		}

		.wdp-gallery-thumb img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
			transition: transform .4s ease;
		}

		.wdp-gallery-thumb:hover img,
		.wdp-gallery-thumb:focus-visible img {
			transform: scale(1.07);
		}

		/* Foto di mode Slide juga bisa diklik untuk membuka lightbox, jadi
		   kursor dan efek hover-nya disamakan dengan thumb di mode Grid. */
		.elementor-image-carousel .swiper-slide[data-gallery-index] {
			cursor: zoom-in;
		}

		.elementor-image-carousel .swiper-slide[data-gallery-index] .swiper-slide-image {
			transition: transform .4s ease;
		}

		.elementor-image-carousel .swiper-slide[data-gallery-index]:hover .swiper-slide-image {
			transform: scale(1.04);
		}

		.wdp-lightbox {
			position: fixed;
			inset: 0;
			z-index: 99999;
			display: none;
			background: rgba(0, 0, 0, .93);
		}

		.wdp-lightbox.is-open {
			display: flex;
			flex-direction: column;
		}

		.wdp-lightbox__bar {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 10px;
			padding: 12px 16px;
			color: #fff;
			font-size: 13px;
		}

		.wdp-lightbox__caption {
			flex: 1;
			text-align: center;
			opacity: .85;
		}

		.wdp-lightbox__stage {
			flex: 1;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 12px;
			min-height: 0;
			padding: 0 12px 12px;
			overflow: hidden;
		}

		.wdp-lightbox__stage img {
			flex: 1 1 auto;
			min-width: 0;
			width: auto;
			max-width: 100%;
			max-height: 100%;
			object-fit: contain;
			border-radius: 6px;
			box-shadow: 0 10px 40px rgba(0, 0, 0, .5);
		}

		.wdp-lightbox__counter {
			opacity: .8;
			font-variant-numeric: tabular-nums;
		}

		.wdp-lightbox button {
			border: 1px solid rgba(255, 255, 255, .45);
			background: rgba(255, 255, 255, .12);
			color: #fff;
			border-radius: 50%;
			width: 42px;
			height: 42px;
			font-size: 16px;
			line-height: 1;
			cursor: pointer;
			flex: 0 0 auto;
		}

		.wdp-lightbox button:hover {
			background: rgba(255, 255, 255, .25);
		}

		/* ============ Footer / Penutup ============ */
		/* Di desktop kolom kiri (66.666%) dipasang `position:fixed`, jadi footer
		   diberi lebar sisa 33.334% di kanan supaya tidak tertutup kolom itu. */
		.wdp-footer-section {
			position: relative;
			z-index: 30;
			background-color: #1a1a1a;
			padding: 42px 24px;
			text-align: center;
			box-sizing: border-box;
		}

		@media (min-width: 768px) {
			.wdp-footer-section {
				margin-left: 66.666%;
				width: 33.334%;
			}
		}

		.wdp-footer-section .wdp-footer-eyebrow {
			font-family: 'Caudex', serif;
			font-size: 1.05rem;
			letter-spacing: 2px;
			color: #AF976A;
			margin-bottom: 8px;
		}

		.wdp-footer-section .wdp-footer-couple {
			font-family: 'Playfair Display', serif;
			font-size: 1.75rem;
			color: #fff;
			margin-bottom: 12px;
		}

		.wdp-footer-section .wdp-footer-thanks {
			font-family: 'Cormorant Garamond', serif;
			font-size: .9rem;
			line-height: 1.7;
			color: #c9c1b6;
			margin-bottom: 18px;
		}

		.wdp-footer-section .wdp-footer-copy {
			font-size: .72rem;
			color: #6f6f6f;
			border-top: 1px solid rgba(175, 151, 106, .3);
			padding-top: 16px;
			margin-bottom: 0;
		}

		/* ============ Best Wishes: daftar ucapan tamu ============ */
		.cui-box .wdp-wishes__head {
			font-size: .8rem;
			font-weight: 600;
			color: #FFF1D4;
			margin-bottom: 8px;
		}

		.cui-box .wdp-wishes__scroll {
			max-height: 320px;
			overflow-y: auto;
			-webkit-overflow-scrolling: touch;
		}

		.cui-box .wdp-wishes__scroll.is-expanded {
			max-height: none;
			overflow-y: visible;
		}

		.cui-box .wdp-wishes__status,
		.cui-box .wdp-wishes__empty {
			font-size: .75rem;
			color: #FFF1D4;
			opacity: .8;
			text-align: center;
			padding: 6px 0;
		}


		.cui-box .wdp-wishes__more {
			width: 100%;
			margin-top: 10px;
			padding: 8px 16px;
			border: 1px solid var(--confirm-border-color, #FFF1D4);
			border-radius: 50px;
			background: transparent;
			color: var(--warna-teks-akan-hadir, #FFF1D4);
			font-size: .8rem;
			cursor: pointer;
		}

		.cui-box .wdp-wishes__more:hover {
			background: rgba(255, 241, 212, .12);
		}

		/* Section visibility toggles controlled from Dashboard > Settings > Teks.

   Semua section ini saudara (anak langsung dari 79bcddc0), jadi satu ID
   hanya mengenai satu section:

     3cda51a5  Our Story            501b5057  Gallery (+ video)
     5236da65  Countdown            54904115  Best Wishes (komentar)
     6a4cb199  Wedding Gift         4a08c6be  Live Moment
     239ae875  Dresscode

   Hati-hati: 7f6eb8cc adalah INDUK dari 6a4cb199 + 4a08c6be + 239ae875.
   Memakainya untuk toggle hadiah akan ikut menyembunyikan Live Moment dan
   Dresscode — karena itu toggle hadiah memakai 6a4cb199 (kartu hadiah saja).

   Toggle per-foto memakai 4acbe1ca / 1775694c / 4ce5666c (lihat daftar
   di bawah), dipakai kalau hanya fotonya yang ingin disembunyikan. */
		@if(($showBridePhoto ?? '1') === '0')
			.elementor-element-4acbe1ca {
				display: none !important;
			}

		@endif

		@if(($showGroomPhoto ?? '1') === '0')
			.elementor-element-1775694c {
				display: none !important;
			}

		@endif

		@if(($showStoryImage ?? '1') === '0')
			.elementor-element-4ce5666c {
				display: none !important;
			}

		@endif

		@if(($showStory ?? '1') === '0')
			.elementor-element-3cda51a5 {
				display: none !important;
			}

		@endif

		@if(($showGallery ?? '1') === '0')
			.elementor-element-501b5057 {
				display: none !important;
			}

		@endif

		@if(($showCountdown ?? '1') === '0')
			.elementor-element-5236da65 {
				display: none !important;
			}

		@endif

		@if(($showGift ?? '1') === '0')
			.elementor-element-6a4cb199 {
				display: none !important;
			}

		@endif

		@if(($showLive ?? '1') === '0')
			.elementor-element-4a08c6be {
				display: none !important;
			}

		@endif

		@if(($showDresscode ?? '1') === '0')
			.elementor-element-239ae875 {
				display: none !important;
			}

		@endif

		@if(($showBestWishes ?? '1') === '0')
			.elementor-element-54904115 {
				display: none !important;
			}

		@endif

		/* Our Story Step-by-Step Timeline */
		.story-timeline {
			position: relative;
			padding: 20px 0;
		}

		.story-timeline::before {
			content: '';
			position: absolute;
			left: 50%;
			top: 0;
			bottom: 0;
			width: 2px;
			background: linear-gradient(to bottom, transparent, #AF976A 10%, #AF976A 90%, transparent);
			transform: translateX(-50%);
		}

		.story-step {
			position: relative;
			margin-bottom: 40px;
			display: flex;
			align-items: flex-start;
		}

		.story-step:nth-child(odd) {
			flex-direction: row;
			padding-right: calc(50% + 30px);
			text-align: right;
		}

		.story-step:nth-child(even) {
			flex-direction: row-reverse;
			padding-left: calc(50% + 30px);
			text-align: left;
		}

		.story-step-dot {
			position: absolute;
			left: 50%;
			transform: translateX(-50%);
			width: 16px;
			height: 16px;
			background: #AF976A;
			border: 3px solid #F5EDE0;
			border-radius: 50%;
			z-index: 2;
			top: 5px;
		}

		.story-step-content {
			background: rgba(255, 255, 255, 0.9);
			border-radius: 12px;
			padding: 16px 20px;
			box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
			width: 100%;
		}

		.story-step-title {
			font-family: 'Caudex', serif;
			font-weight: 700;
			font-size: 0.95rem;
			color: #AF976A;
			margin-bottom: 6px;
			letter-spacing: 1px;
			text-transform: uppercase;
		}

		.story-step-desc {
			font-family: 'Cormorant Garamond', serif;
			font-size: 0.9rem;
			color: #555;
			line-height: 1.6;
			margin: 0;
		}

		@media (max-width: 600px) {
			.story-timeline::before {
				left: 20px;
			}

			.story-step:nth-child(odd),
			.story-step:nth-child(even) {
				flex-direction: row;
				padding-left: 50px;
				padding-right: 0;
				text-align: left;
			}

			.story-step-dot {
				left: 20px;
			}
		}
	</style>

</head>

<body
	class="wp-singular page-template page-template-elementor_canvas page page-id-31261 wp-embed-responsive wp-theme-hello-elementor hello-elementor-default elementor-default elementor-template-canvas elementor-kit-7 elementor-page elementor-page-31261">
	<div data-elementor-type="wp-page" data-elementor-id="31261" class="elementor elementor-31261"
		data-elementor-post-type="page">
		<section
			class="has_ma_el_bg_slider elementor-section elementor-top-section elementor-element elementor-element-6cb022cb elementor-section-full_width elementor-section-height-min-height elementor-section-items-stretch elementor-section-height-default jltma-glass-effect-no"
			data-id="6cb022cb" data-element_type="section"
			data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
			<div class="elementor-container elementor-column-gap-no">
				<div class="has_ma_el_bg_slider elementor-column elementor-col-66 elementor-top-column elementor-element elementor-element-620cb027 elementor-hidden-mobile jltma-glass-effect-no"
					data-id="620cb027" data-element_type="column">
					<div class="elementor-widget-wrap elementor-element-populated">
						<section data-jltma-particle="{
  &quot;particles&quot;: {
    &quot;number&quot;: {
      &quot;value&quot;: 491,
      &quot;density&quot;: {
        &quot;enable&quot;: true,
        &quot;value_area&quot;: 6012.795228245711
      }
    },
    &quot;color&quot;: {
      &quot;value&quot;: &quot;#ffffff&quot;
    },
    &quot;shape&quot;: {
      &quot;type&quot;: &quot;circle&quot;,
      &quot;stroke&quot;: {
        &quot;width&quot;: 0,
        &quot;color&quot;: &quot;#000000&quot;
      },
      &quot;polygon&quot;: {
        &quot;nb_sides&quot;: 3
      },
      &quot;image&quot;: {
        &quot;src&quot;: &quot;img/github.svg&quot;,
        &quot;width&quot;: 100,
        &quot;height&quot;: 100
      }
    },
    &quot;opacity&quot;: {
      &quot;value&quot;: 0.5524033491425908,
      &quot;random&quot;: true,
      &quot;anim&quot;: {
        &quot;enable&quot;: false,
        &quot;speed&quot;: 1,
        &quot;opacity_min&quot;: 0.1,
        &quot;sync&quot;: false
      }
    },
    &quot;size&quot;: {
      &quot;value&quot;: 10,
      &quot;random&quot;: true,
      &quot;anim&quot;: {
        &quot;enable&quot;: true,
        &quot;speed&quot;: 17,
        &quot;size_min&quot;: 0.1,
        &quot;sync&quot;: false
      }
    },
    &quot;line_linked&quot;: {
      &quot;enable&quot;: false,
      &quot;distance&quot;: 150,
      &quot;color&quot;: &quot;#ffffff&quot;,
      &quot;opacity&quot;: 0.4,
      &quot;width&quot;: 1
    },
    &quot;move&quot;: {
      &quot;enable&quot;: true,
      &quot;speed&quot;: 5,
      &quot;direction&quot;: &quot;bottom&quot;,
      &quot;random&quot;: false,
      &quot;straight&quot;: false,
      &quot;out_mode&quot;: &quot;out&quot;,
      &quot;bounce&quot;: false,
      &quot;attract&quot;: {
        &quot;enable&quot;: true,
        &quot;rotateX&quot;: 2130.6986324071363,
        &quot;rotateY&quot;: 4498.141557303954
      }
    }
  },
  &quot;interactivity&quot;: {
    &quot;detect_on&quot;: &quot;canvas&quot;,
    &quot;events&quot;: {
      &quot;onhover&quot;: {
        &quot;enable&quot;: true,
        &quot;mode&quot;: &quot;repulse&quot;
      },
      &quot;onclick&quot;: {
        &quot;enable&quot;: true,
        &quot;mode&quot;: &quot;push&quot;
      },
      &quot;resize&quot;: true
    },
    &quot;modes&quot;: {
      &quot;grab&quot;: {
        &quot;distance&quot;: 400,
        &quot;line_linked&quot;: {
          &quot;opacity&quot;: 1
        }
      },
      &quot;bubble&quot;: {
        &quot;distance&quot;: 400,
        &quot;size&quot;: 40,
        &quot;duration&quot;: 2,
        &quot;opacity&quot;: 8,
        &quot;speed&quot;: 3
      },
      &quot;repulse&quot;: {
        &quot;distance&quot;: 276.1062521824573,
        &quot;duration&quot;: 0.4
      },
      &quot;push&quot;: {
        &quot;particles_nb&quot;: 4
      },
      &quot;remove&quot;: {
        &quot;particles_nb&quot;: 2
      }
    }
  },
  &quot;retina_detect&quot;: true
}" class="has_ma_el_bg_slider elementor-section elementor-inner-section elementor-element elementor-element-3c9f4603 elementor-section-height-min-height elementor-section-full_width elementor-section-content-middle jltma-particle-yes elementor-section-height-default jltma-glass-effect-no"
							data-id="3c9f4603" data-element_type="section"
							data-settings="{&quot;sticky&quot;:&quot;top&quot;,&quot;background_background&quot;:&quot;classic&quot;,&quot;ma_el_particle_area_zindex&quot;:0,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_offset&quot;:0,&quot;sticky_effects_offset&quot;:0,&quot;sticky_anchor_link_offset&quot;:0}">
							<div class="elementor-background-overlay"></div>
							<div class="elementor-container elementor-column-gap-no">
								<div class="has_ma_el_bg_slider elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-2dfebc70 jltma-glass-effect-no"
									data-id="2dfebc70" data-element_type="column">
									<div class="elementor-widget-wrap elementor-element-populated">
										<div class="elementor-element elementor-element-7e189e9b jltma-glass-effect-no elementor-widget elementor-widget-heading"
											data-id="7e189e9b" data-element_type="widget"
											data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">THE WEDDING OF
												</p>
											</div>
										</div>
										<div class="elementor-element elementor-element-6beaa278 jltma-glass-effect-no elementor-widget elementor-widget-heading"
											data-id="6beaa278" data-element_type="widget"
											data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">
													{{ $coupleName }}</p>
											</div>
										</div>
										<div class="elementor-element elementor-element-7598a89c jltma-glass-effect-no elementor-widget elementor-widget-heading"
											data-id="7598a89c" data-element_type="widget"
											data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">
													{{ $eventDateFormatted }} </p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
						<style>
							.elementor-element-3c9f4603.jltma-particle-wrapper>canvas {
								z-index: 0;
								position: absolute;
								top: 0;
							}
						</style>
					</div>
				</div>
				<div class="has_ma_el_bg_slider elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-4f93adc7 jltma-glass-effect-no"
					data-id="4f93adc7" data-element_type="column"
					data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-79bcddc0 e-con-full e-flex e-con e-parent"
							data-id="79bcddc0" data-element_type="container">
							<div class="elementor-element elementor-element-14b9e42d jltma-particle-yes e-flex e-con-boxed e-con e-child"
								data-id="14b9e42d" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ma_el_particle_area_zindex&quot;:0}">
								<div class="e-con-inner">
									<div class="elementor-element elementor-element-46d44090 e-flex e-con-boxed e-con e-child"
										data-id="46d44090" data-element_type="container">
										<div class="e-con-inner">
											<div class="elementor-element elementor-element-1c2d727a jltma-glass-effect-no elementor-widget elementor-widget-spacer"
												data-id="1c2d727a" data-element_type="widget"
												data-widget_type="spacer.default">
												<div class="elementor-widget-container">
													<div class="elementor-spacer">
														<div class="elementor-spacer-inner"></div>
													</div>
												</div>
											</div>
											<div class="elementor-element elementor-element-2f00288 elementor-absolute elementor-align-center jltma-glass-effect-no elementor-widget elementor-widget-lottie"
												data-id="2f00288" data-element_type="widget"
												data-settings="{&quot;source_json&quot;:{&quot;url&quot;:&quot;/assets/vintage/vendor/animation_lnbjd092.json&quot;,&quot;id&quot;:4368,&quot;size&quot;:&quot;&quot;,&quot;alt&quot;:&quot;&quot;,&quot;source&quot;:&quot;library&quot;},&quot;loop&quot;:&quot;yes&quot;,&quot;play_speed&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:0.6,&quot;sizes&quot;:[]},&quot;_position&quot;:&quot;absolute&quot;,&quot;source&quot;:&quot;media_file&quot;,&quot;caption_source&quot;:&quot;none&quot;,&quot;link_to&quot;:&quot;none&quot;,&quot;trigger&quot;:&quot;arriving_to_viewport&quot;,&quot;viewport&quot;:{&quot;unit&quot;:&quot;%&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:{&quot;start&quot;:0,&quot;end&quot;:100}},&quot;start_point&quot;:{&quot;unit&quot;:&quot;%&quot;,&quot;size&quot;:0,&quot;sizes&quot;:[]},&quot;end_point&quot;:{&quot;unit&quot;:&quot;%&quot;,&quot;size&quot;:100,&quot;sizes&quot;:[]},&quot;renderer&quot;:&quot;svg&quot;}"
												data-widget_type="lottie.default">
												<div class="elementor-widget-container">
													<div class="e-lottie__container">
														<div class="e-lottie__animation"></div>
													</div>
												</div>
											</div>
											<div class="elementor-element elementor-element-48f45861 animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-heading"
												data-id="48f45861" data-element_type="widget"
												data-settings="{&quot;_animation_mobile&quot;:&quot;zoomIn&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:100}"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">THE
														WEDDING OF</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-f9f45a3 animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-heading"
												data-id="f9f45a3" data-element_type="widget"
												data-settings="{&quot;_animation_mobile&quot;:&quot;zoomIn&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:200}"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														{{ $brideFirstName }}</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-1f4d9a7 animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-heading"
												data-id="1f4d9a7" data-element_type="widget"
												data-settings="{&quot;_animation_mobile&quot;:&quot;zoomIn&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:100}"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">and</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-4c648cf animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-heading"
												data-id="4c648cf" data-element_type="widget"
												data-settings="{&quot;_animation_mobile&quot;:&quot;zoomIn&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:200}"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														{{ $groomFirstName }}</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-38a8cfe7 animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-heading"
												data-id="38a8cfe7" data-element_type="widget"
												data-settings="{&quot;_animation_mobile&quot;:&quot;zoomIn&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:100}"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														{{ $eventDateFormatted }} </p>
												</div>
											</div>
											<div class="elementor-element elementor-element-531554fc animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-spacer"
												data-id="531554fc" data-element_type="widget"
												data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:700}"
												data-widget_type="spacer.default">
												<div class="elementor-widget-container">
													<div class="elementor-spacer">
														<div class="elementor-spacer-inner"></div>
													</div>
												</div>
											</div>
											<div class="elementor-element elementor-element-31a1cdee jltma-glass-effect-no elementor-widget elementor-widget-image"
												data-id="31a1cdee" data-element_type="widget"
												data-widget_type="image.default">
												<div class="elementor-widget-container">
													<img decoding="async" width="150" height="150"
														src="{{ $template->getAssetUrl('scroll_gif', 'assets/vintage/vendor/Animation-174404519592-scroll.gif') }}"
														class="attachment-full size-full wp-image-154" alt="" />
												</div>
											</div>
											<div class="elementor-element elementor-element-7c23ebb animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-spacer"
												data-id="7c23ebb" data-element_type="widget"
												data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:700}"
												data-widget_type="spacer.default">
												<div class="elementor-widget-container">
													<div class="elementor-spacer">
														<div class="elementor-spacer-inner"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="elementor-element elementor-element-22d063e elementor-absolute jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="22d063e" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img fetchpriority="high" decoding="async" width="1080" height="528"
												src="{{ $template->getAssetUrl('floral_border', 'assets/vintage/vendor/AhaConvert_BUNGA-VIN-2B.webp') }}"
												class="attachment-full size-full wp-image-31321" alt="" />
										</div>
									</div>
									<div class="elementor-element elementor-element-a3de37c elementor-widget__width-initial elementor-absolute jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="a3de37c" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img decoding="async" width="293" height="1427"
												src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/vendor/HORDENG-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31319" alt="" />
										</div>
									</div>
									<div class="elementor-element elementor-element-9a377ec elementor-widget__width-initial elementor-absolute e-transform jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="9a377ec" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img decoding="async" width="293" height="1427"
												src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/vendor/HORDENG-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31319" alt="" />
										</div>
									</div>
									<div class="elementor-element elementor-element-200f1c1 elementor-widget__width-inherit elementor-absolute goyang-2 jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="200f1c1" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async" width="362" height="430"
												src="{{ $template->getAssetUrl('lamp_decoration', 'assets/vintage/vendor/LAMPU-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31320" alt="" />
										</div>
									</div>
									<div class="elementor-element elementor-element-19ec7dc elementor-widget__width-initial elementor-absolute goyang-2 jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="19ec7dc" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async" width="594" height="684"
												src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31318" alt="" />
										</div>
									</div>
									<div class="elementor-element elementor-element-0bf39cc elementor-widget__width-initial elementor-absolute goyang-2 e-transform jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="0bf39cc" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_flipY_effect&quot;:&quot;transform&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async" width="594" height="684"
												src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31318" alt="" />
										</div>
									</div>
									<div class="elementor-element elementor-element-06c7452 elementor-widget__width-initial elementor-absolute goyang-2 e-transform jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="06c7452" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async" width="594" height="684"
												src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31318" alt="" />
										</div>
									</div>
									<div class="elementor-element elementor-element-e3d17c0 elementor-widget__width-initial elementor-absolute goyang-2 e-transform e-transform jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="e3d17c0" data-element_type="widget"
										data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;,&quot;_transform_flipY_effect&quot;:&quot;transform&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async" width="594" height="684"
												src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31318" alt="" />
										</div>
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-26965d50 e-con-full e-flex e-con e-child"
								data-id="26965d50" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
								<div class="elementor-element elementor-element-27db6cb1 e-flex e-con-boxed e-con e-child"
									data-id="27db6cb1" data-element_type="container">
									<div class="e-con-inner">
										<div class="elementor-element elementor-element-337a0d19 muncul jltma-glass-effect-no elementor-widget elementor-widget-image"
											data-id="337a0d19" data-element_type="widget"
											data-widget_type="image.default">
											<div class="elementor-widget-container">
												<img loading="lazy" decoding="async" src="{{ $heroPhotoUrl }}"
													class="attachment-full size-full wp-image-31411"
													alt="{{ $brideFullName }}">
											</div>
										</div>
									</div>
								</div>
								<div class="elementor-element elementor-element-57933628 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
									data-id="57933628" data-element_type="widget" data-widget_type="heading.default">
									<div class="elementor-widget-container">
										<p class="elementor-heading-title elementor-size-default">
											{{ $template->getSetting('hero_subtitle', 'a journey of love begins') }}</p>
									</div>
								</div>
								<div class="elementor-element elementor-element-2c8c86f4 zoom jltma-glass-effect-no elementor-widget elementor-widget-text-editor"
									data-id="2c8c86f4" data-element_type="widget"
									data-widget_type="text-editor.default">
									<div class="elementor-widget-container">
										<p>{{ $template->getSetting('hero_verse', '"Di antara tanda-tanda (kebesaran)-Nya ialah bahwa Dia menciptakan pasangan-pasangan untukmu dari (jenis) dirimu sendiri agar kamu merasa tenteram kepadanya. Dia menjadikan di antaramu rasa cinta dan kasih sayang. Sesungguhnya pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir."') }}
										</p>
										<p>{{ $template->getSetting('hero_verse_source', '(Q.S Ar-rum 21)') }}</p>
									</div>
								</div>
								<div class="elementor-element elementor-element-f3e1884 zoom jltma-glass-effect-no elementor-widget elementor-widget-image"
									data-id="f3e1884" data-element_type="widget" data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img loading="lazy" decoding="async" width="1080" height="312"
											src="{{ $template->getAssetUrl('divider_image', 'assets/vintage/vendor/DIVIDER-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31469" alt="" />
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-263c2d28 jltma-particle-yes e-flex e-con-boxed e-con e-child"
								data-id="263c2d28" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ma_el_particle_area_zindex&quot;:0}">
								<div class="e-con-inner">
									<div class="elementor-element elementor-element-9d26140 e-flex e-con-boxed e-con e-child"
										data-id="9d26140" data-element_type="container"
										data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
										<div class="e-con-inner">
											<div class="elementor-element elementor-element-3cfaa4e9 zoom jltma-glass-effect-no elementor-widget elementor-widget-image"
												data-id="3cfaa4e9" data-element_type="widget"
												data-widget_type="image.default">
												<div class="elementor-widget-container">
													<img loading="lazy" decoding="async" width="819" height="543"
														src="{{ $template->getAssetUrl('logo_image', 'assets/vintage/vendor/LOGO-VIN-2.png') }}"
														class="attachment-full size-full wp-image-31271" alt=""
														style="width:30% !important;height:auto !important;max-width:30% !important;" />
												</div>
											</div>
											<div class="elementor-element elementor-element-2bc4167c zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="2bc4167c" data-element_type="widget"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														{{ $template->getSetting('couple_subtitle', 'Bride & Groom') }}
													</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-921dae7 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="921dae7" data-element_type="widget"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														Bismillahirrahmanirrahim
														<br>Assalamu'alaikum Warahmatullahi Wabarakatuh
														<br><br>
														Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud
														menyelenggarakan acara pernikahan kami:
													</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-5976164a e-con-full e-flex e-con e-child"
												data-id="5976164a" data-element_type="container">
												<div class="elementor-element elementor-element-4acbe1ca animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
													data-id="4acbe1ca" data-element_type="widget"
													data-settings="{&quot;_animation_delay&quot;:100,&quot;_animation_mobile&quot;:&quot;zoomIn&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}"
													data-widget_type="image.default">
													<div class="elementor-widget-container">
														<img loading="lazy" decoding="async" width="682" height="937"
															src="{{ $bridePhotoUrl }}"
															class="attachment-full size-full wp-image-31420"
															alt="{{ $brideFullName }}"
															sizes="auto, (max-width: 682px) 100vw, 682px" />
													</div>
												</div>
												<div class="elementor-element elementor-element-6fefca18 elementor-widget__width-initial goyang-1 elementor-absolute e-transform jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
													data-id="6fefca18" data-element_type="widget"
													data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;}"
													data-widget_type="image.default">
													<div class="elementor-widget-container">
														<img loading="lazy" decoding="async" width="594" height="684"
															src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
															class="attachment-full size-full wp-image-31318" alt="" />
													</div>
												</div>
											</div>
											<div class="elementor-element elementor-element-66bad551 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="66bad551" data-element_type="widget"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														{{ $brideFullName }}</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-615d00b muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="615d00b" data-element_type="widget"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">Putri
														dari<br>Bapak {{ $brideFather }} &amp; Ibu {{ $brideMother }}
													</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-2e448cb7 elementor-shape-circle zoom elementor-grid-0 e-grid-align-center jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-social-icons"
												data-id="2e448cb7" data-element_type="widget"
												data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:400}"
												data-widget_type="social-icons.default">
												<div class="elementor-widget-container">
													<div class="elementor-social-icons-wrapper elementor-grid">
														<span class="elementor-grid-item">
															<a class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-0f94d9a"
																target="_blank">
																<span class="elementor-screen-only">Instagram</span>
																<svg class="e-font-icon-svg e-fab-instagram"
																	viewBox="0 0 448 512"
																	xmlns="http://www.w3.org/2000/svg">
																	<path
																		d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z">
																	</path>
																</svg> </a>
														</span>
													</div>
												</div>
											</div>
											<div class="elementor-element elementor-element-5cbb6301 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="5cbb6301" data-element_type="widget"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">&amp;</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-17bd1bf3 e-con-full e-flex e-con e-child"
												data-id="17bd1bf3" data-element_type="container">
												<div class="elementor-element elementor-element-1775694c animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
													data-id="1775694c" data-element_type="widget"
													data-settings="{&quot;_animation_delay&quot;:100,&quot;_animation_mobile&quot;:&quot;zoomIn&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;}"
													data-widget_type="image.default">
													<div class="elementor-widget-container">
														<img loading="lazy" decoding="async" width="682" height="937"
															src="{{ $groomPhotoUrl }}"
															class="attachment-full size-full wp-image-31421"
															alt="{{ $groomFullName }}"
															sizes="auto, (max-width: 682px) 100vw, 682px" />
													</div>
												</div>
												<div class="elementor-element elementor-element-24df5666 elementor-widget__width-initial goyang-1 elementor-absolute e-transform jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
													data-id="24df5666" data-element_type="widget"
													data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;}"
													data-widget_type="image.default">
													<div class="elementor-widget-container">
														<img loading="lazy" decoding="async" width="594" height="684"
															src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
															class="attachment-full size-full wp-image-31318" alt="" />
													</div>
												</div>
											</div>
											<div class="elementor-element elementor-element-101b9fc3 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="101b9fc3" data-element_type="widget"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														{{ $groomFullName }}</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-2c9e35ea muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="2c9e35ea" data-element_type="widget"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">Putra
														dari<br>Bapak {{ $groomFather }} &amp; Ibu {{ $groomMother }}
													</p>
												</div>
											</div>
											<div class="elementor-element elementor-element-68d487d7 elementor-shape-circle zoom elementor-grid-0 e-grid-align-center jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-social-icons"
												data-id="68d487d7" data-element_type="widget"
												data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:400}"
												data-widget_type="social-icons.default">
												<div class="elementor-widget-container">
													<div class="elementor-social-icons-wrapper elementor-grid">
														<span class="elementor-grid-item">
															<a class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-0f94d9a"
																target="_blank">
																<span class="elementor-screen-only">Instagram</span>
																<svg class="e-font-icon-svg e-fab-instagram"
																	viewBox="0 0 448 512"
																	xmlns="http://www.w3.org/2000/svg">
																	<path
																		d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z">
																	</path>
																</svg> </a>
														</span>
													</div>
												</div>
											</div>
											<div class="elementor-element elementor-element-186c6709 jltma-glass-effect-no elementor-widget elementor-widget-spacer"
												data-id="186c6709" data-element_type="widget"
												data-widget_type="spacer.default">
												<div class="elementor-widget-container">
													<div class="elementor-spacer">
														<div class="elementor-spacer-inner"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-5236da65 e-con-full e-flex e-con e-child"
								data-id="5236da65" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
								<div class="elementor-element elementor-element-79047782 e-con-full e-flex e-con e-child"
									data-id="79047782" data-element_type="container"
									data-settings="{&quot;background_background&quot;:&quot;slideshow&quot;,&quot;background_slideshow_gallery&quot;:[{&quot;id&quot;:31411,&quot;url&quot;:&quot;{{ $template->getAssetUrl('bg_slide_1', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_35_26-AM.jpg') }}&quot;},{&quot;id&quot;:31412,&quot;url&quot;:&quot;{{ $template->getAssetUrl('bg_slide_2', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_36_39-AM.jpg') }}&quot;}],&quot;background_slideshow_transition_duration&quot;:2000,&quot;background_slideshow_ken_burns&quot;:&quot;yes&quot;,&quot;background_slideshow_loop&quot;:&quot;yes&quot;,&quot;background_slideshow_slide_duration&quot;:5000,&quot;background_slideshow_slide_transition&quot;:&quot;fade&quot;,&quot;background_slideshow_ken_burns_zoom_direction&quot;:&quot;in&quot;}">
									<div class="elementor-element elementor-element-17e9d95e jltma-glass-effect-no elementor-widget elementor-widget-spacer"
										data-id="17e9d95e" data-element_type="widget" data-widget_type="spacer.default">
										<div class="elementor-widget-container">
											<div class="elementor-spacer">
												<div class="elementor-spacer-inner"></div>
											</div>
										</div>
									</div>
									<div class="elementor-element elementor-element-7f1c7633 muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="7f1c7633" data-element_type="widget"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">
												{{ $template->getSetting('countdown_title', 'Counting The Days') }}
											</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-1dab6fd2 zoom jltma-glass-effect-no elementor-widget elementor-widget-weddingpress-countdown"
										data-id="1dab6fd2" data-element_type="widget"
										data-widget_type="weddingpress-countdown.default">
										<div class="elementor-widget-container">

											<div class="wpkoi-elements-countdown-wrapper">
												<div
													class="wpkoi-elements-countdown-container wpkoi-elements-countdown-label-block ">
													<ul id="wpkoi-elements-countdown-1dab6fd2"
														class="wpkoi-elements-countdown-items"
														data-date="{{ $countdownDate }}">
														<li class="wpkoi-elements-countdown-item">
															<div class="wpkoi-elements-countdown-days"><span data-days
																	class="wpkoi-elements-countdown-digits">00</span><span
																	class="wpkoi-elements-countdown-label">Days</span>
															</div>
														</li>
														<li class="wpkoi-elements-countdown-item">
															<div class="wpkoi-elements-countdown-hours"><span data-hours
																	class="wpkoi-elements-countdown-digits">00</span><span
																	class="wpkoi-elements-countdown-label">Hours</span>
															</div>
														</li>
														<li class="wpkoi-elements-countdown-item">
															<div class="wpkoi-elements-countdown-minutes"><span
																	data-minutes
																	class="wpkoi-elements-countdown-digits">00</span><span
																	class="wpkoi-elements-countdown-label">Minutes</span>
															</div>
														</li>
														<li class="wpkoi-elements-countdown-item">
															<div class="wpkoi-elements-countdown-seconds"><span
																	data-seconds
																	class="wpkoi-elements-countdown-digits">00</span><span
																	class="wpkoi-elements-countdown-label">Seconds</span>
															</div>
														</li>
													</ul>
													<div class="clearfix"></div>
												</div>
											</div>


											<script type="text/javascript">
												jQuery(document).ready(function ($) {
													'use strict';
													$("#wpkoi-elements-countdown-1dab6fd2").countdown();
												});
											</script>

										</div>
									</div>
									<div class="elementor-element elementor-element-29cf2155 elementor-align-center elementor-mobile-align-center zoom jltma-glass-effect-no elementor-widget elementor-widget-button"
										data-id="29cf2155" data-element_type="widget" data-widget_type="button.default">
										<div class="elementor-widget-container">
											<div class="elementor-button-wrapper">
												<a class="elementor-button elementor-button-link elementor-size-xs"
													href="https://www.google.com/calendar/render?action=TEMPLATE&#038;text=The%20Wedding%20Of%20&#038;details=The%20Wedding%20Of%20Linda%20&#038;%20Mukhsin&#038;dates=20260524T100000/20260524T150000&#038;location"
													target="_blank" rel="noopener">
													<span class="elementor-button-content-wrapper">
														<span class="elementor-button-icon">
															<i aria-hidden="true" class="im im-save"></i> </span>
														<span class="elementor-button-text">SAVE THE DATE</span>
													</span>
												</a>
											</div>
										</div>
									</div>
									<div class="elementor-element elementor-element-3dbf0170 jltma-glass-effect-no elementor-widget elementor-widget-spacer"
										data-id="3dbf0170" data-element_type="widget" data-widget_type="spacer.default">
										<div class="elementor-widget-container">
											<div class="elementor-spacer">
												<div class="elementor-spacer-inner"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-68e309a1 e-con-full e-flex e-con e-child"
								data-id="68e309a1" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
								<div class="elementor-element elementor-element-463876b2 e-con-full e-flex e-con e-child"
									data-id="463876b2" data-element_type="container">
									<div class="elementor-element elementor-element-17371b9 jltma-glass-effect-no elementor-widget elementor-widget-spacer"
										data-id="17371b9" data-element_type="widget" data-widget_type="spacer.default">
										<div class="elementor-widget-container">
											<div class="elementor-spacer">
												<div class="elementor-spacer-inner"></div>
											</div>
										</div>
									</div>
									<div class="elementor-element elementor-element-338eacc5 muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="338eacc5" data-element_type="widget"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">
												{{ $template->getSetting('wedding_day_title', 'Wedding Day') }}</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-3553537d zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="3553537d" data-element_type="widget"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">
												{{ $template->getSetting('wedding_day_subtitle', 'InsyaAllah akan dilaksanakan pada:') }}
											</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-136d0ec4 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="136d0ec4" data-element_type="widget"
										data-settings="{&quot;_animation_mobile&quot;:&quot;zoomIn&quot;}"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">
												{{ $eventDayName }}<br>{{ $eventDateFull }}</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-00f32ab zoom jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="00f32ab" data-element_type="widget" data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async" width="1080" height="312"
												src="{{ $template->getAssetUrl('divider_image', 'assets/vintage/vendor/DIVIDER-VIN-2.png') }}"
												class="attachment-full size-full wp-image-31469" alt="" />
										</div>
									</div> {{-- Semua acara (utama + tambahan) dari dashboard Settings > Acara --}}
									@php
										$ceremonies = $event->allCeremonies($template->getSetting('event_akad_title', 'Akad Nikah'));
									@endphp
									@foreach($ceremonies as $ceremony)
										@php
											$cTitle = trim((string) ($ceremony['title'] ?? ''));
											$cStart = trim((string) ($ceremony['start_time'] ?? ''));
											$cFinish = trim((string) ($ceremony['finish_time'] ?? ''));
											$cTime = ($cStart !== '' && $cFinish !== '') ? $cStart . ' - ' . $cFinish : ($cStart !== '' ? $cStart : $cFinish);
											$cDate = $ceremony['event_date'] ?? null;
											$cDateLabel = $cDate ? \Carbon\Carbon::parse($cDate)->translatedFormat('j F Y') : null;
											$isFirst = $loop->first;
										@endphp
										<div class="elementor-element elementor-element-463876b2 e-con-full e-flex e-con e-child"
											data-element_type="container"
											style="display:flex;flex-direction:column;align-items:center;width:100%;gap:2px;{{ $isFirst ? '' : 'margin-top:14px;padding-top:14px;' }}">
											<div class="elementor-element elementor-element-27718ef zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
												data-id="27718ef-{{ $loop->index }}" data-element_type="widget"
												data-settings="{&quot;_animation_mobile&quot;:&quot;zoomIn&quot;}"
												data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														{{ $cTitle !== '' ? $cTitle : ($isFirst ? $template->getSetting('event_akad_title', 'Akad Nikah') : 'Acara') }}
													</p>
												</div>
											</div>
											@if($cTime !== '')
												<div class="elementor-element elementor-element-3eeeaa4 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
													data-id="3eeeaa4-{{ $loop->index }}" data-element_type="widget"
													data-widget_type="heading.default">
													<div class="elementor-widget-container">
														<p class="elementor-heading-title elementor-size-default">{{ $cTime }}
														</p>
													</div>
												</div>
											@endif
											@if($cDateLabel)
												<div class="elementor-element elementor-element-2ca8e2f2 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
													data-id="2ca8e2f2-{{ $loop->index }}" data-element_type="widget"
													data-widget_type="heading.default">
													<div class="elementor-widget-container">
														<p class="elementor-heading-title elementor-size-default">
															{{ $cDateLabel }}</p>
													</div>
												</div>
											@endif
											@if(!empty($ceremony['location']) || !empty($ceremony['address']))
												<div class="elementor-element elementor-element-22f88233 zoom elementor-view-default jltma-glass-effect-no elementor-widget elementor-widget-icon"
													data-id="22f88233-{{ $loop->index }}" data-element_type="widget"
													data-widget_type="icon.default">
													<div class="elementor-widget-container">
														<div class="elementor-icon-wrapper">
															<div class="elementor-icon">
																<svg aria-hidden="true"
																	class="e-font-icon-svg e-fas-map-marker-alt"
																	viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
																	<path
																		d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z">
																	</path>
																</svg>
															</div>
														</div>
													</div>
												</div>
											@endif
											@if(!empty($ceremony['location']))
												<div class="elementor-element elementor-element-387cd4a8 muncul jltma-glass-effect-no elementor-widget elementor-widget-text-editor"
													data-id="387cd4a8-{{ $loop->index }}" data-element_type="widget"
													data-widget_type="text-editor.default">
													<div class="elementor-widget-container">
														<p>{{ $ceremony['location'] }}</p>
													</div>
												</div>
											@endif
											@if(!empty($ceremony['address']))
												<div class="elementor-element elementor-element-454c17b1 zoom jltma-glass-effect-no elementor-widget elementor-widget-text-editor"
													data-id="454c17b1-{{ $loop->index }}" data-element_type="widget"
													data-widget_type="text-editor.default">
													<div class="elementor-widget-container">
														<p>{!! nl2br(e($ceremony['address'])) !!}</p>
													</div>
												</div>
											@endif
											@if(!empty($ceremony['google_map_link']))
												<div class="elementor-element elementor-element-6dbbf95c elementor-align-center zoom jltma-glass-effect-no elementor-widget elementor-widget-button"
													data-id="6dbbf95c-{{ $loop->index }}" data-element_type="widget"
													data-widget_type="button.default">
													<div class="elementor-widget-container">
														<div class="elementor-button-wrapper">
															<a href="{{ $ceremony['google_map_link'] }}" target="_blank"
																rel="noopener" class="elementor-button elementor-size-xs"
																role="button">
																<span class="elementor-button-content-wrapper">
																	<span class="elementor-button-icon">
																		<svg aria-hidden="true"
																			class="e-font-icon-svg e-fas-location-arrow"
																			viewBox="0 0 512 512"
																			xmlns="http://www.w3.org/2000/svg">
																			<path
																				d="M444.52 3.52L28.74 195.42c-47.97 22.39-31.98 92.75 19.19 92.75h175.91v175.91c0 51.17 70.36 67.17 92.75 19.19l191.9-415.78c15.99-38.39-25.59-79.97-63.97-63.97z">
																			</path>
																		</svg> </span>
																	<span class="elementor-button-text">Google Maps</span>
																</span>
															</a>
														</div>
													</div>
												</div>
											@endif
										</div>
									@endforeach
								</div>
								<div class="elementor-element elementor-element-6c2e8a00 elementor-widget-mobile__width-inherit elementor-absolute animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="6c2e8a00" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img fetchpriority="high" decoding="async" width="1080" height="528"
											src="{{ $template->getAssetUrl('floral_border', 'assets/vintage/vendor/AhaConvert_BUNGA-VIN-2B.webp') }}"
											class="attachment-full size-full wp-image-31321" alt="" />
									</div>
								</div>
								<div class="elementor-element elementor-element-b42308c elementor-widget__width-initial elementor-absolute goyang-2 animated-slow e-transform jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="b42308c" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:600,&quot;_transform_flipY_effect&quot;:&quot;transform&quot;}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img loading="lazy" decoding="async" width="594" height="684"
											src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31318" alt="" />
									</div>
								</div>
								<div class="elementor-element elementor-element-573f035 elementor-widget__width-initial elementor-absolute goyang-2 animated-slow e-transform e-transform jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="573f035" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:600,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;,&quot;_transform_flipY_effect&quot;:&quot;transform&quot;}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img loading="lazy" decoding="async" width="594" height="684"
											src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31318" alt="" />
									</div>
								</div>
								<div class="elementor-element elementor-element-3b16e3c elementor-widget__width-initial elementor-absolute animated-slow e-transform jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="3b16e3c" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInLeft&quot;,&quot;_animation_delay&quot;:300,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img decoding="async" width="293" height="1427"
											src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/vendor/HORDENG-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31319" alt="" />
									</div>
								</div>
								<div class="elementor-element elementor-element-9d7febf elementor-widget__width-initial elementor-absolute animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="9d7febf" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInRight&quot;,&quot;_animation_delay&quot;:300}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img decoding="async" width="293" height="1427"
											src="{{ $template->getAssetUrl('curtain_decoration', 'assets/vintage/vendor/HORDENG-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31319" alt="" />
									</div>
								</div>
								<div class="elementor-element elementor-element-62ee7f4 elementor-widget__width-inherit elementor-absolute goyang-2 animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="62ee7f4" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:200}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img loading="lazy" decoding="async" width="362" height="430"
											src="{{ $template->getAssetUrl('lamp_decoration', 'assets/vintage/vendor/LAMPU-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31320" alt="" />
									</div>
								</div>
								<div class="elementor-element elementor-element-95c77a5 elementor-widget__width-initial elementor-absolute goyang-2 animated-slow jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="95c77a5" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:600}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img loading="lazy" decoding="async" width="594" height="684"
											src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31318" alt="" />
									</div>
								</div>
								<div class="elementor-element elementor-element-21db0bb elementor-widget__width-initial elementor-absolute goyang-2 animated-slow e-transform jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
									data-id="21db0bb" data-element_type="widget"
									data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:600,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;}"
									data-widget_type="image.default">
									<div class="elementor-widget-container">
										<img loading="lazy" decoding="async" width="594" height="684"
											src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
											class="attachment-full size-full wp-image-31318" alt="" />
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-3cda51a5 e-con-full e-flex e-con e-child"
								data-id="3cda51a5" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
								<div class="elementor-element elementor-element-36ab7874 e-con-full e-flex e-con e-child"
									data-id="36ab7874" data-element_type="container">
									<div class="elementor-element elementor-element-2b81c7f4 muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="2b81c7f4" data-element_type="widget"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">
												{{ $template->getSetting('story_title', 'Our Story') }}</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-35145a0a zoom jltma-glass-effect-no elementor-widget elementor-widget-text-editor"
										data-id="35145a0a" data-element_type="widget"
										data-widget_type="text-editor.default">
										<div class="elementor-widget-container">
											<p>{{ $template->getSetting('story_subtitle', 'Every love story is beautiful but ours is my favorite') }}
											</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-4ce5666c muncul jltma-glass-effect-no elementor-widget elementor-widget-image"
										data-id="4ce5666c" data-element_type="widget" data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async"
												src="{{ $template->getAssetUrl('story_image', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_40_16-AM.jpg') }}"
												class="attachment-large size-large wp-image-31414"
												alt="{{ $coupleName }}">
										</div>
									</div>
									@php
										$storyItems = json_decode($template->getSetting('story_items', '[]'), true) ?: [];
									@endphp
									<div class="story-timeline">
										@foreach($storyItems as $story)
											<div class="story-step muncul">
												<div class="story-step-dot"></div>
												<div class="story-step-content">
													<div class="story-step-title">{{ $story['title'] ?? '' }}</div>
													<p class="story-step-desc">{{ $story['description'] ?? '' }}</p>
												</div>
											</div>
										@endforeach
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-501b5057 e-con-full e-flex e-con e-child"
								data-id="501b5057" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
								<div class="elementor-element elementor-element-3345fcc8 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
									data-id="3345fcc8" data-element_type="widget" data-widget_type="heading.default">
									<div class="elementor-widget-container">
										<p class="elementor-heading-title elementor-size-default">
											{{ $template->getSetting('gallery_title', 'the Moments of') }}</p>
									</div>
								</div>
								<div class="elementor-element elementor-element-9e6764 muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
									data-id="9e6764" data-element_type="widget" data-widget_type="heading.default">
									<div class="elementor-widget-container">
										<p class="elementor-heading-title elementor-size-default">{{ $coupleName }}</p>
									</div>
								</div>
								@php $galleryVideoUrl = $template->getSetting('gallery_video_url', ''); @endphp
								@if(!empty($galleryVideoUrl))
								<div class="elementor-element elementor-element-b2d14db jltma-glass-effect-no elementor-widget elementor-widget-video"
									data-id="b2d14db" data-element_type="widget"
									data-settings="{{ json_encode(['youtube_url' => $galleryVideoUrl, 'autoplay' => 'yes', 'play_on_mobile' => 'yes', 'mute' => 'yes', 'loop' => 'yes', 'video_type' => 'youtube']) }}"
									data-widget_type="video.default">
									<div class="elementor-widget-container">
										<div class="elementor-wrapper elementor-open-inline">
											<div class="elementor-video"></div>
										</div>
									</div>
								</div>
								@endif
								{{-- Pilihan tampilan galeri: Slide (swiper) atau Grid (bisa diklik) --}}
								<div class="wdp-gallery-switch" data-gallery-switch role="tablist"
									aria-label="Tampilan galeri">
									<button type="button" role="tab" aria-selected="true" class="is-active"
										data-gallery-view="slide">Slide</button>
									<button type="button" role="tab" aria-selected="false"
										data-gallery-view="grid">Grid</button>
								</div>
								<div class="elementor-element elementor-element-46be85fa elementor-pagination-position-outside jltma-glass-effect-no elementor-widget elementor-widget-image-carousel"
									data-id="46be85fa" data-gallery-panel="slide" data-element_type="widget"
									data-settings="{&quot;slides_to_show_mobile&quot;:&quot;2&quot;,&quot;navigation&quot;:&quot;dots&quot;,&quot;autoplay_speed&quot;:3000,&quot;speed&quot;:2000,&quot;image_spacing_custom_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:10,&quot;sizes&quot;:[]},&quot;autoplay&quot;:&quot;yes&quot;,&quot;pause_on_hover&quot;:&quot;yes&quot;,&quot;pause_on_interaction&quot;:&quot;yes&quot;,&quot;infinite&quot;:&quot;yes&quot;,&quot;image_spacing_custom&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:20,&quot;sizes&quot;:[]},&quot;image_spacing_custom_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}"
									data-widget_type="image-carousel.default">
									<div class="elementor-widget-container">
										{{-- Panah navigasi mode Slide: pindah foto tanpa membuka lightbox.
										     Tombolnya disambungkan ke instance Swiper di script
										     galeri di bagian bawah halaman. --}}
										<button type="button" class="wdp-gallery-nav wdp-gallery-nav--prev"
											data-gallery-nav="-1" aria-label="Foto sebelumnya">&#8249;</button>
										<button type="button" class="wdp-gallery-nav wdp-gallery-nav--next"
											data-gallery-nav="1" aria-label="Foto berikutnya">&#8250;</button>
										<div class="elementor-image-carousel-wrapper swiper" role="region"
											aria-roledescription="carousel" aria-label="Image Carousel" dir="ltr">
											<div class="elementor-image-carousel swiper-wrapper" aria-live="off">
												@forelse($galleryItems as $index => $item)
													@if($item['url'])
														<div class="swiper-slide" role="group" aria-roledescription="slide"
															aria-label="{{ $index + 1 }} of {{ count($galleryItems) }}"
															data-gallery-index="{{ $index }}"
															data-gallery-src="{{ $item['url'] }}"
															data-gallery-caption="{{ $item['caption'] }}">
															<figure class="swiper-slide-inner"><img decoding="async"
																	class="swiper-slide-image"
																	src="{{ $item['url'] }}"
																	alt="{{ $item['caption'] }}" /></figure>
														</div>
													@endif
												@empty
													<div class="swiper-slide" role="group" aria-roledescription="slide"
														aria-label="1 of 2"
														data-gallery-index="0" data-gallery-src="{{ $bridePhotoUrl }}"
													data-gallery-caption="{{ $brideFullName }}">
														<figure class="swiper-slide-inner"><img decoding="async"
																class="swiper-slide-image" src="{{ $bridePhotoUrl }}"
																alt="{{ $brideFullName }}" /></figure>
													</div>
													<div class="swiper-slide" role="group" aria-roledescription="slide"
														aria-label="2 of 2"
														data-gallery-index="1" data-gallery-src="{{ $groomPhotoUrl }}"
													data-gallery-caption="{{ $groomFullName }}">
														<figure class="swiper-slide-inner"><img decoding="async"
																class="swiper-slide-image" src="{{ $groomPhotoUrl }}"
																alt="{{ $groomFullName }}" /></figure>
													</div>
												@endforelse
											</div>

											<div class="swiper-pagination"></div>
										</div>
									</div>
								</div>
								<div class="elementor-element elementor-element-5cc28ef1 wdp-gallery-panel jltma-glass-effect-no elementor-widget elementor-widget-gallery"
									data-gallery-panel="grid" hidden data-id="5cc28ef1" data-element_type="widget"
									data-settings="{&quot;gallery_layout&quot;:&quot;justified&quot;,&quot;ideal_row_height&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:281,&quot;sizes&quot;:[]},&quot;ideal_row_height_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:225,&quot;sizes&quot;:[]},&quot;lazyload&quot;:&quot;yes&quot;,&quot;ideal_row_height_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:150,&quot;sizes&quot;:[]},&quot;gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:10,&quot;sizes&quot;:[]},&quot;gap_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:10,&quot;sizes&quot;:[]},&quot;gap_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:10,&quot;sizes&quot;:[]},&quot;link_to&quot;:&quot;file&quot;,&quot;overlay_background&quot;:&quot;yes&quot;,&quot;content_hover_animation&quot;:&quot;fade-in&quot;}"
									data-widget_type="">
									<div class="elementor-widget-container">
										<div class="wdp-gallery-grid">
											@forelse($galleryItems as $index => $item)
												<button type="button" class="wdp-gallery-thumb"
													data-gallery-index="{{ $index }}" data-gallery-src="{{ $item['url'] }}"
													data-gallery-caption="{{ $item['caption'] }}"
													aria-label="Buka foto {{ $index + 1 }} dari {{ count($galleryItems) }}">
													<img loading="lazy" decoding="async" src="{{ $item['url'] }}"
														alt="{{ $item['caption'] }}">
												</button>
											@empty
												<button type="button" class="wdp-gallery-thumb" data-gallery-index="0"
													data-gallery-src="{{ $bridePhotoUrl }}"
													data-gallery-caption="{{ $brideFullName }}"
													aria-label="Buka foto 1 dari 2">
													<img loading="lazy" decoding="async" src="{{ $bridePhotoUrl }}"
														alt="{{ $brideFullName }}">
												</button>
												<button type="button" class="wdp-gallery-thumb" data-gallery-index="1"
													data-gallery-src="{{ $groomPhotoUrl }}"
													data-gallery-caption="{{ $groomFullName }}"
													aria-label="Buka foto 2 dari 2">
													<img loading="lazy" decoding="async" src="{{ $groomPhotoUrl }}"
														alt="{{ $groomFullName }}">
												</button>
											@endforelse
										</div>
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-7f6eb8cc e-con-full e-flex e-con e-child"
								data-id="7f6eb8cc" data-element_type="container">
								<div class="elementor-element elementor-element-6a4cb199 e-con-full animated-slow e-flex elementor-invisible e-con e-child"
									data-id="6a4cb199" data-element_type="container"
									data-settings="{&quot;animation&quot;:&quot;fadeInUp&quot;,&quot;animation_delay&quot;:10,&quot;background_background&quot;:&quot;classic&quot;}">
									<div class="elementor-element elementor-element-4b0a9b5a elementor-view-stacked elementor-shape-circle jltma-glass-effect-no elementor-widget elementor-widget-icon"
										data-id="4b0a9b5a" data-element_type="widget" data-widget_type="icon.default">
										<div class="elementor-widget-container">
											<div class="elementor-icon-wrapper">
												<div class="elementor-icon">
													<i aria-hidden="true" class="im im-gift"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="elementor-element elementor-element-174a7c72 muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="174a7c72" data-element_type="widget"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">
												{{ $template->getSetting('gift_title', 'Wedding Gift') }}</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-18c384d7 jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-heading"
										data-id="18c384d7" data-element_type="widget"
										data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">
												{{ $template->getSetting('gift_subtitle', 'Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.') }}
											</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-749ac5c jltma-glass-effect-no elementor-widget elementor-widget-n-accordion"
										data-id="749ac5c" data-element_type="widget"
										data-settings="{&quot;default_state&quot;:&quot;all_collapsed&quot;,&quot;max_items_expended&quot;:&quot;one&quot;,&quot;n_accordion_animation_duration&quot;:{&quot;unit&quot;:&quot;ms&quot;,&quot;size&quot;:400,&quot;sizes&quot;:[]}}"
										data-widget_type="nested-accordion.default">
										<div class="elementor-widget-container">
											<div class="e-n-accordion"
												aria-label="Accordion. Open links with Enter or Space, close with Escape, and navigate with Arrow Keys">
												<details id="e-n-accordion-item-1220" class="e-n-accordion-item">
													<summary class="e-n-accordion-item-title" data-accordion-index="1"
														tabindex="0" aria-expanded="false"
														aria-controls="e-n-accordion-item-1220">
														<span class='e-n-accordion-item-title-header'>
															<div class="e-n-accordion-item-title-text"> Klik Disini
															</div>
														</span>
														<span class='e-n-accordion-item-title-icon'>
															<span class='e-opened'><svg aria-hidden="true"
																	class="e-font-icon-svg e-fas-minus"
																	viewBox="0 0 448 512"
																	xmlns="http://www.w3.org/2000/svg">
																	<path
																		d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z">
																	</path>
																</svg></span>
															<span class='e-closed'><svg aria-hidden="true"
																	class="e-font-icon-svg e-fas-gift"
																	viewBox="0 0 512 512"
																	xmlns="http://www.w3.org/2000/svg">
																	<path
																		d="M32 448c0 17.7 14.3 32 32 32h160V320H32v128zm256 32h160c17.7 0 32-14.3 32-32V320H288v160zm192-320h-42.1c6.2-12.1 10.1-25.5 10.1-40 0-48.5-39.5-88-88-88-41.6 0-68.5 21.3-103 68.3-34.5-47-61.4-68.3-103-68.3-48.5 0-88 39.5-88 88 0 14.5 3.8 27.9 10.1 40H32c-17.7 0-32 14.3-32 32v80c0 8.8 7.2 16 16 16h480c8.8 0 16-7.2 16-16v-80c0-17.7-14.3-32-32-32zm-326.1 0c-22.1 0-40-17.9-40-40s17.9-40 40-40c19.9 0 34.6 3.3 86.1 80h-86.1zm206.1 0h-86.1c51.4-76.5 65.7-80 86.1-80 22.1 0 40 17.9 40 40s-17.9 40-40 40z">
																	</path>
																</svg></span>
														</span>

													</summary>
													<div role="region" aria-labelledby="e-n-accordion-item-1220"
														class="elementor-element elementor-element-4f442e6c e-con-full e-flex e-con e-child"
														data-id="4f442e6c" data-element_type="container">
														@foreach($giftAccounts as $gift)
															@php
																$giftLogoUrl = $template->getGiftLogoUrl($gift);
																$giftIsAddress = $gift['type'] === 'address';
																$giftCopyLines = [$gift['label']];
																$giftCopyLines[] = $giftIsAddress ? 'Alamat : ' . $gift['address'] : 'No. Rekening ' . $gift['number'];

																if ($gift['holder'] !== '') {
																	$giftCopyLines[] = 'a.n ' . $gift['holder'];
																}

																// Escape each line, then join with real <br /> tags so the copied text
																// keeps its line breaks (the block is echoed raw).
																$giftCopyText = implode('<br />', array_map(fn($line) => e($line), $giftCopyLines));
															@endphp
															<div role="region" aria-labelledby="e-n-accordion-item-1220"
																class="elementor-element elementor-element-62e96db2 e-con-full animated-slow e-flex elementor-invisible e-con e-child"
																data-id="62e96db2" data-element_type="container"
																data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInUp&quot;,&quot;animation_delay&quot;:100}">
																<div class="elementor-element elementor-element-7332c195 jltma-glass-effect-no elementor-widget elementor-widget-image"
																	data-id="7332c195" data-element_type="widget"
																	data-widget_type="image.default">
																	<div class="elementor-widget-container">
																		@if($giftIsAddress || !$giftLogoUrl)
																			<div class="elementor-icon-wrapper">
																				<div class="elementor-icon"><svg
																						aria-hidden="true"
																						class="e-font-icon-svg e-fas-gift"
																						viewBox="0 0 512 512"
																						xmlns="http://www.w3.org/2000/svg">
																						<path
																							d="M32 448c0 17.7 14.3 32 32 32h160V320H32v128zm256 32h160c17.7 0 32-14.3 32-32V320H288v160zm192-320h-42.1c6.2-12.1 10.1-25.5 10.1-40 0-48.5-39.5-88-88-88-41.6 0-68.5 21.3-103 68.3-34.5-47-61.4-68.3-103-68.3-48.5 0-88 39.5-88 88 0 14.5 3.8 27.9 10.1 40H32c-17.7 0-32 14.3-32 32v80c0 8.8 7.2 16 16 16h480c8.8 0 16-7.2 16-16v-80c0-17.7-14.3-32-32-32zm-326.1 0c-22.1 0-40-17.9-40-40s17.9-40 40-40c19.9 0 34.6 3.3 86.1 80h-86.1zm206.1 0h-86.1c51.4-76.5 65.7-80 86.1-80 22.1 0 40 17.9 40 40s-17.9 40-40 40z">
																						</path>
																					</svg></div>
																		</div>@else<img loading="lazy" decoding="async"
																			src="{{ $giftLogoUrl }}"
																			class="attachment-large size-large"
																		alt="{{ $gift['label'] }}" />@endif
																	</div>
																</div>
																<div class="elementor-element elementor-element-3bfea22 jltma-glass-effect-no elementor-widget elementor-widget-text-editor"
																	data-id="3bfea22" data-element_type="widget"
																	data-widget_type="text-editor.default">
																	<div class="elementor-widget-container">
																		<p>{{ $gift['label'] }}<br />
																			@if($giftIsAddress)
																				Alamat : {{ $gift['address'] }}
																			@else
																				No. Rekening {{ $gift['number'] }}
																			@endif
																			@if($gift['holder'] !== '')
																				<br />a.n <strong>{{ $gift['holder'] }}</strong>
																			@endif
																		</p>
																	</div>
																</div>
																<div class="elementor-element elementor-element-1190eb38 elementor-align-left elementor-mobile-align-left jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-weddingpress-copy-text"
																	data-id="1190eb38" data-element_type="widget"
																	data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
																	data-widget_type="weddingpress-copy-text.default">
																	<div class="elementor-widget-container">

																		<div class="elementor-image img"></div>

																		<div class="head-title"></div>
																		<div class="elementor-button-wrapper">
																			<div class="copy-content spancontent"
																				style="display: none;">{!! $giftCopyText !!}
																			</div>

																			<a style="cursor:pointer;"
																				onclick="copyText(this)"
																				data-message="Copied"
																				class="elementor-button" role="button">

																				<div
																					class="elementor-button-content-wrapper">
																					<span
																						class="elementor-button-icon elementor-align-icon-">
																						<svg aria-hidden="true"
																							class="e-font-icon-svg e-far-copy"
																							viewBox="0 0 448 512"
																							xmlns="http://www.w3.org/2000/svg">
																							<path
																								d="M433.941 65.941l-51.882-51.882A48 48 0 0 0 348.118 0H176c-26.51 0-48 21.49-48 48v48H48c-26.51 0-48 21.49-48 48v320c0 26.51 21.49 48 48 48h224c26.51 0 48-21.49 48-48v-48h80c26.51 0 48-21.49 48-48V99.882a48 48 0 0 0-14.059-33.941zM266 464H54a6 6 0 0 1-6-6V150a6 6 0 0 1 6-6h74v224c0 26.51 21.49 48 48 48h96v42a6 6 0 0 1-6 6zm128-96H182a6 6 0 0 1-6-6V54a6 6 0 0 1 6-6h106v88c0 13.255 10.745 24 24 24h88v202a6 6 0 0 1-6 6zm6-256h-64V48h9.632c1.591 0 3.117.632 4.243 1.757l48.368 48.368a6 6 0 0 1 1.757 4.243V112z">
																							</path>
																						</svg> </span>
																					<span
																						class="elementor-button-text">Copy</span>
																				</div>
																			</a>

																		</div>

																		<style type="text/css">
																			.spancontent {
																				padding-bottom: 20px;
																			}

																			.copy-content {
																				color: #6EC1E4;
																				text-align: center;
																			}

																			.head-title {
																				color: #6EC1E4;
																				text-align: center;
																			}
																		</style>

																		<script>
																			function copyText(el) {
																				var content = jQuery(el).siblings('div.copy-content').html()
																				if (!content || !jQuery.trim(content)) {
																					// Fallback: copy the card caption (bank name, account number, ...)
																					content = jQuery(el).closest('.elementor-widget-container').find('p').first().html() || ''
																				}
																				var temp = jQuery("<textarea>");
																				jQuery("body").append(temp);
																				temp.val(content.replace(/<br ?\/?>/g, "\n")).select();
																				document.execCommand("copy");
																				temp.remove();
																				var text = jQuery(el).html()
																				jQuery(el).html(jQuery(el).data('message'))
																				var counter = 0;
																				var interval = setInterval(function () {
																					counter++;
																					if (counter == 1) {
																						jQuery(el).html(text)
																					}
																				}, 500);
																			}

																		</script>

																	</div>
																</div>
															</div>
														@endforeach
													</div>
												</details>
											</div>
										</div>
									</div>
								</div>

								<div class="elementor-element elementor-element-4a08c6be e-con-full animated-slow e-flex elementor-invisible e-con e-child"
									data-id="4a08c6be" data-element_type="container"
									data-settings="{&quot;animation&quot;:&quot;fadeInUp&quot;,&quot;animation_delay&quot;:10,&quot;background_background&quot;:&quot;classic&quot;}">
									<div class="elementor-element elementor-element-25ec52ed muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="25ec52ed" data-element_type="widget"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">Live Moment</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-10275940 jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-text-editor"
										data-id="10275940" data-element_type="widget"
										data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
										data-widget_type="text-editor.default">
										<div class="elementor-widget-container">
											<p>Pernikahan kami dapat disaksikan langsung secara virtual melalui layanan
												live streaming. Klik tombol di bawah ini untuk bergabung:</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-7e7f9d7f elementor-align-center zoom jltma-glass-effect-no elementor-widget elementor-widget-button"
										data-id="7e7f9d7f" data-element_type="widget" data-widget_type="button.default">
										<div class="elementor-widget-container">
											<div class="elementor-button-wrapper">
												<a href="{{ $template->getSetting('live_stream_url', '#') }}" {{ $template->getSetting('live_stream_url') ? 'target="_blank" rel="noopener"' : '' }} class="elementor-button elementor-size-xs"
													role="button">
													<span class="elementor-button-content-wrapper">
														<span class="elementor-button-icon">
															<svg aria-hidden="true" class="e-font-icon-svg e-fas-video"
																viewBox="0 0 576 512"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M336.2 64H47.8C21.4 64 0 85.4 0 111.8v288.4C0 426.6 21.4 448 47.8 448h288.4c26.4 0 47.8-21.4 47.8-47.8V111.8c0-26.4-21.4-47.8-47.8-47.8zm189.4 37.7L416 177.3v157.4l109.6 75.5c21.2 14.6 50.4-.3 50.4-25.8V127.5c0-25.4-29.1-40.4-50.4-25.8z">
																</path>
															</svg> </span>
														<span class="elementor-button-text">Join Live</span>
													</span>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div class="elementor-element elementor-element-239ae875 e-con-full animated-slow e-flex elementor-invisible e-con e-child"
									data-id="239ae875" data-element_type="container"
									data-settings="{&quot;animation&quot;:&quot;fadeInUp&quot;,&quot;animation_delay&quot;:10,&quot;background_background&quot;:&quot;classic&quot;}">
									<div class="elementor-element elementor-element-70bddeac muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
										data-id="70bddeac" data-element_type="widget"
										data-widget_type="heading.default">
										<div class="elementor-widget-container">
											<p class="elementor-heading-title elementor-size-default">Dresscode</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-188ba15c jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-text-editor"
										data-id="188ba15c" data-element_type="widget"
										data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
										data-widget_type="text-editor.default">
										<div class="elementor-widget-container">
											<p>Kami mengajak Bapak/Ibu/Saudara/i untuk mengenakan pakaian dengan nuansa
												berikut ini di hari bahagia kami:</p>
										</div>
									</div>
									<div class="elementor-element elementor-element-7c0570d2 jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-image"
										data-id="7c0570d2" data-element_type="widget"
										data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;,&quot;_animation_delay&quot;:100,&quot;_animation_mobile&quot;:&quot;fadeInDown&quot;}"
										data-widget_type="image.default">
										<div class="elementor-widget-container">
											<img loading="lazy" decoding="async" width="800" height="345"
												src="{{ $template->getAssetUrl('dresscode_image', 'assets/vintage/vendor/dresscode-color.png') }}"
												class="attachment-large size-large wp-image-423" alt="" />
										</div>
									</div>
								</div>
							</div>
							<div class="elementor-element elementor-element-54904115 e-con-full e-flex e-con e-child"
								data-id="54904115" data-element_type="container"
								data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
								<div class="elementor-element elementor-element-1d957e8d e-flex e-con-boxed e-con e-child"
									data-id="1d957e8d" data-element_type="container">
									<div class="e-con-inner">
										<div class="elementor-element elementor-element-613571e3 zoom jltma-glass-effect-no elementor-widget elementor-widget-image"
											data-id="613571e3" data-element_type="widget"
											data-widget_type="image.default">
											<div class="elementor-widget-container">
												<img loading="lazy" decoding="async" width="800" height="530"
													src="{{ $template->getAssetUrl('logo_image', 'assets/vintage/vendor/LOGO-VIN-2.png') }}"
													class="attachment-large size-large wp-image-31271" alt=""
													style="width:30% !important;height:auto !important;max-width:60% !important;" />
											</div>
										</div>
										<div class="elementor-element elementor-element-6a583333 zoom jltma-glass-effect-no elementor-widget elementor-widget-heading"
											data-id="6a583333" data-element_type="widget"
											data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">Best Wishes
												</p>
											</div>
										</div>
										<div class="elementor-element elementor-element-6405d720 muncul jltma-glass-effect-no elementor-widget elementor-widget-heading"
											data-id="6405d720" data-element_type="widget"
											data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">Happily ever
													after notes for the newly weds</p>
											</div>
										</div>
										<div class="elementor-element elementor-element-4c7a0208 jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-weddingpress-kit2"
											data-id="4c7a0208" data-element_type="widget"
											data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300,&quot;show_avatar&quot;:&quot;yes&quot;,&quot;attendence&quot;:&quot;yes&quot;,&quot;show_date&quot;:&quot;yes&quot;}"
											data-widget_type="weddingpress-kit2.default">
											<div class="elementor-widget-container">
												<script>window.addEventListener('DOMContentLoaded', () => { const ck2_param = window.location.search; document.querySelector('input.ck-query-url').value = ck2_param; }); window.onerror = function (message, url, lineNumber) {
														if (message.includes(`access property "innerHTML"`)) return true;
													};</script>
												<script type="text/javascript">
													jQuery(document).ready(function (e) {
														const theCuiSelect = document.querySelector('.cui-select');
														let txtValiditySelect = 'Mohon konfirmasi kehadiran anda';
														let txtValidityHubungan = 'Mohon isi hubungan anda dengan mempelai';
														// Formulir di template ini memakai id `bestWishesForm` (bukan
														// `commentform-*` bawaan plugin), dan pada saat skrip ini jalan
														// markup form-nya belum tentu sudah ada di DOM. Dulu baris
														// `ckForm.addEventListener` melempar error karena ckForm null,
														// sehingga seluruh blok di bawahnya (termasuk select jumlah
														// tamu) tidak pernah dijalankan.
														const ckForm = document.querySelector('form#bestWishesForm');
														if (ckForm) {
															ckForm.addEventListener('submit', listenSubmit, false);
														}
														// Select ini disembunyikan dan diganti tombol ber-icon, jadi
														// `required` bawaan HTML justru memblokir submit (field
														// tersembunyi tidak bisa difokus browser). Validasinya
														// dikerjakan di `submitBestWishes()`.
														theCuiSelect.removeAttribute('required');
														let ck_add_input = 0;
														let ck_new_input;
														if (ck_add_input) {
															ck_new_input = ckInputEl('text', '', txtValidityHubungan)
															document.querySelector('form[id^=commentform-] p.comment-form-author').appendChild(ck_new_input);
															document.querySelector('select[id=konfirmasi]').disabled = true;
														}
														// Pilihan jumlah tamu yang hadir (1–4 orang), sesuai mode
														// Konfirmasi Kehadiran = "Akan Hadir".
														const _optionHadir = [];
														const qtyPeople = 4;
														for (let iii = 0; iii < qtyPeople; iii++) {
															_optionHadir.push(`${iii + 1} Orang`);
														}

														const savedAttends = {{ (int) ($guestData->guest_attends ?? 1) }};
														const optDataHadir = createSelectEl(_optionHadir, `${savedAttends} Orang`, '-------');
														// Jadikan select ini sumber nilai `guest_attends` yang dikirim
														// ke server (dulu elemennya hanya di-append ke DOM dan tidak
														// punya `name`, jadi jumlah tamu tidak pernah terkirim).
														e(optDataHadir).appendTo('div.cui-select-attending')
														if (ck_add_input) {
															ck_new_input.onchange = function () {
																document.querySelector('select[id=konfirmasi]').disabled = false;
																if (document.querySelector('select[id=konfirmasi]').value == 'Hadir') {
																	e('#comment_note').val(e('.qty-jumlah-kehadiran').val() + '_' + this.value)
																}
															}
														}
														document.querySelector('select[id=konfirmasi]').onchange = function () {
															if (this.value === 'Hadir') {
																if (qtyPeople > 1) e('.qty-jumlah-kehadiran').show();
																if (ck_add_input) {
																	if (qtyPeople > 1) {
																		e('#comment_note').val(e('.qty-jumlah-kehadiran').val() + '_' + ck_new_input.value)
																	} else {
																		e('#comment_note').val('-_' + ck_new_input.value)
																	}
																} else {
																	e('#comment_note').val(e('.qty-jumlah-kehadiran').val())
																}
															} else {
																if (qtyPeople > 1) e('.qty-jumlah-kehadiran').hide();
																if (ck_add_input) {
																	e('#comment_note').val('-_' + ck_new_input.value)
																} else {
																	e('#comment_note').val('-')
																}
															}
														}
														document.querySelector('.qty-jumlah-kehadiran').onchange = function () {
															if (ck_add_input) {
																e('#comment_note').val(this.value + '_' + ck_new_input.value)
															} else {
																e('#comment_note').val(this.value);
															}
														}

														function listenSubmit(evt) {
															if (ck_add_input && evt.srcElement[1].value == '') {
																evt.preventDefault();
																return;
															}
															const txtAreaCui = document.querySelector('textarea[id^=cui-textarea-]');
															if (ck_add_input) {
																txtAreaCui.value = "- " + ck_new_input.value + "\n\n" + txtAreaCui.value;
																ck_new_input.value = '';
															}
														}
													})
													function createSelectEl(options, selected, disableOpt) {
														const selEl = document.createElement('select');
														options.forEach((o) => {
															const option = document.createElement('option');
															// Nilai dikirim sebagai angka murni ("1".."4") karena
															// `guest_attends` divalidasi `integer` di server.
															option.value = String(o).replace(/[^0-9]/g, '') || o;
															option.innerText = o;

															if (selected && (o.toLowerCase() === selected.toLowerCase())) {
																option.selected = 'selected';
															}
															if (disableOpt && (o.toLowerCase() === disableOpt.toLowerCase())) {
																option.disabled = true;
															}

															selEl.appendChild(option);
														});

														selEl.className = 'qty-jumlah-kehadiran';
														selEl.setAttribute('name', 'guest_attends');
														selEl.style.width = '100%';
														selEl.style.marginTop = '10px';
														selEl.style.display = 'none';
														return selEl;
													}
													function ckInputEl(type, placeholder, txtValidity) {
														const selEl = document.createElement('input');
														selEl.className = 'ck-profile-tamu';
														selEl.setAttribute('type', type);
														selEl.setAttribute('required', true);
														selEl.setAttribute('id', 'ck-profile-tamu');
														selEl.setAttribute('maxlength', '50');
														selEl.setAttribute('placeholder', placeholder);
														selEl.setAttribute('oninvalid', `this.setCustomValidity('${txtValidity}')`);
														selEl.setAttribute('oninput', "this.setCustomValidity('')");
														return selEl;
													}
												</script>
												<div class='cui-wrapper cui-golden cui-border'
													style='overflow: hidden;'>
													{{-- Tautan jumlah ucapan. Kelas `auto-load-true` sengaja dilepas:
													     skrip bawaan plugin komentar akan menarik data dari
													     ourinvidigi.com (situs WordPress lama) dan menimpa daftar
													     ucapan yang kita render dari database sendiri. --}}
													<div class='cui-wrap-link'>
														<div class='header-cui'><a id='cui-link-31261'
																class='cui-link cui-icon-link cui-icon-link-true'
																href='#cui-wrap-commnent-31261'
																title='{{ $messages->count() }} Ucapan'><span>{{ $messages->count() }}</span>
																Ucapan</a></div>
													</div><!--.cui-wrap-link-->
													<div id='cui-wrap-commnent-31261' class='cui-wrap-comments'
														style='display:block;'>
														<div id='cui-wrap-form-31261' class='cui-clearfix'>
															<div class="cui-clearfix cui-wrap-form ">
																<div id='cui-container-form-31261'
																	class='cui-container-form cui-no-login'>
																	<div id='respond-31261'
																		class='respond cui-clearfix'>
																		<form
																			action="{{ route('wedding.store-message') }}"
																			method="post" id="bestWishesForm"
																			onsubmit="submitBestWishes(event)">
																			@csrf
																			<input type="hidden" name="guest_code"
																				value="{{ $guestData->code ?? '' }}">
																			<input type="hidden" name="guest_id"
																				value="{{ $guestData->id ?? '' }}">
															{{-- Jumlah tamu diisi oleh select `guest_attends` yang dibuat
															     oleh skrip konfirmasi kehadiran di bawah. --}}
																			<p class="comment-form-author cui-field-1">
																				<input id="author" name="name"
																					type="text" required
																					class="cui-input" placeholder="Nama"
																					value="{{ $guestData->name ?? '' }}" />
																				<span class="cui-required">*</span>
																			</p>
																			<div class="cui-wrap-textarea">
																				<textarea id="cui-textarea-31261"
																					class="waci_comment cui-textarea"
																					name="message" placeholder="Ucapan"
																					rows="2"></textarea>
																				<span class="cui-required">*</span>
																			</div>
																			<div
																				class="cui-clearfix cui-wrap-select cui-field-wrap cui-select-attending">
																{{-- `required` dilepas: select ini disembunyikan dan digantikan
																     tombol "Akan Hadir"/"Tidak Hadir". Kontrol `required`
																     yang `display:none` membuat form tidak bisa disubmit
																     (browser menolak fokus ke field tersembunyi), jadi
																     validasinya dilakukan di JS. --}}
																<select class="waci_comment cui-select"
																	name="attendance" id="konfirmasi">
																	<option value="" disabled selected>
																		Konfirmasi Kehadiran</option>
																	<option value="Hadir">Hadir</option>
																	<option value="Tidak Hadir">Tidak
																		Hadir</option>
																</select>
																				<span class="cui-required">*</span>
																			</div>
																			<div class="cui-wrap-submit cui-clearfix">
																				<p class="form-submit">
																					<input name="submit"
																						id="submit-31261" value="Kirim"
																						type="submit"
																						class="cui-form-btn" />
																				</p>
																			</div>
																		</form>
																	</div>
																</div><!--.cui-container-form-->
															</div><!--.cui-clearfix cui-relative-->
														</div><!--.cui-wrap-form-->
														<div id='cui-comment-status-31261' class='cui-comment-status'>
														</div>
														{{-- Daftar ucapan tamu: 5 terbaru dirender dari database, sisanya
														     diambil otomatis saat daftar di-scroll ke bawah atau
														     tombol "Lihat semua ucapan" ditekan. --}}
														<div id='cui-box' class='cui-box'>
															<div class="wdp-wishes" data-wishes
																data-endpoint="{{ route('wedding.messages') }}"
																data-total="{{ $messages->count() }}"
																data-loaded="{{ min(5, $messages->count()) }}">
																<div class="wdp-wishes__head">
																	<span data-wishes-count>{{ $messages->count() }}</span> Ucapan
																</div>
																<div class="wdp-wishes__scroll" data-wishes-scroll>
																	<ul id='cui-container-comment-31261'
																		class='cui-container-comments cui-order-DESC '
																		data-order='DESC'>
																		@forelse($messages->take(5) as $message)
																			<li class="cui-item-comment">
																				<div class="cui-comment-content">
																					<div class="cui-comment-info">
																						<span
																							class="cui-commenter-name">{{ $message->name ?: 'Tamu Undangan' }}</span>
																						<span
																								class="cui-comment-time">{{ $message->created_at?->locale('id')->translatedFormat('d M Y H:i') }}</span>
																					</div>
																					<div class="cui-comment-text">
																									<p>{{ $message->message }}</p>
																								</div>
																				</div>
																			</li>
																		@empty
																			<li class="wdp-wishes__empty">Belum ada ucapan. Jadilah yang
																				pertama memberikan doa restu!</li>
																		@endforelse
																	</ul>
																</div>
																<div class="wdp-wishes__status" data-wishes-status hidden>Memuat
																	ucapan…</div>
																<button type="button" class="wdp-wishes__more" data-wishes-more
																	hidden>Lihat semua ucapan</button>
															</div>
														</div>
														<div id='cui-holder-id-31261'
															class='cui-holder-31261 cui-holder'></div>
													</div><!--.cui-wrap-comments-->
												</div><!--.cui-wrapper-->
												<style>
													.cui-wrapper .cui-holder {
														display: block !important;
													}
												</style>



												<script>

													var txt = document.getElementById('cui-link-31261').innerHTML;
													var pos = txt
														.replace(/Comments/g, "Wishes")
													document.getElementById('cui-link-31261').innerHTML = pos;

													var txt2 = document.getElementById('commentform-31261').innerHTML;
													var pos2 = txt2
														.replace(/Nama/g, "Name")
														.replace(/Ucapan/g, "Ucapan")
														.replace(/Konfirmasi Kehadiran/g, "Konfirmasi Kehadiran")
														.replace(/Datang/g, "Hadir")
														.replace(/Absen/g, "Tidak Hadir")
														.replace(/Mungkin/g, "Masih Ragu")
														.replace(/Kirim/g, "KIRIM");
													document.getElementById('commentform-31261').innerHTML = pos2;

												</script>

											</div>
										</div>
										<div class="elementor-element elementor-element-7bffe429 jltma-glass-effect-no elementor-widget elementor-widget-html"
											data-id="7bffe429" data-element_type="widget"
											data-widget_type="html.default">
											<div class="elementor-widget-container">
												<style>
													:root {
														/*Warna Tombol Akan Hadir*/
														/*Warna Saat Dipilih*/
														--confirm-yes-bgcolor-selected: #FFF1D4;
														/*Warna Text Hadir Default*/
														--warna-teks-akan-hadir: #FFF1D4;
														/*Warna Text Hadir saat Terpilih*/
														--warna-teks-akan-hadir-selected: #000000;

														/*Warna Tombol Tidak Hadir*/
														/*Warna Saat Dipilih*/
														--confirm-no-bgcolor-selected: #FFF1D4;
														/*Warna Text Tidak Hadir Default*/
														--warna-teks-tidak-hadir: #FFF1D4;
														/*Warna Text Tidak Hadir saat Terpilih*/
														--warna-teks-tidak-hadir-selected: #000000;

														/*Warna Border Tombol Default*/
														--confirm-border-color: #FFF1D4;

														/*Warna background Tombol Default*/
														--confirm-bg-color-color: transparent;

														/*Warna ICON Default*/
														--warna-icon-default: #FFF1D4;

														/*Warna ICON Saat Terpilih*/
														--warna-icon-selected: #000000;

														/*Warna Label Konfirmasi Kehadiran*/
														--label-konfirmasi-font-color: #FFF1D4;

														/*Warna text Jumlah Kehadiran*/
														--label-rombongan-color: #000000;

														/*Warna BG Label Jumlah Kehadiran*/
														--label-rombongan-bgcolor: #FFF1D4;
													}

													.wdpConfirm-wrap {
														display: none;
														justify-content: center;
														align-items: center;
														margin: 2px 5px;
													}

													.wdpConfirm-wrap .wdpConfirm-icon {
														pointer-events: none;
														margin-top: -1px;
													}

													.wdp-confirm-yes {
														background: var()--confirm-bg-color-color);
														border: 1px solid var(--confirm-border-color);
														color: var(--warna-teks-akan-hadir);
													}

													.wdp-confirm-no {
														background: var(--confirm-bg-color-color);
														border: 1px solid var(--confirm-border-color);
														color: var(--warna-teks-tidak-hadir);
													}

													.wdp-confirm-yes,
													.wdp-confirm-no {
														gap: 8px;
														display: flex;
														justify-content: center;
														margin: 5px;
														padding: 5px 15px;
														border-radius: 5px;
														min-width: 50%;
														text-align: center;
														cursor: pointer;
														font-size: .8rem;
														font-family: 'Poppins', Arial;
														font-weight: 600;
													}

													.wdp-confirm-yes.active {
														background: var(--confirm-yes-bgcolor-selected);
														color: var(--warna-teks-akan-hadir-selected);
														border: none;
													}

													.wdpConfirm-icon.btns.active svg path {
														fill: var(--warna-icon-selected);
														stroke: transparent;
													}

													.wdpConfirm-icon.btns svg path {
														fill: transparent;
														stroke: var(--warna-icon-default);
													}

													.wdp-confirm-no.active {
														background: var(--confirm-no-bgcolor-selected);
														color: var(--warna-teks-tidak-hadir-selected);
														border: none;
													}

													.qty-jumlah-kehadiran {
														color: #3e3b38 !important;
														background: #ffffff;
														font-size: .8rem !important;
														width: 100% !important;
														border-radius: 0 5px 5px 0;
														border-left: none;
														height: 35px;
														padding: .5rem 1rem !important;
													}

													.jumlah-hadir-wrapper {
														display: none;
														display: flex;
														align-items: center;
													}

													/*STYLE LABEL Konfirmasi Kehadiran*/
													.confirm-label {
														font-size: .8rem;
														color: var(--label-konfirmasi-font-color);
													}

													/*STYLE LABEL ROMBONGAN*/
													.label-jml {
														border: 1px solid #666;
														margin-top: 10px;
														border-radius: 5px 0 0 5px;
														background: var(--label-rombongan-bgcolor);
														border-right: none;
														font-size: .8rem;
														line-height: inherit;
														padding: .4rem 1rem;
														color: var(--label-rombongan-color);
														font-weight: 600;
														height: 35px;
														white-space: nowrap;
													}

													.author-wrapper {
														display: flex;
													}

													body:not(.elementor-editor-active) .cui-field-wrap {
														margin-top: 0px;
													}
												</style>
												<script>
													const akanHadir = "Akan Hadir";
													const tidakHadir = "Tidak Hadir";
													const labelJumlah = "Jumlah Tamu"
													const konfirmasiLabel = "Konfirmasi Kehadiran :"

													const iconHadir = '<span class="wdpConfirm-icon btns"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" xml:space="preserve" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" clip-rule="evenodd" viewBox="0 0 20 20"><path d="M17.645 8.032c-.294-.307-.599-.625-.714-.903-.106-.256-.112-.679-.118-1.089-.012-.762-.025-1.626-.626-2.227s-1.465-.614-2.227-.626c-.41-.006-.833-.012-1.089-.118-.278-.115-.596-.42-.903-.714-.54-.518-1.152-1.105-1.968-1.105-.816 0-1.428.587-1.968 1.105-.307.294-.625.599-.903.714-.256.106-.679.112-1.089.118-.762.012-1.626.025-2.227.626s-.614 1.465-.626 2.227c-.006.41-.012.833-.118 1.089-.115.278-.42.596-.714.903C1.837 8.572 1.25 9.184 1.25 10c0 .816.587 1.428 1.105 1.968.294.307.599.625.714.903.106.256.112.679.118 1.089.012.762.025 1.626.626 2.227s1.465.614 2.227.626c.41.006.833.012 1.089.118.278.115.596.42.903.714.54.518 1.152 1.105 1.968 1.105.816 0 1.428-.587 1.968-1.105.307-.294.625-.599.903-.714.256-.106.679-.112 1.089-.118.762-.012 1.626-.025 2.227-.626s.614-1.465.626-2.227c.006-.41.012-.833.118-1.089.115-.278.42-.596.714-.903.518-.54 1.105-1.152 1.105-1.968 0-.816-.587-1.428-1.105-1.968Zm-3.343-2.461a.882.882 0 0 0-1.222.256l-4.26 6.509-2.036-1.885a.885.885 0 0 0-1.2 1.297l2.815 2.604c.01.009.023.011.033.02.025.02.04.048.067.067.037.025.08.03.121.048a.86.86 0 0 0 .145.058.817.817 0 0 0 .147.023.883.883 0 0 0 .212-.003.89.89 0 0 0 .086-.02.887.887 0 0 0 .247-.103l.039-.028c.052-.036.108-.062.152-.11.031-.034.045-.078.071-.116l.003-.004 4.835-7.389a.89.89 0 0 0-.255-1.224Z"></path></svg></span>';
													const iconTHadir = '<span class="wdpConfirm-icon btns"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" xml:space="preserve" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" clip-rule="evenodd" viewBox="0 0 20 20"><path d="M17.645 8.032c-.294-.307-.599-.625-.714-.903-.106-.256-.112-.679-.118-1.089-.012-.762-.025-1.626-.626-2.227s-1.465-.614-2.227-.626c-.41-.006-.833-.012-1.089-.118-.278-.115-.596-.42-.903-.714-.54-.518-1.152-1.105-1.968-1.105-.816 0-1.428.587-1.968 1.105-.307.294-.625.599-.903.714-.256.106-.679.112-1.089.118-.762.012-1.626.025-2.227.626s-.614 1.465-.626 2.227c-.006.41-.012.833-.118 1.089-.115.278-.42.596-.714.903C1.837 8.572 1.25 9.184 1.25 10c0 .816.587 1.428 1.105 1.968.294.307.599.625.714.903.106.256.112.679.118 1.089.012.762.025 1.626.626 2.227s1.465.614 2.227.626c.41.006.833.012 1.089.118.278.115.596.42.903.714.54.518 1.152 1.105 1.968 1.105.816 0 1.428-.587 1.968-1.105.307-.294.625-.599.903-.714.256-.106.679-.112 1.089-.118.762-.012 1.626-.025 2.227-.626s.614-1.465.626-2.227c.006-.41.012-.833.118-1.089.115-.278.42-.596.714-.903.518-.54 1.105-1.152 1.105-1.968 0-.816-.587-1.428-1.105-1.968Zm-3.94-1.737a1 1 0 0 0-1.418 0L10 8.592 7.713 6.295a1.002 1.002 0 0 0-1.418 1.418L8.592 10l-2.297 2.287a.998.998 0 0 0 0 1.418 1 1 0 0 0 1.418 0L10 11.408l2.287 2.297a.998.998 0 0 0 1.418 0 1 1 0 0 0 0-1.418L11.408 10l2.297-2.287a.998.998 0 0 0 0-1.418Z"></path></svg></span>';

													jQuery(document).ready((jQ) => {
														document.querySelector('.cui-select').style.display = 'none';
														document.querySelector('input[id^=submit-]').style.width = '100%'
														jQuery('.cui-wrap-select').prepend(`<div class="wdpConfirm-wrap"><div class="wdp-confirm-yes">${akanHadir}</div><div class="wdp-confirm-no">${tidakHadir}</div></div>`);

														const confirmAll = document.querySelectorAll('div[class^=wdp-confirm-]');
														const wdpSelectOpt = document.querySelectorAll('.cui-select option');
														let cuiSelects = false;
														let yesSelected = false;
														confirmAll[0].innerHTML = iconHadir + ' ' + confirmAll[0].textContent
														confirmAll[1].innerHTML = iconTHadir + ' ' + confirmAll[1].textContent
														const iconBtnYesNo = document.querySelectorAll('.wdpConfirm-icon');
														confirmAll.forEach((yesno) => {
															yesno.addEventListener('click', (e) => {
																cuiSelects = document.body.contains(document.querySelector('.qty-jumlah-kehadiran'))
																confirmAll.forEach((yesnoStyle) => {
																	yesnoStyle.classList.remove('active')
																})
																iconBtnYesNo.forEach((yesnoIcon) => {
																	yesnoIcon.classList.remove('active');
																})
																e.target.classList.add('active');
																if (e.target.classList.contains('wdp-confirm-yes')) {
																	if (cuiSelects) {
																		if (!yesSelected) {
																			yesSelected = true
																			jQuery('.jumlah-hadir-wrapper').slideToggle();
																			jQuery('.qty-jumlah-kehadiran').show()
																			// Jangan reset ke opsi pertama: biarkan nilai yang tersimpan
																			// untuk tamu ini tetap terpilih.
																			jQuery('.qty-jumlah-kehadiran').trigger('change');
																		}
																	}
																	wdpSelectOpt[1].selected = 'selected'
																	iconBtnYesNo[0].classList.add('active');
																} else if (e.target.classList.contains('wdp-confirm-no')) {
																	if (cuiSelects) {
																		if (yesSelected) {
																			jQuery('.jumlah-hadir-wrapper').slideToggle();
																		}
																		jQuery('#comment_note').val('-')
																		yesSelected = false
																	}
																	wdpSelectOpt[2].selected = 'selected';
																	iconBtnYesNo[1].classList.add('active');
																}
															})
														})
														/*Setting Switch Kehadiran*/
														setTimeout(() => {
															jQ('.comment-form-author').wrapAll("<div class='author-wrapper' />")
															jQ('.qty-jumlah-kehadiran').wrapAll("<div class='jumlah-hadir-wrapper' />")
															jQ('.cui-wrap-select').insertAfter('.author-wrapper')
															jQ(`<label class="label-jml" for="qty-jumlah-kehadiran">` + labelJumlah + `</label>`).insertBefore('.qty-jumlah-kehadiran')
															jQ('.qty-jumlah-kehadiran').attr('id', 'qty-jumlah-kehadiran')
															jQ('.wdpConfirm-wrap').css('display', 'flex')
															jQ(`<div class="confirm-label">` + konfirmasiLabel + `</div>`).insertBefore('.wdpConfirm-wrap')
															document.querySelector('.jumlah-hadir-wrapper').style.display = 'none';
														}, 200)
													})
												</script>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<section data-jltma-particle="{
  &quot;particles&quot;: {
    &quot;number&quot;: {
      &quot;value&quot;: 33,
      &quot;density&quot;: {
        &quot;enable&quot;: true,
        &quot;value_area&quot;: 2051.7838682439087
      }
    },
    &quot;color&quot;: {
      &quot;value&quot;: &quot;#ffffff&quot;
    },
    &quot;shape&quot;: {
      &quot;type&quot;: &quot;circle&quot;,
      &quot;stroke&quot;: {
        &quot;width&quot;: 0,
        &quot;color&quot;: &quot;#ffffff&quot;
      },
      &quot;polygon&quot;: {
        &quot;nb_sides&quot;: 5
      },
      &quot;image&quot;: {
        &quot;src&quot;: &quot;img/github.svg&quot;,
        &quot;width&quot;: 100,
        &quot;height&quot;: 100
      }
    },
    &quot;opacity&quot;: {
      &quot;value&quot;: 0.7776548495197786,
      &quot;random&quot;: true,
      &quot;anim&quot;: {
        &quot;enable&quot;: false,
        &quot;speed&quot;: 1,
        &quot;opacity_min&quot;: 0.1,
        &quot;sync&quot;: false
      }
    },
    &quot;size&quot;: {
      &quot;value&quot;: 98.64345520403408,
      &quot;random&quot;: true,
      &quot;anim&quot;: {
        &quot;enable&quot;: true,
        &quot;speed&quot;: 31.67101127975246,
        &quot;size_min&quot;: 11.369080972218832,
        &quot;sync&quot;: true
      }
    },
    &quot;line_linked&quot;: {
      &quot;enable&quot;: false,
      &quot;distance&quot;: 150,
      &quot;color&quot;: &quot;#ffffff&quot;,
      &quot;opacity&quot;: 0.4,
      &quot;width&quot;: 1
    },
    &quot;move&quot;: {
      &quot;enable&quot;: true,
      &quot;speed&quot;: 6.413648243462092,
      &quot;direction&quot;: &quot;bottom-right&quot;,
      &quot;random&quot;: false,
      &quot;straight&quot;: false,
      &quot;out_mode&quot;: &quot;out&quot;,
      &quot;bounce&quot;: false,
      &quot;attract&quot;: {
        &quot;enable&quot;: true,
        &quot;rotateX&quot;: 481.0236182596568,
        &quot;rotateY&quot;: 561.194221302933
      }
    }
  },
  &quot;interactivity&quot;: {
    &quot;detect_on&quot;: &quot;canvas&quot;,
    &quot;events&quot;: {
      &quot;onhover&quot;: {
        &quot;enable&quot;: false,
        &quot;mode&quot;: &quot;repulse&quot;
      },
      &quot;onclick&quot;: {
        &quot;enable&quot;: false,
        &quot;mode&quot;: &quot;push&quot;
      },
      &quot;resize&quot;: true
    },
    &quot;modes&quot;: {
      &quot;grab&quot;: {
        &quot;distance&quot;: 400,
        &quot;line_linked&quot;: {
          &quot;opacity&quot;: 1
        }
      },
      &quot;bubble&quot;: {
        &quot;distance&quot;: 400,
        &quot;size&quot;: 40,
        &quot;duration&quot;: 2,
        &quot;opacity&quot;: 8,
        &quot;speed&quot;: 3
      },
      &quot;repulse&quot;: {
        &quot;distance&quot;: 200,
        &quot;duration&quot;: 0.4
      },
      &quot;push&quot;: {
        &quot;particles_nb&quot;: 4
      },
      &quot;remove&quot;: {
        &quot;particles_nb&quot;: 2
      }
    }
  },
  &quot;retina_detect&quot;: true
}" class="has_ma_el_bg_slider elementor-section elementor-inner-section elementor-element elementor-element-3bf6678f elementor-section-height-min-height jltma-particle-yes elementor-section-boxed elementor-section-height-default jltma-glass-effect-no"
							data-id="3bf6678f" data-element_type="section"
							data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ma_el_particle_area_zindex&quot;:0}">
							<div class="elementor-background-overlay"></div>
							<div class="elementor-container elementor-column-gap-default">
								<div class="has_ma_el_bg_slider elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-6a3445e0 jltma-glass-effect-no"
									data-id="6a3445e0" data-element_type="column">
									<div class="elementor-widget-wrap elementor-element-populated">
										<div class="elementor-background-overlay"></div>
										<div class="elementor-element elementor-element-1fff004c jltma-glass-effect-no elementor-widget elementor-widget-spacer"
											data-id="1fff004c" data-element_type="widget"
											data-widget_type="spacer.default">
											<div class="elementor-widget-container">
												<div class="elementor-spacer">
													<div class="elementor-spacer-inner"></div>
												</div>
											</div>
										</div>
										<div class="elementor-element elementor-element-35371983 jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-text-editor"
											data-id="35371983" data-element_type="widget"
											data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
											data-widget_type="text-editor.default">
											<div class="elementor-widget-container">
												<p>{{ $template->getSetting('thank_title', 'Terima Kasih') }}</p>
											</div>
										</div>
										<div class="elementor-element elementor-element-119671aa muncul jltma-glass-effect-no elementor-widget elementor-widget-image"
											data-id="119671aa" data-element_type="widget"
											data-widget_type="image.default">
											<div class="elementor-widget-container">
												<img loading="lazy" decoding="async" width="900" height="1350"
													src="{{ $template->getAssetUrl('closing_image', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_42_15-AM.jpg') }}"
													class="attachment-full size-full wp-image-31415" alt="" />
											</div>
										</div>
										<div class="elementor-element elementor-element-2195b04f jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-text-editor"
											data-id="2195b04f" data-element_type="widget"
											data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
											data-widget_type="text-editor.default">
											<div class="elementor-widget-container">
												<p>{{ $template->getSetting('thank_text', 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir dan memberikan doa restunya untuk pernikahan kami.') }}
												</p>
												<p>{{ $template->getSetting('thank_closing', 'Atas doa & restunya, kami ucapkan terima kasih.') }}
												</p>
											</div>
										</div>
										<div class="elementor-element elementor-element-43b280ac jltma-glass-effect-no elementor-invisible elementor-widget elementor-widget-heading"
											data-id="43b280ac" data-element_type="widget"
											data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:200}"
											data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<h1 class="elementor-heading-title elementor-size-default">
													{{ $coupleName }}</h1>
											</div>
										</div>
										<div class="elementor-element elementor-element-249eaf2 jltma-glass-effect-no elementor-widget elementor-widget-spacer"
											data-id="249eaf2" data-element_type="widget"
											data-widget_type="spacer.default">
											<div class="elementor-widget-container">
												<div class="elementor-spacer">
													<div class="elementor-spacer-inner"></div>
												</div>
											</div>
										</div>
										<div class="elementor-element elementor-element-6b9c02de elementor-widget-mobile__width-inherit elementor-absolute jltma-glass-effect-no elementor-widget elementor-widget-image"
											data-id="6b9c02de" data-element_type="widget"
											data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
											data-widget_type="image.default">
											<div class="elementor-widget-container">
												<img fetchpriority="high" decoding="async" width="1080" height="528"
													src="{{ $template->getAssetUrl('floral_border', 'assets/vintage/vendor/AhaConvert_BUNGA-VIN-2B.webp') }}"
													class="attachment-full size-full wp-image-31321" alt="" />
											</div>
										</div>
										<div class="elementor-element elementor-element-6df234b3 elementor-widget__width-initial elementor-absolute goyang-1 jltma-glass-effect-no elementor-widget elementor-widget-image"
											data-id="6df234b3" data-element_type="widget"
											data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
											data-widget_type="image.default">
											<div class="elementor-widget-container">
												<img loading="lazy" decoding="async" width="594" height="684"
													src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
													class="attachment-full size-full wp-image-31318" alt="" />
											</div>
										</div>
										<div class="elementor-element elementor-element-6d00a23e elementor-widget__width-initial elementor-absolute goyang-1 e-transform jltma-glass-effect-no elementor-widget elementor-widget-image"
											data-id="6d00a23e" data-element_type="widget"
											data-settings="{&quot;_position&quot;:&quot;absolute&quot;,&quot;_transform_flipX_effect&quot;:&quot;transform&quot;}"
											data-widget_type="image.default">
											<div class="elementor-widget-container">
												<img loading="lazy" decoding="async" width="594" height="684"
													src="{{ $template->getAssetUrl('flower_decoration', 'assets/vintage/vendor/BUNGA-VIN-2.png') }}"
													class="attachment-full size-full wp-image-31318" alt="" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
						<style>
							.elementor-element-3bf6678f.jltma-particle-wrapper>canvas {
								z-index: 0;
								position: absolute;
								top: 0;
							}
						</style>
						<section
							class="has_ma_el_bg_slider elementor-section elementor-inner-section elementor-element elementor-element-4a930b66 elementor-section-boxed elementor-section-height-default elementor-section-height-default jltma-glass-effect-no"
							data-id="4a930b66" data-element_type="section"
							data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
							<div class="elementor-container elementor-column-gap-no">
								<div class="has_ma_el_bg_slider elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-23f82655 jltma-glass-effect-no"
									data-id="23f82655" data-element_type="column">
									<div class="elementor-widget-wrap elementor-element-populated">
										<div class="elementor-element elementor-element-3ca3dd75 jltma-glass-effect-no elementor-widget elementor-widget-weddingpress-wellcome"
											data-id="3ca3dd75" data-element_type="widget"
											data-widget_type="weddingpress-wellcome.default">
											<div class="elementor-widget-container">
												<style>
													/* .elementor-widget-container {
					all: unset!important;
				} */
												</style>
												<div class="modalx"
													data-sampul="{{ $template->getAssetUrl('cover_photo', 'assets/vintage/vendor/CB-VIN-2-FIX-RE.jpg') }}">

													<div class="overlayy"></div>
													<div class="content-modalx">
														<div class="info_modalx">
															<div class="elementor-image img"><img decoding="async"
																	src="{{ $template->getAssetUrl('logo_image', 'assets/vintage/vendor/LOGO-VIN-2.png') }}"
																	title="LOGO-VIN-2" alt="LOGO-VIN-2" loading="lazy"
																	style="width:30% !important;height:auto !important;max-width:60% !important;" />
															</div>

															<div class="wdp-txt-the-wedding"
																style="width:auto !important">WEDDING INVITATION </div>


															<div class="wdp-mempelai" style="width: auto !important;">
																{{ $coupleName }} </div>


															<div class="wdp-tgl" style="width: auto !important;">
																{{ $eventDateFormatted }} </div>

															<div class="wdp-dear" style="width: auto !important;">Kepada
																Yth.</div>

															<div class="wdp-name namatamu"
																style="width: auto !important;">
																{{ $guestData->name ?? 'Tamu Undangan' }}</div>


															<div class="wdp-button-wrapper" id="wdp-button-wrapper">
																<button class="elementor-button">
																	<span>
																		<svg aria-hidden="true"
																			class="e-font-icon-svg e-fas-envelope-open"
																			viewBox="0 0 512 512"
																			xmlns="http://www.w3.org/2000/svg">
																			<path
																				d="M512 464c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V200.724a48 48 0 0 1 18.387-37.776c24.913-19.529 45.501-35.365 164.2-121.511C199.412 29.17 232.797-.347 256 .003c23.198-.354 56.596 29.172 73.413 41.433 118.687 86.137 139.303 101.995 164.2 121.512A48 48 0 0 1 512 200.724V464zm-65.666-196.605c-2.563-3.728-7.7-4.595-11.339-1.907-22.845 16.873-55.462 40.705-105.582 77.079-16.825 12.266-50.21 41.781-73.413 41.43-23.211.344-56.559-29.143-73.413-41.43-50.114-36.37-82.734-60.204-105.582-77.079-3.639-2.688-8.776-1.821-11.339 1.907l-9.072 13.196a7.998 7.998 0 0 0 1.839 10.967c22.887 16.899 55.454 40.69 105.303 76.868 20.274 14.781 56.524 47.813 92.264 47.573 35.724.242 71.961-32.771 92.263-47.573 49.85-36.179 82.418-59.97 105.303-76.868a7.998 7.998 0 0 0 1.839-10.967l-9.071-13.196z">
																			</path>
																		</svg> </span>
																	BUKA UNDANGAN </button>
															</div>





														</div>
													</div>
												</div>



												<script>
													var sampulbg = jQuery('.modalx').data('sampul');
													jQuery('.modalx').attr('style', 'background-image: url(' + sampulbg + ') !important;');
													jQuery('body').css('overflow', 'hidden');

													jQuery('.wdp-button-wrapper button').on('click', function () {
														jQuery('.modalx').addClass('removeModals');
														jQuery('body').css('overflow', 'auto');
													});
												</script>



												<script>
													//var z = document.querySelector('#wdp-button-wrapper');
													//z.addEventListener("click", function(event) {

													//Neo - Added New Conditional Statement for select Audio or Youtube
													var isYT = false;
													jQuery("#wdp-button-wrapper").on("click", "button", function () {
														// var isYT = false;
														playAudio();
														if (document.body.contains(document.getElementById("song"))) {
															document.getElementById("song").play();
															isYT = false;
														} else {
															isYT = true;
															player.playVideo();
														}
														function playAudio() {
															var isYT = false;
															if (document.body.contains(document.getElementById("song"))) {
																document.getElementById("song").play();
																isYT = false;
															} else {
																isYT = true;
																player.playVideo();
															}
														}
													});
												</script>
												<script>
													// decode &amp; and \'
													if (jQuery('.namatamu').length >= 1) {
														let tmpHtmlNamatamu = document.querySelectorAll('.namatamu')
														tmpHtmlNamatamu.forEach((tamuTexts) => {
															tamuTexts.innerHTML = cleanIts(tamuTexts.innerHTML);
														});
													}
													function cleanIts(str) {

														return jQuery("<textarea></textarea>").html(str.replace(/\\/g, "")).text();
													}
												</script>

												<style type="text/css">
													.elementor-button-qr {
														display: inline-block;
														line-height: 1;
														background-color: #818a91;
														font-size: 15px;
														padding: 12px 24px;
														border-radius: 3px;
														color: #fff;
														fill: #fff;
														text-align: center;
														-webkit-transition: all .3s;
														-o-transition: all .3s;
														transition: all .3s;
													}
												</style>

											</div>
										</div>
										<div class="elementor-element elementor-element-42d0aa10 jltma-glass-effect-no elementor-widget elementor-widget-html"
											data-id="42d0aa10" data-element_type="widget"
											data-widget_type="html.default">
											<div class="elementor-widget-container">
												<script>
													function revealElements(selector) {
														var elements = document.querySelectorAll(selector);
														var windowHeight = window.innerHeight;
														var elementVisible = 150;

														elements.forEach(function (element) {
															var elementTop = element.getBoundingClientRect().top;
															if (elementTop < windowHeight - elementVisible) {
																element.classList.add("active");
															} else {
																element.classList.remove("active");
															}
														});
													}

													window.addEventListener("scroll", function () {
														revealElements(".muncul, .muncul-kiri, .muncul-kanan, .zoom");
													});
												</script>

												<style>
													.muncul {
														position: relative;
														transform: translateY(6rem) scale(0.93);
														opacity: 0;
														transition: opacity 0.5s ease, transform 1s ease;
														/* Durasi muncul tanpa delay */
													}

													.muncul.active {
														transform: translateY(0);
														opacity: 1;
													}

													.muncul-kiri {
														position: relative;
														transform: translateX(-100%) scale(0.93);
														opacity: 0;
														transition: opacity 0.5s ease, transform 1s ease;
														/* Durasi muncul tanpa delay */
													}

													.muncul-kiri.active {
														transform: translateX(0);
														opacity: 1;
													}

													.muncul-kanan {
														position: relative;
														transform: translateX(100%) scale(0.93);
														opacity: 0;
														transition: opacity 0.5s ease, transform 1s ease;
														/* Durasi muncul tanpa delay */
													}

													.muncul-kanan.active {
														transform: translateX(0);
														opacity: 1;
													}

													.zoom {
														position: relative;
														transform: scale(0.5);
														opacity: 0;
														transition: opacity 0.5s ease, transform 1.5s ease;
														/* Durasi muncul tanpa delay */
													}

													.zoom.active {
														transform: scale(1);
														opacity: 1;
													}
												</style>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
						<section
							class="has_ma_el_bg_slider elementor-section elementor-inner-section elementor-element elementor-element-344d13 elementor-section-full_width elementor-section-height-default elementor-section-height-default jltma-glass-effect-no"
							data-id="344d13" data-element_type="section">
							<div class="elementor-container elementor-column-gap-default">
								<div class="wdp-column-sticky has_ma_el_bg_slider elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-4d014a37 wdp-sticky-section-positon--bottom jltma-glass-effect-no"
									data-type="column" data-top_spacing="" data-id="4d014a37"
									data-element_type="column">
									<div class="elementor-widget-wrap elementor-element-populated">
										<div class="elementor-element elementor-element-810bc20 elementor-view-stacked elementor-shape-circle jltma-glass-effect-no elementor-widget elementor-widget-weddingpress-audio"
											data-id="810bc20" data-element_type="widget"
											data-settings="{&quot;loop&quot;:&quot;yes&quot;}"
											data-widget_type="weddingpress-audio.default">
											<div class="elementor-widget-container">
												<script>
													var settingAutoplay = 'yes';
													window.settingAutoplay = settingAutoplay === 'disable' ? false : true;
												</script>

												<div id="audio-container" class="audio-box">



							{{-- Elemen <audio> selalu ada — skrip bawaan (wdp.min.js & Smart Audio
							     Control) mencari `#song` lewat id ini. Saat backsound dimatikan,
							     <source> tidak dirender dan tombol speaker disembunyikan lewat
							     CSS di bagian atas. --}}
							<audio id="song" loop>
								@if($backsoundUrl)
									<source src="{{ $backsoundUrl }}">
								@endif
							</audio>



													<div class="elementor-icon-wrapper" id="unmute-sound"
														style="display: none;">
														<div class="elementor-icon">
															<svg aria-hidden="true"
																class="e-font-icon-svg e-far-stop-circle"
																viewBox="0 0 512 512"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M504 256C504 119 393 8 256 8S8 119 8 256s111 248 248 248 248-111 248-248zm-448 0c0-110.5 89.5-200 200-200s200 89.5 200 200-89.5 200-200 200S56 366.5 56 256zm296-80v160c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V176c0-8.8 7.2-16 16-16h160c8.8 0 16 7.2 16 16z">
																</path>
															</svg>
														</div>
													</div>

													<div class="elementor-icon-wrapper" id="mute-sound"
														style="display: none;">
														<div class="elementor-icon">
															<svg aria-hidden="true"
																class="e-font-icon-svg e-fas-compact-disc"
																viewBox="0 0 496 512"
																xmlns="http://www.w3.org/2000/svg">
																<path
																	d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zM88 256H56c0-105.9 86.1-192 192-192v32c-88.2 0-160 71.8-160 160zm160 96c-53 0-96-43-96-96s43-96 96-96 96 43 96 96-43 96-96 96zm0-128c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z">
																</path>
															</svg>
														</div>
													</div>

												</div>

											</div>
										</div>
									</div>
								</div>
								<div class="has_ma_el_bg_slider elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-1769291f elementor-hidden-desktop elementor-hidden-tablet elementor-hidden-mobile jltma-glass-effect-no"
									data-id="1769291f" data-element_type="column">
									<div class="elementor-widget-wrap">
									</div>
								</div>
								<div class="has_ma_el_bg_slider elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-3d6f67d1 elementor-hidden-desktop elementor-hidden-tablet elementor-hidden-mobile jltma-glass-effect-no"
									data-id="3d6f67d1" data-element_type="column">
									<div class="elementor-widget-wrap">
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</section>
	</div>
	<script type="speculationrules">
{"prefetch":[{"source":"document","where":{"and":[{"href_matches":"/*"},{"not":{"href_matches":["/wp-*.php","/wp-admin/*","/wp-content/uploads/*","/wp-content/*","/wp-content/plugins/*","/wp-content/themes/hello-elementor/*","/*\\?(.+)"]}},{"not":{"selector_matches":"a[rel~=\"nofollow\"]"}},{"not":{"selector_matches":".no-prefetch, .no-prefetch a"}}]},"eagerness":"conservative"}]}
</script>
	<script>
		var txt = document.getElementById('cui-link-31261').innerHTML;
		var pos = txt
			.replace(/Comments/g, "Wishes")
		document.getElementById('cui-link-31261').innerHTML = pos;

		var txt2 = document.getElementById('commentform-31261').innerHTML;
		var pos2 = txt2
			.replace(/Nama/g, "Name")
			.replace(/Ucapan/g, "Ucapan")
			.replace(/Konfirmasi Kehadiran/g, "Konfirmasi Kehadiran")
			.replace(/Datang/g, "Hadir")
			.replace(/Absen/g, "Tidak Hadir")
			.replace(/Mungkin/g, "Masih Ragu")
			.replace(/Kirim/g, "KIRIM");
		document.getElementById('commentform-31261').innerHTML = pos2;
	</script>
	<script id="wdp-swiper-js-js" src="/assets/vintage/vendor/wdp-swiper.min.js"></script>
	<script id="weddingpress-qr-js" src="/assets/vintage/vendor/qr-code.js"></script>
	<script id="exad-main-script-js" src="/assets/vintage/vendor/exad-scripts.min.js?ver=3.1.12"></script>
	<script id="qr-code-styling-js" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
	<script id="kirimkit-js" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
	<script id="cui_js_script-js-extra">
		var CUI_WP = { "ajaxurl": "https://ourinvidigi.com/wp-admin/admin-ajax.php", "cuiNonce": "201f18d7e1", "jpages": "false", "jPagesNum": "10", "textCounter": "true", "textCounterNum": "500", "widthWrap": "", "autoLoad": "true", "thanksComment": "Thanks for your comment!", "thanksReplyComment": "Thanks for answering the comment!", "duplicateComment": "You might have left one of the fields blank, or duplicate comments", "accept": "Accept", "cancel": "Cancel", "reply": "Reply", "textWriteComment": "Ucapan", "classPopularComment": "cui-popular-comment", "textToDisplay": "Text to display", "textCharacteresMin": "2 characters minimum", "textNavNext": "Next", "textNavPrev": "Previous", "textMsgDeleteComment": "Do you want delete this comment?", "textLoadMore": "Load more" };
		//# sourceURL=cui_js_script-js-extra
	</script>
	<script id="cui_js_script-js" src="/assets/vintage/vendor/cui_script.js?ver=1.0.0"></script>
	<script id="cui_textCounter-js" src="/assets/vintage/vendor/jquery.textareaCounter.js?ver=2.0"></script>
	<script id="cui_placeholder-js" src="/assets/vintage/vendor/jquery.placeholder.min.js?ver=2.0.7"></script>
	<script id="cui_autosize-js" src="/assets/vintage/vendor/autosize.min.js?ver=1.14"></script>
	<script id="hello-theme-frontend-js" src="/assets/vintage/vendor/hello-frontend.js?ver=3.5.1"></script>
	<script id="elementor-webpack-runtime-js" src="/assets/vintage/vendor/webpack.runtime.min.js?ver=3.30.3"></script>
	<script id="elementor-frontend-modules-js" src="/assets/vintage/vendor/frontend-modules.min.js?ver=3.30.3"></script>
	<script id="jquery-ui-core-js-before">
		jQuery.uiBackCompat = true;
		//# sourceURL=jquery-ui-core-js-before
	</script>
	<script id="jquery-ui-core-js" src="/assets/vintage/vendor/core.min.js?ver=1.14.2"></script>
	<script id="elementor-frontend-js-before">
		var elementorFrontendConfig = { "environmentMode": { "edit": false, "wpPreview": false, "isScriptDebug": false }, "i18n": { "shareOnFacebook": "Share on Facebook", "shareOnTwitter": "Share on Twitter", "pinIt": "Pin it", "download": "Download", "downloadImage": "Download image", "fullscreen": "Fullscreen", "zoom": "Zoom", "share": "Share", "playVideo": "Play Video", "previous": "Previous", "next": "Next", "close": "Close", "a11yCarouselPrevSlideMessage": "Previous slide", "a11yCarouselNextSlideMessage": "Next slide", "a11yCarouselFirstSlideMessage": "This is the first slide", "a11yCarouselLastSlideMessage": "This is the last slide", "a11yCarouselPaginationBulletMessage": "Go to slide" }, "is_rtl": false, "breakpoints": { "xs": 0, "sm": 480, "md": 768, "lg": 1025, "xl": 1440, "xxl": 1600 }, "responsive": { "breakpoints": { "mobile": { "label": "Mobile Portrait", "value": 767, "default_value": 767, "direction": "max", "is_enabled": true }, "mobile_extra": { "label": "Mobile Landscape", "value": 880, "default_value": 880, "direction": "max", "is_enabled": false }, "tablet": { "label": "Tablet Portrait", "value": 1024, "default_value": 1024, "direction": "max", "is_enabled": true }, "tablet_extra": { "label": "Tablet Landscape", "value": 1200, "default_value": 1200, "direction": "max", "is_enabled": false }, "laptop": { "label": "Laptop", "value": 1366, "default_value": 1366, "direction": "max", "is_enabled": false }, "widescreen": { "label": "Widescreen", "value": 2400, "default_value": 2400, "direction": "min", "is_enabled": false } }, "hasCustomBreakpoints": false }, "version": "3.30.3", "is_static": false, "experimentalFeatures": { "e_font_icon_svg": true, "additional_custom_breakpoints": true, "container": true, "theme_builder_v2": true, "hello-theme-header-footer": true, "nested-elements": true, "home_screen": true, "global_classes_should_enforce_capabilities": true, "e_opt_in_v4_page": true }, "urls": { "assets": "\/assets\/vintage\/vendor\/elementor-assets\/", "ajaxurl": "https:\/\/ourinvidigi.com\/wp-admin\/admin-ajax.php", "uploadUrl": "https:\/\/ourinvidigi.com\/wp-content\/uploads" }, "nonces": { "floatingButtonsClickTracking": "732d7cb388" }, "swiperClass": "swiper", "settings": { "page": [], "editorPreferences": [] }, "kit": { "active_breakpoints": ["viewport_mobile", "viewport_tablet"], "global_image_lightbox": "yes", "lightbox_enable_counter": "yes", "lightbox_enable_fullscreen": "yes", "lightbox_enable_zoom": "yes", "lightbox_enable_share": "yes", "lightbox_title_src": "title", "lightbox_description_src": "description", "hello_header_logo_type": "title", "hello_footer_logo_type": "logo" }, "post": { "id": 31261, "title": "vintage2%20-%20ourinvidigi.com", "excerpt": "", "featuredImage": false } };
		//# sourceURL=elementor-frontend-js-before
	</script>
	<script id="elementor-frontend-js" src="/assets/vintage/vendor/elementor-frontend.min.js?ver=3.30.3"></script>
	<script id="e-sticky-js" src="/assets/vintage/vendor/jquery.sticky.min.js?ver=3.29.2"></script>
	<script id="lottie-js" src="/assets/vintage/vendor/lottie.min.js?ver=5.6.6"></script>
	<script id="swiper-js" src="/assets/vintage/vendor/swiper.min.js?ver=8.4.5"></script>
	<script id="elementor-gallery-js" src="/assets/vintage/vendor/e-gallery.min.js?ver=1.2.0"></script>
	<script id="master-addons-plugins-js" src="/assets/vintage/vendor/plugins.js?ver=2.0.7.5"></script>
	<script id="master-addons-scripts-js-extra">
		var jltma_scripts = { "plugin_url": "https://ourinvidigi.com/wp-content/plugins/master-addons", "ajaxurl": "https://ourinvidigi.com/wp-admin/admin-ajax.php", "nonce": "master-addons-elementor" };
		var jltma_data_table_vars = { "lengthMenu": "Display _MENU_ records per page", "zeroRecords": "Nothing found - sorry", "info": "Showing page _PAGE_ of _PAGES_", "infoEmpty": "No records available", "infoFiltered": "(filtered from _MAX_ total records)", "searchPlaceholder": "Search...", "processing": "Processing...", "csvHtml5": "CSV", "excelHtml5": "Excel", "pdfHtml5": "PDF", "print": "Print" };
		var jltma_scripts = { "plugin_url": "https://ourinvidigi.com/wp-content/plugins/master-addons", "ajaxurl": "https://ourinvidigi.com/wp-admin/admin-ajax.php", "nonce": "master-addons-elementor" };
		var jltma_data_table_vars = { "lengthMenu": "Display _MENU_ records per page", "zeroRecords": "Nothing found - sorry", "info": "Showing page _PAGE_ of _PAGES_", "infoEmpty": "No records available", "infoFiltered": "(filtered from _MAX_ total records)", "searchPlaceholder": "Search...", "processing": "Processing...", "csvHtml5": "CSV", "excelHtml5": "Excel", "pdfHtml5": "PDF", "print": "Print" };
		//# sourceURL=master-addons-scripts-js-extra
	</script>
	<script id="master-addons-scripts-js" src="/assets/vintage/vendor/master-addons-scripts.js?ver=2.0.7.5"></script>
	<script id="master-addons-particles-js" src="/assets/vintage/vendor/particles.min.js?ver=2.0.7.5"></script>
	<script id="elementor-pro-webpack-runtime-js"
		src="/assets/vintage/vendor/webpack-pro.runtime.min.js?ver=3.29.2"></script>
	<script id="wp-hooks-js" src="/assets/vintage/vendor/hooks.min.js?ver=f0f188028580e8dc1255"></script>
	<script id="wp-i18n-js" src="/assets/vintage/vendor/i18n.min.js?ver=1dfe7db3940c23ea9216"></script>
	<script id="wp-i18n-js-after">
		wp.i18n.setLocaleData({ 'text direction\u0004ltr': ['ltr'] });
		//# sourceURL=wp-i18n-js-after
	</script>
	<script id="elementor-pro-frontend-js-before">
		var ElementorProFrontendConfig = { "ajaxurl": "https:\/\/ourinvidigi.com\/wp-admin\/admin-ajax.php", "nonce": "97e0cbbad0", "urls": { "assets": "\/assets\/vintage\/vendor\/elementor-pro-assets\/", "rest": "https:\/\/ourinvidigi.com\/wp-json\/" }, "settings": { "lazy_load_background_images": false }, "popup": { "hasPopUps": false }, "shareButtonsNetworks": { "facebook": { "title": "Facebook", "has_counter": true }, "twitter": { "title": "Twitter" }, "linkedin": { "title": "LinkedIn", "has_counter": true }, "pinterest": { "title": "Pinterest", "has_counter": true }, "reddit": { "title": "Reddit", "has_counter": true }, "vk": { "title": "VK", "has_counter": true }, "odnoklassniki": { "title": "OK", "has_counter": true }, "tumblr": { "title": "Tumblr" }, "digg": { "title": "Digg" }, "skype": { "title": "Skype" }, "stumbleupon": { "title": "StumbleUpon", "has_counter": true }, "mix": { "title": "Mix" }, "telegram": { "title": "Telegram" }, "pocket": { "title": "Pocket", "has_counter": true }, "xing": { "title": "XING", "has_counter": true }, "whatsapp": { "title": "WhatsApp" }, "email": { "title": "Email" }, "print": { "title": "Print" }, "x-twitter": { "title": "X" }, "threads": { "title": "Threads" } }, "facebook_sdk": { "lang": "en_US", "app_id": "" }, "lottie": { "defaultAnimationUrl": "https:\/\/ourinvidigi.com\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json" } };
		//# sourceURL=elementor-pro-frontend-js-before
	</script>
	<script id="elementor-pro-frontend-js"
		src="/assets/vintage/vendor/elementor-pro-frontend.min.js?ver=3.29.2"></script>
	<script id="pro-elements-handlers-js" src="/assets/vintage/vendor/elements-handlers.min.js?ver=3.29.2"></script>
	<script id="bdt-uikit-js" src="/assets/vintage/vendor/bdt-uikit.js?ver=3.15.1"></script>
	<script id="weddingpress-wdp-js-extra">
		var cevar = { "ajax_url": "https://ourinvidigi.com/wp-admin/admin-ajax.php", "plugin_url": "https://ourinvidigi.com/wp-content/plugins/weddingpress/" };
		//# sourceURL=weddingpress-wdp-js-extra
	</script>
	<script id="weddingpress-wdp-js" src="/assets/vintage/vendor/wdp.min.js?ver=3.1.12"></script>
	<script id="kirim-kit-js-extra">
		var sendkit_ajax = { "ajax_url": "https://ourinvidigi.com/wp-admin/admin-ajax.php" };
		//# sourceURL=kirim-kit-js-extra
	</script>
	<script id="kirim-kit-js" src="/assets/vintage/vendor/guest-form.js?ver=3.1.12"></script>
	<script id="wp-emoji-settings" type="application/json">
{"baseUrl":"https://s.w.org/images/core/emoji/17.0.2/72x72/","ext":".png","svgUrl":"https://s.w.org/images/core/emoji/17.0.2/svg/","svgExt":".svg","source":{"concatemoji":"/assets/vintage/vendor/wp-emoji-release.min.js?ver=7.1"}}
</script>
	<script type="module">
		/*! This file is auto-generated */
		var e = "script#wp-emoji-settings", t = document.querySelector(e); if (!(t instanceof HTMLScriptElement)) throw new Error("Element missing: " + e); const r = JSON.parse(t.text), s = (window._wpemojiSettings = r, "wpEmojiSettingsSupports"), o = ["flag", "emoji"]; function i(e) { try { var t = { supportTests: e, timestamp: (new Date).valueOf() }; sessionStorage.setItem(s, JSON.stringify(t)) } catch (e) { } } function c(e, t, n) { e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(t, 0, 0); t = new Uint32Array(e.getImageData(0, 0, e.canvas.width, e.canvas.height).data); e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(n, 0, 0); const r = new Uint32Array(e.getImageData(0, 0, e.canvas.width, e.canvas.height).data); return t.every((e, t) => e === r[t]) } function p(e, t) { e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(t, 0, 0); var n = e.getImageData(16, 16, 1, 1); for (let e = 0; e < n.data.length; e++)if (0 !== n.data[e]) return !1; return !0 } function u(e, t, n, r) { switch (t) { case "flag": return n(e, "\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f", "\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f") ? !1 : !n(e, "\ud83c\udde8\ud83c\uddf6", "\ud83c\udde8\u200b\ud83c\uddf6") && !n(e, "\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f", "\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f"); case "emoji": return !r(e, "\ud83e\u1fac8") }return !1 } function f(e, t, n, r) { let a; const s = (a = "undefined" != typeof WorkerGlobalScope && self instanceof WorkerGlobalScope ? new OffscreenCanvas(300, 150) : document.createElement("canvas")).getContext("2d", { willReadFrequently: !0 }), o = (s.textBaseline = "top", s.font = "600 32px Arial", {}); return e.forEach(e => { o[e] = t(s, e, n, r) }), o } function a(e) { var t = document.createElement("script"); t.src = e, t.defer = !0, document.head.appendChild(t) } r.supports = { everything: !0, everythingExceptFlag: !0 }, new Promise(t => { let n = function () { try { var e = JSON.parse(sessionStorage.getItem(s)); if ("object" == typeof e && "number" == typeof e.timestamp && (new Date).valueOf() < e.timestamp + 604800 && "object" == typeof e.supportTests) return e.supportTests } catch (e) { } return null }(); if (!n) { if ("undefined" != typeof Worker && "undefined" != typeof OffscreenCanvas && "undefined" != typeof URL && URL.createObjectURL && "undefined" != typeof Blob) try { var e = "postMessage(" + f.toString() + "(" + [JSON.stringify(o), u.toString(), c.toString(), p.toString()].join(",") + "));", r = new Blob([e], { type: "text/javascript" }); const a = new Worker(URL.createObjectURL(r), { name: "wpTestEmojiSupports" }); return void (a.onmessage = e => { i(n = e.data), a.terminate(), t(n) }) } catch (e) { } i(n = f(o, u, c, p)) } t(n) }).then(e => { for (const n in e) r.supports[n] = e[n], r.supports.everything = r.supports.everything && r.supports[n], "flag" !== n && (r.supports.everythingExceptFlag = r.supports.everythingExceptFlag && r.supports[n]); var t; r.supports.everythingExceptFlag = r.supports.everythingExceptFlag && !r.supports.flag, r.supports.everything || ((t = r.source || {}).concatemoji ? a(t.concatemoji) : t.wpemoji && t.twemoji && (a(t.twemoji), a(t.wpemoji))) });
		//# sourceURL=/assets/vintage/vendor/wp-emoji-loader.min.js
	</script>
	<script>
		//*Script Smart Audio Control by WeddingPress

		const audioElement = document.getElementById("song");

		document.addEventListener("visibilitychange", () => {
			if (document.visibilityState === "hidden") {
				if (audioElement && !audioElement.paused) {
					audioElement.pause();
				}

				if (typeof player !== "undefined" && player.getPlayerState) {
					if (player.getPlayerState() === YT.PlayerState.PLAYING || player.getPlayerState() === YT.PlayerState.BUFFERING) {
						player.pauseVideo();
					}
				}
			} else if (document.visibilityState === "visible") {
				if (audioElement && audioElement.paused) {
					audioElement.play().catch((err) => {
						console.warn("Error saat mencoba memutar audio:", err);
					});
				}

				if (typeof player !== "undefined" && player.getPlayerState) {
					if (player.getPlayerState() !== YT.PlayerState.PLAYING) {
						player.playVideo();
					}
				}
			}
		});

		window.addEventListener("load", () => {
			if (audioElement) {
				audioElement.play().catch((err) => {
					console.warn("Error saat mencoba memutar audio saat halaman dimuat:", err);
				});
			}
		});

	</script>

	<script id="custom-vintage-interactive">
		document.addEventListener('DOMContentLoaded', function () {
			// 1. Reveal animated elements immediately or on scroll (prevent permanent hidden state)
			function checkVisibility() {
				var elements = document.querySelectorAll('.elementor-invisible');
				var windowHeight = window.innerHeight;
				elements.forEach(function (el) {
					var rect = el.getBoundingClientRect();
					if (rect.top <= windowHeight * 0.95) {
						var anim = 'zoomIn';
						try {
							var settings = JSON.parse(el.getAttribute('data-settings') || '{}');
							anim = settings._animation || settings.animation || 'zoomIn';
						} catch (e) { }
						el.classList.remove('elementor-invisible');
						el.classList.add('animated', anim, 'is-visible');
					}
				});
			}

			// Run on load and after opening invitation
			setTimeout(checkVisibility, 200);
			setTimeout(checkVisibility, 800);
			window.addEventListener('scroll', checkVisibility, { passive: true });

			// 2. Welcome Modal dismiss handler & audio start
			var openBtn = document.querySelector('.wdp-button-wrapper button, .wdp-button-wrapper a');
			var modal = document.querySelector('.modalx');
			if (openBtn && modal) {
				openBtn.addEventListener('click', function (e) {
					modal.classList.add('removeModals');
					document.body.style.overflow = 'auto';
					setTimeout(checkVisibility, 300);
					setTimeout(checkVisibility, 800);
				});
			}

			// 3. Nested Accordion Toggle (Wedding Gift)
			document.querySelectorAll('.e-n-accordion-item-title').forEach(function (summary) {
				summary.addEventListener('click', function (e) {
					var details = this.closest('details');
					if (!details) return;
					// Toggle open state
					setTimeout(function () {
						var isOpen = details.hasAttribute('open');
						var openedIcon = summary.querySelector('.e-opened');
						var closedIcon = summary.querySelector('.e-closed');
						if (openedIcon && closedIcon) {
							openedIcon.style.display = isOpen ? 'flex' : 'none';
							closedIcon.style.display = isOpen ? 'none' : 'flex';
						}
					}, 10);
				});
			});

			// 4. Fallback Swiper initialization for 'The Moments Of' carousel
			if (typeof Swiper !== 'undefined') {
				var carouselEl = document.querySelector('.elementor-image-carousel-wrapper.swiper');
				if (carouselEl && !carouselEl.swiper) {
					new Swiper(carouselEl, {
						slidesPerView: 2,
						spaceBetween: 10,
						loop: true,
						autoplay: {
							delay: 3000,
							disableOnInteraction: false
						},
						speed: 1500,
						pagination: {
							el: '.swiper-pagination',
							clickable: true
						}
					});
				}
			}

			// 5. Countdown background slideshow animation.
			// Photos come from Dashboard > Settings > Foto (Background Slide 1 & 2).
			// These used to be hardcoded, which silently overwrote any uploaded photo
			// every 5 seconds.
			var bgImages = {!! json_encode(array_values(array_filter([
	$template->getAssetUrl('bg_slide_1', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_35_26-AM.jpg'),
	$template->getAssetUrl('bg_slide_2', 'assets/vintage/vendor/ChatGPT-Image-Jul-23-2026-08_36_39-AM.jpg'),
]))) !!};
			var bgCurrent = 0;
			var bgContainer = document.querySelector('.elementor-element-79047782');
			if (bgContainer && bgImages.length > 1) {
				setInterval(function () {
					bgCurrent = (bgCurrent + 1) % bgImages.length;
					bgContainer.style.backgroundImage = 'url("' + bgImages[bgCurrent] + '")';
				}, 5000);
			}
		});
	</script>

	{{-- Lightbox galeri — dibuka saat foto di tampilan Grid diklik --}}
	<div class="wdp-lightbox" id="wdpGalleryLightbox" role="dialog" aria-modal="true" aria-hidden="true"
		aria-label="Foto galeri">
		<div class="wdp-lightbox__bar">
			<span class="wdp-lightbox__counter" data-lb-counter></span>
			<span class="wdp-lightbox__caption" data-lb-caption></span>
			<button type="button" data-lb-close aria-label="Tutup">&#10005;</button>
		</div>
		<div class="wdp-lightbox__stage" data-lb-stage>
			<button type="button" data-lb-prev aria-label="Foto sebelumnya">&#8249;</button>
			<img src="" alt="" data-lb-image />
			<button type="button" data-lb-next aria-label="Foto berikutnya">&#8250;</button>
		</div>
	</div>

	<script>
		// ==================== GALLERY: SLIDE / GRID + LIGHTBOX ====================
		(function () {
			var switcher = document.querySelector('[data-gallery-switch]');
			var grid = document.querySelector('.wdp-gallery-grid');
			var panels = document.querySelectorAll('[data-gallery-panel]');
			var carousel = document.querySelector('.elementor-image-carousel-wrapper.swiper');

			// --- Slide / Grid switch
			if (switcher && panels.length) {
				switcher.addEventListener('click', function (e) {
					var btn = e.target.closest('[data-gallery-view]');
					if (!btn) return;
					var view = btn.getAttribute('data-gallery-view');

					switcher.querySelectorAll('[data-gallery-view]').forEach(function (b) {
						var on = b === btn;
						b.classList.toggle('is-active', on);
						b.setAttribute('aria-selected', on ? 'true' : 'false');
					});
					panels.forEach(function (panel) {
						panel.hidden = panel.getAttribute('data-gallery-panel') !== view;
					});

					// A Swiper built while hidden has zero width — recalculate.
					if (view === 'slide' && carousel && carousel.swiper) {
						setTimeout(function () { carousel.swiper.update(); }, 60);
					}
				});
			}

			// --- Panah navigasi mode Slide
			// Menggerakkan instance Swiper yang sudah ada (dibuat Elementor, atau
			// oleh fallback di template ini) supaya tamu bisa pindah foto tanpa
			// harus membuka lightbox lebih dulu.
			Array.prototype.forEach.call(document.querySelectorAll('[data-gallery-nav]'), function (btn) {
				btn.addEventListener('click', function () {
					var swiper = carousel && carousel.swiper;
					if (!swiper) return;

					// Sama seperti panah bawaan Elementor: geser satu foto. Autoplay
					// ditangani Swiper sendiri (template ini memakai
					// `pause_on_interaction: yes`, jadi autoplay berhenti setelah tamu
					// menggeser foto secara manual).
					if ((parseInt(btn.getAttribute('data-gallery-nav'), 10) || 1) < 0) {
						swiper.slidePrev();
					} else {
						swiper.slideNext();
					}
				});
			});

			// --- Lightbox
			var box = document.getElementById('wdpGalleryLightbox');
			if (!box) return;

			var image = box.querySelector('[data-lb-image]');
			var caption = box.querySelector('[data-lb-caption]');
			var counter = box.querySelector('[data-lb-counter]');
			var current = 0;
			var previousOverflow = '';

			// Daftar foto untuk lightbox (urutannya sama dengan grid: 0..n-1).
			function thumbs() {
				return Array.prototype.slice.call(
					(grid || document).querySelectorAll('[data-gallery-src]')
				);
			}

			function show(index) {
				var list = thumbs();
				if (!list.length) return;
				current = (index + list.length) % list.length;
				var thumb = list[current];
				var text = thumb.getAttribute('data-gallery-caption') || '';
				image.setAttribute('src', thumb.getAttribute('data-gallery-src'));
				image.setAttribute('alt', text);
				if (caption) caption.textContent = text;
				if (counter) counter.textContent = (current + 1) + ' / ' + list.length;
			}			// Autoplay carousel dihentikan selama lightbox terbuka supaya foto di
			// belakang tidak terus bergeser, lalu dilanjutkan lagi saat lightbox
			// ditutup. `carousel` bisa null kalau widget-nya tidak dirender.
			var wasAutoplaying = false;

			function pauseCarousel() {
				var swiper = carousel && carousel.swiper;
				if (!swiper || !swiper.autoplay) return;
				wasAutoplaying = !!swiper.autoplay.running;
				swiper.autoplay.stop();
			}

			function resumeCarousel() {
				var swiper = carousel && carousel.swiper;
				if (!swiper || !swiper.autoplay || !wasAutoplaying) return;
				wasAutoplaying = false;
				swiper.autoplay.start();
			}

			function open(index) {
				previousOverflow = document.body.style.overflow;
				show(index);
				// Status autoplay hanya dicatat saat lightbox benar-benar baru
				// dibuka, supaya berpindah-pindah foto tidak menghilangkannya.
				if (!box.classList.contains('is-open')) pauseCarousel();
				box.classList.add('is-open');
				box.setAttribute('aria-hidden', 'false');
				document.body.style.overflow = 'hidden';
			}

			function close() {
				box.classList.remove('is-open');
				box.setAttribute('aria-hidden', 'true');
				document.body.style.overflow = previousOverflow;
				resumeCarousel();
			}

			// Foto di mode Slide maupun Grid sama-sama bisa diklik untuk membuka
			// lightbox. Tiap foto membawa `data-gallery-index` yang sama dengan
			// urutannya di daftar (grid), jadi index itu dipakai langsung.
			var pointerStart = null;
			document.addEventListener('pointerdown', function (e) {
				pointerStart = { x: e.clientX, y: e.clientY };
			}, true);

			document.addEventListener('click', function (e) {
				var target = e.target.closest('[data-gallery-index]');
				if (!target) return;

				// Geseran/gesekan carousel tidak dianggap klik: kalau posisi pointer
				// bergeser cukup jauh antara pointerdown dan click, biarkan Swiper
				// yang menanganinya. `detail === 0` berarti klik dari keyboard
				// (Enter/Spasi) — tidak ada koordinat pointer, jadi selalu dibuka.
				if (e.detail > 0 && pointerStart &&
					(Math.abs(e.clientX - pointerStart.x) > 10 ||
						Math.abs(e.clientY - pointerStart.y) > 10)) {
					return;
				}

				var index = parseInt(target.getAttribute('data-gallery-index'), 10);
				open(isNaN(index) ? 0 : index);
			});

			box.addEventListener('click', function (e) {
				if (e.target === box || e.target.classList.contains('wdp-lightbox__stage')) { close(); return; }
				if (e.target.closest('[data-lb-close]')) { close(); return; }
				if (e.target.closest('[data-lb-prev]')) { show(current - 1); return; }
				if (e.target.closest('[data-lb-next]')) { show(current + 1); }
			});

			document.addEventListener('keydown', function (e) {
				if (!box.classList.contains('is-open')) return;
				if (e.key === 'Escape') close();
				else if (e.key === 'ArrowLeft') show(current - 1);
				else if (e.key === 'ArrowRight') show(current + 1);
			});

			// Swipe on touch devices
			var touchStartX = null;
			box.addEventListener('touchstart', function (e) { touchStartX = e.touches[0].clientX; }, { passive: true });
			box.addEventListener('touchend', function (e) {
				if (touchStartX === null) return;
				var dx = e.changedTouches[0].clientX - touchStartX;
				if (Math.abs(dx) > 40) show(current + (dx < 0 ? 1 : -1));
				touchStartX = null;
			});
		})();

		function submitBestWishes(e) {
			e.preventDefault();
			var form = document.getElementById('bestWishesForm');
			if (!form) return;

			// Select kehadiran disembunyikan dan digantikan tombol Hadir/Tidak
			// Hadir, jadi `required` bawaan HTML tidak dipakai — divalidasi di sini.
			var attendanceField = form.querySelector('[name="attendance"]');
			if (attendanceField && !attendanceField.value) {
				alert('Mohon konfirmasi kehadiran anda terlebih dahulu.');
				return;
			}

			var btn = form.querySelector('input[type="submit"]');
			var origText = btn.value;
			btn.value = 'Mengirim...';
			btn.disabled = true;
			var formData = new FormData(form);
			fetch(form.action, {
				method: 'POST',
				headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
				body: formData
			}).then(function (r) { return r.json(); }).then(function (d) {					if (d.success) {
						btn.value = 'Terkirim!';

						// Hanya ucapan yang dikosongkan; nama, kehadiran, dan jumlah
						// tamu dibiarkan supaya tamu bisa menambah ucapan lain.
						var textarea = form.querySelector('textarea[name="message"]');
						if (textarea) textarea.value = '';

					if (d.message_data && window.wdpWishes) {
						window.wdpWishes.push(d.message_data);
					}

					setTimeout(function () { btn.value = origText; btn.disabled = false; }, 2000);
				} else {
					alert(d.message || 'Gagal mengirim');
					btn.value = origText;
					btn.disabled = false;
				}
			}).catch(function () {
				alert('Terjadi kesalahan');
				btn.value = origText;
				btn.disabled = false;
			});
		}

		// ============ DAFTAR UCAPAN: 5 terbaru + auto load sisanya ============
		// 5 ucapan terbaru sudah dirender dari server. Sisa ucapan diambil
		// bertahap dari endpoint JSON saat daftar di-scroll ke bawah atau saat
		// tombol "Lihat semua ucapan" ditekan.
		window.wdpWishes = (function () {
			var box = document.querySelector('[data-wishes]');
			if (!box) return { push: function () { } };

			var list = box.querySelector('.cui-container-comments');
			var scroller = box.querySelector('[data-wishes-scroll]');
			var status = box.querySelector('[data-wishes-status]');
			var moreBtn = box.querySelector('[data-wishes-more]');
			var countEl = box.querySelector('[data-wishes-count]');
			var endpoint = box.getAttribute('data-endpoint');
			var pageSize = 5;
			var total = parseInt(box.getAttribute('data-total'), 10) || 0;
			var loaded = parseInt(box.getAttribute('data-loaded'), 10) || 0;
			var loading = false;

			function escapeHtml(value) {
				return String(value === null || value === undefined ? '' : value)
					.replace(/&/g, '&amp;')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;')
					.replace(/"/g, '&quot;')
					.replace(/'/g, '&#39;');
			}

			function itemHtml(message) {
				return '<li class="cui-item-comment"><div class="cui-comment-content">' +
					'<div class="cui-comment-info"><span class="cui-commenter-name">' + escapeHtml(message.name || 'Tamu Undangan') + '</span>' +
					'<span class="cui-comment-time">' + escapeHtml(message.date || '') + '</span></div>' +
					'<div class="cui-comment-text"><p>' + escapeHtml(message.message || '') + '</p></div>' +
					'</div></li>';
			}

			function syncControls() {
				if (countEl) countEl.textContent = total;
				if (moreBtn) moreBtn.hidden = loaded >= total;
			}

			function insert(messages, position) {
				if (!messages.length) return;
				var empty = list.querySelector('.wdp-wishes__empty');
				if (empty) empty.parentNode.removeChild(empty);
				messages.forEach(function (message) {
					list.insertAdjacentHTML(position, itemHtml(message));
				});
			}

			function loadMore(loadAll) {
				if (loading || !endpoint || loaded >= total) return;
				loading = true;
				if (status) status.hidden = false;

				var limit = loadAll ? (total - loaded) : pageSize;

				fetch(endpoint + '?offset=' + loaded + '&limit=' + limit, {
					headers: { 'Accept': 'application/json' }
				}).then(function (response) { return response.json(); }).then(function (data) {
					var messages = (data && data.messages) || [];
					if (typeof data.total === 'number') total = data.total;
					insert(messages, 'beforeend');
					loaded += messages.length;
					loading = false;
					if (status) status.hidden = true;
					if (loaded >= total && scroller) scroller.classList.add('is-expanded');
					syncControls();
					if (loadAll && messages.length && loaded < total) loadMore(true);
				}).catch(function () {
					loading = false;
					if (status) status.hidden = true;
				});
			}

			if (scroller) {
				scroller.addEventListener('scroll', function () {
					if (scroller.scrollTop + scroller.clientHeight >= scroller.scrollHeight - 32) {
						loadMore(false);
					}
				});
			}

			if (moreBtn) {
				moreBtn.addEventListener('click', function () { loadMore(true); });
			}

			syncControls();

			return {
				push: function (message) {
					if (!message) return;
					insert([message], 'afterbegin');
					total += 1;
					loaded += 1;
					syncControls();
					if (scroller) scroller.scrollTop = 0;
				}
			};
		})();
	</script>

	{{-- Footer / penutup undangan. Sengaja diletakkan di luar struktur Elementor
	     yang di-export (yang sarang-nya mudah bergeser) dan diatur lewat CSS
	     `.wdp-footer-section` supaya tidak tertutup kolom kiri yang `fixed`.

	     Bisa disembunyikan dari Dashboard > Settings > Teks
	     (switch "Section Footer (penutup)"). --}}
	@if(($showFooter ?? '1') !== '0')
		<section class="wdp-footer-section" aria-label="Penutup undangan">
			<p class="wdp-footer-eyebrow">Kami Yang Berbahagia</p>
			<h2 class="wdp-footer-couple">{{ $coupleName }}</h2>
			<p class="wdp-footer-thanks">{{ $template->getSetting('footer_message', 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.') }}</p>
			<p class="wdp-footer-copy">&copy; {{ date('Y') }} {{ $coupleName }} Wedding</p>
		</section>
	@endif

</body>

</html>