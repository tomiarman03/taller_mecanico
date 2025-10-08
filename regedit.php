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
        <form class="modify_form" action="regedit.php" method="POST">
            <?php
                include 'connection.php';

                $id_vehicle = isset($_GET['id_vehicle']) ? (int)$_GET['id_vehicle'] : 0;

                $query = "SELECT c.name, c.surname, c.phone, v.id, v.brand, v.model, v.year_model, v.patent, r.mileage, r.entry_date, r.descript
                FROM register r
                INNER JOIN clients c ON r.id_client = c.id
                INNER JOIN vehicles v ON r.id_vehicle = v.id
                WHERE v.id = ?
                LIMIT 1";

                $stmt = $db->prepare($query);
                $stmt->execute([$id_vehicle]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
        </form>
    </div>
</body>
</html>