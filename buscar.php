<?php
session_start();
require_once 'db/conexion.php';

$query = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : '';
$resultados_articulos = [];
$resultados_usuarios = [];

if($query != '') {
    // Buscar artículos por título
    $sql_art = "SELECT a.*, u.nombre as autor_nombre, u.avatar, c.nombre as cat_nombre
                 FROM articulos a
                 JOIN usuarios u ON a.autor_id = u.id
                 JOIN categorias c ON a.categoria_id = c.id
                 WHERE a.titulo LIKE '%$query%' OR a.contenido LIKE '%$query%'
                 ORDER BY a.fecha DESC";
    $resultados_articulos = $conexion->query($sql_art);

    // Buscar usuarios por nombre o username
    $sql_users = "SELECT * FROM usuarios
                   WHERE nombre LIKE '%$query%' OR username LIKE '%$query%'";
    $resultados_usuarios = $conexion->query($sql_users);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscar: <?php echo htmlspecialchars($query); ?> · Arcana</title>
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
          <input type="search" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Buscar artículos, autores..." style="background:rgba(26,42,58,0.5);border:1px solid var(--color-border);border-radius:6px;padding:8px 12px;color:var(--color-text);font-family:var(--font-ui);font-size:0.85rem;width:220px;">
          <button type="submit" class="nav-search-btn">⌕</button>
        </form>
        <a href="suscripciones.php" class="btn btn-primary nav-cta">Suscribirse</a>
      </div>
    </div>
  </nav>

  <div class="page active">
    <div class="subpage-hero">
      <div class="container">
        <p class="hero-eyebrow">✦ Búsqueda</p>
        <h1 class="subpage-title">Resultados para "<?php echo htmlspecialchars($query); ?>"</h1>
        <p class="subpage-subtitle">Se encontraron <?php echo ($resultados_articulos ? $resultados_articulos->num_rows : 0) + ($resultados_usuarios ? $resultados_usuarios->num_rows : 0); ?> resultados</p>
      </div>
    </div>

    <div class="container" style="padding: 56px 0 100px;">

      <!-- Resultados Usuarios -->
      <?php if($resultados_usuarios && $resultados_usuarios->num_rows > 0): ?>
      <div class="config-section" style="margin-bottom: 48px;">
        <h3 class="config-section-title">Usuarios encontrados</h3>
        <div class="authors-grid">
          <?php while($user = $resultados_usuarios->fetch_assoc()): ?>
          <a href="usuario.php?user=<?php echo $user['username']; ?>" style="text-decoration:none;">
            <div class="author-sub-card">
              <div class="author-avatar" style="width:52px;height:52px;font-size:1.2rem;"><?php echo $user['avatar']; ?></div>
              <div class="author-sub-info">
                <strong><?php echo htmlspecialchars($user['nombre']); ?></strong>
                <span>@<?php echo $user['username']; ?></span>
              </div>
            </div>
          </a>
          <?php endwhile; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Resultados Artículos -->
      <?php if($resultados_articulos && $resultados_articulos->num_rows > 0): ?>
      <div class="config-section">
        <h3 class="config-section-title">Artículos encontrados</h3>
        <div class="articles-grid">
          <?php while($art = $resultados_articulos->fetch_assoc()): ?>
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
              <p class="article-excerpt"><?php echo strip_tags(substr($art['contenido'], 0, 120)); ?>...</p>
              <div class="article-footer">
                <div class="author-mini">
                  <div class="author-dot"><?php echo $art['avatar']; ?></div>
                  <span><?php echo $art['autor_nombre']; ?></span>
                </div>
                <div class="read-info">⏱ <?php echo $art['tiempo_lectura']; ?> min</div>
              </div>
              <a href="articulo.php?id=<?php echo $art['id']; ?>" style="position:absolute;inset:0;z-index:1;"></a>
            </div>
          </article>
          <?php endwhile; ?>
        </div>
      </div>
      <?php elseif($query != ''): ?>
      <div style="text-align:center;padding:60px 0;color:var(--color-text-muted);">
        <p style="font-size:1.2rem;margin:0 0 12px;">No se encontraron resultados para "<?php echo htmlspecialchars($query); ?>"</p>
        <p>Intenta con otros términos de búsqueda</p>
      </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- FOOTER (IGUAL AL ORIGINAL) -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="footer-logo">
            <img src="assets/img/ico.png" class="footer-logo-img" alt="">
            Arcana
          </div>
          <p>Revista digital de fantasía. Narrativa, arte y conocimiento sobre mundos imaginarios.</p>
          <p class="footer-innadi">Un proyecto de <strong><a href="https://www.innadi.com/">INNADI · Escuela de Diseño</a></strong></p>
        </div>
        <div class="footer-col">
          <h4>Contenido</h4>
          <ul>
            <li><a href="categorias.php">Últimas publicaciones</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Comunidad</h4>
          <ul>
            <li><a href="suscripciones.php">Suscripciones</a></li>
            <li><a href="usuario.php">Usuarios</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2025 Arcana · Revista de Fantasía · Proyecto INNADI</p>
      </div>
    </div>
  </footer>

</body>
</html>
<?php $conexion->close(); ?>
