<?php
namespace donami\Helper;

trait Helper {

  static function getUserRank($points) {

    $rank = 'New guy';

    if ($points > 100) {
      $rank = 'Highest';
    }
    else if ($points > 50) {
      $rank = 'Medium';
    }
    else if ($points > 20) {
      $rank = 'Beginner';
    }

    return $rank;
  }


}
