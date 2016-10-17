<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function parse_json($data)
{
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
                $role[$subkey]=$subvalue;
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

$ethicsRequired = mysql_real_escape_string($data['ethicsRequired']);
$iwiConsultationRequired = mysql_real_escape_string($data['iwiConsultationRequired']);

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
$result=[$dmpCreatedDate, $dmplastUpdateDate, $dmplastAccessDate, $project_title, $project_description,
    $field_code, $field_name, $project_startDate, $project_endDate, $contrib_firstname, $contrib_lastname, $role,
    $contrib_affiliation, $contrib_email, $contrib_username, $contrib_orcid, $funder_name, $funder_code, 
    $researchOfficeID, $ethicsRequired, $iwiConsultationRequired, $doc_shortname, $doc_summary, $doc_link, 
    $da_shortname, $da_description, $da_collectionProcess, $da_organisationProcess, $da_storageProcess, 
    $da_metadataRequirements, $da_copyrightOwner, $ac_status, $ac_details, $ac_releaseDate, $ac_complianceProcess, 
    $retention_type, $retention_untildate, $da_publicationProcess, $license_name, $license_logo, $da_archiving,
    $da_dataContact, $da_requiredResources, $issues_type, $issues_description, $issues_managementProcess,
    $policyRequirements_id, $policyreq_controllingBody, $policyreq_relevantText];
return $result;
}