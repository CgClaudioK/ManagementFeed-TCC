<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Gerenciamento da Batelada
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-end mb-8 pr-4" style="margin-bottom:10px;">
                <a href="{{ route('admin.bateladas.relatorio') }}" 
                    class="px-4 py-2 border border-blue-900 bg-blue-600 text-white
                        hover:bg-blue-900 transition duration-300 ease-in-out rounded">
                    Ver Relatório
                </a>
                <a href="{{ route('admin.bateladas.create') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                    hover:bg-green-900 transition duration-300 ease-in-out rounded">Cadastrar Batelada</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="font-bold text-center px-4 py-2">#</th>
                                <th class="font-bold text-center px-4 py-2">Formulação</th>
                                <th class="font-bold text-center px-4 py-2">Data de Produção</th>
                                <th class="font-bold text-center px-4 py-2">Quantidade Total (kg)</th>
                                <th class="font-bold text-center px-4 py-2">Custo Total</th>
                                <th class="font-bold text-center px-4 py-2">Valor/Kg</th>
                                <th class="font-bold text-center px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bateladas as $batelada)
                                <tr>
                                    <td class="font-normal px-4 py-2 text-center">{{ $batelada->id }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $batelada->formulacao->nome ?? 'N/A' }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $batelada->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $batelada->quantidade_produzida }}</td>
                                    <td class="font-normal px-4 py-2 text-center">R$ {{ number_format($batelada->custo_total, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">R$ {{ number_format($batelada->valor_por_kg, 2, ',', '.') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">
                                        <!-- Exemplo de botão para mostrar detalhes -->
                                        <a href="{{ route('admin.bateladas.show', $batelada->id) }}" class="text-black dark:text-white hover:underline fas fa-plus">
                                            
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center">Nenhuma batelada encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-10">
                        {{ $bateladas->links() }}
                    </div>
                    <div class="w-full flex justify-end mb-8 pr-4" style="margin-bottom:10px;">
                <a href="{{ route('admin.bateladas.exportarCsv') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                    hover:bg-green-900 transition duration-300 ease-in-out rounded">Cadastrar csbv</a>
            </div>
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Batelada</th>
                            <th>Formulação ID</th>
                            <th>Quantidade Produzida</th>
                            <th>Custo Total</th>
                            <th>Valor por KG</th>
                            <th>Data de Produção</th>
                            <th>Produtos</th>
                            <th>Quantidades</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bateladas as $batelada)
                        <tr>
                            <td>{{ $batelada->batelada_id }}</td>
                            <td>{{ $batelada->formulacao_id }}</td>
                            <td>{{ number_format($batelada->quantidade_produzida, 2, ',', '.') }} kg</td>
                            <td>R$ {{ number_format($batelada->custo_total, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($batelada->valor_por_kg, 2, ',', '.') }}</td>
                            <td>{{ date('d/m/Y', strtotime($batelada->data_producao)) }}</td>
                            <td>{{ $batelada->produtos }}</td>
                            <td>{{ $batelada->quantidades }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
