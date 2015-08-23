
var wh=[];
jQuery(document).ready(function($){
    function vbean_changeroom(obj){
        console.log('changeroom');
        var thisimg = $(obj).find("img").first();
        if(thisimg!==null){
            $("#the-showroom-backgroundimg").attr('src',$(thisimg).attr('src'));
            console.log('room found: '+$(thisimg).attr('src') );
        }
    }

    function initializeShowroomPage(){
        var tf = $("#theframe"); // the frame
        var theparent = $(tf).parent(); // the room
        $("<img/>").attr("src", $("#thehanging").attr("src")).load(function() {
            // Note: $(this).width() will not
            wh = [this.width,this.height];
            var nw = parseInt($(theparent).width()*0.4); // the new width of the frame;
            var ratio = nw/wh[0]; // the reatio used to get the new width;
            var nh = parseInt(wh[1]*ratio);
            console.log(ratio);
            var theleft = ($(theparent).width()-nw)/2;
            $(tf).css({'position':'absolute','left':theleft,'top':40,'width':nw,'height':nh});
            $("#theframe").draggable();
            $("#theframe").resizable({
              aspectRatio: wh[0] / wh[1]
            });
        });    
    }


    function initializeShowroom(){
        var tf = $("#theframe"); // the frame
        var theparent = $(tf).parent(); // the room
        var theimage = $("#the-showroom-backgroundimg");
        
        var nw = parseInt($(theparent).width()*0.4); // the new width of the frame;
        var ratio = nw/wh[0]; // the reatio used to get the new width;
        var nh = parseInt(wh[1]*ratio);
        console.log(ratio);
        var theleft = ($(theparent).width()-nw)/2;
        $(tf).css({'position':'absolute','left':theleft,'top':40,'width':nw,'height':nh});
    }





    initializeShowroomPage();


    $("#hangerlink").on('click touchend',function(e){
        setTimeout(function() {
            initializeShowroom();
        }, 500);
    });

    
    
        function showMyRoom(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $("#the-showroom-backgroundimg").attr('src',e.target.result);
                };
            
            reader.readAsDataURL(input.files[0]);
            }
        }
    
    $("#myroom").change(function(){
        showMyRoom(this);
    });
    
    
    $("div.showroom-roomchoice").on('click touchend', function(e){
            vbean_changeroom(this);
    });



});


