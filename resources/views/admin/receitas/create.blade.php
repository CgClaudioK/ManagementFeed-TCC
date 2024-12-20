<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Receita') }}
        </h2>
    </x-slot>
    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100"></div>
                    <form action="{{ route('admin.receitas.store') }}" method="POST">
                        @csrf
                        <label for="nome">Nome da Receita</label>
                        <input id="nome" name="nome" type="text" placeholder="Nome da receita" required>

                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" placeholder="Descrição opcional"></textarea>

                        <div id="insumos-container">
                        </div>
                        <button type="button" id="add-insumo">Adicionar Insumo</button>

                        <button type="submit">Salvar Receita</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>