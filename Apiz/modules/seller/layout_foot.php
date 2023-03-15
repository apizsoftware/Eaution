	</div>
	<!-- /row -->

	</div>
	<!-- /container -->

<!-- jQuery library -->
<script src="<?php echo $home_url; ?>src/js/jquery.js"></script>

<!-- bootbox library -->
<script src="<?php echo $home_url; ?>src/js/bootbox.min.js"></script>

<!-- our custom JavaScript -->
<script src="<?php echo $home_url; ?>src/js/custom-script.js"></script>

<!-- bootstrap JavaScript -->
<script src="<?php echo $home_url; ?>src/js/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $home_url; ?>src/js/bootstrap/docs-assets/js/holder.js"></script>

<!-- bootstrap image gallery JavaScript -->
<script src="<?php echo $home_url; ?>src/js/Bootstrap-Image-Gallery-3.1.1/js/jquery.blueimp-gallery.min.js"></script>
<script src="<?php echo $home_url; ?>src/js/Bootstrap-Image-Gallery-3.1.1/js/bootstrap-image-gallery.min.js"></script>

<script>
// jQuery codes
$(document).ready(function(){

	// lightbox settings
	$('#blueimp-gallery').data('useBootstrapModal', false);
	$('#blueimp-gallery').toggleClass('blueimp-gallery-controls', true);

	$(document).on('mouseenter', '.product-img-thumb', function(){
		var data_img_id = $(this).attr('data-img-id');
		$('.product-img').hide();
		$('#product-img-'+data_img_id).show();
	});

	// add to cart button listener
	$('.add-to-cart-form').on('submit', function(){

		// info is in the table / single product layout
		var id = $(this).find('.product-id').text();
		var quantity = $(this).find('.cart-quantity').val();
		var variation_id=$('.variation').find(':selected').val();

		// redirect to add_to_cart.php, with parameter values to process the request
		window.location.href = "<?php echo $home_url; ?>add_to_cart.php?id=" + id + "&quantity=" + quantity + "&variation_id=" + variation_id;
		return false;
	});


	// catch the submit form, used to tell the user if password is good enough
	$('#register, #change-password').submit(function(){

		var password_strenght=$('#passwordStrength').text();

		if(password_strenght!='Good Password!'){
			alert('Password not strong enough');
			return false
		}

		return true;
	});

});


</script>

<!-- end HTML page -->
</body>
</html>
