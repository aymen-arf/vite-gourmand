<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $mongoClient = new MongoDB\Client("mongodb://127.0.0.1:27017");
    $mongoDb = $mongoClient->selectDatabase('vite_gourmand_nosql');
    $mongoStats = $mongoDb->selectCollection('stats_commandes');

    $testCount = $mongoStats->countDocuments([]);
} catch (Exception $e) {
    die("Erreur MongoDB : " . $e->getMessage());
}