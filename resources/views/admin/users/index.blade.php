<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Gerenciamento da Usuários
        </h2>
    </x-slot>

    <div class="py-12 pt-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-end mb-8 pr-4" style="margin-bottom:10px;">
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 border border-green-900 bg-green-600 text-white
                    hover:bg-green-900 transition duration-300 ease-in-out rounded">Registrar Usuário</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="font-bold text-center px-4 py-2">#</th>
                                <th class="font-bold text-center px-4 py-2">Nome</th>
                                <th class="font-bold text-center px-4 py-2">E-mail</th>
                                <th class="font-bold text-center px-4 py-2">Perfil de Acesso</th>
                                <th class="font-bold text-center px-4 py-2">Status</th>
                                <th class="font-bold text-center px-4 py-2">Data de Criação</th>
                                <th class="font-bold text-center px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="font-normal px-4 py-2 text-center">{{ $user->id }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $user->name }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $user->email }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $user->access_level }}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $user->status}}</td>
                                    <td class="font-normal px-4 py-2 text-center">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="font-normal px-4 py-2 text-center">
                                    <a href="{{ route( 'admin.users.edit', $user->id) }}" class="px-2 py-1 text-black dark:text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>