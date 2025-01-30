<?php

  echo $_POST['tracking_code'] ;

  $tracking_code = $_POST['tracking_code'];
  $curl = curl_init();

  if (!$curl) {
    echo " Error retriving tracking info" ;
  } else {
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-eu.dhl.com/track/shipments?trackingNumber='.$tracking_code,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'DHL-API-Key: DwuAtkLvmQ4gj7jXOTsO3UFjeOPA6Pec'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
  }
  

?>