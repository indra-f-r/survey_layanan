<?php
if(!defined('INDEX_AUTH'))define('INDEX_AUTH',1);
require_once __DIR__.'/../../sysconfig.inc.php';
global $sysconf;
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html>
<head>
<title>Survey Kepuasan Pemustaka</title>
<meta name="viewport" content="width=device-width,initial-scale=1.2">

<style>

*{box-sizing:border-box}

body{font-family:Arial;background:#f4f6f9;margin:0;padding:6px;font-size:14px}

.container{
max-width:720px;
margin:auto;
background:#fff;
padding:14px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,.1)
}

.header{text-align:center;margin-bottom:12px}

.header img{
height:60px;
margin-bottom:6px
}

.header h2{
margin:0;
font-size:22px
}

.header h3{
margin:2px 0;
font-size:18px;
font-weight:normal
}

.header h4{
margin-top:6px
}

.form-group{
margin-top:8px
}

label{
font-weight:bold
}

input[type=text],textarea{
width:100%;
padding:10px;
border:1px solid #ccc;
border-radius:4px;
margin-top:4px;
font-size:18px
}

textarea{
resize:vertical
}

.legend{
margin-top:8px;
font-size:14px
}

.table{
width:100%;
border-collapse:collapse;
margin-top:8px;
font-size:12px;
table-layout:fixed
}

.table th,.table td{
border:1px solid #ddd;
padding:5px;
text-align:center
}

.table th{
background:#f4f6f9
}

.table td:first-child{
text-align:left;
font-size:14px;
font-weight:500
}

.category{
margin-top:18px;
border-top:2px solid #1565d8;
padding-top:6px
}

.category h3{
margin:0 0 6px 0;
color:#1565d8
}

input[type=radio]{
transform:scale(1.2)
}

button{
margin-top:14px;
width:100%;
padding:12px;
background:#1565d8;
color:#fff;
border:none;
border-radius:4px;
font-size:14px;
cursor:pointer
}

button:hover{
background:#0d47a1
}

#memberResult{
border:1px solid #ccc;
background:#fff;
max-height:200px;
overflow-y:auto
}

.member-result{
padding:6px;
cursor:pointer
}

.member-result:hover{
background:#f1f1f1
}

@media(max-width:768px){

body{
padding:4px;
font-size:19px
}

.container{
max-width:100%;
padding:12px
}

.header h2{
font-size:24px
}

.header h3{
font-size:19px
}

.table{
font-size:14px
}

input[type=radio]{
transform:scale(1.2)
}

}

.table th:first-child,
.table td:first-child{
width:55%;
}

.table th:first-child,
.table td:first-child{
width:55%;
}

</style>
</head>

<body>

<div class="container">

<div class="header">

<?php if(!empty($sysconf['logo'])){ ?>
<img src="../../images/<?php echo $sysconf['logo']; ?>">
<?php } ?>

<h2><?php echo $sysconf['library_name']; ?></h2>
<h3><?php echo $sysconf['library_subname']; ?></h3>
<h4>Survey Kepuasan Pemustaka</h4>

<div style="color:#c62828;font-size:14px;margin-top:4px">
Semua pertanyaan wajib dijawab kecuali bagian saran.
</div>

</div>

<form method="post" action="<?= SWB ?>plugins/library_survey/submit.php">

<div class="form-group">
<label>Nama Anggota</label>
<input type="text" id="searchMember" placeholder="Ketik nama anggota">
<div id="memberResult"></div>
</div>

<div class="form-group">
<label>Member ID</label>
<input type="text" name="member_id" id="member_id" readonly>
</div>

<div class="form-group">
<label>Kelas/Kode GTK</label>
<input type="text" name="member_class" id="member_class" readonly>
</div>

<input type="hidden" name="member_name" id="member_name">

<div class="legend">
STS = Sangat Tidak Setuju | TS = Tidak Setuju | N = Netral | S = Setuju | SS = Sangat Setuju
</div>

<?php

$kategori=[

"Fasilitas Perpustakaan"=>[
1=>"Fasilitas perpustakaan nyaman digunakan",
2=>"Ruang baca perpustakaan nyaman",
3=>"Peralatan perpustakaan memadai"
],

"Layanan Perpustakaan"=>[
4=>"Petugas perpustakaan ramah",
5=>"Petugas memberikan pelayanan yang baik",
6=>"Petugas membantu ketika mengalami kesulitan"
],

"Koleksi Perpustakaan"=>[
7=>"Koleksi buku perpustakaan lengkap",
8=>"Buku relevan dengan kebutuhan",
9=>"Koleksi buku dalam kondisi baik"
],

"Sistem Perpustakaan"=>[
10=>"Sistem pencarian buku mudah digunakan",
11=>"ketersediaan buku mudah di ketahui",
12=>"Proses Peminjaman dan Pengembalian mudah"
],

"Kenyamanan Perpustakaan"=>[
13=>"Suasana perpustakaan tenang",
14=>"Pencahayaan ruang baca cukup baik",
15=>"Perpustakaan membuat saya betah membaca"
]

];

foreach($kategori as $judul=>$pertanyaan){

echo "<div class='category'>";
echo "<h3>$judul</h3>";

echo "<table class='table'>";

echo "<tr>
<th>Pertanyaan</th>
<th>STS</th>
<th>TS</th>
<th>N</th>
<th>S</th>
<th>SS</th>
</tr>";

foreach($pertanyaan as $no=>$text){

echo "<tr>";
echo "<td>$text</td>";

for($i=1;$i<=5;$i++){
$req=$i==1?'required':'';
echo "<td><input type='radio' name='q$no' value='$i' $req></td>";
}

echo "</tr>";

}

echo "</table>";

echo "</div>";

}

?>

<div class="form-group">
<label>Saran</label>
<textarea name="saran" maxlength="255" rows="3"></textarea>
</div>

<div class="form-group">
<label>Math Challenge</label>
<div id="mathQuestion" style="margin-top:4px;font-weight:bold"></div>
<input type="number" name="math_answer" id="mathAnswer">
<input type="hidden" name="math_correct" id="mathCorrect">
</div>

<button type="submit">Kirim Survey</button>

</form>

</div>

<script>

document.getElementById("searchMember").addEventListener("keyup",function(){

let q=this.value;

if(q.length<2){
document.getElementById("memberResult").innerHTML="";
return;
}

fetch("<?= SWB ?>plugins/library_survey/search_member.php?q="+q)
.then(r=>r.text())
.then(data=>{
document.getElementById("memberResult").innerHTML=data;
});

});

document.addEventListener("click",function(e){

let item=e.target.closest(".member-result");

if(item){

document.getElementById("member_id").value=item.dataset.id;
document.getElementById("member_name").value=item.dataset.name;
document.getElementById("member_class").value=item.dataset.class;
document.getElementById("searchMember").value=item.dataset.name;
document.getElementById("memberResult").innerHTML="";

}

});

function generateMath(){

let a=Math.floor(Math.random()*10)+1;
let b=Math.floor(Math.random()*10)+1;

document.getElementById("mathQuestion").innerHTML="Berapa hasil: "+a+" + "+b+" ?";
document.getElementById("mathCorrect").value=a+b;

}

generateMath();

</script>

</body>
</html>