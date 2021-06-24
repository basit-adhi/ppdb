<?php
/** 
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */
 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
set_time_limit(18000);

define("SESSION_", "session");
define("POST_", "post");
define("GET_", "get");
define("POSTGET_", "postget");

//tes nilai dengan filter dalam bentuk array
function looping_filter($data, &$filter)
{
 $lanjut = true;
 reset($filter);
 while(list($i, $v) = each($filter))
 {
  if ($i == 0)
   $lanjut = strpos(array_key_csv($data, $v[0]), $v[1]) !== false;
  else
   $lanjut = $lanjut && strpos(array_key_csv($data, $v[0]), $v[1]) !== false;
 }
 return $lanjut;
}

//array_key_csv($arr, "1,2") --> $arr[1][2]
function array_key_csv($arr, $csv_key)
{
 $keys = explode(",", $csv_key);
 $num  = count($keys);
 switch ($num)
 {
  case 1: return @$arr[trim($keys[0])];
  case 2: return @$arr[trim($keys[0])][trim($keys[1])];
  case 3: return @$arr[trim($keys[0])][trim($keys[1])][trim($keys[2])];
  case 4: return @$arr[trim($keys[0])][trim($keys[1])][trim($keys[2])][trim($keys[3])];
  case 5: return @$arr[trim($keys[0])][trim($keys[1])][trim($keys[2])][trim($keys[3])][trim($keys[4])];
  case 6: return @$arr[trim($keys[0])][trim($keys[1])][trim($keys[2])][trim($keys[3])][trim($keys[4])][trim($keys[5])];
  case 7: return @$arr[trim($keys[0])][trim($keys[1])][trim($keys[2])][trim($keys[3])][trim($keys[4])][trim($keys[5])][trim($keys[6])];
 }
}

//seperti ifnull pada MySQL
function ifnull($input, $alternative)
{
    return (!isset($input) || is_null($input) || trim($input) == "" || trim($input) == "undefined" || trim($input) == "null") ? $alternative : $input;
}

//ambil data SESSION, POST dan GET dengan lebih aman
function extract_formdata($metode, $nama)
{
    $ret = "";
    if ($metode == SESSION_)
    {
        $ret = $_SESSION[$nama];
    }
    if ($metode == POST_ || $metode == POSTGET_)
    {
        $ret = filter_input(INPUT_POST, $nama, FILTER_SANITIZE_STRING);
    }
    if ($metode == GET_ || ($metode == POSTGET_ && $ret == ""))
    {
        $ret = filter_input(INPUT_GET, $nama, FILTER_SANITIZE_STRING);
    }
    return $ret;
}

//request api kemudian mengubah hasilnya dalam bentuk json
function read_json($url, $isjson=true)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_TIMEOUT, 1000); //10 detik
 $data = curl_exec($ch);
 if (empty($data) or $data == "null")
 {
    $data = curl_error($ch);
 }
 curl_close($ch);
 if ($isjson)
  return json_decode($data, true);
 else
  return $data;
}

//mapping
$map = [ [
          "nama"      => "PPDB Bantul Seleksi Jalur Prestasi",
          "url"       => "https://bantulkab.siap-ppdb.com/sekolah/1-smp-pr.json",
          "loopsiswa" => [ 
                           "url"           => "https://bantulkab.siap-ppdb.com/seleksi/pr/smp/1-[[sekolah_id]]-0.json",
                           "idxsekolah_id" => "sekolah_id",
                           "filter"        => [ ["6", "Luar Kabupaten"] ]
                         ],
          "aspd"      => [ 
                           "url"           => "https://api.siap-ppdb.com/cari?no_daftar=[[nodaftar]]",
                           "idxnodaftar"   => 2,
                           "idxnama"       => 3,
                           "sum"           => [ "3,3,0,3,0", "3,3,1,3,0", "3,3,2,3,0" ],
                           "filter"        => [ ["0,3,8,3", "2021"], ["0,3,5,3", "Kota Yogyakarta"] ]
                         ]
         ],
         [
          "nama"      => "PPDB Sleman Seleksi Jalur Prestasi",
          "url"       => "https://sleman.siap-ppdb.com/sekolah/1-smp-reguler.json",
          "loopsiswa" => [ 
                           "url"           => "https://sleman.siap-ppdb.com/seleksi/reguler/smp/1-[[sekolah_id]]-0.json",
                           "idxsekolah_id" => "sekolah_id"
                         ],
          "aspd"      => [ 
                           "url"           => "https://api.siap-ppdb.com/cari?no_daftar=[[nodaftar]]",
                           "idxnodaftar"   => 3,
                           "idxnama"       => 4,
                           "sum"           => [ "3,3,0,3,0", "3,3,1,3,0", "3,3,2,3,0" ],
                           "filter"        => [ ["0,3,8,3", "2021"], ["0,3,5,3", "Kota Yogyakarta"] ]
                         ]
         ],
         [
          "nama"      => "PPDB JOGJA Daftar Jalur Zonasi Mutu",
          "url"       => "https://yogya.siap-ppdb.com/sekolah/lokasi/1-smp-zm.json",
          "loopsiswa" => [ 
                           "url"           => "https://yogya.siap-ppdb.com/daftar/zm/smp/1-[[sekolah_id]].json",
                           "idxsekolah_id" => "lokasi_id"
                         ],
          "aspd"      => [ 
                           "url"           => "https://api.siap-ppdb.com/cari?no_daftar=[[nodaftar]]",
                           "idxnodaftar"   => 3,
                           "idxnama"       => 4,
                           "sum"           => [ "3,3,4,3,0" ],
                           "cekprestasi"   => "4,0",
                           "pilihan"       => [ "nonprestasi" => ["5,3,0,3,0,3", "5,3,1,3,0,3", "5,3,2,3,0,3"], "prestasi" => ["6,3,0,3,0,3", "6,3,1,3,0,3", "6,3,2,3,0,3"] ],
                           "filter"        => [ ["0,3,8,3", "2021"] ]
                         ]
         ],
         [
          "nama"      => "PPDB JOGJA Daftar Jalur Afirmasi",
          "url"       => "https://yogya.siap-ppdb.com/sekolah/lokasi/1-smp-af.json",
          "loopsiswa" => [ 
                           "url"           => "https://yogya.siap-ppdb.com/daftar/af/smp/1-[[sekolah_id]].json",
                           "idxsekolah_id" => "lokasi_id"
                         ],
          "aspd"      => [ 
                           "url"           => "https://api.siap-ppdb.com/cari?no_daftar=[[nodaftar]]",
                           "idxnodaftar"   => 2,
                           "idxnama"       => 3,
                           "sum"           => [ "3,3,4,3,0" ],
                           "cekprestasi"   => "4,0",
                           "pilihan"       => [ "nonprestasi" => ["5,3,0,3,0,3", "5,3,1,3,0,3", "5,3,2,3,0,3"], "prestasi" => ["6,3,0,3,0,3", "6,3,1,3,0,3", "6,3,2,3,0,3"] ],
                           "filter"        => [ ["0,3,8,3", "2021"] ]
                         ]
         ],
         [
          "nama"      => "PPDB JOGJA Seleksi Jalur Bibit Unggul",
          "url"       => "https://yogya.siap-ppdb.com/sekolah/1-smp-bu.json",
          "loopsiswa" => [ 
                           "url"           => "https://yogya.siap-ppdb.com/seleksi/bu/smp/1-[[sekolah_id]]-0.json",
                           "idxsekolah_id" => "sekolah_id"
                         ],
          "aspd"      => [ //https://yogya.siap-ppdb.com/seleksi/bu/smp/1-22040001.json
                           "url"           => "https://api.siap-ppdb.com/cari?no_daftar=[[nodaftar]]",
                           "idxnodaftar"   => 2,
                           "idxnama"       => 3,
                           "sum"           => [ "3,3,4,3,0" ],
                           "filter"        => [ ["0,3,8,3", "2021"] ]
                         ]
         ]
       ];

//simpan sesi selama 5 hari, jik memungkinkan
ini_set("session.gc_maxlifetime", 3600 * 24 * 5);
ini_set("session.gc_divisor", "1");
ini_set("session.gc_probability", "1");
ini_set("session.sesi_lifetime", "0");
session_start();

//- program utama -//
/*
Algoritma:
Ambil sekolah sesuai dengan Jalur pada Kota/Kabupaten
|- Dari data sekolah tersebut, ambil data semua pendaftar
   |- Dari data pendaftar tersebut, 
      a. jika sudah disimpan di sesi, maka tinggal tampilkan/tidak tergantung status tampilnya. 
         hapus data ini dari sesi.
      b. jika belum ada di sesi, maka filter datanya.
         jika lolos filter, maka hitung nilai aspdnya dan tampilkan.
         data ini nanti akan disimpan ke sesi.
   |- ubah status semua yang masih tersisa di sesi menjadi tidak tampil
   |- salin data ke sesi agar tidak perlu unduh data lagi
*/
$id   = extract_formdata(GET_, "i");
$no   = 1;
$data = $map[$id];

//ambil data sekolah
$list_sekolah = read_json($data["url"]);

echo "<h1>".$data["nama"]."</h1>";
echo "<table></tr><th>No</th><th>No Daftar</th><th>Nama</th><th>Nilai</th><th>Pil 1</th><th>Pil 2</th><th>Pil 3</th></tr>";
$nsek = count($list_sekolah);

//looping per sekolah
foreach ($list_sekolah as $idxsekolah => $datasekolah)
{
 //siapkan sesi per sekolah
 $sesisiswa    = array();
 $idxsesisiswa = "xyzDatasiswa_".$id."_".$datasekolah[$data["loopsiswa"]["idxsekolah_id"]];
 $cs             = @unserialize($_SESSION[$idxsesisiswa]);
 if (is_array($cs))
 {
     $sesisiswa = $cs;
 }
 $sesisiswa__  = array();
 $darisesi     = 0;
 $unduhdata    = 0;
 
 //log proses
 echo "Proses ".str_replace("[[sekolah_id]]", $datasekolah[$data["loopsiswa"]["idxsekolah_id"]], $data["loopsiswa"]["url"])." ".($idxsekolah+1)." dari ".$nsek."...";
 
 //ambil data siswa
 $list_siswa = read_json(str_replace("[[sekolah_id]]", $datasekolah[$data["loopsiswa"]["idxsekolah_id"]], $data["loopsiswa"]["url"]))["data"];
 
 //looping per siswa
 reset($list_siswa);
 while(list($keysiswa, $datasiswa) = each($list_siswa))
 {
  $idsiswa = $datasiswa[$data["aspd"]["idxnodaftar"]];
  //jika data siswa tidak ada di sesi
  if (!array_key_exists($idsiswa, $sesisiswa))
  {
   $unduhdata++;
   $lanjut = false;
   
   //cek apakah data lolos dari filter (jika tidak ada filter berarti langsung lolos)
   if (!array_key_exists("filter", $data["loopsiswa"]))
   {
    $lanjut = true;
   }
   else 
   {
    $lanjut = looping_filter($datasiswa, $data["loopsiswa"]["filter"]);
   }
   
   if ($lanjut)
   {
    //baca data 1 siswa
    $list_detailsiswa = read_json(str_replace("[[nodaftar]]", $idsiswa, $data["aspd"]["url"]));
    
    //cek apakah data lolos dari filter (jika tidak ada filter berarti langsung lolos)
    if (!array_key_exists("filter", $data["aspd"]))
    {
     $lanjut = true;
    }
    else 
    {
     $lanjut = looping_filter($list_detailsiswa, $data["aspd"]["filter"]);
    }
    
    //masuk detail data siswa
    if ($lanjut)
    { 
     $pilihan = [ "", "", "" ];
     
     //hitung total aspd
     $sum     = 0;
     reset($data["aspd"]["sum"]);
     while(list($idx, $val) = each($data["aspd"]["sum"]))
     {
      $sum += array_key_csv($list_detailsiswa, $val);
     }
     
     //jika pilihan ingin ditampilkan
     if (array_key_exists("pilihan", $data["aspd"]))
     {
      //jika siswa memiliki prestasi
      if (array_key_csv($list_detailsiswa, $data["aspd"]["cekprestasi"]) == "prestasi")
      {
       reset($data["aspd"]["pilihan"]["prestasi"]);
       while(list($idx, $val) = each($data["aspd"]["pilihan"]["prestasi"]))
       {
        $pilihan[$idx] = array_key_csv($list_detailsiswa, $val);
       }
      }
      else
      {
       reset($data["aspd"]["pilihan"]["nonprestasi"]);
       while(list($idx, $val) = each($data["aspd"]["pilihan"]["nonprestasi"]))
       {
        $pilihan[$idx] = array_key_csv($list_detailsiswa, $val);
       }
      }
     }
     
     $sesisiswa__[$idsiswa] = [ "nodaftar" => $idsiswa, "nama" => $datasiswa[$data["aspd"]["idxnama"]], "aspd" => str_replace(".",",",$sum), "pil1" => $pilihan[0], "pil2" => $pilihan[1], "pil3" => $pilihan[2], "tampil" => true ];
     echo "<tr><td>".$no++."</td><td>".$idsiswa."</td><td>".$datasiswa[$data["aspd"]["idxnama"]]."</td><td>".str_replace(".",",",$sum)."</td><td>".$pilihan[0]."</td><td>".$pilihan[1]."</td><td>".$pilihan[2]."</td></tr>";
    }
    else
    {
     $sesisiswa__[$idsiswa] = [ "nodaftar" => $idsiswa, "tampil" => false ];
    }
   }
   else
   {
    $sesisiswa__[$idsiswa] = [ "nodaftar" => $idsiswa, "tampil" => false ];
   }
  }
  //jika data siswa ada di sesi
  else
  {
   $darisesi++;
   if ($sesisiswa[$idsiswa]["tampil"])
   {
    echo "<tr><td>".$no++."</td><td>".$idsiswa."</td><td>".$sesisiswa[$idsiswa]["nama"]."</td><td>".$sesisiswa[$idsiswa]["aspd"]."</td><td>".$sesisiswa[$idsiswa]["pil1"]."</td><td>".$sesisiswa[$idsiswa]["pil2"]."</td><td>".$sesisiswa[$idsiswa]["pil3"]."</td></tr>"; 
   }
   
   //salin data yang dipakai
   $sesisiswa__[$idsiswa] = $sesisiswa[$idsiswa];
   
   //hapus data yang sudah dipakai
   unset($sesisiswa[$idsiswa]);
  }
 }
 
 //yang tidak diproses berarti berubah dari tampil=true menjadi tampil=false
 reset($sesisiswa);
 while(list($keyc, $datac) = each($sesisiswa))
 {
  $sesisiswa__[$keyc] = [ "nodaftar" => $keyc, "tampil" => false ]; 
 }
 
 //simpan data ke sesi
 $_SESSION[$idxsesisiswa] = serialize($sesisiswa__);
 echo "Dari Sesi: ".$darisesi.", Unduh Data: ".$unduhdata.". Done<br/>";
}
echo "</table>";
