// chart-area-demo.js (varias instancias por clase .myAreaChart)

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Datos por defecto (usados si el canvas no provee data-* personalizados)
var DEFAULT_AREA_LABELS = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
var DEFAULT_AREA_DATA = [0,10000,5000,15000,10000,20000,15000,25000,20000,30000,25000,40000];

// Función que crea el config para un chart (recibe labels y data array)
function createAreaChartConfig(labels, data) {
  return {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: "Earnings",
        lineTension: 0.3,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(78, 115, 223, 1)",
        pointBorderColor: "rgba(78, 115, 223, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        data: data
      }]
    },
    options: {
      maintainAspectRatio: false,
      layout: {
        padding: { left: 10, right: 25, top: 25, bottom: 0 }
      },
      scales: {
        xAxes: [{
          time: { unit: 'date' },
          gridLines: { display: false, drawBorder: false },
          ticks: { maxTicksLimit: 7 }
        }],
        yAxes: [{
          ticks: {
            maxTicksLimit: 5,
            padding: 10,
            callback: function(value, index, values) {
              return '$' + number_format(value);
            }
          },
          gridLines: {
            color: "rgb(234, 236, 244)",
            zeroLineColor: "rgb(234, 236, 244)",
            drawBorder: false,
            borderDash: [2],
            zeroLineBorderDash: [2]
          }
        }],
      },
      legend: { display: false },
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        intersect: false,
        mode: 'index',
        caretPadding: 10,
        callbacks: {
          label: function(tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
            return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
          }
        }
      }
    }
  };
}

// Inicializar todos los canvases con clase .myAreaChart
(function initAllAreaCharts() {
  var canvases = document.querySelectorAll('.myAreaChart');
  if (!canvases || canvases.length === 0) {
    // No hay canvases in this page — no hacer nada
    return;
  }

  canvases.forEach(function(canvas, idx) {
    try {
      var labels = DEFAULT_AREA_LABELS.slice();
      var data = DEFAULT_AREA_DATA.slice();

      // Si el canvas tiene data-labels o data-values (JSON), intentar parsear
      if (canvas.dataset.labels) {
        try {
          var parsedLabels = JSON.parse(canvas.dataset.labels);
          if (Array.isArray(parsedLabels)) labels = parsedLabels;
        } catch (e) {
          console.warn('data-labels inválido en canvas .myAreaChart #' + idx, e);
        }
      }
      if (canvas.dataset.values) {
        try {
          var parsedValues = JSON.parse(canvas.dataset.values);
          if (Array.isArray(parsedValues)) data = parsedValues;
        } catch (e) {
          console.warn('data-values inválido en canvas .myAreaChart #' + idx, e);
        }
      }

      // Crear chart
      var ctx = canvas.getContext('2d');
      var cfg = createAreaChartConfig(labels, data);
      new Chart(ctx, cfg);

    } catch (err) {
      console.error('Error inicializando myAreaChart #' + idx, err);
    }
  });
})();
