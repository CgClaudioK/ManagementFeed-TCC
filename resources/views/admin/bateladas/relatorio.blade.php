<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Relatório de batelada') }}
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100"></div>
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
