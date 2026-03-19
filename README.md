# Survey Layanan (SLiMS Plugin)

Plugin ini merupakan modul tambahan untuk SLiMS (Senayan Library Management System) yang digunakan untuk mengukur tingkat kepuasan pemustaka terhadap layanan perpustakaan. Data yang dihasilkan dapat digunakan sebagai bahan evaluasi dan peningkatan kualitas layanan secara berkelanjutan.

## Deskripsi Singkat

Survey Layanan membantu perpustakaan dalam:

* Mengukur kepuasan pengguna terhadap layanan
* Menilai kualitas fasilitas dan kenyamanan
* Mengevaluasi kinerja petugas
* Mengetahui persepsi terhadap koleksi dan sistem
* Mengumpulkan saran langsung dari pemustaka

Hasil survey diolah menjadi laporan analitis yang mudah dipahami dan siap digunakan sebagai dasar pengambilan keputusan.

## Fitur Utama

* Form survey berbasis OPAC (mudah diakses pengguna)
* Autocomplete pencarian anggota
* Skala penilaian Likert (1–5)
* Validasi input lengkap (semua pertanyaan wajib)
* Anti spam menggunakan math challenge
* Pembatasan pengisian (1 kali per hari per anggota)
* Penyimpanan data otomatis ke database
* Dashboard laporan dengan statistik utama
* Perhitungan nilai rata-rata per kategori
* Identifikasi nilai tertinggi dan terendah
* Grafik visual menggunakan Chart.js
* Rekap responden per kelas / grup
* Tabel saran pemustaka
* Filter laporan berdasarkan periode
* Fitur cetak laporan

## Struktur Penilaian

Survey menggunakan skala:

1 = Sangat Tidak Setuju
2 = Tidak Setuju
3 = Netral
4 = Setuju
5 = Sangat Setuju

Kategori penilaian terdiri dari:

* Fasilitas Perpustakaan
* Layanan Perpustakaan
* Koleksi Perpustakaan
* Sistem Perpustakaan
* Kenyamanan Perpustakaan

Setiap kategori memiliki beberapa indikator yang diukur secara kuantitatif.

## Cara Kerja
<img width="1920" height="1020" alt="image" src="https://github.com/user-attachments/assets/d162d88e-2d2f-477e-91e3-3dcea117ff2b" />

1. Pemustaka mengakses halaman survey melalui OPAC
2. Pemustaka mencari dan memilih data dirinya
3. Mengisi seluruh pertanyaan dengan skala penilaian
4. Mengisi saran (opsional)
5. Menyelesaikan verifikasi math challenge
6. Sistem menyimpan data ke database
7. Admin melihat hasil dalam bentuk dashboard dan laporan

## Instalasi

1. Salin folder plugin ke direktori:
   `plugins/`

2. Pastikan nama folder sesuai, misalnya:
   `library_survey`

3. Plugin akan otomatis:

   * Membuat tabel database jika belum ada
   * Menyesuaikan struktur tabel (auto upgrade)

4. Akses menu:
   `Reporting → Survey Layanan`

5. Untuk membuka form survey:
   gunakan parameter:
   `?p=surveylayanan`

## Struktur Database

Plugin ini menggunakan tabel:
`library_survey`

Field utama:

* member_id
* member_name
* member_class
* survey_date
* q1 – q15 (nilai kepuasan)
* saran
* created_at

## Output Laporan
<img width="1920" height="1020" alt="image" src="https://github.com/user-attachments/assets/abf11dcd-60c4-4620-930a-979b8e0d7fad" />

Laporan yang dihasilkan meliputi:

* Total responden
* Nilai rata-rata keseluruhan
* Nilai rata-rata per kategori
* Nilai tertinggi (aspek terbaik)
* Nilai terendah (aspek yang perlu ditingkatkan)
* Grafik perbandingan antar kategori
* Distribusi responden per kelas
* Daftar saran pemustaka

Periode laporan dapat ditampilkan dalam bentuk:

* Bulanan
* Triwulan
* Semester
* Tahunan
* Atau berdasarkan filter tanggal manual

## Kegunaan

Plugin ini dapat dimanfaatkan untuk:

* Evaluasi kualitas layanan perpustakaan
* Penyusunan program peningkatan layanan
* Monitoring kepuasan pemustaka
* Bahan laporan kinerja perpustakaan
* Pendukung akreditasi dan audit layanan

## Kelebihan

* Terintegrasi langsung dengan data member SLiMS
* Perhitungan otomatis dan akurat
* Tampilan laporan informatif dan mudah dipahami
* Tidak memerlukan konfigurasi tambahan
* Siap digunakan untuk kebutuhan evaluasi rutin

## Catatan

* Field `pin` pada member digunakan sebagai kelas atau kode grup
* Pastikan data member sudah terisi dengan benar
* Disarankan digunakan secara berkala untuk hasil yang lebih akurat

## Author

Indra Febriana Rulliawan
[https://github.com/indra-f-r](https://github.com/indra-f-r)

## Lisensi

Plugin ini bebas digunakan dan dikembangkan sesuai kebutuhan perpustakaan.
