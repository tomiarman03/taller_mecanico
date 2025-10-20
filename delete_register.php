<?php
    include("connection.php");

    if (isset($_POST['id_vehicle'])) {
        $id_vehicle = (int)$_POST['id_vehicle'];

        // Eliminar registros relacionados
        $stmt = $db->prepare("DELETE FROM register WHERE id_vehicle = ?");
        $stmt->execute([$id_vehicle]);

        $stmt = $db->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->execute([$id_vehicle]);

        $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
        header("Location: $redirect?deleted=1");
        exit;
    }
?>
