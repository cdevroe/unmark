/*!
    Graph Main scripts for Unmark.it
    REQUIRES - Chart.min.js file
*/


if (unmark === undefined) { var unmark = {}; }
if (unmark.graph === undefined) { unmark.graph = {}; }

(function ($) {

    unmark.createGraph = function (a4, a3, a2, a1, a, s4, s3, s2, s1, s) {

        var lineChartData, lineChartOptions, chartElement;

        lineChartElement = document.getElementById("unmark-graph").getContext("2d");

        lineChartData = {
            labels : ["4 Days Ago","3 Days Ago","2 Days Ago", "Yesterday", "Today"],
            datasets : [
                {
                    // Archived Settings
                    strokeColor : "#DCDCDC",
                    pointColor : "#C1C1C1",
                    pointStrokeColor : "#fff",
                    data : [a4, a3, a2, a1, a]
                },
                {
                    // Saved Settings
                    strokeColor : "#B8B7B7",
                    pointColor : "#777777",
                    pointStrokeColor : "#fff",
                    data : [s4, s3, s2, s1, s]
                }
            ]
            
        }

        lineChartOptions = {
            scaleShowLabels : false,
            scaleShowGridLines : false,
            datasetFill : false,
            bezierCurve : false,
            scaleLineColor: 'transparent',
            scaleFontColor: 'transparent'            
        }

        new Chart(lineChartElement).Line(lineChartData, lineChartOptions);

    };

}(window.jQuery));