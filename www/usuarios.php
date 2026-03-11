<?php
require __DIR__ . "/db.php";

$conn->query("CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
)");

$conn->query("INSERT INTO usuarios (nombre)
              SELECT 'Usuario demo'
              WHERE NOT EXISTS (SELECT 1 FROM usuarios)");

$res = $conn->query("SELECT id, nombre FROM usuarios ORDER BY id DESC");
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Usuarios</title></head>
<body>
  <h1>Usuarios</h1>
  <ul>
    <?php while ($row = $res->fetch_assoc()): ?>
      <li>#<?= (int)$row["id"] ?> — <?= htmlspecialchars($row["nombre"]) ?></li>
    <?php endwhile; ?>
  </ul>
</body>
</html>
