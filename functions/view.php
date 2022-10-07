<?php

/**
 * Use for price formatting
 * @param int $price - starting price
 * @return string - formatting price
 */

function priceFormat(int $price): string {
  $price = number_format($price, 0, '', ' ');
  $result = "{$price}&nbsp₽";
  return $result;
}
