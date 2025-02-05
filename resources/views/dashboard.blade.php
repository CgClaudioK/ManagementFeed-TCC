<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Container -->
                    <div class="flex flex-wrap gap-2 justify-start">
                    @foreach ($formulacoes->groupBy('id') as $formulacaoId => $formulacaoGroup)
                        @php
                            $formulacao = $formulacaoGroup->first(); // Primeiro registro como referência para a formulação
                            $uniqueInsumos = $formulacaoGroup->unique('id_produto'); // Filtrar insumos únicos por id_produto
                        @endphp
                            <!-- Card -->
                            <div class="relative flex flex-col my-6 bg-white shadow-lg border border-gray-200 rounded-lg w-96">
                                <!-- Título -->
                                <div class="p-4 bg-gradient-to-r from-green-700 to-green-500 text-white rounded-t-lg">
                                    <h5 class="text-center text-xl font-semibold">
                                        {{ $formulacao->tipo_animal }} - {{ $formulacao->nome }}
                                    </h5>
                                </div>
                                <!-- Conteúdo -->
                                <div class="p-4">
                                    <h6 class="text-lg font-bold mb-3 text-black">Ingredientes:</h6>
                                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                                    @foreach ($uniqueInsumos as $insumo)
                                        <li>{{ $insumo->nome_produto }} ({{ $insumo->quantidade_insumo }} KG)</li>
                                    @endforeach
                                    </ul>
                                    <p class="mt-4 text-gray-800 font-semibold">
                                        Resultado total: <span class="text-green-600">{{ number_format($formulacao->quantidade_total_kg, 2) }} KG</span>
                                    </p>
                                </div>
                                <!-- Botões -->
                                <div class="flex justify-between px-4 pb-4 mt-auto">
                                    <a href="{{ route('admin.bateladas.create', ['formulacao_id' => $formulacao->id]) }}"
                                        class="rounded-md bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 text-sm shadow-md transition-all">
                                        Produzir mais
                                    </a>
                                    <button class="rounded-md bg-green-600 hover:bg-green-800 text-white py-2 px-4 text-sm shadow-md transition-all">
                                        Distribuir
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
