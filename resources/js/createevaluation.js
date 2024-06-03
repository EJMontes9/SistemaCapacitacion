// let questionIndices = [];
// let optionIndices = {};

// const questionsContainer = document.getElementById("questions-container");

// // Agregar preguntas existentes
// // Obtener el contenedor de preguntas existentes
// const existingQuestionsContainer = document.getElementById(
//     "existing-questions-container"
// );

// // Iterar sobre las preguntas existentes y agregarlas
// document.querySelectorAll(".existing-question").forEach((existingQuestion) => {
//     const id = existingQuestion.getAttribute("data-id");
//     const question = existingQuestion.getAttribute("data-question");
//     const score = existingQuestion.getAttribute("data-score");
//     const options = JSON.parse(existingQuestion.getAttribute("data-options"));

//     addQuestion({
//         id: id,
//         question: question,
//         score: score,
//         options: options,
//     });
// });

// document.getElementById("add-question-button").addEventListener("click", () => {
//     let questionIndex = questionIndices.length;

//     addQuestion({
//         id: null,
//         question: "",
//         score: 0,
//         options: [],
//     });
// });

// document
//     .getElementById("remove-question-button")
//     .addEventListener("click", function () {
//         if (questionIndices.length > 2) {
//             var lastQuestionCard = document.getElementById(
//                 "question-card-" + questionIndices.pop()
//             );
//             questionsContainer.removeChild(lastQuestionCard);
//         } else {
//             alert("Debe haber al menos dos pregunta.");
//         }
//     });

// function addQuestion({ id, question, score, options }) {
//     let questionIndex = questionIndices.length;

//     //Contenedor de pregunta
//     const questionCard = document.createElement("div");
//     questionCard.className = "question-card";
//     questionCard.id = `question-card-${questionIndex}`;

//     //Contenedor de pregunta
//     const questionCardBody = document.createElement("div");
//     questionCardBody.className = "card-body mt-3 me-0";

//     //Contenedor de pregunta y puntaje
//     const questionContainer = document.createElement("div");
//     questionContainer.className = "flex justify-between";

//     //Label Pregunta
//     const questionLabel = document.createElement("label");
//     questionLabel.className = "text-xl text-gray-600 font-bold mr-10 mt-10";
//     questionLabel.textContent = `Pregunta ${questionIndex + 1}`;

//     //Label Puntaje
//     const scoreLabel = document.createElement("label");
//     scoreLabel.className = "text-xl text-gray-600 font-bold ml-10 mt-10";
//     scoreLabel.textContent = "Puntaje";

//     //Input de puntaje
//     const scoreInput = document.createElement("input");
//     scoreInput.type = "number";
//     scoreInput.className = "border-2 ml-2 w-215 h-8 rounded-lg";
//     scoreInput.name = `questions[${questionIndex}][score]`;
//     scoreInput.required = true;
//     scoreInput.min = 1;
//     scoreInput.step = 1;
//     scoreInput.max = 10;
//     scoreInput.value = score;

//     //Contenedor de pregunta y puntaje
//     questionContainer.appendChild(questionLabel);
//     questionContainer.appendChild(scoreInput);

//     //Label Opcion Respuesta
//     const optionsLabel = document.createElement("label");
//     optionsLabel.textContent = "Opción de respuesta";
//     optionsLabel.className = "text-lg text-gray-600 font-bold";

//     //Input de pregunta
//     const questionInput = document.createElement("input");
//     questionInput.type = "text";
//     questionInput.className =
//         "border-2 mt-2 mb-2 rounded-lg border-gray-300 p-2 w-full";
//     questionInput.name = `questions[${questionIndex}][question]`;
//     questionInput.required = true;
//     questionInput.value = question;

//     //Contenedor de opciones
//     const optionsContainer = document.createElement("div");
//     optionsContainer.id = `options-container-${questionIndex}`;

//     // Agregar opciones existentes
//     options.forEach((option) => {
//         const optionIndex = optionIndices[questionIndex] || 0;

//         const optionInput = document.createElement("input");
//         optionInput.type = "text";
//         optionInput.className =
//             "border-2 rounded-lg mb-2 border-gray-300 p-2 w-full mt-2 option-input";
//         optionInput.name = `questions[${questionIndex}][options][${optionIndex}]`;
//         optionInput.required = true;
//         optionInput.value = option.options;

//         const correctOptionCheckbox = document.createElement("input");
//         correctOptionCheckbox.type = "checkbox";
//         correctOptionCheckbox.id = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
//         correctOptionCheckbox.className =
//             "ml-2 correct-answer-checkbox form-checkbox text-blue-500 rounded";
//         correctOptionCheckbox.name = `questions[${questionIndex}][correct_answer][${optionIndex}]`;
//         correctOptionCheckbox.value = "true";
//         correctOptionCheckbox.checked = option.correct_answer;

//         const correctOptionLabel = document.createElement("label");
//         correctOptionLabel.className = "ml-2";
//         correctOptionLabel.htmlFor = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
//         correctOptionLabel.textContent = "Marcar como Correcta";

//         const optionDiv = document.createElement("div");
//         optionDiv.appendChild(optionInput);
//         optionDiv.appendChild(correctOptionCheckbox);
//         optionDiv.appendChild(correctOptionLabel);

//         optionsContainer.appendChild(optionDiv);

//         optionIndices[questionIndex] = (optionIndices[questionIndex] || 0) + 1;
//     });

//     //Botones de agregar y eliminar opciones
//     const addOptionButton = document.createElement("button");
//     addOptionButton.type = "button";
//     addOptionButton.textContent = "Agregar opción";
//     addOptionButton.className =
//         "text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800 mt-2";

//     addOptionButton.addEventListener("click", () => {
//         const optionIndex = optionIndices[questionIndex] || 0;

//         const optionInput = document.createElement("input");
//         optionInput.type = "text";
//         optionInput.className =
//             "border-2 rounded-lg mb-2 border-gray-300 p-2 w-full mt-2 option-input";
//         optionInput.name = `questions[${questionIndex}][options][${optionIndex}]`;
//         optionInput.required = true;

//         const correctOptionCheckbox = document.createElement("input");
//         correctOptionCheckbox.type = "checkbox";
//         correctOptionCheckbox.id = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
//         correctOptionCheckbox.className =
//             "ml-2 correct-answer-checkbox form-checkbox text-blue-500 rounded";
//         correctOptionCheckbox.name = `questions[${questionIndex}][correct_answer][${optionIndex}]`;
//         correctOptionCheckbox.value = "true";

//         const correctOptionLabel = document.createElement("label");
//         correctOptionLabel.className = "ml-2";
//         correctOptionLabel.htmlFor = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
//         correctOptionLabel.textContent = "Marcar como Correcta";

//         const optionDiv = document.createElement("div");
//         optionDiv.appendChild(optionInput);
//         optionDiv.appendChild(correctOptionCheckbox);
//         optionDiv.appendChild(correctOptionLabel);

//         optionsContainer.appendChild(optionDiv);

//         optionIndices[questionIndex] = (optionIndices[questionIndex] || 0) + 1;
//     });

//     const removeOptionButton = document.createElement("button");
//     removeOptionButton.type = "button";
//     removeOptionButton.className = "text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-800 mt-2";

//     // Crear el icono de la papelera
//     const trashIcon = document.createElement("i");
//     trashIcon.className = "fas fa-trash-alt";

//     // Texto para el botón
//     const buttonText = document.createTextNode("Eliminar Opción");

//     // Agregar el icono y el texto al botón
//     removeOptionButton.appendChild(trashIcon);
//     removeOptionButton.appendChild(document.createTextNode(" "));
//     removeOptionButton.appendChild(buttonText);

//     removeOptionButton.addEventListener("click", () => {
//         if (optionsContainer.children.length > 2) {
//             optionsContainer.removeChild(optionsContainer.lastChild);
//             optionIndices[questionIndex]--;
//         } else {
//             alert("Debe haber al menos dos opciones.");
//         }
//     });

//     questionCardBody.appendChild(questionLabel);
//     questionCardBody.appendChild(scoreLabel);
//     questionCardBody.appendChild(scoreInput);
//     questionCardBody.appendChild(questionInput);
//     questionCardBody.appendChild(optionsLabel);
//     questionCardBody.appendChild(optionsContainer);
//     questionCardBody.appendChild(addOptionButton);
//     questionCardBody.appendChild(removeOptionButton);

//     questionCard.appendChild(questionCardBody);

//     questionsContainer.appendChild(questionCard);

//     questionIndices.push(questionIndex);
// }

// //Validaciones antes de enviar el formulario
// document
//     .querySelector('button[role="submit"]')
//     .addEventListener("click", function (event) {
//         var questions = document.querySelectorAll(".question-card");
//         var totalScore = 0;

//         if (questions.length < 1) {
//             alert("Por favor, crea al menos 1 pregunta antes de guardar.");
//             event.preventDefault();
//             return;
//         }

//         for (var i = 0; i < questions.length; i++) {
//             var options = questions[i].querySelectorAll(".option-input");
//             var correctAnswers = questions[i].querySelectorAll(
//                 ".correct-answer-checkbox:checked"
//             );
//             var scoreInput = questions[i].querySelector(
//                 'input[name^="questions[' + i + '][score]"]'
//             );
//             var scoreValue = parseInt(scoreInput.value);
//             var hasCorrectAnswer = false;

//             if (scoreValue < 1) {
//                 alert(
//                     "Asegúrese de que el campo de Puntaje para todas las preguntas sea al menos 1."
//                 );
//                 event.preventDefault();
//                 return;
//             }

//             totalScore += scoreValue;

//             if (options.length < 2) {
//                 alert(
//                     "Por favor, crea al menos 2 opciones para cada pregunta antes de guardar."
//                 );
//                 event.preventDefault();
//                 return;
//             }

//             if (correctAnswers.length === options.length) {
//                 alert(
//                     "Debe existir al menos una respuesta falsa por cada pregunta."
//                 );
//                 event.preventDefault();
//                 return;
//             }

//             // Verificar si al menos una opción tiene correct_answer = true
//             for (var j = 0; j < options.length; j++) {
//                 var correctAnswerCheckbox = options[j].parentNode.querySelector(
//                     ".correct-answer-checkbox"
//                 );
//                 if (correctAnswerCheckbox.checked) {
//                     hasCorrectAnswer = true;
//                     break;
//                 }
//             }

//             if (!hasCorrectAnswer) {
//                 alert(
//                     "Debe marcar al menos una opción como correcta para cada pregunta."
//                 );
//                 event.preventDefault();
//                 return;
//             }
//         }

//         if (totalScore < 1 || totalScore > 10) {
//             alert(
//                 "La suma de todos los Puntajes debe ser como máximo 10 y como mínimo 1."
//             );
//             event.preventDefault();
//             return;
//         }
//     });






//Codigo correcto


// let questionIndices = [];
// let optionIndices = {};

// const questionsContainer = document.getElementById("questions-container");

// // Agregar preguntas existentes
// const existingQuestionsContainer = document.getElementById("existing-questions-container");
// document.querySelectorAll(".existing-question").forEach((existingQuestion) => {
//     const id = existingQuestion.getAttribute("data-id");
//     const question = existingQuestion.getAttribute("data-question");
//     const score = existingQuestion.getAttribute("data-score");
//     const options = JSON.parse(existingQuestion.getAttribute("data-options"));

//     addQuestion({
//         id: id,
//         question: question,
//         score: score,
//         options: options,
//     });
// });

// document.getElementById("add-question-button").addEventListener("click", () => {
//     addQuestion({
//         id: null,
//         question: "",
//         score: 0,
//         options: [],
//     });
// });

// document.getElementById("remove-question-button").addEventListener("click", function () {
//     if (questionIndices.length > 2) {
//         const lastIndex = questionIndices.pop();
//         const lastQuestionCard = document.getElementById(`question-card-${lastIndex}`);
//         questionsContainer.removeChild(lastQuestionCard);
//         delete optionIndices[lastIndex];
//     } else {
//         alert("Debe haber al menos dos preguntas.");
//     }
// });

// function addQuestion({ id, question, score, options }) {
//     const questionIndex = questionIndices.length;
//     questionIndices.push(questionIndex);

//     const questionCard = document.createElement("div");
//     questionCard.className = "question-card";
//     questionCard.id = `question-card-${questionIndex}`;

//     const questionCardBody = document.createElement("div");
//     questionCardBody.className = "card-body mt-3 me-0";

//     const questionContainer = document.createElement("div");
//     questionContainer.className = "flex justify-between";

//     const questionLabel = document.createElement("label");
//     questionLabel.className = "text-xl text-gray-600 font-bold mr-10 mt-10";
//     questionLabel.textContent = `Pregunta ${questionIndex + 1}`;

//     const scoreLabel = document.createElement("label");
//     scoreLabel.className = "text-xl text-gray-600 font-bold ml-10 mt-10";
//     scoreLabel.textContent = "Puntaje";

//     const scoreInput = document.createElement("input");
//     scoreInput.type = "number";
//     scoreInput.className = "border-2 ml-2 w-215 h-8 rounded-lg";
//     scoreInput.name = `questions[${questionIndex}][score]`;
//     scoreInput.required = true;
//     scoreInput.min = 1;
//     scoreInput.step = 1;
//     scoreInput.max = 10;
//     scoreInput.value = score;

//     questionContainer.appendChild(questionLabel);
//     questionContainer.appendChild(scoreInput);

//     const optionsLabel = document.createElement("label");
//     optionsLabel.textContent = "Opción de respuesta";
//     optionsLabel.className = "text-lg text-gray-600 font-bold";

//     const questionInput = document.createElement("input");
//     questionInput.type = "text";
//     questionInput.className = "border-2 mt-2 mb-2 rounded-lg border-gray-300 p-2 w-full";
//     questionInput.name = `questions[${questionIndex}][question]`;
//     questionInput.required = true;
//     questionInput.value = question;

//     const optionsContainer = document.createElement("div");
//     optionsContainer.id = `options-container-${questionIndex}`;

//     options.forEach((option, optionIndex) => {
//         addOption(optionsContainer, questionIndex, optionIndex, option);
//     });

//     const addOptionButton = document.createElement("button");
//     addOptionButton.type = "button";
//     addOptionButton.textContent = "Agregar opción";
//     addOptionButton.className = "text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800 mt-2";
//     addOptionButton.addEventListener("click", () => {
//         const optionIndex = optionIndices[questionIndex] || 0;
//         addOption(optionsContainer, questionIndex, optionIndex);
//         optionIndices[questionIndex] = optionIndex + 1;
//     });

//     const removeQuestionButton = document.createElement("button");
//     removeQuestionButton.type = "button";
//     removeQuestionButton.className = "text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-800 mt-2";
//     removeQuestionButton.innerHTML = '<i class="fas fa-trash-alt"></i> Eliminar Pregunta';
//     removeQuestionButton.addEventListener("click", () => {
//         questionsContainer.removeChild(questionCard);
//         questionIndices = questionIndices.filter(index => index !== questionIndex);
//         delete optionIndices[questionIndex];
//     });

//     questionCardBody.appendChild(questionContainer);
//     questionCardBody.appendChild(questionInput);
//     questionCardBody.appendChild(optionsLabel);
//     questionCardBody.appendChild(optionsContainer);
//     questionCardBody.appendChild(addOptionButton);
//     questionCardBody.appendChild(removeQuestionButton);

//     questionCard.appendChild(questionCardBody);
//     questionsContainer.appendChild(questionCard);
// }

// function addOption(optionsContainer, questionIndex, optionIndex, option = { options: '', correct_answer: false }) {
//     const optionInput = document.createElement("input");
//     optionInput.type = "text";
//     optionInput.className = "border-2 rounded-lg mb-2 border-gray-300 p-2 w-full mt-2 option-input";
//     optionInput.name = `questions[${questionIndex}][options][${optionIndex}]`;
//     optionInput.required = true;
//     optionInput.value = option.options;

//     const correctOptionCheckbox = document.createElement("input");
//     correctOptionCheckbox.type = "checkbox";
//     correctOptionCheckbox.id = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
//     correctOptionCheckbox.className = "ml-2 correct-answer-checkbox form-checkbox text-blue-500 rounded";
//     correctOptionCheckbox.name = `questions[${questionIndex}][correct_answer][${optionIndex}]`;
//     correctOptionCheckbox.value = "true";
//     correctOptionCheckbox.checked = option.correct_answer;

//     const correctOptionLabel = document.createElement("label");
//     correctOptionLabel.className = "ml-2";
//     correctOptionLabel.htmlFor = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
//     correctOptionLabel.textContent = "Marcar como Correcta";

//     const removeOptionButton = document.createElement("button");
//     removeOptionButton.type = "button";
//     removeOptionButton.className = "text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-800 mt-2";
//     removeOptionButton.innerHTML = '<i class="fas fa-trash-alt"></i> Eliminar Opción';
//     removeOptionButton.addEventListener("click", () => {
//         optionsContainer.removeChild(optionDiv);
//         optionIndices[questionIndex]--;
//     });

//     const optionDiv = document.createElement("div");
//     optionDiv.className = "flex items-center mb-2";  // Agregar flex items-center para alinear los elementos
//     optionDiv.appendChild(optionInput);
//     optionDiv.appendChild(correctOptionCheckbox);
//     optionDiv.appendChild(correctOptionLabel);
//     optionDiv.appendChild(removeOptionButton);

//     optionsContainer.appendChild(optionDiv);
// }

// // Validaciones antes de enviar el formulario
// document.querySelector('button[role="submit"]').addEventListener("click", function (event) {
//     const questions = document.querySelectorAll(".question-card");
//     let totalScore = 0;

//     if (questions.length < 2) {
//         alert("Por favor, crea al menos 2 preguntas antes de guardar.");
//         event.preventDefault();
//         return;
//     }

//     for (let i = 0; i < questions.length; i++) {
//         const options = questions[i].querySelectorAll(".option-input");
//         const correctAnswers = questions[i].querySelectorAll(".correct-answer-checkbox:checked");
//         const scoreInput = questions[i].querySelector(`input[name^="questions[${i}][score]"]`);
//         const scoreValue = parseInt(scoreInput.value);
//         let hasCorrectAnswer = false;

//         if (scoreValue < 1) {
//             alert("Asegúrese de que el campo de Puntaje para todas las preguntas sea al menos 1.");
//             event.preventDefault();
//             return;
//         }

//         totalScore += scoreValue;

//         if (options.length < 2) {
//             alert("Por favor, crea al menos 2 opciones para cada pregunta antes de guardar.");
//             event.preventDefault();
//             return;
//         }

//         if (correctAnswers.length === 0) {
//             alert("Debe marcar al menos una opción como correcta para cada pregunta.");
//             event.preventDefault();
//             return;
//         }

//         for (let j = 0; j < options.length; j++) {
//             const correctAnswerCheckbox = options[j].parentNode.querySelector(".correct-answer-checkbox");
//             if (correctAnswerCheckbox.checked) {
//                 hasCorrectAnswer = true;
//                 break;
//             }
//         }

//         if (!hasCorrectAnswer) {
//             alert("Debe marcar al menos una opción como correcta para cada pregunta.");
//             event.preventDefault();
//             return;
//         }
//     }

//     if (totalScore < 1 || totalScore > 10) {
//         alert("La suma de todos los Puntajes debe ser como máximo 10 y como mínimo 1.");
//         event.preventDefault();
//         return;
//     }
// });


//Codigo mejorado

let questionIndices = [];
let optionIndices = {};

const questionsContainer = document.getElementById("questions-container");

// Agregar preguntas existentes
const existingQuestionsContainer = document.getElementById("existing-questions-container");
document.querySelectorAll(".existing-question").forEach((existingQuestion, index) => {
    const id = existingQuestion.getAttribute("data-id");
    const question = existingQuestion.getAttribute("data-question");
    const score = existingQuestion.getAttribute("data-score");
    const options = JSON.parse(existingQuestion.getAttribute("data-options"));

    addQuestion({
        id: id,
        question: question,
        score: score,
        options: options,
    }, index);
});

document.getElementById("add-question-button").addEventListener("click", () => {
    addQuestion({
        id: null,
        question: "",
        score: 0,
        options: [],
    });
});

function removeLastQuestion() {
    if (questionIndices.length > 2) {
        const lastIndex = questionIndices.pop();
        const lastQuestionCard = document.getElementById(`question-card-${lastIndex}`);
        questionsContainer.removeChild(lastQuestionCard);
        delete optionIndices[lastIndex];
        resetQuestionIndices();
    } else {
        alert("Debe haber al menos dos preguntas.");
    }
}

document.getElementById("remove-question-button").addEventListener("click", removeLastQuestion);

function addQuestion({ id, question, score, options }, index = null) {
    const questionIndex = index !== null ? index : questionIndices.length;
    questionIndices.push(questionIndex);

    const questionCard = document.createElement("div");
    questionCard.className = "question-card";
    questionCard.id = `question-card-${questionIndex}`;

    const questionCardBody = document.createElement("div");
    questionCardBody.className = "card-body mt-3 me-0";

    const questionContainer = document.createElement("div");
    questionContainer.className = "flex justify-between";

    const questionLabel = document.createElement("label");
    questionLabel.className = "text-xl text-gray-600 font-bold mr-10 mt-10";
    questionLabel.textContent = `Pregunta ${questionIndex + 1}`;

    const scoreLabel = document.createElement("label");
    scoreLabel.className = "text-xl text-gray-600 font-bold ml-10 mt-10";
    scoreLabel.textContent = "Puntaje";

    const scoreInput = document.createElement("input");
    scoreInput.type = "number";
    scoreInput.className = "border-2 ml-2 w-215 h-8 rounded-lg";
    scoreInput.name = `questions[${questionIndex}][score]`;
    scoreInput.required = true;
    scoreInput.min = 1;
    scoreInput.step = 1;
    scoreInput.max = 10;
    scoreInput.value = score;

    questionContainer.appendChild(questionLabel);
    questionContainer.appendChild(scoreInput);

    const optionsLabel = document.createElement("label");
    optionsLabel.textContent = "Opción de respuesta";
    optionsLabel.className = "text-lg text-gray-600 font-bold";

    const questionInput = document.createElement("input");
    questionInput.type = "text";
    questionInput.className = "border-2 mt-2 mb-2 rounded-lg border-gray-300 p-2 w-full";
    questionInput.name = `questions[${questionIndex}][question]`;
    questionInput.required = true;
    questionInput.value = question;

    const optionsContainer = document.createElement("div");
    optionsContainer.id = `options-container-${questionIndex}`;

    options.forEach((option, optionIndex) => {
        addOption(optionsContainer, questionIndex, optionIndex, option);
    });

    const addOptionButton = document.createElement("button");
    addOptionButton.type = "button";
    addOptionButton.textContent = "Agregar opción";
    addOptionButton.className = "text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800 mt-2";
    addOptionButton.addEventListener("click", () => {
        const optionIndex = optionIndices[questionIndex] || 0;
        addOption(optionsContainer, questionIndex, optionIndex);
        optionIndices[questionIndex] = optionIndex + 1;
    });

    const removeQuestionButton = document.createElement("button");
    removeQuestionButton.type = "button";
    removeQuestionButton.className = "text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-800 mt-2";
    removeQuestionButton.innerHTML = '<i class="fas fa-trash-alt"></i> Eliminar Pregunta';
    removeQuestionButton.addEventListener("click", () => {
        questionsContainer.removeChild(questionCard);
        questionIndices = questionIndices.filter(index => index !== questionIndex);
        delete optionIndices[questionIndex];
        resetQuestionIndices();
    });

    questionCardBody.appendChild(questionContainer);
    questionCardBody.appendChild(questionInput);
    questionCardBody.appendChild(optionsLabel);
    questionCardBody.appendChild(optionsContainer);
    questionCardBody.appendChild(addOptionButton);
    questionCardBody.appendChild(removeQuestionButton);

    questionCard.appendChild(questionCardBody);
    questionsContainer.appendChild(questionCard);
}

function addOption(optionsContainer, questionIndex, optionIndex, option = { options: '', correct_answer: false }) {
    const optionInput = document.createElement("input");
    optionInput.type = "text";
    optionInput.className = "border-2 rounded-lg mb-2 border-gray-300 p-2 w-full mt-2 option-input";
    optionInput.name = `questions[${questionIndex}][options][${optionIndex}]`;
    optionInput.required = true;
    optionInput.value = option.options;

    const correctOptionCheckbox = document.createElement("input");
    correctOptionCheckbox.type = "checkbox";
    correctOptionCheckbox.id = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
    correctOptionCheckbox.className = "ml-2 correct-answer-checkbox form-checkbox text-blue-500 rounded";
    correctOptionCheckbox.name = `questions[${questionIndex}][correct_answer][${optionIndex}]`;
    correctOptionCheckbox.value = "true";
    correctOptionCheckbox.checked = option.correct_answer;

    const correctOptionLabel = document.createElement("label");
    correctOptionLabel.className = "ml-2";
    correctOptionLabel.htmlFor = `correct-answer-checkbox-${questionIndex}-${optionIndex}`;
    correctOptionLabel.textContent = "Correcta";

    const removeOptionButton = document.createElement("button");
    removeOptionButton.type = "button";
    removeOptionButton.className = "ml-5 text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-800 mt-2";
    removeOptionButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
    removeOptionButton.addEventListener("click", () => {
        optionsContainer.removeChild(optionDiv);
        optionIndices[questionIndex]--;
    });

    const optionDiv = document.createElement("div");
    optionDiv.className = "flex items-center mb-2";  // Agregar flex items-center para alinear los elementos
    optionDiv.appendChild(optionInput);
    optionDiv.appendChild(correctOptionCheckbox);
    optionDiv.appendChild(correctOptionLabel);
    optionDiv.appendChild(removeOptionButton);

    optionsContainer.appendChild(optionDiv);
}

function resetQuestionIndices() {
    questionIndices = [];
    optionIndices = {};
    const questionCards = document.querySelectorAll('.question-card');
    questionCards.forEach((card, index) => {
        card.id = `question-card-${index}`;
        const questionLabel = card.querySelector('label.text-xl');
        questionLabel.textContent = `Pregunta ${index + 1}`;

        const scoreInput = card.querySelector(`input[name^="questions["][name$="[score]"]`);
        const questionInput = card.querySelector(`input[name^="questions["][name$="[question]"]`);
        scoreInput.name = `questions[${index}][score]`;
        questionInput.name = `questions[${index}][question]`;

        const optionsContainer = card.querySelector(`[id^="options-container-"]`);
        optionsContainer.id = `options-container-${index}`;
        const optionInputs = optionsContainer.querySelectorAll('.option-input');
        const correctAnswerCheckboxes = optionsContainer.querySelectorAll('.correct-answer-checkbox');
        optionInputs.forEach((optionInput, optionIndex) => {
            optionInput.name = `questions[${index}][options][${optionIndex}]`;
            const checkbox = correctAnswerCheckboxes[optionIndex];
            checkbox.name = `questions[${index}][correct_answer][${optionIndex}]`;
            checkbox.id = `correct-answer-checkbox-${index}-${optionIndex}`;
            const correctOptionLabel = optionsContainer.querySelector(`label[for="correct-answer-checkbox-${index}-${optionIndex}"]`);
            correctOptionLabel.htmlFor = `correct-answer-checkbox-${index}-${optionIndex}`;
        });

        questionIndices.push(index);
        optionIndices[index] = optionInputs.length;
    });
}

// Validaciones antes de enviar el formulario
document.querySelector('button[role="submit"]').addEventListener("click", function (event) {
    const questions = document.querySelectorAll(".question-card");
    let totalScore = 0;

    if (questions.length < 2) {
        alert("Por favor, crea al menos 2 preguntas antes de guardar.");
        event.preventDefault();
        return;
    }

    for (let i = 0; i < questions.length; i++) {
        const options = questions[i].querySelectorAll(".option-input");
        const correctAnswers = questions[i].querySelectorAll(".correct-answer-checkbox:checked");
        const scoreInput = questions[i].querySelector(`input[name^="questions[${i}][score]"]`);
        const scoreValue = parseInt(scoreInput.value);

        if (scoreValue < 1) {
            alert("Asegúrese de que el campo de Puntaje para todas las preguntas sea al menos 1.");
            event.preventDefault();
            return;
        }

        totalScore += scoreValue;

        if (options.length < 2) {
            alert("Por favor, crea al menos 2 opciones para cada pregunta antes de guardar.");
            event.preventDefault();
            return;
        }

        if (correctAnswers.length === 0) {
            alert("Debe marcar al menos una opción como correcta para cada pregunta.");
            event.preventDefault();
            return;
        }

        const nonCheckedOptions = options.length - correctAnswers.length;
        if (nonCheckedOptions < 1) {
            alert("Debe haber al menos una opción no marcada como correcta por pregunta.");
            event.preventDefault();
            return;
        }
    }

    if (totalScore < 1 || totalScore > 10) {
        alert("La suma de todos los Puntajes debe ser como máximo 10 y como mínimo 1.");
        event.preventDefault();
        return;
    }
});
