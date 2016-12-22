<link rel="stylesheet" href="http://fancyapps.com/fancybox/source/jquery.fancybox.css?v=2.1.5">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<head>
<script src="/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>

<body>
<nav class="navbar navbar-inverse navbar-static-top">
<div class="container-fluid">
<div class="navbar-header">
<a class="navbar-brand" href="#">
<img alt="YT" src="https://s28.postimg.org/oy4dbguzt/You_Tube_social_circle_red_48px.png" height="40" width="40"></a>
<a class="navbar-brand" href="index.php">Youtube Converter to MP3</a>
</div>
<p class="navbar-text">131110685 Syaifuddin Yudha Saputra | 131110688 Christian Ari Kurniawan | 131110731 Susi Susilowati</p>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<form class="navbar-form navbar-right" role="search">
<div class="form-group">
<input type="text" class="form-control" name="keyword" placeholder="Masukkan Keyword">
</div>
<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Cari</button>
</form>
</div>
</div>
</nav>

<?php
// Include youtube.php
require("youtube.php");

// Data value untuk get ke youtub API
$apikey = "AIzaSyCOGUMWneBcyWhOtE0wGYt0mwZSrLzZk2Q";
$keyword = (!empty($_GET['keyword']) ? $_GET['keyword'] : "");
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

   // Mengextract videos untuk mendapatkan judul, deskripsi dll..
   foreach($videos->items as $video) {
       $gambar = $video->snippet->thumbnails->default->url;
       $judul = $video->snippet->title;
       $deskripsi = $video->snippet->description;
       $video_id = $video->id->videoId;
       
       // Lalu di jadikan HTML
       //$html .= '<div class="row">';
	   $html .= '<div class="col-sm-2">';   
	   $html .= '<div class="thumbnail">';
       $html .= '   <a href="?video='.$video_id.'">';
       $html .= '     <img src="'.$gambar.'" height="200" width="200">';
	   $html .= '<div class="caption">';
       $html .= '     <h4>'.$judul.'</h4>';
       $html .= '   </a>';
       $html .=    $deskripsi;
	   $html .= '</div>';
	   $html .= '</div>';
       $html .= '</div>';
	   $html .= '</div>';
   }

   // Membuat pagging page selanjutnya
   if(!empty($videos->nextPageToken)){
	 $html .= '<div class="col-sm-2">';  
	 $html .= '<div class="thumbnail">';
	 $html .= '   <a href="?keyword='.urlencode($keyword).'&page='.$videos->nextPageToken.'">';
	 $html .= '<img src="https://s28.postimg.org/pz4m0lbzd/next.png" height="100" width="200">';
     $html .= '<div align="center"><a href="?keyword='.urlencode($keyword).'&page='.$videos->nextPageToken.'">Lanjut</a></div>';
	 $html .= '</div>';
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
   // View untuk Video
   $html .= 	'<div class="col-sm-8">';
   $html .= 			'<div class="embed-responsive embed-responsive-16by9">';
   $html .= '				<iframe src="'.$iframe.'"></iframe>';
   $html .= 			'</div>';
   
   //View untuk Judul & Deskripsi
   $html .= '<div class="panel panel-default">';
   $html .= '<div class="panel-heading">';
   $html .= '<h4>'.$judul.'</h4>';
   $html .= '</div>';
   $html .= '<div class="panel-body">';
   $html .= '<p class="text-jutify">'.$deskripsi.'</p>';
   $html .= '</div>';
   $html .= '</div>';
   $html .= '</div>';
   
   //View untuk Atribut
   $html .= '<div class="col-sm-4">';
   $html .= '<div class="well well-sm">';
   $html .= '<ul class="list-group">';
   $html .= '<li class="list-group-item list-group-item-default">';
   $html .= '<span class="badge">'.$Publish.'</span>';
   $html .= '<h4><span class="glyphicon glyphicon-globe" aria-hidden="true"></span>	Publish</h4>';
   $html .= '</li>';
   $html .= '<li class="list-group-item list-group-item-default">';
   $html .= '<span class="badge">'.$lihat.'</span>';
   $html .= '<h4><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>	Views</h4>';
   $html .= '</li>';
   $html .= '<li class="list-group-item list-group-item-default">';
   $html .= '<span class="badge">'.$komen.'</span>';
   $html .= '<h4><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>	Komentar</h4>';
   $html .= '</li>';
   $html .= '<li class="list-group-item list-group-item-default">';
   $html .= '<span class="badge">'.$suka.'</span>';
   $html .= '<h4><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>	Suka</h4>';
   $html .= '</li>';
   $html .= '<li class="list-group-item list-group-item-default">';
   $html .= '<span class="badge">'.$tidak_suka.'</span>';
   $html .= '<h4><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>	Tidak Suka</h4>';
   $html .= '</li>';
   $html .= '</ul>';
   $html .= '</div>';
   $html .= '</div>';
   
   
   $html .= '<div class="col-sm-4">';
   $html .= '<div class="embed-responsive embed-responsive-16by9">';
   $html .= '<iframe class="embed-responsive-item" src="http://embed.yt-mp3.com/watch?v='.$video_id.'" style="height:100px"></frame>';
   $html .= '</div>';
   $html .= '</div>';
   
   
   $html .= '</div>';
   
   
}

// Output HTML
echo $html;
?>

</body>
</head>