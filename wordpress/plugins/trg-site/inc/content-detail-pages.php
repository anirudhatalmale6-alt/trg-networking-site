<?php
/**
 * The twelve service and industry pages.
 *
 * All of them share one shape — hero, intro with an image, capability grid,
 * perspective band, optional questions, related pages, closing call to action —
 * which is what makes a section carried over from the Lovable build look
 * purpose-built rather than bolted on.
 *
 * Copy is the Lovable copy. The Azure entry comes from the Hostinger build,
 * which was the only one of the two that had that page.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Page data.
 *
 * @return array<string,array<string,mixed>>
 */
function trg_detail_page_data() {
	return array(

	/* ------------------------------------------------------- services */

	'managed-it-services' => array(
		'kind'       => 'service',
		'title'      => 'Managed IT Services',
		'nav_title'  => 'Managed IT',
		'eyebrow'    => 'Managed IT services',
		'hero'       => 'Reliable technology without the daily worry.',
		'lede'       => 'TRG manages, maintains and improves your technology so your people can stay focused on the work they were hired to do.',
		'meta'       => 'Proactive monitoring, responsive support and clear technology planning from TRG Networking—managed IT for organizations nationwide.',
		'seo_title'  => 'Managed IT Services in Maryland | TRG Networking',
		'introTitle' => 'More than fixing problems after they happen.',
		'introBody'  => 'Managed IT should reduce interruption, strengthen security and give leadership a clearer picture of technology costs and priorities. TRG combines proactive care with responsive human support and experienced guidance.',
		'features'   => array(
			array( 'Proactive monitoring', 'We watch for emerging issues and address them before they become larger disruptions.' ),
			array( 'Responsive support', 'Multiple TRG team members oversee incoming requests to help ensure attention and follow-through.' ),
			array( 'Patch and device management', 'Systems stay current, supported and managed according to an agreed technology standard.' ),
			array( 'Vendor coordination', 'We help coordinate internet, software and technology vendors so you are not stuck in the middle.' ),
			array( 'Predictable planning', 'Clear recommendations and technology roadmaps make budgeting and decision-making easier.' ),
			array( 'Plain-English communication', 'Your team receives understandable answers without jargon or condescension.' ),
		),
		'perspective' => array(
			'A technology partner—not another repair company.',
			'TRG looks beyond the immediate ticket to help improve the reliability, security and long-term value of your entire environment.',
		),
		'faq'        => array(
			array( 'Can TRG support organizations outside Maryland?', 'Yes. TRG is headquartered in Columbia, Maryland and provides remote managed services and technology support to organizations nationwide.' ),
			array( 'Do you replace an internal IT person?', 'TRG can serve as a complete outsourced IT team or work alongside internal technology staff, depending on your needs.' ),
			array( 'Is managed IT billed monthly?', 'Managed services are generally structured around a predictable monthly agreement. The exact scope depends on your users, locations, systems and support requirements.' ),
		),
		'card'       => array( 'IT', 'Proactive care, responsive support and a clear technology plan—without the cost of building an internal IT department.' ),
	),

	'cybersecurity' => array(
		'kind'       => 'service',
		'title'      => 'Cybersecurity',
		'eyebrow'    => 'Cybersecurity',
		'hero'       => 'Protection built for the way your business actually works.',
		'lede'       => 'Layered cybersecurity that protects people, devices, identities, email, cloud systems and critical business data.',
		'meta'       => 'Layered cybersecurity for people, devices, identities, email and data—practical protection and guidance from TRG Networking.',
		'seo_title'  => 'Business Cybersecurity Services | TRG Networking',
		'introTitle' => 'Security is not one product or one checkbox.',
		'introBody'  => 'Effective cybersecurity connects technology, policies, training, monitoring and recovery. TRG helps organizations build practical protection around their real risks without turning every recommendation into fear or jargon.',
		'features'   => array(
			array( 'Identity and access', 'Multi-factor authentication, access controls and account practices that reduce unauthorized access.' ),
			array( 'Endpoint protection', 'Security controls and monitoring for the computers and devices your employees depend on.' ),
			array( 'Email security', 'Protection and education designed to reduce phishing, impersonation and malicious attachments.' ),
			array( 'Security awareness', 'Practical employee training that helps people recognize risks and respond appropriately.' ),
			array( 'Vulnerability reduction', 'Patching, configuration review and ongoing improvements that reduce preventable exposure.' ),
			array( 'Backup and recovery', 'Recovery planning that assumes prevention may not stop every incident.' ),
		),
		'perspective' => array(
			'Strong security should support the business—not paralyze it.',
			'TRG balances protection with usability, operational needs and the realities of how your employees work.',
		),
		'faq'        => array(
			array( 'Is antivirus enough for a small business?', 'No single security product is enough. Businesses need layered controls covering identity, email, endpoints, cloud systems, employees, backups and response planning.' ),
			array( 'Can TRG review our current security?', 'Yes. A review can help identify gaps, outdated controls and priorities for improving your overall security posture.' ),
			array( 'Do you provide employee security training?', 'Security awareness and responsible technology use can be included as part of a broader cybersecurity program.' ),
		),
		'card'       => array( 'CY', 'Layered protection for your people, devices, identities and data, backed by practical guidance your team can follow.' ),
	),

	'microsoft-365-cloud' => array(
		'kind'       => 'service',
		'title'      => 'Microsoft 365 & Cloud',
		'eyebrow'    => 'Microsoft 365 & cloud',
		'hero'       => 'Get more value—and stronger security—from Microsoft 365.',
		'lede'       => 'Licensing, configuration, security, collaboration and support designed around how your organization works.',
		'meta'       => 'Licensing, security, migrations and everyday Microsoft 365 support that help your organization get more from the Microsoft cloud.',
		'seo_title'  => 'Microsoft 365 & Cloud Services | TRG Networking',
		'introTitle' => 'Microsoft 365 is powerful. The default setup is rarely the finished setup.',
		'introBody'  => 'TRG helps organizations choose the right licenses, configure security, improve collaboration and support employees across Outlook, Teams, SharePoint, OneDrive and the wider Microsoft environment.',
		'features'   => array(
			array( 'Licensing guidance', 'Choose the right Microsoft plans and features without paying for tools your organization does not need.' ),
			array( 'Security configuration', 'Strengthen identities, access, email and data protection across the Microsoft environment.' ),
			array( 'Migrations', 'Plan and carry out moves to Microsoft 365 with attention to users, data, timing and disruption.' ),
			array( 'Teams and SharePoint', 'Improve collaboration, file access and information organization across departments and locations.' ),
			array( 'Copilot readiness', 'Prepare permissions, data and employee guidance before introducing Microsoft Copilot.' ),
			array( 'Everyday support', 'Give employees a knowledgeable resource when Microsoft tools are not behaving as expected.' ),
		),
		'perspective' => array(
			'Better Microsoft decisions start with licensing and security together.',
			'TRG considers cost, functionality, data access and protection instead of treating licenses as simple line items.',
		),
		'card'       => array( '365', 'Licensing, security, migrations and everyday support that help your organization get more from Microsoft 365.' ),
	),

	'azure-cloud-hosting' => array(
		'kind'       => 'service',
		'title'      => 'Azure Cloud Hosting',
		'eyebrow'    => 'Azure cloud hosting',
		'hero'       => 'Host your servers and network in Azure—without replacing hardware.',
		'lede'       => 'TRG migrates and manages your servers, applications and network infrastructure in Microsoft Azure, eliminating costly hardware refresh cycles and the security gaps that aging on-premise equipment creates.',
		'meta'       => 'Host your servers and network in Microsoft Azure with TRG—eliminate hardware replacement costs and the vulnerabilities of aging on-premise infrastructure.',
		'seo_title'  => 'Azure Cloud Hosting | Microsoft Azure Managed Servers | TRG Networking',
		'introTitle' => 'Stop buying servers. Start gaining flexibility.',
		'introBody'  => 'On-premise servers age, fail and fall out of support—each replacement brings capital expense, downtime risk and fresh vulnerabilities. Azure cloud hosting lets your environment run on secure, always-current Microsoft infrastructure instead, with TRG designing, migrating and managing every layer.',
		'features'   => array(
			array( 'Azure migration', 'We plan and move your servers, applications and network configuration to Azure with minimal disruption.' ),
			array( 'No hardware refresh cycles', 'Retire aging on-premise servers and the recurring capital expense of replacing them every few years.' ),
			array( 'Always-current security', 'Run on continuously patched, Microsoft-managed infrastructure instead of unsupported hardware that builds vulnerabilities.' ),
			array( 'Scalable resources', 'Scale compute, storage and network capacity up or down as the business changes—without overbuying.' ),
			array( 'Built-in resilience', 'Benefit from Azure redundancy, backups and disaster recovery without building a second data center.' ),
			array( 'Managed monitoring', 'TRG monitors, patches and supports your Azure environment alongside the rest of your technology.' ),
		),
		'perspective' => array(
			'The cloud should reduce risk and expense—not add complexity.',
			'TRG turns Azure into a practical, secure foundation for your servers and network—managed as part of one connected technology environment rather than a separate, confusing system.',
		),
		'faq'        => array(
			array( 'Do we have to move everything to Azure at once?', 'No. TRG can migrate servers and applications in phases, keeping critical systems running while we move workloads in a planned sequence.' ),
			array( 'What happens to our existing servers?', 'You can retire aging hardware rather than replacing it. TRG helps prioritize which workloads move first and decommissions on-premise servers as they are replaced by Azure.' ),
			array( 'Is Azure more secure than on-premise servers?', 'Microsoft keeps Azure infrastructure continuously updated and patched. Combined with proper configuration, monitoring and access controls managed by TRG, it eliminates the vulnerability gaps that aging, unsupported hardware creates.' ),
			array( 'Does TRG manage Azure for organizations outside Maryland?', 'Yes. TRG is headquartered in Columbia, Maryland and manages Azure environments for organizations nationwide.' ),
		),
		'card'       => array( 'AZ', 'Host your servers and network in Microsoft Azure—eliminating the cost of replacing aging hardware and the vulnerabilities it creates.' ),
	),

	'secure-ai-adoption' => array(
		'kind'       => 'service',
		'title'      => 'Secure AI Adoption',
		'eyebrow'    => 'Secure AI adoption',
		'hero'       => 'Put AI to work—without putting company information at risk.',
		'lede'       => 'Practical guidance, policies and training for organizations ready to use AI responsibly and productively.',
		'meta'       => 'Policies, training and Copilot readiness that help your team save time with AI while protecting company information.',
		'seo_title'  => 'Secure AI Adoption Services | TRG Networking',
		'introTitle' => 'AI adoption needs more than enthusiasm.',
		'introBody'  => 'Employees may already be experimenting with AI. TRG helps leadership understand what is being used, protect sensitive information, create clear rules and identify workflows where AI can provide genuine value.',
		'features'   => array(
			array( 'AI readiness review', 'Assess current tools, employee use, Microsoft 365 permissions, data concerns and organizational goals.' ),
			array( 'Responsible-use policies', 'Create practical guidance about approved tools, sensitive data, review requirements and accountability.' ),
			array( 'Microsoft Copilot preparation', 'Review licensing, permissions, SharePoint access and data hygiene before broader adoption.' ),
			array( 'Workflow discovery', 'Identify repetitive, time-consuming work where AI assistance may deliver measurable benefit.' ),
			array( 'Employee training', 'Teach people how to use AI effectively while recognizing accuracy, privacy and security limitations.' ),
			array( 'Ongoing governance', 'Revisit tools and policies as AI products, risks and business needs continue to change.' ),
		),
		'perspective' => array(
			'Use AI with a plan—not a free-for-all.',
			'The goal is not to adopt every new tool. It is to find responsible uses that improve work while protecting the business.',
		),
		'faq'        => array(
			array( 'What if employees are already using free AI tools?', 'That is common. The first step is understanding current use, identifying data risks and providing clear guidance about approved tools and information.' ),
			array( 'Can you help us prepare for Microsoft Copilot?', 'Yes. Copilot readiness includes licensing, data access, SharePoint permissions, security settings, policy and employee preparation.' ),
			array( 'Does TRG build custom AI software?', 'TRG focuses on secure adoption, Microsoft Copilot readiness, policy, training and practical business use. Custom development would be evaluated separately based on scope.' ),
		),
		'card'       => array( 'AI', 'Policies, training and practical use cases that help your team save time with AI while protecting company information.' ),
	),

	'backup-business-continuity' => array(
		'kind'       => 'service',
		'title'      => 'Business Continuity',
		'eyebrow'    => 'Backup & business continuity',
		'hero'       => 'Recovery should be a plan—not a hope.',
		'lede'       => 'Protect critical systems and prepare your organization to continue operating when technology is disrupted.',
		'meta'       => 'Verified backups and recovery planning designed to keep a disruption from becoming a business-ending event.',
		'seo_title'  => 'Backup & Business Continuity | TRG Networking',
		'introTitle' => 'A successful backup is only the beginning.',
		'introBody'  => 'Business continuity considers what must be restored, how quickly it is needed, who makes decisions and how employees continue working. TRG helps connect reliable backup technology with a practical recovery plan.',
		'features'   => array(
			array( 'Protected data', 'Back up the servers, systems and cloud data that your organization cannot afford to lose.' ),
			array( 'Verification', 'Monitor backup jobs and address failures rather than assuming everything completed successfully.' ),
			array( 'Recovery priorities', 'Identify the systems and information that must return first to support essential operations.' ),
			array( 'Continuity planning', 'Document responsibilities, communication and temporary processes for operating through disruption.' ),
		),
		'perspective' => array(
			'The real question is not whether data was backed up.',
			'It is whether your business can recover the right systems and data within a timeframe that protects operations and customers.',
		),
		'card'       => array( 'BC', 'Verified backups and recovery planning designed to keep a disruption from becoming a business-ending event.' ),
	),

	'cmmc-readiness' => array(
		'kind'       => 'service',
		'title'      => 'CMMC Readiness',
		'eyebrow'    => 'CMMC readiness',
		'hero'       => 'Build a stronger foundation for protecting CUI.',
		'lede'       => 'Technology and security guidance for government contractors preparing for CMMC requirements and assessment.',
		'meta'       => 'Technology and security guidance for government contractors working toward a stronger, audit-ready environment.',
		'seo_title'  => 'CMMC Readiness Support | TRG Networking',
		'introTitle' => 'CMMC is an organizational program—not an IT product.',
		'introBody'  => 'TRG helps address the technology controls, Microsoft environment, security tools and operational practices that support CMMC readiness. Documentation and formal assessment remain coordinated with the appropriate compliance partners and assessors.',
		'features'   => array(
			array( 'Environment scoping', 'Identify users, devices, locations, applications and third parties that may handle controlled information.' ),
			array( 'Microsoft government cloud', 'Plan licensing and technical architecture when GCC or GCC High requirements apply.' ),
			array( 'Identity and device controls', 'Strengthen authentication, access, endpoints and administrative practices around sensitive systems.' ),
			array( 'Monitoring and protection', 'Align security tooling, logging, endpoint protection, email defense and backup with the target environment.' ),
			array( 'Gap remediation', 'Translate identified technical gaps into prioritized projects and managed operational controls.' ),
			array( 'Partner coordination', 'Work alongside documentation specialists, consultants and assessors without blurring responsibilities.' ),
		),
		'perspective' => array(
			'Readiness without false promises.',
			'TRG supports the technical foundation for CMMC. We do not describe a business as compliant or guarantee certification before the appropriate assessment is completed.',
		),
		'card'       => array( 'C3', 'Technology and security guidance for government contractors working toward a stronger, audit-ready environment.' ),
	),

	'help-desk-it-support' => array(
		'kind'       => 'service',
		'title'      => 'Help Desk & IT Support',
		'eyebrow'    => 'Help desk & IT support',
		'hero'       => 'When your team needs help, they should feel helped.',
		'lede'       => 'Friendly, responsive support that treats people with respect and keeps technology issues moving toward resolution.',
		'meta'       => 'Responsive, human help desk support with shared oversight so every request is seen, assigned and kept moving.',
		'seo_title'  => 'Help Desk & IT Support | TRG Networking',
		'introTitle' => 'Human support with shared oversight.',
		'introBody'  => 'Support should not disappear into a queue. Multiple people across TRG oversee incoming requests, help identify urgency, assign the right technical resource and keep work moving.',
		'features'   => array(
			array( 'Remote assistance', 'Many everyday issues can be diagnosed and resolved quickly through secure remote support.' ),
			array( 'Escalation when needed', 'More complex problems are routed to experienced technical resources rather than endlessly transferred.' ),
			array( 'User-friendly communication', 'We explain what is happening and what comes next in language employees can understand.' ),
			array( 'Request oversight', 'Incoming support is visible to multiple team members, strengthening accountability and follow-through.' ),
		),
		'perspective' => array(
			'Support is part technical skill and part customer care.',
			'TRG believes both matter. Your employees deserve capable answers delivered with patience, professionalism and respect.',
		),
		'card'       => array( 'HD', 'Friendly, responsive support that treats people with respect and keeps technology issues moving toward resolution.' ),
	),

	/* ----------------------------------------------------- industries */

	'construction' => array(
		'kind'       => 'industry',
		'title'      => 'Construction',
		'eyebrow'    => 'IT for construction & contractors',
		'hero'       => 'Keep projects moving from the office to the job site.',
		'lede'       => 'Responsive support, secure access and dependable technology for construction companies and specialty contractors.',
		'meta'       => 'Keep field teams, offices and projects connected without technology slowing the work.',
		'seo_title'  => 'IT for Construction & Contractors | TRG Networking',
		'introTitle' => 'Your people and systems rarely sit in one place.',
		'introBody'  => 'TRG helps connect offices, project managers, field teams, remote employees and the business applications that coordinate bids, schedules, documents and financial information.',
		'features'   => array(
			array( 'Field and office support', 'Support employees wherever work happens, from headquarters to project sites and home offices.' ),
			array( 'Secure file access', 'Improve collaboration on plans, contracts, photos and project information without losing control of access.' ),
			array( 'Business application support', 'Coordinate with software vendors and help maintain the technology surrounding critical construction systems.' ),
			array( 'Cybersecurity', 'Protect email, payments, project data and employee identities from fraud, phishing and disruption.' ),
		),
		'perspective' => array(
			'Technology should support the schedule—not become another delay.',
			'TRG combines remote responsiveness with Maryland-based on-site capability for organizations throughout the region.',
		),
		'card'       => array( 'Field teams, project sites, secure file access', 'Keep field teams, offices and projects connected without technology slowing the work.' ),
	),

	'manufacturing' => array(
		'kind'       => 'industry',
		'title'      => 'Manufacturing',
		'eyebrow'    => 'IT for manufacturing',
		'hero'       => 'Protect production. Reduce disruption. Plan for growth.',
		'lede'       => 'Technology management and cybersecurity for manufacturers that depend on reliable systems and coordinated vendors.',
		'meta'       => 'Protect production, reduce disruption and build a technology foundation that supports growth.',
		'seo_title'  => 'IT for Manufacturing | TRG Networking',
		'introTitle' => 'Production and office technology are increasingly connected.',
		'introBody'  => 'That creates opportunity—and risk. TRG helps manufacturers improve reliability, secure business systems, coordinate technology vendors and prepare for operational disruption.',
		'features'   => array(
			array( 'Operational reliability', 'Proactive maintenance and monitoring help reduce preventable technology disruption.' ),
			array( 'Vendor coordination', 'Work with software, equipment and connectivity vendors to resolve issues without endless finger-pointing.' ),
			array( 'Network segmentation', 'Separate systems and manage access based on operational and security requirements.' ),
			array( 'Recovery planning', 'Prioritize systems and prepare practical restoration plans around production and business needs.' ),
		),
		'perspective' => array(
			'Modernization should improve operations without introducing unnecessary risk.',
			'TRG helps leadership evaluate upgrades, cloud services, security and AI with attention to reliability and business impact.',
		),
		'card'       => array( 'Production systems, vendor coordination, recovery', 'Protect production, reduce disruption and build a technology foundation that supports growth.' ),
	),

	'government-contractors' => array(
		'kind'       => 'industry',
		'title'      => 'Government Contractors',
		'eyebrow'    => 'IT for government contractors',
		'hero'       => 'Secure technology for demanding contract requirements.',
		'lede'       => 'Managed IT, Microsoft government cloud and CMMC readiness support for organizations protecting federal contract information.',
		'meta'       => 'Strengthen security practices and move toward CMMC readiness with experienced guidance.',
		'seo_title'  => 'IT for Government Contractors | TRG Networking',
		'introTitle' => 'Contract requirements change the technology conversation.',
		'introBody'  => 'Government contractors must consider where data lives, who can access it, which systems are in scope and how technical safeguards are operated over time. TRG helps build and manage that technical foundation.',
		'features'   => array(
			array( 'CUI environment planning', 'Identify users, devices, cloud systems, applications and partners connected to controlled information.' ),
			array( 'GCC and GCC High', 'Plan Microsoft licensing and environment decisions around contractual and security requirements.' ),
			array( 'CMMC readiness', 'Address technical gaps and operate security controls in coordination with documentation and assessment partners.' ),
			array( 'Managed security', 'Support identities, endpoints, email, monitoring, backup and the ongoing technology environment.' ),
		),
		'perspective' => array(
			'Technical readiness, clearly scoped.',
			'TRG supports the technology and security environment while coordinating with qualified compliance specialists and independent assessors.',
		),
		'card'       => array( 'CMMC, GCC High, CUI protection', 'Strengthen security practices and move toward CMMC readiness with experienced guidance.' ),
	),

	'professional-services' => array(
		'kind'       => 'industry',
		'title'      => 'Professional Services',
		'eyebrow'    => 'IT for professional services',
		'hero'       => 'Secure, responsive technology for people whose time matters.',
		'lede'       => 'Dependable support and protected collaboration for firms built around expertise, relationships and client trust.',
		'meta'       => 'Give your people secure, reliable tools to serve clients from the office or anywhere else.',
		'seo_title'  => 'IT for Professional Services | TRG Networking',
		'introTitle' => 'When employees lose time to technology, the business loses billable value.',
		'introBody'  => 'TRG helps professional-service organizations reduce interruption, protect confidential information and give employees reliable access to the Microsoft tools and business applications they use every day.',
		'features'   => array(
			array( 'Responsive user support', 'Help employees return to productive work without being passed endlessly between vendors.' ),
			array( 'Microsoft 365', 'Secure email, collaboration, documents and remote work across the Microsoft environment.' ),
			array( 'Client data protection', 'Apply layered security and responsible access practices around sensitive information.' ),
			array( 'Predictable planning', 'Create clearer technology budgets, upgrade priorities and vendor decisions.' ),
		),
		'perspective' => array(
			'Professional support should feel professional.',
			'TRG combines technical experience with patience, communication and respect for your employees and clients.',
		),
		'card'       => array( 'Law firms, CPAs, consultancies', 'Give your people secure, reliable tools to serve clients from the office or anywhere else.' ),
	),

	);
}

/**
 * Make a string safe to sit inside a shortcode attribute.
 *
 * Deliberately not esc_attr(): that turns "&" into "&amp;", which the shortcode
 * parser stores literally and the renderer then escapes again, so the page ends
 * up displaying "&amp;" to visitors. The only characters that actually break a
 * shortcode attribute are the double quote and the closing bracket.
 *
 * @param string $value Raw text.
 * @return string
 */
function trg_attr( $value ) {
	return str_replace( array( '"', '[', ']' ), array( '”', '(', ')' ), (string) $value );
}

/**
 * Build the page content for one detail page.
 *
 * @param array $page Entry from trg_detail_page_data().
 * @return string
 */
function trg_detail_page_content( $page ) {
	$is_service = 'service' === $page['kind'];

	/*
	 * The shape is test2's, band for band: hero, then one section carrying the
	 * lead heading and its numbered capability grid, then the perspective band,
	 * then the questions where a page has them, then the closing call to action.
	 *
	 * No image band and no "related pages" band, because test2's detail pages
	 * have neither. The [trg_media_split] and [trg_related] shortcodes are
	 * deliberately left registered — they are still used elsewhere, and dropping
	 * them would break any page the client builds with them later.
	 */
	/*
	 * Every page gets its own picture slot, named after the page, so the client
	 * can hand over a photo per page. An unset slot simply renders no image and
	 * the hero falls back to test2's single-column shape.
	 */
	$out = sprintf(
		'[trg_hero eyebrow="%s" title="%s" lede="%s" image="%s" image_alt="%s" button_text="Talk With Our Team" button_link="contact" call_button="1"]',
		trg_attr( $page['eyebrow'] ),
		trg_attr( $page['hero'] ),
		trg_attr( $page['lede'] ),
		trg_attr( isset( $page['slug'] ) ? 'pg-' . $page['slug'] : '' ),
		trg_attr( isset( $page['image_alt'] ) ? $page['image_alt'] : $page['title'] )
	) . "\n\n";

	$out .= sprintf(
		'[trg_cards bg="white" columns="%s" title="%s" body="%s"]' . "\n",
		4 === count( $page['features'] ) ? '2' : '3',
		trg_attr( $page['introTitle'] ),
		trg_attr( $page['introBody'] )
	);
	foreach ( $page['features'] as $i => $feature ) {
		$out .= sprintf(
			'[trg_card num="%02d" title="%s"]%s[/trg_card]' . "\n",
			$i + 1,
			trg_attr( $feature[0] ),
			$feature[1]
		);
	}
	$out .= "[/trg_cards]\n\n";

	$out .= sprintf(
		'[trg_perspective title="%s" body="%s"]',
		trg_attr( $page['perspective'][0] ),
		trg_attr( $page['perspective'][1] )
	) . "\n\n";

	if ( ! empty( $page['faq'] ) ) {
		$out .= '[trg_faq eyebrow="Common questions" title="What business leaders ask us."]' . "\n";
		foreach ( $page['faq'] as $item ) {
			$out .= sprintf( '[trg_faq_item q="%s"]%s[/trg_faq_item]' . "\n", trg_attr( $item[0] ), $item[1] );
		}
		$out .= "[/trg_faq]\n\n";
	}

	$out .= '[trg_cta_band title="Ready for technology that feels easier?" body="Start with a practical conversation about your organization, your concerns and where you want to go next."]';

	return $out;
}
