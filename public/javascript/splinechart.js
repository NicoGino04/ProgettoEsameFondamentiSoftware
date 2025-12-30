window.onload = function () {
		var chart = new CanvasJS.Chart("chartContainer",
		{
			   
			axisY: {
        lineColor: "#dcdcdc",        // colore asse Y
				title: "Bicchieri d'acqua",
        valueFormatString: "#0,,.",
        suffix: " m",
        gridColor: "#e6e6e6",     // colore più chiaro
        gridThickness: 0.8,         // spessore griglia orizzontale
			},
      axisX: {
        lineColor: "#dcdcdc",        // colore asse X
        gridThickness: 0,         // elimina griglia verticale
        title: "Tempo",
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
				dataPoints: [
				{x: new Date(1992,0), y: 2506000},     
				{x: new Date(1993,0), y: 2798000},     
				{x: new Date(1994,0), y: 3386000},     
				{x: new Date(1995,0), y: 6944000},     
				{x: new Date(1996,0), y: 6026000},     
				{x: new Date(1997,0), y: 2394000},     
				{x: new Date(1998,0), y: 1872000},     
				{x: new Date(1999,0), y: 2140000},     
				{x: new Date(2000,0), y: 7289000, indexLabel: "highest"},     
				{x: new Date(2001,0), y: 4830000},     
				{x: new Date(2002,0), y: 2009000},     
				{x: new Date(2003,0), y: 2840000},     
				{x: new Date(2004,0), y: 2396000},     
				{x: new Date(2005,0), y: 1613000},     
				{x: new Date(2006,0), y: 2821000},     
				{x: new Date(2007,0), y: 2000000},     
				{x: new Date(2008,0), y: 1397000}    
				]
			}             
			]
		});

chart.render();
}