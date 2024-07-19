questions = document.getElementsByClassName('question')
current = 0;

if (questions.length > 0) 
{
    questions[0].id = "current-question"
}

function Click(indexQuestion, indexAnswer)
{
    if (quiz.IsClicked(indexQuestion))
        return;

    question = questions[indexQuestion];
    question.classList.add("answered-question");
    answer = question
        .getElementsByClassName("question-buttons")[0]
        .getElementsByTagName("button")[indexAnswer];

    let value = quiz.Click(indexQuestion, indexAnswer);

    explanation = question
        .getElementsByClassName("question-explanation")[0];

    explanation.style.visibility = "visible";

    if (value < 1)
    {
        answer.classList.add("button-wrong");
    }
    else
        answer.classList.add("button-correct");


    if (quiz.AllAnswered())
    {
        EndQuiz();
    }
}

function SwitchToQuestion(indexQuestion)
{
    questions[current].id = ""
    questions[indexQuestion].id = "current-question"
    current = indexQuestion
}

function SendTestResults()
{
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', score_insert_post_link);
    form.style.display = 'hidden';

    var action = document.createElement("input");
    action.setAttribute('type', 'hidden');
    action.setAttribute('name', 'action');
    action.setAttribute('value', 'insert-score');
    form.appendChild(action);

    var score = document.createElement("input");
    score.setAttribute('type', 'text');
    score.setAttribute('name', 'score');
    score.setAttribute('value', quiz.score);
    form.appendChild(score);

    var testInfo = document.createElement("input");
    testInfo.setAttribute('type', 'text');
    testInfo.setAttribute('name', 'testInfo');
    testInfo.setAttribute('value', page_info);
    form.appendChild(testInfo);

    var percent = document.createElement("input");
    percent.setAttribute('type', 'text');
    percent.setAttribute('name', 'percent');
    percent.setAttribute('value', quiz.GetPercentCorrect());
    form.appendChild(percent);

    var refs = document.createElement("input");
    refs.setAttribute('type', 'text');
    refs.setAttribute('name', 'refs');
    refs.setAttribute('value', quiz.NeededReferences());
    form.appendChild(refs);

    document.body.appendChild(form);
    form.submit();
}

function EndQuiz()
{
    btn = document.getElementById("end_test_btn");
    btn.style.display = "block";
}

function fadein(div) {
    div.style.opacity = ctr !== 10 ? '0.' + ctr : 1;
    div.style.transform = ctr !== 10 ? 'scale(' + ('0.' + ctr) + ')' : 'scale(1)';
    ctr++;

    if (ctr < 11)
        requestAnimationFrame(fadein);
    else
        ctr = 0;
}