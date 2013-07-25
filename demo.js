var xhr = new XMLHttpRequest();
xhr.open("GET", "./results.csv");
xhr.send();
xhr.onreadystatechange = function() {
    if(xhr.response) {
        if(document.reasyState === "complete") {
            show(xhr.response);
        }
        else {
            window.addEventListener("load", function() {
                show(xhr.response);
            });
        }
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
    var questionList = document.createElement("div");
    var resultsDiv = document.getElementById('results');
    var questionNav = document.getElementById('question-nav');
    //var questionNavTimeout = setTimeout(stickyQuestionNav, 500);
    
    function stickyQuestionNav() {
        var currentMTop = parseInt(questionNav.style["margin-top"].toString().match(/\d+/g));
        var newMTop = currentMTop;
        var dy = window.scrollY - questionNav.offsetTop;
        newMTop += dy;
        if(newMTop < 0) {
            newMTop = 0;
        }
        questionNav.style["margin-top"] = newMTop + 'px';
        questionNavTimeout = setTimeout(stickyQuestionNav, 500);
    }
    document.addEventListener('scroll', function() {
        clearTimeout(questionNavTimeout);
        questionNavTimeout = setTimeout(stickyQuestionNav, 500);
    });
    resultsDiv.appendChild(questionList);
    //document.body.appendChild(questionList);
    var pieChartId = 1;
    var barChartId = 1;
    questions.forEach(function(question, index) {
        //accessibility
        var qID = 'question-' + (index + 1);
        var navLi = document.createElement('li');
        navLi.style['line-height'] = '2em';
        var navLink = document.createElement('a');
        navLink.innerHTML = "Question " + (index+1);
        navLink.href = '#' + qID;
        navLink.title = question.questionText;
        navLink.classList.add("btnarrow");
        navLi.appendChild(navLink);
        questionNav.appendChild(navLi);

        var questionContainer = document.createElement("div");
        var questionTitle = document.createElement("h2");
        questionTitle.id = qID;
        var hiddenIndicator = document.createElement('a');
        var shownIndicator = document.createElement('a');
        hiddenIndicator.innerHTML = "Show responses >";
        shownIndicator.innerHTML = "< Hide responses";
        hiddenIndicator.classList.add("btnarrow");
        shownIndicator.classList.add("btnarrow");
        shownIndicator.classList.add("hidden");
        var questionResponses = document.createElement("div");
        questionResponses.classList.add('hidden');
        questionTitle.addEventListener('click', showHideResponses);
        hiddenIndicator.addEventListener('click', showHideResponses);
        shownIndicator.addEventListener('click', showHideResponses);
        questionResponses.addEventListener('click', showHideResponses);
        function showHideResponses() {
            questionResponses.classList.toggle('hidden');
            hiddenIndicator.classList.toggle('hidden');
            shownIndicator.classList.toggle("hidden");
        }
        questionContainer.appendChild(questionTitle);
        questionContainer.appendChild(hiddenIndicator);
        questionContainer.appendChild(shownIndicator);
        questionContainer.appendChild(questionResponses);
        questionTitle.innerHTML = (index+1) + '. ' + question.questionText;
        if(question.type == "Open-Ended Response") {
            var responseList = document.createElement("ul");
            question.responses.forEach(function(response, index) {
                if(response) {
                    var responseContainer = document.createElement("li");
                    responseContainer.innerHTML = response;
                    responseList.appendChild(responseContainer);
                }
            });
            questionResponses.appendChild(responseList);
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
                questionResponses.appendChild(pieChart);
                pieChartId += 1;
            }
            else {
                var barChart = getBarChart(question, barChartId);
                questionResponses.appendChild(barChart);
                barChartId += 1;
            }
        }
        questionList.appendChild(questionContainer);
    });
    function $$(query) {
        try {
            return Array.prototype.slice.call(document.querySelectorAll(query));
        }
        catch(e) {
            console.log("Query `" + query + "` failed");
            console.log(e);
            return [];
        }
    }
    $$("ol li h2").forEach(function(h2) {
        h2.addEventListener("click", function() {
            var responses = h2.parentNode.getElementsByTagName("ul")[0];
        })
    });
}
