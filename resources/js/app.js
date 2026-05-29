document.addEventListener('DOMContentLoaded', () => {
    const metaTag = document.querySelector('meta[name="api-base-url"]');
    if (!metaTag) return;

    const API_BASE = metaTag.getAttribute('content');
    const periods = ['day', 'week', 'month'];

    const fetchStats = async () => {
        try {
            const response = await fetch(`${API_BASE}/tickets/statistics`, {
                headers: { 'Accept': 'application/json' }
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

            btn.disabled = true;
            msg.textContent = 'Sending...';

            try {
                const response = await fetch(`${API_BASE}/tickets`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: new FormData(form)
                });

                if (response.status === 201) {
                    msg.className = 'text-green-600 font-semibold mt-2';
                    msg.textContent = 'Ticket submitted successfully!';
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
            }
        });
    }
});
