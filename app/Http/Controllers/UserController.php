<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all(); // Obtém todos os usuários cadastrados
        return view('admin.users.index', compact('users')); // Passa os dados para a view
    }
    public function create()
    {
        return view('admin.users.create'); // Retorna a view do formulário de cadastro
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'access_level' => 'required|in:USER,ADMIN',
            'status' => 'required|in:ATIVO,INATIVO',
        ]);

        $user->update($request->only('name', 'email', 'access_level', 'status'));

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function toggleStatus(User $user)
    {
        $user->status = $user->isActive() ? 'INATIVO' : 'ATIVO'; // Alterna entre true e false
        $user->save();

        $status = $user->status;
        return redirect()->route('admin.users.index')->with('success', "Usuário {$status} com sucesso!");
    }


    public function sendTestEmail()
    {
        Mail::raw('Teste de envio com Mailtrap!', function ($message) {
            $message->to('destinatario@dominio.com')
                    ->subject('Teste Laravel com Mailtrap');
        });

        return 'Email enviado com sucesso!';
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'access_level' => 'required|in:USER,ADMIN', // USER ou ADMIN
        ]);

        $password = Str::random(12); // Gera uma senha aleatória

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'access_level' => $request->access_level,
        ]);

        Mail::to($user->email)->send(new UserCredentialsMail($user, $password));

        return redirect()->route('admin.users.create')->with('success', 'Usuário criado com sucesso. A senha foi enviada para o e-mail do usuário.');
    }
}
