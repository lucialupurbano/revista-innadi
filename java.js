/* ═══════════════════════════════════════════════
   ARCANA · Revista Digital de Fantasía
   arcana-pages.js — navegación entre pantallas
═══════════════════════════════════════════════ */

'use strict';

/* ── Navegación entre páginas ── */
function showPage(pageId, cat) {
  // Ocultar todas las páginas
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

  // Si entramos a publicaciones con categoría
  if (pageId === 'publicaciones' && cat) {
    setTimeout(() => filterPub(cat), 200);
  }

  // Actualizar nav activo
  document.querySelectorAll('.nav-link').forEach(l => l.style.color = '');
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
  // Actualizar botones sidebar
  document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
  const activeBtn = btn || document.querySelector(`.sidebar-btn[data-cat="${cat}"]`);
  if (activeBtn) activeBtn.classList.add('active');

  // Filtrar tarjetas
  const cards = document.querySelectorAll('#pubGrid .article-card');
  let visible = 0;
  cards.forEach((card, i) => {
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

  // Actualizar título y conteo
  const titleEl = document.getElementById('pubPageTitle');
  const countEl = document.getElementById('pubCount');
  if (titleEl) titleEl.textContent = catNames[cat] || 'Publicaciones';
  if (countEl) countEl.textContent = `${catCounts[cat] || visible} artículos`;
}

/* ── Vista grid/lista publicaciones ── */
function setPubView(view, btn) {
  document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const grid = document.getElementById('pubGrid');
  if (!grid) return;
  if (view === 'list') {
    grid.classList.add('pub-list-view');
  } else {
    grid.classList.remove('pub-list-view');
  }
}

/* ── Tabs de configuración ── */
function showConfigTab(tabId, btn) {
  document.querySelectorAll('.config-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.config-nav-btn').forEach(b => b.classList.remove('active'));
  const tab = document.getElementById('config-tab-' + tabId);
  if (tab) tab.classList.add('active');
  if (btn) btn.classList.add('active');
}

/* ── Sort buttons sidebar ── */
document.querySelectorAll('.sort-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
  });
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

/* ── Fuente size config ── */
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

/* ── Paginación ── */
document.querySelectorAll('.page-btn:not(.page-next)').forEach(btn => {
  btn.addEventListener('click', function() {
    if (!isNaN(parseInt(this.textContent))) {
      document.querySelectorAll('.page-btn:not(.page-next)').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    }
  });
});
