/* ══════════════════════════════════════════════
   ARCANA · Revista Digital de Fantasía
   java.js — funcionalidad completa
   ══════════════════════════════════════════════ */

'use strict';

/* ── Navegación burger ── */
document.addEventListener('DOMContentLoaded', function() {
  const burger = document.getElementById('burger');
  const navLinks = document.getElementById('navLinks');

  if(burger && navLinks) {
    burger.addEventListener('click', function() {
      navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
    });
  }

  /* ── Scroll nav ── */
  const nav = document.getElementById('nav');
  if(nav) {
    window.addEventListener('scroll', function() {
      if(window.scrollY > 50) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    });
  }

  /* ── Partículas hero ── */
  const particles = document.getElementById('particles');
  if(particles) {
    for(let i = 0; i < 30; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      p.style.left = Math.random() * 100 + '%';
      p.style.top = (Math.random() * 100 + 100) + '%';
      p.style.animationDuration = (Math.random() * 4 + 3) + 's';
      p.style.animationDelay = (Math.random() * 5) + 's';
      particles.appendChild(p);
    }
  }
});

/* ── Búsqueda funcional ── */
function realizarBusqueda() {
  const input = document.getElementById('searchInput');
  if(input && input.value.trim() !== '') {
    window.location.href = 'buscar.php?q=' + encodeURIComponent(input.value.trim());
  }
}

/* ── Toggle suscripción autor ── */
function toggleSub(autorId, btn) {
  const card = btn.closest('.author-sub-card');
  const isSubscribed = btn.classList.contains('subscribed');

  if(isSubscribed) {
    btn.classList.remove('subscribed');
    btn.textContent = '+ Seguir';
    card.classList.remove('subscribed');
    // PHP: aquí iría AJAX para eliminar suscripción
  } else {
    btn.classList.add('subscribed');
    btn.textContent = '✓ Siguiendo';
    card.classList.add('subscribed');
    // PHP: aquí iría AJAX para agregar suscripción
  }
}

/* ── Newsletter success ── */
function newsletterSuccess(btn) {
  const success = document.getElementById('nlSuccess');
  btn.textContent = '✓ Guardado';
  btn.disabled = true;
  if(success) {
    success.classList.add('show');
    setTimeout(() => {
      success.classList.remove('show');
      btn.textContent = 'Guardar preferencias';
      btn.disabled = false;
    }, 4000);
  }
}

/* ── Formulario contacto ── */
document.addEventListener('DOMContentLoaded', function() {
  const contactForm = document.getElementById('contactForm');
  if(contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const success = document.getElementById('formSuccess');
      if(success) {
        success.classList.add('show');
        setTimeout(() => success.classList.remove('show'), 4000);
      }
      this.reset();
    });
  }
});

/* ── Filtro categorías (categorias.php) ── */
function filtrarCategoria(cat, btn) {
  // Actualizar botones activos
  document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
  if(btn) btn.classList.add('active');

  // Filtrar tarjetas
  const cards = document.querySelectorAll('#pubGrid .article-card');
  let visible = 0;
  cards.forEach(card => {
    const show = cat === 'todos' || card.dataset.cat === cat;
    if(show) {
      card.style.display = '';
      card.style.animation = 'fadeInUp 0.4s ease both';
      visible++;
    } else {
      card.style.display = 'none';
    }
  });

  // Actualizar título y conteo
  const tituloCats = {
    'todos': 'Todas las publicaciones',
    'fantasia': 'Sobre la fantasía',
    'mitologia': 'Mitología',
    'mundos': 'Creación de mundos',
    'criaturas': 'Criaturas',
    'magia': 'Sistemas mágicos',
    'relatos': 'Relatos originales'
  };

  const titleEl = document.querySelector('.subpage-title');
  const countEl = document.getElementById('pubCount');
  if(titleEl) titleEl.textContent = tituloCats[cat] || 'Publicaciones';
  if(countEl) countEl.textContent = visible + ' artículos';

  // Actualizar URL sin recargar
  const url = cat === 'todos' ? 'categorias.php' : 'categorias.php?cat=' + cat;
  history.pushState(null, '', url);
}

/* ── Toggle tags suscripciones ── */
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.tag-sub-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      this.classList.toggle('active');
    });
  });

  /* ── Filtrar por URL al cargar categorias.php ── */
  const urlParams = new URLSearchParams(window.location.search);
  const catParam = urlParams.get('cat');
  if(catParam && document.getElementById('pubGrid')) {
    // Dar tiempo a que se rendericen las tarjetas
    setTimeout(() => {
      const btn = document.querySelector(`.sidebar-btn[data-cat="${catParam}"]`);
      if(btn) filtrarCategoria(catParam, btn);
    }, 100);
  }
});
