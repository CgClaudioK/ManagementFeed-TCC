<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Produto') }}
        </h2>
    </x-slot>
    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Formulário de Cadastro de Produto -->
                    <form action="{{ route('admin.produtos.store') }}" method="POST">
                        @csrf
                        <div class="w-full mb-6">
                            <label for="nome_produto">Nome do Produto</label>
                            <input id="nome_produto" name="nome_produto" placeholder="Digite o nome do produto" type="text" 
                                   class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('nome_produto')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="nome_comercial">Nome Comercial</label>
                            <input id="nome_comercial" name="nome_comercial" placeholder="Digite o nome comercial" type="text" 
                                   class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('nome_comercial')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button class="bg-green-700 px-4 py-2 border border-green-900 rounded hover:bg-green-900 transition duration-300 ease-in-out text-white">
                            Salvar Produto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
