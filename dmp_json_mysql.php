<?php
$con = mysql_connect('localhost', 'root', '');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

mysql_select_db("dmp_tool", $con);

$jsondata = file_get_contents($_FILES['json_file']['name']);
//echo $jsondata;
if (!isValidJson($jsondata)) {
    die('Not a Valid JSON File');
}
$data = json_decode($jsondata, true);

$dmpCreatedDate = $data['dmpCreatedDate'];
$dmplastUpdateDate = $data['lastUpdateDate'];
$dmplastAccessDate = $data['lastAccessDate'];
//$projectDetails = mysql_real_escape_string($data['projectDetails']);
$project_title = mysql_real_escape_string($data['projectDetails']['title']);
$project_description = mysql_real_escape_string($data['projectDetails']['description']);
//$fieldOfResearch = mysql_real_escape_string($data['projectDetails']['fieldOfResearch']);
$field_code = mysql_real_escape_string($data['projectDetails']['fieldOfResearch']['0']['code']);
$field_name = mysql_real_escape_string($data['projectDetails']['fieldOfResearch']['0']['name']);
$project_startDate = $data['projectDetails']['startDate'];
$project_endDate = $data['projectDetails']['endDate'];

//$contributor = mysql_real_escape_string($data['contributor']);
//$contributor_id=$data['contributor']['0']['id'];

$contrib_firstname = mysql_real_escape_string($data['contributor']['0']['firstname']);
$contrib_lastname = mysql_real_escape_string($data['contributor']['0']['lastname']);
$contrib_role = $data['contributor']['0']['role'];
$contrib_affiliation = mysql_real_escape_string($data['contributor']['0']['affiliation']);
$contrib_email = mysql_real_escape_string($data['contributor']['0']['email']);
$contrib_username = mysql_real_escape_string($data['contributor']['0']['username']);
$contrib_orcid = mysql_real_escape_string($data['contributor']['0']['orcid']);

//$funding = mysql_real_escape_string($data['funding']);
$funder_name = mysql_real_escape_string($data['funding']['0']['funder']);
$funder_code = mysql_real_escape_string($data['funding']['0']['funderID']);
$researchOfficeID = mysql_real_escape_string($data['funding']['0']['researchOfficeID']);

$ethicsRequired = mysql_real_escape_string($data['ethicsRequired']);
$iwiConsultationRequired = mysql_real_escape_string($data['iwiConsultationRequired']);

//$document = mysql_real_escape_string($data['document']);
//$document_id=$data['document']['0']['id'];
$doc_shortname = mysql_real_escape_string($data['document']['0']['shortname']);
$doc_summary = mysql_real_escape_string($data['document']['0']['summary']);
$doc_link = mysql_real_escape_string($data['document']['0']['link']);

//$dataAsset = mysql_real_escape_string($data['dataAsset']);
//$dataAsset_id=$data['dataAsset']['0']['id'];
$da_shortname = mysql_real_escape_string($data['dataAsset']['0']['shortname']);
$da_description = mysql_real_escape_string($data['dataAsset']['0']['description']);
$da_collectionProcess = mysql_real_escape_string($data['dataAsset']['0']['collectionProcess']);
$da_organisationProcess = mysql_real_escape_string($data['dataAsset']['0']['organisationProcess']);
$da_storageProcess = mysql_real_escape_string($data['dataAsset']['0']['storageProcess']);
$da_metadataRequirements = mysql_real_escape_string($data['dataAsset']['0']['metadataRequirements']);
$da_copyrightOwner = mysql_real_escape_string($data['dataAsset']['0']['copyrightOwner']);
//$accessControl = mysql_real_escape_string($data['dataAsset']['0']['accessControl']);
$ac_status = mysql_real_escape_string($data['dataAsset']['0']['accessControl']['status']);
$ac_details = mysql_real_escape_string($data['dataAsset']['0']['accessControl']['details']);
$ac_releaseDate = $data['dataAsset']['0']['accessControl']['releaseDate'];
$ac_complianceProcess = mysql_real_escape_string($data['dataAsset']['0']['accessControl']['complianceProcess']);
//$retention = mysql_real_escape_string($data['dataAsset']['0']['retention']);
$retention_type = mysql_real_escape_string($data['dataAsset']['0']['retention']['retentionType']);
$retention_untildate = $data['dataAsset']['0']['retention']['retainUntil'];
$publicationProcess = mysql_real_escape_string($data['dataAsset']['0']['publicationProcess']);
//$license = mysql_real_escape_string($data['dataAsset']['0']['license']);
$license_name = mysql_real_escape_string($data['dataAsset']['0']['license']['name']);
$license_logo = mysql_real_escape_string($data['dataAsset']['0']['license']['logo']);
$archiving = mysql_real_escape_string($data['dataAsset']['0']['archiving']);
$dataContact = mysql_real_escape_string($data['dataAsset']['0']['dataContact']);
$requiredResources = mysql_real_escape_string($data['dataAsset']['0']['requiredResources']);
//$issues = mysql_real_escape_string($data['dataAsset']['0']['issues']);
// $issues_id=$data['dataAsset']['0']['issues']['0']['id'];
$issues_type = mysql_real_escape_string($data['dataAsset']['0']['issues']['0']['type']);
$issues_description = mysql_real_escape_string($data['dataAsset']['0']['issues']['0']['description']);
$issues_managementProcess = mysql_real_escape_string($data['dataAsset']['0']['issues']['0']['managementProcess']);
//$policyRequirements = mysql_real_escape_string($data['dataAsset']['0']['policyRequirements']);
$policyRequirements_id = mysql_real_escape_string($data['dataAsset']['0']['policyRequirements']['0']['id']);
$policyreq_controllingBody = mysql_real_escape_string($data['dataAsset']['0']['policyRequirements']['0']['controllingBody']);
$policyreq_relevantText = mysql_real_escape_string($data['dataAsset']['0']['policyRequirements']['0']['relevantText']);

//insert into field table
$sql1 = "INSERT INTO tbl_field(field_code, field_name) VALUES('$field_code', '$field_name')";
if (!mysql_query($sql1, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in field table';
//}
//Get field Id
$sql_getid1 = "SELECT MAX(field_id) FROM tbl_field";
$result1=mysql_query($sql_getid1, $con);
$field_id =  mysql_fetch_row($result1)[0];
//$field_id=$field_id[0];
//insert into project table
$sql2 = "INSERT INTO tbl_project(project_title, field_id, project_startdate, project_enddate) "
        . "VALUES('$project_title', '$field_id', '$project_startDate', '$project_endDate')";
//echo mysql_query($sql2,$con);
if (!mysql_query($sql2, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in project table';
//}
//Get project Id
$sql_getid2 = "SELECT MAX(project_id) FROM tbl_project";
$result2=mysql_query($sql_getid2, $con);
$project_id =  mysql_fetch_row($result2)[0];
//insert into funder table
$sql3 = "INSERT INTO tbl_funder(funder_name, funder_researchofficeid, funder_code) "
        . "VALUES('$funder_name', '$researchOfficeID', '$funder_code')";
if (!mysql_query($sql3, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in funder table';
//}
//Get Funder Id
$sql_getid3 = "SELECT MAX(funder_id) FROM tbl_funder";
$result3=mysql_query($sql_getid3, $con);
$funder_id =  mysql_fetch_row($result3)[0];
//insert into Access Control table
$sql4 = "INSERT INTO tbl_accesscontrol(ac_status, ac_details, ac_releasedate, ac_complianceprocess) "
        . "VALUES('$ac_status', '$ac_details', '$ac_releaseDate', '$ac_complianceProcess')";
if (!mysql_query($sql4, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in access control table';
//}
//Get accesscontrol Id
$sql_getid4 = "SELECT MAX(ac_id) FROM tbl_accesscontrol";
$result4=mysql_query($sql_getid4, $con);
$ac_id =  mysql_fetch_row($result4)[0];
//insert into document table
$sql5 = "INSERT INTO tbl_document(doc_shortname, doc_summary, doc_link) "
        . "VALUES('$doc_shortname', '$doc_summary', '$doc_link')";
if (!mysql_query($sql5, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in document table';
//}
//Get document Id
$sql_getid5 = "SELECT MAX(doc_id) FROM tbl_document";
$result5=mysql_query($sql_getid5, $con);
$doc_id =  mysql_fetch_row($result5)[0];
//insert into retentioon table
$sql6 = "INSERT INTO tbl_retention(retention_type, retention_untildate) "
        . "VALUES('$retention_type', '$retention_untildate')";
if (!mysql_query($sql6, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in retention table';
//}
//Get retention Id
$sql_getid6 = "SELECT MAX(retention_id) FROM tbl_retention";
$result6=mysql_query($sql_getid6, $con);
$retention_id=  mysql_fetch_row($result6)[0];
//insert into license table
$sql7 = "INSERT INTO tbl_license(license_name, license_logo) "
        . "VALUES('$license_name', '$license_logo')";
if (!mysql_query($sql7, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in license table';
//}
//Get license Id
$sql_getid7 = "SELECT MAX(license_id) FROM tbl_license";
$result7=mysql_query($sql_getid7, $con);
$license_id=  mysql_fetch_row($result7)[0];
//insert into policy requiremnt table
$sql8 = "INSERT INTO tbl_policyreq(policyreq_controllingbody, policyreq_relevanttext) "
        . "VALUES('$policyreq_controllingBody', '$policyreq_relevantText')";
if (!mysql_query($sql8, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in policy requirement table';
//}
//Get policy requirement Id
$sql_getid8 = "SELECT MAX(policyreq_id) FROM tbl_policyreq";
$result8=mysql_query($sql_getid8, $con);
$policyreq_id=  mysql_fetch_row($result8)[0];
//insert into issues table
$sql9 = "INSERT INTO tbl_issues(issues_type, issues_description, policyreq_id, issues_managementProcess) "
        . "VALUES('$issues_type', '$issues_description', '$policyreq_id', '$issues_managementProcess')";
if (!mysql_query($sql9, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in issues table';
//}
//Get issues Id
$sql_getid9 = "SELECT MAX(issues_id) FROM tbl_issues";
$result9=mysql_query($sql_getid9, $con);
$issues_id=  mysql_fetch_row($result9)[0];

//insert into contributor table
$sql10 = "INSERT INTO tbl_contributor(contrib_firstname, contrib_lastname, contrib_affiliation, "
        . " contrib_email, contrib_username, contrib_orcid) "
        . "VALUES('$contrib_firstname', '$contrib_lastname', '$contrib_affiliation',"
        . "'$contrib_email','$contrib_username','$contrib_orcid')";
if (!mysql_query($sql10, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in contributor table';
//}
//Get contributor Id
$sql_getid10 = "SELECT MAX(contrib_id) FROM tbl_contributor";
$result10=mysql_query($sql_getid10, $con);
$contrib_id=  mysql_fetch_row($result10)[0];

foreach ($contrib_role as &$rol_val)
{
    $rol_val=mysql_real_escape_string($rol_val);
    //insert into role table
    $sql11 = "INSERT INTO tbl_role(role_name) VALUES('$rol_val')";
    if (!mysql_query($sql11, $con)) {
        die('Error : ' . mysql_error());
    } 
//    else {
//        echo 'Inserted successfully in role table';
//    }
    //Get role Id
    $sql_getid11 = "SELECT MAX(role_id) FROM tbl_role";
    $result11=mysql_query($sql_getid11, $con);
    $role_id=  mysql_fetch_row($result11)[0];
    //insert into contribrole table
    $sql12 = "INSERT INTO tbl_contribrole(contrib_id, role_id) VALUES('$contrib_id','$role_id')";
    if (!mysql_query($sql12, $con)) {
        die('Error : ' . mysql_error());
    } 
//    else {
//        echo 'Inserted successfully in contribrole table';
//    }
}

//insert into dataasset table
$sql13 = "INSERT INTO tbl_dataasset(da_shortname, da_description, da_collectionprocess, da_organisationprocess, "
        . " da_storageprocess, da_metadatareq, da_copyrightowner, ac_id, retention_id, da_publicationprocess,"
        . "license_id, da_archiving, da_datacontact, da_requiredresources, issues_id, policyreq_id) "
        . "VALUES('$da_shortname', '$da_description', '$da_collectionProcess', '$da_organisationProcess',"
        . "'$da_storageProcess','$da_metadataRequirements','$da_copyrightOwner','$ac_id', '$retention_id', '$publicationProcess',"
        . "'$license_id','$archiving','$dataContact','$requiredResources','$issues_id','$policyreq_id')";

if (!mysql_query($sql13, $con)) {
    die('Error : ' . mysql_error());
} 
//else {
//    echo 'Inserted successfully in data asset table';
//}
//Get data asset Id
$sql_getid13 = "SELECT MAX(da_id) FROM tbl_dataasset";
$result13=mysql_query($sql_getid13, $con);
$da_id=  mysql_fetch_row($result13)[0];
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

header('Location: view_inserted_dmp.php');    
