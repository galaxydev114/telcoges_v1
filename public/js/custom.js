$(window).on("load",function() {
    $('.preloader img').fadeOut();
    $('.preloader').fadeOut(1000);
});

$(document).ready(function() {
    var wrapper = $(".add-items");
    var add_button = $(".add-item-to-invoice");

    $('.invoice-item-input').off('input', calculateTotalRow);
    $('.invoice-item-input').on('input', calculateTotalRow);
    
    var x = 1;
    $(add_button).on('click', function(e) {
        e.preventDefault();
        x++;
        $(wrapper).append(`<div class="form-row">
                            <div class="col">
                                <input id="name`+x+`" ind="`+x+`" class="form-control item-name-autocomplete" type="text" name="itemname[]" placeholder="Nombre" autocomplete="off"/>
                                <div id="suggesstion-box`+x+`">
                                </div>
                            </div>
                            <div class="col">
                                <input id="description`+x+`" class="form-control" type="text" name="itemdescription[]" placeholder="Descripción" autocomplete="off"/>
                            </div>
                            <div class="col">
                                <input id="qty`+x+`" class="form-control invoice-item-input" line="`+x+`" type="number" min="1" step="1" name="itemqty[]" placeholder="Cantidad" autocomplete="off"/>
                            </div>
                            <div class="col">
                                <input id="price`+x+`" class="form-control invoice-item-input" line="`+x+`" type="number" step="0.01" name="itemprice[]" placeholder="Precio" autocomplete="off"/>
                            </div>
                            <div class="col">
                                <input id="taxrate`+x+`" class="form-control invoice-item-input" line="`+x+`" type="number" step="0.01" name="taxrate[]" value="21"/>
                            </div>
                            <div class="col">
                                %
                            </div>
                            <div class="col">
                                <input class="form-control total" id="total`+x+`" type="text" placeholder="total" readonly/>
                            </div>
                            <input id="subtotal`+x+`" type="hidden">
                            <input id="iva`+x+`" type="hidden">
                            <a href="#" class="delete">
                                <i class="fa fa-trash"></i>
                            </a>
                            </div>`); //add input box
        $('.invoice-item-input').off('input', calculateTotalRow);
        $('.invoice-item-input').on('input', calculateTotalRow);
    });

    $(wrapper).on("click", ".delete", function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
        calculateInvoiceTotals();
        //x--;
    });

    $('.alert-success').fadeIn().delay(5000).fadeOut();
    $('.alert-danger').fadeIn().delay(5000).fadeOut();

    // $('.change-invoice-select').on('change', function (e) {
        // $('#invoice-status').submit();
    // });

    // Client autocomplete code in create invoice view
    $('input.typeahead').keyup(function(){
		var query = $(this).val();
        if(query != '') {
            var _token = $('input[name="_token"]').val();
            $.ajax({
            url: $('#url').val(),
            method:"POST",
            data:{query:query, _token:_token},
            success:function(data) {
                $('#clientList').html(data);
                $('#clientList').fadeIn();
            }
            });
        }
    });

    $(document).on('click','.list-client',function(){
        str = $(this).text();
        $('#client').val(str.split('-')[0]);
		$('#clientnif').val(str.split('-')[1]);
        $('#clientList').fadeOut();
    });  
    
    // Provider autocomplete code in create product view
    $(document).on('keyup', '.provider-autocomplete', function(){
        var query = $(this).val();
        var row = $(this).attr('whorow');
        if(query.length > 1) {
            var _token = $('input[name="_token"]').val();
            $.ajax({
            url: $('#url').val(),
            method:"POST",
            data:{query:query, _token:_token},
            success:function(data) {
                $('#providerList'+row).html(data);
                $('#providerList'+row).fadeIn();
            }
            });
        } else {
            $('#providerList'+row).fadeOut();
        }
    });

    $(document).on('click','.provider-client',function(){
        var row = $($($(this).parent()).parent()[0]).attr('whorow');
        console.log( row );
        str = $(this).text();
        $('#provider'+row).val();
        $('#providerList'+row).fadeOut();
    });  

    function calculateTotalRow(e){
        var row = $(this).attr('line');
        if ( $('#qty'+row).val() === '') { var qty = 0; } else { var qty = $('#qty'+row).val(); }
        if ( $('#price'+row).val() === '') { var price = 0; } else { var price = $('#price'+row).val(); }
        if ( $('#taxrate'+row).val() === '') { var taxrate = 0; } else { var taxrate = $('#taxrate'+row).val(); }
        var total = qty * price; 
        $('#subtotal'+row).val(total.toFixed(2));
        $('#iva'+row).val((total * (taxrate/100)).toFixed(2));
        var totalWIva = total + (total * (taxrate/100));
        $('#total'+row).val(totalWIva.toFixed(2));

        calculateInvoiceTotals();
    }

    function calculateInvoiceTotals() {
        var subtotal = 0;
        var iva = 0;
        var total = 0;
        for (var i = 0; i <= x; i++) {
            if ($('#subtotal'+i).length) {
            subtotal = parseFloat(subtotal) + parseFloat($('#subtotal'+i).val());
            iva = parseFloat(iva) + parseFloat($('#iva'+i).val());
            total = parseFloat(total) + parseFloat($('#total'+i).val());
            }
        }
        $('#grandSubTotal').val(subtotal.toFixed(2));
        $('#grandIva').val(iva.toFixed(2));
        $('#grandTotal').val(total.toFixed(2));
    }

    $('.edit-organization').on('click', function() {
        if ( $('.text-edit-organization').hasClass('cancel-edit-organization') ) {
            $('.text-edit-organization').removeClass('cancel-edit-organization');
            $('.text-edit-organization').removeClass('cancel-edit-organization');
            $('.text-edit-organization').text('Editar');
            $('#clicked').val(0);
            $('#organization-edit :input').prop('readonly', true);
            $('.non-clicked-update').hide();
        } else {
            $('.text-edit-organization').addClass('cancel-edit-organization');
            $('.text-edit-organization').text('Cancelar');
            $('#clicked').val(1);
            $('.non-clicked-update').show();
            $('#organization-edit :input').prop('readonly', false);
            $('#country').prop('readonly', true);
        }
        //$('.non-clicked-update');
    });

    $('.check-organization-status').on('change', function(e){
        if ( $(this).prop('checked') == true) {
            var status = 'active';
        } else if ( $(this).prop('checked') == false) {
            var status = 'inactive';
        }

        $.ajax({
            url: $(this).attr('url')+'/'+status,
            method: 'PUT',
            headers: {
                      'X-CSRF-TOKEN': $('#token').val(),
                    },
            data: {'status':status},
            success: function(response) {
                console.log("listo");
            },
            beforeSend: function(){
                $('.preloader').show();
                $('.preloader').css('opacity','0.5');
                $('.preloader > div > img').show();
            },
            complete: function() {
                $('.preloader').hide();
            }
        });
    });

    $('.check-membership-status').on('change', function(e){
        $.ajax({
            url: $(this).attr('url'),
            method: 'PUT',
            headers: {
                      'X-CSRF-TOKEN': $('#token').val(),
                    },
            success: function(response) {
                location.reload();
            },
            beforeSend: function(){
                $('.preloader').show();
                $('.preloader').css('opacity','0.5');
                $('.preloader > div > img').show();
            },
            complete: function() {
                //$('.preloader').hide();
            }
        });
    });

    $('.update-serie-status').on('change', function(e){
        $.ajax({
            url: $(this).attr('url'),
            method: 'PUT',
            headers: {
                      'X-CSRF-TOKEN': $('#token').val(),
                    },
            success: function(response) {
                location.reload();
            },
            beforeSend: function(){
                $('.preloader').show();
                $('.preloader').css('opacity','0.5');
                $('.preloader > div > img').show();
            },
            complete: function() {
                //$('.preloader').hide();
            }
        });
    });
	// CHANGE STATUS OF NEW INVOICE
	$('.update-status').on('change', function(e){
        if($('.update-status').prop("checked")){
			$('#status').val('payed');
			$('.status-pending').css({"color": "gray" , "font-weight" : "normal" });
			$('.status-payed').css({"color" : "#8cbc13" , "font-weight" : "bold" });
		}else{
			$('#status').val('pending');
			$('.status-payed').css({"color": "gray" , "font-weight" : "normal" });
			$('.status-pending').css({"color": "red" , "font-weight" : "bold" });
		}
    });
	//
	
	// UPDATE STATUS OF INVOICE
	$('.change-invoice-select').on('change', function (e) {
		if( $('#status').length > 0 ){
			if(	$('.change-invoice-select').prop("checked") ){
				$('#status').val('payed');
				$('.status-pending').css({"color": "gray" , "font-weight" : "normal" });
				$('.status-payed').css({"color" : "#8cbc13" , "font-weight" : "bold" });
			}else {
				$('#status').val('pending');
				$('.status-payed').css({"color": "gray" , "font-weight" : "normal" });
				$('.status-pending').css({"color": "red" , "font-weight" : "bold" });
			}
		}
        $('#invoice-status').submit();
		
    });
	//

    $(document).on('focusout', '.item-name-autocomplete', function(e){
        var ind = $(this).attr('ind');
        $( '#suggesstion-box' + ind ).hide();
    });

    $(document).on('keyup', '.item-name-autocomplete', function(e){
        var ind = $(this).attr('ind');
        if ( $(this).val().length > 1 ) {
            $.ajax({
                url: $('#ajaxurl').val() + '&str=' + $(this).val() + '&ind=' + ind,
                method: 'GET',
                success: function(response) {
                    $( '#suggesstion-box' + ind ).html(response);
                    $( '#suggesstion-box' + ind ).show();
                },
                beforeSend: function(){
                },
                complete: function() {
                }
            });
        } else {
            $( '#suggesstion-box' + ind ).hide();
        }
    });

    $(document).on('mousedown', '.pl', function(e){
        e.preventDefault();
        var ind = $(this).attr('ind');
        var price = $(this).attr('price');
        var name = $(this).attr('name');
        var description = $(this).attr('description');
        $('#price'+ind).val(parseFloat(price));
        $('#description'+ind).val(description);
        $('#name'+ind).val(name);
        $('#qty'+ind).val(parseInt(1));
        $( '#suggesstion-box' + ind ).hide();
        calculateTotalRowByIndex(ind);
    });

    function calculateTotalRowByIndex(index){
        var row = index;
        if ( $('#qty'+row).val() === '') { var qty = 0; } else { var qty = $('#qty'+row).val(); }
        if ( $('#price'+row).val() === '') { var price = 0; } else { var price = $('#price'+row).val(); }
        if ( $('#taxrate'+row).val() === '') { var taxrate = 0; } else { var taxrate = $('#taxrate'+row).val(); }
        var total = qty * price; 
        $('#subtotal'+row).val(total.toFixed(2));
        $('#iva'+row).val((total * (taxrate/100)).toFixed(2));
        var totalWIva = total + (total * (taxrate/100));
        $('#total'+row).val(totalWIva.toFixed(2));

        calculateInvoiceTotals();
    }

    var y = 1;
    $('.add-product-item').on('click', function(e) {
        e.preventDefault();
        y++;
        $('.product-items')
            .append(
                `
                <div class="form-row">
                    <div class="col bmd-form-group mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="fa fa-code"></i>
                          </span>
                        </div>
                        <input oninput="this.value = this.value.toUpperCase()" type="text" name="serie[]" id="serie`+ y +`" class="form-control" placeholder="'Serie...">
                      </div>
                    </div>
                    <div class="col bmd-form-group mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="fa fa-crosshairs"></i>
                          </span>
                        </div>
                        <input type="text" name="provider[]" whorow="`+ y +`" id="provider`+ y +`" class="form-control provider-autocomplete" placeholder="Proveedor..." autocomplete="off">
                      </div>

                      <div id="providerList`+ y +`" whorow="`+ y +`">
                      </div>
                    </div>
                    
                    <a href="#" class="delete-product-item">
                        <i class="fa fa-trash" style="color:#fb8c00;"></i>
                    </a>
                </div>
                `); //add input row
    });

    $('.product-items').on('click', '.delete-product-item', function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
    });
	
    // PUT YOUR CUSTOM CODE HERE
});