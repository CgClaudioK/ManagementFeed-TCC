<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Listagem de Formulações') }}
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->access_level === 'ADMIN')
                <div class="w-full flex justify-end mb-8 pr-4" style="margin-bottom:10px;">
                    <a href="{{ route('admin.formulacoes.create') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                        hover:bg-green-900 transition duration-300 ease-in-out rounded">Cadastrar Formulação</a>
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full min-w-max">
                        <thead>
                        <tr class="border-b border-gray-700">
                                <th class="font-bold px-4 py-2 text-center">#</th>
                                <th class="font-bold px-4 py-2 text-center">Nome</th>
                                <th class="font-bold px-4 py-2 text-center">Tipo de Animal</th>
                                <th class="font-bold px-4 py-2 text-center">Quantidade Total (Kg)</th>
                                <th class="font-bold px-4 py-2 text-center">Criado Em</th>
                                @if(auth()->user()->access_level === 'ADMIN')
                                    <th class="font-bold text-left px-4 py-2">Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($formulacoes as $formulacao)
                                <tr class="border-gray-600">
                                    <td class="font-normal px-4 py-2 text-center">{{ $formulacao->id }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $formulacao->nome }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $formulacao->tipo_animal }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ number_format($formulacao->quantidade_total_kg) }} Kg</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $formulacao->created_at->format('d/m/Y H:i') }}</td>
                                    @if(auth()->user()->access_level === 'ADMIN')
                                        <td class="font-normal px-4 py-2">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.formulacoes.edit', ['formulacao' => $formulacao->id]) }}" 
                                                    class="px-2 py-1 text-black dark:text-white">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.formulacoes.destroy', ['formulacao' => $formulacao->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="px-2 py-1 text-black dark:text-white" type="submit">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-400">
                                        Nenhuma formulação cadastrada ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($formulacoes->hasPages())
                        <div class="mt-10">
                            {{ $formulacoes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>