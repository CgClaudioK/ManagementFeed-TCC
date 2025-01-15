<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Cadastrar Nova Batelada
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.bateladas.store') }}" method="POST">
                        @csrf

                        <!-- Seleção da Formulação -->
                        <div class="mb-6">
                            <label for="formulacao_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Selecionar Formulação
                            </label>
                            <select id="formulacao_id" name="formulacao_id" 
                                    class="w-full border rounded dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Selecione uma formulação</option>
                                @foreach ($formulacoes as $formulacao)
                                    <option value="{{ $formulacao->id }}">{{ $formulacao->nome }}</option>
                                @endforeach
                            </select>
                            @error('formulacao_id')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Quantidade Produzida -->
                        <div class="mb-6">
                            <label for="quantidade_produzida" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Quantidade Produzida (kg)
                            </label>
                            <input type="number" id="quantidade_produzida" name="quantidade_produzida" 
                                   class="w-full border rounded dark:border-gray-700 dark:bg-gray-900" 
                                   placeholder="Digite a quantidade produzida" required>
                            @error('quantidade_produzida')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Insumos (Será preenchido dinamicamente via JavaScript) -->
                        <div id="insumos-container" class="mb-6 hidden">
                            <h3 class="text-lg font-semibold mb-4">Insumos</h3>
                            <div id="insumos-list"></div>
                        </div>

                        <!-- Exibir o valor por kg -->
                        <div id="valor-kg" class="text-lg font-semibold mt-4"></div>

                        <!-- Custo Total da Batelada -->
                        <div id="custo-total" class="text-lg font-semibold mt-4"></div>

                        <!-- Botão de Envio -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-800">
                                Cadastrar Batelada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('formulacao_id').addEventListener('change', function () {
                const formulacaoId = this.value;
                const insumosContainer = document.getElementById('insumos-container');
                const insumosList = document.getElementById('insumos-list');
                const custoTotalElement = document.getElementById('custo-total');
                const valorKgElement = document.getElementById('valor-kg'); // Aqui

                if (!formulacaoId) {
                    insumosContainer.classList.add('hidden');
                    insumosList.innerHTML = '';
                    return;
                }

                // Buscar insumos via AJAX
                fetch(`/admin/formulacoes/${formulacaoId}/insumos`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.insumos.length > 0) {
                            insumosContainer.classList.remove('hidden');
                            insumosList.innerHTML = data.insumos.map(insumo => `
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        ${insumo.produto.nome} (R$ ${insumo.valor_insumo_kg}/kg)
                                    </label>
                                    <input type="number" class="w-full border rounded dark:border-gray-700 dark:bg-gray-900" 
                                        value="${insumo.quantidade}" readonly>
                                </div>
                            `).join('');

                            // Exibir o valor por kg
                            if (valorKgElement) {
                                valorKgElement.innerHTML = `Valor por kg: R$ ${data.valor_kg.toFixed(2)}`;
                            }

                            // Exibir o custo total da batelada
                            if (custoTotalElement) {
                                custoTotalElement.innerHTML = `Custo total da batelada: R$ ${data.custo_total.toFixed(2)}`;
                            }
                        } else {
                            insumosContainer.classList.add('hidden');
                            insumosList.innerHTML = '';
                            if (custoTotalElement) custoTotalElement.innerHTML = '';
                            if (valorKgElement) valorKgElement.innerHTML = '';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar insumos:', error);
                    });
            });
        });
    </script>
</x-app-layout>
