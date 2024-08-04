<h3 class="text-xl leading-none font-bold text-gray-900 mb-10">Progreso de cursos</h3>
<div class="block w-full overflow-x-auto">
    <table class="items-center w-full bg-transparent border-collapse">
        <thead>
        <tr>
            <th class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">Cursos</th>
            <th class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap min-w-140-px">Progreso</th>
        </tr>
        </thead>
        <tbody id="courses-progress" class="divide-y divide-gray-100">
        <!-- Dynamic content will be inserted here -->
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = '{{ Auth::id() }}';
        if (!userId) {
            console.error('User is not authenticated.');
            return;
        }

        fetch(`/api/user-courses-progress/${userId}`)
            .then(response => {
                console.log('la respuesta es: ',response);
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(data => {
                const tbody = document.getElementById('courses-progress');
                tbody.innerHTML = ''; // Clear existing content

                data.forEach(course => {
                    const progress = course.progress;
                    const row = document.createElement('tr');
                    row.classList.add('text-gray-500');

                    row.innerHTML = `
                        <th class="border-t-0 px-4 align-middle text-sm font-normal whitespace-nowrap p-4 text-left">${course.title}</th>
                        <td class="border-t-0 px-4 align-middle text-xs whitespace-nowrap p-4">
                            <div class="flex items-center">
                                <span class="mr-2 text-xs font-medium">${progress}%</span>
                                <div class="relative w-full">
                                    <div class="w-full bg-gray-200 rounded-sm h-2">
                                        <div class="bg-cyan-600 h-2 rounded-sm" style="width: ${progress}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    `;

                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching courses progress:', error));
    });
</script>