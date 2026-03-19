<?php
/**
 * Plugin Name: Survey Layanan
 * Plugin URI: https://github.com/indra-f-r/survey_layanan
 * Description: Survey Kepuasan Perpustakaan
 * Version: 1.0.0
 * Author: Indra Febriana Rulliawan
 * Author URI: https://github.com/indra-f-r
 */

$plugin = \SLiMS\Plugins::getInstance();
global $dbs;

/* =====================================
CEK / BUAT TABEL
===================================== */

$check = $dbs->query("SHOW TABLES LIKE 'library_survey'");

if ($check && $check->num_rows == 0) {

$sql = "CREATE TABLE library_survey (

id INT AUTO_INCREMENT PRIMARY KEY,

member_id VARCHAR(20),
member_name VARCHAR(100),
member_class VARCHAR(50),

survey_date DATE,

q1 TINYINT,
q2 TINYINT,
q3 TINYINT,
q4 TINYINT,
q5 TINYINT,
q6 TINYINT,
q7 TINYINT,
q8 TINYINT,
q9 TINYINT,
q10 TINYINT,
q11 TINYINT,
q12 TINYINT,
q13 TINYINT,
q14 TINYINT,
q15 TINYINT,

saran VARCHAR(255),

created_at DATETIME

) ENGINE=InnoDB";

$dbs->query($sql);

}

/* =====================================
CEK KOLOM TAMBAHAN (UPGRADE)
===================================== */

$columns = [];

$result = $dbs->query("SHOW COLUMNS FROM library_survey");

while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

if (!in_array('member_class', $columns)) {
    $dbs->query("ALTER TABLE library_survey ADD member_class VARCHAR(50)");
}

if (!in_array('survey_date', $columns)) {
    $dbs->query("ALTER TABLE library_survey ADD survey_date DATE");
}

if (!in_array('created_at', $columns)) {
    $dbs->query("ALTER TABLE library_survey ADD created_at DATETIME");
}

if (!in_array('saran', $columns)) {
    $dbs->query("ALTER TABLE library_survey ADD saran VARCHAR(255)");
}

/* =====================================
REGISTER MENU
===================================== */

$plugin->registerMenu(
'reporting',
'Survey Layanan',
__DIR__.'/index.php'
);

/* =====================================
ROUTING OPAC SURVEY
===================================== */

if (isset($_GET['p']) && $_GET['p'] == 'surveylayanan') {
    if(!defined('INDEX_AUTH')) define('INDEX_AUTH',1);
    require_once SB.'sysconfig.inc.php';
    require __DIR__.'/survey_layanan.php';
    exit;
}
