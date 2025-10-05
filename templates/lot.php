    <main>
        <?= $navigation; ?>

        <section class="lot-item container">
            <h2><?= htmlspecialchars($lot['lot_name']); ?></h2>
            <div class="lot-item__content">
                <div class="lot-item__left">
                    <div class="lot-item__image">
                        <img src="/uploads/<?= htmlspecialchars($lot['image']); ?>" width="730" height="548" alt="<?= $lot['lot_name']; ?>">
                    </div>
                    <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category_name']); ?></span></p>
                    <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
                </div>
                <div class="lot-item__right">
                    <?php if ($is_auth) : ?>
                        <div class="lot-item__state">
                            <?php [$h, $i] = getDateRange(htmlspecialchars($lot['expiration_date'])) ?>
                            <div class="lot-item__timer timer<?= $h === 0 ? ' timer--finishing' : '' ?>">
                                <?= sprintf('%02d', $h) . ':' . sprintf('%02d', $i) ?>
                            </div>
                            <div class="lot-item__cost-state">
                                <div class="lot-item__rate">
                                    <span class="lot-item__amount">Текущая цена</span>
                                    <span class="lot-item__cost"><?= htmlspecialchars($lot['start_price']); ?></span>
                                </div>
                                <div class="lot-item__min-cost">
                                    Мин. ставка <span><?= htmlspecialchars(formatPrice($lot['step'])); ?></span>
                                </div>
                            </div>

                            <?php if ($isBetAllowed && $h !== 0) : ?>
                                <form class="lot-item__form" action="/lot.php?id=<?= htmlspecialchars($lot['id']) ?>" method="post" autocomplete="off">
                                    <p class="lot-item__form-item form__item<?= !empty($errors) ? ' form__item--invalid' : ''; ?>">
                                        <label for="cost">Ваша ставка</label>
                                        <input id="cost" type="text" name="cost" placeholder="<?= htmlspecialchars($lot['start_price'] + $lot['step']); ?>">
                                        <span class="form__error"><?= isset($errors) ? renderErrorsMessage($errors, 'cost') : ''; ?></span>
                                    </p>
                                    <button type="submit" class="button">Сделать ставку</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($betsHistory)) : ?>
                        <div class="history">
                            <h3>История ставок (<span><?= count($betsHistory); ?></span>)</h3>
                            <table class="history__list">
                                <?php foreach ($betsHistory as $item) : ?>
                                    <tr class="history__item">
                                        <td class="history__name"><?= htmlspecialchars($item['user_name']); ?></td>
                                        <td class="history__price"><?= htmlspecialchars(formatPrice($item['amount'])); ?></td>
                                        <?php [$h, $i] = getBetDateRange(htmlspecialchars($item['created_at'])) ?>
                                        <td class="history__time"><?= sprintf('%02d', $h) . ':' . sprintf('%02d', $i) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
