<?php

define('INDEX_AUTH',1);
require '../../sysconfig.inc.php';

date_default_timezone_set('Asia/Jakarta');

global $dbs;

/* =========================
CEK METHOD
========================= */

if($_SERVER['REQUEST_METHOD']!='POST'){
die("Invalid request");
exit;
}

/* =========================
VALIDASI MATH CHALLENGE
========================= */

if($_POST['math_answer'] != $_POST['math_correct']){
die("Math Challenge salah");
}

/* =========================
AMBIL DATA MEMBER
========================= */

$member_id = $dbs->escape_string($_POST['member_id']);
$member_name = $dbs->escape_string($_POST['member_name']);
$member_class = $dbs->escape_string($_POST['member_class']);

if(empty($member_id)){
die("Member tidak valid");
}

/* =========================
CEK SUDAH SURVEY HARI INI
========================= */

$check = $dbs->query("
SELECT id
FROM library_survey
WHERE member_id='$member_id'
AND survey_date=CURDATE()
");

if($check->num_rows>0){
echo "
<!DOCTYPE html>
<html>
<head>
<meta http-equiv='refresh' content='5;url=../../'>
<style>
body{
font-family:Arial;
text-align:center;
padding-top:80px;
background:#f4f6f9;
}
.box{
display:inline-block;
background:#fff;
padding:30px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<div class='box'>
<h2>Terima kasih</h2>
<p>Anda sudah mengisi survey hari ini.</p>
<p>Halaman akan kembali ke OPAC dalam <b>5 detik</b>.</p>
</div>

</body>
</html>
";
exit;
}

/* =========================
VALIDASI JAWABAN
========================= */

$answers=[];

for($i=1;$i<=15;$i++){

if(!isset($_POST["q$i"])){
die("Semua pertanyaan wajib dijawab");
}

$val=(int)$_POST["q$i"];

if($val<1 || $val>5){
die("Nilai tidak valid");
}

$answers[$i]=$val;

}

/* =========================
SARAN
========================= */

$saran='';

if(isset($_POST['saran'])){
$saran = substr($dbs->escape_string($_POST['saran']),0,255);
}

/* =========================
SIMPAN DATA
========================= */

$sql="
INSERT INTO library_survey
(
member_id,
member_name,
member_class,
survey_date,

q1,q2,q3,q4,q5,
q6,q7,q8,q9,q10,
q11,q12,q13,q14,q15,

saran,
created_at
)

VALUES
(
'$member_id',
'$member_name',
'$member_class',
CURDATE(),

'{$answers[1]}','{$answers[2]}','{$answers[3]}','{$answers[4]}','{$answers[5]}',
'{$answers[6]}','{$answers[7]}','{$answers[8]}','{$answers[9]}','{$answers[10]}',
'{$answers[11]}','{$answers[12]}','{$answers[13]}','{$answers[14]}','{$answers[15]}',

'$saran',
NOW()
)
";

$dbs->query($sql);

echo "
<!DOCTYPE html>
<html>
<head>

<style>
body{
font-family:Arial;
text-align:center;
padding-top:80px;
background:#f4f6f9;
}

.box{
display:inline-block;
background:#fff;
padding:30px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.count{
font-size:28px;
font-weight:bold;
color:#1565d8;
margin-top:10px;
}

</style>

</head>

<body>

<div class='box'>

<h2>Survey Berhasil</h2>

<p>Terima kasih telah mengisi survey kepuasan perpustakaan.</p>

<p>Halaman akan kembali ke OPAC dalam</p>

<div class='count'>
<span id='countdown'>5</span> detik
</div>

</div>

<script>

let time=5;

let timer=setInterval(function(){

time--;

document.getElementById('countdown').innerText=time;

if(time<=0){

clearInterval(timer);

window.location='../../';

}

},1000);

</script>

</body>
</html>
";
exit;