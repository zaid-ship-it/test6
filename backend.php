<?php
header('Content-Type: application/json');

if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo json_encode(['error' => 'Username is required.']);
    exit;
}

$username = htmlspecialchars($_GET['username']);
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://instagram-bulk-profile-scrapper.p.rapidapi.com/clients/api/ig/ig_profile?ig=dunkizabora&response_type=feeds",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: instagram-bulk-profile-scrapper.p.rapidapi.com",
        "x-rapidapi-key: 1fcb997f25mshbde5dfeab9feabdp177c57jsn49ab5784887c"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo json_encode(['error' => "cURL Error #: $err"]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['feeds']) && is_array($data['feeds'])) {
    $feeds = array_map(function($feedItem) {
        return [
            'media' => $feedItem['media'] ?? null
        ];
    }, $data['feeds']);

    echo json_encode(['feeds' => $feeds]);
} else {
    echo json_encode(['error' => 'No feed data found.']);
}
