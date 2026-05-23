<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$fichier = "colis.json";

if (!file_exists($fichier)) {
    file_put_contents($fichier, "[]");
}

$methode = $_SERVER["REQUEST_METHOD"];

if ($methode === "GET") {
    $colis = file_get_contents($fichier);
    echo $colis;
}

if ($methode === "POST") {
    $body = file_get_contents("php://input");
    $nouveau = json_decode($body, true);
    $colis = json_decode(file_get_contents($fichier), true);
    array_unshift($colis, $nouveau);
    file_put_contents($fichier, json_encode($colis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(["success" => true]);
}

if ($methode === "DELETE") {
    $body = file_get_contents("php://input");
    $data = json_decode($body, true);
    $numero = $data["numero"];
    $colis = json_decode(file_get_contents($fichier), true);
    $colis = array_values(array_filter($colis, function($c) use ($numero) {
        return $c["numero"] !== $numero;
    }));
    file_put_contents($fichier, json_encode($colis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(["success" => true]);
}

if ($methode === "PUT") {
    $body = file_get_contents("php://input");
    $data = json_decode($body, true);
    $colis = json_decode(file_get_contents($fichier), true);
    foreach ($colis as &$c) {
        if ($c["numero"] === $data["numero"]) {
            $c["statut"] = $data["statut"];
            break;
        }
    }
    file_put_contents($fichier, json_encode($colis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(["success" => true]);
}
?>