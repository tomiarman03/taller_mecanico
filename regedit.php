<?php
    include 'connection.php';

    $message = "";

    $id_vehicle = isset($_GET['id_vehicle']) ? (int)$_GET['id_vehicle'] : (isset($_POST['id_vehicle']) ? (int)$_POST['id_vehicle'] : 0);

    if(isset($_POST['modify_btn'])){
        $patent = strtoupper(trim($_POST['patent']));
        $pattern = "/^([A-Z]{3}[0-9]{3}|[A-Z]{2}[0-9]{3}[A-Z]{2})$/";

        if (!preg_match($pattern, $patent)) {
            $message = "<div class='error_txt'>Patente inválida. Debe ser 'ABC123' o 'AB123CD'</div>";
        } else{
            $id_client = $_POST['id_client'];
            $id_vehicle = $_POST['id_vehicle'];
            $id_register = $_POST['id_register'];

            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $phone = $_POST['phone'];
            $brand = $_POST['brand'];
            $model = $_POST['model'];
            $year_model = $_POST['year_model'];
            $mileage = $_POST['mileage'];
            $entry_date = $_POST['entry_date'];
            $descript = $_POST['descript'];

            $updateClient = "UPDATE clients SET name = ?, surname = ?, phone = ? WHERE id = ?";
            $stmtClient = $db->prepare($updateClient);
            $stmtClient->execute([$name, $surname, $phone, $id_client]);

            $updateVehicle = "UPDATE vehicles SET brand = ?, model = ?, year_model = ?, patent = ? WHERE id = ?";
            $stmtVehicle = $db->prepare($updateVehicle);
            $stmtVehicle->execute([$brand, $model, $year_model, $patent, $id_vehicle]);

            $updateRegister = "UPDATE register SET mileage = ?, entry_date = ?, descript = ? WHERE id = ?";
            $stmtRegister = $db->prepare($updateRegister);
            $stmtRegister->execute([$mileage, $entry_date, $descript, $id_register]);
            
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
    LIMIT 1";

    $stmt = $db->prepare($query);
    $stmt->execute([$id_vehicle]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
                <input type="hidden" name="id_client" value="<?php echo $row['id_client']; ?>">
                <input type="hidden" name="id_vehicle" value="<?php echo $row['id_vehicle']; ?>">
                <input type="hidden" name="id_register" value="<?php echo $row['id_register']; ?>">   
                <?php
                    if($row){
                        echo "<table class='mod_table' style='border: 2px inset black; border-collapse: collapse;'>";
                        echo "<tr>
                            <td class='main_camp_table'><div class='camp_table'>Nombre</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Apellido</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Teléfono</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Marca</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Modelo</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Año</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Patente</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Kilometraje</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Fecha de ingreso</div></td>
                            <td class='main_camp_table'><div class='camp_table'>Descripción</div></td>
                        </tr>";
                        echo"<tr>
                            <td><div class='camp_table'><input type='text' name='name' value='" . htmlspecialchars($row['name']) . "'></div></td>
                            <td><div class='camp_table'><input type='text' name='surname' value='" . htmlspecialchars($row['surname']) . "'></div></td>
                            <td><div class='camp_table'><input type='number' name='phone' value='" . htmlspecialchars($row['phone']) . "'></div></td>
                            <td><div class='camp_table'><input type='text' name='brand' value='" . htmlspecialchars($row['brand']) . "'></div></td>
                            <td><div class='camp_table'><input type='text' name='model' value='" . htmlspecialchars($row['model']) . "'></div></td>
                            <td><div class='camp_table'><input type='number' name='year_model' value='" . htmlspecialchars($row['year_model']) . "'></div></td>
                            <td><div class='camp_table'><input type='text' name='patent' value='" . htmlspecialchars($row['patent']) . "'></div></td>
                            <td><div class='camp_table'><input type='number' name='mileage' value='" . htmlspecialchars($row['mileage']) . "'></div></td>
                            <td><div class='camp_table'><input type='date' name='entry_date' value='" . htmlspecialchars($row['entry_date']) . "'></div></td>
                            <td><div class='camp_table'><input type='text' name='descript' value='" . htmlspecialchars($row['descript']) . "'></div></td>
                        </tr>";
                        echo "</table>";
                    }
                ?>
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