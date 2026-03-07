<?php

/**
 * Script de nettoyage pour compléter GenreVehicule dans
 * data/conseilauto_data_20260225_230502.json en se basant
 * sur les autres lignes renseignées.
 *
 * Utilisation :
 *   php scripts/fill_genrevehicule_from_examples.php
 *
 * Résultat :
 *   Crée data/conseilauto_data_20260225_230502_filled.json
 *   avec les GenreVehicule complétés lorsque possible.
 */

$root = dirname(__DIR__);
$input  = $root . '/data/conseilauto_data_20260225_230502.json';
$output = $root . '/data/conseilauto_data_20260225_230502_filled.json';

if (!file_exists($input)) {
    fwrite(STDERR, "Fichier introuvable : {$input}\n");
    exit(1);
}

$json = file_get_contents($input);
$data = json_decode($json, true);

if (!is_array($data)) {
    fwrite(STDERR, "JSON invalide dans {$input}\n");
    exit(1);
}

// 1. Construire une table de correspondance clé -> distribution des GenreVehicule
// La clé agrège plusieurs champs pour identifier un même type de véhicule.
function build_key(array $row): string
{
    $parts = [
        strtoupper(trim((string)($row['Marque'] ?? ''))),
        strtoupper(trim((string)($row['Modele'] ?? ''))),
        strtoupper(trim((string)($row['NomCommercial'] ?? ''))),
        strtoupper(trim((string)($row['Energie'] ?? ''))),
        strtoupper(trim((string)($row['concessionnaire'] ?? ''))),
    ];

    return implode('|', $parts);
}

$distributions = [];

foreach ($data as $row) {
    $genreRaw = isset($row['GenreVehicule']) ? (string)$row['GenreVehicule'] : '';
    $genre = trim(str_replace(["\r", "\n"], '', $genreRaw));

    if ($genre === '') {
        continue;
    }

    $key = build_key($row);
    if (!isset($distributions[$key])) {
        $distributions[$key] = [];
    }

    if (!isset($distributions[$key][$genre])) {
        $distributions[$key][$genre] = 0;
    }
    $distributions[$key][$genre]++;
}

// 2. Pour chaque ligne avec GenreVehicule vide, tenter de déduire à partir de la distribution
$filled = 0;
foreach ($data as $i => $row) {
    $genreRaw = isset($row['GenreVehicule']) ? (string)$row['GenreVehicule'] : '';
    $genre = trim(str_replace(["\r", "\n"], '', $genreRaw));

    if ($genre !== '') {
        continue; // déjà renseigné
    }

    $key = build_key($row);
    if (!isset($distributions[$key]) || empty($distributions[$key])) {
        continue; // aucune information pour ce type de véhicule
    }

    // Prendre le GenreVehicule le plus fréquent pour cette clé
    arsort($distributions[$key]);
    $bestGenre = array_key_first($distributions[$key]);

    $data[$i]['GenreVehicule'] = $bestGenre;
    $filled++;
}

// 3. Écrire le nouveau fichier JSON
$encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($encoded === false) {
    fwrite(STDERR, "Erreur lors de l'encodage JSON\n");
    exit(1);
}

if (file_put_contents($output, $encoded) === false) {
    fwrite(STDERR, "Impossible d'écrire dans {$output}\n");
    exit(1);
}

fwrite(STDOUT, "Genres complétés pour {$filled} lignes. Fichier généré : {$output}\n");

