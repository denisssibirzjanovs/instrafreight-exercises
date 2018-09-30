<?php

$aArticles = [];

// TODO: need to cache today's articles for 24h as there is no point to load them everytime someone opens this page
if ($aJson = json_decode(file_get_contents('https://www.reddit.com/r/UpliftingNews/.json'), true))
{
  $aArticles = !empty($aJson['data']['children']) ? $aJson['data']['children'] : [];
}

require('uplifting-news.tpl.php');