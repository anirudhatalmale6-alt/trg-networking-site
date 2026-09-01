<?php
/**
 * One-click site setup.
 *
 * Creates the twenty-five pages, the service/industry/testimonial cards and the
 * four menus, then points WordPress at the right front page. Running it twice
 * is safe: anything that already exists is left exactly as it is, so a client
 * who has edited a page will never lose that work to a second click.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

require_once TRG_SITE_DIR . 'inc/content-detail-pages.php';

/**
 * Every page the site ships with.
 *
 * @return array<int,array<string,mixed>>
 */
function trg_page_definitions() {
	$pages = array();

	$pages[] = array(
		'slug'    => 'home',
		'title'   => 'Home',
		'excerpt' => 'Managed IT, cybersecurity, Microsoft 365 and Azure, CMMC readiness and secure AI adoption from a Maryland-based team supporting organizations nationwide. Trusted since 1992.',
		'content' => trg_home_content(),
	);

	$pages[] = array(
		'slug'    => 'services',
		'title'   => 'Services',
		'excerpt' => 'Managed IT, help desk support, cybersecurity, Microsoft 365 and Azure, secure AI adoption, CMMC readiness and business continuity from TRG Networking.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Complete technology care" title="Every layer of your technology, working together." lede="From daily support to long-term strategy, TRG connects the pieces so your technology works as one secure, reliable system."]',
			'[trg_services bg="white" title="" id=""]',
			'[trg_perspective title="One partner is easier than five vendors." body="When support, security, Microsoft and planning sit with the same team, problems stop falling between the gaps — and nobody has to referee."]',
			'[trg_cta_band]',
		) ),
	);

	$pages[] = array(
		'slug'    => 'industries',
		'title'   => 'Industries',
		'excerpt' => 'IT and cybersecurity for construction, manufacturing, government contractors, professional services, healthcare and nonprofits.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Industries we serve" title="Technology aligned with how your organization works." lede="Technology decisions are better when they reflect your operations, risks, customers and compliance responsibilities."]',
			'[trg_industries bg="white" title=""]',
			'[trg_split_points id="healthcare" bg="canvas" eyebrow="HIPAA compliance, EHR support and clinical IT" title="Healthcare" body="Protect patient information and keep clinical systems dependable for the people who rely on them. TRG supports practices with security, access control, backup and responsive help for staff who cannot afford to wait." points="HIPAA-aware security controls|EHR and clinical application support|Secure remote and multi-site access|Backup and recovery for patient data"]',
			'[trg_split_points id="nonprofits" bg="white" eyebrow="Affordable, mission-aligned technology" title="Nonprofits" body="Affordable, mission-aligned technology that stretches limited budgets without cutting corners on security. TRG helps nonprofits get more from donated and discounted Microsoft licensing while keeping donor and constituent data protected." points="Nonprofit Microsoft licensing guidance|Donor and constituent data protection|Predictable, budget-aware planning|Support for volunteers and hybrid staff"]',
			'[trg_cta_band]',
		) ),
	);

	$pages[] = array(
		'slug'    => 'why-trg',
		'title'   => 'Why TRG',
		'excerpt' => 'Responsive by design, plain-English answers, business-minded guidance and long-term relationships — the standards behind the way TRG Networking works since 1992.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Why TRG" title="Experienced enough to guide. Personal enough to care." lede="Since 1992, TRG has built long-term relationships through responsiveness, integrity and practical technology guidance."]',
			'[trg_cards bg="white" columns="3" title="We keep clients through service — not by keeping them in the dark." body="TRG believes you should understand your technology, know what you are paying for and retain appropriate documentation about your environment. Trust is earned through consistent action."]' . "\n"
				. '[trg_card icon="check" title="Responsive by design"]Multiple team members oversee incoming support so requests receive attention and follow-through.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Plain-English answers"]We explain technology without making employees or leadership feel talked down to.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Business-minded guidance"]Recommendations consider cost, risk, usability, operations and long-term value.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Seasoned professionals"]Experienced technical people work together to solve issues and plan improvements.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Proactive care"]Monitoring and maintenance focus on preventing disruption, not simply reacting to it.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Long-term relationships"]We aim to become a trusted extension of the organizations we support.[/trg_card]' . "\n"
				. '[/trg_cards]',
			'[trg_cards bg="canvas" columns="3" eyebrow="What that means in practice" title="The standards behind the work."]' . "\n"
				. '[trg_card icon="check" title="Enterprise experience"]Deep expertise with complex environments and compliance-driven organizations.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Strategic IT leadership"]vCIO services that align technology investments with your business objectives.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Microsoft experts"]Certified Microsoft Partner delivering Azure, 365, Teams and Copilot AI.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Security-first design"]Cybersecurity is built into every solution — not bolted on as an afterthought.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="CMMC and compliance"]A track record of helping defense contractors work toward and maintain compliance.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="AI-ready organization"]Helping teams responsibly adopt AI tools for measurable business impact.[/trg_card]' . "\n"
				. '[/trg_cards]',
			'[trg_perspective title="Every request is seen. Every solution is explained." body="Every recommendation should serve the business. That is the standard behind the way TRG works."]',
			'[trg_cta_band]',
		) ),
	);

	$pages[] = array(
		'slug'    => 'about',
		'title'   => 'About',
		'excerpt' => 'TRG Networking is headquartered in Columbia, Maryland and has supported small and midsize organizations with managed IT, cybersecurity and Microsoft solutions since 1992.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="About TRG Networking" title="Technology has changed since 1992. Our commitment has not." lede="TRG helps organizations use technology confidently through experienced support, thoughtful security and genuine long-term partnership."]',
			'[trg_media_split bg="white" reverse="1" image="about-team" image_alt="The TRG Networking team collaborating with a client" title="Maryland roots. Nationwide support." body="TRG Networking is headquartered in Columbia, Maryland and supports small and midsize organizations locally and across the country. Our work spans managed IT, cybersecurity, Microsoft 365 and Azure, CMMC readiness and secure AI adoption."]',
			'[trg_cards bg="canvas" columns="2"]' . "\n"
				. '[trg_card icon="check" title="Our purpose"]Make technology simpler to manage, safer to use and better aligned with each client’s business.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Our approach"]Listen first, communicate clearly, recommend responsibly and follow through on the work.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Our experience"]More than three decades of adapting to major technology change while supporting real business operations.[/trg_card]' . "\n"
				. '[trg_card icon="check" title="Our location"]Based at 9861 Broken Land Parkway, Suite 100, Columbia, Maryland 21046.[/trg_card]' . "\n"
				. '[/trg_cards]',
			'[trg_perspective title="The right technology relationship should reduce stress — not create more of it." body="TRG works to give leadership and employees confidence that their technology has an experienced team behind it."]',
			'[trg_cta_band]',
		) ),
	);

	$pages[] = array(
		'slug'    => 'contact',
		'title'   => 'Contact',
		'excerpt' => 'Talk with TRG Networking about managed IT, cybersecurity, Microsoft 365, CMMC readiness or secure AI adoption.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Talk with our team" title="Start with a straightforward conversation." lede="Tell us what is working, what is frustrating your team and what you want technology to do better. No technical preparation required."]',
			'[trg_contact_section title="No technical preparation required." body="Whether you are replacing an IT provider, strengthening cybersecurity, preparing for CMMC, reviewing Microsoft 365 or exploring AI, we will help identify a sensible next step."]',
			'[trg_process bg="canvas" eyebrow="What happens next" title="Ready to begin?"]' . "\n" . trg_process_steps() . '[/trg_process]',
			'[trg_perspective title="A conversation costs nothing and usually clarifies a lot." body="Send the form above or call us. We will listen first, then suggest a sensible next step — whether or not that step involves TRG."]',
		) ),
	);

	$pages[] = array(
		'slug'    => 'resources',
		'title'   => 'Resources',
		'excerpt' => 'Practical technology guidance for business leaders — checklists and insights on IT support, cybersecurity, Microsoft 365, CMMC and AI.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Resources" title="Practical technology guidance for business leaders." lede="Clear explanations, useful checklists and timely insights — without unnecessary jargon or fear-based selling."]',
			'[trg_cards bg="white" columns="2" title="Useful content should help someone make a better decision." body="TRG’s resource library prioritizes original guidance built around the questions clients actually ask about IT support, cybersecurity, Microsoft 365, CMMC and AI."]' . "\n"
				. '[trg_card icon="none" title="IT and Security Health Checklist" badge="Coming soon"]A practical starting point for reviewing support, security, backups and technology planning.[/trg_card]' . "\n"
				. '[trg_card icon="none" title="CMMC Readiness Checklist" badge="Coming soon"]Questions government contractors should answer before technical remediation begins.[/trg_card]' . "\n"
				. '[trg_card icon="none" title="Secure AI Policy Starter" badge="Coming soon"]A framework for clarifying approved tools, sensitive information and responsible employee use.[/trg_card]' . "\n"
				. '[trg_card icon="none" title="Technology Insights" badge="Coming soon"]Original articles that connect changing technology to practical business decisions.[/trg_card]' . "\n"
				. '[trg_card icon="none" title="Case studies" link="resources/case-studies" cta="Read client results"]What TRG clients say about responsiveness, communication and cost-effective management of their IT.[/trg_card]' . "\n"
				. '[trg_card icon="none" title="Guides and downloads" link="resources/guides" cta="Browse the guides"]Short, usable checklists built around the questions clients actually ask.[/trg_card]' . "\n"
				. '[/trg_cards]',
			'[trg_perspective title="Want one of these before it is published?" body="Ask us. Call and we will walk you through the checklist on the phone rather than making you wait for a download."]',
			'[trg_cta_band]',
		) ),
	);

	$pages[] = array(
		'slug'    => 'case-studies',
		'title'   => 'Case Studies',
		'parent'  => 'resources',
		'excerpt' => 'What TRG Networking clients say about responsiveness, communication and cost-effective management of their IT.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Client results" title="Real clients. Real relationships." lede="The clearest measure of an IT partner is whether clients stay — and whether they would say so out loud."]',
			'[trg_testimonials bg="white" title=""]',
			'[trg_note title="Written case studies are in progress" button_text="Ask for a reference" button_link="contact"]Detailed write-ups covering CMMC readiness, Azure migrations and managed IT transitions are being prepared with the clients involved. If you would like to speak to a reference in your industry, ask us and we will arrange an introduction.[/trg_note]',
			'[trg_cta_band]',
		) ),
	);

	$pages[] = array(
		'slug'    => 'guides',
		'title'   => 'Guides & Downloads',
		'parent'  => 'resources',
		'excerpt' => 'Practical checklists on IT health, CMMC readiness and secure AI policy from TRG Networking.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Guides and downloads" title="Practical checklists, not lead-magnet filler." lede="Short, usable documents built around the questions clients actually ask."]',
			'[trg_cards bg="white" columns="3"]' . "\n"
				. '[trg_card icon="none" title="IT and Security Health Checklist" badge="Coming soon"]Review support, security, backups and technology planning in one pass.[/trg_card]' . "\n"
				. '[trg_card icon="none" title="CMMC Readiness Checklist" badge="Coming soon"]What government contractors should answer before technical remediation begins.[/trg_card]' . "\n"
				. '[trg_card icon="none" title="Secure AI Policy Starter" badge="Coming soon"]Clarify approved tools, sensitive information and responsible employee use.[/trg_card]' . "\n"
				. '[/trg_cards]',
			'[trg_note title="Want one now?" button_text="Call us" button_link="phone"]Call and we will walk you through it rather than making you wait for a download.[/trg_note]',
			'[trg_cta_band]',
		) ),
	);

	// The twelve service and industry pages, all from one description.
	foreach ( trg_detail_page_data() as $slug => $page ) {
		$pages[] = array(
			'slug'    => $slug,
			'title'   => $page['title'],
			'excerpt' => $page['meta'],
			'content' => trg_detail_page_content( $page ),
		);
	}

	$pages[] = array(
		'slug'    => 'support-center',
		'title'   => 'Support Center',
		'excerpt' => 'Technical support contact details for existing TRG Networking clients.',
		'content' => implode( "\n\n", array(
			'[trg_hero eyebrow="Support Center" title="Welcome to the TRG Networking Support Center." lede="Please use the details below for technical support requests only. For anything else, the contact page is the right place."]',
			'[trg_support_cards]',
			'[trg_cta_band]',
		) ),
	);

	foreach ( trg_legal_pages() as $legal ) {
		$pages[] = $legal;
	}

	return $pages;
}

/**
 * The three "what happens next" steps, shared by the homepage and contact page.
 *
 * @return string
 */
function trg_process_steps() {
	return '[trg_step n="1" title="Talk with our team"]We listen first and learn what your organization needs.[/trg_step]' . "\n"
		. '[trg_step n="2" title="Review your environment"]We identify risks, gaps and opportunities worth addressing.[/trg_step]' . "\n"
		. '[trg_step n="3" title="Build a practical plan"]You receive clear priorities and a sensible path forward.[/trg_step]' . "\n";
}

/**
 * Homepage content.
 *
 * @return string
 */
function trg_home_content() {
	return implode( "\n\n", array(
		'[trg_home_hero eyebrow="Trusted technology partner since 1992" line1="Simpler IT." line2="Stronger security." accent="A team that responds." '
			. 'lede="TRG Networking helps organizations modernize, secure and grow through managed IT, Microsoft Azure and 365, cybersecurity, CMMC readiness and secure AI — delivered by a Maryland-based team supporting businesses nationwide." '
			. 'button_text="Talk with our team" button_link="contact" button2_text="Free IT assessment" button2_link="/contact?type=assessment" '
			. 'badges="Microsoft Partner|24×7 Support|CMMC Readiness|Women/Minority Owned" jump_text="Explore our services" jump_link="#services" '
			. 'image="hero-team" image_alt="A TRG Networking consultant working through a technology plan with a client team" '
			. 'caption_eyebrow="The TRG difference" caption="Technology that feels more human." '
			. 'cards="Responsive support|Real people. Clear ownership.;Security first|Protection built in." '
			. 'strip="Managed IT|Cybersecurity|Microsoft 365|CMMC|Secure AI"]',

		'[trg_stats items="1992|Serving clients since;163+|Organizations served;8|Technology solution areas;24×7|Monitoring and support"]',

		'[trg_partners title="Technology partners and alliances" items="Microsoft, Cisco, Dell, Fortinet, Sophos, Datto, Synnex, ConnectWise"]',

		'[trg_cards bg="canvas" columns="4" eyebrow="Technology should move your business forward" title="Less disruption. More confidence." body="TRG makes technology easier to manage, easier to understand and better aligned with the way your organization actually works."]' . "\n"
			. '[trg_card icon="activity" title="Fewer interruptions"]Proactive monitoring and maintenance help address small issues before they become costly problems.[/trg_card]' . "\n"
			. '[trg_card icon="shield" title="Stronger protection"]Security that covers people, devices, cloud systems, data and the everyday decisions that connect them.[/trg_card]' . "\n"
			. '[trg_card icon="users" title="Responsive attention"]Multiple team members oversee incoming support so requests are seen, assigned and kept moving.[/trg_card]' . "\n"
			. '[trg_card icon="map" title="Clearer planning"]Plain-English recommendations, predictable costs and a practical roadmap for what comes next.[/trg_card]' . "\n"
			. '[/trg_cards]',

		'[trg_services id="services" bg="white" pill="1" eyebrow="Complete technology care" title="One team. Every layer covered." body="From daily support to long-term strategy, TRG connects the pieces so your technology works as one secure, reliable system."]',

		'[trg_media_split bg="canvas" reverse="1" image="lov-support" image_alt="A TRG IT specialist working alongside a client team member" '
			. 'eyebrow="Responsive by design" title="Multiple eyes on every request. One team accountable." '
			. 'body="Your support request should never feel lost in a queue. Multiple people across TRG oversee incoming requests, help ensure the right person is assigned and keep work moving toward resolution." '
			. 'bullets="Human attention — not an anonymous call center|Clear ownership and follow-through|Plain-English communication throughout" '
			. 'button_text="Meet your responsive IT team" button_link="help-desk-it-support" '
			. 'note_title="Shared oversight" note_body="Multiple team members help keep requests moving." note_icon="users"]',

		'[trg_industries bg="white" pill="1" eyebrow="Experience that fits your world" title="We learn how your business works." body="Technology decisions are better when they reflect your operations, risks, customers and compliance responsibilities."]',

		'[trg_ai_panel bg="canvas" eyebrow="Secure AI adoption" title="Use AI with a plan." accent="Not a free-for-all." '
			. 'body="TRG helps your organization adopt AI responsibly — protecting company information while giving employees practical ways to work faster and make better decisions." '
			. 'pills="AI readiness, Usage policies, Microsoft Copilot, Employee training" '
			. 'button_text="Explore secure AI services" button_link="secure-ai-adoption" '
			. 'steps="Protect the data|Security and access first;Set clear policies|Responsible use guidance;Find practical wins|Workflows worth improving;Train the team|Confidence without the hype"]',

		'[trg_testimonials bg="white" pill="1" eyebrow="Trusted relationships" title="Technology expertise. A genuinely personal approach." '
			. 'body="Since 1992, organizations have trusted TRG to care for critical systems, support their people and explain complex decisions without the jargon." '
			. 'cta_text="Why businesses choose TRG" cta_link="why-trg"]',

		'[trg_process bg="canvas" eyebrow="A simple place to begin" title="Start with a conversation." body="No technical preparation required. Tell us what is working, what is frustrating your team and what you want technology to do better."]' . "\n" . trg_process_steps() . '[/trg_process]',

		'[trg_perspective title="The right technology relationship should reduce stress — not create more of it." body="TRG works to give leadership and employees confidence that their technology has an experienced team behind it."]',

		'[trg_cta_band]',
	) );
}

/**
 * Privacy, terms and accessibility.
 *
 * These carry no hero shortcode on purpose: page.php then builds the hero from
 * the title and excerpt and puts the content in a readable column, so the copy
 * is ordinary blocks the client can edit visually.
 *
 * @return array<int,array<string,mixed>>
 */
function trg_legal_pages() {
	$block = static function ( $heading, $paragraphs ) {
		$out = '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . $heading . '</h2><!-- /wp:heading -->' . "\n";
		foreach ( (array) $paragraphs as $p ) {
			$out .= '<!-- wp:paragraph --><p>' . $p . '</p><!-- /wp:paragraph -->' . "\n";
		}
		return $out;
	};

	$privacy = $block( 'Information we collect', 'When you submit the enquiry form on this site we collect the name, email address, company, phone number, service of interest and message you provide. We also record the date and the IP address the submission came from, which helps us block automated abuse.' )
		. $block( 'How we use it', 'We use these details only to respond to your enquiry and to follow up about the services you asked about. We do not sell your information, and we do not add you to a marketing list without your agreement.' )
		. $block( 'How it is stored', 'Enquiries are emailed to our team and stored in this website’s dashboard, and are retained for as long as needed to serve you and to meet our record-keeping obligations.' )
		. $block( 'Third parties', 'This site loads web fonts from Google Fonts, which means Google receives the IP address of visitors. We do not run advertising trackers on this site.' )
		. $block( 'Your choices', 'You can ask us what information we hold about you, ask us to correct it, or ask us to delete it. Contact us using the details in the footer of this page.' )
		. $block( 'Contact', 'TRG Networking, Inc., 9861 Broken Land Parkway, Suite 100, Columbia, Maryland 21046.' );

	$terms = $block( 'About this site', 'This website is operated by TRG Networking, Inc. By using it you agree to these terms. If you do not agree, please do not use the site.' )
		. $block( 'Information provided here', 'Content on this site describes our services in general terms. It is not technical, legal or compliance advice, and it does not create a client relationship. Descriptions of CMMC and other regulatory work describe readiness support — they are not a guarantee of certification or of a compliance outcome.' )
		. $block( 'Accuracy', 'We work to keep this site accurate and current, but we do not warrant that every page is free of error or omission. Service details, availability and scope may change.' )
		. $block( 'Intellectual property', 'The text, images, logos and design on this site belong to TRG Networking, Inc. or are used with permission. Please do not reproduce them without our written agreement.' )
		. $block( 'External links', 'This site links to systems and resources we do not control. We are not responsible for the content or availability of external sites.' )
		. $block( 'Questions', 'Contact us using the details in the footer of this page.' );

	$accessibility = '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">What we have done</h2><!-- /wp:heading -->' . "\n"
		. '<!-- wp:list --><ul class="wp-block-list">' . "\n"
		. '<!-- wp:list-item --><li>Every page can be reached and operated with a keyboard alone, and focus is always visible.</li><!-- /wp:list-item -->' . "\n"
		. '<!-- wp:list-item --><li>A “Skip to content” link is the first thing a keyboard or screen reader user encounters.</li><!-- /wp:list-item -->' . "\n"
		. '<!-- wp:list-item --><li>Headings follow a logical order, and each page has a single main heading.</li><!-- /wp:list-item -->' . "\n"
		. '<!-- wp:list-item --><li>Images that carry meaning have text alternatives; decorative graphics are hidden from screen readers.</li><!-- /wp:list-item -->' . "\n"
		. '<!-- wp:list-item --><li>Body text meets the WCAG 2.1 AA contrast ratio against its background.</li><!-- /wp:list-item -->' . "\n"
		. '<!-- wp:list-item --><li>The layout reflows without horizontal scrolling down to a 320px-wide screen.</li><!-- /wp:list-item -->' . "\n"
		. '<!-- wp:list-item --><li>Animation is disabled automatically for visitors whose system requests reduced motion.</li><!-- /wp:list-item -->' . "\n"
		. '<!-- wp:list-item --><li>The enquiry form uses real labels, and errors are announced rather than shown by colour alone.</li><!-- /wp:list-item -->' . "\n"
		. '</ul><!-- /wp:list -->' . "\n"
		. $block( 'Where we are', 'We aim to meet WCAG 2.1 Level AA. Accessibility is ongoing rather than a one-time project, and we review new pages as they are added.' )
		. $block( 'Tell us about a barrier', 'If any part of this site is difficult to use, please tell us — it is the fastest way for us to fix it. Contact us using the details in the footer and we will respond and offer the information you needed in another format.' );

	return array(
		array(
			'slug'    => 'privacy',
			'title'   => 'Privacy Policy',
			'excerpt' => 'How TRG Networking handles the information you share through this website.',
			'content' => $privacy,
		),
		array(
			'slug'    => 'terms',
			'title'   => 'Terms of Use',
			'excerpt' => 'The terms that apply to your use of this website.',
			'content' => $terms,
		),
		array(
			'slug'    => 'accessibility',
			'title'   => 'Accessibility',
			'excerpt' => 'We want this site to be usable by everyone, including people using screen readers, keyboard navigation or magnification.',
			'content' => $accessibility,
		),
	);
}

/**
 * Run the whole setup.
 *
 * @return array<int,string> Log lines.
 */
function trg_run_setup() {
	$log = array();

	/* ------------------------------------------------------------- pages */

	$ids = array();

	// Two passes: parents first, so a child page can be attached to one.
	foreach ( array( false, true ) as $children_pass ) {
		foreach ( trg_page_definitions() as $page ) {
			$has_parent = ! empty( $page['parent'] );
			if ( $has_parent !== $children_pass ) {
				continue;
			}

			$existing = get_page_by_path( $has_parent ? $page['parent'] . '/' . $page['slug'] : $page['slug'] );
			if ( $existing ) {
				$ids[ $page['slug'] ] = $existing->ID;
				/* translators: %s: page title. */
				$log[] = sprintf( __( 'Kept the existing “%s” page — nothing was overwritten.', 'trg-site' ), $page['title'] );
				continue;
			}

			$id = wp_insert_post( array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $page['slug'],
				'post_excerpt' => isset( $page['excerpt'] ) ? $page['excerpt'] : '',
				'post_content' => $page['content'],
				'post_parent'  => $has_parent && isset( $ids[ $page['parent'] ] ) ? $ids[ $page['parent'] ] : 0,
			), true );

			if ( is_wp_error( $id ) ) {
				/* translators: 1: page title, 2: error message. */
				$log[] = sprintf( __( 'Could not create “%1$s": %2$s', 'trg-site' ), $page['title'], $id->get_error_message() );
				continue;
			}

			$ids[ $page['slug'] ] = $id;
			/* translators: %s: page title. */
			$log[] = sprintf( __( 'Created the “%s” page.', 'trg-site' ), $page['title'] );
		}
	}

	/* ------------------------------------------- WordPress's own samples */

	// A live business site should not carry "Sample Page". It is only moved to
	// the trash, and only when its content is still WordPress's untouched
	// default — if anyone has edited it, it is left alone.
	$sample = get_page_by_path( 'sample-page' );
	if ( $sample && 'trash' !== $sample->post_status && false !== strpos( $sample->post_content, 'This is an example page' ) ) {
		wp_trash_post( $sample->ID );
		$log[] = __( 'Moved WordPress’s “Sample Page” to the trash.', 'trg-site' );
	}

	/* ------------------------------------------------- reading settings */

	if ( isset( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
		$log[] = __( 'Set the homepage.', 'trg-site' );
	}

	// Pretty permalinks. Without this every page URL is ?page_id=12, which
	// would throw away the point of matching the old site's URLs.
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		flush_rewrite_rules();
		$log[] = __( 'Switched permalinks to /page-name/.', 'trg-site' );
	}

	/* ------------------------------------------------------------- cards */

	$log = array_merge( $log, trg_seed_cards( $ids ) );

	/* ------------------------------------------------------------- menus */

	$log = array_merge( $log, trg_build_menus( $ids ) );

	update_option( 'trg_setup_done', gmdate( 'c' ) );

	return $log;
}

/**
 * Create the service, industry and testimonial cards.
 *
 * @param array<string,int> $ids Page IDs by slug.
 * @return array<int,string>
 */
function trg_seed_cards( $ids ) {
	$log = array();

	$service_order  = array( 'managed-it-services', 'cybersecurity', 'microsoft-365-cloud', 'secure-ai-adoption', 'backup-business-continuity', 'cmmc-readiness', 'help-desk-it-support', 'azure' );
	$industry_order = array( 'construction', 'manufacturing', 'government-contractors', 'professional-services' );
	$data           = trg_detail_page_data();

	$make = static function ( $post_type, $title, $body, $meta, $order ) use ( &$log ) {
		$found = get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'title'          => $title,
			'numberposts'    => 1,
			'fields'         => 'ids',
		) );
		if ( $found ) {
			return;
		}
		$id = wp_insert_post( array(
			'post_type'    => $post_type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $body,
			'menu_order'   => $order,
			'meta_input'   => $meta,
		) );
		if ( $id ) {
			/* translators: %s: card title. */
			$log[] = sprintf( __( 'Added the “%s” card.', 'trg-site' ), $title );
		}
	};

	$order = 0;
	foreach ( $service_order as $slug ) {
		if ( ! isset( $data[ $slug ] ) ) {
			continue;
		}
		$make(
			'trg_service',
			$data[ $slug ]['title'],
			$data[ $slug ]['card'][1],
			array(
				'_trg_icon' => $data[ $slug ]['card'][0],
				'_trg_link' => isset( $ids[ $slug ] ) ? (string) $ids[ $slug ] : '',
			),
			++$order
		);
	}

	$order = 0;
	foreach ( $industry_order as $slug ) {
		if ( ! isset( $data[ $slug ] ) ) {
			continue;
		}
		$make(
			'trg_industry',
			$data[ $slug ]['title'],
			$data[ $slug ]['card'][1],
			array(
				'_trg_tags' => $data[ $slug ]['card'][0],
				'_trg_link' => isset( $ids[ $slug ] ) ? (string) $ids[ $slug ] : '',
			),
			++$order
		);
	}

	// Healthcare and nonprofits are sections on the Industries page rather than
	// pages of their own — there is no copy for a full page and inventing it
	// would be worse than linking to the section that actually exists.
	$make( 'trg_industry', 'Healthcare', 'Protect patient information and keep clinical systems dependable for the people who rely on them.', array( '_trg_tags' => 'HIPAA compliance, EHR, clinical IT', '_trg_link' => isset( $ids['industries'] ) ? (string) $ids['industries'] : '' ), 5 );
	$make( 'trg_industry', 'Nonprofits', 'Affordable, mission-aligned technology that stretches limited budgets without cutting corners on security.', array( '_trg_tags' => 'Affordable mission-aligned IT', '_trg_link' => isset( $ids['industries'] ) ? (string) $ids['industries'] : '' ), 6 );

	$make( 'trg_testimonial', 'Nick Pirovolidis', 'TRG gives that personal touch, good communication and a skilled staff that fully understands our needs.', array( '_trg_org' => 'BSC America' ), 1 );
	$make( 'trg_testimonial', 'Todd Hirsch', 'TRG personnel provide prompt and thorough support as well as cost-effective management of our IT needs.', array( '_trg_org' => 'Belt Built Contracting, LLC' ), 2 );

	return $log;
}

/**
 * Build the header, footer and legal menus.
 *
 * @param array<string,int> $ids Page IDs by slug.
 * @return array<int,string>
 */
function trg_build_menus( $ids ) {
	$log       = array();
	$locations = get_nav_menu_locations();

	$services   = array( 'managed-it-services', 'help-desk-it-support', 'cybersecurity', 'microsoft-365-cloud', 'azure', 'secure-ai-adoption', 'cmmc-readiness', 'backup-business-continuity' );
	$industries = array( 'construction', 'manufacturing', 'government-contractors', 'professional-services' );

	$page_item = static function ( $menu_id, $slug, $parent = 0, $order = 0, $label = '' ) use ( $ids ) {
		if ( ! isset( $ids[ $slug ] ) ) {
			return 0;
		}
		return (int) wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $label ? $label : get_the_title( $ids[ $slug ] ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $ids[ $slug ],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
			'menu-item-position'  => $order,
		) );
	};

	$link_item = static function ( $menu_id, $title, $url, $parent = 0, $order = 0 ) {
		return (int) wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $title,
			'menu-item-url'       => $url,
			'menu-item-type'      => 'custom',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
			'menu-item-position'  => $order,
		) );
	};

	$ensure_menu = static function ( $name ) {
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			return $menu; // Already built: leave whatever the client has arranged.
		}
		$id = wp_create_nav_menu( $name );
		return is_wp_error( $id ) ? null : wp_get_nav_menu_object( $id );
	};

	/* ------------------------------------------------------------ header */

	$primary = $ensure_menu( 'Main menu' );
	if ( $primary && ! wp_get_nav_menu_items( $primary->term_id ) ) {
		$pos = 0;

		$services_id = $page_item( $primary->term_id, 'services', 0, ++$pos );
		foreach ( $services as $slug ) {
			$page_item( $primary->term_id, $slug, $services_id, ++$pos );
		}

		$industries_id = $page_item( $primary->term_id, 'industries', 0, ++$pos );
		foreach ( $industries as $slug ) {
			$page_item( $primary->term_id, $slug, $industries_id, ++$pos );
		}
		// The two industries that are sections rather than pages.
		$link_item( $primary->term_id, 'Healthcare', trg_site_page_url( 'industries' ) . '#healthcare', $industries_id, ++$pos );
		$link_item( $primary->term_id, 'Nonprofits', trg_site_page_url( 'industries' ) . '#nonprofits', $industries_id, ++$pos );

		$page_item( $primary->term_id, 'why-trg', 0, ++$pos );
		$page_item( $primary->term_id, 'resources', 0, ++$pos );
		$page_item( $primary->term_id, 'about', 0, ++$pos );

		$locations['primary'] = $primary->term_id;
		$log[]                = __( 'Built the main menu.', 'trg-site' );
	}

	/* ------------------------------------------------------------ footer */

	$footer_services = $ensure_menu( 'Footer — Services' );
	if ( $footer_services && ! wp_get_nav_menu_items( $footer_services->term_id ) ) {
		$pos = 0;
		foreach ( $services as $slug ) {
			$page_item( $footer_services->term_id, $slug, 0, ++$pos );
		}
		$locations['footer_services'] = $footer_services->term_id;
	}

	$footer_industries = $ensure_menu( 'Footer — Industries' );
	if ( $footer_industries && ! wp_get_nav_menu_items( $footer_industries->term_id ) ) {
		$pos = 0;
		foreach ( $industries as $slug ) {
			$page_item( $footer_industries->term_id, $slug, 0, ++$pos );
		}
		$link_item( $footer_industries->term_id, 'Healthcare', trg_site_page_url( 'industries' ) . '#healthcare', 0, ++$pos );
		$link_item( $footer_industries->term_id, 'Nonprofits', trg_site_page_url( 'industries' ) . '#nonprofits', 0, ++$pos );
		$locations['footer_industries'] = $footer_industries->term_id;
	}

	$footer_company = $ensure_menu( 'Footer — Company' );
	if ( $footer_company && ! wp_get_nav_menu_items( $footer_company->term_id ) ) {
		$pos = 0;
		foreach ( array( 'about', 'why-trg', 'services', 'industries', 'contact', 'resources', 'case-studies', 'guides' ) as $slug ) {
			$page_item( $footer_company->term_id, $slug, 0, ++$pos );
		}
		$locations['footer_company'] = $footer_company->term_id;
	}

	$legal = $ensure_menu( 'Footer — Legal' );
	if ( $legal && ! wp_get_nav_menu_items( $legal->term_id ) ) {
		$pos = 0;
		foreach ( array( 'privacy', 'terms', 'accessibility' ) as $slug ) {
			$page_item( $legal->term_id, $slug, 0, ++$pos );
		}
		$locations['legal'] = $legal->term_id;
		$log[]              = __( 'Built the footer menus.', 'trg-site' );
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	return $log;
}

/**
 * The "TRG Setup" screen.
 */
function trg_setup_menu() {
	add_management_page(
		__( 'TRG Setup', 'trg-site' ),
		__( 'TRG Setup', 'trg-site' ),
		'manage_options',
		'trg-setup',
		'trg_setup_page'
	);
}
add_action( 'admin_menu', 'trg_setup_menu' );

/**
 * Render (and, on submit, run) the setup screen.
 */
function trg_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$log = array();
	if ( isset( $_POST['trg_setup_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['trg_setup_nonce'] ) ), 'trg_run_setup' ) ) {
		$log = trg_run_setup();
	}

	$done = get_option( 'trg_setup_done' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'TRG Setup', 'trg-site' ); ?></h1>

		<p style="max-width:44em">
			<?php esc_html_e( 'This builds the site: twenty-five pages, the service, industry and testimonial cards, and the header and footer menus. It is safe to run more than once — anything that already exists is left exactly as it is, so a page you have edited will never be overwritten.', 'trg-site' ); ?>
		</p>

		<?php if ( $done ) : ?>
			<p><em><?php
				/* translators: %s: date and time. */
				printf( esc_html__( 'Last run: %s (UTC).', 'trg-site' ), esc_html( $done ) );
			?></em></p>
		<?php endif; ?>

		<?php if ( $log ) : ?>
			<div class="notice notice-success"><p><strong><?php esc_html_e( 'Setup finished.', 'trg-site' ); ?></strong></p></div>
			<ul style="list-style:disc;margin-left:2em">
				<?php foreach ( $log as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'trg_run_setup', 'trg_setup_nonce' ); ?>
			<p><button type="submit" class="button button-primary"><?php esc_html_e( 'Build the site', 'trg-site' ); ?></button></p>
		</form>
	</div>
	<?php
}
