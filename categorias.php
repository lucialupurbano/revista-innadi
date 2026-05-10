<?php
session_start();
require_once 'db/conexion.php';

$cat_slug = isset($_GET['cat']) ? $_GET['cat'] : 'todos';

// Obtener todas las categorías
$categorias = $conexion->query("SELECT * FROM categorias");

// Obtener TODOS los artículos (el filtro se hace en JS)
$sql_articulos = "SELECT a.*, u.nombre as autor_nombre, u.avatar, c.nombre as cat_nombre, c.slug as cat_slug
                  FROM articulos a
                  JOIN usuarios u ON a.autor_id = u.id
                  JOIN categorias c ON a.categoria_id = c.id
                  ORDER BY a.fecha DESC";
$articulos = $conexion->query($sql_articulos);
$total = $articulos->num_rows;

// Título inicial según parámetro
if($cat_slug == 'todos') {
    $titulo = "Todas las publicaciones";
} else {
    $cat_info = $conexion->query("SELECT * FROM categorias WHERE slug = '$cat_slug'")->fetch_assoc();
    $titulo = $cat_info ? $cat_info['nombre'] : "Todas las publicaciones";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categorías · Arcana</title>
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
        <p class="hero-eyebrow">✦ Publicaciones</p>
        <h1 class="subpage-title"><?php echo $titulo; ?></h1>
        <p class="subpage-subtitle">Explora nuestra biblioteca de artículos por categoría</p>
      </div>
    </div>

    <div class="container pub-layout">
      <!-- Sidebar categorías -->
      <aside class="pub-sidebar">
        <div class="sidebar-label">Categorías</div>
        <nav class="sidebar-nav">
          <button class="sidebar-btn <?php echo $cat_slug=='todos'?'active':''; ?>" data-cat="todos" onclick="filtrarCategoria('todos', this)">
            <span class="sidebar-btn-icon">✦</span> Todos
            <?php
            $total_todos = $conexion->query("SELECT COUNT(*) as t FROM articulos")->fetch_assoc();
            ?>
            <span class="sidebar-count"><?php echo $total_todos['t']; ?></span>
          </button>
          <?php
          $cats_sidebar = $conexion->query("SELECT c.*, COUNT(a.id) as total FROM categorias c LEFT JOIN articulos a ON c.id = a.categoria_id GROUP BY c.id");
          while($cat = $cats_sidebar->fetch_assoc()):
          ?>
          <button class="sidebar-btn <?php echo $cat_slug==$cat['slug']?'active':''; ?>" data-cat="<?php echo $cat['slug']; ?>" onclick="filtrarCategoria('<?php echo $cat['slug']; ?>', this)">
            <span class="sidebar-btn-icon"><?php echo $cat['icono']; ?></span> <?php echo $cat['nombre']; ?>
            <span class="sidebar-count"><?php echo $cat['total']; ?></span>
          </button>
          <?php endwhile; ?>
        </nav>
      </aside>

      <!-- Grid de artículos -->
      <main class="pub-main">
        <div class="pub-top-bar">
          <span class="pub-count"><?php echo $total; ?> artículos</span>
        </div>

        <div class="articles-grid pub-grid" id="pubGrid">
          <?php
          $articulos->data_seek(0);
          while($art = $articulos->fetch_assoc()):
          ?>
          <article class="article-card" data-cat="<?php echo $art['cat_slug']; ?>">
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
        <div class="footer-col">
          <h4>Contenido</h4>
          <ul>
            <?php
            $cats_footer = $conexion->query("SELECT * FROM categorias LIMIT 6");
            while($cat = $cats_footer->fetch_assoc()):
            ?>
            <li><a href="categorias.php?cat=<?php echo $cat['slug']; ?>"><?php echo $cat['nombre']; ?></a></li>
            <?php endwhile; ?>
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
