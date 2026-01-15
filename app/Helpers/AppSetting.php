<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AppSetting
{
  /**
   * Ambil nilai setting berdasarkan key dengan dukungan Cache.
   */
  public static function get($key, $default = null)
  {
    // Kita simpan di cache selamanya (sampai dihapus manual saat update)
    return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
      $setting = DB::table('settings')->where('key', $key)->first();
      return $setting ? $setting->value : $default;
    });
  }

  /**
   * Update setting dan otomatis bersihkan cache agar data sinkron.
   */
  public static function set($key, $value)
  {
    DB::table('settings')->updateOrInsert(
      ['key' => $key],
      ['value' => $value, 'updated_at' => now()]
    );

    // Hapus cache yang lama supaya saat get() lagi, data baru yang diambil
    Cache::forget("setting_{$key}");
  }
}
