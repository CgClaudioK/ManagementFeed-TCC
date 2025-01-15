<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gerenciamento da Batelada - Detalhes
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <h3 class="font-semibold text-xl">Informações da Batelada</h3>
                        <p><strong>ID da Batelada:</strong> {{ $batelada->id }}</p>
                        <p><strong>Formulação:</strong> {{ $batelada->formulacao->nome ?? 'N/A' }}</p>
                        <p><strong>Data de Produção:</strong> {{ $batelada->data_producao }}</p>
                        <p><strong>Quantidade Total (kg):</strong> {{ $batelada->quantidade_total_kg }} kg</p>
                        <p><strong>Estoque Atual (kg):</strong> {{ $batelada->estoque_disponivel_kg }} kg</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-lg">Insumos Utilizados</h4>
                        <ul>
                            @foreach ($batelada->formulacao->insumos as $insumo)
                                <li>
                                    {{ $insumo->produto->nome_produto ?? 'Produto Desconhecido' }} -
                                    {{ $insumo->pivot->quantidade }} kg
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-lg">Distribuições</h4>
                        <ul>
                            @foreach ($batelada->distribuicoes as $distribuicao)
                                <li>
                                    <strong>Data:</strong> {{ $distribuicao->data_distribuicao }} |
                                    <strong>Quantidade:</strong> {{ $distribuicao->quantidade_distribuida_kg }} kg
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.bateladas.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
