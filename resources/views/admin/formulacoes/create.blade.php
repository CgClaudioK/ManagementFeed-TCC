<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Cadastrar Formulação') }}
        </h2>
    </x-slot>
    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.formulacoes.store') }}" method="POST">
                        @csrf
                        <div class="w-full mb-6">
                            <label for="tipo_animal">Tipo de Animal</label>
                            <select searchable id="tipo_animal" name="tipo_animal" required
                                class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                                <option value="" disabled selected>Selecione o tipo de animal</option>
                                <option value="Aves">Aves</option>
                                <option value="Avestruzes">Avestruzes</option>
                                <option value="Bovinos de corte">Bovinos de Corte</option>
                                <option value="Bovinos leiteiros">Bovinos Leiteiros</option>
                                <option value="Camarões">Camarões</option>
                                <option value="Caprinos">Caprinos</option>
                                <option value="Codornas">Codornas</option>
                                <option value="Cunicultura">Cunicultura</option>
                                <option value="Equinos">Equinos</option>
                                <option value="Ovinos">Ovinos</option>
                                <option value="Peixes">Peixes</option>
                                <option value="Pets">Pets (Cães, Gatos)</option>
                                <option value="Pombos">Pombos e Aves Ornamentais</option>
                                <option value="Répteis">Répteis</option>
                                <option value="Suínos">Suínos</option>
                            </select>
                            @error('tipo_animal')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="nome_formulacao">Nome da Formulação</label>
                            <input id="nome_formulacao" name="nome" type="text" placeholder="Ex: Lactação" required
                            class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('nome_formulacao')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="descricao">Descrição</label>
                            <textarea class="w-full border dark:border-gray-700 rounded dark:bg-gray-900"id="descricao" name="descricao" placeholder="Descrição opcional"></textarea>
                            @error('descricao')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="quantidade_total_kg">Quantidade Total (KG)</label>
                            <input type="number" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900"id="quantidade_total_kg" name="quantidade_total_kg" value='1015'></input>
                            @error('quantidade_total_kg')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="insumos">Insumos</label>
                            <div id="insumos-list" class="space-y-4">
                            </div>
                            <button type="button" id="add-insumo"
                                class="mt-4 w-full bg-green-700 dark:text-white font-bold py-2 px-4 rounded">
                                + Adicionar Insumo
                            </button>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const insumosList = document.querySelector('#insumos-list');
                                const addInsumoButton = document.querySelector('#add-insumo');

                                // Insumos disponíveis no banco (injetados no Blade como JSON)
                                const insumosDisponiveis = @json($insumos);

                                addInsumoButton.addEventListener('click', () => {
                                    const insumoDiv = document.createElement('div');
                                    insumoDiv.className = "flex items-center space-x-4";

                                    // Campo para seleção do insumo
                                    const insumoSelect = document.createElement('select');
                                    insumoSelect.name = 'insumos[]';
                                    insumoSelect.required = true;
                                    insumoSelect.className = 'w-2/3 border dark:border-gray-700 rounded dark:bg-gray-900 p-2';

                                    // Adiciona as opções do select
                                    const defaultOption = document.createElement('option');
                                    defaultOption.value = '';
                                    defaultOption.textContent = 'Selecione um insumo';
                                    defaultOption.disabled = true;
                                    defaultOption.selected = true;
                                    insumoSelect.appendChild(defaultOption);

                                    insumosDisponiveis.forEach(insumo => {
                                        const option = document.createElement('option');
                                        option.value = insumo.id; // O ID do insumo
                                        option.textContent = `${insumo.produto.nome_produto} (${insumo.unidade})`; // Nome do produto e unidade
                                        insumoSelect.appendChild(option);
                                    });

                                    // Campo para a quantidade
                                    const quantidadeInput = document.createElement('input');
                                    quantidadeInput.type = 'number';
                                    quantidadeInput.step = '0.01'; // Permite dois dígitos decimais
                                    quantidadeInput.name = 'quantidades[]';
                                    quantidadeInput.placeholder = 'Quantidade';
                                    quantidadeInput.required = true;
                                    quantidadeInput.className = 'w-1/3 border dark:border-gray-700 rounded dark:bg-gray-900 p-2';

                                    // Botão para remover o insumo
                                    const removeButton = document.createElement('button');
                                    removeButton.type = 'button';
                                    removeButton.textContent = '×';
                                    removeButton.className = 'text-red-500 font-bold';
                                    removeButton.addEventListener('click', () => insumoDiv.remove());

                                    // Adiciona os elementos ao contêiner do insumo
                                    insumoDiv.appendChild(insumoSelect);
                                    insumoDiv.appendChild(quantidadeInput);
                                    insumoDiv.appendChild(removeButton);

                                    insumosList.appendChild(insumoDiv);
                                });
                            });

                        </script>
                        <button class="bg-green-700 px-4 py-2 border border-green-900 rounded hover:bg-green-900 transition duration-300 ease-in-out text-white" type="submit">
                            Salvar Receita
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>