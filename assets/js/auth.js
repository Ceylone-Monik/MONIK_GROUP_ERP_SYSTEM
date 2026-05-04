/**
 * AJAX login — posts to auth/login_process.php
 */
(function () {
  const form = document.getElementById('login-form');
  if (!form) return;

  const msg = document.getElementById('login-msg');
  const submit = form.querySelector('button[type="submit"]');

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    if (msg) {
      msg.textContent = '';
      msg.className = 'msg';
    }
    if (submit) submit.disabled = true;

    const body = new FormData(form);

    try {
      const res = await fetch('auth/login_process.php', {
        method: 'POST',
        body,
        credentials: 'same-origin',
      });
      const data = await res.json().catch(() => ({}));

      if (data.ok && data.redirect) {
        window.location.href = data.redirect;
        return;
      }

      if (msg) {
        msg.textContent = data.message || 'Login failed.';
        msg.className = 'msg error';
      }
    } catch {
      if (msg) {
        msg.textContent = 'Network error. Try again.';
        msg.className = 'msg error';
      }
    } finally {
      if (submit) submit.disabled = false;
    }
  });
})();
