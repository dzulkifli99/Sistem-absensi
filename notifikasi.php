<?php

function kirimWA($nomor, $pesan)
{

  $token = "nEyzvzjcDdthSYUXJoMZ";

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.fonnte.com/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => array(
      'target' => $nomor,
      'message' => $pesan,
    ),
    CURLOPT_HTTPHEADER => array(
      "Authorization: $token"
    ),
  ));

  curl_exec($curl);
  curl_close($curl);
}
