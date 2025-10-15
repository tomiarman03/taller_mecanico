<?php
if (isset($_GET['id_vehicle'])) {
    $id_vehicle = $_GET['id_vehicle'];
} else {
    die("No se especificó el vehículo.");
}
?>

<?php
    include 'connection.php';
    if (isset($_POST['submit'])) {
        try{
            $mileage = $_POST['mileage'];
            $entry_date = $_POST['entry_date'];
            $descript = $_POST['descript'];

            $stmt = $db->prepare("SELECT id_client FROM vehicles WHERE id = ?");
            $stmt->execute([$id_vehicle]);
            $id_client = $stmt->fetchColumn();

            $insert = "INSERT INTO register (id_client, id_vehicle, mileage, entry_date, descript) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($insert);
            $stmt->execute([$id_client, $id_vehicle, $mileage, $entry_date, $descript]);

            $message = "<div class='success_txt'>Ingreso agregado correctamente.</div>";
        }catch (Exeption $e){
            $message = "<div class='error_txt'>Error al guardar el ingreso: " . $e->getMessage() . "</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=git, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style_new_entry.css">
    <title>Nuevo ingreso</title>
</head>
<body>
    <h1>NUEVO INGRESO</h1>
    <div class="">
        <form method="post" action="new_entry.php?id_vehicle=<?= $id_vehicle ?>">
            <div class="form_container">
                <div class="camp_container">
                    <label for="mileage">Kilometraje</label>
                    <input type="number" name="mileage" required>
                </div>
                <div class="camp_container">
                    <label for="entry_date">Fecha de ingreso</label>
                    <input type="date" name="entry_date" required>
                </div>
                <div class="camp_container">
                    <label for="descript">Descripción</label>
                    <input type="text" name="descript" required>
                </div>
            </div>
            <div class="msg_container">
                <?php if (isset($message)) echo $message; ?>
            </div>
            <div class="form_btns_container">
                <input type="submit" class="form_btn" name="submit" value="Ingresar">
                <button type="button" class="form_btn" onclick="window.location.href='regedit.php?id_vehicle=<?= $id_vehicle ?>'">Volver</button>
            </div>
        </form>
    </div>
</body>
</html>