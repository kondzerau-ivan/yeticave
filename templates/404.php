<style>
    main.container {
        width: unset;
        margin-top: unset;
        margin-left: unset;
        margin-right: unset;
        padding: unset;
    }
</style>

<nav class="nav">
      <ul class="nav__list container">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $category) : ?>
          <li class="nav__item">
            <a href="pages/all-lots.html"><?= htmlspecialchars($category['title']); ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

<section class="lot-item container">
    <h2>404 Страница не найдена</h2>
    <p>Данной страницы не существует на сайте.</p>
</section>