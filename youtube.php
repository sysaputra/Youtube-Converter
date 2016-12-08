<?php

class Youtube {
   
   private $apikey;
   
  function __construct($apikey) {
     $this->apikey = $apikey;
  }

   function cari($keyword = "", $page = "") {
     $json = file_get_contents('https://www.googleapis.com/youtube/v3/search?type=video&part=snippet&q='.urlencode($keyword).'&key='.$this->apikey.'&pageToken='.$page);
     $array = json_decode($json);
     return $array;
   }

   function lihat($video = "") {
     $json = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$video.'&key='.$this->apikey.'&part=snippet,statistics');
     $array = json_decode($json);
     return $array;
   }
   
}

?>