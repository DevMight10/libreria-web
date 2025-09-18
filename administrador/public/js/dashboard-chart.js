document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('ventasChart');
    if (!canvas) {
        return;
    }

    const fechas = JSON.parse(canvas.dataset.fechas);
    const ventas = JSON.parse(canvas.dataset.ventas);

    const ctx = canvas.getContext('2d');
    const ventasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Ventas (Bs.)',
                data: ventas,
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Bs. ' + value;
                        }
                    }
                }
            }
        }
    });
});
