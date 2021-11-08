function addButon(){
    let butons = $('.fav');
    if(butons.length > 0){
        butons.click(function(){
            addToFav(this);
        })
    }
}

function addToFav(buton){
    let id = $(buton).attr('data-id');
    ajaxAddToFav(id);
}

function ajaxAddToFav(id){

    $.ajax({
        url: '/addFavoris',
        type: 'POST',
        dataType: "json",
        data: {
            "id": id
        },
        success: function(response){
           if(response.hasOwnProperty('message')){
               alert(response.message);
           }            
        }
      });
}

$(document).ready(function(){
    addButon();
})