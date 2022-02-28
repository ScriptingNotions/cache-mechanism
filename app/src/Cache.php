<?php 
declare(strict_types=1);

namespace Homework;
require_once  __DIR__ . '/../interfaces/CacheInterface.php';

use CacheInterface;



class Cache implements CacheInterface
{

  /**
 * The path to the cache file folder
 *
 * @var string
 */
private $cachepath = '/../cache/';

/**
 * The cache file extension
 *
 * @var string
 */
private $extension = '.cache';


  /**
   * Store a mixed type value in cache for a certain amount of seconds.
   * Supported values should be scalar types and arrays.
   *
   * @param string $key
   * @param mixed $value
   * @param int $duration Duration in seconds
   * @return mixed
   */
  public function set(string $key, $value, int $duration) 
  {
    $key = sha1($key);
    $file = __DIR__ . $this->cachepath . $key . $this->extension;

    $storeData = [
      'time'   => time(),
      'duration' => $duration,
      'value'   => serialize($value)
    ];
    
    $cacheData = json_encode($storeData);

    return file_put_contents($file, $cacheData, LOCK_EX);

  }

  /**
   * Retrieve stored item.
   * Returns the same type as it was stored in.
   * Returns null if entry has expired.
   *
   * @param string $key
   * @return mixed|null
   */
  public function get(string $key) 
  {
    $key = sha1($key);
    $file = __DIR__ . $this->cachepath . $key . $this->extension;
    
    if (file_exists($file)) {
      $fileContent = file_get_contents($file);
      $cacheData = json_decode($fileContent, true);
      $isExpired = time() - $cacheData['time'] > $cacheData['duration'] ? true : false;

      if($isExpired) {
        unlink($file);

        return null;
      } else {
        
        return unserialize($cacheData['value']);
      }
    } else {

      return null;
    }
  }

}

