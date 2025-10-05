<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $category) : ?>
                <li class="promo__item promo__item--<?= htmlspecialchars($category['code']); ?>">
                    <a class="promo__link" href="/all-lots.php?category_id=<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <?php if (empty($lots)) : ?>
            «Ничего не найдено по вашему запросу»
        <?php else : ?>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="/uploads/<?= htmlspecialchars($lot['image']) ?>" width="350" height="260" alt="<?= htmlspecialchars($lot['lot_name']) ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($lot['category_name']) ?></span>
                            <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id']; ?>"><?= htmlspecialchars($lot['lot_name']) ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <?php if ($lot['bet_count']) : ?>
                                        <span class="lot__amount"><?= $lot['bet_count'] . ' ' . get_noun_plural_form($lot['bet_count'], 'ставка', 'ставки', 'ставок'); ?></span>
                                    <?php else : ?>
                                        <span class="lot__amount">Стартовая цена</span>
                                    <?php endif; ?>
                                    <span class="lot__cost"><?= htmlspecialchars(formatPrice($lot['start_price'])) ?></span>
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
        <?php endif; ?>
    </section>
</main>
