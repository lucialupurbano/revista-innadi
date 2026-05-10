<?php
session_start();
require_once 'db/conexion.php';

// Obtener artículo destacado
$sql_destacado = "SELECT a.*, u.nombre as autor_nombre, u.username, u.avatar, c.nombre as cat_nombre, c.slug as cat_slug
                  FROM articulos a
                  JOIN usuarios u ON a.autor_id = u.id
                  JOIN categorias c ON a.categoria_id = c.id
                  WHERE a.destacado = 1 LIMIT 1";
$articulo_destacado = $conexion->query($sql_destacado)->fetch_assoc();

// Obtener todos los artículos para el grid
$sql_articulos = "SELECT a.*, u.nombre as autor_nombre, u.username, u.avatar, c.nombre as cat_nombre, c.slug as cat_slug
                  FROM articulos a
                  JOIN usuarios u ON a.autor_id = u.id
                  JOIN categorias c ON a.categoria_id = c.id
                  ORDER BY a.fecha DESC";
$articulos = $conexion->query($sql_articulos);

// Obtener categorías con conteo
$sql_categorias = "SELECT c.*, COUNT(a.id) as total
                   FROM categorias c
                   LEFT JOIN articulos a ON c.id = a.categoria_id
                   GROUP BY c.id";
$categorias = $conexion->query($sql_categorias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Arcana — Revista digital de fantasía. Narrativa, arte y conocimiento sobre mundos imaginarios.">
  <title>Arcana · Revista de Fantasía</title>
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
        <li class="nav-dropdown">
          <a href="#" class="nav-link">Categorías ▾</a>
          <ul class="dropdown-menu">
            <?php
            $cats_dropdown = $conexion->query("SELECT * FROM categorias");
            while($cat = $cats_dropdown->fetch_assoc()):
            ?>
            <li><a href="categorias.php?cat=<?php echo $cat['slug']; ?>"><?php echo $cat['icono'] . ' ' . $cat['nombre']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </li>
        <li><a href="index.php#iniciativas" class="nav-link">Iniciativas</a></li>
        <li><a href="index.php#quienes" class="nav-link">Quiénes somos</a></li>
        <li><a href="index.php#contacto" class="nav-link">Contacto</a></li>
      </ul>
      <div class="nav-actions">
        <form action="buscar.php" method="GET" style="display:flex;gap:8px;align-items:center;">
          <input type="search" name="q" placeholder="Buscar artículos, autores..." style="background:rgba(26,42,58,0.5);border:1px solid var(--color-border);border-radius:6px;padding:8px 12px;color:var(--color-text);font-family:var(--font-ui);font-size:0.85rem;width:200px;">
          <button type="submit" class="nav-search-btn" aria-label="Buscar">⌕</button>
        </form>
        <a href="usuario.php" class="nav-icon-btn" aria-label="Usuario">👤</a>
        <a href="suscripciones.php" class="btn btn-primary nav-cta">Suscribirse</a>
      </div>
      <button class="nav-burger" id="burger" aria-label="Menú">☰</button>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero-main" id="inicio">
    <div class="hero-bg">
      <div class="hero-orb hero-orb-1"></div>
      <div class="hero-orb hero-orb-2"></div>
      <div class="hero-particles" id="particles"></div>
    </div>
    <div class="hero-content">
      <p class="hero-eyebrow">✦ &nbsp;Revista Digital de Fantasía &nbsp;✦</p>
      <h1 class="hero-title">
        <span class="hero-title-line">Mundos</span>
        <span class="hero-title-line hero-title-accent">sin límites</span>
      </h1>
      <p class="hero-subtitle">Narrativa, arte y conocimiento sobre mundos imaginarios.<br>Donde los lectores se convierten en creadores.</p>
      <div class="hero-cta-group">
        <a href="categorias.php" class="btn btn-primary">Explorar la revista</a>
        <a href="#quienes" class="btn btn-ghost">Conocer el proyecto</a>
      </div>
    </div>
    <div class="hero-scroll-hint">
      <span>Desplázate para explorar</span>
      <div class="scroll-arrow">↓</div>
    </div>
  </section>

  <!-- QUIÉNES SOMOS -->
  <section class="quienes-section" id="quienes">
    <div class="container">
      <div class="quienes-inner">
        <div class="quienes-text">
          <div class="section-label"><span>✦</span> Quiénes somos</div>
          <h2>Un proyecto nacido de la pasión por los mundos imaginarios</h2>
          <p>Arcana es una revista digital creada desde INNADI Escuela de Diseño con el objetivo de explorar la fantasía como género literario, artístico y cultural. Creemos que imaginar mundos es una forma de entender el nuestro.</p>
          <p>Nuestro equipo lo forman escritores, ilustradores, diseñadores y lectores apasionados que comparten contenido riguroso y accesible para todos los niveles.</p>
          <a href="#contacto" class="btn btn-primary-cont">Colaborar con nosotros</a>
          <a href="https://www.innadi.com/" class="btn btn-primary">Accede a INNADI</a>
        </div>
        <div class="quienes-visual">
          <div class="manifesto-card">
            <p class="manifesto-quote">"La fantasía no huye de la realidad, sino que la atraviesa para alcanzar una verdad más profunda."</p>
            <p class="manifesto-author">— C.S. Lewis</p>
            <div class="manifesto-deco">✦</div>
          </div>
          <div class="values-grid">
            <div class="value-item"><span>🌍</span><strong>Imaginar</strong></div>
            <div class="value-item"><span>📚</span><strong>Aprender</strong></div>
            <div class="value-item"><span>✍</span><strong>Crear</strong></div>
            <div class="value-item"><span>🤝</span><strong>Compartir</strong></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- BANNER -->
  <section class="banner-section">
    <div class="banner-inner">
      <div class="banner-text-col">
        <p class="banner-eyebrow">✦ Edición especial</p>
        <h2 class="banner-title">El gran atlas<br>de los mundos</h2>
        <p class="banner-desc">Una colección de mapas, lenguas y leyendas de los universos más ricos de la fantasía contemporánea.</p>
        <a href="articulo.php?id=<?php echo $articulo_destacado['id']; ?>" class="btn btn-primary">Leer ahora</a>
      </div>
      <div class="banner-visual">
        <div class="banner-orb"></div>
        <div class="banner-map-art">
          <svg viewBox="0 0 320 260" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="10" width="300" height="240" rx="4" stroke="rgba(240,217,138,0.2)" stroke-width="1"/>
            <path d="M40 80 Q80 40 140 60 Q200 80 250 50 Q280 40 300 60" stroke="rgba(240,217,138,0.3)" stroke-width="1.5" fill="none"/>
            <path d="M20 130 Q60 110 100 130 Q160 160 220 120 Q260 100 300 130" stroke="rgba(240,217,138,0.2)" stroke-width="1" fill="none"/>
            <path d="M60 200 Q120 170 180 200 Q230 220 280 190" stroke="rgba(240,217,138,0.2)" stroke-width="1" fill="none"/>
            <circle cx="100" cy="90" r="6" fill="rgba(240,217,138,0.4)"/>
            <circle cx="180" cy="110" r="4" fill="rgba(240,217,138,0.3)"/>
            <circle cx="240" cy="70" r="5" fill="rgba(240,217,138,0.35)"/>
            <circle cx="140" cy="180" r="3" fill="rgba(240,217,138,0.25)"/>
            <text x="96" y="110" font-size="8" fill="rgba(240,217,138,0.5)" font-family="serif">Aethermor</text>
            <text x="175" y="130" font-size="8" fill="rgba(240,217,138,0.4)" font-family="serif">Vel Karath</text>
            <text x="230" y="60" font-size="8" fill="rgba(240,217,138,0.45)" font-family="serif">Nirvhas</text>
          </svg>
        </div>
      </div>
    </div>
  </section>

  <!-- ARTÍCULO DESTACADO -->
  <section class="featured-wrap" id="publicaciones">
    <div class="container">
      <div class="section-label"><span>✦</span> Artículo destacado</div>
      <?php if($articulo_destacado): ?>
      <article class="featured-article">
        <div class="featured-img">
          <div class="featured-img-placeholder">
            <div class="img-art">
              <div class="img-art-circle"></div>
              <div class="img-art-rune">᚛ᚅᚔᚋᚊᚔᚂ᚜</div>
            </div>
          </div>
          <span class="featured-badge">✦ Destacado</span>
        </div>
        <div class="featured-body">
          <div class="featured-meta">
            <span class="tag"><?php echo $articulo_destacado['cat_nombre']; ?></span>
            <span class="meta-sep">·</span>
            <span class="meta-date"><?php echo date('d M Y', strtotime($articulo_destacado['fecha'])); ?></span>
            <span class="meta-sep">·</span>
            <span class="meta-time">⏱ <?php echo $articulo_destacado['tiempo_lectura']; ?> min</span>
          </div>
          <h2 class="featured-title"><?php echo htmlspecialchars($articulo_destacado['titulo']); ?></h2>
          <p class="featured-excerpt"><?php echo strip_tags(substr($articulo_destacado['contenido'], 0, 250)); ?>...</p>
          <div class="featured-author">
            <div class="author-avatar"><?php echo $articulo_destacado['avatar']; ?></div>
            <div>
              <strong><?php echo htmlspecialchars($articulo_destacado['autor_nombre']); ?></strong>
            </div>
          </div>
          <a href="articulo.php?id=<?php echo $articulo_destacado['id']; ?>" class="btn btn-primary">Leer artículo completo</a>
        </div>
      </article>
      <?php endif; ?>
    </div>
  </section>

  <!-- CATEGORÍAS GRID -->
  <section class="cat-section" id="categorias">
    <div class="container">
      <div class="section-label"><span>✦</span> Categorías</div>
      <div class="cat-grid">
        <?php while($cat = $categorias->fetch_assoc()): ?>
        <a href="categorias.php?cat=<?php echo $cat['slug']; ?>" class="cat-card">
          <div class="cat-icon"><?php echo $cat['icono']; ?></div>
          <h3><?php echo $cat['nombre']; ?></h3>
          <p><?php echo $cat['descripcion']; ?></p>
          <span class="cat-count"><?php echo $cat['total']; ?> artículos</span>
        </a>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <!-- INICIATIVAS -->
  <section class="iniciativas-section" id="iniciativas">
    <div class="container">
      <div class="section-label"><span>✦</span> Iniciativas</div>
      <div class="iniciativas-grid">
        <article class="iniciativa-card iniciativa-main">
          <div class="iniciativa-tag">Convocatoria abierta</div>
          <h3>Antología de Relatos 2025</h3>
          <p>Envíanos tu relato corto de fantasía. Los mejores serán publicados en la antología anual de Arcana, disponible en digital.</p>
          <div class="iniciativa-meta">
            <span>📅 Cierre: 30 Jun 2025</span>
            <span>📝 Hasta 5.000 palabras</span>
          </div>
          <a href="#contacto" class="btn btn-primary">Participar</a>
        </article>
        <article class="iniciativa-card">
          <div class="iniciativa-tag">Taller</div>
          <h3>Worldbuilding en 30 días</h3>
          <p>Un reto mensual de creación de mundos con prompts diarios y feedback de la comunidad.</p>
          <a href="#" class="btn btn-ghost">Más información</a>
        </article>
        <article class="iniciativa-card">
          <div class="iniciativa-tag">Comunidad</div>
          <h3>Club de lectura</h3>
          <p>Cada mes leemos una obra de fantasía y debatimos en sesiones abiertas para la comunidad.</p>
          <a href="#" class="btn btn-ghost">Unirse</a>
        </article>
      </div>
    </div>
  </section>

  <!-- CONTACTO -->
  <section class="contacto-section" id="contacto">
    <div class="container">
      <div class="contacto-inner">
        <div class="contacto-text">
          <div class="section-label"><span>✦</span> Contacto</div>
          <h2>Escríbenos</h2>
          <p>¿Tienes un relato, una propuesta de colaboración, o quieres unirte a la comunidad? Estamos aquí.</p>
          <div class="contacto-info">
            <div class="contacto-item"><span>✉</span><span>hola@arcana-revista.es</span></div>
            <div class="contacto-item"><span>↗</span><span>@arcana_revista</span></div>
            <div class="contacto-item"><span>⌘</span><span>INNADI Escuela de Diseño</span></div>
          </div>
        </div>
        <form class="contacto-form" id="contactForm" action="" method="POST">
          <div class="field">
            <label for="c-nombre">Nombre</label>
            <input id="c-nombre" type="text" name="nombre" placeholder="Tu nombre completo" required>
          </div>
          <div class="field">
            <label for="c-email">Correo electrónico</label>
            <input id="c-email" type="email" name="email" placeholder="nombre@dominio.com" required>
          </div>
          <div class="field">
            <label for="c-tipo">¿En qué podemos ayudarte?</label>
            <select id="c-tipo" name="tipo">
              <option>Selecciona una opción</option>
              <option>Propuesta de colaboración</option>
              <option>Envío de relato</option>
              <option>Suscripción</option>
              <option>Otros</option>
            </select>
          </div>
          <div class="field">
            <label for="c-mensaje">Mensaje</label>
            <textarea id="c-mensaje" name="mensaje" rows="4" placeholder="Cuéntanos tu propuesta…" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary form-submit">Enviar mensaje</button>
          <div class="form-success" id="formSuccess"><span>✓</span> Mensaje enviado. ¡Nos ponemos en contacto pronto!</div>
        </form>
      </div>
    </div>
  </section>

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
          <p class="footer-innadi">Un proyecto de <strong> <a href="https://www.innadi.com/">INNADI · Escuela de Diseño</a></strong></p>
        </div>
        <div class="footer-col">
          <h4>Contenido</h4>
          <ul>
            <li><a href="categorias.php">Últimas publicaciones</a></li>
            <?php
            $cats_footer = $conexion->query("SELECT * FROM categorias LIMIT 5");
            while($cat = $cats_footer->fetch_assoc()):
            ?>
            <li><a href="categorias.php?cat=<?php echo $cat['slug']; ?>"><?php echo $cat['nombre']; ?></a></li>
            <?php endwhile; ?>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Comunidad</h4>
          <ul>
            <li><a href="#iniciativas">Iniciativas</a></li>
            <li><a href="suscripciones.php">Suscripciones</a></li>
            <li><a href="#contacto">Colaborar</a></li>
            <li><a href="#quienes">Quiénes somos</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Newsletter</h4>
          <p>Recibe los mejores artículos cada dos semanas.</p>
          <form action="suscripciones.php" method="POST" class="footer-newsletter">
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
