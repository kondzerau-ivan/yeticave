<main>
    <?= $navigation; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php if (!empty($bets)) : ?>
                <?php foreach ($bets as $bet) : ?>
                    <?php if ($bet['is_winner']) {
                        $modificator = ' rates__item--win';
                    } elseif ($bet['is_finished']) {
                        $modificator = ' rates__item--end';
                    } else {
                        $modificator = '';
                    }
                    ?>
                    <tr class="rates__item<?= $modificator; ?>">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="uploads/<?= htmlspecialchars($bet['lot_image']); ?>" width="54" height="40" alt="<?= htmlspecialchars($bet['lot_name']); ?>">
                            </div>
                            <div>
                                <h3 class="rates__title"><a href="lot.html"><?= htmlspecialchars($bet['lot_name']); ?></a></h3>
                                <p><?= htmlspecialchars($bet['contacts']); ?></p>
                            </div>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($bet['category_name']); ?>
                        </td>
                        <td class="rates__timer">
                            <?php if ($modificator === ' rates__item--win') : ?>
                                <div class="timer timer--win">Ставка выиграла</div>
                            <?php elseif ($modificator === ' rates__item--end') : ?>
                                <div class="timer timer--end">Торги окончены</div>
                            <?php else : ?>
                                <?php [$h, $i] = getDateRange($bet['expiration_date']) ?>
                                <div class="timer <?= $h === 0 ? ' timer--finishing' : '' ?>">
                                    <?= sprintf('%02d', $h) . ':' . sprintf('%02d', $i) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="rates__price">
                            <?= formatPrice(htmlspecialchars($bet['amount'])); ?>
                        </td>
                        <td class="rates__time">
                            <?php [$h, $i] = getBetDateRange($bet['created_at']) ?>
                            <?= sprintf('%02d', $h) . ':' . sprintf('%02d', $i) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </section>
</main>
