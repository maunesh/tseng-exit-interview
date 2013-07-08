<?
function showPieChart($data) {
    /* The data should be in the following format:
     * 
     *  array(
     *      "questionText" => "a question",
     *      "responses" => array(
     *          array(
     *              "choiceText" => "the text of a response",
     *              "count" => numberOfPeopleWhoGaveThatResponse
     *          )
     *          array(
     *              "choiceText" => "the text of another response",
     *              "count" => numberOfPeopleWhoGaveTheSecondResponse
     *          )
     *      )
     *  )
     */


    // Starts at 1 and increments each time the function is called.
    static $id = 0;
    $id++;
?>
    <div class="pie-chart" id="pie-chart-<? echo $id; ?>">
    </div>
    <script type="text/javascript">
    // Wrap everything in a function so as not to contaminate the global scope.
    // This script has no dependencies.    
    (function() {
        // Get the data from the server-side PHP to the client-side JS and do
        // some basic processing on it.
        var data = <? echo json_encode($data); ?>;
        var responses = data.responses;
        var questionText = data.questionText;
        var totalResponses = 0;

        var RADIUS = 100;
        var VERTICAL_COMPRESSION = 0.6;

        function randomColor(i) {
            //var color = "#" + (Math.random()*(1<<24)|0).toString(16);
            var colors = ["red", "orange", "yellow", "green", "blue", "purple", "black", "pink", "gray"];
            if(typeof i != "undefined") var color = colors[i];
            else var color = colors[(Math.random() * colors.length)|0];
            return color;
        }
        for(var i = 0; i < responses.length; i++) {
            if(!responses[i].color) {
                // If the wedge doesn't have a color, give it a random one.
                // Prevent two things having the same color, too.
                do {
                    responses[i].color = randomColor(i);
                    var collision = false;
                    for(var j = 0; j < i; j++) {
                        if(responses[j].color == responses[i].color) {
                            collision = true;
                        }
                    }
                } while(collision);
            }
            totalResponses += responses[i].count;
        }
        
        // Make sure we have the right element to put the chart in.
        var chartContainer = document.getElementById("pie-chart-<? echo $id; ?>");

        // The caption, in this case, contains the question. This could be
        // changed in the future.
        var caption = document.createElement("div");
        caption.innerHTML = data.questionText;
        caption.setAttribute("class", "caption");
        chartContainer.appendChild(caption);

        // The chart itself is an HTML canvas. There are upsides and downsides
        // to this approach. On the upside, 
        var chart = document.createElement("canvas");
        chart.width = 500;
        chart.height = chart.width / 2;
        chart.style.height = chart.height;
        chart.style.width = chart.width;
        chartContainer.appendChild(chart);
        chart.addEventListener("mousemove", showActiveSegment);

        // Here is where the actual drawing takes place
        var ctx = chart.getContext("2d");
        var center = {
            x: chart.width / 5,
            y: chart.height / 2
        };
        ctx.drawWedgeBG = function(start, end, radius, color, bold) {
            ctx.scale(1, VERTICAL_COMPRESSION);
            ctx.beginPath();
            // First the 3d part
            var startArc = {
                x: center.x + Math.cos(start * 2 * Math.PI) * radius,
                y: center.y + Math.sin(start * 2 * Math.PI) * radius
            }
            var endArc = {
                x: center.x + Math.cos(start * 2 * Math.PI) * radius,
                y: center.y + Math.sin(start * 2 * Math.PI) * radius
            }
            var offset3d = {
                x: 0,
                y: 15
            };
            ctx.moveTo(startArc.x, startArc.y);
            ctx.arc(center.x, center.y, radius, 2 * Math.PI * start, 2 * Math.PI * end, false);
            ctx.arc(center.x + offset3d.x, center.y + offset3d.y, radius, 2 * Math.PI * end, 2 * Math.PI * start, true);
            ctx.fillStyle = color;
            ctx.closePath();
            ctx.strokeStyle = "black";
            ctx.fill();
            if(bold) ctx.lineWidth = 3;
            else ctx.lineWidth = 1;
            ctx.stroke();
            ctx.scale(1, 1 / VERTICAL_COMPRESSION);
        }
        ctx.drawWedge = function(start, end, radius, color, bold) {
            // Start and end are numbers from 0 to 1
            ctx.scale(1, VERTICAL_COMPRESSION);
            // First the 3d part
            var startArc = {
                x: center.x + Math.cos(start * 2 * Math.PI) * radius,
                y: center.y + Math.sin(start * 2 * Math.PI) * radius
            }
            var endArc = {
                x: center.x + Math.cos(start * 2 * Math.PI) * radius,
                y: center.y + Math.sin(start * 2 * Math.PI) * radius
            }
            ctx.beginPath();
            ctx.moveTo(center.x, center.y);
            ctx.lineTo(startArc.x, startArc.y);
            
            // centerX (px), centerY (px), radius (px), startAngle (rad), endAngle (rad), counterclockwise (boolean)?
            ctx.arc(center.x, center.y, radius, 2 * Math.PI * start, 2 * Math.PI * end, false);
            ctx.fillStyle = color;
            ctx.closePath();
            ctx.strokeStyle = "black";
            ctx.fill();
            if(bold) ctx.lineWidth = 3;
            else ctx.lineWidth = 1;
            ctx.stroke();
            ctx.scale(1, 1 / VERTICAL_COMPRESSION);
        }


        var radius = 100;
        function showPieChart() {
            ctx.clearRect(0, 0, chart.width, chart.height);
            ctx.fillStyle = "black";
            ctx.fillText("Legend:", 2 * radius + 40, 20);
            for(var i = 0, responsesSoFar = 0; i < responses.length; i++) {
                // Do the drawing on the canvas
                var start = responsesSoFar / totalResponses;
                var end = (responsesSoFar + responses[i].count) / totalResponses;
                var color = responses[i].color;
                var bold = (chart.title == responses[i].choiceText);
                ctx.drawWedgeBG(start, end, radius, color, bold);
                responsesSoFar += responses[i].count;
            }
            //ctx.fillStyle = "rgba(255, 255, 255, 0.5)";
            //ctx.fillRect(center.x - radius, center.y - radius, 2 * radius, 2 * radius);
            for(var i = 0, responsesSoFar = 0; i < responses.length; i++) {
                // Do the drawing on the canvas
                var start = responsesSoFar / totalResponses;
                var end = (responsesSoFar + responses[i].count) / totalResponses;
                var color = responses[i].color;
                var bold = (chart.getAttribute("title") == responses[i].choiceText);
                ctx.drawWedge(start, end, radius, color, bold);
                responsesSoFar += responses[i].count;
            }
            showLegend();
        }
        function showLegend() {
            for(var i = 0; i < responses.length; i++) {
                // Draw the legend
                ctx.fillStyle = responses[i].color;
                ctx.fillRect(2 * radius + 10, 20 * i + 30, 10, 10);
                ctx.fillStyle = "black";
                ctx.fillText(responses[i].choiceText, 2 * radius + 25, 20 * i + 40);

                // For accessibility
                var responseAsText = document.createElement("p");
                responseAsText.innerHTML  = responses[i].count + " out of " + totalResponses + " participants";
                responseAsText.innerHTML += " chose \"" + responses[i].choiceText + ".\"";
                chart.appendChild(responseAsText);

            }
        }
        function showActiveSegment(e) {
            var x = e.pageX - chart.offsetLeft;
            var y = e.pageY - chart.offsetTop;
            var dx = x - center.x;
            var dy = y / VERTICAL_COMPRESSION - center.y;
            var origTitle = chart.getAttribute("title");
            function showTitleText(curTitle) {
                // Remove it first so that it shows in the right spot.
                if(chart.getAttribute("title") && chart.getAttribute("title") != curTitle) {
                    chart.removeAttribute("title");
                }
                if(curTitle !== origTitle) {
                    setTimeout(function() {
                        chart.setAttribute("title", curTitle);
                        showPieChart();
                    }, 1);
                }
            };
            if(dx * dx + dy * dy < RADIUS * RADIUS) {
                for(var i = 0, responsesSoFar = 0; i < responses.length; i++) {
                    var start = responsesSoFar / totalResponses;
                    var end = (responsesSoFar + responses[i].count) / totalResponses;
                    var distanceAroundCircle = Math.atan2(dy, dx) / (2 * Math.PI);
                    if(distanceAroundCircle < 0) distanceAroundCircle = 1 + distanceAroundCircle;
                    if(start < distanceAroundCircle && distanceAroundCircle < end) {
                        showTitleText(responses[i].choiceText);
                    }
                    responsesSoFar += responses[i].count;
                }
            }
            else {
                if(origTitle) {
                    chart.removeAttribute("title");
                    showPieChart();
                }
                chart.removeAttribute("title");
            }
        }
        showPieChart();
    })();
    </script>
<?
}

?>
