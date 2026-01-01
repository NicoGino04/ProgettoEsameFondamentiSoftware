let chart
document.addEventListener('DOMContentLoaded', function () {

    fetch('/macronutrienti', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.error || 'Errore di caricamento');
                }
                return data;
            });
        })
        .then(data => {
            console.log('Macronutrienti:', data);

            const ctx = document
                .getElementById('myDoughnutChart')
                .getContext('2d');

            const chartData = {
                labels: ['Carboidrati', 'Lipidi', 'Proteine'],
                datasets: [{
                    label: 'Macronutrienti',
                    data: [
                        data.carboidrati,
                        data.grassi,
                        data.proteine
                    ],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgba(43, 245, 87, 1)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            };
            const config = {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        title: {
                            display: true,
                            text: '' //testo sopra la legenda
                        },
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            };

            chart = new Chart(ctx, config);

        })
        .catch(error => {
            console.error('Errore:', error);
            Swal.fire({
                icon: 'error',
                title: error.message,
                confirmButtonColor: '#d33'
            });
        })
        .finally(() => {
        })
});
