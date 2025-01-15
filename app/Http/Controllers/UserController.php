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

        // Opcional: enviar a senha gerada por e-mail para o usuário
        Mail::to($user->email)->send(new UserCredentialsMail($user, $password));

        return redirect()->route('admin.users.create')->with('success', 'Usuário criado com sucesso. A senha foi enviada para o e-mail do usuário.');
    }
}
