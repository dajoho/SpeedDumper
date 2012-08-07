<?php
## Configuration
$db    = '';
$user  = '';
$pass  = '';
$host  = '';
$utf8  = false;
$nohup = true;
$gzip  = true;
$file  = 'dump-'.$db.'-'.date('Ymd_His').'.sql';


## Dump Logic
if (!empty($_POST)) {
  foreach (glob('*.sql*') as $file) {
    $file = basename($file);
    echo '<a href="'.$file.'">'.$file.'</a> - '.round(filesize($file)/1024,2).'kB<br>';
  }
  die();
}
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
$script = <<<JQUERY
<script type="text/javascript">
jQuery(document).ready(function(){
  setInterval(function(){
    jQuery.post('?',{ sizes: 1 }, function(data){
      jQuery('#response').html(data);
    });
  }, 500);
});
</script>
JQUERY;
echo $script;
echo '<pre>';
$dump = '';
if ($nohup == 1) {
  $dump .= 'nohup ';
}
$dump = 'mysqldump --user '.$user.' -p'.$pass.' '.$db
        .' --host='.$host.' --dump-date=false ';
if (!$utf8) {
  $dump .= '--default-character-set=latin1 ';
}
if ($gzip) {
  $file .= '.gz';
  $dump .= '| gzip ';
}
$dump .= '> '.$file.' 2>&1 &';

system($dump);
echo '<div id="response"></div>';
echo '</pre>';
?>