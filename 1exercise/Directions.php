<?php

class Point
{
  /** @var float */
  private $lat;
  
  /** @var float */
  private $lng;
  
  /**
   * @param float $lat
   * @param float $lng
   */
  public function __construct($lat, $lng)
  {
    $this->lat = $lat;
    $this->lng = $lng;
  }
  
  /**
   * @return string
   */
  public function __toString()
  {
    return '(' . $this->lat . ', ' . $this->lng . ')';
  }
}

class RouteDecoder
{
  /**
   * @param string $encodedRoute
   * @param int $index
   * @return int
   */
  private function getDelta($encodedRoute, &$index)
  {
    $shift = $result = 0;
    do {
      $b = unpack('V', iconv('UTF-8', 'UCS-4LE', $encodedRoute[$index++]))[1] - 63;
      $result |= ($b & 0x1f) << $shift;
      $shift += 5;
    } while ($b >= 0x20);
    return (($result & 1) ? ~($result >> 1) : ($result >> 1));
  }

  /**
   * @param string $encodedRoute
   * @return Point[]
   */
  public function decode($encodedRoute)
  {
    $return = [];
    $index = $lat = $lng = 0;

    while ($index < strlen($encodedRoute)) {

      $lng += $this->getDelta($encodedRoute, $index);

      $lat += $this->getDelta($encodedRoute, $index);

      $return[] = new Point($lat * 1e-5, $lng * 1e-5);
    }

    return $return;
  }
}

$routeDecoder = new RouteDecoder();
$points = $routeDecoder->decode('mkk_Ieg_qAiPePsHd[}CzMq@`CaAfCwCvLyApG[xBKZyCpPaDjQ');

echo 'Route has ', count($points), ' points', PHP_EOL;

foreach ($points as $point) {
  echo $point, PHP_EOL;
}
