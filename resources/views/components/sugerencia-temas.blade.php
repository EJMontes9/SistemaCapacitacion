<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold leading-none text-gray-900">Creemos que deberias reforzar estas secciones</h3>
        <a href="#" class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2">
            Ver todos
        </a>
    </div>
    <div class="flow-root">
        <ul role="list" class="divide-y divide-gray-200" id="reforzarseccion">

        </ul>
    </div>
</div>


<script>
$(document).ready(function() {
    $.ajax({
        url: '/low-score-evaluations',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data); // Log the data to see what is returned

            // Get the list element
            let list = $('#reforzarseccion');

            // Clear the list
            list.empty();

            // Add each evaluation to the list
            $.each(data, function(index, evaluation) {
                let listItem = $('<li>').addClass('py-3 sm:py-4');

                let div = $('<div>').addClass('flex items-center space-x-4');

                let div2 = $('<div>').addClass('flex-1 min-w-0');

                let p1 = $('<p>').addClass('text-sm font-medium text-gray-900 truncate').text(evaluation.course_name);

                let p2 = $('<p>').addClass('text-sm text-gray-500 truncate').text('Module: ' + evaluation.section_name);

                let a = $('<a>').attr('href', '/evaluations/'+ evaluation.evaluation_id).text(' - Realizar Evaluación');

                p2.append(a);
                div2.append(p1);
                div2.append(p2);

                let div3 = $('<div>').addClass('inline-flex items-center text-base font-semibold text-gray-900').text('Total Score: ' + evaluation.total_score);

                div.append(div2);
                div.append(div3);

                listItem.append(div);

                list.append(listItem);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('There has been a problem with your fetch operation:', errorThrown);
        }
    });
});
</script>
