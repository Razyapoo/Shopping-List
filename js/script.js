$(document).ready(function(){
    // Delete 
    $('.red').click(function(){
        if (this.innerHTML == 'Delete'){

            var tag = this, tr="",
                id = this.id, dMov="",
                del = id.replace("del_id",""),
                ret = document.body.getElementsByClassName('block3');

            // AJAX Request
           $.ajax({
                url: 'php/delete.php',
                type: 'POST',
                data: { del_id:del },
                success: function(response){
                    if(response == 1){
                        $(ret).closest('div').remove();
                        $(tag).closest('tr').remove();
                        tr = document.getElementsByTagName('tbody')[0];
                        dMov = tr.children[0].getElementsByClassName('blue');
                        if (dMov){ 
                                dMov[0].remove();
                        }
                    }else{
                     alert('Invalid Item.'); }
                }
                       
            });
        }
    });

    $('.blue').click(function(){
        // Move
        var 
            id = this.id, 
            position = document.getElementById('e'+(id)).min,
            num = $(this).closest('tr')[0];
            rows = num.rowIndex-2;
           
            // AJAX Request
            $.ajax({
                url: 'php/move.php',
                type: 'POST',
                data: { 
                    id          : id,
                    position    : position,
                    rows        : rows
                },
                success: function(){
                    location.reload(false);
                }
            });
        
    });
   
});