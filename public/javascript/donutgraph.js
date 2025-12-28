document.addEventListener('DOMContentLoaded', function () {

  const ctx = document
    .getElementById('myDoughnutChart')
    .getContext('2d');

  const data = {
    labels: ['Proteine', 'Carboidrati', 'Lipidi'],
    datasets: [{
      label: 'Esempio Doughnut',
      data: [20, 50, 100], //valori di esempio del grafico a torta
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
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%',
      plugins: {
        title: {
          display: true,
          text: '' //testo sopra la legenda
        }
      }
    }
  };

  new Chart(ctx, config);

});