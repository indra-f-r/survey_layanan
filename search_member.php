<?php
define('INDEX_AUTH',1);
require '../../sysconfig.inc.php';

global $dbs;

$q=isset($_GET['q'])?$dbs->escape_string($_GET['q']):'';

if(strlen($q)<2) exit;

$query=$dbs->query("
SELECT member_id,member_name,pin
FROM member
WHERE member_name LIKE '%$q%'
ORDER BY member_name
LIMIT 10
");

while($row=$query->fetch_assoc()){

$name=htmlspecialchars($row['member_name']);
$id=htmlspecialchars($row['member_id']);
$class=htmlspecialchars($row['pin']);

echo "<div class='member-result'
data-id=\"$id\"
data-name=\"$name\"
data-class=\"$class\"
style='padding:8px;border-bottom:1px solid #ddd;cursor:pointer;background:#fff'>";

echo "<b>$name</b><br>";
echo "<small>ID: $id | Kelas: $class</small>";

echo "</div>";

}