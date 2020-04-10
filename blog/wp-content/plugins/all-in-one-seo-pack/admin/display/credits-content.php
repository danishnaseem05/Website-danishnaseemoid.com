<?php
/**
 * Credits Content
 *
 * Displays AIOSEOP's team and additional individuals who have contributed.
 *
 * @link https://wordpress.org/plugins/all-in-one-seo-pack/
 *
 * @package All_in_One_SEO_Pack
 * @since ?
 */

?>
<div class="wrap credits-wrap">

<p class="about-description">
<?php
	/* translators: %s is a placeholder so it should not be translated. It will be replaced with the name of the plugin, All in One SEO Pack. */
	printf( __( '%s is created by a worldwide network of friendly folks like these.', 'all-in-one-seo-pack' ), AIOSEOP_PLUGIN_NAME );
?>
	</p>

<h3 class="wp-people-group"><?php _e( 'Project Leaders', 'all-in-one-seo-pack' ); ?></h3>
<ul class="wp-people-group " id="wp-people-group-project-leaders">
	<li class="wp-person" id="wp-person-stevemortiboy">
		<a class="web" target="_blank" href="https://twitter.com/wpsmort"><img alt="" class="gravatar" src="https://www.gravatar.com/avatar/40e33d813c16a63500675d851b0cbf3a?s=60">
			Steve Mortiboy</a>
		<span class="title"><?php _e( 'Project Manager', 'all-in-one-seo-pack' ); ?></span>
	</li>
</ul>

<h3 class="wp-people-group"><?php printf( __( 'Core Team', 'all-in-one-seo-pack' ) ); ?></h3>
<ul class="wp-people-group " id="wp-people-group-contributors">
	<li class="wp-person" id="wp-person-arnaudbroes">
		<a class="web" target="_blank" href="https://profiles.wordpress.org/arnaudbroes"><img alt="" class="gravatar" src="https://www.gravatar.com/avatar/0ce0d554c2b0bd61d326e15c8dcde756?s=60">
			Arnaud Broes</a>
		<span class="title"><?php _e( 'Team Lead', 'all-in-one-seo-pack' ); ?></span>
	</li>
	<li class="wp-person" id="EkoJR">
		<a class="web" target="_blank" href="https://profiles.wordpress.org/EkoJR/"><img alt="" class="gravatar" src="https://secure.gravatar.com/avatar/bb4c78fe944b58bd5f127d836500c30a?s=200&d=mm&r=g">
			Ben Reames</a>
		<span class="title"><?php _e( 'Development Team', 'all-in-one-seo-pack' ); ?></span>
	</li>

</ul>

<h3 class="wp-people-group dashicons-before dashicons-translation">
<?php
/* translators: In this context, "translation contributors" are translators who submit strings on translate.wordpress.org and "translation editors" are those who proofread and approve them (also known as PTEs - Project Translation Editors).*/
printf( _e( 'Translation contributors and translation editors', 'all-in-one-seo-pack' ), '1.2' );
?>
</h3>
<p class="wp-credits-list">
	<a href="https://profiles.wordpress.org/pierrelannoy/" target="_blank">Pierre Lannoy</a>,
	<a href="https://profiles.wordpress.org/sonjanyc/" target="_blank">Sonja Leix</a>,
	<a href="https://profiles.wordpress.org/dev-ide/" target="_blank">Adil El hallaoui</a>,
	<a href="https://profiles.wordpress.org/simonie/" target="_blank">simonie</a>,
	<a href="https://profiles.wordpress.org/lenasterg/" target="_blank">lenasterg</a>,
	<a href="https://profiles.wordpress.org/arnaudbroes/" target="_blank">Arnaud Broes</a>,
	<a href="https://profiles.wordpress.org/pixolin/" target="_blank">Bego Mario Garde</a>,
	<a href="https://profiles.wordpress.org/wp-yogi/" target="_blank">wp-yogi</a>,
	<a href="https://profiles.wordpress.org/wpsmort/" target="_blank">Steve Mortiboy</a>,
	<a href="https://profiles.wordpress.org/webaware/" target="_blank">webaware</a>,
	<a href="https://profiles.wordpress.org/escribirelmundo/" target="_blank">escribirelmundo</a>,
	<a href="https://profiles.wordpress.org/casiepa/" target="_blank">Pascal Casier</a>,
	<a href="https://profiles.wordpress.org/shoheitanaka/" target="_blank">Shohei Tanaka</a>,
	<a href="https://profiles.wordpress.org/nurron/" target="_blank">Nurron Shodiqin</a>,
	<a href="https://profiles.wordpress.org/aprmndr/" target="_blank">Alyssa Primandaru</a>,
	<a href="https://profiles.wordpress.org/facestoro/" target="_blank">facestoro</a>,
	<a href="https://profiles.wordpress.org/yuqianl/" target="_blank">Dawa Torbert</a>,
	<a href="https://profiles.wordpress.org/hallsofmontezuma/" target="_blank">Michael Torbert</a>,
	<a href="https://profiles.wordpress.org/istvanzseller/" target="_blank">Istvan Zseller</a>,
	<a href="https://profiles.wordpress.org/paaljoachim" target="_blank">Paal Joachim Romdahl</a>,
	<a href="https://profiles.wordpress.org/almaz/" target="_blank">Almaz Mannanov</a>,
	<a href="https://profiles.wordpress.org/vide13 /" target="_blank">vide13</a>,
	<a href="https://profiles.wordpress.org/yuraz/" target="_blank">Jurica Zuanovic</a>,
	<a href="https://profiles.wordpress.org/arhipaiva/" target="_blank">arhipaiva</a>,
	<a href="https://profiles.wordpress.org/maximanikin/" target="_blank">Maxim Anikin</a>,
	<a href="https://profiles.wordpress.org/petya/" target="_blank">Petya Raykovska</a>,
	<a href="https://profiles.wordpress.org/hathanh0809/" target="_blank">hathanh0809</a>,
	<a href="https://profiles.wordpress.org/cedric3131/" target="_blank">Cédric Valmary</a>,
	<a href="https://profiles.wordpress.org/smitka/" target="_blank">Vladimir Smitka</a>,
	<a href="https://profiles.wordpress.org/brewtal/" target="_blank">Paul P.</a>,
	<a href="https://profiles.wordpress.org/wpaleks/" target="_blank">Aleksander Savkovic</a>,
	<a href="https://profiles.wordpress.org/diogosanches/" target="_blank">Diogo Sanches</a>,
	<a href="https://profiles.wordpress.org/klemenfajs/" target="_blank">Klemen Fajs</a>,
	<a href="https://profiles.wordpress.org/adriancastellanos/" target="_blank">Adrian Castellanos</a>,
	<a href="https://profiles.wordpress.org/exilhamburger/" target="_blank">exilhamburger</a>,
	<a href="https://profiles.wordpress.org/garyj/" target="_blank">Gary Jones</a>,
	<a href="https://profiles.wordpress.org/fernandot/" target="_blank">Fernando Tellado</a>,
	<a href="https://profiles.wordpress.org/hiwhatsup/" target="_blank">Carlos Zuniga</a>,
	<a href="https://profiles.wordpress.org/fxbenard/" target="_blank">François Bernard</a>,
	<a href="https://profiles.wordpress.org/jack0falltrades/" target="_blank">jack0falltrades</a>,
	<a href="https://profiles.wordpress.org/dancaragea/" target="_blank">Dan Caragea</a>,
	<a href="https://profiles.wordpress.org/kyla81975/" target="_blank">kyla81975</a>,
	<a href="https://profiles.wordpress.org/arildknudsen1/" target="_blank">Arild Knudsen</a>.
</p>

</div>
