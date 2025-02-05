<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Editar Usuário') }}
        </h2>
    </x-slot>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-12 pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nome -->
                        <div>
                            <x-input-label for="name" :value="__('Nome')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $user->name) }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Nível de Acesso -->
                        <div class="mt-4">
                            <x-input-label for="access_level" :value="__('Nível de Acesso')" />
                            <select name="access_level" id="access_level" class="form-control border rounded dark:border-gray-700 dark:bg-gray-900" required>
                                <option value="USER" {{ $user->access_level === 'USER' ? 'selected' : '' }}>Usuário</option>
                                <option value="ADMIN" {{ $user->access_level === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="form-control border rounded dark:border-gray-700 dark:bg-gray-900" required>
                                <option value="ATIVO" {{ $user->status === 'ATIVO' ? 'selected' : '' }}>Ativo</option>
                                <option value="INATIVO" {{ $user->status === 'INATIVO' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>

                        <div class="mt-6">
                            <x-primary-button>
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
