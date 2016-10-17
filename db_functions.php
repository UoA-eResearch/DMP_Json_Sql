<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$con = mysql_connect('localhost', 'root', '');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

mysql_select_db("dmp_tool", $con);

function insert_db($str)
{
    if (!mysql_query($str, $con)) {
    die('Error : ' . mysql_error());
    } 
}

function get_id($str)
{
    $result=mysql_query($str, $con);
    return mysql_fetch_row($result)[0];
}