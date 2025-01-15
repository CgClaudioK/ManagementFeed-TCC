<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Cadastrar Insumo') }}
        </h2>
    </x-slot>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Botão para a página de cadastro de produto -->
                    <!-- <div class="mb-6 flex justify-end">
                        <a href="{{ route('admin.produtos.create') }}" 
                           class="bg-blue-700 px-4 py-2 border border-blue-900 rounded hover:bg-blue-900 transition duration-300 ease-in-out text-white">
                            + Cadastrar Produto
                        </a>
                    </div> -->

                    <!-- Formulário de Cadastro do Insumo -->
                    <form action="{{ route('admin.insumos.store') }}" method="POST">
                        @csrf
                        
                        <div class="w-full mb-6">
                            <label for="id_produto">Nome do Produto</label>
                             <!-- Botão para cadastrar um novo produto -->
                             <a href="{{ route('admin.produtos.create') }}" 
                                class="bg-green-500 px-2 border justify-content border-green-500 rounded hover:bg-green-500 transition duration-300 ease-in-out text-white">
                                <i class="fas fa-plus"></i>
                            </a>
                            <div class="flex items-center space-x-2">
                                <select id="id_produto" name="id_produto" class="w-full border my-2 dark:border-gray-700 rounded dark:bg-gray-900">
                                    <option value="">-- Selecione um Produto --</option>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}">{{ $produto->nome_produto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('id_produto')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="unidade">Unidade de Peso</label>
                            <select id="unidade" name="unidade" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                                <option value="" selected>-- Selecione a Unidade --</option>
                                <option value="KG">KG</option>
                                <option value="SACA">SACA</option>
                                <option value="G">G</option>
                                <option value="ML">ML</option>
                                <option value="L">L</option>
                            </select>
                            @error('unidade')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="quantidade_insumo">Quantidade</label>
                            <input id="quantidade_insumo" name="quantidade_insumo" placeholder="Quantidade" type="text" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('quantidade_insumo')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="valor_insumo_kg">Valor/Kg</label>
                            <input id="valor_insumo_kg" name="valor_insumo_kg"  placeholder="R$ 0,00" type="text" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('valor_insumo_kg')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="valor_unitario">Valor Unitário</label>
                            <input id="valor_unitario" name="valor_unitario"  placeholder="R$ 0,00" type="text" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('valor_unitario')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="valor_total">Valor Total</label>
                            <input id="valor_total" name="valor_total"  placeholder="R$ 0,00" type="text" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('valor_total')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="w-full mb-6">
                            <label for="kg_insumo_total">KG Total</label>
                            <input id="kg_insumo_total" name="kg_insumo_total" placeholder="KG Total" type="text" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                            @error('kg_insumo_total')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button class="bg-green-700 px-4 py-2 border border-green-900 rounded hover:bg-green-900 transition duration-300 ease-in-out text-white"> 
                            Salvar 
                        </button> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
