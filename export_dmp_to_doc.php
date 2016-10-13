<?php
require_once 'vsword/VsWord.php'; 

VsWord::autoLoad();

$doc = new VsWord(); 
$parser = new HtmlParser($doc);

$con = mysql_connect('localhost', 'root', '');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("dmp_tool", $con);

/* show View */
$sql = "SELECT * FROM `vw_dmp` \n"
    . "ORDER BY dmp_id DESC\n"
    . "LIMIT 1";

$result = mysql_query($sql) or die('cannot show columns');

$html= '<!DOCTYPE html>';
$html.='<html>';
$html.='<head>';
$html.='<link rel="stylesheet" href="form_css/style1.css">';
$html.='</head>';
$html.='<body class="table">';

$arr=['DMP No.', 'DMP Created Date', 'Project Title', 'Project Start Date', 'Project End Date', 'Contributor First Name', 'Last Name', 'Affiliation', 'Email', 
    'Funding Agency', 'Funding ID', 'Document Short Name', 'Summary', 'Type of Data', 'Description', 'Data Collection Process', 'Data Organisation Process',
    'Data Storage Process', 'Meta Data Requirement', 'Copyright Owner', 'Data Access', 'Data Access Detail', 'Release Date', 'Compliance Process',
    'Type of Data Retention', 'Data must be retained after submission of thesis or publication of results until', 'Data Publication Process', 'Data Licensing',
    'License Logo', 'The long-term preservation plan for the dataset', 'Research Data Management Contact','Required Resources', 'Data Issues' ,
    'Issues Description', 'Policy Control Body', 'Policy Requirements'];

    $html.='<table font="12" border="1">';
    $html.='<tr><th colspan="2"><h1 class="pen-title">Research Data Management Plan</h1></th></tr>';
if(mysql_num_rows($result))
{
    $html.='<tr align="left">';
    $html.='<td colspan="2" bgcolor="#008CBA"><input type="button" onClick=parent.location="index.html" value="Back to Home"></td>';
    $html.='</tr>';
    while($row = mysql_fetch_row($result))
    {
        foreach($row as $key=>$value)
        {
            $html.='<tr class="form-module span" align="left">';
            $html.='<td width="200" class="pen-title-left">'. $arr[$key] . ': ' .'</td>';
            $html.='<td class="pen-title-left">' . $value .'</td>';
            $html.='</tr>';
        }
    }
    $html.='</table>';
}
$html.='</body></html>';
echo $html;
$parser->parse($html);
$doc->saveAs( 'dmp.docx' );
echo 'Exported the selected json file to dmp.docx';