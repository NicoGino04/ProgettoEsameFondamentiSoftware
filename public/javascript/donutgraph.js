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
                        'rgb(255, 205, 86)',
                        'rgba(43, 245, 87, 1)',
                        'rgb(255, 99, 132)'
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
                        },
                        tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)', // colore hover
                        titleColor: '#282a32',   // testo titolo scuro
                        bodyColor: '#282a32',    // testo body scuro
                        borderColor: 'rgba(0, 0, 0, 0.08)',
                        borderWidth: 1
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
