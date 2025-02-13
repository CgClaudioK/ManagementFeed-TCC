<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Detalhes da Batelada #{{ $batelada->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <h3 class="text-lg font-bold mb-4">Informações da Batelada</h3>
                    <p><strong>Formulação:</strong> {{ $batelada->formulacao->nome ?? 'N/A' }}</p>
                    <p><strong>Data de Produção:</strong> {{ $batelada->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Quantidade Total:</strong> {{ $batelada->quantidade_produzida }} kg</p>
                    <p><strong>Custo Total:</strong> R$ {{ number_format($batelada->custo_total, 2, ',', '.') }}</p>
                    <p><strong>Valor por Kg:</strong> R$ {{ number_format($batelada->valor_por_kg, 2, ',', '.') }}</p>

                    <h3 class="text-lg font-bold mt-6 mb-4">Ingredientes Utilizados</h3>
                    <table class="w-full border-collapse border border-gray-500">
                        <thead>
                            <tr class="bg-green-700 text-white">
                                <th class="border border-gray-500 px-4 py-2">Nome do Insumo</th>
                                <th class="border border-gray-500 px-4 py-2">Quantidade Utilizada (kg)</th>
                                <th class="border border-gray-500 px-4 py-2">Custo por Kg</th>
                                <th class="border border-gray-500 px-4 py-2">Custo Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batelada->formulacao->insumos as $insumo)
                                <tr>
                                    <td class="border border-gray-500 px-4 py-2 text-center">{{ $insumo->produto->nome_produto ?? 'N/A' }}</td>
                                    <td class="border border-gray-500 px-4 py-2 text-center">{{ $insumo->pivot->quantidade ?? 0 }} kg</td>
                                    <td class="border border-gray-500 px-4 py-2 text-center">R$ {{ number_format($insumo->valor_insumo_kg, 2, ',', '.') }}</td>
                                    <td class="border border-gray-500 px-4 py-2 text-center">R$ {{ number_format(($insumo->pivot->quantidade ?? 0) * $insumo->valor_insumo_kg, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        <a href="{{ route('admin.bateladas.index') }}" class="bg-green-700 text-white px-4 py-2 rounded">
                            Voltar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
