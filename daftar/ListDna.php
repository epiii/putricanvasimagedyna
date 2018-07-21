<?php
function dnaList($urutan, $seq){
  if (strtolower($seq) == "angka") {
    for ($i = 1; $i <= 1000; $i++) {
      if ($urutan == $i) {
        if ($i > 99) {
          $dna = $i;
        } else if ($i > 9) {
          $dna = "0".$i;
        } else {
          $dna = "00".$i;
        }
      }
    }
    return $dna;
  } else {
    if ($urutan % 676 == 0) {
      $dig1=floor($urutan/676);
    } else {
      $dig1=floor($urutan/676)+1;
    }

    if ($urutan % 26 == 0) {
      $dig2=floor($urutan/26);
      $dig3=26;
    } else {
      $dig2=floor($urutan/26)+1;
      $dig3=$urutan % 26;
    }

    if ($dig2 > 234) { // j
      $dig2 = $dig2-234;
    } else if ($dig2 > 208) { // i
      $dig2 = $dig2-208;
    } else if ($dig2 > 182) { // h
      $dig2 = $dig2-182;
    } else if ($dig2 > 156) { // g
      $dig2 = $dig2-156;
    } else if ($dig2 > 130) { // f
      $dig2 = $dig2-130;
    } else if ($dig2 > 104) { // e
      $dig2 = $dig2-104;
    } else if ($dig2 > 78) { // d
      $dig2 = $dig2-78;
    } else if ($dig2 > 52) { // c
      $dig2 = $dig2-52;
    } else if ($dig2 > 26) { // b
      $dig2 = $dig2-26;
    } else { // a
      $dig2 = $dig2;
    }
    $dna=chr($dig1+96).chr($dig2+96).chr($dig3+96);
    return strtoupper($dna);
  }
}

function readDna($urutan, $seq){
  if (strtolower($seq) == "huruf") {
    $hur1=substr($urutan,0,1);
    $hur2=substr($urutan,1,1);
    $hur3=substr($urutan,2,1);

    $a1=ord(strtolower($hur1))-96;
    $a2=ord(strtolower($hur2))-96;
    $a3=ord(strtolower($hur3))-96;

    if ($a1 > 1)
      { $h1 = ($a1-1)*676; } else { $h1 = 0; }

    if ($a2 > 1)
      { $h2 = ($a2-1)*26; } else { $h2 = 0; }

    $h3 = $a3;
    $hasil = $h1+$h2+$h3;
  }
  return $hasil;
}
/*
echo dna(80,"Huruf")."<br>";
echo readDna("ADB","Huruf");
*/
/*
echo "<br> hallo <br>";
echo dna(101,"Huruf");
*/
?>
