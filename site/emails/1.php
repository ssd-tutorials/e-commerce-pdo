<p>Dear <?php echo $first_name; ?>,</p>
<p>Thank you for registering at our website.

<?php if (!empty($password)) { ?>

	<br />Your login details are as follow:
	<p>Login: <?php echo $email; ?><br />
	Password: <?php echo $password; ?></p>

<?php } ?>

</p>

<p>In order to activate your account please click on the link below:</p>

<p><?php echo $link; ?></p>