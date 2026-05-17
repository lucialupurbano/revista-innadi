/* ═══════════════════════════════════════════════
   ARCANA · Revista Digital de Fantasía
   java.js — funcionalidad completa (SPA + UI)
   ═══════════════════════════════════════════════ */

'use strict';

/* ── Inicialización ── */
document.addEventListener('DOMContentLoaded', function() {
  initBurger();
  initScrollNav();
  initParticles();
  initContactForm();
  initTagToggles();
  initSortButtons();
  initPagination();
  initUrlParams();
});

function initBurger() {
  const burger = document.getElementById('burger');
  const navLinks = document.getElementById('navLinks');
  if(burger && navLinks) {
    burger.addEventListener('click', function() {
      navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
    });
  }
}

function initScrollNav() {
  const nav = document.getElementById('nav');
  if(nav) {
    window.addEventListener('scroll', function() {
      nav.classList.toggle('scrolled', window.scrollY > 50);
    });
  }
}

function initParticles() {
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
}

function initContactForm() {
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
}

function initTagToggles() {
  document.querySelectorAll('.tag-sub-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      this.classList.toggle('active');
    });
  });
}

function initSortButtons() {
  document.querySelectorAll('.sort-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    });
  });
}

function initPagination() {
  document.querySelectorAll('.page-btn:not(.page-next)').forEach(btn => {
    btn.addEventListener('click', function() {
      const val = parseInt(this.textContent);
      if (!isNaN(val)) {
        document.querySelectorAll('.page-btn:not(.page-next)').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      }
    });
  });
}

function initUrlParams() {
  const path = window.location.pathname.replace('.php', '');
  const parts = path.split('/').filter(Boolean);
  const lastPart = parts[parts.length - 1];

  const pageMap = {
    'categorias': 'publicaciones',
    'usuario': 'usuario',
    'articulo': 'articulo',
    'suscripciones': 'suscripciones',
    'buscar': 'publicaciones',
    'index': 'home',
    '': 'home'
  };

  const targetPage = pageMap[lastPart] || 'home';

  const urlParams = new URLSearchParams(window.location.search);
  const catParam = urlParams.get('cat');

  setTimeout(() => {
    showPage(targetPage);
    if ((targetPage === 'publicaciones' || lastPart === 'categorias') && catParam) {
      setTimeout(() => {
        const btn = document.querySelector(`.sidebar-btn[data-cat="${catParam}"]`);
        if(btn) filterPub(catParam, btn);
      }, 200);
    }
    if (lastPart === 'buscar') {
      const q = urlParams.get('q');
      if (q) {
        const input = document.getElementById('searchInput');
        if (input) {
          input.value = q;
          setTimeout(() => realizarBusqueda(), 300);
        }
      }
    }
  }, 100);
}

/* ── Navegación entre páginas SPA ── */
function showPage(pageId, cat) {
  document.querySelectorAll('.page').forEach(p => {
    p.classList.remove('active');
    p.classList.add('exiting');
    setTimeout(() => p.classList.remove('exiting'), 300);
  });

  const target = document.getElementById('page-' + pageId);
  if (!target) return;

  setTimeout(() => {
    target.classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, 150);

  if (pageId === 'publicaciones' && cat) {
    setTimeout(() => filterPub(cat), 200);
  }

  document.querySelectorAll('.nav-link').forEach(l => l.style.color = '');
}

/* ── Tabs de usuario ── */
function showUsuarioTab(tabId, btn) {
  document.querySelectorAll('.usuario-tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.usuario-tab').forEach(b => b.classList.remove('active'));
  const tab = document.getElementById('usuario-tab-' + tabId);
  if (tab) tab.classList.add('active');
  if (btn) btn.classList.add('active');
}

/* ── Filtro de publicaciones ── */
const catNames = {
  todos: 'Todas las publicaciones',
  fantasia: 'Sobre la fantasía',
  mitologia: 'Mitología',
  mundos: 'Creación de mundos',
  criaturas: 'Criaturas',
  magia: 'Sistemas mágicos',
  relatos: 'Relatos originales'
};

const catCounts = {
  todos: 76,
  fantasia: 12,
  mitologia: 8,
  mundos: 15,
  criaturas: 10,
  magia: 9,
  relatos: 22
};

function filterPub(cat, btn) {
  document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
  const activeBtn = btn || document.querySelector(`.sidebar-btn[data-cat="${cat}"]`);
  if (activeBtn) activeBtn.classList.add('active');

  const cards = document.querySelectorAll('#pubGrid .article-card');
  let visible = 0;
  cards.forEach(card => {
    const show = cat === 'todos' || card.dataset.cat === cat;
    if (show) {
      card.style.display = '';
      card.style.animationDelay = `${visible * 0.07}s`;
      card.style.animation = 'none';
      void card.offsetWidth;
      card.style.animation = 'fadeInUp 0.4s ease both';
      visible++;
    } else {
      card.style.display = 'none';
    }
  });

  const titleEl = document.getElementById('pubPageTitle');
  const countEl = document.getElementById('pubCount');
  if (titleEl) titleEl.textContent = catNames[cat] || 'Publicaciones';
  if (countEl) countEl.textContent = `${catCounts[cat] || visible} artículos`;

  history.pushState(null, '', cat === 'todos' ? '?page=publicaciones' : '?page=publicaciones&cat=' + cat);
}

/* ── Vista grid/lista ── */
function setPubView(view, btn) {
  document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const grid = document.getElementById('pubGrid');
  if (!grid) return;
  grid.classList.toggle('pub-list-view', view === 'list');
}

/* ── Tabs de configuración ── */
function showConfigTab(tabId, btn) {
  document.querySelectorAll('.config-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.config-nav-btn').forEach(b => b.classList.remove('active'));
  const tab = document.getElementById('config-tab-' + tabId);
  if (tab) tab.classList.add('active');
  if (btn) btn.classList.add('active');
}

/* ── Búsqueda ── */
function realizarBusqueda() {
  const input = document.getElementById('searchInput');
  if (input && input.value.trim() !== '') {
    const q = input.value.trim().toLowerCase();
    showPage('publicaciones');
    const cards = document.querySelectorAll('#pubGrid .article-card');
    let found = 0;
    cards.forEach(card => {
      const text = card.textContent.toLowerCase();
      const match = text.includes(q);
      card.style.display = match ? '' : 'none';
      if (match) found++;
    });
    document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
    const titleEl = document.getElementById('pubPageTitle');
    const countEl = document.getElementById('pubCount');
    if (titleEl) titleEl.textContent = 'Resultados: "' + q + '"';
    if (countEl) countEl.textContent = found + ' artículos';
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') realizarBusqueda();
    });
  }
  const searchToggle = document.getElementById('searchToggle');
  const searchBar = document.getElementById('searchBar');
  const searchClose = document.getElementById('searchClose');
  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', function() {
      searchBar.classList.toggle('open');
      if (searchBar.classList.contains('open')) {
        document.getElementById('searchInput')?.focus();
      }
    });
  }
  if (searchClose && searchBar) {
    searchClose.addEventListener('click', function() {
      searchBar.classList.remove('open');
    });
  }
});

/* ── Toggle suscripción autores ── */
function toggleSub(btn) {
  const card = btn.closest('.author-sub-card');
  const isSubscribed = btn.classList.contains('subscribed');
  if (isSubscribed) {
    btn.classList.remove('subscribed');
    btn.textContent = '+ Seguir';
    card.classList.remove('subscribed');
  } else {
    btn.classList.add('subscribed');
    btn.textContent = '✓ Siguiendo';
    card.classList.add('subscribed');
  }
}

/* ── Newsletter success ── */
function newsletterSuccess(btn) {
  const success = document.getElementById('nlSuccess');
  btn.textContent = '✓ Guardado';
  btn.disabled = true;
  if (success) {
    success.classList.add('show');
    setTimeout(() => {
      success.classList.remove('show');
      btn.textContent = 'Guardar preferencias';
      btn.disabled = false;
    }, 4000);
  }
}

/* ── Config save ── */
function configSave(btn) {
  const original = btn.textContent;
  btn.textContent = '✓ Guardado';
  btn.style.background = '#A8D4B8';
  btn.style.borderColor = '#A8D4B8';
  btn.style.color = '#1A2A3A';
  setTimeout(() => {
    btn.textContent = original;
    btn.style.background = '';
    btn.style.borderColor = '';
    btn.style.color = '';
  }, 2500);
}

/* ── Fuente ── */
let fontSize = 16;
function adjustFont(delta) {
  fontSize = Math.min(22, Math.max(13, fontSize + delta));
  const el = document.getElementById('fontSizeVal');
  if (el) el.textContent = fontSize + 'px';
  document.querySelectorAll('.articulo-content').forEach(c => c.style.fontSize = fontSize + 'px');
}

function setFontType(type, btn) {
  document.querySelectorAll('.toggle-opt').forEach(b => {
    if (b.closest('.config-option-row') === btn.closest('.config-option-row')) {
      b.classList.remove('active');
    }
  });
  btn.classList.add('active');
}

function setViewType(view, btn) {
  document.querySelectorAll('.toggle-opt').forEach(b => {
    if (b.closest('.config-option-row') === btn.closest('.config-option-row')) {
      b.classList.remove('active');
    }
  });
  btn.classList.add('active');
}

/* ── Scroll to top ── */
document.addEventListener('DOMContentLoaded', function() {
  const scrollBtn = document.getElementById('scrollTop');
  if (scrollBtn) {
    window.addEventListener('scroll', function() {
      scrollBtn.classList.toggle('visible', window.scrollY > 400);
    });
    scrollBtn.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
});
