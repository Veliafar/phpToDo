<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap');
    <?php include __DIR__ . "/../styles.css";?>
</style>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$pageTitle?></title>
</head>
<body>



<div class="auth-wrapper">
    <form method="post" class="auth-form">
        <h2 class="auth-form__header"><?=$pageHeader?></h2>

        <div class="auth-form__error <?=$error ? 'auth-form__error--alert' : ''?>">
          <?=$error?>
        </div>

        <div class="auth-form__item">
            <label class="auth-form__label" for="name">имя</label>
            <input class="auth-form__input" type="text" id="name" name="name" required autofocus>
        </div>

        <div class="auth-form__item">
            <label class="auth-form__label" for="username">логин</label>
            <input class="auth-form__input" type="text" id="username" name="username" required>
        </div>

        <div class="auth-form__item">
            <label class="auth-form__label" for="password">пароль</label>
            <input class="auth-form__input" type="password" id="password" name="password" required>
        </div>

        <div class="auth-form__control">
            <button class="menu-button" type="submit">
                Регистрация
            </button>

            <a class="menu-button secondary-button" href="/"> Назад</a>
        </div>
    </form>

</div>

</body>
</html>