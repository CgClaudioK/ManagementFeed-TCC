<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- {{ __("FORMULAÇÕES") }} -->
                    <div class="flex space-x-6">
                        <!-- Card 1 -->
                        @foreach ($formulacoes as $formulacao)
                        <div class="relative flex text-slate-800 flex-col my-6 bg-gray-400 shadow-sm border border-black rounded-lg w-96">
                            <div class="p-4">
                                <h5 class="mb-2 text-xl font-semibold">
                                    {{ $formulacao->tipo_animal }} - {{ $formulacao->nome }}
                                </h5>
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-700">
                                            <th class="font-bold text-left px-4 py-2">Ingredientes:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-normal px-4 py-2 text-left">
                                            @forelse ($formulacao->insumos as $insumo)
                                                {{ $insumo->produto->nome_produto }} ({{ $insumo->pivot->quantidade }} kg) <br>
                                                
                                            @empty
                                                <li>Nenhum insumo encontrado</li>
                                            @endforelse
                                            <br>
                                            Resulta em: {{number_format( $formulacao->quantidade_total_kg )}} KG
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <button class="rounded-md bg-slate-800 py-2 px-4 mt-6 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                                    Produzir mais
                                </button>
                                <button class="rounded-md justify-items-end bg-slate-800 py-2 px-4 mt-6 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                                    Distribuir
                                </button>
                            </div>
                        </div>
                        
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
