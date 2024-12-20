<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("CARDS DE RAÇÃO") }}
                    <div class="flex space-x-6">
                        <!-- Card 1 -->
                        <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-96">
                            <div class="p-4">
                                <h5 class="mb-2 text-slate-800 text-xl font-semibold">
                                    Suínos - Lactação: 2KG disponíveis
                                </h5>
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-700">
                                            <th class="font-bold text-left px-4 py-2">Ingredientes:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-normal px-4 py-2 text-left">. Farelo de aveia...</td>
                                        </tr>
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
                        
                        <!-- Card 2 -->
                        <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-96">
                            <div class="p-4">
                                <h5 class="mb-2 text-slate-800 text-xl font-semibold">
                                    Suínos - Gestação: 1KG disponíveis
                                </h5>
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-700">
                                            <th class="font-bold text-left px-4 py-2">Ingredientes:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-normal px-4 py-2 text-left">. Milho...</td>
                                        </tr>
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
