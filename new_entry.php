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
    <div class="form_container">
        <form action="new_entry.php">
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
        </form>
    </div>
</body>
</html>