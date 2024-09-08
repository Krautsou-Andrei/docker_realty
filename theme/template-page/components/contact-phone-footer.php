<div class="contact-phone-footer">
    <div class="contact-phone-footer__phone">
        <div class="wrapper-social">
            <?php
				$crb_phone = carbon_get_theme_option('crb_phone'); // Получить телефон
				$crb_phone_link = carbon_get_theme_option('crb_phone_link'); // Получить телефон для ссылки
				$crb_email = carbon_get_theme_option('crb_email'); // Получить email
				$crb_link_youtube = carbon_get_theme_option('crb_link_youtube'); 
				$crb_link_instagram = carbon_get_theme_option('crb_link_instagram'); 
				$crb_crb_link_avito = carbon_get_theme_option('crb_link_avito'); 
				$whatsaap_phone = str_replace("+", "", $crb_phone_link);
			?>
            
			<a href="https://wa.me/<?php echo $whatsaap_phone ?>" target="_blank"><img src="<?php bloginfo('template_url'); ?>/assets/images/footer/whatsapp.svg" alt="" width="22" height="22"></a>
			<a href="https://t.me/<?php echo $crb_phone_link ?>" target="_blank"><img src="<?php bloginfo('template_url'); ?>/assets/images/footer/telegram.svg" alt="" width="22" height="22"></a>
		    <a href="<?php echo $crb_link_youtube?>" target="_blank"> <img src="<?php bloginfo('template_url'); ?>/assets/images/footer/instagram.svg" alt="" width="22" height="22" /></a>
            <a href="<?php echo $crb_link_instagram?>" target="_blank"> <img src="<?php bloginfo('template_url'); ?>/assets/images/footer/youtube.svg" alt="" width="22" height="22" /></a>
            <a href="<?php echo $crb_crb_link_avito?>" target="_blank"> <img src="<?php bloginfo('template_url'); ?>/assets/images/footer/avito.svg" alt="" width="22" height="22" /></a>
		</div>
		<?php
			if (!empty($crb_phone)) {
				echo	'<a class="phone" href="tel:' . $crb_phone_link . '">' . $crb_phone . '</a>';
			}
		?>
		
	</div>
	<a href="mailto:<?php echo $crb_email ?>"><?php echo $crb_email ?></a>
</div>