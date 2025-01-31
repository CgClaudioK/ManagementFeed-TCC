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
            <!-- <a href="{{ route('admin.insumos.movimentacoes') }}" class="px-4 py-2 border border-blue-900 bg-blue-600 text-white hover:bg-blue-900 transition duration-300 ease-in-out rounded">
                Ver Histórico de Movimentações
            </a> -->
                <a href="{{ route('admin.insumos.create') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                    hover:bg-green-900 transition duration-300 ease-in-out rounded">Cadastrar Insumo</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="font-bold text-center px-4 py-2">#</th>
                                <th class="font-bold text-center px-4 py-2">Nome</th>
                                <th class="font-bold text-center px-4 py-2">Unidade</th>
                                <th class="font-bold text-center px-4 py-2">Quantidade</th>
                                <th class="font-bold text-center px-4 py-2">Valor Unitário</th>
                                <th class="font-bold text-center px-4 py-2">Valor Total</th>
                                <th class="font-bold text-center px-4 py-2">KG Total</th>
                                <th class="font-bold text-center px-4 py-2">Valor/Kg</th>
                                <th class="font-bold text-center px-4 py-2">Criado Em</th>
                                <th class="font-bold text-center px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ( $insumos as $insumo )
                                <tr>
                                    <td class="font-normal px-4 py-2 text-center">{{ $insumo->insumo_id }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $insumo->nome_produto ?? 'Não encontrado'  }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $insumo->unidade }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ number_format($insumo->quantidade_insumo, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center"> R$ {{ number_format($insumo->valor_unitario, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center"> R$ {{ number_format($insumo->valor_total, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ number_format($insumo->kg_insumo_total, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center"> R$ {{ number_format($insumo->valor_insumo_kg, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">
                                        {{ \Carbon\Carbon::parse($insumo->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="font-normal px-4 py-2 text-center">
                                    <div class="flex justify-center gap-2">
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
                                    <td colspan="5">
                                        <h3>Nenhum Insumo Cadastrado...</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-10">
                        {{ $insumos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>