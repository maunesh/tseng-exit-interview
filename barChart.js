function getBarChart(question, id) {
    // Wrap everything in a function so as not to contaminate the global scope.
    // This script has no dependencies.    
    // Get the data from the server-side PHP to the client-side JS and do
    // some basic processing on it.
    var data = question;
    var responses = data.responses;
    var questionText = data.questionText;
    var totalResponses = 0;
    var mostForOneChoice = 0;

    function getColor(i) {
        var colors = ["red", "orange", "yellow", "green", "blue", "purple", "black", "pink", "gray"];
        return colors[i];
    }
    for(var i = 0; i < responses.length; i++) {
        responses[i].color = getColor(i);
        totalResponses += responses[i].count;
        if(mostForOneChoice < responses[i].count) mostForOneChoice = responses[i].count;
    }

    // Make sure we have the right element to put the chart in.
    var chartContainer = document.createElement("div");
    chartContainer.setAttribute("id", "pie-chart-" + id);

    // The caption, in this case, contains the question. This could be
    // changed in the future.
    var caption = document.createElement("div");
    caption.innerHTML = data.questionText;
    caption.setAttribute("class", "caption");
    chartContainer.appendChild(caption);

    // The chart itself is an HTML canvas. There are upsides and downsides
    // to this approach. On the upside, 
    var chart = document.createElement("canvas");
    chart.width = 400;
    chart.height = chart.width / 2;
    chart.style.height = chart.height;
    chart.style.width = chart.width;
    chartContainer.appendChild(chart);

    // Here is where the actual drawing takes place
    var ctx = chart.getContext("2d");
    ctx.lineWidth = 1;
    ctx.strokeStyle = "black";
    ctx.fillStyle = "black";
    var baseY = chart.height * 0.8;
    ctx.drawBar = function(x, height, color, bold) {
        // Start and end are numbers from 0 to 1
        ctx.fillStyle = color;
        ctx.fillRect(x, baseY - height, 20, height);
        ctx.lineWidth = 2 + 2 * bold;
        ctx.strokeStyle = "black";
        ctx.strokeRect(x - 1, baseY - height - 1, 20 + 1, height + 1);
        ctx.lineWidth = 1;
    }

    function showBarChart() {
        ctx.clearRect(0, 0, chart.width, chart.height);
        ctx.fillStyle = "black";
        ctx.lineWidth = 1;
        ctx.fillRect(0, baseY - 1, 30 * responses.length + 10, 3);
        var responsesSoFar = 0;
        for(var i = 0; i < responses.length; i++) {

            // Do the drawing on the canvas
            var x = i * 30 + 10;
            var height = responses[i].count * chart.height * 0.6 / mostForOneChoice;
            var color = responses[i].color;
            var bold = (chart.title == responses[i].choiceText);
            ctx.drawBar(x, height, color, bold);

            // Draw the legend

            // For accessibility
            var responseAsText = document.createElement("p");
            responseAsText.innerHTML  = responses[i].count + " out of " + totalResponses + " participants";
            responseAsText.innerHTML += " chose \"" + responses[i].choiceText + ".\"";
            chart.appendChild(responseAsText);

            responsesSoFar += responses[i].count;
        }
        showLegend();
    }
    function showLegend() {
        for(var i = 0; i < responses.length; i++) {
            // Draw the legend
            ctx.fillStyle = responses[i].color;
            ctx.fillRect(30 * responses.length + 20, 20 * i + 30, 10, 10);
            ctx.fillStyle = "black";
            ctx.fillText(responses[i].choiceText, 30 * responses.length + 35, 20 * i + 40);

            // For accessibility
            var responseAsText = document.createElement("p");
            responseAsText.innerHTML  = responses[i].count + " out of " + totalResponses + " participants";
            responseAsText.innerHTML += " chose \"" + responses[i].choiceText + ".\"";
            chart.appendChild(responseAsText);
        }
    }
    function showActiveBar(e) {
        var x = e.pageX - chart.offsetLeft;
        var y = e.pageY - chart.offsetTop;
        var origTitle = chart.getAttribute("title");
        function showTitleText(curTitle) {
            // Remove it first so that it shows in the right spot.
            if(chart.getAttribute("title") && chart.getAttribute("title") != curTitle) {
                chart.removeAttribute("title");
            }
            if(curTitle !== origTitle) {
                setTimeout(function() {
                    chart.setAttribute("title", curTitle);
                    showBarChart();
                }, 1);
            }
        };
        var isInsideBar = false;
        for(var i = 0; i < responses.length; i++) {

            // Do the drawing on the canvas
            var barX = i * 30 + 10;
            var height = responses[i].count * chart.height * 0.6 / mostForOneChoice;
            if(x > barX && x < barX + 20 && y > baseY - height && y < baseY) {
                showTitleText(responses[i].choiceText);
                isInsideBar = true;
            }
        }
        if(!isInsideBar) {
            if(origTitle) {
                chart.removeAttribute("title");
                showBarChart();
            }
            chart.removeAttribute("title");
        }
    }
    showBarChart();
    chart.addEventListener("mousemove", showActiveBar);
    return chartContainer;
};
