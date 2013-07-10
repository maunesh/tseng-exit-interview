var xhr = new XMLHttpRequest();
xhr.open("GET", "./results.csv");
xhr.send();
xhr.onreadystatechange = function() {
    if(xhr.response) {
        show(xhr.response);
        xhr.onreadystatechange = function(){};
    }
}
function show(csv_data) {
    var metaColCount = 9;
    var data = CSV.parse(csv_data);
    // First row, columns 9 and up.
    var questions = data[0]
        .slice(metaColCount, data[0].length)
        .map(function(question, index) {
            return {
                questionText: question,
                responses: []
            }
        });
    var types     = data[1]
        .slice(metaColCount, data[1].length)
        .forEach(function(type, index) {
            questions[index].type = type;
        });
    var responses = data
        .slice(2, data.length)
        .map(function(row, responseIndex) {
            row
                .slice(metaColCount, row.length)
                .forEach(function(response, questionIndex) {
                    questions[questionIndex].responses.push(response);
                });
        });
    // Collapse multiple answer options -- untested on more than 2 options, so beware.
    for(var i = 0; i < questions.length; i++) {
        var j = i;
        while(!questions[j].questionText) {
            j -= 1;
            for(var k = 0; k < questions[j].responses.length; k++) {
                if(!questions[j].responses[k]) {
                    questions[j].responses[k] = questions[j+1].responses[k];
                    questions[j+1].responses[k] = "";
                }
            }
            questions.splice(j+1, i - j);
        }
    }
    var questionList = document.createElement("ol");
    document.body.appendChild(questionList);
    var pieChartId = 1;
    var barChartId = 1;
    questions.forEach(function(question, index) {
        var questionContainer = document.createElement("li");
        var questionTitle = document.createElement("h2");
        questionContainer.appendChild(questionTitle);
        questionTitle.innerHTML = question.questionText;
        if(question.type == "Open-Ended Response") {
            var responseList = document.createElement("ul");
            question.responses.forEach(function(response, index) {
                if(response) {
                    var responseContainer = document.createElement("li");
                    responseContainer.innerHTML = response;
                    responseList.appendChild(responseContainer);
                }
            });
            questionContainer.appendChild(responseList);
        }
        else {
            var responses = {};
            question.responses.forEach(function(response) {
                if(responses[response]) {
                    responses[response].count += 1;
                }
                else {
                    responses[response] = {
                        choiceText: response,
                        count: 1
                    };
                }
            });
            if(question.questionText.match(/1 to 5/g)) {
                for(var i = 1; i <= 5; i++) {
                    if(!responses[i.toString()]) {
                        responses[i.toString()] = {
                            choiceText: i.toString(),
                            count: 0
                        };
                    }
                }
            }
            question.responses = [];
            for(key in responses) {
                var response = responses[key];
                question.responses.push(response);
            };
            console.log(question);
            if(question.type) {
                var pieChart = getPieChart(question, pieChartId);
                questionContainer.appendChild(pieChart);
                pieChartId += 1;
            }
            else {
                var barChart = getBarChart(question, barChartId);
                questionContainer.appendChild(barChart);
                barChartId += 1;
            }
        }
        questionList.appendChild(questionContainer);
    });
    console.log(questions);
}
