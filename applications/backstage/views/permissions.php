<div class="user-info">
    <?php if ( isset($message) ): ?>
    <div id="message-wrapper">
        <div class="message">
            <?php print $message; ?>
        </div>
    </div>
    <?php endif; ?>

	<?php if ( $this->user_model->logged_in() ) : ?>
	<nav>
		<ul>
			<li class="active">
				<a href="<?php print base_url(); ?>permissions/roles">Roles / Groups</a>
				<span><img src="/demo/client/static/images/green-triangle.png" class="greenTriangle"/></span>
			</li>
		</ul>
	</nav>
	<?php endif; ?>
	<?php echo $this->load->view("permissions/" . $sub_view, $this->data); ?>
</div>