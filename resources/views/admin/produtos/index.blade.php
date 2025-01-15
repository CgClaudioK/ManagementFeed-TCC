<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Listagem de Produtos') }}
        </h2>
    </x-slot>
    <script src="{{ mix('js/sweetalerts.js') }}"></script>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-end mb-8 pr-4" style="margin-bottom:10px;">
                <a href="{{ route('admin.produtos.create') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                    hover:bg-green-900 transition duration-300 ease-in-out rounded">Cadastrar Produto</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="font-bold text-center px-4 py-2">#</th>
                                <th class="font-bold text-center px-4 py-2">Nome do produto</th>
                                <th class="font-bold text-center px-4 py-2">Nome Comercial</th>
                                <th class="font-bold text-center px-4 py-2">Criado Em</th>
                                <th class="font-bold text-center px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ( $produtos as $produto )
                                <tr>
                                    <td class="font-normal px-4 py-2 text-center">{{ $produto->id }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $produto->nome_produto ?? 'Não encontrado'  }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $produto->nome_comercial }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $produto->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{route('admin.produtos.edit', ['produto'=> $produto->id])}}" class="px-2 py-1 text-black dark:text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.produtos.destroy', ['produto' => $produto->id]) }}" method="POST" id="delete-form-{{ $produto->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeletion(event, 'delete-form-{{ $produto->id }}')" class="px-2 py-1 text-black dark:text-white">
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
                                        <h3>Nenhum produto Cadastrado...</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-10">
                        {{ $produtos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>