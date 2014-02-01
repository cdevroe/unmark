/*!
    Graph Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/


if (nilai === undefined) { var nilai = {}; }
if (nilai.graph === undefined) { nilai.graph = {}; }

(function ($) {

    // changes between data sets in global graph object
    nilai.graph.advanceGraph = function () {
        if(this.graphData.current < this.graphData.charts.length -1) {
            this.graphData.current++;
        } else {
            this.graphData.current = 1;
        }

        // animate to new data positions
        this.animatePoints(this.graphData, this.graphData.charts[this.graphData.current]);
    };

    // draw initial points
    nilai.graph.drawPoints = function (data, options) {

        var i, xPos, yPos, circle, length,
            radius = options.radius, // point radius
            points = data.charts[0].points; // set points to initial data set

        for (i = 0, length = points.length; i < length; i++) {
            // calculate x and y positions: x delta is a constant, y value is intially set to start at 0 on y axis
            // xOffset and yOffets values are the locations within the canvas where the x and y axes are located
            xPos = data.xOffset + (i * data.xDelta);
            yPos = data.yOffset;
      
            circle = data.paper.circle(xPos, yPos, radius);
            circle.attr(this.pointOptions);
            points[i].point = circle; // store raphael.js point object in global data set
        }   
    };

    // animate points into new positions 
    // data is the global data object, newData is the new dataset to animate to
    nilai.graph.animatePoints = function (data, newData) {
        
        var scaleFactor, points, i, length,
            newPath = '', // varibale to hold new raphael path string
            upperLimit = parseInt(newData.upper), // upper and lower limits are the limits of the data set and are used to scale the data values into pixel positions
            lowerLimit = parseInt(newData.lower);
    
        if(isNaN(upperLimit)) {
            upperLimit = 1; // don't set to 0 to avoid divide by 0 error
        }

        if(isNaN(lowerLimit)) {
            lowerLimit = 0;
        }

        scaleFactor = data.yOffset / (upperLimit - lowerLimit) ; // used to calculate pixel positions based on limits
        points = data.charts[0].points; // get initial points from global data

        for (i = 0, length = points.length; i < length; i++) {
            if(i == 0) {
                newPath += 'M 25 291 L '; // I have hard coded the start of the line, sorry
                newX = data.xOffset + ' '; // since the x axis is constant, pass along the original x coordinate
                newPath += newX;
                newY = data.yOffset - ((newData.points[i].value - lowerLimit) * scaleFactor) + ' '; // calculate the new y value using scale factor and limits
                newPath += newY; // add new y to path string
            } else {
                newPath += ' L ';
                newX = data.xOffset + (i * data.xDelta) + ' ';
                newPath += newX;
                newY = data.yOffset - ((newData.points[i].value - lowerLimit) * scaleFactor);
                newPath += newY;
            }
      
            // animate raphael.js points to new positions
            points[i].point.animate({
                cy : data.yOffset - ((newData.points[i].value - lowerLimit) * scaleFactor)
            }, 800, 'ease-in-out' );
        }

        newPath += ' L 500 291 Z'; // add end of path string, sorry hardcoded again
        data.line.animate({path : newPath}, 800, 'ease-in-out'); // animate raphael.js line into new position

    };

    /* create a raphael.js path string based on a data set */
    nilai.graph.createPathString = function (data) {

        var points = data.charts[data.current].points;

        
        var path = 'M 25 291 L ' + data.xOffset + ' ' + (data.yOffset - points[0].value);
        var prevY = data.yOffset - points[0].value;

        for (var i = 1, length = points.length; i < length; i++) {
          path += ' L ';
          path += data.xOffset + (i * data.xDelta) + ' ';
          path += (data.yOffset - points[i].value);

          prevY = data.yOffset - points[i].value;
        }
        path += ' L 989 291 Z';
        return path;
    };

    // Init Script
    nilai.graph.initGraph = function () {

        // point attributes object to pass to raphael.js
        this.pointOptions = {'fill' : '#333333', 'stroke' : '#7a7a7a', radius : 6 }

        // line attributes object to pass to raphael.js  
        this.lineOptions = {'stroke': 'rgba(102, 102, 102, .08)', 'stroke-width': 2, 'fill': '#000', 'fill-opacity': 0.03 }

        this.graphData = {
          current     : 0, // constant distance between points on the x axis
          xDelta      : 69, // location of y axis in horizontal space
          xOffset     : 100, // location of x axis in vertical space
          yOffset     : 150,
          charts      :[
              {
                  lower  : 0,
                  upper  : 200,
                  points : [
                      { value : 0},
                      { value : 0},
                      { value : 0},
                      { value : 0},
                      { value : 0}
                  ]
              },
              {
                  lower  : 0,
                  upper  : 200,
                  points : [
                      { value : 58},
                      { value : 64},
                      { value : 12},
                      { value : 90},
                      { value : 101}
                  ]
              },
              {
                  lower  : 0,
                  upper  : 200,
                  points : [
                      { value : 132},
                      { value : 112},
                      { value : 124},
                      { value : 73},
                      { value : 92}
                  ]
              }
          ]
        };


        // set up raphael.js canvas with the elements of the graph element
        var paper = new Raphael(document.getElementById('line-graph'), $('#line-graph').width(), $('#line-graph').height());  
        this.graphData.paper = paper;
        
        // create initial line
        var path = this.createPathString(this.graphData);

        // draw intial line with raphael.js
        var line = paper.path(path); 
        
        // set line drawing attributes
        line.attr(this.lineOptions);
        
        // save line to our global(I know, I know) data object
        this.graphData.line = line;

        // draw initial points
        this.drawPoints(this.graphData, this.pointOptions);
        
        /* set graph auto changing (for demo purposes) */
        setInterval(function(){
          nilai.graph.advanceGraph();
        }, 2000);
        this.advanceGraph();
    }



    $(document).ready(function(){ nilai.graph.initGraph(); });

}(window.jQuery));