/*!
    Graph Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/


if (nilai === undefined) { var nilai = {}; }
if (nilai.graph === undefined) { nilai.graph = {}; }

(function ($) {

    nilai.graph.getMaxY = function () {
        var max = 0;
        
        for(var i = 0; i < this.data.length; i ++) {
            if(this.data[i] > max) {
                max = this.data[i];
            }
        }
        
        max += 10 - max % 10;
        return max;
    };

    nilai.graph.getXPixel = function (val) {
        return ((this.graph.width() - this.xPadding) / this.data.length) * val + (this.xPadding * 1.5);
    };

    nilai.graph.getYPixel = function (val) {
        return this.graph.height() - (((this.graph.height() - this.yPadding) / this.getMaxY()) * val) - this.yPadding;
    };

    nilai.graph.initGraph = function (obj, x, y, data, color, fill) {

        this.graph       = obj,
        this.xPadding    = x,
        this.yPadding    = y,
        this.data        = data;
        

        var c = this.graph[0].getContext('2d');            
        
        c.lineWidth = 2;
        c.strokeStyle = color;
        
        // Draw the line graph
        c.beginPath();
        c.moveTo(this.getXPixel(0), this.getYPixel(this.data[0]));
        for(var i = 1; i < this.data.length; i ++) {
            c.lineTo(this.getXPixel(i), this.getYPixel(this.data[i]));
        }
        c.stroke();
        
        // Draw the dots
        c.fillStyle = fill;
        
        for(var i = 0; i < this.data.length; i ++) {  
            c.beginPath();
            c.arc(this.getXPixel(i), this.getYPixel(this.data[i]), 4, 0, Math.PI * 2, true);
            c.fill();
        }
    };

}(window.jQuery));