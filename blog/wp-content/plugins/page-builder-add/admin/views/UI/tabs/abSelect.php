<?php if ( ! defined( 'ABSPATH' ) ) exit;
if (function_exists('ulpb_RenderABTestFeatures')) {
    echo ulpb_RenderABTestFeatures($postId);
}else{
	?>
	<h2 class="abTestNotice"> This feature is only available for pro users, Upgrade now to maximize your lead collection efforts. Our customers achieve an average growth of 78.3% in their leads. <a href="https://pluginops.com/page-builder?ref=abTesting"> Grow Your Leads Faster Than Ever </a>  </h2>
	<?php
}
?>