<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Api</title>
</head>
<body>
    <form action="https://assembler.xloopinteractive.com/api?route=save-model" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="title" id=""> <br>
        <input type="text" name="kid" placeholder="kid" id=""> <br>
        <input type="file" name="file" placeholder="file" id=""> <br>
        <textarea name="json" id="" cols="30" placeholder="{name:value}" rows="10"></textarea> <br>
        <button type="submit">Gönder</button>

    </form>
</body>
</html>