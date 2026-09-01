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
			'hero'       => 'Managed IT That Keeps Your Business Moving',
			'lede'       => 'Proactive technology management, responsive support and security-first IT services that let your team focus on business — not technology.',
			'meta'       => 'Proactive monitoring, responsive support, patch management and technology planning from a Maryland-based managed IT provider serving clients nationwide.',
			'image'      => 'hero-team',
			'image_alt'  => 'A TRG consultant working through a technology plan with a client team',
			'introTitle' => 'Less disruption. More confidence.',
			'introBody'  => 'Managed IT should reduce interruption, strengthen security and give leadership a clearer picture of technology costs and priorities. TRG combines proactive care with responsive human support.',
			'pills'      => '24x7 monitoring, Help desk, Endpoint management, Patch management, Infrastructure management, Network management, Microsoft 365 support, Security monitoring, Backup and business continuity, Vendor coordination, Strategic planning, vCIO services',
			'features'   => array(
				array( 'Proactive monitoring', '24x7 monitoring watches for emerging issues and addresses them before they become larger disruptions.' ),
				array( 'Multiple eyes on every request', 'One team accountable. Several TRG people oversee incoming support so requests are seen, assigned and kept moving.' ),
				array( 'Endpoint and patch management', 'Computers and servers stay current, supported and managed to an agreed technology standard.' ),
				array( 'Infrastructure and network management', 'Servers, networks and the Microsoft 365 environment are maintained as one connected system rather than separate parts.' ),
				array( 'Vendor coordination', 'We coordinate internet, software and technology vendors so you are not stuck in the middle.' ),
				array( 'Strategic planning and vCIO', 'Clear recommendations, technology roadmaps and executive-level guidance that make budgeting and decisions easier.' ),
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
			'hero'       => 'Responsive IT Support From People Who Know Your Business',
			'lede'       => 'Friendly, responsive support that treats people with respect and keeps technology issues moving toward resolution.',
			'meta'       => 'Responsive help desk support with shared oversight, remote assistance and clear escalation from TRG Networking in Columbia, Maryland.',
			'image'      => 'lov-support',
			'image_alt'  => 'A TRG IT specialist working alongside a client team member',
			'introTitle' => 'Human support with shared oversight.',
			'introBody'  => 'Support should not disappear into a queue. Multiple people across TRG oversee incoming requests, help identify urgency, assign the right technical resource and keep work moving toward resolution.',
			'pills'      => 'Responsive support, Remote assistance, Escalation, Issue ownership, Ticket visibility, Microsoft support, Endpoint troubleshooting, Proactive resolution',
			'features'   => array(
				array( 'Remote assistance', 'Many everyday issues can be diagnosed and resolved quickly through secure remote support.' ),
				array( 'Escalation when needed', 'More complex problems are routed to experienced technical resources rather than endlessly transferred.' ),
				array( 'Issue ownership and ticket visibility', 'You can see what was raised, who has it and where it stands, rather than wondering whether anything is happening.' ),
				array( 'User-friendly communication', 'We explain what is happening and what comes next in language employees can understand.' ),
			),
			'perspective' => array(
				'Multiple eyes on every request. One team accountable.',
				'Support is part technical skill and part customer care, and your employees deserve capable answers delivered with patience, professionalism and respect.',
			),
			'card'       => array( 'headset', 'Friendly, responsive support that treats people with respect and keeps issues moving toward resolution.' ),
		),

		'cybersecurity' => array(
			'kind'       => 'service',
			'title'      => 'Cybersecurity',
			'eyebrow'    => 'Cybersecurity',
			'hero'       => 'Cybersecurity That Protects Modern Businesses',
			'lede'       => 'Layered cybersecurity that protects people, devices, identities, email, cloud systems and critical business data — built as part of how the business runs, not bolted on afterwards.',
			'meta'       => 'Layered cybersecurity covering identity, endpoints, email, awareness training, vulnerability reduction and recovery — aligned to NIST, CMMC and Zero Trust.',
			'image'      => 'security',
			'image_alt'  => 'A security operations team reviewing monitoring dashboards',
			'introTitle' => 'Security is not one product or one checkbox.',
			'introBody'  => 'Effective cybersecurity connects technology, policies, training, monitoring and recovery. TRG helps organizations build practical protection around their real risks.',
			'pills'      => 'Zero Trust, Microsoft Defender, Identity protection, MFA, Endpoint security, Email security, Threat detection, SIEM, Vulnerability management, Security awareness, Compliance support, Incident readiness',
			'features'   => array(
				array( 'Identity protection and Zero Trust', 'Multi-factor authentication, conditional access and account practices built on verifying every request rather than trusting the network.' ),
				array( 'Endpoint security', 'Microsoft Defender and monitoring for the computers and devices your employees depend on.' ),
				array( 'Email security', 'Protection and education designed to reduce phishing, impersonation and malicious attachments.' ),
				array( 'Threat detection and SIEM', 'Logging, alerting and review so unusual activity is noticed rather than discovered months later.' ),
				array( 'Vulnerability management', 'Patching, configuration review and ongoing improvements that reduce preventable exposure.' ),
				array( 'Security awareness and incident readiness', 'Practical employee training, plus a plan for who does what if something does get through.' ),
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
			'hero'       => 'Unlock the Full Power of Microsoft',
			'lede'       => 'Productivity, security and management across Microsoft 365 — configured, protected and supported around how your organization actually works.',
			'meta'       => 'Microsoft 365 configuration, security, migrations, Teams and SharePoint, Entra ID, Intune, Copilot readiness and everyday support from a certified Microsoft Partner.',
			'image'      => 'lov-support',
			'image_alt'  => 'A business team reviewing a Microsoft cloud environment together',
			'introTitle' => 'Microsoft 365 is powerful. The default setup is rarely the finished setup.',
			'introBody'  => 'TRG helps organizations configure security, improve collaboration, govern where information lives and support employees across Outlook, Teams, SharePoint, OneDrive and the wider Microsoft environment. Licensing is part of that conversation — it is not the point of it.',
			'pills'      => 'Microsoft 365, Teams, SharePoint, Exchange Online, Intune, Defender, Entra ID, Copilot, Azure integration, Security, Governance, Migration, Adoption',
			'features'   => array(
				array( 'Security and identity', 'Strengthen Entra ID, conditional access, email and data protection across the Microsoft environment.' ),
				array( 'Device management with Intune', 'Enrol, configure and protect the laptops and phones people work on, wherever they are.' ),
				array( 'Migration', 'Plan and carry out moves to Microsoft 365 and Exchange Online with attention to users, data, timing and disruption.' ),
				array( 'Teams and SharePoint', 'Improve collaboration, file access and information organization across departments and locations.' ),
				array( 'Governance and Copilot readiness', 'Get permissions and data hygiene right so information is shared deliberately — the same work Copilot depends on.' ),
				array( 'Adoption and everyday support', 'Help employees actually use the tools, with a knowledgeable resource when something is not behaving.' ),
			),
			'perspective' => array(
				'The value is in how Microsoft 365 is configured, not in which licenses were bought.',
				'TRG considers productivity, data access, governance and protection together instead of treating the Microsoft environment as a set of line items.',
			),
			// The homepage and services grid call this card "Microsoft Solutions",
			// which is broader than the page title. Overriding the card title keeps
			// both wordings from the change request without duplicating the page.
			'card_title' => 'Microsoft Solutions',
			'card'       => array( 'cloud', 'Azure, Microsoft 365, Teams, SharePoint, Intune, Defender and Copilot — configured, secured and supported.' ),
		),

		'azure' => array(
			'kind'       => 'service',
			'title'      => 'Azure Cloud',
			'eyebrow'    => 'Azure Cloud',
			'hero'       => 'Azure Cloud Built for Performance & Security',
			'lede'       => 'Architecture, migration and ongoing optimization for organizations running critical workloads on Microsoft Azure.',
			'meta'       => 'Azure architecture, migration, cost optimization, backup and security hardening from a certified Microsoft Partner serving Maryland and the DC metro area.',
			'image'      => 'ai',
			'image_alt'  => 'Executives reviewing Azure cloud architecture on screen',
			'introTitle' => 'The cloud only saves money when it is designed and reviewed.',
			'introBody'  => 'Moving to Azure is a beginning, not a finish line. TRG plans the migration, then keeps reviewing architecture, cost and security so the environment stays healthy as your organization changes.',
			'pills'      => 'Azure architecture, Azure migration, Azure Virtual Machines, Networking, Identity, Backup, Disaster recovery, Monitoring, Security, Optimization, Hybrid cloud, Cost management',
			'features'   => array(
				array( 'Architecture and design', 'Design an Azure environment around your workloads, users, access requirements and budget.' ),
				array( 'Migration planning', 'Move servers, files and applications with attention to sequencing, downtime and rollback.' ),
				array( 'Networking and identity', 'Connect virtual networks, Entra ID, conditional access and device policy so cloud access stays controlled.' ),
				array( 'Backup and disaster recovery', 'Protect cloud workloads with backup and recovery designed around real recovery priorities.' ),
				array( 'Monitoring and security', 'Watch the environment, harden its configuration and act on what the alerts are actually saying.' ),
				array( 'Optimization and cost management', 'Review sizing, reservations, hybrid options and unused resources so cloud spend stays understandable.' ),
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
			'hero'       => 'Artificial Intelligence That Drives Business Performance',
			'lede'       => 'Use AI with a plan — not a free-for-all. Practical guidance, policies and training for organizations ready to use AI responsibly and productively.',
			'meta'       => 'AI readiness reviews, responsible-use policies, Microsoft Copilot preparation, workflow discovery and employee training from TRG Networking.',
			'image'      => 'ai',
			'image_alt'  => 'Executives examining AI analytics dashboards',
			'introTitle' => 'AI adoption needs more than enthusiasm.',
			'introBody'  => 'Employees may already be experimenting with AI. TRG helps leadership understand what is being used, protect sensitive information, create clear rules and identify where AI genuinely helps.',
			'pills'      => 'Microsoft Copilot, Copilot readiness, Power BI, Power Automate, Workflow automation, Data readiness, Security, Permissions, AI governance, Responsible adoption, User training',
			'features'   => array(
				array( 'AI readiness review', 'Assess current tools, employee use, Microsoft 365 permissions, data concerns and organizational goals.' ),
				array( 'Data readiness and permissions', 'Get information organized and shared deliberately, because an AI assistant inherits whatever access the person already has.' ),
				array( 'Microsoft Copilot preparation', 'Review licensing, permissions, SharePoint access and data hygiene before broader adoption.' ),
				array( 'Workflow automation', 'Use Power Automate and Power BI to take repetitive work off people and put decisions in front of them instead.' ),
				array( 'Responsible-use policy and governance', 'Practical guidance on approved tools, sensitive data, review requirements and accountability — revisited as products and risks change.' ),
				array( 'User training', 'Teach people how to use AI effectively while recognizing accuracy, privacy and security limitations.' ),
			),
			'perspective' => array(
				'Use AI with a plan — not a free-for-all.',
				'The goal is not to adopt every new tool. It is to find responsible uses that improve work while protecting the business.',
			),
			'faq'        => array(
				array( 'What if employees are already using free AI tools?', 'That is common. The first step is understanding current use, identifying data risks and providing clear guidance about approved tools and information.' ),
				array( 'Can you help us prepare for Microsoft Copilot?', 'Yes. Copilot readiness includes licensing, data access, SharePoint permissions, security settings, policy and employee preparation.' ),
				array( 'Does TRG build custom AI software?', 'No. TRG does not build AI models. Our work is secure adoption of established platforms — Microsoft Copilot readiness, Power Platform automation, policy, governance, training and practical business use.' ),
			),
			'card'       => array( 'sparkles', 'Policies, training and practical use cases that help your team save time with AI while protecting company information.' ),
		),

		'cmmc-readiness' => array(
			'kind'       => 'service',
			'title'      => 'CMMC Readiness',
			'eyebrow'    => 'CMMC Readiness',
			'hero'       => 'Helping Government Contractors Prepare for CMMC',
			'lede'       => 'Readiness assessments, gap analysis, documentation, remediation planning, security improvements and ongoing compliance support for defense contractors.',
			'meta'       => 'CMMC readiness support covering scoping, gap analysis, NIST SP 800-171 alignment, SSP and POA&M development, remediation and C3PAO coordination.',
			'image'      => 'cmmc',
			'image_alt'  => 'Government contractors reviewing compliance documentation',
			'introTitle' => 'Readiness support — not the certification assessment itself.',
			'introBody'  => 'Certification under CMMC is awarded on the basis of an assessment carried out by an authorized C3PAO. TRG is not a C3PAO and does not award, grant or guarantee certification. What TRG does is prepare you for that assessment: scoping the environment, closing technical gaps, building the documentation and operating the controls afterwards.',
			'pills'      => 'CMMC readiness, Scoping, Gap analysis, NIST SP 800-171 alignment, CUI environment review, Policies and procedures, SSP, POA&M, Remediation, Technical controls, Evidence preparation, Mock assessment, C3PAO coordination, Ongoing compliance',
			'features'   => array(
				array( 'Scoping and CUI environment review', 'Identify the users, devices, locations, applications and third parties that may handle controlled unclassified information.' ),
				array( 'Gap analysis against NIST SP 800-171', 'Measure the environment control by control and produce a clear picture of where it currently stands.' ),
				array( 'Policies, procedures and SSP', 'Develop the System Security Plan and supporting documentation that an assessment will expect to see.' ),
				array( 'POA&M and remediation', 'Turn identified gaps into a Plan of Action and Milestones with prioritized, costed technical work behind it.' ),
				array( 'Technical controls and Microsoft GCC', 'Implement identity, endpoint, logging, email and backup controls, including GCC or GCC High where requirements call for it.' ),
				array( 'Evidence preparation and mock assessment', 'Assemble the evidence and rehearse the review so the real assessment is not the first time anyone has asked.' ),
			),
			'perspective' => array(
				'Readiness without false promises.',
				'TRG prepares the technical environment, the documentation and the evidence. The certification decision belongs to an authorized C3PAO, and we will not describe a business as compliant or certified before that assessment has happened.',
			),
			'faq'        => array(
				array( 'Does TRG certify us for CMMC?', 'No. CMMC certification is awarded following an assessment by an authorized C3PAO. TRG is not a C3PAO and cannot award or guarantee certification. We prepare your environment, documentation and evidence for that assessment, and we coordinate with the C3PAO you select.' ),
				array( 'What is the difference between a TRG readiness assessment and a certification assessment?', 'A TRG readiness assessment is an internal review: we measure your environment against the requirements, tell you where the gaps are and help close them. A certification assessment is a formal, independent evaluation performed by an authorized C3PAO that results in a certification decision.' ),
				array( 'Where does TRG stop and a compliance consultant start?', 'TRG covers the technology environment, the security controls, the technical documentation and ongoing operation of those controls. Where a program needs legal interpretation or a specialist consultant, we work alongside them rather than claiming the work ourselves.' ),
			),
			'card'       => array( 'badge-check', 'Readiness assessments, gap analysis, documentation and remediation that prepare defense contractors for a C3PAO assessment.' ),
		),

		'backup-business-continuity' => array(
			'kind'       => 'service',
			'title'      => 'Backup & Business Continuity',
			'eyebrow'    => 'Backup & Business Continuity',
			'hero'       => 'Business Continuity Built for the Unexpected',
			'lede'       => 'Protect critical systems and prepare your organization to continue operating when technology is disrupted.',
			'meta'       => 'Verified backups, Microsoft 365 and Azure backup, RTO and RPO planning, recovery testing and ransomware resilience from TRG Networking.',
			'image'      => 'about-team',
			'image_alt'  => 'A TRG team reviewing recovery priorities with a client',
			'introTitle' => 'A successful backup is only the beginning.',
			'introBody'  => 'Business continuity considers what must be restored, how quickly it is needed, who makes decisions and how employees continue working. TRG helps connect reliable backup to a realistic recovery plan.',
			'pills'      => 'Backup, Disaster recovery, Microsoft 365 backup, Azure backup, Endpoint and server backup, Recovery planning, RTO and RPO planning, Testing, Ransomware resilience, Business continuity planning',
			'features'   => array(
				array( 'Backup that covers the cloud too', 'Servers, endpoints, Microsoft 365 and Azure workloads. Microsoft protects its platform; your data inside it is still your responsibility.' ),
				array( 'Verification and testing', 'Monitor backup jobs, address failures and actually test a restore, rather than assuming everything completed successfully.' ),
				array( 'RTO and RPO planning', 'Agree how quickly each system must return and how much data you can afford to lose, then design backup to meet those numbers.' ),
				array( 'Ransomware resilience', 'Retention and isolation so a backup cannot be encrypted alongside everything it was meant to protect.' ),
				array( 'Disaster recovery', 'A documented path back for the systems and information that must return first to support essential operations.' ),
				array( 'Continuity planning', 'Responsibilities, communication and temporary processes for operating through the disruption itself.' ),
			),
			'perspective' => array(
				'The real question is not whether data was backed up.',
				'It is whether your business can recover the right systems and data within a timeframe that protects operations and customers.',
			),
			'card'       => array( 'database', 'Verified backups and recovery planning designed to keep a disruption from becoming a business-ending event.' ),
		),

		// The two solution areas the consolidated change request added. There was
		// no copy for either on the source sites, so this is written from the
		// capability lists in sections 2 and 7 of that document and nothing else —
		// no claims about clients, results or credentials have been invented.
		'network-infrastructure' => array(
			'kind'       => 'service',
			'title'      => 'Network Infrastructure',
			'eyebrow'    => 'Network Infrastructure',
			'hero'       => 'A Network Your Business Can Build On',
			'lede'       => 'Secure LAN and WAN, wireless, SD-WAN, network architecture and infrastructure optimization for organizations that cannot afford an unreliable connection.',
			'meta'       => 'Network architecture, secure LAN and WAN, wireless, SD-WAN and infrastructure optimization for multi-site organizations from TRG Networking.',
			'image'      => 'security',
			'image_alt'  => 'Technology staff reviewing network performance on screen',
			'introTitle' => 'Everything else runs on top of the network.',
			'introBody'  => 'Applications, phones, cloud services, security tools and remote workers all depend on it. TRG designs, secures and maintains the network layer so the systems above it stay dependable as the organization grows and adds locations.',
			'pills'      => 'LAN, WAN, Wireless, SD-WAN, Network architecture, Segmentation, Firewalls, Multi-site connectivity, Remote access, Monitoring, Optimization',
			'features'   => array(
				array( 'Network architecture', 'Design around how your sites, users, applications and cloud services actually connect, rather than around what was installed years ago.' ),
				array( 'Secure LAN and WAN', 'Firewalls, segmentation and access rules that keep systems separated according to real operational and security needs.' ),
				array( 'Wireless', 'Coverage and capacity planning for offices, warehouses and sites where a dead spot stops work.' ),
				array( 'SD-WAN and multi-site connectivity', 'Connect locations with resilience and sensible routing instead of one fragile link per building.' ),
				array( 'Monitoring', 'See problems on the network before the help desk hears about them from users.' ),
				array( 'Infrastructure optimization', 'Review what is installed, retire what is unsupported and plan replacements before hardware forces the decision.' ),
			),
			'perspective' => array(
				'Nobody notices the network until it stops.',
				'That is exactly why it deserves design, monitoring and a replacement plan rather than attention only when something has already broken.',
			),
			'card'       => array( 'network', 'Secure LAN and WAN, wireless, SD-WAN and network architecture that keep every site and system connected.' ),
		),

		'strategic-it-vcio' => array(
			'kind'       => 'service',
			'title'      => 'Strategic IT / vCIO',
			'eyebrow'    => 'Strategic IT / vCIO',
			'hero'       => 'Technology Decisions Made With the Business in Mind',
			'lede'       => 'Technology strategy, planning, budgeting and executive-level guidance aligned with where your organization is going.',
			'meta'       => 'Virtual CIO services: technology strategy, roadmaps, budgeting, vendor decisions and executive-level guidance from TRG Networking.',
			'image'      => 'cmmc',
			'image_alt'  => 'Leadership team reviewing a technology roadmap together',
			'introTitle' => 'Most technology money is spent reacting.',
			'introBody'  => 'A vCIO changes that. TRG sits with leadership, learns how the business makes money and where it is heading, and turns that into a technology roadmap with priorities, costs and timing attached — so decisions are made ahead of the renewal date rather than on it.',
			'pills'      => 'Technology strategy, Roadmaps, Budgeting, Lifecycle planning, Vendor evaluation, Risk review, Executive reporting, Compliance planning',
			'features'   => array(
				array( 'Technology strategy', 'Connect what the technology is doing to what the organization is trying to achieve over the next one to three years.' ),
				array( 'Roadmap and lifecycle planning', 'Know what needs replacing, upgrading or retiring, and roughly when, before it becomes urgent.' ),
				array( 'Budgeting', 'Predictable annual technology budgets with capital and recurring costs separated and explained.' ),
				array( 'Vendor evaluation', 'An informed second opinion on the software and services being sold to you, and on the ones you already have.' ),
				array( 'Risk and compliance planning', 'Look ahead at security, regulatory and contractual requirements while there is still time to plan for them.' ),
				array( 'Executive reporting', 'Regular reviews written for leadership, in plain English, with the decisions clearly separated from the detail.' ),
			),
			'perspective' => array(
				'Every recommendation should serve the business.',
				'Cost, risk, usability, operations and long-term value all belong in the conversation. A recommendation that only makes technical sense is not a recommendation.',
			),
			'card'       => array( 'compass', 'Technology strategy, roadmaps, budgeting and executive-level guidance aligned with your business goals.' ),
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
