<!-- 
<?php if(isset($msg_primary)): ?><div class="alert alert-primary" role="alert"> <?php echo $msg_primary; ?> </div><?php endif; ?>
<?php if(isset($msg_secondary)): ?><div class="alert alert-secondary" role="alert"> <?php echo $msg_secondary; ?> </div><?php endif; ?>
<?php if(isset($msg_danger)): ?><div class="alert alert-danger" role="alert"> <?php echo $msg_danger; ?> </div><?php endif; ?>
<?php if(isset($msg_warning)): ?><div class="alert alert-warning" role="alert"> <?php echo $msg_warning; ?> </div><?php endif; ?>
<?php if(isset($msg_info)): ?><div class="alert alert-info" role="alert"> <?php echo $msg_info; ?> </div><?php endif; ?>
<?php if(isset($msg_light)): ?><div class="alert alert-light" role="alert"> <?php echo $msg_light; ?> </div><?php endif; ?>
<?php if(isset($msg_dark)): ?><div class="alert alert-dark" role="alert"> <?php echo $msg_dark; ?> </div><?php endif; ?>

 -->

<?php if(isset($msg_error)): ?><div class="w3-panel w3-pale-red w3-border w3-center" style="padding: 10px"><b> <?php echo $msg_error; ?> </b></div><?php endif; ?>
<?php if(isset($msg_success)): ?><div class="w3-panel w3-pale-green w3-border w3-center" style="padding: 10px"><b> <?php echo $msg_success; ?> </b></div><?php endif; ?>