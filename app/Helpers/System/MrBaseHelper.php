<?php

namespace App\Helpers\System;

use App\Http\Controllers\Controller;
use FilesystemIterator;
use Illuminate\Support\Facades\File;

/*
 * Common helpers
 */

abstract class MrBaseHelper extends Controller
{
  const MR_EMAIL = 'support@mymarket.test';
  const MR_SITE_NAME = 'MyMarket';
  const MR_DOMAIN = 'MyMarket';
  const MR_SITE_URL = 'https://mymarket.ezon.by';

  const ADMIN_PHONE = '375297896282';
  const ADMIN_PHONE_FORMAT = '+375(29)789-62-82';
  const ADMIN_TELEGRAM = 'tg://resolve?domain=Allximik50';
  const ADMIN_VIBER = 'viber://chat?number=375297896282';

  /**
   * Generate short link
   *
   * @param string $url
   * @return string
   */
  public static function GetShortLink(string $url): string
  {
    return @file_get_contents("https://clck.ru/--?url=" . $url) ?? $url;
  }

  /**
   * Send Mail
   *
   * @param string $email_to
   * @param string $subject
   * @param string $message
   * @return bool
   */
  public static function SendEmail(string $email_to, string $subject, string $message): bool
  {
    if (preg_match('/\S+@\S+\.\S+/', $email_to)) {
      $headers = "Content-type: text/html; charset=UTF8 \r\n";
      $headers .= "From: " . MrBaseHelper::MR_EMAIL . "\r\n";

      return mail($email_to, $subject, $message, $headers);
    }

    return false;
  }

  /**
   * Generate random str
   *
   * @param int $length
   * @return string
   */
  public static function GenerateRandomString($length = 10): string
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }


  public static function createDir(string $dir)
  {
    File::makeDirectory($dir, 0777, true, true);
  }

  /**
   * Удаление директории со всеми файлами и папками внутри
   *
   * @param $dir
   */
  public static function DirDelete($dir)
  {
    if (!file_exists($dir)) {
      return;
    }

    $includes = new FilesystemIterator($dir);

    foreach ($includes as $include) {
      if (is_dir($include) && !is_link($include)) {
        self::DirDelete($include);
      } else {
        unlink($include);
      }
    }

    rmdir($dir);
  }

  public static function translit($s)
  {
    $s = (string)$s;                                                            // преобразуем в строковое значение
    $s = strip_tags($s);                                                        // убираем HTML-теги
    $s = str_replace(array("\n", "\r"), " ", $s);                               // убираем перевод каретки
    $s = preg_replace("/\s+/", ' ', $s);                                        // удаляем повторяющие пробелы
    $s = trim($s);                                                              // убираем пробелы в начале и конце строки
    $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
    $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
    $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
    $s = str_replace(" ", "-", $s);               // заменяем пробелы знаком минус

    return $s;                                    // возвращаем результат
  }

  private static $date_words = array(
    ['год', 'года', 'лет'],
    ['месяц', 'месяца', 'месяцев'],
    ['неделя', 'недели', 'недель'],
    ['день', 'дня', 'дней'],
    ['сутки', 'суток', 'суток'],
    ['час', 'часа', 'часов'],
    ['минута', 'минуты', 'минут'],
    ['секунда', 'секунды', 'секунд'],
    ['микросекунда', 'микросекунды', 'микросекунд'],
    ['миллисекунда', 'миллисекунды', 'миллисекунд'],
  );

  public static function num_word($value, $words, $show = true)
  {
    $num = $value % 100;
    if ($num > 19) {
      $num = $num % 10;
    }

    $out = ($show) ? $value . ' ' : '';
    switch ($num) {
      case 1:
        $out .= $words[0];
        break;
      case 2:
      case 3:
      case 4:
        $out .= $words[1];
        break;
      default:
        $out .= $words[2];
        break;
    }

    return $out;
  }

  public static function getUploadMaxSize()
  {
    static $max_size = -1;

    if ($max_size < 0) {
      // Start with post_max_size.
      $max_size = (ini_get('post_max_size'));

      // If upload_max_size is less, then reduce. Except if upload_max_size is
      // zero, which indicates no limit.
      $upload_max = (ini_get('upload_max_filesize'));
      if ((int)$upload_max > 0 && (int)$upload_max < (int)$max_size) {
        $max_size = $upload_max;
      }
    }

    return $max_size;
  }

  public static function getBoolValueDisplay(bool $value): string
  {
    if ($value) {
      return "<i class='fa fa-check text-success'></i>";
    } else {
      return "<i class='fa fa-ban text-danger'></i>";
    }
  }
}
