<?php 
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';
date_default_timezone_set('UTC');

// $rs = Capsule::select('select * from  account_account');
$rs = Capsule::select('select * from   HR.JOBS');
var_dump($rs);

// $conn = oci_connect('system', 'roottoor', 'localhost/XE');

// if($conn) {
//     echo "Successed full ";
// } else {

//     echo "connection failure";
// }

// $stid = oci_parse($conn, 'select * from   HR.JOBS');
// oci_execute($stid);
// echo "<table border='1'>\n";
// while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
//     echo "<tr>\n";
//     foreach ($row as $item) {
//         echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
//     }
//     echo "</tr>\n";
// }
// echo "</table>\n";
// phpinfo();