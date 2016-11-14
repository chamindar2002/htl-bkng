$(window).on('load', function () {

    // Page Preloader

    $('#preloader').delay(1500).fadeOut('slow', function () {

        $(this).remove();

    });
});


$('#select2-item-search').keyup(function(e){
   
    if(e.keyCode == 13)
    {
       DynamicItemsTable.init(
                    $('#item_list_table'),
                    $('#temp_item_id'),
                    $('#temp_item_name'),
                    $('#temp_item_price'),
                    $('#temp_row_count'),
                    $('#temp_booking_id')
                ); 
       
       if(DynamicItemsTable.appendRow()){
          DynamicItemsTable.resetAll($(this));
       }
    }
});

$('#select2-room-search').keyup(function(e){
   
    if(e.keyCode == 13)
    {
       $('#select2-item-search').focus();
    }
});

$('#btn_place_order').click(function(e){
   DynamicItemsTable.placeOrder(); 
});

$('.order-item-view').click(function(e){
    e.preventDefault();
    var order_item_id = $(this).attr('attribute-id');
    DynamicItemsTable.fetchOrderItem(order_item_id);
   
    
    
})

var table, id, name, price, _counter=1, _rows, _booking;
var DynamicItemsTable = {
  
  init:function(tbl, item_id, item_name, item_price, row_count,booking_id){
      table = tbl;
      _id = item_id;
      _name = item_name;
      _price = item_price;
      _rows = row_count;
      _booking = booking_id;
      
  },
  appendRow: function () {
    
    if(_id.val() !== '' && _booking.val() !== ''){

        var _htm = '<tr>'
                   +'<td>'+_id.val()+'</td><td>'
                   +_name.val()+'</td><td>'
                   +_price.val()+'</td>'
                   +'<td>'
                   +'<input type="number" min="1" max="1000" name=row_qty_'+_counter+' class="form-control  row-quantity" item-id="'+_id.val()+'" value="1">'
                   +'<input type="hidden" name=row_item_id_'+_counter+' class="form-control" value="'+_id.val()+'">'
                   +'</td>'
                   +'<td><input type="button" class="btn btn-warning" value="Remove" onClick="$(this).closest(\'tr\').remove();"></td>';
                   +'</tr>';
                   
        _rows.val(_counter);  
           
        table.append(_htm);
        
        _counter++;

        return true;
    }else{
        DynamicItemsTable.runBookingValidations();
    }
        
    return false;
  },
  resetAll: function(_text_search)
  {
      _id.val('');
      _name.val('');
      _price.val('');
      _text_search.val('');
      _text_search.focus();
  },
  runBookingValidations: function()
  {
      if(_booking.val() === ''){
          $('.grc-booking-room-id').addClass('has-error');
          $('#w1').focus();
      }else{
          if($('.grc-booking-room-id').hasClass('has-error')){
              $('.grc-booking-room-id').removeClass('has-error');
          }
      }
      if(_id.val() === ''){
          $('.grc-item-master-id').addClass('has-error');
           $('#select2-item-search').focus();
      }else{
          if($('.grc-item-master-id').hasClass('has-error')){
              $('.grc-item-master-id').removeClass('has-error');
          }
      }
  
      
  },
  placeOrder: function()
  {   
      var itemsArray = []; 
      $('.row-quantity').each(function(i, obj) {
        var item = {id:$(this).attr('item-id'),quantity:$(this).val()};
        itemsArray.push(item);
      });
      
      if(itemsArray.length == 0)
      {
          alert('array empty');
      
      }else{
          
        var r = confirm("Confirm!");
        
        if (r == true) {
            var booking_id = $('#temp_booking_id').val();  
            var rows = JSON.stringify(itemsArray);
            $.ajax({
                url: "place-order",
                type: "POST",
                data: {booking_id:booking_id, item_rows:rows}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                     if(response.result == 'success'){
                        DynamicItemsTable.resetAll($('#select2-item-search'));
                        $('#tbody_item_list_table').empty();
                        $('#select2-room-search').val('');
                        $('#temp_guest').val('');
                        $.pjax.reload({container:'#grid-demo-invn-items'});

                     }   
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
          }
     }

        
  },
  fetchOrderItem: function(id){
       //$('#order-items-view-placeholder').html('xxxx : '+id);
       
        $.ajax({
                url: "fetch-order",
                type: "POST",
                data: {ivoice_item_id:id}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                     if(response.result == 'success'){
                        var _htm = '<tr>'
                                   +'<td>'+response.data.item_description+'</td>' 
                                   +'<td>'+response.data.price+'</td>' 
                                   +'<td>'+response.data.order_quantity+'</td>' 
                                   +'<td>'+response.data.sub_total+'</td>' 
                                   +'<td>'
                                   if(response.data.status == 'OPEN'){
                                        _htm += '<input type="button" value="Cancel" class="btn btn-danger" onClick="DynamicItemsTable.cancelOrderItem('+response.data.id+')"></td>'; 
                                   }else{
                                        _htm += response.data.status;
                                   }
                                   _htm += '</td>';
                                   _htm += '</tr>'; 
                        $('#order-items-view-table-tbody').empty();   
                        $('#order-items-view-table').append(_htm);
                        
                        $('#modal-item-view').modal('show');
                     }   
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
  },
  cancelOrderItem: function(id){
      var r = confirm("Confirm!");
        
        if (r == true) {
             $.ajax({
                url: "cancel-order-item",
                type: "POST",
                data: {ivoice_item_id:id}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                     if(response.result == 'success'){
                         $.pjax.reload({container:'#grid-demo-invn-items'});
                         $('#modal-item-view').modal('hide');
                     }   
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
        }
  }
  
  
  
  
};

$('#reloadme').click(function(){
  $.pjax.reload({container:'#grid-demo'});
});
