window.onload = function () {

    fetch('/splinechart', {
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

            console.log('Parametri linechart:', data);

            // Trasformo i dati backend in dataPoints CanvasJS
            const dataPoints = data.data.map(item => ({
                x: new Date(item.data),
                y: parseInt(item.totale)
            }));

            var chart = new CanvasJS.Chart("chartContainer",
                {

                    axisY: {
                        lineColor: "#dcdcdc",        // colore asse Y

                        valueFormatString: "#0,,.",
                        //suffix: " m",
                        gridColor: "#e6e6e6",     // colore più chiaro
                        gridThickness: 0.8,         // spessore griglia orizzontale
                    },
                    axisX: {
                        valueType: "dateTime",
                        // mostra solo la data
                        valueFormatString: "YYYY-MM-DD",
                        // forza un punto al giorno
                        intervalType: "day",
                        interval: 1,

                        startOnTick: true,
                        endOnTick: true,

                        lineColor: "#dcdcdc",        // colore asse X
                        gridThickness: 0,         // elimina griglia verticale
                        title: "Data",
                    },
                    backgroundColor: "transparent",
                    plotArea: {
                        backgroundColor: "transparent" //rende lo sfondo del grafico trasparente
                    },
                    data: [
                        {
                            toolTipContent: "{y} units",
                            type: "splineArea",
                            showInLegend: true,

                            markerSize: 5,
                            color: "rgba(54,158,173,.7)",
                            dataPoints: dataPoints
                        }
                    ]
                });

            console.log("DataPoints:", dataPoints);
            chart.render();

        })
        .catch(error => {
            console.error('Errore:', error);
            Swal.fire({
                icon: 'error',
                title: error.message,
                confirmButtonColor: '#d33'
            });
        })

}
