<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$jsondata = file_get_contents($_FILES['json_file']['name']);
$data = json_decode($jsondata, true);

$dmpCreatedDate = $data['dmpCreatedDate'];
$dmplastUpdateDate = $data['lastUpdateDate'];
$dmplastAccessDate = $data['lastAccessDate'];
$project_title = mysql_real_escape_string($data['projectDetails']['title']);
$project_description = mysql_real_escape_string($data['projectDetails']['description']);
$field_code = mysql_real_escape_string($data['projectDetails']['fieldOfResearch']['0']['code']);
$field_name = mysql_real_escape_string($data['projectDetails']['fieldOfResearch']['0']['name']);
$project_startDate = $data['projectDetails']['startDate'];
$project_endDate = $data['projectDetails']['endDate'];

$contrib_firstname = mysql_real_escape_string($data['contributor']['0']['firstname']);
$contrib_lastname = mysql_real_escape_string($data['contributor']['0']['lastname']);
$contrib_role = $data['contributor']['0']['role'];
$contrib_affiliation = mysql_real_escape_string($data['contributor']['0']['affiliation']);
$contrib_email = mysql_real_escape_string($data['contributor']['0']['email']);
$contrib_username = mysql_real_escape_string($data['contributor']['0']['username']);
$contrib_orcid = mysql_real_escape_string($data['contributor']['0']['orcid']);

$funder_name = mysql_real_escape_string($data['funding']['0']['funder']);
$funder_code = mysql_real_escape_string($data['funding']['0']['funderID']);
$researchOfficeID = mysql_real_escape_string($data['funding']['0']['researchOfficeID']);

$ethicsRequired = mysql_real_escape_string($data['ethicsRequired']);
$iwiConsultationRequired = mysql_real_escape_string($data['iwiConsultationRequired']);

$doc_shortname = mysql_real_escape_string($data['document']['0']['shortname']);
$doc_summary = mysql_real_escape_string($data['document']['0']['summary']);
$doc_link = mysql_real_escape_string($data['document']['0']['link']);

$da_shortname = mysql_real_escape_string($data['dataAsset']['0']['shortname']);
$da_description = mysql_real_escape_string($data['dataAsset']['0']['description']);
$da_collectionProcess = mysql_real_escape_string($data['dataAsset']['0']['collectionProcess']);
$da_organisationProcess = mysql_real_escape_string($data['dataAsset']['0']['organisationProcess']);
$da_storageProcess = mysql_real_escape_string($data['dataAsset']['0']['storageProcess']);
$da_metadataRequirements = mysql_real_escape_string($data['dataAsset']['0']['metadataRequirements']);
$da_copyrightOwner = mysql_real_escape_string($data['dataAsset']['0']['copyrightOwner']);

$ac_status = mysql_real_escape_string($data['dataAsset']['0']['accessControl']['status']);
$ac_details = mysql_real_escape_string($data['dataAsset']['0']['accessControl']['details']);
$ac_releaseDate = $data['dataAsset']['0']['accessControl']['releaseDate'];
$ac_complianceProcess = mysql_real_escape_string($data['dataAsset']['0']['accessControl']['complianceProcess']);

$retention_type = mysql_real_escape_string($data['dataAsset']['0']['retention']['retentionType']);
$retention_untildate = $data['dataAsset']['0']['retention']['retainUntil'];
$publicationProcess = mysql_real_escape_string($data['dataAsset']['0']['publicationProcess']);

$license_name = mysql_real_escape_string($data['dataAsset']['0']['license']['name']);
$license_logo = mysql_real_escape_string($data['dataAsset']['0']['license']['logo']);
$archiving = mysql_real_escape_string($data['dataAsset']['0']['archiving']);
$dataContact = mysql_real_escape_string($data['dataAsset']['0']['dataContact']);
$requiredResources = mysql_real_escape_string($data['dataAsset']['0']['requiredResources']);

$issues_type = mysql_real_escape_string($data['dataAsset']['0']['issues']['0']['type']);
$issues_description = mysql_real_escape_string($data['dataAsset']['0']['issues']['0']['description']);
$issues_managementProcess = mysql_real_escape_string($data['dataAsset']['0']['issues']['0']['managementProcess']);

$policyRequirements_id = mysql_real_escape_string($data['dataAsset']['0']['policyRequirements']['0']['id']);
$policyreq_controllingBody = mysql_real_escape_string($data['dataAsset']['0']['policyRequirements']['0']['controllingBody']);
$policyreq_relevantText = mysql_real_escape_string($data['dataAsset']['0']['policyRequirements']['0']['relevantText']);

$result=[$dmpCreatedDate, $project_title, $project_startDate, $project_endDate, $contrib_firstname, $contrib_lastname,
    $contrib_affiliation, $contrib_email, $funder_name, $funder_code, $doc_shortname, $doc_summary, $da_shortname, $da_description,
    $da_collectionProcess, $da_organisationProcess, $da_storageProcess, $da_metadataRequirements, $da_copyrightOwner, $ac_status, $ac_details, $ac_releaseDate,
    $ac_complianceProcess, $retention_type, $retention_untildate, $publicationProcess, $license_name, $license_logo, $archiving,
    $dataContact, $requiredResources, $issues_type, $issues_description, $policyreq_controllingBody, $policyreq_relevantText];

$html= '<!DOCTYPE html>';
$html.='<html>';
$html.='<head>';
$html.='<link rel="stylesheet" href="form_css/style1.css">';
$html.='</head>';
$html.='<body class="table">';

$arr=['DMP Created Date', 'Project Title', 'Project Start Date', 'Project End Date', 'Contributor First Name', 'Last Name', 
    'Affiliation', 'Email', 'Funding Agency', 'Funding ID', 'Document Short Name', 'Summary', 'Type of Data', 'Description', 
    'Data Collection Process', 'Data Organisation Process', 'Data Storage Process', 'Meta Data Requirement', 'Copyright Owner', 'Data Access', 'Data Access Detail', 'Release Date', 
    'Compliance Process', 'Type of Data Retention', 'Data must be retained after submission of thesis or publication of results until', 'Data Publication Process', 'Data Licensing',
    'License Logo', 'The long-term preservation plan for the dataset', 'Research Data Management Contact','Required Resources', 'Data Issues' ,
    'Issues Description', 'Policy Control Body', 'Policy Requirements'];

    $html.='<table font="12" border="1">';
    $html.='<tr><th colspan="2"><h1 class="pen-title">Research Data Management Plan</h1></th></tr>';
if(count($result))
{
    for ($i=0;$i<count($result);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left">'. $arr[$i] . ': ' .'</td>';
        $html.='<td class="pen-title-left">' . $result[$i] .'</td>';
        $html.='</tr>';
    }
    $html.='</table>';
}
$html.='</body></html>';
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream();
exit;