<?php defined( 'ABSPATH' ) or die; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title><?php echo __( 'MotoPress Content Editor', 'motopress-content-editor' ) . ' | ' . get_the_title(); ?></title>

	<?php do_action( 'mpce_frontend_editor_head' ); ?>

</head>
<body class="mpce-frontend-editor-active">
<div id="mpce-frontend-editor-wrapper">

	<?php do_action( 'mpce_frontend_editor_before_main' ); ?>

	<?php do_action( 'mpce_frontend_editor_main' ); ?>

	<?php do_action( 'mpce_frontend_editor_after_main' ); ?>

</div>

<?php do_action( 'mpce_frontend_editor_footer' ); ?>

</body>
</html>