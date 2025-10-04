<main>
    <?= $navigation; ?>
    
    <div class="container">
        <section class="lots">
            <?php if (empty($lots)) : ?>
                <h2>Ничего не найдено по вашему запросу «<span><?= isset($_GET['search']) ? $searchTarget : ''; ?></span>»</h2>
            <?php else : ?>
                <h2>Результаты поиска по запросу «<span><?= isset($_GET['search']) ? $searchTarget : ''; ?></span>»</h2>
                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="/uploads/<?= htmlspecialchars($lot['image']); ?>" width="350" height="260" alt="<?= htmlspecialchars($lot['lot_name']); ?>">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= htmlspecialchars($lot['category_name']); ?></span>
                                <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['lot_id']; ?>"><?= htmlspecialchars($lot['lot_name']); ?></a></h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <?php if ($lot['bet_count']) : ?>
                                            <span class="lot__amount"><?= htmlspecialchars($lot['bet_count']) . ' ' . get_noun_plural_form(htmlspecialchars($lot['bet_count']), 'ставка', 'ставки', 'ставок'); ?></span>
                                        <?php else : ?>
                                            <span class="lot__amount">Стартовая цена</span>
                                        <?php endif; ?>
                                        <span class="lot__cost"><?= htmlspecialchars($lot['price']); ?><b class="rub">р</b></span>
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
        <?php if (isset($pagesCount) && $pagesCount > 1) : ?>
            <ul class="pagination-list">

                <li class="pagination-item pagination-item-prev">
                    <a <?php if ($currentPage >= 2) : ?> href="/search.php?search=<?= $searchTarget; ?>&page=<?= $currentPage - 1; ?>" <?php endif; ?>>Назад</a>
                </li>

                <?php foreach ($pages as $page) : ?>
                    <li class="pagination-item<?= $currentPage == $page ? ' pagination-item-active' : ''; ?>">
                        <a href="/search.php?search=<?= $searchTarget; ?>&page=<?= $page; ?>"><?= $page; ?></a>
                    </li>
                <?php endforeach; ?>

                <li class="pagination-item pagination-item-next">
                    <a <?php if ($currentPage < $pagesCount) : ?> href="/search.php?search=<?= $searchTarget; ?>&page=<?= $currentPage + 1; ?>" <?php endif; ?>>Вперед</a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</main>
