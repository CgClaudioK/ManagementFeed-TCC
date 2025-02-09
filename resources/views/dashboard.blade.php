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
                            $formulacao = $formulacaoGroup->first(); 
                            $uniqueInsumos = $formulacaoGroup->unique('id_produto'); // Insumos únicos

                            $bateladasIds = $bateladas->where('formulacao_id', $formulacaoId)->pluck('id')->toArray();
                            
                            // Somar a quantidade total produzida para esta formulação
                            $quantidadeTotalProducao = $bateladas->where('formulacao_id', $formulacaoId)->sum('quantidade_produzida');

                            $quantidadeDistribuida = $estoques
                                ->whereIn('batelada_id', $bateladasIds)
                                ->where('tipo_movimento', 'saida')
                                ->sum('quantidade_movimento');

                            // Calcular a quantidade restante
                            $quantidadeRestante = max(0, $quantidadeTotalProducao - $quantidadeDistribuida);
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
                                    <p class="mt-2 text-gray-800 font-semibold flex items-center">
                                        Estoque disponível: 
                                        <span class="{{ $quantidadeRestante == 0 ? 'text-red-600' : 'text-blue-600' }} ml-1">
                                            {{ number_format($quantidadeRestante, 2) }} KG
                                        </span>
                                        @if ($quantidadeRestante == 0)
                                            <span class="ml-2 text-red-600" title="Estoque zerado">
                                                ❓
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <!-- Botões -->
                                <div class="flex justify-between px-4 pb-4 mt-auto">
                                    <a href="{{ route('admin.bateladas.create', ['formulacao_id' => $formulacao->id]) }}"
                                        class="rounded-md bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 text-sm shadow-md transition-all">
                                        Produzir mais
                                    </a>

                                    @if ($quantidadeRestante > 0)
                                        <button 
                                            onclick="openModal({{ $formulacao->id }}, {{ $quantidadeRestante }})"
                                            class="rounded-md bg-green-600 hover:bg-green-800 text-white py-2 px-4 text-sm shadow-md transition-all">
                                            Distribuir
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Distribuição -->
    <div id="modalDistribuir" class="fixed inset-0 items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4">Distribuir Ração</h2>
            <form id="formDistribuir" method="POST">
                @csrf
                <input type="hidden" id="batelada_id" name="batelada_id">

                <label class="block mb-2">Quantidade (kg)</label>
                <input type="number" id="quantidade_distribuida" name="quantidade_distribuida" class="w-full border rounded p-2" min="1">
                
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(formulacaoId, maxQuantidade) {
            document.getElementById('batelada_id').value = formulacaoId;
            document.getElementById('quantidade_distribuida').max = maxQuantidade;
            document.getElementById('modalDistribuir').classList.remove('hidden', 'flex');
            document.getElementById('modalDistribuir').classList.add('flex'); 
            document.getElementById('formDistribuir').setAttribute('action', `/admin/bateladas/distribuir/${formulacaoId}`);

        }

        function closeModal() {
            document.getElementById('modalDistribuir').classList.add('hidden');
        }
    </script>
</x-app-layout>
