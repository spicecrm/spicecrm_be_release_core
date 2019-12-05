<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

/*********************************************************************************
 * Description:  Defines the Arabic language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Mehyar Sawas-Donauer (mehyar.sawas@twentyreasons.com)
 ********************************************************************************/
$app_list_strings = array(
    'language_pack_name' => 'SY Arabic',
    'checkbox_dom' => array(
        '' => '',
        '1' => 'نعم',
        '2' => 'لا',
    ),

    'account_type_dom' =>        array(
        '' => '',
        'Analyst' => 'محلل',
        'Competitor' => 'منافس',
        'Customer' => 'زبون',
        'Integrator' => 'دامج',
        'Investor' => 'مستثمر',
        'Partner' => 'شريك',
        'Press' => 'صحافة',
        'Prospect' => 'محتمل',
        'Reseller' => 'موزع',
        'Other' => 'أخرى',
    ),
    'account_user_roles_dom' =>        array(
        '' => '',
        'am' => 'مدير حسابات',
        'se' => 'مهندس دعم',
        'es' => 'راعي تنفيذي'
    ),
    'events_account_roles_dom' =>        array(
        '' => '',
        'organizer' => 'منظّم',
        'sponsor' => 'راع',
        'caterer' => 'متعهد حفلات'
    ),
    'events_contact_roles_dom' =>        array(
        '' => '',
        'organizer' => 'منظّم',
        'speaker' => 'متحدث',
        'moderator' => 'مشرف',
    ),
    'userabsences_type_dom' =>        array(
        '' => '',
        'Sick leave' => 'مرض',
        'Vacation' => 'إجازة',
    ),
    'industry_dom' =>
        array(
            '' => '',
            'Apparel' => 'ملابس',
            'Banking' => 'خدمات مصرفية',
            'Biotechnology' => 'تكنولوجيا حيوية',
            'Chemicals' => 'مواد كيميائية',
            'Communications' => 'مجال الاتصالات',
            'Construction' => 'اعمال بناء',
            'Consulting' => 'استشارات',
            'Education' => 'تعليم',
            'Electronics' => 'إلكترونيات',
            'Energy' => 'طاقة',
            'Engineering' => 'هندسة',
            'Entertainment' => 'وسائل الترفيه',
            'Environmental' => 'بيئي',
            'Finance' => 'مالية',
            'Government' => 'حكومي',
            'Healthcare' => 'رعاية صحية',
            'Hospitality' => 'استضافة',
            'Insurance' => 'تأمين',
            'Machinery' => 'آلات',
            'Manufacturing' => 'تصنيع',
            'Media' => 'وسائل الإعلام',
            'Not For Profit' => 'غير ربحية',
            'Recreation' => 'ترفيهية',
            'Retail' => 'بيع بالتجزئة',
            'Shipping' => 'شحن',
            'Technology' => 'تقنية',
            'Telecommunications' => 'اتصالات',
            'Transportation' => 'وسائل النقل',
            'Utilities' => 'خدمات',
            'Other' => 'أخرى',
        ),
    'lead_source_default_key' => 'ولد نفسه',
    'lead_source_dom' =>
        array(
            '' => '',
            'Cold Call' => 'نداء بارد',
            'Existing Customer' => 'زبون موجود',
            'Self Generated' => 'ولد نفسه',
            'Employee' => 'موظف',
            'Partner' => 'شريك',
            'Public Relations' => 'علاقات عامة',
            'Direct Mail' => 'أيميل مباشر',
            'Conference' => 'مؤتمر',
            'Trade Show' => 'عرض تجاري',
            'Web Site' => 'موقع أنترنت',
            'Word of mouth' => 'كلمة التسويق',
            'Email' => 'أيميل',
            'Campaign' => 'حملة إعلانية',
            'Other' => 'أخرى',
        ),
    'opportunity_type_dom' =>
        array(
            '' => '',
            'Existing Business' => 'أعمال قائمة',
            'New Business' => 'أعمال جديدة',
        ),
    'roi_type_dom' =>
        array(
            'Revenue' => 'إيرادات',
            'Investment' => 'استثمار',
            'Expected_Revenue' => 'الإيرادات المتوقعة',
            'Budget' => 'ميزانية',

        ),
    //Note:  do not translate opportunity_relationship_type_default_key
//       it is the key for the default opportunity_relationship_type_dom value
    'opportunity_relationship_type_default_key' => 'صانع القرار الرئيسي',
    'opportunity_relationship_type_dom' =>
        array(
            '' => '',
            'Primary Decision Maker' => 'صانع القرار الرئيسي',
            'Business Decision Maker' => 'صانع القرار التجاري',
            'Business Evaluator' => 'مقيم الأعمال',
            'Technical Decision Maker' => 'صانع القرار الفني',
            'Technical Evaluator' => 'مقيم فني',
            'Executive Sponsor' => 'الراعي التنفيذي',
            'Influencer' => 'مؤثر',
            'Project Manager' => 'مدير المشاريع',
            'Other' => 'أخرى',
        ),
    //Note:  do not translate case_relationship_type_default_key
//       it is the key for the default case_relationship_type_dom value
    'case_relationship_type_default_key' => 'جهة إتصال رئيسية',
    'case_relationship_type_dom' =>
        array(
            '' => '',
            'Primary Contact' => 'جهة إتصال رئيسية',
            'Alternate Contact' => 'جهة إتصال بديلة',
        ),
    'payment_terms' =>
        array(
            '' => '',
            'Net 15' => 'صافي 15',
            'Net 30' => 'صافي 30',
        ),
    'sales_stage_default_key' => 'تقصّي',
    'fts_type' => array(
        '' => '',
        'Elastic' => 'elasticsearch'
    ),
    'sales_stage_dom' => array(
// CR1000302 adapt to match opportunity spicebeanguidestages
//        'Prospecting' => 'تقصّي',
        'Qualification' => 'مؤهل',
        'Analysis' => 'تحليل الاحتياجات',
        'Proposition' => 'إقتراح قيمة',
//        'Id. Decision Makers' => 'تحديد صناع القرار',
//        'Perception Analysis' => 'تحليل المعرفة',
        'Proposal' => 'اقتراح / اقتباس السعر',
        'Negotiation' => 'تفاوض / مراجعة',
        'Closed Won' => 'مغلق رابح',
        'Closed Lost' => 'مغلق خاسر',
        'Closed Discontinued' => 'مغلق متوقف'
    ),
    'opportunityrevenuesplit_dom' => array(
        'none' => 'لا شيء',
        'split' => 'شق',
        'rampup' => 'تكثيف'
    ),
    'opportunity_relationship_buying_center_dom' => array(
        '++' => 'إيجابي جدا',
        '+' => 'إيجابي',
        'o' => 'حيادي',
        '-' => 'سلبي',
        '--' => 'سلبي جداً'
    ),
    'in_total_group_stages' => array(
        'Draft' => 'مسودة',
        'Negotiation' => 'تفاوض',
        'Delivered' => 'تم إيصاله',
        'On Hold' => 'في الانتظار',
        'Confirmed' => 'تم تأكيده',
        'Closed Accepted' => 'مغلق مقبول',
        'Closed Lost' => 'مغلق خاسر',
        'Closed Dead' => 'مغلق ميت',
    ),
    'sales_probability_dom' => // keys must be the same as sales_stage_dom
        array(
            'Prospecting' => '10',
            'Qualification' => '20',
            'Needs Analysis' => '25',
            'Value Proposition' => '30',
            'Id. Decision Makers' => '40',
            'Perception Analysis' => '50',
            'Proposal/Price Quote' => '65',
            'Negotiation/Review' => '80',
            'Closed Won' => '100',
            'Closed Lost' => '0',
        ),
    'competitive_threat_dom' => array(
        '++' => 'عال جداً',
        '+' => 'عال',
        'o' => 'حيادي',
        '-' => 'منخفض',
        '--' => 'منخفض جداً'
    ),
    'competitive_status_dom' => array(
        'active' => 'نشط في دورة المبيعات',
        'withdrawn' => 'سحبت من قبل المنافس',
        'rejected' => 'رفض من قبل العملاء'
    ),
    'activity_dom' => array(
        'Call' => 'إتصال',
        'Meeting' => 'إجتماع',
        'Task' => 'مهمة',
        'Email' => 'أيميل',
        'Note' => 'ملاحظة',
    ),
    'salutation_dom' => array(
        '' => '',
        'Mr.' => 'السيد',
        'Ms.' => 'الآنسة',
    ),
    'salutation_letter_dom' => array(
        '' => '',
        'Mr.' => 'عزيزي السيد',
        'Ms.' => 'عزيزتي السيدة',
    ),
    'gdpr_marketing_agreement_dom' => array(
        '' => '',
        'r' => 'مرفوض',
        'g' => 'تم منحه',
    ),
    'uom_unit_dimensions_dom' => array(
        '' => '',
        'none' => 'لاشيئ',
        'weight' => 'وزن',
        'volume' => 'كمية',
        'area' => 'منطقة',
    ),
    'contacts_title_dom' => array(
        '' => '',
        'ceo' => 'المدير التنفيذي',
        'cfo' => 'المدير المالي',
        'cto' => 'المدير التقني',
        'cio' => 'مدير المعلومات',
        'coo' => 'مدير العمليات',
        'cmo' => 'مدير التسويق',
        'vp sales' => 'نائب رئيس المبيعات',
        'vp engineering' => 'نائب رئيس المهندسين',
        'vp procurement' => 'نائب رئيس المشتريات',
        'vp finance' => 'نائب رئيس المالية',
        'vp marketing' => 'نائب رئيس للتسويق',
        'sales' => 'مبيعات',
        'engineering' => 'هندسة',
        'procurement' => 'مشتريات',
        'finance' => 'مالية',
        'marketing' => 'تسويق'
    ),
    'personalinterests_dom' => array(
        'sports' => 'رياضة',
        'food' => 'طعام',
        'wine' => 'نبيذ',
        'culture' => 'ثقافة',
        'travel' => 'سفر',
        'books' => 'كتب',
        'animals' => 'حيوانات',
        'clothing' => 'ألبسة',
        'cooking' => 'طبخ',
        'fashion' => 'موضة',
        'music' => 'موسيقى',
        'fitness' => 'لياقة بدنية'
    ),
    'questionstypes_dom' => array(
        'rating' => 'تقييم',
        'binary' => 'اختيار ثنائي',
        'single' => 'اختيار واحد',
        'multi' => 'إختيار متعدد',
        'text' => 'نصل إدخال',
        'ist' => 'يكون',
        'nps' => 'NPS (تقييم درجة الرضى)'
    ),
    'evaluationtypes_dom' => array(
        'default' => 'إفتراضي',
        'spiderweb' => 'الشبكة العنكبوتية'
    ),
    'evaluationsorting_dom' => array(
        'categories' => 'حسب الفئات (أبجدي)',
        'points asc' => 'حسب النقاط, تصاعدي',
        'points desc' => 'حسب النقاط, تنازلي'
    ),
    'interpretationsuggestions_dom' => array(
        'top3' => 'أعلى 3',
        'top5' => 'أعلى 5',
        'over20' => 'أعلى من 20 نقطة',
        'over30' => 'أعلى من 30 نقطة',
        'over40' => 'أعلى من 40 نقطة',
        'top3_upfrom20' => 'أعلى 3 أو أعلى من 20 نقطة',
        'top5_upfrom20' => 'أعلى 5 أو أعلى من 20 نقطة',
        'top3_upfrom30' => 'أعلى 3 أو أعلى من 30 نقطة',
        'top5_upfrom30' => 'أعلى 5 أو أعلى من 30 نقطة',
        'top3_upfrom40' => 'أعلى 3 أو أعلى من 40 نقطة',
        'top5_upfrom40' => 'أعلى 5 أو أعلى من 40 نقطة',
        'all' => 'كل التفسيرات',
        'mbti' => 'MBTI'
    ),
    //time is in seconds; the greater the time the longer it takes;
    'reminder_max_time' => 90000,
    'reminder_time_options' => array(60 => '1 minute prior',
        300 => 'قبل 5 دقائق',
        600 => 'قبل 10 دقائق',
        900 => 'قبل 15 دقيقة',
        1800 => 'قبل 30 دقيقة',
        3600 => 'قبل 1 ساعة',
        7200 => 'قبل 2 ساعة',
        10800 => 'قبل 3 ساعة',
        18000 => 'قبل 5 ساعة',
        86400 => 'قبل 1 يوم',
    ),

    'task_priority_default' => 'Medium',
    'task_priority_dom' =>
        array(
            'High' => 'عال',
            'Medium' => 'متوسط',
            'Low' => 'منخفض',
        ),
    'task_status_default' => 'لم يبدء',
    'task_status_dom' =>
        array(
            'Not Started' => 'لم يبدء',
            'In Progress' => 'جار العمل عليه',
            'Completed' => 'مكتمل',
            'Pending Input' => 'بانتظار مدخل',
            'Deferred' => 'مؤجل',
        ),
    'meeting_status_default' => 'مخطط له',
    'meeting_status_dom' =>
        array(
            'Planned' => 'مخطط له',
            'Held' => 'تم عقده',
            'Not Held' => 'لم يعقد',
        ),
    'extapi_meeting_password' =>
        array(
            'WebEx' => 'WebEx',
        ),
    'meeting_type_dom' =>
        array(
            'Other' => 'أخرى',
            'Spice' => 'SpiceCRM',
        ),
    'call_status_default' => 'مخطط له',
    'call_status_dom' =>
        array(
            'Planned' => 'مخطط له',
            'Held' => 'تم عقده',
            'Not Held' => 'لم يعقد',
        ),
    'call_direction_default' => 'صادر',
    'call_direction_dom' =>
        array(
            'Inbound' => 'وارد',
            'Outbound' => 'صادر',
        ),
    'lead_status_dom' =>
        array(
            '' => '',
            'New' => 'جديد',
            'Assigned' => 'تم تعيينه',
            'In Process' => 'جار العمل عليه',
            'Converted' => 'تم تحويله',
            'Recycled' => 'أعيد تدويره',
            'Dead' => 'ميت',
        ),
    'lead_classification_dom' =>
        array(
            'cold' => 'بارد',
            'warm' => 'دافئ',
            'hot' => 'ساخن'
        ),
    'gender_list' =>
        array(
            'male' => 'ذكر',
            'female' => 'أنثى',
        ),
    //Note:  do not translate case_status_default_key
//       it is the key for the default case_status_dom value
    'case_status_default_key' => 'جديد',
    'case_status_dom' =>
        array(
            'New' => 'جديد',
            'Assigned' => 'تم تعيينه',
            'Closed' => 'مغلق',
            'Pending Input' => 'بانتظار مدخل',
            'Rejected' => 'تم رفضه',
            'Duplicate' => 'مكرر',
        ),
    'case_priority_default_key' => 'P2',
    'case_priority_dom' =>
        array(
            'P1' => 'عال',
            'P2' => 'متوسط',
            'P3' => 'منخفض',
        ),
    'user_type_dom' =>
        array(
            'RegularUser' => 'مستخدم عادي',
            'PortalUser' => 'مستخدم البوابة',
            'Administrator' => 'مدير',
        ),
    'user_status_dom' =>
        array(
            'Active' => 'نشط',
            'Inactive' => 'غير نشط',
        ),
    'calendar_type_dom' =>
        array(
            'Full' => 'كامل',
            'Day' => 'يوم',
        ),
    'knowledge_status_dom' =>
        array(
            'Draft' => 'مسودة',
            'Released' => 'تم إصداره',
            'Retired' => 'متقاعد',
        ),
    'employee_status_dom' =>
        array(
            'Active' => 'نشط',
            'Terminated' => 'تم إنهاءه',
            'Leave of Absence' => 'إجازة الغياب',
        ),
    'messenger_type_dom' =>
        array(
            '' => '',
            'MSN' => 'MSN',
            'Yahoo!' => 'Yahoo!',
            'AOL' => 'AOL',
        ),
    'project_task_priority_options' => array(
        'High' => 'عال',
        'Medium' => 'متوسط',
        'Low' => 'منخفض',
    ),
    'project_task_priority_default' => 'Medium',

    'project_task_status_options' => array(
        'Not Started' => 'لم يبدء',
        'In Progress' => 'جار العمل عليه',
        'Completed' => 'مكتمل',
        'Pending Input' => 'بانتظار مدخل',
        'Deferred' => 'مؤجل',
    ),
    'project_task_utilization_options' => array(
        '0' => 'لاشيئ',
        '25' => '25',
        '50' => '50',
        '75' => '75',
        '100' => '100',
    ),
    'project_type_dom' => array(
        'customer' => 'زبون',
        'development' => 'تطوير',
        'sales' => 'مبيعات'
    ),
    'project_status_dom' => array(
        'planned' => 'مخطط له',
        'active' => 'نشط',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغى',
        'Draft' => 'مسودة',
        'In Review' => 'في مراجعة',
        'Published' => 'تم نشره',
    ),
    'project_status_default' => 'مسودة',

    'project_duration_units_dom' => array(
        'Days' => 'أيام',
        'Hours' => 'ساعات',
    ),

    'project_priority_options' => array(
        'High' => 'عال',
        'Medium' => 'متوسط',
        'Low' => 'منخفض',
    ),
    'project_priority_default' => 'متوسط',
    //Note:  do not translate record_type_default_key
//       it is the key for the default record_type_module value
    'record_type_default_key' => 'Accounts',
    'record_type_display' =>
        array(
            '' => '',
            'Accounts' => 'حساب',
            'Opportunities' => 'فرصة',
            'Proposals' => 'إقتراح',
            'Cases' => 'قضية',
            'Leads' => 'جهة مهتمة',
            'Contacts' => 'جهاة اتصال', // cn (11/22/2005) added to support Emails


            'Bugs' => 'خلل',
            'Projects' => 'مشروع',

            'Prospects' => 'هدف',
            'ProjectTasks' => 'مهمة مشروع',


            'Tasks' => 'مهمة',

        ),

    'record_type_display_notes' =>
        array(
            'Accounts' => 'حساب',
            'Contacts' => 'جهة إتصال',
            'Opportunities' => 'فرصة',
            'Proposals' => 'اقتراح',
            'Tasks' => 'مهمة',
            'Emails' => 'أيميل',

            'Bugs' => 'خلل',
            'Projects' => 'مشروع',
            'ProjectTasks' => 'مهمة مشروع',
            'Prospects' => 'هدف',
            'Cases' => 'قضية',
            'Leads' => 'جهة مهتمة',

            'Meetings' => 'إجتماع',
            'Calls' => 'إتصال',
        ),

    'parent_type_display' =>
        array(
            'Accounts' => 'حساب',
            'Contacts' => 'جهة إتصال',
            'Tasks' => 'مهمة',
            'Opportunities' => 'فرصة',
            'Proposals' => 'اقتراح',

            'Bugs' => 'خلل',
            'Cases' => 'قضية',
            'Leads' => 'جهة مهتمة',

            'Projects' => 'مشروع',
            'ProjectTasks' => 'مهمة مشروع',

            'Prospects' => 'هدف',
            'Events' => 'حدث',

        ),

    'issue_priority_default_key' => 'Medium',
    'issue_priority_dom' =>
        array(
            'Urgent' => 'طارئ',
            'High' => 'عال',
            'Medium' => 'متوسط',
            'Low' => 'منخفض',
        ),
    'issue_resolution_default_key' => '',
    'issue_resolution_dom' =>
        array(
            '' => '',
            'Accepted' => 'مقبول',
            'Duplicate' => 'مكرر',
            'Closed' => 'مغلق',
            'Out of Date' => 'انتهت صلاحيته',
            'Invalid' => 'غير صالح',
        ),

    'issue_status_default_key' => 'New',
    'issue_status_dom' =>
        array(
            'New' => 'جديد',
            'Assigned' => 'معين',
            'Closed' => 'مغلق',
            'Pending' => 'قيد الانتظار',
            'Rejected' => 'مرفوض',
        ),

    'bug_priority_default_key' => 'Medium',
    'bug_priority_dom' =>
        array(
            'Urgent' => 'طارئ',
            'High' => 'عال',
            'Medium' => 'متوسط',
            'Low' => 'منخفض',
        ),
    'bug_resolution_default_key' => '',
    'bug_resolution_dom' =>
        array(
            '' => '',
            'Accepted' => 'مقبول',
            'Duplicate' => 'مكرر',
            'Fixed' => 'تم إصلاحه',
            'Out of Date' => 'انتهت صلاحيته',
            'Invalid' => 'غير صالح',
            'Later' => 'لاحقاً',
        ),
    'bug_status_default_key' => 'New',
    'bug_status_dom' =>
        array(
            'New' => 'جديد',
            'Assigned' => 'معين',
            'Closed' => 'مغلق',
            'Pending' => 'قيد الانتظار',
            'Rejected' => 'مرفوض',
        ),
    'bug_type_default_key' => 'Bug',
    'bug_type_dom' =>
        array(
            'Defect' => 'عطل',
            'Feature' => 'خاصية',
        ),
    'case_type_dom' =>
        array(
            'Administration' => 'إدارة',
            'Product' => 'منتج',
            'User' => 'مستخدم',
        ),

    'source_default_key' => '',
    'source_dom' =>
        array(
            '' => '',
            'Internal' => 'داخلي',
            'Forum' => 'منتدى',
            'Web' => 'موقع إلكتروني',
            'InboundEmail' => 'بريد وارد'
        ),

    'product_category_default_key' => '',
    'product_category_dom' =>
        array(
            '' => '',
            'Accounts' => 'حسابات',
            'Activities' => 'نشاطات',
            'Bugs' => 'خلائل',
            'Calendar' => 'روزنامة',
            'Calls' => 'إتصالات',
            'Campaigns' => 'حملات إعلانية',
            'Cases' => 'قضايا',
            'Contacts' => 'جهات إتصال',
            'Currencies' => 'عملات',
            'Dashboard' => 'لوحة قيادة',
            'Documents' => 'مستندات',
            'Emails' => 'أيميلات',
            'Feeds' => 'مغذيات',
            'Forecasts' => 'توقعات',
            'Help' => 'مساعدة',
            'Home' => 'رئيسية',
            'Leads' => 'جهات مهتمة',
            'Meetings' => 'إجتماعات',
            'Notes' => 'ملاحظات',
            'Opportunities' => 'فرص',
            'Outlook Plugin' => 'Outlook إضافة ',
            'Projects' => 'مشاريع',
            'Quotes' => 'إقتباسات',
            'Releases' => 'إصدارات',
            'RSS' => 'RSS',
            'Studio' => 'ستوديو',
            'Upgrade' => 'تحسين',
            'Users' => 'مستخدمين',
        ),
    'product_types_dom' => array(
        'service' => 'خدمة',
        'license' => 'رخصة',
        'product' => 'منتج'
    ),
    'product_occurence_dom' => array(
        'onetime' => 'مرة واحدة',
        'recurring' => 'تناوبي'
    ),
    /*Added entries 'Queued' and 'Sending' for 4.0 release..*/
    'campaign_status_dom' =>
        array(
            '' => '',
            'Planning' => 'تخطيط',
            'Active' => 'نشط',
            'Inactive' => 'غير نشط',
            'Complete' => 'مكتمل',
            'In Queue' => 'في قائمة الانتظار',
            'Sending' => 'جار الإرسال',
        ),
    'campaign_type_dom' => array(
        '' => '',
        'Event' => 'حدث',
        'Telesales' => 'مبيعات عبر الهاتف',
        'Mail' => 'بريد',
        'Email' => 'أيميل',
        'Print' => 'طباعة',
        'Web' => 'ويب',
        'Radio' => 'راديو',
        'Television' => 'تلفزيون',
        'NewsLetter' => 'نشرة إخبارية',
    ),
    'campaigntask_type_dom' => array(
        '' => '',
        'Event' => 'حدث',
        'Telesales' => 'مبيعات عبر الهاتف',
        'Mail' => 'بريد',
        'Email' => 'أيميل',
        'Print' => 'طباعة',
        'Web' => 'ويب',
        'Radio' => 'راديو',
        'Television' => 'تلفزيون',
        'NewsLetter' => 'نشرة إخبارية',
    ),
    'newsletter_frequency_dom' =>
        array(
            '' => '',
            'Weekly' => 'أسبوعي',
            'Monthly' => 'شهري',
            'Quarterly' => 'فصلي',
            'Annually' => 'سنوي',
        ),

    'notifymail_sendtype' =>
        array(
            'SMTP' => 'SMTP',
        ),
    'servicecall_type_dom' => array(
        'info' => 'طلب معلومات',
        'complaint' => 'شكوى',
        'return' => 'إرجاع',
        'service' => 'طلب خدمة',
    ),
    'dom_cal_month_long' => array(
        '0' => "",
        '1' => "كانون الثاني",
        '2' => "شباط",
        '3' => "آذار",
        '4' => "نيسان",
        '5' => "أيار",
        '6' => "حزيران",
        '7' => "تموز",
        '8' => "آب",
        '9' => "أيلول",
        '10' => "تشرين الأول",
        '11' => "تشرين الثاني",
        '12' => "كانون الأول",
    ),
    'dom_cal_month_short' => array(
        '0' => "",
        '1' => "كانون الثاني",
        '2' => "شباط",
        '3' => "آذار",
        '4' => "نيسان",
        '5' => "أيار",
        '6' => "حزيران",
        '7' => "تموز",
        '8' => "آب",
        '9' => "أيلول",
        '10' => "تشرين الأول",
        '11' => "تشرين الثاني",
        '12' => "كانون الأول",
    ),
    'dom_cal_day_long' => array(
        '0' => "",
        '1' => "الأحد",
        '2' => "الأثنين",
        '3' => "الثلاثاء",
        '4' => "الأربعاء",
        '5' => "الخميس",
        '6' => "الجمعة",
        '7' => "السبت",
    ),
    'dom_cal_day_short' => array(
        '0' => "",
        '1' => "الأحد",
        '2' => "الأثنين",
        '3' => "الثلاثاء",
        '4' => "الأربعاء",
        '5' => "الخميس",
        '6' => "الجمعة",
        '7' => "السبت",
    ),
    'dom_meridiem_lowercase' => array(
        'am' => "ص",
        'pm' => "م"
    ),
    'dom_meridiem_uppercase' => array(
        'AM' => 'ص',
        'PM' => 'م'
    ),

    'dom_report_types' => array(
        'tabular' => 'الصفوف والأعمدة',
        'summary' => 'خلاصة',
        'detailed_summary' => 'خلاصة مع التفاصيل',
        'Matrix' => 'مصفوفة',
    ),


    'dom_email_types' => array(
        'out' => 'إرسال',
        'archived' => 'مأرشف',
        'draft' => 'مسودة',
        'inbound' => 'وارد',
        'campaign' => 'حملة إعلانية'
    ),
    'dom_email_status' => array(
        'archived' => 'مأرشف',
        'closed' => 'مغلق',
        'draft' => 'مسودة',
        'read' => 'مقروء',
        'replied' => 'رد عليه',
        'sent' => 'مرسل',
        'send_error' => 'خطأ بالإرسال',
        'unread' => 'غير مقروء',
    ),
    'dom_textmessage_status' => array(
        'archived' => 'مأرشف',
        'closed' => 'مغلق',
        'draft' => 'مسودة',
        'read' => 'مقروء',
        'replied' => 'رد عليه',
        'sent' => 'مرسل',
        'send_error' => 'خطأ بالإرسال',
        'unread' => 'غير مقروء',
    ),
    'dom_email_archived_status' => array(
        'archived' => 'مأرشف',
    ),
    'dom_email_openness' => array(
        'open' => 'مفتوح',
        'user_closed' => 'مغلق من قبل المستخدم',
        'system_closed' => 'مغلق من قبل النظام'
    ),
    'dom_textmessage_openness' => array(
        'open' => 'مفتوح',
        'user_closed' => 'مغلق من قبل المستخدم',
        'system_closed' => 'مغلق من قبل النظام'
    ),
    'dom_email_server_type' => array(
        '' => '--غير محدد--',
        'imap' => 'IMAP',
    ),
    'dom_mailbox_type' => array(
        'pick' => '--غير محدد--',
        'createcase' => 'إنشاء قضية',
        'bounce' => 'معالجمة الخطوات',
    ),
    'dom_email_distribution' => array(
        '' => '--غير محدد--',
        'direct' => 'تعيين مباشر',
        'roundRobin' => 'جولة منافسة',
        'leastBusy' => 'إقل إنشغالاً',
    ),
    'dom_email_distribution_for_auto_create' => array(
        'roundRobin' => 'جولة منافسة',
        'leastBusy' => 'إقل إنشغالاً',
    ),
    'dom_email_errors' => array(
        1 => 'حدد مستخدمًا واحدًا فقط عند تحديد العناصر مباشرة.',
        2 => 'يجب عليك تعيين العناصر المحددة فقط عند تحديد عناصر المباشرة.',
    ),
    'dom_email_bool' => array(
        'bool_true' => 'نعم',
        'bool_false' => 'لا',
    ),
    'dom_int_bool' => array(
        1 => 'نعم',
        0 => 'لا',
    ),
    'dom_switch_bool' => array(
        '' => '',
        'on' => 'نعم',
        'off' => 'لا',
    ),

    'dom_email_link_type' => array(
        'sugar' => 'Spice عميل الإيميل',
        'mailto' => 'عميل الأيميل الخارجي'),


    'dom_email_editor_option' => array('' => 'Default Email Format',
        'html' => 'HTML أيميل',
        'plain' => 'إيميل نص بدون تنسيق'),

    'schedulers_times_dom' => array(
        'not run' => 'وقت التشغيل الماضي ، لم يتم التنفيذ',
        'ready' => 'جاهز',
        'in progress' => 'جار العمل عليه',
        'failed' => 'أخفق',
        'completed' => 'مكتمل',
        'no curl' => 'لا يعمل: لاتوجد cURL متاحة',
    ),

    'scheduler_status_dom' =>
        array(
            'Active' => 'نشط',
            'Inactive' => 'غير نشط',
        ),

    'scheduler_period_dom' =>
        array(
            'min' => 'دقائق',
            'hour' => 'ساعات',
        ),
    'forecast_schedule_status_dom' =>
        array(
            'Active' => 'نشط',
            'Inactive' => 'غير نشط',
        ),
    'forecast_type_dom' =>
        array(
            'Direct' => 'مباشر',
            'Rollup' => 'تراكمي',
        ),
    'document_category_dom' =>
        array(
            '' => '',
            'Marketing' => 'تسويق',
            'Knowledege Base' => 'قاعدة المعرفة',
            'Sales' => 'مبيعات',
        ),

    'document_subcategory_dom' =>
        array(
            '' => '',
            'Marketing Collateral' => 'ضمان التسويق',
            'Product Brochures' => 'كتيبات المنتجات',
            'FAQ' => 'أسئلة المتكررة',
        ),

    'document_status_dom' =>
        array(
            'Active' => 'نشط',
            'Draft' => 'مسودة',
            'FAQ' => 'أسئلة المتكررة',
            'Expired' => 'منتهية الصلاحية',
            'Under Review' => 'تحت المراجعة',
            'Pending' => 'قيد الانتظار',
        ),
    'document_template_type_dom' =>
        array(
            '' => '',
            'mailmerge' => 'دمج البريد',
            'eula' => 'EULA',
            'nda' => 'NDA',
            'license' => 'اتفاقية الترخيص',
        ),
    'dom_meeting_accept_options' =>
        array(
            'accept' => 'قبول',
            'decline' => 'رفض',
            'tentative' => 'مؤقت',
        ),
    'dom_meeting_accept_status' =>
        array(
            'accept' => 'مقبول',
            'decline' => 'مرفوض',
            'tentative' => 'مؤقت',
            'none' => 'لا شيئ',
        ),
    'duration_intervals' => array('0' => '00',
        '15' => '15',
        '30' => '30',
        '45' => '45'),

    'repeat_type_dom' => array(
        '' => 'None',
        'Daily' => 'يومي',
        'Weekly' => 'أسبوعي',
        'Monthly' => 'شهري',
        'Yearly' => 'سنوي',
    ),

    'repeat_intervals' => array(
        '' => '',
        'Daily' => 'أيام',
        'Weekly' => 'أسابيع',
        'Monthly' => 'أشهر',
        'Yearly' => 'سنوات',
    ),

    'duration_dom' => array(
        '' => 'لا شيئ',
        '900' => '15 دقيقة',
        '1800' => '30 دقيقة',
        '2700' => '45 دقيقة',
        '3600' => '1 ساعة',
        '5400' => '1.5 ساعة',
        '7200' => '2 ساعة',
        '10800' => '3 ساعات',
        '21600' => '6 ساعات',
        '86400' => '1 يوم',
        '172800' => '2 يوم',
        '259200' => '3 أيام',
        '604800' => '1 أسبوع',
    ),

// deferred
    /*// QUEUES MODULE DOMs
    'queue_type_dom' => array(
        'Users' => 'Users',
        'Mailbox' => 'Mailbox',
    ),
    */
//prospect list type dom
    'prospect_list_type_dom' =>
        array(
            'default' => 'إفتراضي',
            'seed' => 'بذرة',
            'exempt_domain' => 'قائمة إخماد - حسب الدومين',
            'exempt_address' => 'قائمة إخماد - حسب الأيميل',
            'exempt' => 'قائمة إخماد - حسب حقل الهوية',
            'test' => 'فحص',
        ),

    'email_settings_num_dom' =>
        array(
            '10' => '10',
            '20' => '20',
            '50' => '50'
        ),
    'email_marketing_status_dom' =>
        array(
            '' => '',
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ),

    'campainglog_activity_type_dom' =>
        array(
            '' => '',
            'queued' => 'في قائمة الانتظار',
            'sent' => 'مرسل',
            'delivered' => 'تم إيصاله',
            'opened' => 'مفتوح',
            'deferred' => 'مؤجل',
            'bounced' => 'تم تخطيه',
            'targeted' => 'رسالة مرسلة/محاولة',
            'send error' => 'رسائل تخطي،أخرى',
            'invalid email' => 'رسائل تخطي، أيميل خاطئ',
            'link' => 'تمت زيارته',
            'viewed' => 'مفتوح',
            'removed' => 'تم سحبه',
            'lead' => 'تم إنشاء جهات مهتمة',
            'contact' => 'تم إنشاء جهات إتصال',
            'blocked' => 'حظرت حسب العنوان أو الدومين',
            'error' => 'خطأ عام',
            'noemail' => 'لا يوجد عنوان بريد الكتروني'
        ),

    'campainglog_target_type_dom' =>
        array(
            'Contacts' => 'جهات إتصال',
            'Users' => 'مستخدمين',
            'Prospects' => 'أهداف',
            'Leads' => 'جهات مهتمة',
            'Accounts' => 'حسابات',
        ),
    'merge_operators_dom' => array(
        'like' => 'يحتوي',
        'exact' => 'تحديداً',
        'start' => 'يبدأ بـ',
    ),

    'custom_fields_importable_dom' => array(
        'true' => 'نعم',
        'false' => 'لا',
        'required' => 'مطلوب',
    ),

    'Elastic_boost_options' => array(
        '0' => 'معطل',
        '1' => 'تحفيز متدني',
        '2' => 'تحفيز متوسط',
        '3' => 'تحفيز عال',
    ),

    'custom_fields_merge_dup_dom' => array(
        0 => 'معطل',
        1 => 'مفعل',
    ),

    'navigation_paradigms' => array(
        'm' => 'وحدات',
        'gm' => 'وحدات مجمّعة',
    ),


    'projects_priority_options' => array(
        'high' => 'عال',
        'medium' => 'متوسط',
        'low' => 'منخفض',
    ),

    'projects_status_options' => array(
        'notstarted' => 'لم يبدأ',
        'inprogress' => 'جار العمل عليه',
        'completed' => 'مكتمل',
    ),
    // strings to pass to Flash charts
    'chart_strings' => array(
        'expandlegend' => 'توسيع الأسطورة',
        'collapselegend' => 'طي الأسطورة',
        'clickfordrilldown' => 'نقر للتنقيب باتجاه الأسفل',
        'drilldownoptions' => 'خصائص التنقيب باتجاه الأسفل',
        'detailview' => 'مزيد من التفاصيل ...',
        'piechart' => 'مخطط دائري',
        'groupchart' => 'مخطط المجموعة',
        'stackedchart' => 'مخطط مكدس',
        'barchart' => 'مخطط شريطي',
        'horizontalbarchart' => 'مخطط شريط أفقي',
        'linechart' => 'مخطط خطي',
        'noData' => 'البيانات غير متوفرة',
        'print' => 'طباعة',
        'pieWedgeName' => 'الأقسام',
    ),
    'release_status_dom' =>
        array(
            'Active' => 'نشط',
            'Inactive' => 'غير نشط',
        ),
    'email_settings_for_ssl' =>
        array(
            '0' => '',
            '1' => 'SSL',
            '2' => 'TLS',
        ),
    'import_enclosure_options' =>
        array(
            '\'' => 'علامة إقتباس مفردة (\')',
            '"' => 'علامة إقتباس مزدوجة (")',
            '' => 'لا شيئ',
            'other' => 'أخرى:',
        ),
    'import_delimeter_options' =>
        array(
            ',' => ',',
            ';' => ';',
            '\t' => '\t',
            '.' => '.',
            ':' => ':',
            '|' => '|',
            'other' => 'أخرى:',
        ),
    'link_target_dom' =>
        array(
            '_blank' => 'نافذة جديدة',
            '_self' => 'في نفس النافذة',
        ),
    'dashlet_auto_refresh_options' =>
        array(
            '-1' => 'لا تقم بالتحديث التلقائي',
            '30' => 'كل 30 ثانية',
            '60' => 'كل 1 دقيقة',
            '180' => 'كل 3 دقائق',
            '300' => 'كل 5 دقائق',
            '600' => 'كل 10 دقائق',
        ),
    'dashlet_auto_refresh_options_admin' =>
        array(
            '-1' => 'أبداً',
            '30' => 'كل 30 ثانية',
            '60' => 'كل 1 دقيقة',
            '180' => 'كل 3 دقائق',
            '300' => 'كل 5 دقائق',
            '600' => 'كل 10 دقائق',
        ),
    'date_range_search_dom' =>
        array(
            '=' => 'يساوي',
            'not_equal' => 'لا يساوي',
            'greater_than' => 'بعد',
            'less_than' => 'قبل',
            'last_7_days' => 'آخر 7 أيام',
            'next_7_days' => 'الأيام ال 7 القادمة',
            'last_30_days' => 'آخر 30 يوم',
            'next_30_days' => 'الأيام ال 30 القادمة',
            'last_month' => 'آخر شهر',
            'this_month' => 'هذا الشهر',
            'next_month' => 'الشهر القادم',
            'last_year' => 'آخر سنة',
            'this_year' => 'هذه السنة',
            'next_year' => 'السنة القادمة',
            'between' => 'بين',
        ),
    'numeric_range_search_dom' =>
        array(
            '=' => 'يساوي',
            'not_equal' => 'لا يساوي',
            'greater_than' => 'أكبر من',
            'greater_than_equals' => 'أكبر من أو يساوي',
            'less_than' => 'أقل من',
            'less_than_equals' => 'أقل من أو يساوي',
            'between' => 'بين',
        ),
    'lead_conv_activity_opt' =>
        array(
            'copy' => 'نسخ',
            'move' => 'نقل',
            'donothing' => 'لا تفعل شيئ'
        ),
    'salesdoc_doctypes' => array(
        'QT' => 'اقتباس',
        'OR' => 'طلب',
        'IV' => 'فاتورة'
    ),
    'salesdoc_docparties' => array(
        'I' => 'شخصي',
        'B' => 'اعمال'
    ),
    'salesdoc_uoms' => array(
        'm2' => 'm²',
        'PC' => 'PC'
    ),
    'salesdocs_paymentterms' => array(
        '7DN' => '7 أيام صافي',
        '14DN' => '14 يوم صافي',
        '30DN' => '30 يوم صافي',
        '30DN7D3' => '30 يوم صافي, 7 أيام 3%',
        '60DN' => '60 يوم صافي',
        '60DN7D3' => '60 يوم صافي, 7 أيام 3%',
    ),
    'mediatypes_dom' => array(
        1 => 'صورة',
        2 => 'صوت',
        3 => 'فيديو'
    ),
    'workflowftastktypes_dom' => array(
        'task' => 'مهمة',
        'decision' => 'قرار',
        'email' => 'أيميل',
        'system' => 'نظام',
    ),
    'workflowdefinition_status' => array(
        'active' => 'نشط',
        'active_once' => 'نشط (تشغيل مرة واحدة)',
        'active_scheduled' => 'نشط مقرر',
        'active_scheduled_once' => 'نشط مقرر (تشغيل مرة واحدة)',
        'inactive' => 'غير نشط'
    ),
    'workflowdefinition_precondition' => array(
        'a' => 'دائماً',
        'u' => 'عند التحديث',
        'n' => 'عند إدخال جديد'
    ),
    'workflowdefinition_emailtypes' => array(
        '1' => 'المستخدم مخصص إلى المهمة',
        '2' => 'المستخدم مخصص إلى السجل',
        '3' => 'المستخدم أنشأ سجل',
        '4' => 'المدير مخصص للسجل',
        '5' => 'المدير أنشأ سجل',
        '6' => 'عنوان بريد الكتروني',
        '7' => 'روتين النظام'
    ),
    'workflowdefinition_assgintotypes' => array(
        '1' => 'مستخدم',
        '2' => 'مجموعة العمل',
        '3' => 'المستخدم مخصص لعنصر والد ',
        '4' => ' مدير المستخدم مخصص لعنصر والد ',
        '5' => 'روتين النظام'
    ),
    'workflowdefinition_conditionoperators' => array(
        'EQ' => '=',
        'NE' => '≠',
        'GT' => '>',
        'GE' => '≥',
        'LT' => '<',
        'LE' => '≤',
    ),
    'workflowtask_status' => array(
        '5' => 'Scheduled',
        '10' => 'جديد',
        '20' => 'جار العمل عليه',
        '30' => 'مكتمل',
        '40' => 'مغلق من قبل النظام'
    ),
    'page_sizes_dom' => array(
        'A3' => 'A3',
        'A4' => 'A4',
        'A5' => 'A5',
        'A6' => 'A6'
    ),
    'page_orientation_dom' => array(
        'P' => 'طولي',
        'L' => 'عرضي'
    )
);

$app_list_strings['library_type'] = array(
    'Books' => 'كتاب',
    'Music' => 'موسيقى',
    'DVD' => 'دي في دي',
    'Magazines' => 'مجلات'
);
$app_list_strings['project_priority_default'] = 'متوسط';

$app_list_strings['project_priority_options'] = array(
    'High' => 'مرتفع',
    'Medium' => 'متوسط',
    'Low' => 'منخفض',
);

$app_list_strings['kbdocument_status_dom'] = array(
    'Draft' => 'مسودة',
    'Expired' => 'منتهي',
    'In Review' => 'في المراجعة',
    'Published' => 'تم نشره',
);

$app_list_strings['kbadmin_actions_dom'] =
    array(
        '' => '--إجراءات المشرف--',
        'Create New Tag' => 'إنشاء تاغ جديد',
        'Delete Tag' => 'حذف تاغ',
        'Rename Tag' => 'إعادة تسمية التاغ',
        'Move Selected Articles' => 'نقل المقالة المختارة',
        'Apply Tags On Articles' => 'تطبيق التاغ للمقالة',
        'Delete Selected Articles' => 'حذف المقالات المحددة',
    );

$app_list_strings['kbdocument_attachment_option_dom'] =
    array(
        '' => '',
        'some' => 'لديه مرفقات',
        'none' => 'لا شيئ',
        'mime' => 'حدد تنوع Mime',
        'name' => 'حدد إسم',
    );

$app_list_strings['kbdocument_viewing_frequency_dom'] =
    array(
        '' => '',
        'Top_5' => 'أعلى 5',
        'Top_10' => 'أعلى 10',
        'Top_20' => 'أعلى 20',
        'Bot_5' => 'أدنى 5',
        'Bot_10' => 'أدنى 10',
        'Bot_20' => 'أدنى 20',
    );

$app_list_strings['kbdocument_canned_search'] =
    array(
        'all' => 'الكل',
        'added' => 'أضيفت آخر 30 يوم',
        'pending' => 'في انتظار موافقتي',
        'updated' => 'تحديث آخر 30 يوما',
        'faqs' => 'أسئلة وأجوبة',
    );
$app_list_strings['kbdocument_date_filter_options'] =
    array(
        '' => '',
        'on' => 'عند',
        'before' => 'قبل',
        'after' => 'بعد',
        'between_dates' => 'بين',
        'last_7_days' => 'آخر 7 أيام',
        'next_7_days' => '7 أيام القادمة',
        'last_month' => 'آخر شهر',
        'this_month' => 'هذا الشهر',
        'next_month' => 'الشهر القادم',
        'last_30_days' => 'آخر 30 يوما',
        'next_30_days' => '30 يوم القادمة',
        'last_year' => 'آخر سنة',
        'this_year' => 'هذه السنة',
        'next_year' => 'السنة القادمة',
        'isnull' => 'باطل',
    );

$app_list_strings['countries_dom'] = array(
    '' => '',
    'ABU DHABI' => 'أبو ظبي',
    'ADEN' => 'عدن',
    'AFGHANISTAN' => 'أفغانستان',
    'ALBANIA' => 'ألبانيا',
    'ALGERIA' => 'الجزائر',
    'AMERICAN SAMOA' => 'ساموا الأمريكية',
    'ANDORRA' => 'أندورا',
    'ANGOLA' => 'أنجولا',
    'ANTARCTICA' => 'القارة القطبية الجنوبية',
    'ANTIGUA' => 'أنتيغوا',
    'ARGENTINA' => 'الأرجنتين',
    'ARMENIA' => 'أرمينيا',
    'ARUBA' => 'عروبا',
    'AUSTRALIA' => 'أستراليا',
    'AUSTRIA' => 'النمسا',
    'AZERBAIJAN' => 'أذربيجان',
    'BAHAMAS' => 'الباهاما',
    'BAHRAIN' => 'البحرين',
    'BANGLADESH' => 'بنغلادش',
    'BARBADOS' => 'باربادوس',
    'BELARUS' => 'روسيا البيضاء',
    'BELGIUM' => 'بلجيكا',
    'BELIZE' => 'بليز',
    'BENIN' => 'بنين',
    'BERMUDA' => 'برمودا',
    'BHUTAN' => 'بهوتان',
    'BOLIVIA' => 'بوليفيا',
    'BOSNIA' => 'البوسنة',
    'BOTSWANA' => 'بوتسوانا',
    'BOUVET ISLAND' => 'جزيرة بوفيت',
    'BRAZIL' => 'البرازيل',
    'BRITISH ANTARCTICA TERRITORY' => 'أراضي أنتاركتيكا البريطانية',
    'BRITISH INDIAN OCEAN TERRITORY' => 'إقليم المحيط البريطاني الهندي',
    'BRITISH VIRGIN ISLANDS' => 'جزر فيرجن البريطانية',
    'BRITISH WEST INDIES' => 'جزر الهند الغربية البريطانية',
    'BRUNEI' => 'BRUNEI',
    'BULGARIA' => 'بلغاريا',
    'BURKINA FASO' => 'بوركينا فاسو',
    'BURUNDI' => 'بوروندي',
    'CAMBODIA' => 'كمبوديا',
    'CAMEROON' => 'الكاميرون',
    'CANADA' => 'كندا',
    'CANAL ZONE' => 'منطقة القناة',
    'CANARY ISLAND' => 'جزر الكناري',
    'CAPE VERDI ISLANDS' => 'جزر الرأس الأخضر',
    'CAYMAN ISLANDS' => 'جزر كايمان',
    'CEVLON' => 'سيفلون',
    'CHAD' => 'التشاد',
    'CHANNEL ISLAND UK' => 'قناة الجزيرة المملكة المتحدة',
    'CHILE' => 'تشيلي',
    'CHINA' => 'الصين',
    'CHRISTMAS ISLAND' => 'جزيرة عيد الميلاد',
    'COCOS (KEELING) ISLAND' => 'جزيرة كوكوس (كيلينغ)',
    'COLOMBIA' => 'كولومبيا',
    'COMORO ISLANDS' => 'جزر القمر',
    'CONGO' => 'الكونغو',
    'CONGO KINSHASA' => 'الكونغو كينشاسا',
    'COOK ISLANDS' => 'جزر كوك',
    'COSTA RICA' => 'كوستا ريكا',
    'CROATIA' => 'كرواتيا',
    'CUBA' => 'كوبا',
    'CURACAO' => 'كوراكاو',
    'CYPRUS' => 'قبرص',
    'CZECH REPUBLIC' => 'جمهورية التشيك',
    'DAHOMEY' => 'داهومي',
    'DENMARK' => 'الدانمارك',
    'DJIBOUTI' => 'جيبوتي',
    'DOMINICA' => 'الدومينيكان',
    'DOMINICAN REPUBLIC' => 'جمهورية الدومنيكان',
    'DUBAI' => 'دبي',
    'ECUADOR' => 'الإكوادور',
    'EGYPT' => 'مصر',
    'EL SALVADOR' => 'السلفادور',
    'EQUATORIAL GUINEA' => 'غينيا الإستوائية',
    'ESTONIA' => 'استونيا',
    'ETHIOPIA' => 'أثيوبيا',
    'FAEROE ISLANDS' => 'جزر صناعية',
    'FALKLAND ISLANDS' => 'جزر فوكلاند',
    'FIJI' => 'فيجي',
    'FINLAND' => 'فنلندا',
    'FRANCE' => 'فرنسا',
    'FRENCH GUIANA' => 'غيانا الفرنسية',
    'FRENCH POLYNESIA' => 'بولينيزيا الفرنسية',
    'GABON' => 'الجابون',
    'GAMBIA' => 'غامبيا',
    'GEORGIA' => 'جورجيا',
    'GERMANY' => 'ألمانيا',
    'GHANA' => 'غانا',
    'GIBRALTAR' => 'جبل طارق',
    'GREECE' => 'اليونان',
    'GREENLAND' => 'الأرض الخضراء',
    'GUADELOUPE' => 'جوادلوب',
    'GUAM' => 'جوام',
    'GUATEMALA' => 'غواتيمالا',
    'GUINEA' => 'غينيا',
    'GUYANA' => 'غيانا',
    'HAITI' => 'هايتي',
    'HONDURAS' => 'هندوراس',
    'HONG KONG' => 'هونج كونج',
    'HUNGARY' => 'هنغاريا',
    'ICELAND' => 'أيسلندا',
    'IFNI' => 'إفني',
    'INDIA' => 'الهند',
    'INDONESIA' => 'أندونيسيا',
    'IRAN' => 'إيران',
    'IRAQ' => 'العراق',
    'IRELAND' => 'إيرلندا',
    'ISRAEL' => 'أسرائيل',
    'ITALY' => 'إيطاليا',
    'IVORY COAST' => 'ساحل العاج',
    'JAMAICA' => 'جامايكا',
    'JAPAN' => 'اليابان',
    'JORDAN' => 'الأردن',
    'KAZAKHSTAN' => 'كازغستان',
    'KENYA' => 'كينيا',
    'KOREA' => 'كوريا',
    'KOREA, SOUTH' => 'كوريا الجنوبية',
    'KUWAIT' => 'الكويت',
    'KYRGYZSTAN' => 'قيرغيزستان',
    'LAOS' => 'لاوس',
    'LATVIA' => 'لاتفيا',
    'LEBANON' => 'لبنان',
    'LEEWARD ISLANDS' => 'جزر يوارد',
    'LESOTHO' => 'ليسوتو',
    'LIBYA' => 'ليبيا',
    'LIECHTENSTEIN' => 'ليختنشتاين',
    'LITHUANIA' => 'ليثوانيا',
    'LUXEMBOURG' => 'لوكسمبورغ',
    'MACAO' => 'ماكاو',
    'MACEDONIA' => 'مقدونيا',
    'MADAGASCAR' => 'مدغشقر',
    'MALAWI' => 'ملاوي',
    'MALAYSIA' => 'ماليزيا',
    'MALDIVES' => 'جزر المالديف',
    'MALI' => 'مالي',
    'MALTA' => 'مالطة',
    'MARTINIQUE' => 'مارتينيك',
    'MAURITANIA' => 'موريتانيا',
    'MAURITIUS' => 'موريشيوس',
    'MELANESIA' => 'ميلانيزيا',
    'MEXICO' => 'المكسيك',
    'MOLDOVIA' => 'مولدوفيا',
    'MONACO' => 'موناكو',
    'MONGOLIA' => 'منغوليا',
    'MOROCCO' => 'المغرب',
    'MOZAMBIQUE' => 'موزمبيق',
    'MYANAMAR' => 'مينامار',
    'NAMIBIA' => 'ناميبيا',
    'NEPAL' => 'نيبال',
    'NETHERLANDS' => 'هولندا',
    'NETHERLANDS ANTILLES' => 'جزر الأنتيل الهولندية',
    'NETHERLANDS ANTILLES NEUTRAL ZONE' => 'هولندا الأنتيل منطقة محايدة',
    'NEW CALADONIA' => 'كاليدونيا الجديدة',
    'NEW HEBRIDES' => 'هيبريدس الجديدة',
    'NEW ZEALAND' => 'نيوزيلندا',
    'NICARAGUA' => 'نيكاراغوا',
    'NIGER' => 'النيجر',
    'NIGERIA' => 'نيجيريا',
    'NORFOLK ISLAND' => 'جزيرة نورفولك',
    'NORWAY' => 'النرويج',
    'OMAN' => 'سلطنة عمان',
    'OTHER' => 'أخرى',
    'PACIFIC ISLAND' => 'جزيره في المحيط الهادي',
    'PAKISTAN' => 'باكستان',
    'PANAMA' => 'بنما',
    'PAPUA NEW GUINEA' => 'بابوا غينيا الجديدة',
    'PARAGUAY' => 'باراغواي',
    'PERU' => 'بيرو',
    'PHILIPPINES' => 'الفلبين',
    'POLAND' => 'بولندا',
    'PORTUGAL' => 'البرتغال',
    'PORTUGUESE TIMOR' => 'تيمور البرتغال',
    'PUERTO RICO' => 'بورتو ريكو',
    'QATAR' => 'دولة قطر',
    'REPUBLIC OF BELARUS' => 'جمهورية بيلاروسيا',
    'REPUBLIC OF SOUTH AFRICA' => 'جمهورية جنوب أفريقيا',
    'REUNION' => 'ريونيون',
    'ROMANIA' => 'رومانيا',
    'RUSSIA' => 'روسيا',
    'RWANDA' => 'رواندا',
    'RYUKYU ISLANDS' => 'جزر ريوكيو',
    'SABAH' => 'صباه',
    'SAN MARINO' => 'سان مارينو',
    'SAUDI ARABIA' => 'المملكة العربية السعودية',
    'SENEGAL' => 'السنغال',
    'SERBIA' => 'صربيا',
    'SEYCHELLES' => 'سيشيل',
    'SIERRA LEONE' => 'سيرا ليون',
    'SINGAPORE' => 'سنغافورة',
    'SLOVAKIA' => 'سلوفاكيا',
    'SLOVENIA' => 'سلوفينيا',
    'SOMALILIAND' => 'أرض الصومال',
    'SOUTH AFRICA' => 'جنوب أفريقيا',
    'SOUTH YEMEN' => 'جنوب اليمن',
    'SPAIN' => 'أسبانيا',
    'SPANISH SAHARA' => 'صحارى الأسبانية',
    'SRI LANKA' => 'سيريلانكا',
    'ST. KITTS AND NEVIS' => 'سانت كيتس ونيفيس',
    'ST. LUCIA' => 'سانت لوقا',
    'SUDAN' => 'السودان',
    'SURINAM' => 'سورينام',
    'SW AFRICA' => 'جنوب غرب أفريقيا',
    'SWAZILAND' => 'سوازيلاند',
    'SWEDEN' => 'السويد',
    'SWITZERLAND' => 'سويسرا',
    'SYRIA' => 'سوريا',
    'TAIWAN' => 'تايوان',
    'TAJIKISTAN' => 'طاجغستان',
    'TANZANIA' => 'تنزانيا',
    'THAILAND' => 'تايلاند',
    'TONGA' => 'تونغا',
    'TRINIDAD' => 'ترينيداد',
    'TUNISIA' => 'تونس',
    'TURKEY' => 'تركيا',
    'UGANDA' => 'أوغندا',
    'UKRAINE' => 'أوكرانيا',
    'UNITED ARAB EMIRATES' => 'الإمارات العربية المتحدة',
    'UNITED KINGDOM' => 'المملكة المتحدة',
    'UPPER VOLTA' => 'فولتا العليا',
    'URUGUAY' => 'أوروغواي',
    'US PACIFIC ISLAND' => 'جزر المحيط الهادئ الامريكية',
    'US VIRGIN ISLANDS' => 'جزر فيرجن الأمريكية',
    'USA' => 'الولايات المتحدة الأمريكية',
    'UZBEKISTAN' => 'أوزبكستان',
    'VANUATU' => 'فانواتو',
    'VATICAN CITY' => 'مدينة الفاتيكان',
    'VENEZUELA' => 'فنزويلا',
    'VIETNAM' => 'فيتنام',
    'WAKE ISLAND' => 'جزيرة ويك',
    'WEST INDIES' => 'جزر الهند الغربية',
    'WESTERN SAHARA' => 'صحارى الغربية',
    'YEMEN' => 'اليمن',
    'ZAIRE' => 'زائير',
    'ZAMBIA' => 'زامبيا',
    'ZIMBABWE' => 'زيمبابوي',
);

$app_list_strings['charset_dom'] = array(
    'BIG-5' => 'BIG-5 (تايوان وهونج كونج)',
    'CP1251' => 'CP1251 (MS السيريلية)',
    'CP1252' => 'CP1252 (MS أوروبا الغربية والولايات المتحدة)',
    'EUC-CN' => 'EUC-CN (GB2312 الصينية المبسطة)',
    'EUC-JP' => 'EUC-JP (يونكس يابانية)',
    'EUC-KR' => 'EUC-KR (الكورية)',
    'EUC-TW' => 'EUC-TW (تايواني)',
    'ISO-2022-JP' => 'ISO-2022-JP (اليابانية)',
    'ISO-2022-KR' => 'ISO-2022-KR (الكورية)',
    'ISO-8859-1' => 'ISO-8859-1 (أوروبا الغربية والولايات المتحدة)',
    'ISO-8859-2' => 'ISO-8859-2 (وسط وشرق أوروبا)',
    'ISO-8859-3' => "ISO-8859-3 (اللاتينية 3)",
    'ISO-8859-4' => 'ISO-8859-4 (اللاتينية 4)',
    'ISO-8859-5' => 'ISO-8859-5 (السيريلية)',
    'ISO-8859-6' => 'ISO-8859-6 (العربية)',
    'ISO-8859-7' => 'ISO-8859-7 (اليونانية)',
    'ISO-8859-8' => 'ISO-8859-8 (العبرية)',
    'ISO-8859-9' => 'ISO-8859-9 (اللاتينية 5)',
    'ISO-8859-10' => 'ISO-8859-10 (اللاتينية 6)',
    'ISO-8859-13' => 'ISO-8859-13 (اللاتينية 7)',
    'ISO-8859-14' => 'ISO-8859-14 (اللاتينية 8)',
    'ISO-8859-15' => 'ISO-8859-15 (اللاتينية 9)',
    'KOI8-R' => 'KOI8-R (السيريلية الروسية)',
    'KOI8-U' => 'KOI8-U (السيريلية الأوكرانية)',
    'SJIS' => 'SJIS (MS اليابانية)',
    'UTF-8' => 'UTF-8',
);

$app_list_strings['timezone_dom'] = array(

    'Africa/Algiers' => 'أفريقيا / الجزائر',
    'Africa/Luanda' => 'إفريقيا / لواندا',
    'Africa/Porto-Novo' => 'إفريقيا / بورتو نوفو',
    'Africa/Gaborone' => 'أفريقيا / غابورون',
    'Africa/Ouagadougou' => 'إفريقيا / واغادوغو',
    'Africa/Bujumbura' => 'إفريقيا / بوجمبورا',
    'Africa/Douala' => 'إفريقيا / دوالا',
    'Atlantic/Cape_Verde' => 'الأطلسي / الرأس الأخضر',
    'Africa/Bangui' => 'أفريقيا / بانغي',
    'Africa/Ndjamena' => 'إفريقيا / نجامينا',
    'Indian/Comoro' => 'الهندي / جزر القمر',
    'Africa/Kinshasa' => 'إفريقيا / كينشاسا',
    'Africa/Lubumbashi' => 'إفريقيا / لوبومباشي',
    'Africa/Brazzaville' => 'إفريقيا / برازافيل',
    'Africa/Abidjan' => 'إفريقيا / أبيدجان',
    'Africa/Djibouti' => 'إفريقيا / جيبوتي',
    'Africa/Cairo' => 'إفريقيا / القاهرة',
    'Africa/Malabo' => 'إفريقيا / مالابو',
    'Africa/Asmera' => 'إفريقيا / أسمره',
    'Africa/Addis_Ababa' => 'أفريقيا / أديس أبابا',
    'Africa/Libreville' => 'إفريقيا / ليبرفيل',
    'Africa/Banjul' => 'إفريقيا / بانجول',
    'Africa/Accra' => 'إفريقيا / أكرا',
    'Africa/Conakry' => 'إفريقيا / كوناكري',
    'Africa/Bissau' => 'إفريقيا / بيساو',
    'Africa/Nairobi' => 'أفريقيا / نيروبي',
    'Africa/Maseru' => 'إفريقيا / ماسيرو',
    'Africa/Monrovia' => 'إفريقيا / مونروفيا',
    'Africa/Tripoli' => 'إفريقيا / طرابلس',
    'Indian/Antananarivo' => 'الهندي / أنتاناناريفو',
    'Africa/Blantyre' => 'أفريقيا / بلانتير',
    'Africa/Bamako' => 'إفريقيا / باماكو',
    'Africa/Nouakchott' => 'إفريقيا / نواكشوط',
    'Indian/Mauritius' => 'الهندي / موريشيوس',
    'Indian/Mayotte' => 'الهندي / مايوت',
    'Africa/Casablanca' => 'إفريقيا / الدار البيضاء',
    'Africa/El_Aaiun' => 'أفريقيا / العيون',
    'Africa/Maputo' => 'إفريقيا / مابوتو',
    'Africa/Windhoek' => 'إفريقيا / ويندهوك',
    'Africa/Niamey' => 'إفريقيا / نيامي',
    'Africa/Lagos' => 'أفريقيا / لاغوس',
    'Indian/Reunion' => 'الهندي / ريونيون',
    'Africa/Kigali' => 'أفريقيا / كيغالي',
    'Atlantic/St_Helena' => 'الأطلسي / سانت هيلانة',
    'Africa/Sao_Tome' => 'أفريقيا / ساو تومي',
    'Africa/Dakar' => 'إفريقيا / داكار',
    'Indian/Mahe' => 'الهندي / ماهي',
    'Africa/Freetown' => 'إفريقيا / فريتاون',
    'Africa/Mogadishu' => 'أفريقيا / مقديشو',
    'Africa/Johannesburg' => 'افريقيا / جوهانسبرغ',
    'Africa/Khartoum' => 'إفريقيا / الخرطوم',
    'Africa/Mbabane' => 'إفريقيا / مباباني',
    'Africa/Dar_es_Salaam' => 'أفريقيا / دار السلام',
    'Africa/Lome' => 'إفريقيا / لومي',
    'Africa/Tunis' => 'أفريقيا / تونس',
    'Africa/Kampala' => 'إفريقيا / كمبالا',
    'Africa/Lusaka' => 'إفريقيا / لوساكا',
    'Africa/Harare' => 'أفريقيا / هراري',
    'Antarctica/Casey' => 'أنتاركتيكا / كاسي',
    'Antarctica/Davis' => 'أنتاركتيكا / ديفيس',
    'Antarctica/Mawson' => 'أنتاركتيكا / ماوسون',
    'Indian/Kerguelen' => 'الهندية / كيرغولن',
    'Antarctica/DumontDUrville' => 'أنتاركتيكا / دومونت دورفيل',
    'Antarctica/Syowa' => 'أنتاركتيكا / سيووا',
    'Antarctica/Vostok' => 'أنتاركتيكا / فوستوك',
    'Antarctica/Rothera' => 'أنتاركتيكا / روثيرا',
    'Antarctica/Palmer' => 'أنتاركتيكا / بالمر',
    'Antarctica/McMurdo' => 'أنتاركتيكا / ماكموردو',
    'Asia/Kabul' => 'آسيا / كابول',
    'Asia/Yerevan' => 'آسيا / يريفان',
    'Asia/Baku' => 'آسيا / باكو',
    'Asia/Bahrain' => 'آسيا / البحرين',
    'Asia/Dhaka' => 'آسيا / دكا',
    'Asia/Thimphu' => 'آسيا / تيمفو',
    'Indian/Chagos' => 'الهندي / تشاغوس',
    'Asia/Brunei' => 'آسيا / بروناي',
    'Asia/Rangoon' => 'آسيا / رانغون',
    'Asia/Phnom_Penh' => 'آسيا / بنوم بنه',
    'Asia/Beijing' => 'آسيا / بكين',
    'Asia/Harbin' => 'آسيا / هاربين',
    'Asia/Shanghai' => 'آسيا / شنغهاي',
    'Asia/Chongqing' => 'آسيا / تشونغتشينغ',
    'Asia/Urumqi' => 'آسيا / أورومتشي',
    'Asia/Kashgar' => 'آسيا / كاشغر',
    'Asia/Hong_Kong' => 'آسيا / هونغ كونغ',
    'Asia/Taipei' => 'آسيا / تايبيه',
    'Asia/Macau' => 'آسيا / ماكاو',
    'Asia/Nicosia' => 'آسيا / نيقوسيا',
    'Asia/Tbilisi' => 'آسيا / تبليسي',
    'Asia/Dili' => 'آسيا / ديلي',
    'Asia/Calcutta' => 'آسيا / كالكوتا',
    'Asia/Jakarta' => 'آسيا / جاكرتا',
    'Asia/Pontianak' => 'آسيا / بونتياناك',
    'Asia/Makassar' => 'آسيا / ماكاسار',
    'Asia/Jayapura' => 'آسيا / جايابورا',
    'Asia/Tehran' => 'آسيا / طهران',
    'Asia/Baghdad' => 'آسيا / بغداد',
    'Asia/Jerusalem' => 'آسيا / القدس',
    'Asia/Tokyo' => 'آسيا / طوكيو',
    'Asia/Amman' => 'آسيا / عمان',
    'Asia/Almaty' => 'آسيا / ألماتي',
    'Asia/Qyzylorda' => 'آسيا / كيزيلورودا',
    'Asia/Aqtobe' => 'آسيا / أكتوب',
    'Asia/Aqtau' => 'آسيا / أقتاو',
    'Asia/Oral' => 'آسيا / أورال',
    'Asia/Bishkek' => 'آسيا / بيشكيك',
    'Asia/Seoul' => 'آسيا / سيول',
    'Asia/Pyongyang' => 'آسيا / بيونغ يانغ',
    'Asia/Kuwait' => 'آسيا / الكويت',
    'Asia/Vientiane' => 'آسيا / فينتيان',
    'Asia/Beirut' => 'آسيا / بيروت',
    'Asia/Kuala_Lumpur' => 'آسيا / كوالالمبور',
    'Asia/Kuching' => 'آسيا / كوتشينغ',
    'Indian/Maldives' => 'الهند / جزر المالديف',
    'Asia/Hovd' => 'آسيا / هوود',
    'Asia/Ulaanbaatar' => 'آسيا / أولان باتور',
    'Asia/Choibalsan' => 'آسيا / شويبالسان',
    'Asia/Katmandu' => 'آسيا / كاتماندو',
    'Asia/Muscat' => 'آسيا / مسقط',
    'Asia/Karachi' => 'آسيا / كراتشي',
    'Asia/Gaza' => 'آسيا / غزة',
    'Asia/Manila' => 'آسيا / مانيلا',
    'Asia/Qatar' => 'آسيا / قطر',
    'Asia/Riyadh' => 'آسيا / الرياض',
    'Asia/Singapore' => 'آسيا / سنغافورة',
    'Asia/Colombo' => 'آسيا / كولومبو',
    'Asia/Damascus' => 'آسيا / دمشق',
    'Asia/Dushanbe' => 'آسيا / دوشانبي',
    'Asia/Bangkok' => 'آسيا / بانكوك',
    'Asia/Ashgabat' => 'آسيا / عشق أباد',
    'Asia/Dubai' => 'آسيا / دبي',
    'Asia/Samarkand' => 'آسيا / سمرقند',
    'Asia/Tashkent' => 'آسيا / طشقند',
    'Asia/Saigon' => 'آسيا / سايغون',
    'Asia/Aden' => 'آسيا / عدن',
    'Australia/Darwin' => 'أستراليا / داروين',
    'Australia/Perth' => 'أستراليا / بيرث',
    'Australia/Brisbane' => 'أستراليا / بريسبان',
    'Australia/Lindeman' => 'أستراليا / ليندمان',
    'Australia/Adelaide' => 'أستراليا / أديليد',
    'Australia/Hobart' => 'أستراليا / هوبارت',
    'Australia/Currie' => 'أستراليا / كوري',
    'Australia/Melbourne' => 'استراليا / ملبورن',
    'Australia/Sydney' => 'أستراليا / سيدني',
    'Australia/Broken_Hill' => 'أستراليا / بروكن هيل',
    'Indian/Christmas' => 'الهندي / عيد الميلاد',
    'Pacific/Rarotonga' => 'المحيط الهادئ / راروتونغا',
    'Indian/Cocos' => 'الهندي / كوكوس',
    'Pacific/Fiji' => 'المحيط الهادئ / فيجي',
    'Pacific/Gambier' => 'المحيط الهادئ / جامبير',
    'Pacific/Marquesas' => 'المحيط الهادئ / الماركيز',
    'Pacific/Tahiti' => 'المحيط الهادئ / تاهيتي',
    'Pacific/Guam' => 'المحيط الهادئ / غوام',
    'Pacific/Tarawa' => 'المحيط الهادئ / تاراوا',
    'Pacific/Enderbury' => 'المحيط الهادئ / إندربري',
    'Pacific/Kiritimati' => 'المحيط الهادئ / كيريتيماتي',
    'Pacific/Saipan' => 'المحيط الهادئ / سايبان',
    'Pacific/Majuro' => 'المحيط الهادئ / ماجورو',
    'Pacific/Kwajalein' => 'المحيط الهادئ / كواجالين',
    'Pacific/Truk' => 'المحيط الهادئ / تراك',
    'Pacific/Ponape' => 'المحيط الهادئ / بوناب',
    'Pacific/Kosrae' => 'المحيط الهادئ / كوسراي',
    'Pacific/Nauru' => 'المحيط الهادئ / ناورو',
    'Pacific/Noumea' => 'المحيط الهادئ / نوميا',
    'Pacific/Auckland' => 'المحيط الهادئ / أوكلاند',
    'Pacific/Chatham' => 'المحيط الهادئ / تشاتام',
    'Pacific/Niue' => 'المحيط الهادئ / نيوي',
    'Pacific/Norfolk' => 'المحيط الهادئ / نورفولك',
    'Pacific/Palau' => 'المحيط الهادئ / بالاو',
    'Pacific/Port_Moresby' => 'المحيط الهادئ / بور مورسبي',
    'Pacific/Pitcairn' => 'المحيط الهادئ / بيتكيرن',
    'Pacific/Pago_Pago' => 'المحيط الهادئ / باغو باغو',
    'Pacific/Apia' => 'المحيط الهادئ / آبيا',
    'Pacific/Guadalcanal' => 'المحيط الهادئ / القنال',
    'Pacific/Fakaofo' => 'المحيط الهادئ / فاكاوفو',
    'Pacific/Tongatapu' => 'المحيط الهادئ / تونجاتابو',
    'Pacific/Funafuti' => 'المحيط الهادئ / فونافوتي',
    'Pacific/Johnston' => 'المحيط الهادئ / جونستون',
    'Pacific/Midway' => 'المحيط الهادئ / ميدواي',
    'Pacific/Wake' => 'المحيط الهادئ / ويك',
    'Pacific/Efate' => 'المحيط الهادئ / إيفات',
    'Pacific/Wallis' => 'المحيط الهادئ / واليس',
    'Europe/London' => 'أوروبا / لندن',
    'Europe/Dublin' => 'أوروبا / دبلن',
    'WET' => 'أوروبا الغربية',
    'CET' => 'أوروبا الوسطى',
    'MET' => 'أوروبا الوسطى',
    'EET' => 'أوروبا الشرقية',
    'Europe/Tirane' => 'أوروبا / تيرانا',
    'Europe/Andorra' => 'أوروبا / أندورا',
    'Europe/Vienna' => 'أوروبا / فيينا',
    'Europe/Minsk' => 'أوروبا / مينسك',
    'Europe/Brussels' => 'أوروبا / بروكسل',
    'Europe/Sofia' => 'أوروبا / صوفيا',
    'Europe/Prague' => 'أوروبا / براغ',
    'Europe/Copenhagen' => 'أوروبا / كوبنهاجن',
    'Atlantic/Faeroe' => 'الأطلسي / فارو',
    'America/Danmarkshavn' => 'أمريكا / دانماركشافن',
    'America/Scoresbysund' => 'أمريكا / سكورسبيسوند',
    'America/Godthab' => 'أمريكا / غودتاب',
    'America/Thule' => 'أمريكا / ثول',
    'Europe/Tallinn' => 'أوروبا / تالين',
    'Europe/Helsinki' => 'أوروبا / هلسنكي',
    'Europe/Paris' => 'أوروبا / باريس',
    'Europe/Berlin' => 'أوروبا / برلين',
    'Europe/Gibraltar' => 'أوروبا / جبل طارق',
    'Europe/Athens' => 'أوروبا / أثينا',
    'Europe/Budapest' => 'أوروبا / بودابست',
    'Atlantic/Reykjavik' => 'الأطلسي / ريكيافيك',
    'Europe/Rome' => 'أوروبا / روما',
    'Europe/Riga' => 'أوروبا / ريغا',
    'Europe/Vaduz' => 'أوروبا / فادوز',
    'Europe/Vilnius' => 'أوروبا / فيلنيوس',
    'Europe/Luxembourg' => 'أوروبا / لوكسمبورغ',
    'Europe/Malta' => 'أوروبا / مالطا',
    'Europe/Chisinau' => 'أوروبا / كيشيناو',
    'Europe/Monaco' => 'أوروبا / موناكو',
    'Europe/Amsterdam' => 'أوروبا / أمستردام',
    'Europe/Oslo' => 'أوروبا / أوسلو',
    'Europe/Warsaw' => 'أوروبا / وارسو',
    'Europe/Lisbon' => 'أوروبا / لشبونة',
    'Atlantic/Azores' => 'الأطلسي / الأزور',
    'Atlantic/Madeira' => 'الأطلسي / ماديرا',
    'Europe/Bucharest' => 'أوروبا / بوخارست',
    'Europe/Kaliningrad' => 'أوروبا / كالينينغراد',
    'Europe/Moscow' => 'أوروبا / موسكو',
    'Europe/Samara' => 'أوروبا / سمارة',
    'Asia/Yekaterinburg' => 'آسيا / يكاترينبورغ',
    'Asia/Omsk' => 'آسيا / أومسك',
    'Asia/Novosibirsk' => 'آسيا / نوفوسيبيرسك',
    'Asia/Krasnoyarsk' => 'آسيا / كراسنويارسك',
    'Asia/Irkutsk' => 'آسيا / إيركوتسك',
    'Asia/Yakutsk' => 'آسيا / ياكوتسك',
    'Asia/Vladivostok' => 'آسيا / فلاديفوستوك',
    'Asia/Sakhalin' => 'آسيا / ساخالين',
    'Asia/Magadan' => 'آسيا / ماغادان',
    'Asia/Kamchatka' => 'آسيا / كامتشاتكا',
    'Asia/Anadyr' => 'آسيا / أنادير',
    'Europe/Belgrade' => 'أوروبا / بلغراد',
    'Europe/Madrid' => 'أوروبا / مدريد',
    'Africa/Ceuta' => 'إفريقيا / سبتة',
    'Atlantic/Canary' => 'الأطلسي / الكناري',
    'Europe/Stockholm' => 'أوروبا / ستوكهولم',
    'Europe/Zurich' => 'أوروبا / زيوريخ',
    'Europe/Istanbul' => 'أوروبا / اسطنبول',
    'Europe/Kiev' => 'أوروبا / كييف',
    'Europe/Uzhgorod' => 'أوروبا / أوزجورود',
    'Europe/Zaporozhye' => 'أوروبا / زابوروجي',
    'Europe/Simferopol' => 'أوروبا / سيمفيروبول',
    'America/New_York' => 'أمريكا / نيويورك',
    'America/Chicago' => 'أمريكا / شيكاغو',
    'America/North_Dakota/Center' => 'أمريكا / داكوتا الشمالية / الوسط',
    'America/Denver' => 'أمريكا / دنفر',
    'America/Los_Angeles' => 'أمريكا / لوس أنجلس',
    'America/Juneau' => 'أمريكا / جونو',
    'America/Yakutat' => 'أمريكا / من Yakutat',
    'America/Anchorage' => 'أمريكا / مرسى',
    'America/Nome' => 'أمريكا / نومي',
    'America/Adak' => 'أمريكا / أداك',
    'Pacific/Honolulu' => 'المحيط الهادئ / هونولولو',
    'America/Phoenix' => 'أمريكا / فينكس',
    'America/Boise' => 'America/Boise',
    'America/Indiana/Indianapolis' => 'أمريكا / إنديانا / إنديانابوليس',
    'America/Indiana/Marengo' => 'أمريكا / إنديانا / مارينغو',
    'America/Indiana/Knox' => 'أمريكا / إنديانا / نوكس',
    'America/Indiana/Vevay' => 'أمريكا / إنديانا / فيفاي',
    'America/Kentucky/Louisville' => 'أمريكا / كنتاكي / لويزفيل',
    'America/Kentucky/Monticello' => 'أمريكا / كنتاكي / مونتيسيلو',
    'America/Detroit' => 'أمريكا / ديترويت',
    'America/Menominee' => 'أمريكا / مينوميني',
    'America/St_Johns' => 'أمريكا / سانت جونز',
    'America/Goose_Bay' => 'أمريكا / خليج الأوز',
    'America/Halifax' => 'أمريكا / هاليفاكس',
    'America/Glace_Bay' => 'أمريكا / خليج جلاس',
    'America/Montreal' => 'أمريكا / مونتريال',
    'America/Toronto' => 'أمريكا / تورونتو',
    'America/Thunder_Bay' => 'أمريكا / خليج الرعد',
    'America/Nipigon' => 'أمريكا / نيبجون',
    'America/Rainy_River' => 'أمريكا / نهر الراني',
    'America/Winnipeg' => 'أمريكا / وينيبيغ',
    'America/Regina' => 'أمريكا / ريجينا',
    'America/Swift_Current' => 'أمريكا / سويفت الحالية',
    'America/Edmonton' => 'أمريكا / أدمنتون',
    'America/Vancouver' => 'أمريكا / فانكوفر',
    'America/Dawson_Creek' => 'أمريكا / داوسون كريك',
    'America/Pangnirtung' => 'أمريكا / بانجنيرتونج',
    'America/Iqaluit' => 'أمريكا / إكالويت',
    'America/Coral_Harbour' => 'أمريكا / كورال هاربور',
    'America/Rankin_Inlet' => 'أمريكا / رانكين مدخل',
    'America/Cambridge_Bay' => 'أمريكا / كامبردج باي',
    'America/Yellowknife' => 'أمريكا / يلونايف',
    'America/Inuvik' => 'أمريكا / إنوفيك',
    'America/Whitehorse' => 'أمريكا / وايت هورس',
    'America/Dawson' => 'أمريكا / داوسون',
    'America/Cancun' => 'أمريكا / كانكون',
    'America/Merida' => 'أمريكا / ميريدا',
    'America/Monterrey' => 'أمريكا / مونتيري',
    'America/Mexico_City' => 'أمريكا / Mexico_City',
    'America/Chihuahua' => 'أمريكا / تشيهواهوا',
    'America/Hermosillo' => 'أمريكا / هيرموسيلو',
    'America/Mazatlan' => 'أمريكا / مازاتلان',
    'America/Tijuana' => 'أمريكا / تيخوانا',
    'America/Anguilla' => 'أمريكا / أنغيلا',
    'America/Antigua' => 'أمريكا / أنتيغوا',
    'America/Nassau' => 'أمريكا / ناسو',
    'America/Barbados' => 'أمريكا / بربادوس',
    'America/Belize' => 'أمريكا / بليز',
    'Atlantic/Bermuda' => 'الأطلسي / برمودا',
    'America/Cayman' => 'أمريكا / كايمان',
    'America/Costa_Rica' => 'أمريكا / كوستاريكا',
    'America/Havana' => 'أمريكا / هافانا',
    'America/Dominica' => 'أمريكا / دومينيكا',
    'America/Santo_Domingo' => 'أمريكا / سانتو دومينغو',
    'America/El_Salvador' => 'أمريكا / السلفادور',
    'America/Grenada' => 'أمريكا / غرينادا',
    'America/Guadeloupe' => 'أمريكا / غوادلوب',
    'America/Guatemala' => 'أمريكا / غواتيمالا',
    'America/Port-au-Prince' => 'أمريكا / بورت أو برنس',
    'America/Tegucigalpa' => 'أمريكا / تيغوسيغالبا',
    'America/Jamaica' => 'أمريكا / جامايكا',
    'America/Martinique' => 'أمريكا / مارتينيك',
    'America/Montserrat' => 'أمريكا / مونتسيرات',
    'America/Managua' => 'أمريكا / ماناغوا',
    'America/Panama' => 'أمريكا / بنما',
    'America/Puerto_Rico' => 'أمريكا / بورتوريكو',
    'America/St_Kitts' => 'أمريكا / سانت كيتس',
    'America/St_Lucia' => 'أمريكا / سانت لوسيا',
    'America/Miquelon' => 'أمريكا / ميكلون',
    'America/St_Vincent' => 'أمريكا / سانت فنسنت',
    'America/Grand_Turk' => 'أمريكا / غراند ترك',
    'America/Tortola' => 'أمريكا / تورتولا',
    'America/St_Thomas' => 'أمريكا / سانت توماس',
    'America/Argentina/Buenos_Aires' => 'أمريكا / الأرجنتين / بيونس آيرس',
    'America/Argentina/Cordoba' => 'أمريكا / الأرجنتين / كوردوبا',
    'America/Argentina/Tucuman' => 'أمريكا / الأرجنتين / توكومان',
    'America/Argentina/La_Rioja' => 'أمريكا / الأرجنتين / لاريوخا',
    'America/Argentina/San_Juan' => 'أمريكا / الأرجنتين / سان خوان',
    'America/Argentina/Jujuy' => 'أمريكا / الأرجنتين / خوخوي',
    'America/Argentina/Catamarca' => 'أمريكا / الأرجنتين / كاتاماركا',
    'America/Argentina/Mendoza' => 'أمريكا / الأرجنتين / مندوزا',
    'America/Argentina/Rio_Gallegos' => 'أمريكا / الأرجنتين / ريو غاليغوس',
    'America/Argentina/Ushuaia' => 'أمريكا / الأرجنتين / أوشوايا',
    'America/Aruba' => 'أمريكا / أروبا',
    'America/La_Paz' => 'أمريكا / لا باز',
    'America/Noronha' => 'أمريكا / نورونها',
    'America/Belem' => 'أمريكا / بيليم',
    'America/Fortaleza' => 'أمريكا / فورتاليزا',
    'America/Recife' => 'أمريكا / ريسيفي',
    'America/Araguaina' => 'أمريكا / أراغوينا',
    'America/Maceio' => 'أمريكا / ماسيو',
    'America/Bahia' => 'أمريكا / باهيا',
    'America/Sao_Paulo' => 'ؤأمريكا / باهيا',
    'America/Campo_Grande' => 'أمريكا / كامبو غراندي',
    'America/Cuiaba' => 'أمريكا / كويابا',
    'America/Porto_Velho' => 'أمريكا / بورتو فيلهو',
    'America/Boa_Vista' => 'أمريكا / بوا فيستا',
    'America/Manaus' => 'أمريكا / ماناوس',
    'America/Eirunepe' => 'أمريكا / من Eirunepe',
    'America/Rio_Branco' => 'أمريكا / ريو برانكو',
    'America/Santiago' => 'أمريكا / سانتياغو',
    'Pacific/Easter' => 'المحيط الهادئ / عيد الفصح',
    'America/Bogota' => 'أمريكا / بوغوتا',
    'America/Curacao' => 'أمريكا / كوراكاو',
    'America/Guayaquil' => 'أمريكا / غواياكيل',
    'Pacific/Galapagos' => 'المحيط الهادي / غالاباغوس',
    'Atlantic/Stanley' => 'الأطلسي / ستانلي',
    'America/Cayenne' => 'أمريكا / كاين',
    'America/Guyana' => 'أمريكا / غيانا',
    'America/Asuncion' => 'أمريكا / أسونسيون',
    'America/Lima' => 'أمريكا / ليما',
    'Atlantic/South_Georgia' => 'المحيط الأطلسي / جورجيا الجنوبية',
    'America/Paramaribo' => 'أمريكا / باراماريبو',
    'America/Port_of_Spain' => 'أمريكا / بورت أوف سبين',
    'America/Montevideo' => 'أمريكا / مونتيفيديو',
    'America/Caracas' => 'أمريكا / كاراكاس',
);

$app_list_strings['eapm_list'] = array(
    'Spice' => 'Spice',
    'WebEx' => 'WebEx',
    'GoToMeeting' => 'GoToMeeting',
    'IBMSmartCloud' => 'IBM SmartCloud',
    'Google' => 'غوغل',
    'Box' => 'Box.net',
    'Facebook' => 'فيسبوك',
    'Twitter' => 'تويتر',
);
$app_list_strings['eapm_list_import'] = array(
    'Google' => 'جهات إتصال غوغل',
);
$app_list_strings['eapm_list_documents'] = array(
    'Google' => 'غوغل درايف',
);
$app_list_strings['token_status'] = array(
    1 => 'الطلب',
    2 => 'الوصول',
    3 => 'غير صالح',
);

$app_list_strings['emailTemplates_type_list'] = array(
    '' => '',
    'campaign' => 'حملة إعلانية',
    'email' => 'أيميل',
    'bean2mail' => 'أرسل المدخل عبر البريد الألكتروني',
    'sendCredentials' => 'إرسال بيانات الاعتماد',
    'sendTokenForNewPassword' => 'إرسال الرمز المميز ، عند فقدان كلمة المرور'
);

$app_list_strings ['emailTemplates_type_list_campaigns'] = array(
    '' => '',
    'campaign' => 'حملة إعلانية',
);

$app_list_strings ['emailTemplates_type_list_no_workflow'] = array(
    '' => '',
    'campaign' => 'حملة إعلانية',
    'email' => 'إيميل',
);
$app_strings ['documentation'] = array(
    'LBL_DOCS' => 'الوثائق',
    'ULT' => '02_Spice النهائي',
    'ENT' => '02_Spice مؤسساتي',
    'CORP' => '03_Spice التشاركي',
    'PRO' => '04_Spice الأحترافي',
    'COM' => '05_Spice نسخة المجتمع'
);

/** KReporter **/
$app_list_strings['kreportstatus'] = array(
    '1' => 'مسودة',
    '2' => 'إصدار محدود',
    '3' => 'إصدار عام'
);

/** Proposals */
$app_list_strings['proposalstatus_dom'] = array(
    '1' => 'مسودة',
    '2' => 'تم إرساله',
    '3' => 'تمت الموافقة عليه',
    '4' => 'تم رفضه',
);

$app_list_strings['crstatus_dom'] = array(
    '0' => 'تم إنشائه',
    '1' => 'جار العمل عليه',
    '2' => 'جار تجربته',
    '3' => 'تم إكماله',
    '4' => 'تم إلغائه/تأجيله'
);

$app_list_strings['crtype_dom'] = array(
    '0' => 'خلل',
    '1' => 'طلب خاصية جديدة',
    '2' => 'طلب تغيير',
    '3' => 'إصلاح فوري'
);

$app_list_strings['rpstatus_dom'] = array(
    '0' => 'تم إنشائه',
    '1' => 'جار العمل عليه',
    '2' => 'تم إكماله',
    '3' => 'جار تجربته',
    '4' => 'تم توصيله',
    '5' => 'تم جلبه',
    '6' => 'تم نشره',
    '7' => 'تم إصداره'
);

$app_list_strings['rptype_dom'] = array(
    '0' => 'تصحيح',
    '1' => 'باقة خاصية',
    '2' => 'إصدار',
    '3' => 'باقة سوفتوير',
    '4' => 'مستورد'
);

$app_list_strings['systemdeploymentpackage_repair_dom'] = array(
    'repairDatabase' => 'إصلاح قاعدة البيانات',
    'rebuildExtensions' => 'إعادة بناء ملحقات',
    'clearTpls' => 'تنظيف القوالب',
    'clearJsFiles' => 'تنظيف ملفات جافا سكربت',
    'clearDashlets' => 'تنظيف عناصر لوحة القيادة',
    'clearSugarFeedCache' => 'تنظيف تغذية ذاكرة التخزين المؤقت لـ Sugar',
    'clearThemeCache' => 'تنظيف ذاكرة التخزين المؤقت للثيمات',
    'clearVardefs' => 'تنظيف ملفات تعريف الحقول',
    'clearJsLangFiles' => 'تنظيف ملفات جافا سكربت للغة',
    'rebuildAuditTables' => 'إعادة بناء جداول التدقيق',
    'clearSearchCache' => 'تنظيف تغذية ذاكرة التخزين المؤقت للبحث',
    'clearAll' => 'تنظيف الكل',
);
include('include/modules.php');
//include('modules/Administration/');
$app_list_strings['systemdeploymentpackage_repair_modules_dom'] = array(
    translate('LBL_ALL_MODULES', 'Administration') => translate('LBL_ALL_MODULES', 'الإدارة')
);
foreach ($beanList as $module => $bean) {
    $app_list_strings['systemdeploymentpackage_repair_modules_dom'][$module] = $module;
}

$app_list_strings['mwstatus_dom'] = array(
    'planned' => 'تم التخطيط له',
    'active' => 'نشط',
    'completed' => 'مكتمل'
);

$app_list_strings['kdeploymentsystems_type_dom'] = array(
    "repo" => "مستودع البرمجيات",
    "ext" => "خارجي",
    "dev" => "تطوير",
    "test" => "تجربة",
    "qc" => "جودة",
    "prod" => "إنتاج"
);

//EventRegistrations module
$app_list_strings['eventregistration_status_dom'] = array(
    'interested' => 'غير متاح',
    'tentative' => 'مؤقت',
    'registered' => 'مسجل',
    'unregistered' => 'غير مسجل',
    'attended' => 'تم حضوره',
    'notattended' => 'لم يتم حضوره'
);

//ProjectWBSs module
$app_list_strings['wbs_status_dom'] = array(
    '0' => 'تم إنشائه',
    '1' => 'بدأ به',
    '2' => 'تم إكماله'
);
//Projectactivities
$app_list_strings['projects_activity_types_dom'] = array(
    'consulting' => 'استشارات',
    'dev' => 'تطوير',
    'support' => 'دعم'
);
$app_list_strings['projects_activity_levels_dom'] = array(
    'standard' => 'إفتراضي',
    'senior' => 'متقدّم',
);
//Projectmilestones
$app_list_strings['projects_milestone_status_dom'] = array(
    'not startet' => 'إفتراضي',
    'senior' => 'متقدّم',
);
$app_list_strings['projects_activity_status_dom'] = array(
    'created' => 'تم إنشائه',
    'billed' => 'مفوتر',
);

//ProductAttributes
$app_list_strings['productattributedatatypes_dom'] = array(
    'di' => 'قائمة منسدلة',
    'f' => 'مربع إختيار',
    'n' => 'رقمي',
    's' => 'إختيار متعدد',
    'vc' => 'نصّي'
);
$app_list_strings['productattribute_usage_dom'] = array(
    'required' => 'مطلوب',
    'optional' => 'إختياري',
    'none' => 'غير مدخل',
    'hidden' => 'مخفي'
);

//AccountCCDetails
$app_list_strings['abccategory_dom'] = array(
    '' => '',
    'A' => 'أ',
    'B' => 'ب',
    'C' => 'س',
);

$app_list_strings['logicoperators_dom'] = array(
    'and' => 'و',
    'or' => 'أو',
);

$app_list_strings['comparators_dom'] = array(
    'equal' => 'يساوي',
    'unequal' => 'لا يساوي',
    'greater' => 'أكبر',
    'greaterequal' => 'أكبر ويساوي',
    'less' => 'أقل',
    'lessequal' => 'أقل ويساوي',
    'contain' => 'يحتوي',
);

$app_list_strings['mailboxes_imap_pop3_protocol_dom'] = array(
    'imap' => 'IMAP',
    'pop3' => 'POP3',
);

$app_list_strings['mailboxes_imap_pop3_encryption_dom'] = array(
    'ssl_enable' => 'تفعيل SSL',
    'ssl_disable' => 'تعطيل SSL'
);

$app_list_strings['mailboxes_smtp_encryption_dom'] = [
    'none' => 'غير مشفر',
    'ssl' => 'SSL',
    'tls' => 'TLS/STARTTLS',
];

$app_strings = array_merge($app_strings, $addAppStrings);

if (file_exists('modules/ServiceOrders/ServiceOrder.php')) {
    $app_list_strings['serviceorder_status_dom'] = array(
        'new' => 'جديد',
        'planned' => 'تم التخطيط له',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغى',
        'signed' => 'تم توقيعه',
    );
    $app_list_strings['parent_type_display']['ServiceOrders'] = 'عقود الخدمة';
    $app_list_strings['record_type_display']['ServiceOrders'] = 'عقد الخدمة';
    $app_list_strings['record_type_display_notes']['ServiceOrders'] = 'عقود الخدمة';
}
if (file_exists('modules/ServiceTickets/ServiceTicket.php')) {
    $app_list_strings['serviceticket_status_dom'] = array(
        'New' => 'جديد',
        'In Process' => 'جار العمل عليه',
        'Assigned' => 'تم تعيينه',
        'Closed' => 'مغلق',
        'Pending Input' => 'في انتظار المدخل',
        'Rejected' => 'مرفوض',
        'Duplicate' => 'مكرر',
    );
    $app_list_strings['serviceticket_class_dom'] = array(
        'P1' => 'عال',
        'P2' => 'متوسط',
        'P3' => 'منخفض',
    );
    $app_list_strings['serviceticket_resaction_dom'] = array(
        '' => '',
        'credit' => 'إصدار مذكرة الائتمان',
        'replace' => 'إرسال بديل',
        'return' => 'إعادة البضائع'
    );
    $app_list_strings['parent_type_display']['ServiceTickets'] = 'تذاكر الخدمة';
    $app_list_strings['record_type_display']['ServiceTickets'] = 'تذاكر الخدمة';
    $app_list_strings['record_type_display_notes']['ServiceTickets'] = 'تذاكر الخدمة';

}
if (file_exists('modules/ServiceFeedbacks/ServiceFeedback.php')) {
    $app_list_strings['service_satisfaction_scale_dom'] = array(
        1 => '1 - غير راض',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5 - سعيد',
    );
    $app_list_strings['servicefeedback_status_dom'] = array(
        'sent' => 'تم إرساله',
        'completed' => 'مكتمل',
    );
    $app_list_strings['servicefeedback_parent_type_display'] = array(
        'ServiceTickets' => 'تذاكر الخدمة',
        'ServiceOrders' => 'طلبات الخدمة',
        'ServiceCalls' => 'إتصالات الخدمة',
    );
    $app_list_strings['record_type_display'] = array(
        'ServiceTickets' => 'تذاكر الخدمة',
        'ServiceOrders' => 'طلبات الخدمة',
        'ServiceCalls' => 'إتصالات الخدمة',
    );
}

$app_list_strings['mailboxes_transport_dom'] = [
    'imap'     => 'IMAP/SMTP',
    'mailgun'  => 'Mailgun',
    'sendgrid' => 'Sendgrid',
    'twillio'  => 'Twillio',
    'a1'       => 'A1 SMS Gateway',
];

$app_list_strings['mailboxes_outbound_comm'] = [
    'no'         => 'غير متاح',
    'single'     => 'فقط أيميلات مفردة',
    'mass'       => 'أيميلات مفردة وكمية',
    'single_sms' => 'الرسائل النصية الفردية فقط (SMS)',
    'mass_sms'   => 'الرسائل النصية الفردية والكمية (SMS)',
];

include('include/SpiceBeanGuides/SpiceBeanGuideLanguage.php');

$app_list_strings['output_template_types'] = [
    ''      => '',
    'email' => 'أيميل',
    'pdf'   => 'بي دي أف',
];

$app_list_strings['languages'] = [
    ''   => '',
    'de' => 'ألماني',
    'en' => 'إنكليزي',
    'ar' => 'عربي',
];


$app_list_strings['spiceaclobjects_types_dom'] = [
    '0' => 'إفتراضي',
    '1' => 'تقييد (الكل)',
    '2' => 'استبعاد (الكل)',
    '3' => 'نشاط محدود'
];

$app_list_strings['deploymentrelease_status_dom'] = [
    ''         => '',
    'planned'  => 'مخطط له',
    'released' => 'تم إصداره',
    'canceled' => 'ملغى',
];

$app_list_strings['product_status_dom'] = [
    'draft'    => 'مسودة',
    'active'   => 'نشط',
    'inactive' => 'غير نشط',
];

$app_list_strings['textmessage_direction'] = [
    'i' => 'وارد',
    'o' => 'صادر',
];

$app_list_strings['textmessage_delivery_status'] = [
    'draft'  => 'Draft',
    'sent'   => 'Sent',
    'failed' => 'Failed',
];

$app_list_strings['event_status_dom'] = array(
    'planned' => 'مخطط له',
    'active' => 'نشط',
    'canceled' => 'ملغى'
);

$app_list_strings['event_category_dom'] = array(
    'presentations' => 'عروض',
    'seminars' => 'ندوات',
    'conferences' => 'مؤتمرات'
);
