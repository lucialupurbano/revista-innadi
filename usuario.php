<?php
session_start();
require_once 'db/conexion.php';

$username = isset($_GET['user']) ? $_GET['user'] : 'svrael';

// Obtener usuario
$sql_user = "SELECT * FROM usuarios WHERE username = '$username'";
$usuario = $conexion->query($sql_user)->fetch_assoc();

if(!$usuario) {
    header("Location: index.php");
    exit();
}

// Artículos del usuario
$user_id = $usuario['id'];
$sql_articulos = "SELECT a.*, c.nombre as cat_nombre, c.slug as cat_slug
                   FROM articulos a
                   JOIN categorias c ON a.categoria_id = c.id
                   WHERE a.autor_id = $user_id
                   ORDER BY a.fecha DESC";
$articulos = $conexion->query($sql_articulos);

// Verificar si el usuario actual sigue a este autor (simulado)
$siguiendo = isset($_SESSION['user_id']) && $conexion->query("SELECT * FROM suscripciones_usuarios WHERE usuario_id = {$_SESSION['user_id']} AND autor_id = $user_id")->num_rows > 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($usuario['nombre']); ?> · Arcana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/revista.css">
  <link rel="stylesheet" href="assets/css/estilo.css">
  <script src="assets/js/java.js"></script>
</head>
<body>

  <!-- NAVEGACIÓN -->
  <nav class="nav" id="nav">
    <div class="nav-inner">
      <a href="index.php" class="nav-logo">
        <img src="assets/img/ico.png" class="nav-logo-img" alt="Arcana">
        <span class="nav-logo-text">Arcana</span>
      </a>
      <ul class="nav-links">
        <li><a href="index.php" class="nav-link">Inicio</a></li>
        <li><a href="categorias.php" class="nav-link">Publicaciones</a></li>
        <li><a href="usuario.php" class="nav-link">Usuarios</a></li>
      </ul>
      <div class="nav-actions">
        <form action="buscar.php" method="GET" style="display:flex;gap:8px;">
          <input type="search" name="q" placeholder="Buscar..." style="background:rgba(26,42,58,0.5);border:1px solid var(--color-border);border-radius:6px;padding:8px 12px;color:var(--color-text);font-family:var(--font-ui);font-size:0.85rem;width:160px;">
          <button type="submit" class="nav-search-btn">⌕</button>
        </form>
        <a href="suscripciones.php" class="btn btn-primary nav-cta">Suscribirse</a>
      </div>
    </div>
  </nav>

  <div class="page active">
    <div class="subpage-hero">
      <div class="container">
        <p class="hero-eyebrow">✦ Usuario</p>
        <h1 class="subpage-title"><?php echo htmlspecialchars($usuario['nombre']); ?></h1>
        <p class="subpage-subtitle">@<?php echo $usuario['username']; ?></p>
      </div>
    </div>

    <div class="container config-layout">
      <aside class="config-sidebar">
        <div style="text-align:center;padding:20px;">
          <div class="profile-avatar" style="width:80px;height:80px;font-size:2rem;margin:0 auto 16px;"><?php echo $usuario['avatar']; ?></div>
          <h3 style="margin:0 0 8px;color:var(--color-parchment);"><?php echo htmlspecialchars($usuario['nombre']); ?></h3>
          <p style="font-size:0.85rem;color:var(--color-text-muted);margin:0 0 16px;">@<?php echo $usuario['username']; ?></p>
          <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user_id): ?>
          <button class="btn <?php echo $siguiendo?'btn-ghost':'btn-primary'; ?>" onclick="toggleFollow(<?php echo $user_id; ?>, this)">
            <?php echo $siguiendo?'✓ Siguiendo':'+ Seguir'; ?>
          </button>
          <?php endif; ?>
        </div>
      </aside>

      <main class="config-main">
        <div class="config-section">
          <h3 class="config-section-title">Sobre mí</h3>
          <p style="color:var(--color-text-soft);line-height:1.7;"><?php echo htmlspecialchars($usuario['bio']); ?></p>
        </div>

        <div class="config-section">
          <h3 class="config-section-title">Artículos publicados</h3>
          <div class="articles-grid" style="grid-template-columns:repeat(2,1fr);">
            <?php while($art = $articulos->fetch_assoc()): ?>
            <article class="article-card">
              <div class="article-img">
                <div class="article-img-art">
                  <svg viewBox="0 0 120 120" fill="none">
                    <circle cx="60" cy="60" r="55" stroke="rgba(240,217,138,0.15)" stroke-width="1"/>
                    <circle cx="60" cy="60" r="30" stroke="#F0D98A" stroke-width="1" fill="none" opacity="0.5"/>
                  </svg>
                </div>
              </div>
              <div class="article-body">
                <div class="article-meta">
                  <span class="tag"><?php echo $art['cat_nombre']; ?></span>
                  <span class="meta-date"><?php echo date('d M Y', strtotime($art['fecha'])); ?></span>
                </div>
                <h3 class="article-title"><?php echo htmlspecialchars($art['titulo']); ?></h3>
                <div class="article-footer">
                  <div class="read-info">⏱ <?php echo $art['tiempo_lectura']; ?> min</div>
                  <div class="read-info">👁 <?php echo $art['visitas']; ?></div>
                </div>
                <a href="articulo.php?id=<?php echo $art['id']; ?>" style="position:absolute;inset:0;z-index:1;"></a>
              </div>
            </article>
            <?php endwhile; ?>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="footer-logo">
            <img src="assets/img/ico.png" class="footer-logo-img" alt="">
            Arcana
          </div>
          <p>Revista digital de fantasía.</p>
          <p class="footer-innadi">Un proyecto de <strong><a href="https://www.innadi.com/">INNADI</a></strong></p>
        </div>
        <div class="footer-bottom">
          <p>© 2025 Arcana · Revista de Fantasía · Proyecto INNADI</p>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
<?php $conexion->close(); ?>
