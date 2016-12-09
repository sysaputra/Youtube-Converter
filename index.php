<!DOCTYPE html>
<html> 
	<head> 
	<meta charset="utf-8"> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>

<body>
<nav class="navbar navbar-inverse navbar-static-top">
  <div class="container-fluid">
	<div class="navbar-header">
		<a class="navbar-brand" href="#">
        <img alt="Brand" src="icon/48px/YouTube-social-circle_red_48px.png"></a>
		<a class="navbar-brand" href="#">Youtube Converter</a>		
	</div>
	<p class="navbar-text">131110685 Syaifuddin Yudha Saputra | 131110688 Christian Ari Kurniawan | 131110731 Susi Susilowati</p>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">	
		<form class="navbar-form navbar-right" role="search">
			<div class="form-group">
			<input type="text" class="form-control" name="keyword" placeholder="Masukkan Keyword">
			</div>
			<button type="submit" class="btn btn-default">Cari</button>	
		</form>
    </div>
  </div>
</nav>

<?php

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

// Output HTML
echo $html;
?>
</body>
</html>