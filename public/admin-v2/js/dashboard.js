/**
 * Dashboard (v2) — count-up numbers + Chart.js trend/breakdown/sparkline
 * charts. Depends on Chart.js (loaded before this file) and the two
 * inline globals set by index_v2.blade.php: window.__dashboardTrend and
 * window.__dashboardBreakdown.
 */
(function () {
  // ---- Count-up animation for [data-countup] elements -------------------
  function formatNumber(n) {
    return Math.round(n).toLocaleString('en-IN');
  }

  function animateCountUp(el) {
    var target = parseFloat(el.getAttribute('data-countup'));
    if (isNaN(target)) return;
    var duration = 900;
    var start = null;
    var from = 0;

    function step(ts) {
      if (!start) start = ts;
      var progress = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
      el.textContent = formatNumber(from + (target - from) * eased);
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = formatNumber(target);
      }
    }
    requestAnimationFrame(step);
  }

  document.querySelectorAll('[data-countup]').forEach(animateCountUp);

  // ---- Charts -------------------------------------------------------------
  if (typeof Chart === 'undefined') return;

  var rootStyles = getComputedStyle(document.documentElement);
  function token(name, fallback) {
    var v = rootStyles.getPropertyValue(name).trim();
    return v || fallback;
  }

  Chart.defaults.font.family = "'Inter', sans-serif";
  Chart.defaults.color = token('--color-text-secondary', '#64748b');

  var trend = window.__dashboardTrend || [];
  var trendCanvas = document.getElementById('trendChart');
  if (trendCanvas && trend.length) {
    new Chart(trendCanvas, {
      type: 'line',
      data: {
        labels: trend.map(function (t) { return t.label; }),
        datasets: [
          {
            label: 'Sales Orders',
            data: trend.map(function (t) { return t.sales; }),
            borderColor: '#e74a3b',
            backgroundColor: 'rgba(231, 74, 59, 0.08)',
            tension: 0.35,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#e74a3b',
          },
          {
            label: 'Invoices',
            data: trend.map(function (t) { return t.invoice; }),
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.08)',
            tension: 0.35,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#1cc88a',
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
          legend: { position: 'top', align: 'end', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true } },
          tooltip: {
            callbacks: {
              label: function (ctx) { return ctx.dataset.label + ': ₹' + Math.round(ctx.parsed.y).toLocaleString('en-IN'); },
            },
          },
        },
        scales: {
          x: { grid: { display: false } },
          y: {
            grid: { color: token('--color-border', '#e2e8f0') },
            ticks: { callback: function (v) { return '₹' + v; } },
          },
        },
      },
    });
  }

  var breakdown = window.__dashboardBreakdown;
  var breakdownCanvas = document.getElementById('breakdownChart');
  if (breakdownCanvas && breakdown) {
    new Chart(breakdownCanvas, {
      type: 'doughnut',
      data: {
        labels: breakdown.labels,
        datasets: [{
          data: breakdown.values,
          backgroundColor: breakdown.colors,
          borderWidth: 2,
          borderColor: token('--color-bg-surface', '#fff'),
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: { legend: { display: false } },
      },
    });
  }

  // ---- Revenue widget sparklines ------------------------------------------
  document.querySelectorAll('[data-spark]').forEach(function (canvas) {
    var key = canvas.getAttribute('data-spark'); // 'sales' | 'invoice'
    var series = trend.map(function (t) { return t[key] || 0; });
    if (!series.length) return;
    var color = key === 'sales' ? '#4e73df' : '#1cc88a';
    new Chart(canvas, {
      type: 'line',
      data: {
        labels: series.map(function (_, i) { return i; }),
        datasets: [{
          data: series,
          borderColor: color,
          backgroundColor: 'transparent',
          tension: 0.4,
          pointRadius: 0,
          borderWidth: 2,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        scales: { x: { display: false }, y: { display: false } },
      },
    });
  });
})();
