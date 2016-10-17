<?php
require_once 'dompdf/autoload.inc.php';
require_once 'parsejson.php';
use Dompdf\Dompdf;

$jsondata = file_get_contents($_FILES['json_file']['name']);
$data = json_decode($jsondata, true);
$dmpCreatedDate = $data['dmpCreatedDate'];
$dmplastUpdateDate = $data['lastUpdateDate'];
$dmplastAccessDate = $data['lastAccessDate'];

$project_title = mysql_real_escape_string($data['projectDetails']['title']);
$project_description = mysql_real_escape_string($data['projectDetails']['description']);

$fieldOfResearch = $data['projectDetails']['fieldOfResearch'];

if (is_array($fieldOfResearch))
{
    foreach ($fieldOfResearch as $key=>$value)
    {
        $field_code[$key]=$value['code'];
        $field_name[$key]=$value['name'];
    }
}

$project_startDate = $data['projectDetails']['startDate'];
$project_endDate = $data['projectDetails']['endDate'];

$contributor=$data['contributor'];
if (is_array($contributor))
{
    foreach ($contributor as $key=>$value)
    {
        $contrib_firstname[$key]=$value['firstname'];
        $contrib_lastname[$key]=$value['lastname'];
        $contrib_role[$key]=$value['role'];
        if (is_array($contrib_role[$key]))
        {
            foreach ($contrib_role[$key] as $subkey=>$subvalue)
            {
                $subrole[$subkey]=$subvalue;
                $role[$key][]=$subrole[$subkey];
                
                //echo $role[$key];
            }
        }
        $contrib_affiliation[$key]=$value['affiliation'];
        $contrib_email[$key]=$value['email'];
        $contrib_username[$key]=$value['username'];
        $contrib_orcid[$key]=$value['orcid'];
    }
}

$funding = $data['funding'];
if (is_array($funding))
{
    foreach ($funding as $key=>$value)
    {
        $funder_name[$key]=$value['funder'];
        $funder_code[$key]=$value['funderID'];
        $researchOfficeID[$key]=$value['researchOfficeID'];
    }
}

$ethicsRequired = $data['ethicsRequired'];
$iwiConsultationRequired = $data['iwiConsultationRequired'];

$document = $data['document'];
if (is_array($document))
{
    foreach ($document as $key=>$value)
    {
        $doc_shortname[$key]=$value['shortname'];
        $doc_summary[$key]=$value['summary'];
        $doc_link[$key]=$value['link'];
    }
}

$dataAsset = $data['dataAsset'];
if (is_array($dataAsset))
{
    foreach ($dataAsset as $key=>$value)
    {
        $da_shortname[$key]=$value['shortname'];
        $da_description[$key]=$value['description'];
        $da_collectionProcess[$key]=$value['collectionProcess'];
        $da_organisationProcess[$key]=$value['organisationProcess'];
        $da_storageProcess[$key]=$value['storageProcess'];
        $da_metadataRequirements[$key]=$value['metadataRequirements'];
        $da_copyrightOwner[$key]=$value['copyrightOwner'];
        $da_accessControl[$key]=$value['accessControl'];

        if (is_array($da_accessControl[$key]))
        {
            $ac_status[$key]=$da_accessControl[$key]['status'];
            $ac_details[$key]=$da_accessControl[$key]['details'];
            $ac_releaseDate[$key]=$da_accessControl[$key]['releaseDate'];
            $ac_complianceProcess[$key]=$da_accessControl[$key]['complianceProcess'];
        }
        
        $da_retention[$key] = $value['retention'];
        if (is_array($da_retention[$key]))
        {
            $retention_type[$key]=$da_retention[$key]['retentionType'];
            $retention_untildate[$key]=$da_retention[$key]['retainUntil'];
        }
        
        $da_publicationProcess[$key]=$value['publicationProcess'];
        
        $da_license[$key]=$value['license'];
        if (is_array($da_license[$key]))
        {
            $license_name[$key]=$da_license[$key]['name'];
            $license_logo[$key]=$da_license[$key]['logo'];
        }
        
        $da_archiving[$key]=$value['archiving'];
        $da_dataContact[$key]=$value['dataContact'];
        $da_requiredResources[$key]=$value['requiredResources'];
        $da_issues[$key]=$value['issues'];
        if (is_array($da_issues[$key]))
        {
            foreach ($da_issues[$key] as $subkey=>$subvalue)
            {
                $issues_type[$subkey]=$subvalue['type'];
                $issues_description[$subkey]=$subvalue['description'];
                $issues_managementProcess[$subkey]=$subvalue['managementProcess'];
            }
        }
        $policyRequirements[$key]=$value['policyRequirements'];
        if (is_array($policyRequirements[$key]))
        {
            foreach ($policyRequirements[$key] as $subkey=>$subvalue)
            {
                $policyRequirements_id[$subkey]=$subvalue['id'];
                $policyreq_controllingBody[$subkey]=$subvalue['controllingBody'];
                $policyreq_relevantText[$subkey]=$subvalue['relevantText'];
            }
        }
    }
}
$html= '<!DOCTYPE html>';
$html.='<html>';
$html.='<head>';
$html.='<link rel="stylesheet" href="form_css/style1.css">';
$html.='</head>';
$html.='<body class="body">';
//$result=[$dmpCreatedDate, $dmplastUpdateDate, $dmplastAccessDate, $project_title, $project_description,
//    $field_code, $field_name, $project_startDate, $project_endDate, $contrib_firstname, $contrib_lastname, $role,
//    $contrib_affiliation, $contrib_email, $contrib_username, $contrib_orcid, $funder_name, $funder_code, 
//    $researchOfficeID, $ethicsRequired, $iwiConsultationRequired, $doc_shortname, $doc_summary, $doc_link, 
//    $da_shortname, $da_description, $da_collectionProcess, $da_organisationProcess, $da_storageProcess, 
//    $da_metadataRequirements, $da_copyrightOwner, $ac_status, $ac_details, $ac_releaseDate, $ac_complianceProcess, 
//    $retention_type, $retention_untildate, $da_publicationProcess, $license_name, $license_logo, $da_archiving,
//    $da_dataContact, $da_requiredResources, $issues_type, $issues_description, $issues_managementProcess,
//    $policyRequirements_id, $policyreq_controllingBody, $policyreq_relevantText];

$arr1=['Project Title', 'Description'];
$res1=[$project_title, $project_description];
$arr2=['DMP Created Date', 'DMP Last Updated Date', 'Project Start Date', 'Project End Date'];
$res2=[$dmpCreatedDate, $dmplastUpdateDate, $project_startDate, $project_endDate];
$arr3=['Full Name', 'Affiliation', 'Email', 'User Name', 'Role'];
//$res3=[$contrib_firstname, $contrib_lastname, $contrib_affiliation, $contrib_email, $contrib_username, $contrib_orcid];
$arr4=['Funding Agency', 'Fudning ID', 'Research Office ID'];
$res4=[$funder_name, $funder_code, $researchOfficeID];
$arr6=['Ethics Requirement', 'IWI Consultation Requirement'];
$res6=[$ethicsRequired, $iwiConsultationRequired];
$arr7=['Document Short Name', 'Summary', 'Link'];
$res7=[$doc_shortname, $doc_summary, $doc_link];
$arr8=['Data Type', 'Description', 'Data Collection Process', 'Data Organisation Process', 'Data Storage Process', 
    'Meta Data Requirement', 'Copyright Owner', 'Data Publication Process'];
$res8=[$da_shortname, $da_description, $da_collectionProcess, $da_organisationProcess, $da_storageProcess, 
     $da_metadataRequirements, $da_copyrightOwner,$da_publicationProcess];
$arr9=['Data Access', 'Data Access Detail', 'Release Date', 'Compliance Process'];
$res9=[$ac_status, $ac_details, $ac_releaseDate, $ac_complianceProcess];
$arr10=['Type of Data Retention', 'Data must be retained after submission of thesis or publication of results until'];
$res10=[$retention_type, $retention_untildate];
$arr11=['Data Licensing', 'License Logo'];
$res11=[$license_name, $license_logo];
$arr14=['The long-term preservation plan for the dataset', 'Research Data Management Contact','Required Resources'];
$res14=[$da_archiving, $da_dataContact, $da_requiredResources];
$arr12=['Data Issues' ,'Issues Description'];
$res12=[$issues_type, $issues_description, $issues_managementProcess];
$arr13=['Policy ID','Policy Control Body', 'Policy Requirements'];
$res13=[$policyRequirements_id, $policyreq_controllingBody, $policyreq_relevantText];

    $html.='<table class="form-module" font="12" border="1" width="100%">';
    $html.='<tr><th><h1>RESEARCH DATA MANAGEMENT PLAN</h1></th></tr>';
    $html.='<tr><th class="pen-title-left" align="left"><h2>PROJECT</h2></th></tr>';
    $html.='</table>';
    $html.='<table class="form-module" font="12" border="1" width="100%" position="relative">';
    for ($i=0;$i<count($arr1);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b>'. $arr1[$i] . '</b></td>';
        $html.='<td class="pen-title-left">' . $res1[$i] .'</td>';
        $html.='</tr>';
    }
    $html.='<tr class="form-module span"><td class="pen-title-left"><b>Field of Research</b></td>';
    for ($i=0;$i<count($field_code);$i++)
    {
        $html.='<td class="pen-title-left">'. $field_code[$i] . ' - '. $field_name[$i] . '</td>';
    }
    $html.='</tr>';
    for ($i=0;$i<count($arr2);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b>'. $arr2[$i] . '</b></td>';
        $html.='<td class="pen-title-left">' . $res2[$i] .'</td>';
        $html.='</tr>';
    }
    $html.='</table>';
    $html.='<table class="form-module" font="12" border="1" width="100%" position="relative">';
    $html.='<tr><th class="pen-title-left" colspan="5" align="left"><h2>PROJECT CONTRIBUTORS</h2></th></tr>';
    $html.='<tr class="form-module span" align="left">';
    for ($i=0;$i<count($arr3);$i++)
    {
        $html.='<td class="pen-title-left"><b>'. $arr3[$i] . '</b></td>';
    }
        $html.='</tr>';

    for ($i=0;$i<count($contrib_firstname);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $name=$contrib_firstname[$i]. ' ' . $contrib_lastname[$i];   
        $html.='<td class="pen-title-left">' . $name .'</td>';
        $html.='<td class="pen-title-left">' . $contrib_affiliation[$i] .'</td>';
        $html.='<td class="pen-title-left">' . $contrib_email[$i] .'</td>';
        $html.='<td class="pen-title-left">' . $contrib_username[$i] .'</td>';
        //$html.='<td width="16.6" class="pen-title-left">' . $contrib_orcid[$i] .'</td>';
        $html.='<td class="pen-title-left">' . implode(" , ",$role[$i]) .'</td>';     
        $html.='</tr>';
    }
    $html.='</table>';
    $html.='<table class="form-module" font="12" border="1" width="100%" position="relative">';
    $html.='<tr><th class="pen-title-left" colspan="3" align="left"><h2>POLICIES AND GUIDANCE</h2></th></tr>';
    $html.='<tr class="form-module span" align="left">';
    for ($i=0;$i<count($arr13);$i++)
    {
        $html.='<td class="pen-title-left"><b>'. $arr13[$i] . '</b></td>';
    }
        $html.='</tr>';
    for ($i=0;$i<count($policyRequirements_id);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left">'. $policyRequirements_id[$i] . '</td>';
        $html.='<td class="pen-title-left">'. $policyreq_controllingBody[$i] . '</td>';
        $html.='<td class="pen-title-left">'. $policyreq_relevantText[$i] . '</td>';
        $html.='</tr>';
    }
    $html.='<tr><td class="pen-title-left" colspan="3"><h2>FUNDING</h2></td></tr>';
    $html.='<tr class="form-module span" align="left">';
    for ($i=0;$i<count($arr4);$i++)
    {
        $html.='<td class="pen-title-left"><b>'. $arr4[$i] . '</b></td>';
    }
        $html.='</tr>';
    for ($i=0;$i<count($funder_name);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left">'. $funder_name[$i] . '</td>';
        $html.='<td class="pen-title-left">'. $funder_code[$i] . '</td>';
        $html.='<td class="pen-title-left">'. $researchOfficeID[$i] . '</td>';
        $html.='</tr>';
    }
    $html.='<tr><td class="pen-title-left" colspan="3"><h2>ETHICS & PRIVACY</h2></td></tr>';
    for ($i=0;$i<count($arr6);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left">'. $arr6[$i] . '</td>';
        $html.='<td colspan="2" class="pen-title-left">' . $res6[$i] .'</td>';
        $html.='</tr>';
    }
    $html.='<tr class="form-module span" align="left">';
    for ($i=0;$i<count($arr7);$i++)
    {
        $html.='<td class="pen-title-left"><b>'. $arr7[$i] . '</b></td>';
    }
        $html.='</tr>';
    for ($i=0;$i<count($doc_shortname);$i++)
    {
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left">'. $doc_shortname[$i] . '</td>';
        $html.='<td class="pen-title-left">'. $doc_summary[$i] . '</td>';
        $html.='<td class="pen-title-left">'. $doc_link[$i] . '</td>';
        $html.='</tr>';
    }
    $html.='<tr><th class="pen-title-left" colspan="3" align="left" class="form-module toggle tooltip"><h2>DATA ORGANISATION</h2></th></tr>';

    for ($i=0;$i<count($da_shortname);$i++)
    {
        $var=$i+1;
        $html.='<tr bgcolor="#B0E0E6"><th class="pen-title-left" colspan="3" align="left"  class="form-module toggle tooltip"><h3>DATA - '. (string)$var .'</h3></th></tr>';
        
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Type </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_shortname[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Description </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_description[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Collection Process </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_collectionProcess[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Organisation Process </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_organisationProcess[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Storage Process </td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_storageProcess[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Meta Data Requirement </td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_metadataRequirements[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Copyright Owner </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_copyrightOwner[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Publication Process </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_publicationProcess[$i] .'</td>';
        $html.='</tr>';
        
        $html.='<tr><td class="pen-title-left" colspan="3" align="left"><h3>SHARING AND ACCESS CONTROL</h3></td></tr>';
        
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Access </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $ac_status[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Access Detail </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $ac_details[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Release Date </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $ac_releaseDate[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Compliance Process </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $ac_complianceProcess[$i] .'</td>';
        $html.='</tr>';
        
        $html.='<tr><th class="pen-title-left" colspan="3" align="left"><h3>RETENTION & DISPOSAL</h3></th></tr>';
        
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Type of Data Retention </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $retention_type[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data must be retained after submission of thesis or publication of results until </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $retention_untildate[$i] .'</td>';
        $html.='</tr>';
        
        $html.='<tr><th class="pen-title-left" colspan="3" align="left"><h3>DATA PUBLISHING AND DISCOVERY</h3></th></tr>';
        
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Licensing </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $license_name[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> License Logo </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $license_logo[$i] .'</td>';
        $html.='</tr>';
        
        $html.='<tr><th class="pen-title-left" colspan="3" align="left"><h3>DATA PUBLISHING AND DISCOVERY</h3></th></tr>';
        
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> The long-term preservation plan for the data </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_archiving[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Research Data Management Contact </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_dataContact[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Required Resources </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $da_requiredResources[$i] .'</td>';
        $html.='</tr>';
        
        $html.='<tr><th class="pen-title-left" colspan="3" align="left"><h3>DATA ISSUES</h3></th></tr>';

        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Data Issues </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $issues_type[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Issues Description </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $issues_description[$i] .'</td>';
        $html.='</tr>';
        $html.='<tr class="form-module span" align="left">';
        $html.='<td class="pen-title-left"><b> Issues Management Process </b></td>';
        $html.='<td class="pen-title-left" colspan="2">' . $issues_managementProcess[$i] .'</td>';
        $html.='</tr>';
    }

//    for ($i=0;$i<count($arr8);$i++)
//    {
//        $html.='<tr class="form-module span" align="left">';
//        $html.='<td class="pen-title-left"><b>'. $arr8[$i] . '</b></td>';
//        for ($j=0;$j<count($res8[$i]);$j++)
//        {
//            $html.='<td class="pen-title-left">' . $res8[$i][$j] .'</td>';
//        }
//        $html.='</tr>';
//    }
//    $html.='<tr><th colspan="3" align="left"><h3 class="pen-title">SHARING AND ACCESS CONTROL</h3></th></tr>';
//    for ($i=0;$i<count($arr9);$i++)
//    {
//        $html.='<tr class="form-module span" align="left">';
//        $html.='<td class="pen-title-left">'. $arr9[$i] . '</td>';
//        for ($j=0;$j<count($res9[$i]);$j++)
//        {
//            $html.='<td class="pen-title-left">' . $res9[$i][$j] .'</td>';
//        }
//        $html.='</tr>';
//    }
//    $html.='<tr><th colspan="3" align="left"><h3 class="pen-title">RETENTION & DISPOSAL</h3></th></tr>';
//    for ($i=0;$i<count($arr10);$i++)
//    {
//        $html.='<tr class="form-module span" align="left">';
//        $html.='<td class="pen-title-left">'. $arr10[$i] . '</td>';
//        for ($j=0;$j<count($res10[$i]);$j++)
//        {
//            $html.='<td class="pen-title-left">' . $res10[$i][$j] .'</td>';
//        }
//        $html.='</tr>';
//    }
//    $html.='<tr><th colspan="3" align="left"><h3 class="pen-title">DATA PUBLISHING AND DISCOVERY</h3></th></tr>';
//    for ($i=0;$i<count($arr11);$i++)
//    {
//        $html.='<tr class="form-module span" align="left">';
//        $html.='<td class="pen-title-left">'. $arr11[$i] . '</td>';
//        for ($j=0;$j<count($res11[$i]);$j++)
//        {
//            $html.='<td class="pen-title-left">' . $res11[$i][$j] .'</td>';
//        }
//        $html.='</tr>';
//    }
//    $html.='<tr><th colspan="3" align="left"><h3 class="pen-title">LONG-TERM ARCHIVE / PRESERVATION </h3></th></tr>';
//    for ($i=0;$i<count($arr14);$i++)
//    {
//        $html.='<tr class="form-module span" align="left">';
//        $html.='<td class="pen-title-left">'. $arr14[$i] . '</td>';
//        for ($j=0;$j<count($res14[$i]);$j++)
//        {
//            $html.='<td class="pen-title-left">' . $res14[$i][$j] .'</td>';
//        }
//        $html.='</tr>';
//    }
//    $html.='<tr><th colspan="3" align="left"><h3 class="pen-title">DATA ISSUES</h3></th></tr>';
//    for ($i=0;$i<count($arr12);$i++)
//    {
//        $html.='<tr class="form-module span" align="left">';
//        $html.='<td class="pen-title-left">'. $arr12[$i] . '</td>';
//        for ($j=0;$j<count($res12[$i]);$j++)
//        {
//            $html.='<td class="pen-title-left">' . $res12[$i][$j] .'</td>';
//        }
//        $html.='</tr>';
//    }
    $html.='</table>';

$html.='</body></html>';
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream();
exit;