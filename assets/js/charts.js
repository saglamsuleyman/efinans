if (window.Chart && window.efinansCharts) {
    Chart.defaults.color = '#a4acb9';
    Chart.defaults.borderColor = 'rgba(255,255,255,.08)';
    Chart.defaults.font.family = 'Arial, Helvetica, sans-serif';

    const palette = {
        red: '#e50914',
        green: '#23c483',
        white: '#f6f7fb',
    };

    const categories = ['Kripto', 'Döviz', 'Emtia'];

    categories.forEach((category) => {
        const data = window.efinansCharts[category];
        if (!data) {
            return;
        }

        const line = document.getElementById(`chart${category}Line`);
        const bar = document.getElementById(`chart${category}Bar`);
        const change = document.getElementById(`chart${category}Change`);

        if (line) {
            new Chart(line, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Fiyat',
                        data: data.prices,
                        borderColor: palette.red,
                        backgroundColor: 'rgba(229,9,20,.14)',
                        fill: true,
                        tension: .35,
                    }],
                },
                options: { responsive: true, plugins: { legend: { display: false } } },
            });
        }

        if (bar) {
            new Chart(bar, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Hacim',
                        data: data.volumes,
                        backgroundColor: 'rgba(246,247,251,.78)',
                    }],
                },
                options: { responsive: true, plugins: { legend: { display: false } } },
            });
        }

        if (change) {
            new Chart(change, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Değişim',
                        data: data.changes,
                        backgroundColor: data.changes.map((value) => value >= 0 ? palette.green : palette.red),
                    }],
                },
                options: { responsive: true, plugins: { legend: { display: false } } },
            });
        }
    });
}
