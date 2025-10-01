<?php
$dbFile = __DIR__ . '/database/taller.db';

try {
    $db = new PDO("sqlite:" . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("
        CREATE TABLE IF NOT EXISTS clients (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        surname TEXT NOT NULL
    )
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS vehicles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_client INTEGER NOT NULL,
        brand TEXT NOT NULL,
        model TEXT NOT NULL,
        year_model INTEGER DEFAULT NULL,
        patent TEXT NOT NULL
    )
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS register (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_client INTEGER DEFAULT NULL,
        id_vehicle INTEGER DEFAULT NULL,
        mileage INTEGER NOT NULL,
        entry_date date NOT NULL,
        descript varchar(100) DEFAULT NULL
    )
    ");

} catch (Exception $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>