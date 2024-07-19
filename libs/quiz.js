//Класс, который представляет сам тест
class Quiz
{
    constructor()
    {
        //Массив с вопросами
        this.questions = [];

        //Количество набранных очков
        this.score = 0;

        //Завершён ли тест
        this.completed = false;
    }

    IsClicked(questionIndex)
    {
        return this.questions[questionIndex].clicked;
    }

    Click(questionIndex, answerIndex)
    {
        if (this.questions[questionIndex].clicked)
            return;

        //Добавляем очки
        let value = this.questions[questionIndex].Click(answerIndex);
        this.score += value;

        return value;
    }

    AllAnswered()
    {
        for (const question of this.questions) {
            if (question.clicked == false)
                return false;
        }

        return true;
    }

    NeededReferences()
    {
        var refs = "";

        for (const question of this.questions) {
            if (question.reference != "" && ! question.answeredCorrectly)
            {
                if (refs == "")
                    refs = refs.concat(question.reference);
                else
                    refs = refs.concat(", ", question.reference);
            }
        }

        return refs;
    }

    GetPercentCorrect()
    {
        var allQuestions = this.questions.length;
        var correctQuestions = 0;

        for (const question of this.questions) {
            if (question.answeredCorrectly)
                correctQuestions++;
        }

        return Math.round(correctQuestions / allQuestions * 100);
    }

    // Добавить вопрос
    AddQuestion(question)
    {
        this.questions.push(question);
    }
}

//Класс, представляющий вопрос
class Question
{
    constructor()
    {
        this.title = "";
        this.answers = [];
        this.explanation = "";
        this.reference = "";
        this.clicked = false;
        this.answeredCorrectly = false;
    }

    Click(index)
    {
        if (this.clicked == false)
        {
            this.clicked = true;
            var score = this.answers[index].value;

            if (score > 0) 
                this.answeredCorrectly = true;

            return score;
        }
        else
        {
            return 0;
        }
    }

    AddAnswer(answer)
    {
        this.answers.push(answer);
    }
    
    GetCorrectAnswer()
    {
        let i = 0;
        this.answers.forEach(ans => {
            if (ans.value > 0)
                return ans;

            i++
        })

        return i;
    }
}

//Класс, представляющий ответ
class Answer
{
    constructor(text, value)
    {
        this.text = text;
        this.value = value;
    }
}
