<?php
session_start();
require_once 'db/conexion.php';

// Procesar suscripción por email (newsletter)
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $conexion->real_escape_string($_POST['email']);
    // Aquí se guardaría el email para newsletter (simulado)
    $mensaje = "Te has suscrito correctamente al newsletter de Arcana.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Suscripciones · Arcana</title>
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
        <p class="hero-eyebrow">✦ Comunidad</p>
        <h1 class="subpage-title">Suscripciones y alertas</h1>
        <p class="subpage-subtitle">Personaliza qué contenido recibes - Totalmente gratuito</p>
      </div>
    </div>

    <div class="container subs-layout">

      <!-- A. Suscripción a autores -->
      <section class="subs-section">
        <div class="subs-section-header">
          <div class="subs-section-icon">👤</div>
          <div>
            <h2>Autores que sigues</h2>
            <p>Recibe notificaciones cuando publiquen nuevo contenido - Gratis</p>
          </div>
        </div>
        <div class="authors-grid">
          <?php
          $autores = $conexion->query("SELECT u.*, COUNT(a.id) as total_art FROM usuarios u LEFT JOIN articulos a ON u.id = a.autor_id GROUP BY u.id");
          while($autor = $autores->fetch_assoc()):
          ?>
          <div class="author-sub-card" id="author-card-<?php echo $autor['id']; ?>">
            <div class="author-avatar" style="width:52px;height:52px;font-size:1.2rem;"><?php echo $autor['avatar']; ?></div>
            <div class="author-sub-info">
              <strong><?php echo htmlspecialchars($autor['nombre']); ?></strong>
              <span><?php echo $autor['total_art']; ?> publicaciones</span>
            </div>
            <button class="sub-toggle" onclick="toggleSub(<?php echo $autor['id']; ?>, this)">+ Seguir</button>
          </div>
          <?php endwhile; ?>
        </div>
      </section>

      <!-- B. Suscripción a categorías -->
      <section class="subs-section">
        <div class="subs-section-header">
          <div class="subs-section-icon">🏷</div>
          <div>
            <h2>Categorías que sigues</h2>
            <p>Te avisamos cuando se publique contenido en estas categorías - Gratis</p>
          </div>
        </div>
        <div class="tags-sub-grid">
          <?php
          $cats = $conexion->query("SELECT * FROM categorias");
          while($cat = $cats->fetch_assoc()):
          ?>
          <button class="tag-sub-btn" onclick="this.classList.toggle('active')"><?php echo $cat['nombre']; ?></button>
          <?php endwhile; ?>
        </div>
      </section>

      <!-- C. Newsletter gratuito -->
      <section class="subs-section">
        <div class="subs-section-header">
          <div class="subs-section-icon">✉</div>
          <div>
            <h2>Boletín de Arcana</h2>
            <p>Recibe los mejores artículos cada dos semanas - Totalmente gratuito</p>
          </div>
        </div>
        <div class="newsletter-config">
          <div class="newsletter-form-wrap">
            <?php if(isset($mensaje)): ?>
            <div class="form-success show"><span>✓</span> <?php echo $mensaje; ?></div>
            <?php endif; ?>
            <form action="" method="POST">
              <div class="field">
                <label>Tu correo electrónico</label>
                <input type="email" name="email" placeholder="nombre@dominio.com" required>
              </div>
              <div class="field">
                <label>Frecuencia (Gratuita)</label>
                <div class="freq-options">
                  <label class="freq-option">
                    <input type="radio" name="freq" value="semanal" checked>
                    <span class="freq-label">
                      <strong>Semanal</strong>
                      <em>Cada lunes, un resumen de lo publicado</em>
                    </span>
                  </label>
                  <label class="freq-option">
                    <input type="radio" name="freq" value="quincenal">
                    <span class="freq-label">
                      <strong>Quincenal</strong>
                      <em>Cada dos semanas, selección editorial</em>
                    </span>
                  </label>
                  <label class="freq-option">
                    <input type="radio" name="freq" value="mensual">
                    <span class="freq-label">
                      <strong>Mensual</strong>
                      <em>Una vez al mes, lo más destacado</em>
                    </span>
                  </label>
                </div>
              </div>
              <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Suscribirse gratis</button>
            </form>
          </div>
        </div>
      </section>

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
        <div class="footer-col">
          <h4>Newsletter</h4>
          <p>Recibe los mejores artículos cada dos semanas.</p>
          <form action="" method="POST" class="footer-newsletter">
            <input type="email" name="email" placeholder="tu@email.com" class="footer-email-input" required>
            <button type="submit" class="btn btn-primary footer-subscribe">→</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2025 Arcana · Revista de Fantasía · Proyecto INNADI</p>
        <div class="footer-links">
          <a href="#">Política de privacidad</a>
          <span>·</span>
          <a href="#">Aviso legal</a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
<?php $conexion->close(); ?>
