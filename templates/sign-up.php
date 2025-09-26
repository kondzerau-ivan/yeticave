  <main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category) : ?>
        <li class="nav__item">
          <a href="all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <form class="form container<?= !empty($errors) ? ' form--invalid' : ''; ?>" action="/sign-up.php" method="post" autocomplete="off">
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item<?= isset($errors['email']) ? ' form__item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars($registrationData['email'] ?? ''); ?>">
        <span class="form__error"><?= isset($errors) ? renderErrorsMessage($errors, 'email') : ''; ?></span>
      </div>
      <div class="form__item<?= isset($errors['password']) ? ' form__item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= htmlspecialchars($registrationData['password'] ?? ''); ?>">
        <span class="form__error"><?= isset($errors) ? renderErrorsMessage($errors, 'password') : ''; ?></span>
      </div>
      <div class="form__item<?= isset($errors['password']) ? ' form__item--invalid' : ''; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars($registrationData['name'] ?? ''); ?>">
        <span class="form__error"><?= isset($errors) ? renderErrorsMessage($errors, 'name') : ''; ?></span>
      </div>
      <div class="form__item<?= isset($errors['message']) ? ' form__item--invalid' : ''; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= htmlspecialchars($registrationData['message'] ?? ''); ?></textarea>
        <span class="form__error"><?= isset($errors) ? renderErrorsMessage($errors, 'message') : ''; ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
  </main>
