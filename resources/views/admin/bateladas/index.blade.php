<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Gerenciamento da Batelada
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-end mb-8 pr-4" style="margin-bottom:10px;">
                <a href="{{ route('admin.bateladas.create') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                    hover:bg-green-900 transition duration-300 ease-in-out rounded">Cadastrar Batelada</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full min-w-max">
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
                 <!-- Separação Visual -->
            <div class="mt-12">
                <h2 class="text-xl font-bold text-gray-100 bg-gray-700 p-4 rounded-lg shadow-lg text-center">
                    Relatórios de Produção
                </h2>
            </div>

            <!-- Tabela de Relatórios -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full min-w-max">
                        <thead class="text-white">
                            <tr>
                                <th class="font-bold text-center px-4 py-2">Mês/Ano</th>
                                <th class="font-bold text-center px-4 py-2">Nome da Formulação</th>
                                <th class="font-bold text-center px-4 py-2">Quantidade Produzida</th>
                                <th class="font-bold text-center px-4 py-2">Custo Total</th>
                                <th class="font-bold text-center px-4 py-2">Valor por KG (Médio)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100 dark:bg-gray-800">
                            @foreach($bateladaRelatorio as $batelada)
                                <tr>
                                    <td class="px-4 py-2 text-center">
                                        {{ str_pad($batelada->mes, 2, '0', STR_PAD_LEFT) }}/{{ $batelada->ano }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $batelada->nome_formulacao }}</td>
                                    <td class="px-4 py-2 text-center">{{ number_format($batelada->quantidade_total, 2, ',', '.') }} kg</td>
                                    <td class="px-4 py-2 text-center">R$ {{ number_format($batelada->custo_total, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-center">R$ {{ number_format($batelada->valor_por_kg, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal para Distribuir Ração -->
            <div id="modalDistribuir" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-xl font-bold mb-4">Distribuir Ração</h2>
                    <form id="formDistribuir" method="POST">
                        @csrf
                        <input type="hidden" id="batelada_id" name="batelada_id">
                        
                        <label class="block mb-2">Quantidade (kg)</label>
                        <input type="number" id="quantidade_distribuida" name="quantidade_distribuida" class="w-full border rounded p-2" min="1">
                        
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</button>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
    
            <script>
                function openModal(bateladaId, maxQuantidade) {
                    document.getElementById('batelada_id').value = bateladaId;
                    document.getElementById('quantidade_distribuida').max = maxQuantidade;
                    document.getElementById('modalDistribuir').style.display = "flex";
                    document.getElementById('formDistribuir').action = `/admin/bateladas/distribuir/${bateladaId}`;
                }

                function closeModal() {
                    document.getElementById('modalDistribuir').style.display = "none";
                }
            </script>
</x-app-layout>
