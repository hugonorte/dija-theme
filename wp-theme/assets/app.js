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
      if (!res.ok) throw new Error(String(res.status));
      form.reset();
      say(form.dataset.success || 'Mensagem enviada!', true);
    } catch {
      say('Não foi possível enviar agora. Tente de novo ou fale pelo WhatsApp.', false);
    } finally {
      button.disabled = false;
    }
  });
});

// Carrossel de eventos: alterna os .slide dentro de [data-carousel].
// Para adicionar um evento, duplique um <div class="slide"> no index.html.
document.querySelectorAll('[data-carousel]').forEach((carousel) => {
  const slides = [...carousel.querySelectorAll('.slide')];
  let current = Math.max(0, slides.findIndex((s) => s.classList.contains('is-active')));

  const show = (i) => {
    slides[current].classList.remove('is-active');
    current = (i + slides.length) % slides.length;
    slides[current].classList.add('is-active');
  };

  carousel.querySelector('.carousel-prev')?.addEventListener('click', () => show(current - 1));
  carousel.querySelector('.carousel-next')?.addEventListener('click', () => show(current + 1));
});
