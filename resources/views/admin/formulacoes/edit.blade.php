<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Editar Formulação') }}
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.formulacoes.update', $formulacao->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="w-full mb-6">
                            <label for="nome" >Nome</label>
                            <input type="text" id="nome" name="nome" value="{{ $formulacao->nome }}" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900" required>
                        </div>

                        <div class="w-full mb-6">
                            <label for="descricao">Descrição</label>
                            <textarea id="descricao" name="descricao" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">{{ $formulacao->descricao }}</textarea>
                        </div>

                        <div class="w-full mb-6">
                            <label for="tipo_animal" >Tipo de Animal</label>
                            <input type="text" id="tipo_animal" name="tipo_animal" value="{{ $formulacao->tipo_animal }}" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900" required>
                        </div>

                        <div class="w-full mb-6">
                            <label for="quantidade_total_kg" >Quantidade Total (Kg)</label>
                            <input type="number" id="quantidade_total_kg" name="quantidade_total_kg" value="{{ $formulacao->quantidade_total_kg }}" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                        </div>

                        <div class="w-full mb-6">
                            <label for="insumos">Insumos</label>
                            <div id="insumos-list" class="space-y-4">
                                <!-- O JavaScript preencherá os insumos existentes aqui -->
                            </div>
                            <button type="button" id="add-insumo" class="mt-4 w-full bg-green-700 dark:text-white font-bold py-2 px-4 rounded">
                                + Adicionar Insumo
                            </button>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                            const insumosList = document.querySelector('#insumos-list');
                            const addInsumoButton = document.querySelector('#add-insumo');

                            // Insumos disponíveis no banco (injetados no Blade como JSON)
                            const insumosDisponiveis = @json($insumos);

                            // Insumos associados à formulação (injetados no Blade como JSON)
                            const insumosExistentes = @json($formulacao->insumos);
                            console.log(insumosDisponiveis)

                            // Função para criar um novo campo de insumo
                            const criarCampoInsumo = (insumoSelecionado = null, quantidade = '') => {
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
                                insumoSelect.appendChild(defaultOption);
                                console.log(insumosDisponiveis, 'disponi')
                                insumosDisponiveis.forEach(insumo => {
                                    const option = document.createElement('option');
                                    option.value = insumo.id_produto;
                                    console.log(insumoSelecionado , 'option select')
                                    option.textContent = `${insumo.produto.nome_produto} (${insumo.unidade})`;
                                    if (insumoSelecionado && insumoSelecionado == insumo.id_produto) {
                                        option.selected = true;
                                    }
                                    insumoSelect.appendChild(option);
                                });
                                

                                // Campo para a quantidade
                                const quantidadeInput = document.createElement('input');
                                quantidadeInput.type = 'number';
                                quantidadeInput.step = '0.001';
                                quantidadeInput.name = 'quantidades[]';
                                quantidadeInput.value = quantidade;
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
                            };

                            // // Adicionar campos para insumos existentes
                            // insumosExistentes.forEach(insumo => {
                            //     criarCampoInsumo(insumo.produto.id, insumo.pivot.quantidade);
                            // });
                            console.log('Insumos Existentes:', insumosExistentes);
                            insumosExistentes.forEach(insumo => {
                                 console.log('Criando campo para insumo:', insumo.pivot.insumo_id, 'Quantidade:', insumo.pivot.quantidade);
                                criarCampoInsumo(insumo.pivot.insumo_id, insumo.pivot.quantidade);
                            });
                        });
                        </script>

                        <div class="mt-6">
                            <button type="submit" class="bg-green-700 px-4 py-2 border border-green-900 rounded hover:bg-green-900 transition duration-300 ease-in-out text-white">
                                Atualizar Formulação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
