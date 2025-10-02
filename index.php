

<?php
    include("connection.php");

    $patentFilter = "";
    $params = [];

    if (isset($_GET['patent']) && !empty($_GET['patent'])) {
        $patentFilter = " WHERE v.patent LIKE ?";
        $params[] = "%" . $_GET['patent'] . "%";
    }

    $limit = 8; // registros por página
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    // Consulta principal
    $query = "SELECT c.name, c.surname, c.phone, v.brand, v.model, v.year_model, v.patent, r.mileage, r.entry_date, r.descript
            FROM register r
            INNER JOIN clients c ON r.id_client = c.id
            INNER JOIN vehicles v ON r.id_vehicle = v.id
            $patentFilter
            ORDER BY r.entry_date DESC
            LIMIT ? OFFSET ?";

    $params[] = $limit;
    $params[] = $offset;

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style_index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=edit" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>Gestor de vehiculos</title>
</head>
<body>
    <div class="main_container">
        <h1>GESTOR TALLER</h1>
        <form  class="search_form" method="GET" action="index.php">
            <input type="text" class="search_input" name="patent" placeholder="Buscar por patente" value="<?php echo isset($_GET['patent']) ? $_GET['patent'] : ''; ?>">
            <div class="button_search_content">
                <button type="submit" class="input_btns">Buscar</button>
                <button type="button" class="input_btns" onclick="window.location.href='index.php'">Borrar filtro</button>
            </div>
        </form>
        <div class="background_table">
            <?php
                 if ($results && count($results) > 0) {
                    echo "<table class='register_table' style='border: 2px inset black; border-collapse: collapse;'>";
                    echo "<tr>
                            <td class='main_camp_content'><div class='camp_table'>Nombre</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Apellido</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Teléfono</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Marca</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Modelo</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Año</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Patente</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Kilometraje</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Fecha de ingreso</div></td>
                            <td class='main_camp_content'><div class='camp_table'>Descripción</div></td>
                            <td class='main_camp_content'><div class='camp_table'></div></td>
                        </tr>";
                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td><div class='camp_table'>" . $row['name'] . "</div></td>";
                        echo "<td><div class='camp_table'>" . $row['surname'] . "</div></td>";
                        echo "<td><div class='camp_table'>" . $row['phone'] . "</div></td>";
                        echo "<td><div class='camp_table'>" . $row['brand'] . "</div></td>";
                        echo "<td><div class='camp_table'>" . $row['model'] . "</div></td>";
                        echo "<td><div class='camp_table'>" . $row['year_model'] . "</div></td>";
                        echo "<td><div class='camp_table'>" . $row['patent'] . "</div></td>";
                        echo "<td><div class='camp_table'>" . number_format($row['mileage'], 0, ',', '.') . "Km" . "</div></td>";
                        echo "<td><div class='camp_table'>" . date("d/m/Y", strtotime($row['entry_date'])) . "</div></td>";
                        echo "<td><div class='camp_table'>" . $row['descript'] . "</div></td>";
                        echo "<td class='edit_camp_table'><div class='camp_table'><button type='button' class='edit_btn' onclick=\"window.location.href='register_edit.php'\"><img src='images/edit_icon.png'></button></div></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No se encontraron registros.</p>";
                }    
            ?>
        </div>
        <div>
            <?php
                $countQuery = "SELECT COUNT(*) as total
                               FROM register r
                               INNER JOIN clients c ON r.id_client = c.id
                               INNER JOIN vehicles v ON r.id_vehicle = v.id
                               " . ($patentFilter ?: "");

                $countStmt = $db->prepare($countQuery);
                $countStmt->execute(isset($params) ? array_slice($params, 0, count($params)-2) : []);
                $totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
                $totalPages = ceil($totalRows / $limit);

                echo "<div class='pagination'>";
                for ($i = 1; $i <= $totalPages; $i++) {
                    $url = "register.php?page=$i";
                    if (!empty($_GET['patent'])) {
                        $url .= "&patent=" . urlencode($_GET['patent']);
                    }
                    echo "<a class='page_numbers' href='$url' style='margin: 0 5px; " . 
                        ($i == $page ? "font-weight:bold; color:#fc5555;" : "") . "'>$i</a>";
                }
                echo "</div>";
            ?>
        </div>
        <div class="back_btn_container">
            <button class="back_btn" onclick="window.location.href='register.php'">Ingresar un vehículo</button>
        </div>
    </div>
    
</body>
</html>