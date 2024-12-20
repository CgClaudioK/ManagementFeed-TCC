<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Insumo') }}
        </h2>
    </x-slot>
    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.insumos.update', ['insumo' => $insumo->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="w-full mb-6">
                            <label for="id_produto">Produto</label>
                            <select id="id_produto" name="id_produto" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                                <option value="">-- Selecione um Produto --</option>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}" 
                                        {{ $produto->id == $insumo->id_produto ? 'selected' : '' }}>
                                        {{ $produto->nome_produto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full mb-6">
                            <label for="unidade">Unidade de Peso</label>
                            <select id="unidade" name="unidade" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900">
                                <option value="KG" {{ $insumo->unidade == 'KG' ? 'selected' : '' }}>KG</option>
                                <option value="G" {{ $insumo->unidade == 'G' ? 'selected' : '' }}>G</option>
                                <option value="ML" {{ $insumo->unidade == 'ML' ? 'selected' : '' }}>ML</option>
                                <option value="L" {{ $insumo->unidade == 'L' ? 'selected' : '' }}>L</option>
                            </select>
                            @error('unidade')
                                <div class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="quantidade_insumo">Quantidade</label>
                            <input id="quantidade_insumo" name="quantidade_insumo" type="number" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900" value="{{$insumo->quantidade_insumo}}">
                            @error('quantidade_insumo')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="valor_insumo_kg">R$/Kg</label>
                            <input id="valor_insumo_kg" type="text" name="valor_insumo_kg" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900" value="{{$insumo->valor_insumo_kg}}">
                            @error('valor_insumo_kg')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="valor_unitario">Valor Unitário</label>
                            <input id="valor_unitario" type="text" name="valor_unitario" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900" value="{{$insumo->valor_unitario}}">
                            @error('valor_unitario')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="valor_total">Valor Total</label>
                            <input id="valor_total" type="text" name="valor_total" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900" value="{{$insumo->valor_total}}">
                            @error('valor_total')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label for="kg_insumo_total">KG Total</label>
                            <input id="kg_insumo_total" name="kg_insumo_total" type="text" class="w-full border dark:border-gray-700 rounded dark:bg-gray-900" value="{{$insumo->kg_insumo_total}}">
                            @error('kg_insumo_total')
                                <div
                                    class="w-full my-4 p-4 border border-red-900 bg-red-300 text-red-900 rounded font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button
                           class="px-4 py-2 border border-green-600 rounded hover:bg-green-900 transition duration-300 ease-in-out bg-green-600">
                            Atualizar
                        </button>
                        <!-- <div id="success-message" 
                            class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            Registro atualizado com sucesso!
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>