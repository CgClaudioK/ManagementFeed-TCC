<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Histórico de Movimentações de Estoque') }}
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="font-bold text-center px-4 py-2">#</th>
                                <th class="font-bold text-center px-4 py-2">Insumo</th>
                                <th class="font-bold text-center px-4 py-2">Tipo</th>
                                <th class="font-bold text-center px-4 py-2">Quantidade</th>
                                <th class="font-bold text-center px-4 py-2">Valor Unitário</th>
                                <th class="font-bold text-center px-4 py-2">Valor Total</th>
                                <th class="font-bold text-center px-4 py-2">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movimentacoes as $movimentacao)
                                <tr>
                                    <td class="font-normal px-4 py-2 text-center">{{ $movimentacao->id }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $movimentacao->insumo->produto->nome_produto ?? 'Não encontrado' }}</td>
                                    <td class="font-normal px-4 py-2 text-center">
                                        <span class="{{ $movimentacao->tipo === 'entrada' ? 'text-green-500' : 'text-red-500' }}">
                                            {{ ucfirst($movimentacao->tipo) }}
                                        </span>
                                    </td>
                                    <td class="font-normal px-4 py-2 text-center">{{ number_format($movimentacao->quantidade, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">R$ {{ number_format($movimentacao->valor_unitario, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">R$ {{ number_format($movimentacao->valor_total, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $movimentacao->data_movimentacao->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h3>Nenhuma movimentação encontrada...</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-10">
                        {{ $movimentacoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
