document.addEventListener('DOMContentLoaded', () => {
    const metaTag = document.querySelector('meta[name="api-base-url"]');
    if (!metaTag) return;

    const API_BASE = metaTag.getAttribute('content');
    // Read the current locale set by Blade from the HTML tag
    const currentLocale = document.documentElement.getAttribute('lang') || 'en';
    const periods = ['day', 'week', 'month'];

    // Helper for shared fetch headers
    const getRequestHeaders = () => ({
        'Accept': 'application/json',
        'Accept-Language': currentLocale
    });

    const fetchStats = async () => {
        try {
            const response = await fetch(`${API_BASE}/tickets/statistics`, {
                headers: getRequestHeaders()
            });
            const result = await response.json();

            if (result.success && result.metrics) {
                periods.forEach(p => {
                    const card = document.getElementById(`period-${p}`);
                    if (card && result.metrics[p]) {
                        card.querySelector('.total').textContent = result.metrics[p].total_tickets;
                        card.querySelector('.new').textContent = result.metrics[p].new;
                    }
                });
                document.getElementById('stats-loader').classList.add('hidden');
                document.getElementById('stats-content').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Failed to update stats', error);
        }
    };

    fetchStats();

    const form = document.getElementById('ticket-form');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submit-btn');
            const msg = document.getElementById('form-message');

            const originalBtnText = btn.textContent;
            btn.disabled = true;
            btn.textContent = btn.getAttribute('data-sending-text');
            msg.textContent = '';

            try {
                const response = await fetch(`${API_BASE}/tickets`, {
                    method: 'POST',
                    headers: getRequestHeaders(), // Automatically injects target locale header
                    body: new FormData(form)
                });

                if (response.status === 201) {
                    msg.className = 'text-green-600 font-semibold mt-2';
                    msg.textContent = msg.getAttribute('data-success-text');
                    form.reset();
                    await fetchStats();
                } else {
                    const result = await response.json();
                    throw new Error(result.message || 'Validation error');
                }
            } catch (error) {
                msg.className = 'text-red-600 font-semibold mt-2';
                msg.textContent = error.message || 'Something went wrong.';
            } finally {
                btn.disabled = false;
                btn.textContent = originalBtnText;
            }
        });
    }
});