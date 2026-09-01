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
			'eyebrow'    => 'Managed IT Services',
			'hero'       => 'Reliable technology without the daily worry.',
			'lede'       => 'TRG manages, maintains and improves your technology so your people can stay focused on the work they were hired to do.',
			'meta'       => 'Proactive monitoring, responsive support, patch management and technology planning from a Maryland-based managed IT provider serving clients nationwide.',
			'image'      => 'hero-team',
			'image_alt'  => 'A TRG consultant working through a technology plan with a client team',
			'introTitle' => 'More than fixing problems after they happen.',
			'introBody'  => 'Managed IT should reduce interruption, strengthen security and give leadership a clearer picture of technology costs and priorities. TRG combines proactive care with responsive human support.',
			'features'   => array(
				array( 'Proactive monitoring', 'We watch for emerging issues and address them before they become larger disruptions.' ),
				array( 'Responsive support', 'Multiple TRG team members oversee incoming requests to help ensure attention and follow-through.' ),
				array( 'Patch and device management', 'Systems stay current, supported and managed according to an agreed technology standard.' ),
				array( 'Vendor coordination', 'We help coordinate internet, software and technology vendors so you are not stuck in the middle.' ),
				array( 'Predictable planning', 'Clear recommendations and technology roadmaps make budgeting and decision-making easier.' ),
				array( 'Plain-English communication', 'Your team receives understandable answers without jargon or condescension.' ),
			),
			'perspective' => array(
				'A technology partner — not another repair company.',
				'TRG looks beyond the immediate ticket to help improve the reliability, security and long-term value of your entire environment.',
			),
			'faq'        => array(
				array( 'Can TRG support organizations outside Maryland?', 'Yes. TRG is headquartered in Columbia, Maryland and provides remote managed services and technology support to organizations nationwide.' ),
				array( 'Do you replace an internal IT person?', 'TRG can serve as a complete outsourced IT team or work alongside internal technology staff, depending on your needs.' ),
				array( 'Is managed IT billed monthly?', 'Managed services are generally structured around a predictable monthly agreement. The exact scope depends on your users, locations, systems and support requirements.' ),
			),
			'card'       => array( 'server', 'Proactive care, responsive support and a clear technology plan — without the cost of building an internal IT department.' ),
		),

		'help-desk-it-support' => array(
			'kind'       => 'service',
			'title'      => 'Help Desk & IT Support',
			'eyebrow'    => 'Help Desk & IT Support',
			'hero'       => 'When your team needs help, they should feel helped.',
			'lede'       => 'Friendly, responsive support that treats people with respect and keeps technology issues moving toward resolution.',
			'meta'       => 'Responsive help desk support with shared oversight, remote assistance and clear escalation from TRG Networking in Columbia, Maryland.',
			'image'      => 'lov-support',
			'image_alt'  => 'A TRG IT specialist working alongside a client team member',
			'introTitle' => 'Human support with shared oversight.',
			'introBody'  => 'Support should not disappear into a queue. Multiple people across TRG oversee incoming requests, help identify urgency, assign the right technical resource and keep work moving toward resolution.',
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
			'card'       => array( 'headset', 'Friendly, responsive support that treats people with respect and keeps issues moving toward resolution.' ),
		),

		'cybersecurity' => array(
			'kind'       => 'service',
			'title'      => 'Cybersecurity',
			'eyebrow'    => 'Cybersecurity',
			'hero'       => 'Protection built for the way your business actually works.',
			'lede'       => 'Layered cybersecurity that protects people, devices, identities, email, cloud systems and critical business data.',
			'meta'       => 'Layered cybersecurity covering identity, endpoints, email, awareness training, vulnerability reduction and recovery — aligned to NIST, CMMC and Zero Trust.',
			'image'      => 'security',
			'image_alt'  => 'A security operations team reviewing monitoring dashboards',
			'introTitle' => 'Security is not one product or one checkbox.',
			'introBody'  => 'Effective cybersecurity connects technology, policies, training, monitoring and recovery. TRG helps organizations build practical protection around their real risks.',
			'features'   => array(
				array( 'Identity and access', 'Multi-factor authentication, access controls and account practices that reduce unauthorized access.' ),
				array( 'Endpoint protection', 'Security controls and monitoring for the computers and devices your employees depend on.' ),
				array( 'Email security', 'Protection and education designed to reduce phishing, impersonation and malicious attachments.' ),
				array( 'Security awareness', 'Practical employee training that helps people recognize risks and respond appropriately.' ),
				array( 'Vulnerability reduction', 'Patching, configuration review and ongoing improvements that reduce preventable exposure.' ),
				array( 'Backup and recovery', 'Recovery planning that assumes prevention may not stop every incident.' ),
			),
			'perspective' => array(
				'Strong security should support the business — not paralyze it.',
				'TRG balances protection with usability, operational needs and the realities of how your employees work.',
			),
			'faq'        => array(
				array( 'Is antivirus enough for a small business?', 'No single security product is enough. Businesses need layered controls covering identity, email, endpoints, cloud systems, employees, backups and response planning.' ),
				array( 'Can TRG review our current security?', 'Yes. A review can help identify gaps, outdated controls and priorities for improving your overall security posture.' ),
				array( 'Do you provide employee security training?', 'Security awareness and responsible technology use can be included as part of a broader cybersecurity program.' ),
			),
			'card'       => array( 'shield', 'Layered protection for your people, devices, identities and data, backed by practical guidance your team can follow.' ),
		),

		'microsoft-365-cloud' => array(
			'kind'       => 'service',
			'title'      => 'Microsoft 365 & Cloud',
			'eyebrow'    => 'Microsoft 365 & Cloud',
			'hero'       => 'Get more value — and stronger security — from Microsoft 365.',
			'lede'       => 'Licensing, configuration, security, collaboration and support designed around how your organization works.',
			'meta'       => 'Microsoft 365 licensing guidance, security configuration, migrations, Teams and SharePoint, Copilot readiness and everyday support from a certified Microsoft Partner.',
			'image'      => 'lov-support',
			'image_alt'  => 'A business team reviewing a Microsoft cloud environment together',
			'introTitle' => 'Microsoft 365 is powerful. The default setup is rarely the finished setup.',
			'introBody'  => 'TRG helps organizations choose the right licenses, configure security, improve collaboration and support employees across Outlook, Teams, SharePoint, OneDrive and the wider Microsoft environment.',
			'pills'      => 'Azure, Microsoft 365, Teams, SharePoint, Intune, Copilot AI, Defender, Sentinel',
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
			'card'       => array( 'cloud', 'Licensing, security, migrations and everyday support that help your organization get more from Microsoft 365.' ),
		),

		'azure' => array(
			'kind'       => 'service',
			'title'      => 'Azure Cloud',
			'eyebrow'    => 'Azure Cloud',
			'hero'       => 'Azure cloud built for performance and security.',
			'lede'       => 'Architecture, migration and ongoing optimization for organizations running critical workloads on Microsoft Azure.',
			'meta'       => 'Azure architecture, migration, cost optimization, backup and security hardening from a certified Microsoft Partner serving Maryland and the DC metro area.',
			'image'      => 'ai',
			'image_alt'  => 'Executives reviewing Azure cloud architecture on screen',
			'introTitle' => 'The cloud only saves money when it is designed and reviewed.',
			'introBody'  => 'Moving to Azure is a beginning, not a finish line. TRG plans the migration, then keeps reviewing architecture, cost and security so the environment stays healthy as your organization changes.',
			'pills'      => 'Azure Virtual Machines, Azure AD / Entra ID, Azure Backup, Azure Files, Networking, Cost management',
			'features'   => array(
				array( 'Architecture and design', 'Design an Azure environment around your workloads, users, access requirements and budget.' ),
				array( 'Migration planning', 'Move servers, files and applications with attention to sequencing, downtime and rollback.' ),
				array( 'Identity and access', 'Connect Entra ID, conditional access and device policy so cloud access stays controlled.' ),
				array( 'Cost optimization', 'Review sizing, reservations and unused resources so cloud spend stays understandable.' ),
				array( 'Backup and resilience', 'Protect cloud workloads with backup and recovery designed around real recovery priorities.' ),
				array( 'Ongoing management', 'Monitor, patch and improve the environment rather than leaving it untouched after go-live.' ),
			),
			'perspective' => array(
				'A cloud environment is something you operate — not something you finish.',
				'TRG reviews architecture, spend and security on an ongoing basis so Azure keeps serving the business well after the migration.',
			),
			'card'       => array( 'cloud-cog', 'Azure architecture, migration and optimization — designed for performance, cost control and security.' ),
		),

		'secure-ai-adoption' => array(
			'kind'       => 'service',
			'title'      => 'Secure AI Adoption',
			'eyebrow'    => 'Secure AI Adoption',
			'hero'       => 'Put AI to work — without putting company information at risk.',
			'lede'       => 'Practical guidance, policies and training for organizations ready to use AI responsibly and productively.',
			'meta'       => 'AI readiness reviews, responsible-use policies, Microsoft Copilot preparation, workflow discovery and employee training from TRG Networking.',
			'image'      => 'ai',
			'image_alt'  => 'Executives examining AI analytics dashboards',
			'introTitle' => 'AI adoption needs more than enthusiasm.',
			'introBody'  => 'Employees may already be experimenting with AI. TRG helps leadership understand what is being used, protect sensitive information, create clear rules and identify where AI genuinely helps.',
			'features'   => array(
				array( 'AI readiness review', 'Assess current tools, employee use, Microsoft 365 permissions, data concerns and organizational goals.' ),
				array( 'Responsible-use policies', 'Create practical guidance about approved tools, sensitive data, review requirements and accountability.' ),
				array( 'Microsoft Copilot preparation', 'Review licensing, permissions, SharePoint access and data hygiene before broader adoption.' ),
				array( 'Workflow discovery', 'Identify repetitive, time-consuming work where AI assistance may deliver measurable benefit.' ),
				array( 'Employee training', 'Teach people how to use AI effectively while recognizing accuracy, privacy and security limitations.' ),
				array( 'Ongoing governance', 'Revisit tools and policies as AI products, risks and business needs continue to change.' ),
			),
			'perspective' => array(
				'Use AI with a plan — not a free-for-all.',
				'The goal is not to adopt every new tool. It is to find responsible uses that improve work while protecting the business.',
			),
			'faq'        => array(
				array( 'What if employees are already using free AI tools?', 'That is common. The first step is understanding current use, identifying data risks and providing clear guidance about approved tools and information.' ),
				array( 'Can you help us prepare for Microsoft Copilot?', 'Yes. Copilot readiness includes licensing, data access, SharePoint permissions, security settings, policy and employee preparation.' ),
				array( 'Does TRG build custom AI software?', 'TRG focuses on secure adoption, Microsoft Copilot readiness, policy, training and practical business use. Custom development would be evaluated separately based on scope.' ),
			),
			'card'       => array( 'sparkles', 'Policies, training and practical use cases that help your team save time with AI while protecting company information.' ),
		),

		'cmmc-readiness' => array(
			'kind'       => 'service',
			'title'      => 'CMMC Readiness',
			'eyebrow'    => 'CMMC Readiness',
			'hero'       => 'Build a stronger foundation for protecting CUI.',
			'lede'       => 'Technology and security guidance for government contractors preparing for CMMC requirements and assessment.',
			'meta'       => 'CMMC readiness support covering environment scoping, Microsoft GCC and GCC High, identity and device controls, monitoring and gap remediation.',
			'image'      => 'cmmc',
			'image_alt'  => 'Government contractors reviewing compliance documentation',
			'introTitle' => 'CMMC is an organizational program — not an IT product.',
			'introBody'  => 'TRG helps address the technology controls, Microsoft environment, security tools and operational practices that support CMMC readiness. Documentation and formal assessment are handled with the appropriate specialists.',
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
			'card'       => array( 'badge-check', 'Technology and security guidance for government contractors working toward a stronger, audit-ready environment.' ),
		),

		'backup-business-continuity' => array(
			'kind'       => 'service',
			'title'      => 'Backup & Business Continuity',
			'eyebrow'    => 'Backup & Business Continuity',
			'hero'       => 'Recovery should be a plan — not a hope.',
			'lede'       => 'Protect critical systems and prepare your organization to continue operating when technology is disrupted.',
			'meta'       => 'Verified backups, recovery priorities and continuity planning that keep a disruption from becoming a business-ending event.',
			'image'      => 'about-team',
			'image_alt'  => 'A TRG team reviewing recovery priorities with a client',
			'introTitle' => 'A successful backup is only the beginning.',
			'introBody'  => 'Business continuity considers what must be restored, how quickly it is needed, who makes decisions and how employees continue working. TRG helps connect reliable backup to a realistic recovery plan.',
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
			'card'       => array( 'database', 'Verified backups and recovery planning designed to keep a disruption from becoming a business-ending event.' ),
		),

		/* ----------------------------------------------------- industries */

		'construction' => array(
			'kind'       => 'industry',
			'title'      => 'Construction & Contractors',
			'eyebrow'    => 'IT for Construction & Contractors',
			'hero'       => 'Keep projects moving from the office to the job site.',
			'lede'       => 'Responsive support, secure access and dependable technology for construction companies and specialty contractors.',
			'meta'       => 'Field and office IT support, secure file access, business application support and cybersecurity for construction companies and specialty contractors.',
			'image'      => 'lov-hero',
			'image_alt'  => 'A project team coordinating between the office and a job site',
			'introTitle' => 'Your people and systems rarely sit in one place.',
			'introBody'  => 'TRG helps connect offices, project managers, field teams, remote employees and the business applications that coordinate bids, schedules, documents and financial information.',
			'features'   => array(
				array( 'Field and office support', 'Support employees wherever work happens, from headquarters to project sites and home offices.' ),
				array( 'Secure file access', 'Improve collaboration on plans, contracts, photos and project information without losing control of access.' ),
				array( 'Business application support', 'Coordinate with software vendors and help maintain the technology surrounding critical construction systems.' ),
				array( 'Cybersecurity', 'Protect email, payments, project data and employee identities from fraud, phishing and disruption.' ),
			),
			'perspective' => array(
				'Technology should support the schedule — not become another delay.',
				'TRG combines remote responsiveness with Maryland-based on-site capability for organizations throughout the region.',
			),
			'card'       => array( 'Field connectivity, BEC protection', 'Keep field teams, offices and projects connected without technology slowing the work.' ),
		),

		'manufacturing' => array(
			'kind'       => 'industry',
			'title'      => 'Manufacturing',
			'eyebrow'    => 'IT for Manufacturing',
			'hero'       => 'Protect production. Reduce disruption. Plan for growth.',
			'lede'       => 'Technology management and cybersecurity for manufacturers that depend on reliable systems and coordinated vendors.',
			'meta'       => 'Operational reliability, vendor coordination, network segmentation and recovery planning for manufacturers in Maryland and nationwide.',
			'image'      => 'security',
			'image_alt'  => 'Operations staff monitoring connected production systems',
			'introTitle' => 'Production and office technology are increasingly connected.',
			'introBody'  => 'That creates opportunity — and risk. TRG helps manufacturers improve reliability, secure business systems, coordinate technology vendors and prepare for operational disruption.',
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
			'card'       => array( 'OT security, ERP, production uptime', 'Protect production, reduce disruption and build a technology foundation that supports growth.' ),
		),

		'government-contractors' => array(
			'kind'       => 'industry',
			'title'      => 'Government Contractors',
			'eyebrow'    => 'IT for Government Contractors',
			'hero'       => 'Secure technology for demanding contract requirements.',
			'lede'       => 'Managed IT, Microsoft government cloud and CMMC readiness support for organizations protecting federal contract information.',
			'meta'       => 'CUI environment planning, Microsoft GCC and GCC High, CMMC readiness and managed security for government contractors.',
			'image'      => 'cmmc',
			'image_alt'  => 'Government contracting team reviewing security requirements',
			'introTitle' => 'Contract requirements change the technology conversation.',
			'introBody'  => 'Government contractors must consider where data lives, who can access it, which systems are in scope and how technical safeguards are operated over time. TRG helps build that foundation.',
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
			'card'       => array( 'CMMC, NIST 800-171, CUI protection', 'Strengthen security practices and move toward CMMC readiness with experienced guidance.' ),
		),

		'professional-services' => array(
			'kind'       => 'industry',
			'title'      => 'Professional Services',
			'eyebrow'    => 'IT for Professional Services',
			'hero'       => 'Secure, responsive technology for people whose time matters.',
			'lede'       => 'Dependable support and protected collaboration for firms built around expertise, relationships and client trust.',
			'meta'       => 'Responsive user support, Microsoft 365, client data protection and predictable technology planning for law firms, CPAs and consultancies.',
			'image'      => 'about-team',
			'image_alt'  => 'Professional services team collaborating in a modern office',
			'introTitle' => 'When employees lose time to technology, the business loses billable value.',
			'introBody'  => 'TRG helps professional-service organizations reduce interruption, protect confidential information and give employees reliable access to the Microsoft tools and business systems they use every day.',
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

	$out = sprintf(
		'[trg_hero eyebrow="%s" title="%s" lede="%s" button_text="Talk with our team" button_link="contact" call_button="1"]',
		trg_attr( $page['eyebrow'] ),
		trg_attr( $page['hero'] ),
		trg_attr( $page['lede'] )
	) . "\n\n";

	$out .= sprintf(
		'[trg_media_split bg="white" image="%s" image_alt="%s" title="%s" body="%s" pills="%s"]',
		trg_attr( $page['image'] ),
		trg_attr( $page['image_alt'] ),
		trg_attr( $page['introTitle'] ),
		trg_attr( $page['introBody'] ),
		trg_attr( isset( $page['pills'] ) ? $page['pills'] : '' )
	) . "\n\n";

	$out .= sprintf(
		'[trg_cards bg="canvas" columns="%s" eyebrow="%s" title="%s"]' . "\n",
		4 === count( $page['features'] ) ? '2' : '3',
		$is_service ? 'What this includes' : 'How we help',
		$is_service ? 'What working with TRG looks like.' : 'Where TRG makes a difference.'
	);
	foreach ( $page['features'] as $feature ) {
		$out .= sprintf( '[trg_card icon="check" title="%s"]%s[/trg_card]' . "\n", trg_attr( $feature[0] ), $feature[1] );
	}
	$out .= "[/trg_cards]\n\n";

	$out .= sprintf(
		'[trg_perspective title="%s" body="%s"]',
		trg_attr( $page['perspective'][0] ),
		trg_attr( $page['perspective'][1] )
	) . "\n\n";

	if ( ! empty( $page['faq'] ) ) {
		$out .= "[trg_faq]\n";
		foreach ( $page['faq'] as $item ) {
			$out .= sprintf( '[trg_faq_item q="%s"]%s[/trg_faq_item]' . "\n", trg_attr( $item[0] ), $item[1] );
		}
		$out .= "[/trg_faq]\n\n";
	}

	$out .= sprintf( '[trg_related type="%s"]', $is_service ? 'service' : 'industry' ) . "\n\n";
	$out .= '[trg_cta_band body="Start with a practical conversation about your organization, your concerns and where you want to go next."]';

	return $out;
}
