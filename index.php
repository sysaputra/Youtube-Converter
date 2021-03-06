<style>

body {
   background: #f5f5f5
}

div.videos {
   background: #fff;
   display: inline-block;
   width: 20%;
   padding: 10px;
   margin: 15px
}

div.videos img {
   width: 100%
}

div.video {
   background: #fff;
   width: 700px;
   padding: 10px;
   margin: 20 auto
}

div.video iframe {
   width: 100%;
   border: 0;
   height: 400px
}

</style>

<form action="" method="GET">
   <input type="text" name="keyword" />
   <button>Search</button>
</form>

<?php

// Include youtube.php
require("youtube.php");

// Data value untuk get ke youtub API
$apikey = "AIzaSyDoBbGRRFgrbFx-zu0U0hOWkGvyunOm0i4";
$keyword = (!empty($_GET['keyword']) ? $_GET['keyword'] : "tutorial php");
$page = (!empty($_GET['page']) ? $_GET['page'] : "");
$video_id = (!empty($_GET['video']) ? $_GET['video'] : "");

// Membuat sebuah object
$youtube = new youtube($apikey);

// HTML untuk output
$html = '';

// Melakukan pencarian video
if(empty($video_id)){
   
   // Mencari videos berdasarkan keyword judul & output ARRAY
   $videos = $youtube->cari($keyword, $page);

   // Mengextract videos untuk mendaptkan judul, deskripsi dll..
   foreach($videos->items as $video) {
       $gambar = $video->snippet->thumbnails->default->url;
       $judul = $video->snippet->title;
       $deskripsi = $video->snippet->description;
       $video_id = $video->id->videoId;
       
       // Lalu di jadikan HTML
       $html .= '<div class="videos">';
       $html .= '   <a href="?video='.$video_id.'">';
       $html .= '     <img src="'.$gambar.'"/>';
       $html .= '     <h3>'.$judul.'</h3>';
       $html .= '   </a>';
       $html .=    $deskripsi;
       $html .= '</div>';
   }

   // Membuat pagging page selanjutnya
   if(!empty($videos->nextPageToken)){
     $html .= '<div align="center"><a href="?keyword='.urlencode($keyword).'&page='.$videos->nextPageToken.'">Lanjut</a></div>';
   }

}

// Atau melihat detail video
else{
   
   // Mencari videos berdasarkan keyword judul & output ARRAY
   $video = $youtube->lihat($video_id);

   // Mendaptkan judul, deskripsi, jumlah viewers, likes dll..
   $iframe = 'https://www.youtube.com/embed/'.$video_id;
   $judul = $video->items[0]->snippet->title;
   $deskripsi = $video->items[0]->snippet->description;
   $Publish = date_format(date_create($video->items[0]->snippet->publishedAt), "d/m/Y");
   $lihat = $video->items[0]->statistics->viewCount;
   $komen = $video->items[0]->statistics->commentCount;
   $favorit = $video->items[0]->statistics->favoriteCount;
   $suka = $video->items[0]->statistics->likeCount;
   $tidak_suka = $video->items[0]->statistics->dislikeCount;
     
   // Lalu di jadikan HTML
   $html .= '<div class="video">';
   $html .= '   <iframe src="'.$iframe.'"></iframe>';
   $html .= '   <h3>'.$judul.'</h3>';
   $html .= '   <p>Publish: '.$Publish.' - Lihat: '.$lihat.' - Komen: '.$komen.' - Favorit: '.$favorit.' - Suka: '.$suka.' - Tidak suka: '.$tidak_suka.'</p>';
   $html .=    $deskripsi;
   $html .= '</div>';
   
}
//susielek
// Output HTML
echo $html;

?>