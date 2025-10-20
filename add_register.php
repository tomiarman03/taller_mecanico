<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style_add_register.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Gestor de vehiculos</title>
</head>
<body>
    <h1>ALTA DE VEHÍCULO</h1>
    <div class="form">
        <form action="add_register.php" method="post">
            <div class="form_info">
                <div class="form_container">
                    <h3>Datos del cliente</h3>
                    <div class="inputs_container">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" required>
                        <label for="surname">Apellido</label>
                        <input type="text" name="surname" required>
                        <label for="phone">Teléfono</label>
                        <input type="number" name="phone" required>
                    </div>
                </div>
                <div class="form_container">
                    <h3>Datos del vehículo</h3>
                    <div class="inputs_container">
                        <label for="brand">Marca</label>
                        <input type="text" name="brand" required>
                        <label for="model">Modelo</label>
                        <input type="text" name="model" required>
                        <label for="year_model">Año</label>
                        <input type="number" name="year_model">
                        <label for="patent">Patente</label>
                        <input type="text" name="patent" required>                        
                        <label for="mileage">Kilometraje</label>
                        <input type="number" name="mileage" required>
                    </div>
                </div>
                <div class="form_container">    
                    <h3>Motivo y fecha de ingreso</h3>
                    <div class="inputs_container">
                        <label for="descript">Descripción</label>
                        <input type="text" name="descript">
                        <label for="entry_date">Fecha de ingreso</label>
                        <input type="date" name="entry_date" required>
                    </div>
                </div>
            </div>
            <div class="form_answer">
                <?php
                    include 'connection.php';

                    $message = '';

                    if(isset($_POST['submit'])){

                        $patent = strtoupper(trim($_POST['patent']));
                        $pattern = "/^([A-Z]{3}[0-9]{3}|[A-Z]{2}[0-9]{3}[A-Z]{2})$/";

                        if (!preg_match($pattern, $patent)) {
                            $message = "<div class='error_txt'>Patente inválida. Debe ser 'ABC123' o 'AB123CD'</div>";
                        } else {
                            $name = $_POST['name'];
                            $surname = $_POST['surname'];
                            $phone = $_POST['phone'];
                            $brand = $_POST['brand'];
                            $model = $_POST['model'];
                            $year_model = $_POST['year_model'];
                            $mileage = $_POST['mileage'];
                            $descript = $_POST['descript'];
                            $entry_date = $_POST['entry_date'];

                            try {
                                $stmt = $db->prepare('INSERT INTO clients (name, surname, phone) VALUES (?, ?, ?)');
                                $stmt->execute([$name, $surname, $phone]);
                                $id_client = $db->lastInsertId();

                                $stmt = $db->prepare('INSERT INTO vehicles (id_client, brand, model, year_model, patent) VALUES (?, ?, ?, ?, ?)');
                                $stmt->execute([$id_client, $brand, $model, $year_model, $patent]);
                                $id_vehicle = $db->lastInsertId();

                                $stmt = $db->prepare('INSERT INTO register (id_client, id_vehicle, mileage, descript, entry_date) VALUES (?, ?, ?, ?, ?)');
                                $success = $stmt->execute([$id_client, $id_vehicle, $mileage,  $descript, $entry_date]);

                                if($success){
                                    $message = "<div class='success_txt'>Vehículo ingresado con éxito</div>";
                                } else {
                                    $message = "<div class='error_txt'>Error: " . $stmt->errorInfo()[2] . "</div>";
                                }

                            } catch (Exception $e) {
                                $message = "<div class='error_txt'>Error: " . $e->getMessage() . "</div>";
                            }
                        }
                    }
                ?>
                <?php echo $message; ?>
                <input class="submit_btn" type="submit" name="submit" value="Ingresar">
            </div>
        </form>
    </div>
    <div class="history_btn_container">
        <button class="history_btn" onclick="window.location.href='index.php'">Volver</button>
    </div>
</body>
</html>