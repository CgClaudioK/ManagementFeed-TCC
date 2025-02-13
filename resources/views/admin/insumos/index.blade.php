<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Listagem de Insumos') }}
        </h2>
    </x-slot>
    <script src="{{ mix('js/sweetalerts.js') }}"></script>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-end mb-8 pr-4" style="margin-bottom:10px;">

                <a href="{{ route('admin.insumos.create') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                    hover:bg-green-900 transition duration-300 ease-in-out rounded">Cadastrar Insumo</a>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">#</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Nome</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Unidade</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Quantidade</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Valor Unitário</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Valor Total</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">KG Total</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Valor/Kg</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Criado Em</th>
                                <th class="font-bold text-center px-4 py-2 text-sm md:text-base">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ( $insumos as $insumo )
                                <tr>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base">{{ $insumo->insumo_id }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base">{{ $insumo->nome_produto ?? 'Não encontrado'  }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base">{{ $insumo->unidade }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base">{{ number_format($insumo->quantidade_insumo, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base"> R$ {{ number_format($insumo->valor_unitario, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base"> R$ {{ number_format($insumo->valor_total, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base">{{ number_format($insumo->kg_insumo_total, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base"> R$ {{ number_format($insumo->valor_insumo_kg, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center text-sm md:text-base">
                                        {{ \Carbon\Carbon::parse($insumo->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="font-normal px-4 py-2 text-center">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <a href="{{route('admin.insumos.edit', ['insumo'=> $insumo->insumo_id])}}" class="px-2 py-1 text-black dark:text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.insumos.destroy', ['insumo' => $insumo->insumo_id]) }}" method="POST" id="delete-form-{{ $insumo->insumo_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeletion(event, 'delete-form-{{ $insumo->insumo_id }}')" class="px-2 py-1 text-black dark:text-white">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>                                         
                                        </a>
                                    </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-gray-500">
                                        <h3>Nenhum Insumo Cadastrado...</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-10">
                        {{ $insumos->links() }}
                    </div>

                    <div class="mt-12">
                        <h2 class="text-xl font-bold dark:text-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-lg text-center">
                            Relatório de Insumos
                        </h2>
                    </div>
                    <br>
                    <!-- Filtro de Ano -->
                    <form method="GET" action="{{ route('admin.insumos.index') }}" class="mb-4">
                        <label for="ano" class="mr-2">Selecione o Ano:</label>
                        <select class="dark:bg-gray-800" name="ano" id="ano" onchange="this.form.submit()">
                            @for ($i = date('Y'); $i >= 2024; $i--)
                                <option value="{{ $i }}" {{ $ano == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </form>

                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="px-4 py-2 text-center text-sm md:text-base">Nome do Produto</th>
                                <th class="px-4 py-2 text-center text-sm md:text-base">Valor Total Gasto (R$)</th>
                                <th class="px-4 py-2 text-center text-sm md:text-base">Preço Médio (R$/Kg)</th>
                                <th class="px-4 py-2 text-center text-sm md:text-base">Estoque Disponível (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($relatorio as $item)
                                <tr>
                                    <td class="px-4 py-2 text-center text-sm md:text-base">{{ $item->nome_produto }}</td>
                                    <td class="px-4 py-2 text-center text-sm md:text-base">R$ {{ number_format($item->total_gasto, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-center text-sm md:text-base">R$ {{ number_format($item->preco_medio, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-center text-sm md:text-base">{{ number_format($item->estoque_disponivel, 2, ',', '.') }} Kg</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">Nenhum dado encontrado para o ano selecionado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>