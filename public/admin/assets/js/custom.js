$(document).ready(function(){

	$('.btnsave').attr('disabled',true);

	$('.pass').blur(function(){

		//hiding the both warning divs
		$('#passalert').hide();
		$('#cpassalert').hide();

		//fetching the value of the two input fields
		var password = $('#password').val();
		var cpassword = $('#cpassword').val();

		if(password==''){ //checking if the password field is empty after click
			// password missing
			$('#passalert').show(); //showing only the password field
			$('#passalert').removeClass('alert-danger');
			$('#passalert').addClass('alert-danger');
			$('#cpassalert').removeClass('alert-success');
			$('.btnsave').attr('disabled',true);
			$('#passalert').html('<span>Password Can\'t be empty</span>');

		}

		if(password!='' && cpassword==''){
			console.log('cpassword is empty');
			$('.btnsave').attr('disabled',true);
		}

		if (password!='' && cpassword!='' && password!=cpassword) {
			// Password do not match
			$('#cpassalert').show();
			$('#passalert').removeClass('alert-danger');
			$('#cpassalert').addClass('alert-danger');
			$('.btnsave').attr('disabled',true);
			$('#cpassalert').html('<span>Password do not match</span>');
		}

		if (password!='' && cpassword!='' && password==cpassword) {
			// Password matched
			$('#cpassalert').show();
			$('#passalert').removeClass('alert-danger');
			$('#cpassalert').removeClass('alert-danger');
			$('#cpassalert').addClass('alert-success');
			$('#cpassalert').html('<span>Password matched</span>');
			$('.btnsave').attr('disabled',false);
		}
	});


	$(document).on('change','#category',function(){
		console.log('clicked');
		$('#brands').html('<option value="" selected>Choose Brand</option>');
		var id = $(this).val();
		$.ajaxSetup({
		   headers: {
		     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   }
		});

		$.ajax({
			url:'/admin/product/category/'+id,
			type:'GET',
			success:function(res){
				// console.log(res);
				jQuery.each(res, function(index, brand) {
		            console.log(brand.name);
		            $('#brands').append('<option value="'+brand.id+'">'+brand.name+'</option>');
		        });
			}
		});
	});
});