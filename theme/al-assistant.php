<?php
/*
Template Name: al-assistant
*/
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат-бот на PHP</title>
</head>

<body>
    <h1>Чат-бот на PHP</h1>
    <div id="chat"></div>
    <form id="chatForm" method="POST">
        <input type="text" name="userInput" placeholder="Введите сообщение..." required>
        <button type="submit">Отправить</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userInput = $_POST['userInput'];
        echo "<div>User: $userInput</div>";

        $apiKey = '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';
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [['role' => 'user', 'content' => $userInput]]
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n" .
                    "Authorization: Bearer $apiKey\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents('https://api.openai.com/v1/chat/completions', false, $context);
        $response = json_decode($result, true);
        $botMessage = $response['choices'][0]['message']['content'];
        echo "<div>Bot: $botMessage</div>";
    }
    ?>
</body>

</html>