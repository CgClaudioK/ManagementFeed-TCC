<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciais de Acesso</title>
</head>
<body>
    <h1>Olá, {{ $name }}!</h1>
    <p>Seu cadastro foi realizado com sucesso. Aqui estão suas credenciais de acesso:</p>
    <ul>
        <li><strong>E-mail:</strong> {{ $email }}</li>
        <li><strong>Senha:</strong> {{ $password }}</li>
    </ul>
    <p>Por favor, troque sua senha ao fazer login pela primeira vez.</p>
    <p>Obrigado!</p>
</body>
</html>
