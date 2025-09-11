<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $key => $category) : ?>
            <li class="promo__item promo__item--<?= htmlspecialchars($key); ?>">
                <a class="promo__link" href="pages/<?= htmlspecialchars($key); ?>-lots.html"><?= htmlspecialchars($category); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($lot['image']) ?>" width="350" height="260" alt="<?= htmlspecialchars($lot['name']) ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($lot['category']) ?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($lot['name']) ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= htmlspecialchars(formatPrice($lot['price'])) ?></span>
                        </div>
                        <?php [$h, $i] = getDateRange(htmlspecialchars($lot['expiration_date'])) ?>
                        <div class="lot__timer timer<?= $h === 0 ? ' timer--finishing' : '' ?>">
                            <?= sprintf('%02d', $h) . ':' . sprintf('%02d', $i) ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
