<h1><?php echo $this->pageTitle = __( 'Invalid Param For Token', true ); ?></h1>

<p class="error">
	<strong><?php __('Error'); ?>: </strong>
	<?php echo sprintf(
        __( 'Invalid param for token for %1$s%2$s in %3$s line %4$s. URL was %5$s.', true),
        "<em>". $controller."Controller::</em>",
        "<em>". $action ."()</em>",
        "<em>". $file ."</em>",
        "<em>". $line ."</em>",
        "<em>". $url ."</em>"
    );?>
</p>

<p class="notice">
    <strong><?php __('Notice'); ?>: </strong>
    <?php echo sprintf(__('If you want to customize this error message, edit %s', true), APP_DIR.DS."views".DS."errors".DS."invalid_parameter.ctp");?>
</p>