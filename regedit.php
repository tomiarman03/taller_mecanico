<?php
    include 'connection.php';

    $message = "";

    $id_register = isset($_GET['id_vehicle']) ? (int)$_GET['id_vehicle'] : (isset($_POST['id_vehicle']) ? (int)$_POST['id_vehicle'] : 0);

    if(isset($_POST['modify_btn'])){
        $patent = strtoupper(trim($_POST['patent']));
        $pattern = "/^([A-Z]{3}[0-9]{3}|[A-Z]{2}[0-9]{3}[A-Z]{2})$/";

        if (!preg_match($pattern, $patent)) {
            $message = "<div class='error_txt'>Patente inválida. Debe ser 'ABC123' o 'AB123CD'</div>";
        } else{
            $id_client = $_POST['id_client'];
            $id_vehicle = $_POST['id_vehicle'];

            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $phone = $_POST['phone'];
            $brand = $_POST['brand'];
            $model = $_POST['model'];
            $year_model = $_POST['year_model'];

            $updateClient = "UPDATE clients SET name = ?, surname = ?, phone = ? WHERE id = ?";
            $stmtClient = $db->prepare($updateClient);
            $stmtClient->execute([$name, $surname, $phone, $id_client]);

            $updateVehicle = "UPDATE vehicles SET brand = ?, model = ?, year_model = ?, patent = ? WHERE id = ?";
            $stmtVehicle = $db->prepare($updateVehicle);
            $stmtVehicle->execute([$brand, $model, $year_model, $patent, $id_vehicle]);

            foreach ($_POST['id_registers'] as $id) {
                $mileage    = $_POST["mileage_$id"] ?? null;
                $entry_date = $_POST["entry_date_$id"] ?? null;
                $descript   = $_POST["descript_$id"] ?? null;

                if ($mileage && $entry_date && $descript) {
                    $stmtRegister = $db->prepare("UPDATE register SET mileage = ?, entry_date = ?, descript = ? WHERE id = ?");
                    $stmtRegister->execute([$mileage, $entry_date, $descript, $id]);
                }
            }

            $message = "<div class='success_txt'>Registro modificado correctamente.</div>";
        }
    }

    $query = "SELECT 
        c.id AS id_client, c.name, c.surname, c.phone,
        v.id AS id_vehicle, v.brand, v.model, v.year_model, v.patent,
        r.id AS id_register, r.mileage, r.entry_date, r.descript
    FROM register r
    INNER JOIN clients c ON r.id_client = c.id
    INNER JOIN vehicles v ON r.id_vehicle = v.id
    WHERE v.id = ?
    ORDER BY r.entry_date ASC";

    $id_vehicle = isset($_GET['id_vehicle']) ? (int)$_GET['id_vehicle'] : (isset($_POST['id_vehicle']) ? (int)$_POST['id_vehicle'] : 0);

    $stmt = $db->prepare($query);
    $stmt->execute([$id_vehicle]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rowCount = count($rows);
    $rowPrincipal = $rows[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style_regedit.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=edit" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>Gestor de vehiculos</title>
</head>
<body>
    <div class="main_container">
        <h1>MODIFICAR REGISTRO</h1>
        <div class="modify_form">
            <form action="regedit.php" method="POST">
                <input type="hidden" name="id_client" value="<?= $rowPrincipal['id_client'] ?>">
                <input type="hidden" name="id_vehicle" value="<?= $rowPrincipal['id_vehicle'] ?>">
                <table class="mod_table" style="border: 2px inset black; border-collapse: collapse;">
                    <colgroup>
                        <col style="width: 8%;">   <!-- Nombre -->
                        <col style="width: 8%;">   <!-- Apellido -->
                        <col style="width: 8%;">  <!-- Teléfono -->
                        <col style="width: 8%;">   <!-- Marca -->
                        <col style="width: 10%;">   <!-- Modelo -->
                        <col style="width: 5%;">   <!-- Año -->
                        <col style="width: 5%;">   <!-- Patente -->
                        <col style="width: 6%;">  <!-- Kilometraje -->
                        <col style="width: 8%;">  <!-- Fecha de ingreso -->
                        <col style="width: 26%;">  <!-- Descripción -->
                    </colgroup>
                    <tr>
                        <td class="main_camp_table">Nombre</td>
                        <td class="main_camp_table">Apellido</td>
                        <td class="main_camp_table">Teléfono</td>
                        <td class="main_camp_table">Marca</td>
                        <td class="main_camp_table">Modelo</td>
                        <td class="main_camp_table">Año</td>
                        <td class="main_camp_table">Patente</td>
                        <td class="main_camp_table">Kilometraje</td>
                        <td class="main_camp_table">Fecha de ingreso</td>
                        <td class="main_camp_table">Descripción</td>
                    </tr>
                    <?php
                        $first = true;
                        foreach ($rows as $row) {
                            echo "<tr>";

                            if ($first) {
                                echo "<td rowspan='{$rowCount}'><input type='text' name='name' value='" . htmlspecialchars($row['name']) . "'></td>";
                                echo "<td rowspan='{$rowCount}'><input type='text' name='surname' value='" . htmlspecialchars($row['surname']) . "'></td>";
                                echo "<td rowspan='{$rowCount}'><input type='number' name='phone' value='" . htmlspecialchars($row['phone']) . "'></td>";
                                echo "<td rowspan='{$rowCount}'><input type='text' name='brand' value='" . htmlspecialchars($row['brand']) . "'></td>";
                                echo "<td rowspan='{$rowCount}'><input type='text' name='model' value='" . htmlspecialchars($row['model']) . "'></td>";
                                echo "<td rowspan='{$rowCount}'><input type='number' name='year_model' value='" . htmlspecialchars($row['year_model']) . "'></td>";
                                echo "<td rowspan='{$rowCount}'><input type='text' name='patent' value='" . htmlspecialchars($row['patent']) . "'></td>";
                                $first = false;
                            }
                            echo "<input type='hidden' name='id_registers[]' value='{$row['id_register']}'>";
                            echo "<td><input type='number' name='mileage_{$row['id_register']}' value='" . htmlspecialchars($row['mileage']) . "'></td>";
                            echo "<td><input type='date' name='entry_date_{$row['id_register']}' value='" . htmlspecialchars($row['entry_date']) . "'></td>";
                            echo "<td><input type='text' name='descript_{$row['id_register']}' value='" . htmlspecialchars($row['descript']) . "'></td>";

                            echo "</tr>";
                        }
                    ?>
                </table>
        </div>
            <div class="msg_container">
                <?php if (isset($message)) echo $message; ?>
            </div>
                <div class="btns_container">
                    <button type="submit" class="input_btns" name="modify_btn">Modificar</button>
                    <button type="button" class="input_btns" onclick="window.location.href='new_entry.php?id_vehicle=<?= $row['id_vehicle'] ?>'">Ingresar nuevo registro</button>
                    <button type="button" class="input_btns" onclick="window.location.href='index.php'">Volver</button>
                </div>
            </form>
    </div>
</body>
</html>