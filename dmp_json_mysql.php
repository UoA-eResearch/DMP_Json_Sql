<?php
//$jsondata = file_get_contents($_FILES['json_file']['name']);
$jsondata = file_get_contents('dmp.json');

if (!isValidJson($jsondata)) {
    die('Not a Valid JSON File');
}
$data = json_decode($jsondata, true);
//parse_json($data);

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
#insert into field table
for ($i=0;$i<=count($field_code);$i++)
{
    $sql1="INSERT INTO tbl_field(field_code, field_name) VALUES('$field_code[$i]]', '$field_name[$i]]')";
    insert_db($sql1);
}
    $sql_id1="SELECT MAX(field_id) FROM tbl_field";
    $field_id=get_id($sql_id1);

//insert into project table
$sql2 = "INSERT INTO tbl_project(project_title, field_id, project_startdate, project_enddate) "
        . "VALUES('$project_title', '$field_id', '$project_startDate', '$project_endDate')";
insert_db($sql2);
$sql_getid2 = "SELECT MAX(project_id) FROM tbl_project";
$project_id =  get_id($sql_getid2);

//insert into funder table
for ($i=0;$i<=count($funder_code);$i++)
{
    $sql3 = "INSERT INTO tbl_funder(funder_name, funder_researchofficeid, funder_code) "
        . "VALUES('$funder_name[$i]]', '$researchOfficeID[$i]]', '$funder_code[$i]]')";
    insert_db($sql3);
}
$sql_getid3 = "SELECT MAX(funder_id) FROM tbl_funder";
$funder_id = get_id($sql_getid3);

//insert into Access Control table
for ($i=0;$i<=count($ac_status);$i++)
{
    $sql4 = "INSERT INTO tbl_accesscontrol(ac_status, ac_details, ac_releasedate, ac_complianceprocess) "
        . "VALUES('$ac_status[$i]', '$ac_details[$i]', '$ac_releaseDate[$i]', '$ac_complianceProcess[$i]')";
    insert_db($sql4);
} 
$sql_getid4 = "SELECT MAX(ac_id) FROM tbl_accesscontrol";
$ac_id = get_id($sql_getid4);

//insert into document table
for ($i=0;$i<=count($doc_shortname);$i++)
{
    $sql5 = "INSERT INTO tbl_document(doc_shortname, doc_summary, doc_link) "
        . "VALUES('$doc_shortname[$i]', '$doc_summary[$i]', '$doc_link[$i]')";
    insert_db($sql5);
} 
$sql_getid5 = "SELECT MAX(doc_id) FROM tbl_document";
$doc_id = get_id($sql_getid5);

//insert into retention table
for ($i=0;$i<=count($retention_type);$i++)
{
    $sql6 = "INSERT INTO tbl_retention(retention_type, retention_untildate) "
        . "VALUES('$retention_type[$i]', '$retention_untildate[$i]')";
    insert_db($sql6);
}
$sql_getid6 = "SELECT MAX(retention_id) FROM tbl_retention";
$retention_id= get_id($sql_getid6);

//insert into license table
for ($i=0;$i<=count($license_name);$i++)
{
    $sql7 = "INSERT INTO tbl_license(license_name, license_logo) "
        . "VALUES('$license_name[$i]', '$license_logo[$i]')";
    insert_db($sql7);
}
$sql_getid7 = "SELECT MAX(license_id) FROM tbl_license";
$license_id= get_id($sql_getid7);

//insert into policy requiremnt table
for ($i=0;$i<=count($policyreq_controllingBody);$i++)
{
    $sql8 = "INSERT INTO tbl_policyreq(policyreq_controllingbody, policyreq_relevanttext) "
        . "VALUES('$policyreq_controllingBody[$i]', '$policyreq_relevantText[$i]')";
    insert_db($sql8);
}
$sql_getid8 = "SELECT MAX(policyreq_id) FROM tbl_policyreq";
$policyreq_id= get_id($sql_getid8);

//insert into issues table
for ($i=0;$i<=count($issues_type);$i++)
{
    $sql9 = "INSERT INTO tbl_issues(issues_type, issues_description, policyreq_id, issues_managementProcess) "
        . "VALUES('$issues_type[$i]', '$issues_description[$i]', '$policyreq_id[$i]', '$issues_managementProcess[$i]')";
    insert_db($sql9);
}
$sql_getid9 = "SELECT MAX(issues_id) FROM tbl_issues";
$issues_id= get_id($sql_getid9);

//insert into contributor table
for ($i=0;$i<=count($contrib_firstname);$i++)
{
$sql10 = "INSERT INTO tbl_contributor(contrib_firstname, contrib_lastname, contrib_affiliation, "
        . " contrib_email, contrib_username, contrib_orcid) "
        . "VALUES('$contrib_firstname[$i]', '$contrib_lastname[$i]', '$contrib_affiliation[$i]',"
        . "'$contrib_email[$i]','$contrib_username[$i]','$contrib_orcid[$i]')";
    insert_db($sql10);
}
$sql_getid10 = "SELECT MAX(contrib_id) FROM tbl_contributor";
$contrib_id= get_id($sql_getid10);

#insert into role table
for ($i=0;$i<=count($role);$i++)
{
    $sql11 = "INSERT INTO tbl_role(role_name) VALUES('$role[$i]')";
    insert_db($sql11);
}
$sql_getid11 = "SELECT MAX(role_id) FROM tbl_role";
$role_id= get_id($sql_getid11);

//insert into dataasset table
for ($i=0;$i<=count($da_shortname);$i++)
{
    $sql12 = "INSERT INTO tbl_dataasset(da_shortname, da_description, da_collectionprocess, da_organisationprocess, "
        . " da_storageprocess, da_metadatareq, da_copyrightowner, da_publicationprocess,"
        . " da_archiving, da_datacontact, da_requiredresources) "
        . "VALUES('$da_shortname[$i]', '$da_description[$i]', '$da_collectionProcess[$i]', '$da_organisationProcess[$i]',"
        . "'$da_storageProcess[$i]','$da_metadataRequirements[$i]','$da_copyrightOwner[$i]', '$publicationProcess[$i]',"
        . "'$archiving[$i]','$dataContact[$i]','$requiredResources[$i]')";
    insert_db($sql12);
    $sql_getid12 = "SELECT MAX(da_id) FROM tbl_dataasset";
    $da_id[$i]=  get_id($sql_getid12);
}




//insert into dataasset_link table ****
for ($i=0;$i<=count($da_id);$i++)
{
    $sql13 = "INSERT INTO tbl_dataassetlink(da_id, ac_id, retention_id, license_id, issues_id, policyreq_id) "
        . "VALUES('$da_id[$i]', '$da_description', '$da_collectionProcess', '$da_organisationProcess',"
        . "'$da_storageProcess','$da_metadataRequirements','$da_copyrightOwner','$ac_id', '$retention_id', '$publicationProcess',"
        . "'$license_id','$archiving','$dataContact','$requiredResources','$issues_id','$policyreq_id')";

}

//insert into dmp table (the main table)
$sql14 = "INSERT INTO tbl_dmp(dmp_createdate, dmp_updatedate, dmp_lastaccessdate, project_id, "
        . " contrib_id, funder_id, dmp_ethics_required, dmp_iwiconsultation_required, doc_id, da_id) "
        . "VALUES('$dmpCreatedDate', '$dmplastUpdateDate', '$dmplastAccessDate', '$project_id',"
        . "'$contrib_id','$funder_id','$ethicsRequired','$iwiConsultationRequired', '$doc_id', '$da_id')";
if (!mysql_query($sql14, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in main dmp table';
//}

mysql_close($con);

function isValidJson($strJson) {
    json_decode($strJson);
    return (json_last_error() === JSON_ERROR_NONE);
}

//header('Location: view_inserted_dmp.php');    