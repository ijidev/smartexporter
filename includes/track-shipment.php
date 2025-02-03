<?php

  // echo $_POST['tracking_code'] ;

  $tracking_code = $_POST['tracking_code'];
  $curl = curl_init();

  
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

    $data = json_decode($response, true) ;
    $shipment = $data['shipments'][0];

    // echo $shipment['service'];
    // echo "<br>";
    // print_r($data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Tracking - The smartexporter</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
            margin: 2rem 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            height: 100%;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-event {
            position: relative;
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }
        .timeline-event::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 4px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d6efd;
            border: 3px solid #fff;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        .shipment-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="shipment-card bg-white p-4">
                    <h2 class="mb-3">Shipment Tracking</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-muted mb-0">Tracking Number</h5>
                            <h3 class="text-primary"> <?php echo $shipment['id'] ?> </h3>
                        </div>
                        <div class="text-end">
                            <h5 class="text-muted mb-2">Current Status</h5>
                            <span class="status-badge bg-primary mt-2 text-white"><?php echo $shipment['status']['statusCode'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="row">
            <div class="col-md-8">
                <div class="shipment-card bg-white p-4 mb-4">
                    <h4 class="mb-4">Tracking History</h4>
                    <div class="timeline">
                        <!-- Timeline Events will be inserted here -->
                        <?php
                            foreach ($shipment['events'] as $event) {
                                echo '
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between">
                                        <h5>' . htmlspecialchars($event['location']['address']['addressLocality']) . '</h5>
                                        <small class="text-muted">' . date('Y-m-d H:i', strtotime($event['timestamp'])) . '</small>
                                    </div>
                                    <p class="mb-0">' . htmlspecialchars($event['description']) . '</p>
                                    <small class="text-muted">' . htmlspecialchars($event['statusCode']) . '</small>
                                </div>';
                            }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Shipment Details -->
            <div class="col-md-4">
                <div class="shipment-card bg-white p-4">
                    <h4 class="mb-4">Shipment Details</h4>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">From</h6>
                        <p class="mb-0">{{senderName}}<br>
                        <?php echo $shipment['origin']['address']['addressLocality'] ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">To</h6>
                        <p class="mb-0">{{recipientName}}<br>
                        <?php echo $shipment['destination']['address']['addressLocality'] ?></p>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Est. Delivery</h6>
                            <p class="mb-0">{{estimatedDelivery}}</p>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Weight</h6>
                            <p class="mb-0">{{weight}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>