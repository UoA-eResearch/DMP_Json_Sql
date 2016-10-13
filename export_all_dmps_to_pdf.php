<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
ini_set("memory_limit","64M");
        
$con = mysql_connect('localhost', 'root', '');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("dmp_tool", $con);

/* show View */
$sql = "SELECT * FROM `vw_dmp` \n"
    . "ORDER BY dmp_id DESC\n";

$result = mysql_query($sql) or die('cannot show columns');

$html= '<!DOCTYPE html>';
$html.='<html>';
$html.='<head>';
$html.='<link rel="stylesheet" href="form_css/style1.css">';
$html.='</head>';
$html.='<body  class="form-module">';

$arr=['DMP No.', 'DMP Created Date', 'Project Title', 'Project Start Date', 'Project End Date', 'Contributor First Name', 'Last Name', 'Affiliation', 'Email', 
    'Funding Agency', 'Funding ID', 'Document Short Name', 'Summary', 'Type of Data', 'Description', 'Data Collection Process', 'Data Organisation Process',
    'Data Storage Process', 'Meta Data Requirement', 'Copyright Owner', 'Data Access', 'Data Access Detail', 'Release Date', 'Compliance Process',
    'Type of Data Retention', 'Data must be retained after submission of thesis or publication of results until', 'Data Publication Process', 'Data Licensing',
    'License Logo', 'The long-term preservation plan for the dataset', 'Research Data Management Contact','Required Resources', 'Data Issues' ,
    'Issues Description', 'Policy Control Body', 'Policy Requirements'];

if(mysql_num_rows($result))
{
    $html='<h3 class="pen-title">Research Data Management Plan</h3><br>';
    $html.='<table font="12" border="1">';
    while($row = mysql_fetch_row($result))
    {
        foreach($row as $key=>$value)
        {
            $html.= '<tr align="left">';
            $html.='<td width="200" bgcolor="#ddddaa">'. $arr[$key] . ': ' .'</td>';
            $html.='<td bgcolor="#bbccbb">' . $value .'</td>';
            $html.='</tr>';
        }
    }
    $html.='</table>';
}
$html.='</body></html>';
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream();
exit;