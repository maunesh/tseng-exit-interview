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
    static $id = 1;
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

        function randomColor() {
            //var color = "#" + (Math.random()*(1<<24)|0).toString(16);
            var colors = ["red", "orange", "yellow", "green", "blue", "purple", "black", "pink", "gray"];
            var color = colors[(Math.random() * colors.length)|0];
            return color;
        }
        for(var i = 0; i < responses.length; i++) {
            if(!responses[i].color) {
                // If the wedge doesn't have a color, give it a random one.
                // Prevent two things having the same color, too.
                do {
                    responses[i].color = randomColor();
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
        chart.width = 400;
        chart.height = chart.width / 2;
        chart.style.height = chart.height;
        chart.style.width = chart.width;
        chartContainer.appendChild(chart);

        // Here is where the actual drawing takes place
        var ctx = chart.getContext("2d");
        var center = {
            x: chart.width / 4,
            y: chart.height / 2
        };
        ctx.drawWedge = function(start, end, radius, color) {
            // Start and end are numbers from 0 to 1
            ctx.beginPath();
            ctx.moveTo(center.x, center.y);
            var startArc = {
                x: center.x + Math.cos(start * 2 * Math.PI) * radius,
                y: center.y + Math.sin(start * 2 * Math.PI) * radius
            }
            ctx.lineTo(startArc.x, startArc.y);
            
            // centerX (px), centerY (px), radius (px), startAngle (rad), endAngle (rad), counterclockwise (boolean)?
            ctx.arc(center.x, center.y, radius, 2 * Math.PI * start, 2 * Math.PI * end, false);
            ctx.fillStyle = color;
            ctx.closePath();
            ctx.fill();
        }

        var responsesSoFar = 0;

        var radius = 100;
        ctx.fillStyle = "black";
        ctx.fillText("Legend:", 2 * radius + 30, 20);
        for(var i = 0; i < responses.length; i++) {

            // Do the drawing on the canvas
            var start = responsesSoFar / totalResponses;
            var end = (responsesSoFar + responses[i].count) / totalResponses;
            var color = responses[i].color;
            ctx.drawWedge(start, end, radius, color);

            // Draw the legend
            ctx.fillStyle = color;
            ctx.fillRect(2 * radius, 20 * i + 30, 10, 10);
            ctx.fillStyle = "black";
            ctx.fillText(responses[i].choiceText, 2 * radius + 15, 20 * i + 40);

            // For accessibility
            var responseAsText = document.createElement("p");
            responseAsText.innerHTML  = responses[i].count + " out of " + totalResponses + " participants";
            responseAsText.innerHTML += " chose \"" + responses[i].choiceText + ".\"";
            chart.appendChild(responseAsText);

            responsesSoFar += responses[i].count;
        }
    })();
    </script>
<?
}

$q1data = array(
    "questionText" => "What degree did you earn from the Tseng College?",
    "responses" => array(
        array(
            "choiceText" => "answered question",
            "count" => 4
        ),
        array(
            "choiceText" => "skipped question",
            "count" => 6
        )
    )
);
showPieChart($q1data);
?>
