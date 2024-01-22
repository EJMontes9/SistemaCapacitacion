let questionIndices = [];
let optionIndices = {};

const questionsContainer = document.getElementById("questions-container");

// Agregar preguntas existentes
// Obtener el contenedor de preguntas existentes
const existingQuestionsContainer = document.getElementById(
    "existing-questions-container"
);

// Iterar sobre las preguntas existentes y agregarlas
document.querySelectorAll(".existing-question").forEach((existingQuestion) => {
    const id = existingQuestion.getAttribute("data-id");
    const question = existingQuestion.getAttribute("data-question");
    const score = existingQuestion.getAttribute("data-score");
    const options = JSON.parse(existingQuestion.getAttribute("data-options"));

    addQuestion({
        id: id,
        question: question,
        score: score,
        options: options,
    });
});

document.getElementById("add-question-button").addEventListener("click", () => {
    let questionIndex = questionIndices.length;

    addQuestion({
        id: null,
        question: "",
        score: 0,
        options: [],
    });
});

document
    .getElementById("remove-question-button")
    .addEventListener("click", function () {
        if (questionIndices.length > 2) {
            var lastQuestionCard = document.getElementById(
                "question-card-" + questionIndices.pop()
            );
            questionsContainer.removeChild(lastQuestionCard);
        } else {
            alert("Debe haber al menos dos pregunta.");
        }
    });

function addQuestion({ id, question, score, options }) {
    let questionIndex = questionIndices.length;

    //Contenedor de pregunta
    const questionCard = document.createElement("div");
    questionCard.className = "question-card";
    questionCard.id = `question-card-${questionIndex}`;

    //Contenedor de pregunta
    const questionCardBody = document.createElement("div");
    questionCardBody.className = "card-body mt-3 me-0";

    //Contenedor de pregunta y puntaje
    const questionContainer = document.createElement("div");
    questionContainer.className = "flex justify-between";

    //Label Pregunta
    const questionLabel = document.createElement("label");
    questionLabel.className = "text-xl text-gray-600 font-bold mr-10 mt-10";
    questionLabel.textContent = `Pregunta ${questionIndex + 1}`;

    //Label Puntaje
    const scoreLabel = document.createElement("label");
    scoreLabel.className = "text-xl text-gray-600 font-bold ml-10 mt-10";
    scoreLabel.textContent = "Puntaje";

    //Input de puntaje
    const scoreInput = document.createElement("input");
    scoreInput.type = "number";
    scoreInput.className = "border-2 ml-2 w-215 h-8 rounded-lg";
    scoreInput.name = `questions[${questionIndex}][score]`;
    scoreInput.required = true;
    scoreInput.min = 0;
    scoreInput.step = 1;
    scoreInput.max = 10;
    scoreInput.value = score;

    //Contenedor de pregunta y puntaje
    questionContainer.appendChild(questionLabel);
    questionContainer.appendChild(scoreInput);

    //Label Opcion Respuesta
    const optionsLabel = document.createElement("label");
    optionsLabel.textContent = "Opción de respuesta";
    optionsLabel.className = "text-lg text-gray-600 font-bold";

    //Input de pregunta
    const questionInput = document.createElement("input");
    questionInput.type = "text";
    questionInput.className =
        "border-2 mt-2 mb-2 rounded-lg border-gray-300 p-2 w-full";
    questionInput.name = `questions[${questionIndex}][question]`;
    questionInput.required = true;
    questionInput.value = question;

    //Contenedor de opciones
    const optionsContainer = document.createElement("div");
    optionsContainer.id = `options-container-${questionIndex}`;

    // Agregar opciones existentes
    options.forEach((option) => {
        const optionIndex = optionIndices[questionIndex] || 0;

        const optionInput = document.createElement("input");
        optionInput.type = "text";
        optionInput.className =
            "border-2 rounded-lg mb-2 border-gray-300 p-2 w-full mt-2 option-input";
        optionInput.name = `questions[${questionIndex}][options][${optionIndex}]`;
        optionInput.required = true;
        optionInput.value = option.options;

        const correctOptionCheckbox = document.createElement("input");
        correctOptionCheckbox.type = "checkbox";
        correctOptionCheckbox.id = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
        correctOptionCheckbox.className =
            "ml-2 correct-answer-checkbox form-checkbox text-blue-500 rounded";
        correctOptionCheckbox.name = `questions[${questionIndex}][correct_answer][${optionIndex}]`;
        correctOptionCheckbox.value = "true";
        correctOptionCheckbox.checked = option.correct_answer;

        const correctOptionLabel = document.createElement("label");
        correctOptionLabel.className = "ml-2";
        correctOptionLabel.htmlFor = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
        correctOptionLabel.textContent = "Marcar como Correcta";

        const optionDiv = document.createElement("div");
        optionDiv.appendChild(optionInput);
        optionDiv.appendChild(correctOptionCheckbox);
        optionDiv.appendChild(correctOptionLabel);

        optionsContainer.appendChild(optionDiv);

        optionIndices[questionIndex] = (optionIndices[questionIndex] || 0) + 1;
    });

    //Botones de agregar y eliminar opciones
    const addOptionButton = document.createElement("button");
    addOptionButton.type = "button";
    addOptionButton.textContent = "Agregar opción";
    addOptionButton.className =
        "text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 mt-2";

    addOptionButton.addEventListener("click", () => {
        const optionIndex = optionIndices[questionIndex] || 0;

        const optionInput = document.createElement("input");
        optionInput.type = "text";
        optionInput.className =
            "border-2 rounded-lg mb-2 border-gray-300 p-2 w-full mt-2 option-input";
        optionInput.name = `questions[${questionIndex}][options][${optionIndex}]`;
        optionInput.required = true;

        const correctOptionCheckbox = document.createElement("input");
        correctOptionCheckbox.type = "checkbox";
        correctOptionCheckbox.id = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
        correctOptionCheckbox.className =
            "ml-2 correct-answer-checkbox form-checkbox text-blue-500 rounded";
        correctOptionCheckbox.name = `questions[${questionIndex}][correct_answer][${optionIndex}]`;
        correctOptionCheckbox.value = "true";

        const correctOptionLabel = document.createElement("label");
        correctOptionLabel.className = "ml-2";
        correctOptionLabel.htmlFor = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
        correctOptionLabel.textContent = "Marcar como Correcta";

        const optionDiv = document.createElement("div");
        optionDiv.appendChild(optionInput);
        optionDiv.appendChild(correctOptionCheckbox);
        optionDiv.appendChild(correctOptionLabel);

        optionsContainer.appendChild(optionDiv);

        optionIndices[questionIndex] = (optionIndices[questionIndex] || 0) + 1;
    });

    const removeOptionButton = document.createElement("button");
    removeOptionButton.type = "button";
    removeOptionButton.textContent = "Eliminar opción";
    removeOptionButton.className =
        "text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-800 mt-2";

    removeOptionButton.addEventListener("click", () => {
        if (optionsContainer.children.length > 2) {
            optionsContainer.removeChild(optionsContainer.lastChild);
            optionIndices[questionIndex]--;
        } else {
            alert("Debe haber al menos dos opciones.");
        }
    });

    questionCardBody.appendChild(questionLabel);
    questionCardBody.appendChild(scoreLabel);
    questionCardBody.appendChild(scoreInput);
    questionCardBody.appendChild(questionInput);
    questionCardBody.appendChild(optionsLabel);
    questionCardBody.appendChild(optionsContainer);
    questionCardBody.appendChild(addOptionButton);
    questionCardBody.appendChild(removeOptionButton);

    questionCard.appendChild(questionCardBody);

    questionsContainer.appendChild(questionCard);

    questionIndices.push(questionIndex);
}

//Validaciones antes de enviar el formulario
document
    .querySelector('button[role="submit"]')
    .addEventListener("click", function (event) {
        var questions = document.querySelectorAll(".question-card");
        var totalScore = 0;

        if (questions.length < 1) {
            alert("Por favor, crea al menos 1 pregunta antes de guardar.");
            event.preventDefault();
            return;
        }

        for (var i = 0; i < questions.length; i++) {
            var options = questions[i].querySelectorAll(".option-input");
            var correctAnswers = questions[i].querySelectorAll(
                ".correct-answer-checkbox:checked"
            );
            var scoreInput = questions[i].querySelector(
                'input[name^="questions[' + i + '][score]"]'
            );

            if (!scoreInput || scoreInput.value === "") {
                alert(
                    "Por favor, llena el campo de Puntaje para todas las preguntas antes de guardar."
                );
                event.preventDefault();
                return;
            }

            totalScore += Number(scoreInput.value);

            if (options.length < 2) {
                alert(
                    "Por favor, crea al menos 2 opciones para cada pregunta antes de guardar."
                );
                event.preventDefault();
                return;
            }

            if (correctAnswers.length < 1) {
                alert(
                    "Por favor, selecciona al menos una respuesta correcta para cada pregunta antes de guardar."
                );
                event.preventDefault();
                return;
            }
        }

        if (totalScore < 1 || totalScore > 10) {
            alert(
                "La suma de todos los Puntajes debe ser como máximo 10 y como mínimo 1."
            );
            event.preventDefault();
            return;
        }
    });
