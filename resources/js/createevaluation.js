let questionIndices = [];
let optionIndices = {};

const questionsContainer = document.getElementById("questions-container");

document.getElementById("add-question-button").addEventListener("click", () => {
    let questionIndex = questionIndices.length;

    const questionCard = document.createElement("div");
    questionCard.className = "question-card";
    questionCard.id = `question-card-${questionIndex}`;

    const questionCardBody = document.createElement("div");
    questionCardBody.className = "card-body mt-2 mb-2";

    const questionLabel = document.createElement("label");
    questionLabel.className = "text-xl text-gray-600 font-bold";
    questionLabel.textContent = `Pregunta ${questionIndex + 1}`;

    const optionsLabel = document.createElement("label");
    optionsLabel.textContent = "Opción de respuesta";
    optionsLabel.className = "text-lg text-gray-600 font-bold";

    const questionInput = document.createElement("input");
    questionInput.type = "text";
    questionInput.className =
        "border-2 mt-2 mb-2 rounded-lg border-gray-300 p-2 w-full";
    questionInput.name = `questions[${questionIndex}][question]`;
    questionInput.required = true;

    const optionsContainer = document.createElement("div");
    optionsContainer.id = `options-container-${questionIndex}`;

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

    questionIndices.push(questionIndex);

    questionCardBody.appendChild(questionLabel);
    questionCardBody.appendChild(questionInput);
    questionCardBody.appendChild(optionsLabel);
    questionCardBody.appendChild(optionsContainer);
    questionCardBody.appendChild(addOptionButton);
    questionCardBody.appendChild(removeOptionButton);

    questionCard.appendChild(questionCardBody);

    questionsContainer.appendChild(questionCard);
});

document
    .getElementById("remove-question-button")
    .addEventListener("click", function () {
        if (questionIndices.length > 2) {
            var questionsContainer = document.getElementById(
                "questions-container"
            );
            var lastQuestionCard = document.getElementById(
                "question-card-" + questionIndices.pop()
            );

            questionsContainer.removeChild(lastQuestionCard);
        } else {
            alert("Debe haber al menos dos pregunta.");
        }
    });

function generateUUID() {
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
        /[xy]/g,
        function (c) {
            var r = (Math.random() * 16) | 0,
                v = c == "x" ? r : (r & 0x3) | 0x8;
            return v.toString(16);
        }
    );
}

//Validaciones antes de enviar el formulario
document
    .querySelector('button[role="submit"]')
    .addEventListener("click", function (event) {
        var questions = document.querySelectorAll(".question-card");

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
    });
