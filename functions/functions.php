<?php

/**
 * Use for price formatting
 * @param int $price - starting price
 * @return string - formatting price
 */

function priceFormat(int $price): string
{
  $price = number_format($price, 0, '', ' ');
  $result = "{$price}&nbsp₽";
  return $result;
}

/**
 * Use for date range
 * @param string $target - date in future
 * @return array - hours and minutes
 */

function getDateRange(string $target): array
{
  $origin = new DateTime('now');
  $target = new DateTime($target);
  $interval = $origin->diff($target)->format('%d %H %I');
  $arr = explode(' ', $interval);
  $hours = strval(intval($arr[0]) * 24 + intval($arr[1]));
  $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
  $minutes = $arr[2];
  $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
  return [
    'hours' => $hours,
    'minutes' => $minutes
  ];
}

/**
 * Get File Type
 * @param string $file_type
 * @return string
 */

function getFileType(string $file_type): string
{
  if ($file_type == 'image/jpeg') {
    return '.jpeg';
  } else if ($file_type == 'image/png') {
    return '.png';
  } else {
    return '';
  }
}

/**
 * Set preselect catygory
 */

function setPreselectCategory(string $sybol): string
{
  if (isset($_POST) && !empty($_POST['category'])) {
    if ($_POST['category'] == $sybol) {
      return 'selected';
    } else return '';
  } else return '';
}
