<?php
defined('INDEX_AUTH') OR die('Direct access not allowed!');
require SB.'admin/default/session.inc.php';
date_default_timezone_set('Asia/Jakarta');
global $dbs;

/* FILTER TANGGAL */

$start=$_GET['start'] ?? date('Y-m-01');
$end=$_GET['end'] ?? date('Y-m-d');

$ts_start=strtotime($start);
$ts_end=strtotime($end);

$where=" AND survey_date BETWEEN '$start' AND '$end'";

/* TOTAL RESPONDEN */

$total=$dbs->query("SELECT COUNT(*) t FROM library_survey WHERE 1=1 $where")->fetch_assoc()['t'];

/* RATA-RATA NILAI */

$avg=$dbs->query("
SELECT
AVG((q1+q2+q3)/3) fasilitas,
AVG((q4+q5+q6)/3) layanan,
AVG((q7+q8+q9)/3) koleksi,
AVG((q10+q11+q12)/3) sistem,
AVG((q13+q14+q15)/3) kenyamanan
FROM library_survey
WHERE 1=1 $where
")->fetch_assoc();

/* HITUNG STATISTIK */

$nilai=[
'fasilitas'=>$avg['fasilitas'],
'layanan'=>$avg['layanan'],
'koleksi'=>$avg['koleksi'],
'sistem'=>$avg['sistem'],
'kenyamanan'=>$avg['kenyamanan']
];

$nilai_rata=array_sum($nilai)/5;
$nilai_tertinggi=max($nilai);
$nilai_terendah=min($nilai);

$label_tertinggi=array_search($nilai_tertinggi,$nilai);
$label_terendah=array_search($nilai_terendah,$nilai);

$label=[
'fasilitas'=>'Fasilitas',
'layanan'=>'Layanan',
'koleksi'=>'Koleksi',
'sistem'=>'Sistem',
'kenyamanan'=>'Kenyamanan'
];

/* RESPONDEN PER KELAS (TOP 10) */

$kelas=$dbs->query("
SELECT member_class,COUNT(*) total
FROM library_survey
WHERE 1=1 $where
GROUP BY member_class
ORDER BY total DESC
LIMIT 10
");

/* SARAN */

$saran=$dbs->query("
SELECT member_name,member_class,saran
FROM library_survey
WHERE 1=1 $where
AND saran!=''
ORDER BY id DESC
LIMIT 50
");

/* HITUNG PERIODE */

$bulan=date('n',$ts_start);
$tahun=date('Y',$ts_start);

$bulan_nama=[1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",9=>"September",10=>"Oktober",11=>"November",12=>"Desember"];

$periode="Filter Tanggal";

if(date('Y-m',$ts_start)==date('Y-m',$ts_end)) $periode="Bulan ".$bulan_nama[$bulan]." ".$tahun;
elseif((date('m',$ts_end)-date('m',$ts_start))==2){
$tri=floor(($bulan-1)/3)+1;
$periode="Triwulan ".$tri." Tahun ".$tahun;
}
elseif((date('m',$ts_end)-date('m',$ts_start))==5){
$sem=($bulan<=6)?1:2;
$periode="Semester ".$sem." Tahun ".$tahun;
}
elseif(date('Y',$ts_start)==date('Y',$ts_end)) $periode="Tahun ".$tahun;
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.dashboard{display:flex;gap:20px;flex-wrap:wrap;margin-bottom:25px;justify-content:center}
.stat{color:#fff;padding:20px;border-radius:8px;font-size:26px;font-weight:bold;min-width:200px;text-align:center}
.stat small{display:block;font-size:13px;margin-top:5px;font-weight:normal}
.table{width:100%;border-collapse:collapse;margin-top:10px}
.table th,.table td{border:1px solid #ddd;padding:8px}
.table th{background:#f4f6f9}
.filter-box{margin-bottom:20px}
button{padding:6px 12px;border:none;background:#1565d8;color:#fff;border-radius:4px;cursor:pointer}
button:hover{background:#0d47a1}
.saran-table th:nth-child(1),.saran-table td:nth-child(1){width:180px}
.saran-table th:nth-child(2),.saran-table td:nth-child(2){width:120px;text-align:center}
.saran-table th:nth-child(3),.saran-table td:nth-child(3){width:auto}
.report-title{text-align:center;margin-bottom:20px}
.report-periode{font-size:16px;margin-top:5px}
.report-title hr{margin-top:10px}
@media print{.filter-box{display:none}button{display:none}}
</style>

<div class="menuBox">
<div class="menuBoxInner reportingIcon">
<div style="max-width:1100px;margin:auto;padding:20px;">

<div class="filter-box">
<form method="get" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
<input type="hidden" name="mod" value="<?= $_GET['mod'] ?? '' ?>">
<input type="hidden" name="id" value="<?= $_GET['id'] ?? '' ?>">
Tanggal
<input type="date" name="start" value="<?= $start ?>">
s/d
<input type="date" name="end" value="<?= $end ?>">
<button type="submit">Filter</button>
<button type="button" onclick="window.print()">Print</button>
</form>
</div>

<div class="report-title">
<h2>Survey Kepuasan Pemustaka</h2>
<div class="report-periode"><?= $periode ?></div>
<hr>
</div>

<div class="dashboard">
<div class="stat" style="background:#1565d8">Total Responden<br><?= $total ?></div>
<div class="stat" style="background:#2e7d32">Nilai Rata-rata<br><?= number_format($nilai_rata,2) ?></div>
<div class="stat" style="background:#6a1b9a">Nilai Tertinggi<br><?= number_format($nilai_tertinggi,2) ?><small><?= $label[$label_tertinggi] ?></small></div>
<div class="stat" style="background:#c62828">Nilai Terendah<br><?= number_format($nilai_terendah,2) ?><small><?= $label[$label_terendah] ?></small></div>
</div>

<h3>Grafik Kepuasan</h3>
<canvas id="surveyChart"></canvas>

<script>
const data={
labels:['Fasilitas','Layanan','Koleksi','Sistem','Kenyamanan'],
datasets:[{
label:'Nilai Rata-rata',
data:[
<?= number_format($avg['fasilitas'],2) ?>,
<?= number_format($avg['layanan'],2) ?>,
<?= number_format($avg['koleksi'],2) ?>,
<?= number_format($avg['sistem'],2) ?>,
<?= number_format($avg['kenyamanan'],2) ?>
],
backgroundColor:['#1565d8','#2e7d32','#ef6c00','#6a1b9a','#c62828']
}]
};
new Chart(document.getElementById('surveyChart'),{type:'bar',data:data});
</script>

<h3>Responden</h3>
<table class="table">
<tr><th>Kelas/Rombel</th><th>Jumlah</th></tr>
<?php while($row=$kelas->fetch_assoc()){ ?>
<tr><td><?= $row['member_class'] ?></td><td><?= $row['total'] ?></td></tr>
<?php } ?>
</table>

<h3>Saran</h3>
<table class="table saran-table">
<tr><th>Nama</th><th>Kelas</th><th>Saran</th></tr>
<?php while($row=$saran->fetch_assoc()){ ?>
<tr><td><?= $row['member_name'] ?></td><td><?= $row['member_class'] ?></td><td><?= $row['saran'] ?></td></tr>
<?php } ?>
</table>

</div>
</div>
</div>