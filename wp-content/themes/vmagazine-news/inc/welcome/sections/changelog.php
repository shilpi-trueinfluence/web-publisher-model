<?php
/**
* Changelog
*/
?>
<div class="featured-section changelog">
<?php
WP_Filesystem();
global $wp_filesystem;
$changelog     = $wp_filesystem->get_contents( get_stylesheet_directory_uri() . '/readme.txt' );
$changelog_start 			= strpos($changelog,'== Changelog ==');
$changelog_before = substr($changelog,0,($changelog_start));
$changelog = str_replace($changelog_before,'',$changelog);
$changelog = str_replace('== Changelog ==','<h2>== Changelog ==</h2>',$changelog);

for($i=0; $i<10; $i++){
	
	$changelog = str_replace('== '.$i.'.','<br/><br/>== '.$i.'.',$changelog);
}
$changelog = str_replace('*','<br/>*',$changelog);
echo ''.wp_kses_post($changelog);
echo '<hr/>';
?>
</div>