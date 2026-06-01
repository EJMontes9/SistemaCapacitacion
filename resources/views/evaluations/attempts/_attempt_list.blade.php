<div class="mt-6">
    <h3 class="font-bold text-lg mb-4">Mis Intentos</h3>
    @php
        $attempts = $evaluation->userAttempts(auth()->id())->get();
    @endphp

    @if ($attempts->isEmpty())
        <div class="bg-orange-100 rounded-lg p-4 text-center text-gray-600">
            Aún no has realizado ningún intento en esta evaluación.
        </div>
    @else
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="table-auto w-full text-sm text-left text-gray-500">
                <thead class="font-bold text-base text-center text-black bg-gray-200">
                    <tr>
                        <th class="px-4 py-3 border">Intento #</th>
                        <th class="px-4 py-3 border">Iniciado</th>
                        <th class="px-4 py-3 border">Finalizado</th>
                        <th class="px-4 py-3 border">Puntaje</th>
                        <th class="px-4 py-3 border">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attempts as $attempt)
                        <tr class="{{ $loop->index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }} text-center">
                            <td class="border px-4 py-2">Intento {{ $attempt->attempt_number }}</td>
                            <td class="border px-4 py-2">{{ $attempt->started_at->format('d/m/Y H:i') }}</td>
                            <td class="border px-4 py-2">
                                {{ $attempt->finished_at ? $attempt->finished_at->format('d/m/Y H:i') : 'En curso' }}
                            </td>
                            <td class="border px-4 py-2">
                                {{ $attempt->score !== null ? $attempt->score . ' / ' . $evaluation->questions->sum('score') : '-' }}
                            </td>
                            <td class="border px-4 py-2">
                                @if ($attempt->passed === null)
                                    <span class="text-yellow-500 font-semibold">Pendiente</span>
                                @elseif ($attempt->passed)
                                    <span class="text-green-600 font-semibold">Aprobado</span>
                                @else
                                    <span class="text-red-600 font-semibold">Reprobado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
