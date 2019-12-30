/***********Customer Module js code starts***************/


function addLoadingC(flag=true){
	if(flag){
		$('.customer-result').addClass('customer-js-loading');
		//$('#show_filters').addClass('customer-js-loading');
		$('#customerTable').addClass('customer-js-loading');
	}else{
		$('.customer-result').removeClass('customer-js-loading');
		//$('#show_filters').removeClass('customer-js-loading');
		$('#customerTable').removeClass('customer-js-loading');
	}
	
}



var CustomersearchStart='';
var xhr='';
var ajax_inprocess = false;
/*filter customer list*/
function filterdata(orderbyVal='', whereVal='', limit='',segments=[],page=1,gridview='default'){

	window.clearTimeout(CustomersearchStart);
	 CustomersearchStart = window.setTimeout(function () { 
	 	if (ajax_inprocess == true)
			{
			    xhr.abort();
			}
	 		addLoadingC();	 		
	 		if(gridview=='default'){	 			
	 			gridview = window.matchMedia("only screen and (max-width: 767px)").matches;
	 			if(gridview){
	 				$(".customer_grid_view").removeClass("active");
					$(".customer_list_view").removeClass("active");
					$(".customer_grid_view").addClass("active");
	 			}else{
	 				$(".customer_grid_view").removeClass("active");
					$(".customer_list_view").removeClass("active");					
	 			}
	 		}
	 		
	 		var orderby ='';
			if($('#up').hasClass('fa-arrow-down')){
				orderby = 'desc';
			}else{
				orderby = 'asc';
			}
			segments=[];
			var $currentWrap = $('.customer-body-area' );
		    var count = $currentWrap.find(".filter-container:last").data('rowid')  || 0;
		    $currentWrap.find(".filter-container").each(function () {
		    	let column = $(this).find('.customer_segment_select').find(':selected').data('column');
		    	let method = $(this).find('.customer_segment_select').find(':selected').data('method');
			    segments.push(
			    	{
			    		filter:$(this).find(".customer_segment_select").val(),
			    		column:column,
			    		method:method,
			    		option:$(this).find(".customer_option_dropdown").val(),
			    		from:$(this).find(".customer_option_dropdown_input:eq(0)").val(),
			    		to:$(this).find(".customer_option_dropdown_input:eq(1)").val()
			    		},
		    	)
			});			

			let whereVal = $('#search_user').val();

			if(!!whereVal){
				segments=[];
			}
			let limit = $('#changelimit').val();
			let filter_type = $("#filter_type").val();
			let orderbyVal = $('#orderby').val();
			let range_picker = $('#order_range').val();
			$('[data-toggle=popover]').popover('hide');	
			ajax_inprocess = true;
			xhr=$.ajax({
				url:site_url+'/getPaginatedUser',
				type:'post',
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },    
				data:{
					orderbyVal:orderbyVal, 
					orderby:orderby,
					whereVal:whereVal,
				 	limit:limit, 
				 	gridview:gridview, 
				 	range_picker:range_picker,
				 	segments:segments,
				 	page:page,
				 	filter_type:filter_type
				},
				success:function(data){
					//console.log("data.count", data);
					let text='customer';
					if(data.total>1){
						text='customers';
						$('#export_btn').html('Export '+data.total+' customers');
						if(data.total>exp_total_limit){
							$('#exportCustomer').attr('disabled', true);
						}else{
							$('#exportCustomer').attr('disabled', false);
						}
					}else{
						$('#exportCustomer').attr('disabled', false);
						$('#export_btn').html('Export');
					}
					if($('.show_rec_export_tab').is(":hidden") == true && $("#modal-settings-notifications").is(":hidden") == true){
						$('.showCustomerCount').html('<strong>Export</strong> '+data.total+' '+text+'<i class="fa fa-info-circle mx-3" aria-hidden="true"></i>');	
					}			
					$('[data-toggle="popover"]').popover(); 	
					$('[data-toggle="tooltip"]').tooltip(); 
					$('#customerTable').empty();
					$('#customerTable').html(data.html);
					//$('.customer-result').html(data.result);
					$(".select_custom").select2({		      
				      minimumResultsForSearch: Infinity,
				      allowClear: false,
				      
				  	});
					addLoadingC(false);
					ajax_inprocess = false;
				},error:function(e){
					addLoadingC(false);	
					if(e.statusText!="abort"){
						show_error_msg(msg_something_went_wrong);
					}
					console.log(e);
				}
			})	
 	}, 1000);

	
}


/*get customers on change of datepicker*/
$(function(){
	$(document).on('change', '#order_range', function(){
		var range_picker = $(this).val();
		filterdata('','', '');
	});
	filterdata('','', '');
	 var windowWidth = $(window).width();
	$(window).resize(function () {
		if ($(window).width() != windowWidth) {
			filterdata();
		}	   
	});
})






$('#customer_private_note').validate({
	errorClass: "is-invalid",
	rules:{
		'name':{
			required:true,
			maxlength:200
		},	

		
	},
	messages: {
		name: {
            required: "Please enter a check in note",
           // maxlength: jQuery.format("Enter at least {0} characters")           
        }       
      
    },
	submitHandler:function(event){			
	   $('#private_note_update_submit').attr('disabled','disabled');
		$.ajax({
	            url:site_url+"/customer/private_note_update",
	            type:"post",
	            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },    
				data:$("#customer_private_note").serialize(),
	            success: function(data){		            
	              $('#private_note_update_submit').removeAttr('disabled');
	              $('#private_note_list').html(data.html);
	              $('#contact-form').hide();
	              $('#sent').show();
	              $("#customer_private_note").find("#name").val('');
	              if(data.status){
	              	show_success_msg(data.msg);
	               $('[data-toggle="popover"]').popover(); 
	              	$(".date_calendar").each(function(){
				      let date = $(this).data('date');      
				      $(this).html(moment(date).calendar());     
				   });
	              }else{
	              	show_error_msg(data.msg);
	              }
	      }
	    });
	   return false;		
	}
});



$(document).on('click', ".remove_item_private_note_confirm", function(e){	
	let rowid = $(this).data('rowid');	
	console.log("rowid",rowid);
	$("#remove_item_private_note_confirm").val(rowid);
	$('#modal-delete-note').modal('show');		
	e.preventDefault();	
});



$(document).on('click', ".remove_item_private_note", function(e){	
	let rowid = $("#remove_item_private_note_confirm").val();
	let customer_id = $("#customer_id").val();
	e.preventDefault();
	$('#private_note_section').addClass('customer-js-loading-segment');
	$.ajax({
		url:site_url+'/customer/delete_private_note',
		type:'post',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },    
		data:{rowid:rowid,customer_id:customer_id},
		success:function(data){	

			if(data.status){
		        $('#private_note_section').removeClass('customer-js-loading-segment');
				$('#private_note_list').html(data.html);	
				show_success_msg(data.msg);
              }else{
              	show_error_msg(data.msg);
              }
              $('#modal-delete-note').modal('hide');	
			
		},error:function(e){
			$('#modal-delete-note').modal('hide');	
		}
	});
});

jQuery.validator.addMethod("atLeastOneCategory", function(value, element) {
		let child=$('input[name="product_cat[]"]:checked').length;			
		let user_role_id=$("#user_role_id").val();			
		if(user_role_id==4){
			$(".user_role_id").find(".is-invalid").show();
			return (parseFloat(child) > 0);
		}else{
			$(".user_role_id").find(".is-invalid").hide();
			return true;
		}    	
}, msg_atleast_one_category_must_be_selected);

$(document).on('change', '#user_role_id', function(){
	let user_role_id=$("#user_role_id").val();		
	let child=$('input[name="product_cat[]"]:checked').length;		
	if(user_role_id==4){
		$(".user_role_id").find(".is-invalid").show();
		return (parseFloat(child) > 0);
	}else{
		$(".user_role_id").find(".is-invalid").hide();
		return true;
	}
});

$(document).on('change', 'input[name="product_cat[]"]', function(){
	let user_role_id=$("#user_role_id").val();		
	let child=$('input[name="product_cat[]"]:checked').length;

	if(user_role_id==4){
		$(".user_role_id").find(".is-invalid").show();
		return (parseFloat(child) > 0);
	}else{
		$(".user_role_id").find(".is-invalid").hide();
		return true;
	}
});


$(document).ready(function () {
	$(document).on('click', '#submit_btn', function(){
		$('#add_user').validate({
			errorClass: "is-invalid",
			errorElement: "span",
			rules:{
				'first_name':{
					required:true,
				},
				'last_name':{
					required:true,
				},
				'user_phone':{
					required:true,
					number:true,
					maxlength:12,
					minlength:10,
				},				
				'user_email':{
					required:true,
					email:true,
					remote:{
						url: site_url+"/validateEmail",
					    type: "post",
					    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') }, 
					    data: {
					        email: function () {
					            return $("input[name='user_email']").val();
					        },
					        id: function(){
					        	if($("#id").val()!=''){
					        		return $("#id").val();
					        	}
					        }
					    }
					}
				},
				'user_role_id':{	
					required:true,
					atLeastOneCategory: true	
				},

			},messages:{
				'user_email':{
					remote:msg_email_already_exists_user,
				},
			},
			submitHandler:function(form, e){
				e.preventDefault();
				$('#add_user').addClass('customer-js-loading');
				$('#submit_btn').addClass('is-loading');

				var formdata = $('#add_user').serializeArray();

				$.ajax({
					url:site_url+'/manage_user',
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') }, 
				    data: formdata,
					success:function(data){
						$('#modal-add_user').modal('hide');
						$('#id').val('');
						if(data.status==1){
							show_success_msg(data.msg);
						}else{
							show_error_msg(data.msg);
						}
						$('#add_user')[0].reset();
						$('#add_user').removeClass('customer-js-loading');
						$('#submit_btn').removeClass('is-loading');
						setTimeout(function(){

							filterdata();
						}, timeout);
					},error:function(e){
						show_error_msg(e);
					}
				});
			}
		});
	})
});




//edit_user
$(document).on('click', '.edit_user', function(){
	let id = $(this).attr('data-id');
	$('#id').val(id);
	$.get(site_url+'/manage_user', {id:id}, function(data){
	    console.log("Data: " + data);
	    if(data.status == 1){
	    	$('#modal-add_user').empty().html(data.html);
	    	if($('#user_role_id').children('option:selected').text() == 'Producer'){
	    		$('.category_div').show()
	    	}
	    	$("#user_role_id").select2({		      
			    minimumResultsForSearch: Infinity,
			    allowClear: false,
		  	});
			$('#modal-add_user').modal('show');
	    }
	});
});




//OPEN ADD USER POPUP ad refresh modal
$(document).on('click', '#add_btn', function(){
	$('.add_update_user').empty().html('Add User');
	$('#first_name').val('');
	$('#last_name').val('');
	$('#user_email').val('');
	$('#id').val('');
	$('#user_phone').val('');
	$("#user_role_id").select2({		      
			    minimumResultsForSearch: Infinity,
			    allowClear: false,
		  	});
	$('#user_role_id').val(1).trigger('change');
	$('#category_div').hide();

	$('#modal-add_user').modal('show');
});

//delete_user
$(document).on('click', '.delete_user', function(){
	let id = $(this).attr('data-id');
	$('#delete_user_btn').attr('data-id', id);
	$('#modal-delete-user').modal('show');
});
/*confirm delete*/
$(document).on('click', '#delete_user_btn', function(){
	let id = $(this).attr('data-id');	
	
	$.ajax({
        url: site_url+'/manage_user',
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },  
        data: {id:id},
    })
    .done(function(data)
    {      
		if(data.status == 1){			
			show_success_msg(data.msg);
		}else{
			show_error_msg(msg_something_went_wrong);			
		}
		$('#modal-delete-user').modal('hide');
		setTimeout(function(){
			filterdata();
		}, timeout);
        
    })
    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        show_error_msg(msg_something_went_wrong);
    });
});



	$(document).on('click', "#up", function(){
		orderbyVal = '';
		if($('#up').hasClass('fa-arrow-up')){
			$('#up').removeClass('fa-arrow-up text-primary');
			$('#up').addClass('fa-arrow-down text-danger');		
		}else{
			$('#up').removeClass('fa-arrow-down text-danger');
			$('#up').addClass('fa-arrow-up text-primary');	
		}
		orderbyVal = $("#orderby").children("option:selected").val();
		filterdata(orderbyVal);
	});

	$(document).on('keyup', "#search_user", function(){
		//$(".order_status_change").hide();	
		//let resource = $("#segment_filter").data('resource');
		var whereVal = $(this).val();
		//localStorage.setItem("search_"+resource,whereVal);
		if(whereVal!=''){
			$(".search-overlay").show();
			$(".search-notice").show();
			$(".customers-filters-area").addClass('search-active');
			$(".customers-filters-area-1").addClass('search-active');
		}else{
			$(".search-notice").hide();
			$(".search-overlay").hide();
			$(".customers-filters-area").removeClass('search-active');
			$(".customers-filters-area-1").removeClass('search-active');
		}
		filterdata('',whereVal);
	});


	$(document).on('change', "#orderby", function(){
		var orderbyVal = $(this).val();
		filterdata(orderbyVal)
	});

	$(document).on('change', "#changelimit", function(){
		var limit = $(this).val();	
		$('html, body').animate({
	        scrollTop: $("#customer_export_grid").offset().top
	    }, "slow");
		filterdata('','', limit);
	});


	$(document).on('click', ".page-link", function(e){
		e.preventDefault();
		var href = $(this).attr('href');	
		let searchParams = new URLSearchParams(new URL(href).search);	
		let page = searchParams.get('page');
		//$("html, body").animate({ scrollTop: 0 }, "slow");

		$('html, body').animate({
	        scrollTop: $("#customer_export_grid").offset().top
	    }, "slow");

		filterdata('','', '','',page);

	});

	/*check and uncheck categories list at add and edit user*/
	$(function() {
	    $(document).on('click', '.select_text', function( ) {
	    	let self = $(this);
		
				
		    	if(self.hasClass("checked")){
		    		self.parent().next('li').find('input[type="checkbox"]').prop('checked', false);
		    		self.parent().next('li').find('.select_text').empty().text('Select All');
					self.empty().text('Select All');
		    		self.parent().next('li').find('.select_text').removeClass("checked");
					self.removeClass("checked");
				}
				else{
		    		self.parent().next('li').find('input[type="checkbox"]').prop('checked', true);
		    		self.parent().next('li').find('.select_text').empty().text('Unselect All');
					self.empty().text('Unselect All');
		    		self.parent().next('li').find('.select_text').addClass("checked");
					self.addClass("checked");
				}
	    });
	});

/*
	$(function() {
		$(document).on('click', '[id^=in-product_cat-]', function() {
			let child_check = $(this).closest('.select_all_li').attr('class');
			if(child_check.parent().next('li').find('input[type="checkbox"]:checked').length == 0){

				child_check.prop('checked', false);
				child_check.empty().text('Select All');
	    	}

	    });
	});
*/
	

	$(document).on('change', '#user_role_id', function(){
		if($('#user_role_id').children("option:selected").text() == 'Producer'){
			$('.category_div').show();
		}else{
			$('.category_div').hide();
		}
	});


	//change_password
	$(document).on('click', '.change_password', function(){
		var id = $(this).attr('data-id');
		$('#change_pass_user_id').val(id);
		$('#modal-chage_password').modal('show');
	});

	/*confirm delete*/

$(document).ready(function () {
	$(document).on('click', '#submit_cp_btn', function(){
		$('#change_password').validate({
			//errorClass: "is-invalid",
			errorElement: "span",
			rules:{
				'password':{
					required:true,
					minlength:7,
				},
				'con_password':{
					required:true,
					equalTo:'#password',
					minlength:7,
				},				

			},messages:{
				'con_password':{
					equalTo:msg_pass_and_con_pass_must_be_same,
				},
			},
			submitHandler:function(form, e){
				e.preventDefault();
				$('#change_password').addClass('customer-js-loading');
				$('#submit_cp_btn').addClass('is-loading');

				let id = $('#change_pass_user_id').val();		
				let password = $('#password').val();		
				$.ajax({
			        url: site_url+'/update_password',
			        type: "post",
			        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },  
			        data: {id:id, password:password},
			    
					success:function(data){
						$('#modal-chage_password').modal('hide');
						$('#change_pass_user_id').val('');
						if(data){
							if(data.status==1){
								show_success_msg(data.msg);
							}else{
								show_error_msg(data.msg);
							}
						}else{
							show_error_msg(data.msg);
						}
						$('#change_password')[0].reset();
						$('#change_password').removeClass('customer-js-loading');
						$('#submit_cp_btn').removeClass('is-loading');						
					},error:function(e){
						show_error_msg(e);
					}
				});
			}
		});
	})
});


/****************User module js ends*********************/
