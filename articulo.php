<?php
session_start();
require_once 'db/conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Obtener artículo
$sql = "SELECT a.*, u.nombre as autor_nombre, u.username, u.bio as autor_bio, u.avatar, c.nombre as cat_nombre, c.slug as cat_slug
        FROM articulos a
        JOIN usuarios u ON a.autor_id = u.id
        JOIN categorias c ON a.categoria_id = c.id
        WHERE a.id = $id";
$articulo = $conexion->query($sql)->fetch_assoc();

if(!$articulo) {
    header("Location: index.php");
    exit();
}

// Incrementar visitas
$conexion->query("UPDATE articulos SET visitas = visitas + 1 WHERE id = $id");

// Artículos relacionados (misma categoría)
$cat_id = $articulo['categoria_id'];
$sql_relacionados = "SELECT a.*, u.nombre as autor_nombre, u.avatar, c.nombre as cat_nombre
                     FROM articulos a
                     JOIN usuarios u ON a.autor_id = u.id
                     JOIN categorias c ON a.categoria_id = c.id
                     WHERE a.categoria_id = $cat_id AND a.id != $id
                     LIMIT 3";
$relacionados = $conexion->query($sql_relacionados);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($articulo['titulo']); ?> · Arcana</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
      <ul class="nav-links" id="navLinks">
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

  <!-- ARTÍCULO -->
  <div class="page active">
    <div class="articulo-hero">
      <div class="container">
        <div class="articulo-breadcrumb">
          <a href="index.php">Inicio</a>
          <span>›</span>
          <a href="categorias.php?cat=<?php echo $articulo['cat_slug']; ?>"><?php echo $articulo['cat_nombre']; ?></a>
          <span>›</span>
          <span>Artículo</span>
        </div>
        <span class="tag" style="margin-bottom:20px;display:inline-flex;"><?php echo $articulo['cat_nombre']; ?></span>
        <h1 class="articulo-titulo"><?php echo htmlspecialchars($articulo['titulo']); ?></h1>
        <div class="articulo-meta-bar">
          <div class="articulo-author-info">
            <div class="author-avatar"><?php echo $articulo['avatar']; ?></div>
            <div>
              <strong><?php echo htmlspecialchars($articulo['autor_nombre']); ?></strong>
              <span>Autor · <?php echo date('d M Y', strtotime($articulo['fecha'])); ?></span>
            </div>
          </div>
          <div class="articulo-stats">
            <span>⏱ <?php echo $articulo['tiempo_lectura']; ?> min de lectura</span>
            <span>·</span>
            <span>👁 <?php echo $articulo['visitas']; ?> lecturas</span>
          </div>
        </div>
      </div>
    </div>

    <div class="container articulo-layout">
      <article class="articulo-body">
        <div class="articulo-cover-img">
          <div class="img-art" style="opacity:0.5;transform:scale(1.5);">
            <div class="img-art-circle"></div>
          </div>
        </div>

        <div class="articulo-content">
          <?php echo $articulo['contenido']; ?>
        </div>

        <div class="articulo-tags-bar">
          <span class="tag">Worldbuilding</span>
          <span class="tag">Narrativa</span>
          <span class="tag">Técnica</span>
          <span class="tag">Fantasía</span>
        </div>

        <div class="articulo-nav">
          <a href="categorias.php" class="articulo-nav-btn">← Volver a publicaciones</a>
          <?php
          $next = $conexion->query("SELECT id FROM articulos WHERE id > $id LIMIT 1")->fetch_assoc();
          if($next):
          ?>
          <a href="articulo.php?id=<?php echo $next['id']; ?>" class="articulo-nav-btn">Siguiente artículo →</a>
          <?php endif; ?>
        </div>
      </article>

      <!-- Sidebar -->
      <aside class="articulo-sidebar">
        <div class="art-sidebar-card">
          <div class="sidebar-label">Sobre el autor</div>
          <div class="art-author-card">
            <div class="author-avatar" style="width:56px;height:56px;font-size:1.3rem;"><?php echo $articulo['avatar']; ?></div>
            <div>
              <strong><?php echo htmlspecialchars($articulo['autor_nombre']); ?></strong>
              <p><?php echo htmlspecialchars($articulo['autor_bio']); ?></p>
            </div>
          </div>
        </div>

        <div class="art-sidebar-card">
          <div class="sidebar-label">Artículos relacionados</div>
          <div class="related-list">
            <?php while($rel = $relacionados->fetch_assoc()): ?>
            <a href="articulo.php?id=<?php echo $rel['id']; ?>" class="related-item">
              <span class="tag" style="font-size:0.65rem"><?php echo $rel['cat_nombre']; ?></span>
              <p><?php echo htmlspecialchars($rel['titulo']); ?></p>
            </a>
            <?php endwhile; ?>
          </div>
        </div>

        <div class="art-sidebar-card art-subscribe-card">
          <p class="sidebar-label">Boletín de Arcana</p>
          <p style="font-size:0.88rem;color:var(--color-text-soft);margin:0 0 16px;">Recibe los mejores artículos cada dos semanas.</p>
          <form action="suscripciones.php" method="POST">
            <input type="email" name="email" placeholder="tu@email.com" style="margin-bottom:10px;width:100%;padding:8px 12px;background:rgba(26,42,58,0.5);border:1px solid var(--color-border);border-radius:6px;color:var(--color-text);">
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Suscribirse</button>
          </form>
        </div>
      </aside>
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
          <p>Revista digital de fantasía. Narrativa, arte y conocimiento sobre mundos imaginarios.</p>
          <p class="footer-innadi">Un proyecto de <strong><a href="https://www.innadi.com/">INNADI · Escuela de Diseño</a></strong></p>
        </div>
        <div class="footer-col">
          <h4>Comunidad</h4>
          <ul>
            <li><a href="suscripciones.php">Suscripciones</a></li>
            <li><a href="usuario.php">Usuarios</a></li>
            <li><a href="index.php#iniciativas">Iniciativas</a></li>
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
