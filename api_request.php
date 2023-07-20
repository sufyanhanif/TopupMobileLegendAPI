<?php

if (isset($_GET['id']) && isset($_GET['server'])) {
    $id = $_GET['id'];
    $server = $_GET['server'];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://id-game-checker.p.rapidapi.com/mobile-legends/".$id."/".$server,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: id-game-checker.p.rapidapi.com",
			"X-RapidAPI-Key: a21aa92503mshe500a6954b19342p1a8a38jsn318dc860bae1"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "Error: " . $err;
    } else {
        // Redirect back to index.php with the API response
        $params = http_build_query(['response' => $response]);
        header("Location: index.php?$params");
        exit();
    }
}
