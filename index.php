
<?php
$html .= '<head>';
$html .= '<link type="text/css" rel="stylesheet" href="/stylesheets/bootstrap.css">';
$html .= '<script src="/js/bootstrap.min.js"></script>';
$html .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>';
$html .= '</head>';

$html .= '<body>';
$html .= '<nav class="navbar navbar-inverse navbar-static-top">';
$html .= '<div class="container-fluid">';
$html .= '<div class="navbar-header">';
$html .= '<a class="navbar-brand" href="#">';
$html .= '<img alt="Brand" src="/YouTube-social-circle_red_48px.png"></a>';
$html .= '<a class="navbar-brand" href="#">Youtube Converter</a>';
$html .= '</div>';
$html .= '<p class="navbar-text">131110685 Syaifuddin Yudha Saputra | 131110688 Christian Ari Kurniawan | 131110731 Susi Susilowati</p>';
$html .= '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
$html .= '<form class="navbar-form navbar-right" role="search">';
$html .= '<div class="form-group">';
$html .= '<input type="text" class="form-control" name="keyword" placeholder="Masukkan Keyword">';
$html .= '</div>';
$html .= '<button type="submit" class="btn btn-default">Cari</button>';
$html .= '</form>';
$html .= '</div>';
$html .= '</div>';
$html .= '</nav>';



// Include youtube.php
require("youtube.php");

// Data value untuk get ke youtub API
$apikey = "AIzaSyCOGUMWneBcyWhOtE0wGYt0mwZSrLzZk2Q";
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
$html .= '</body>';

// Output HTML
echo $html;
?>