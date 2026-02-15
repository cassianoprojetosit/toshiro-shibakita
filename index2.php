<?php
$conn = new mysqli("mysql-db", "root", "123456", "meubanco");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);

    if (!empty($nome) && !empty($email)) {

        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);

        if ($stmt->execute()) {
            $mensagem = "Usuário cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar.";
        }

        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM usuarios ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuários</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        input { padding: 5px; margin: 5px 0; }
        button { padding: 6px 12px; }
        table { border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; }
        th { background: #f2f2f2; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h2>Cadastro de Usuário</h2>

<?php if ($mensagem): ?>
    <p class="msg"><?= $mensagem ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <button type="submit">Cadastrar</button>
</form>

<h2>Usuários Cadastrados</h2>

<table>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Criado em</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row["id"] ?></td>
    <td><?= $row["nome"] ?></td>
    <td><?= $row["email"] ?></td>
    <td><?= $row["criado_em"] ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
