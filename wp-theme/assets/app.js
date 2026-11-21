// Dija Theme — Copyright (c) 2026 Hugo Norte — https://github.com/hugonorte/dija-theme
// Licenciado sob a GNU GPL v2 ou posterior (ver COPYING e LICENSE).

// Marca que o JavaScript está ativo (o CSS esconde o que só serve sem JS).
document.documentElement.classList.add('js');

// Menu hambúrguer (telas < 768px): abre/fecha a lista de links.
const nav = document.querySelector('.nav');
const toggle = nav?.querySelector('.nav-toggle');
if (nav && toggle) {
  const setOpen = (open) => {
    nav.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
  };
  toggle.addEventListener('click', () => setOpen(!nav.classList.contains('is-open')));
  // fecha ao escolher um link ou apertar Esc
  nav.querySelectorAll('.nav-links a').forEach((a) => a.addEventListener('click', () => setOpen(false)));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setOpen(false); });
}

// Formulário de contato: envia para a REST API do tema (inc/contact.php).
document.querySelectorAll('[data-contact-form]').forEach((form) => {
  const status = form.querySelector('.form-status');
  const button = form.querySelector('[type="submit"]');
  const say = (text, ok) => {
    status.textContent = text;
    status.classList.toggle('is-ok', ok);
    status.classList.toggle('is-error', !ok);
  };

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form));
    if (!data.name?.trim() || !data.email?.trim() || !data.message?.trim()) {
      say('Preencha nome, e-mail e mensagem.', false);
      return;
    }
    if (!form.querySelector('[name="email"]').checkValidity()) {
      say('Confira o e-mail informado.', false);
      return;
    }
    button.disabled = true;
    say('Enviando…', true);
    try {
      const res = await fetch(form.dataset.endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      if (!res.ok) {
        // 429 = limite de envios: mostra o aviso do servidor.
        const body = await res.json().catch(() => ({}));
        if (res.status === 429 && body.message) { say(body.message, false); return; }
        throw new Error(String(res.status));
      }
      form.reset();
      say(form.dataset.success || 'Mensagem enviada!', true);
    } catch {
      say('Não foi possível enviar agora. Tente de novo ou fale pelo WhatsApp.', false);
    } finally {
      button.disabled = false;
    }
  });
});

// Carrosséis (eventos na home, divulgação em /turmas): alterna os .slide dentro de [data-carousel].
// Os slides vêm do painel (Eventos; Turmas com imagem de divulgação).
document.querySelectorAll('[data-carousel]').forEach((carousel) => {
  const slides = [...carousel.querySelectorAll('.slide')];
  let current = Math.max(0, slides.findIndex((s) => s.classList.contains('is-active')));

  // Pontinhos (opcional): elemento [data-carousel-dots] logo após o carrossel.
  const dotsBox = carousel.nextElementSibling?.matches('[data-carousel-dots]') ? carousel.nextElementSibling : null;
  const dots = dotsBox ? [...dotsBox.children] : [];
  const show = (i) => {
    slides[current].classList.remove('is-active');
    dots[current]?.classList.remove('is-active');
    current = (i + slides.length) % slides.length;
    slides[current].classList.add('is-active');
    dots[current]?.classList.add('is-active');
  };
  // Deslizar o dedo no celular troca o slide.
  let touchX = null;
  carousel.addEventListener('touchstart', (e) => { touchX = e.touches[0].clientX; }, { passive: true });
  carousel.addEventListener('touchend', (e) => {
    if (touchX === null || slides.length < 2) return;
    const dx = e.changedTouches[0].clientX - touchX;
    if (Math.abs(dx) > 40) show(current + (dx < 0 ? 1 : -1));
    touchX = null;
  });

  carousel.querySelector('.carousel-prev')?.addEventListener('click', () => show(current - 1));
  carousel.querySelector('.carousel-next')?.addEventListener('click', () => show(current + 1));
});

// Galeria de fotos (/eventos): filtros aplicam ao trocar a seleção; foto ampliada em <dialog>.
document.querySelectorAll('[data-fotos-filtros]').forEach((form) => {
  form.querySelectorAll('select').forEach((select) => {
    select.addEventListener('change', () => form.requestSubmit());
  });
  // Envia só os filtros preenchidos (URL mais limpa).
  form.addEventListener('submit', () => {
    form.querySelectorAll('select').forEach((s) => { s.disabled = !s.value; });
  });
});

const zoom = document.querySelector('[data-foto-zoom]');
if (zoom && typeof zoom.showModal === 'function') {
  const img = zoom.querySelector('img');
  const caption = zoom.querySelector('.foto-zoom-legenda');
  document.querySelectorAll('[data-foto-full]').forEach((link) => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      img.src = link.href;
      img.alt = link.querySelector('img')?.alt || '';
      caption.textContent = link.dataset.fotoCaption || '';
      zoom.showModal();
    });
  });
  zoom.querySelector('[data-foto-close]').addEventListener('click', () => zoom.close());
  // Clique fora da foto (no fundo escuro) também fecha.
  zoom.addEventListener('click', (e) => { if (e.target === zoom) zoom.close(); });
  zoom.addEventListener('close', () => { img.src = ''; });
}

// Hero animado: começa depois do carregamento (não disputa com a primeira
// pintura), pausa quando o hero sai da tela e sorteia altura/tamanho de cada
// pássaro a cada passagem (sempre fora da tela, então não há "pulo").
const scene = document.querySelector('[data-hero-scene]');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
if (scene && !reduceMotion.matches) {
  const start = () => scene.classList.add('anim-on');
  const whenIdle = (fn) => ('requestIdleCallback' in window ? requestIdleCallback(fn, { timeout: 1500 }) : setTimeout(fn, 300));
  if (document.readyState === 'complete') whenIdle(start);
  else window.addEventListener('load', () => whenIdle(start), { once: true });

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(([entry]) => {
      scene.classList.toggle('is-paused', !entry.isIntersecting);
    }).observe(scene.closest('.hero') || scene);
  }

  const rand = (min, max) => (min + Math.random() * (max - min)).toFixed(2);
  scene.querySelectorAll('[data-bird]').forEach((lane) => {
    lane.addEventListener('animationiteration', (e) => {
      if (e.target !== lane) return; // ignora o bater de asas e o sobe-e-desce
      lane.style.setProperty('--y', `${rand(3, 30)}%`);
      lane.style.setProperty('--w', `${rand(1.2, 4)}%`);
    });
  });
}
