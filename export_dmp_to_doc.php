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

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<link rel="stylesheet" href="form_css/style1.css">';
echo '</head>';
echo '<body>';

$arr=['DMP No.', 'DMP Created Date', 'Project Title', 'Project Start Date', 'Project End Date', 'Contributor First Name', 'Last Name', 'Affiliation', 'Email', 
    'Funding Agency', 'Funding ID', 'Document Short Name', 'Summary', 'Type of Data', 'Description', 'Data Collection Process', 'Data Organisation Process',
    'Data Storage Process', 'Meta Data Requirement', 'Copyright Owner', 'Data Access', 'Data Access Detail', 'Release Date', 'Compliance Process',
    'Type of Data Retention', 'Data must be retained after submission of thesis or publication of results until', 'Data Publication Process', 'Data Licensing',
    'License Logo', 'The long-term preservation plan for the dataset', 'Research Data Management Contact','Required Resources', 'Data Issues' ,
    'Issues Description', 'Policy Control Body', 'Policy Requirements'];

if(mysql_num_rows($result))
{
    $parser->parse('<h3>Research Data Management Plan</h3>');
    while($row = mysql_fetch_row($result))
    {
        foreach($row as $key=>$value)
        {
            $parser->parse( '<p><b>'. $arr[$key]. ': </b> ' . $value .'</p>');
        }
    }
}
$parser->parse('</body>');
$parser->parse('</html>');
$doc->saveAs( 'dmp.docx' );
echo 'Exported the last imported DMP to dmp.docx';