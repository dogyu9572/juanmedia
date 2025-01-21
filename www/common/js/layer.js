function abspos(e){  // 클릭 이벤트가 발생한 바로 그 위치
	try{
       this.x = e.clientX + (document.documentElement.scrollLeft?document.documentElement.scrollLeft:document.body.scrollLeft);
	   this.y = e.clientY + (document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop);
       return this;
	}catch(e){}
	try{
       this.x = event.screenX - window.screenLeft - document.body.leftMargin + document.body.scrollLeft  - event.offsetX;
       this.y = event.screenY - window.screenTop - document.body.topMargin + document.body.scrollTop  - event.offsetY;
       return this;
	}catch(e){}
   }

function layerPositionSet(div_id, e) {
    var d = document.getElementById(div_id);
	if(!e) e = window.Event;
    pos = abspos(e);
    d.style.left = pos.x+"px";
    d.style.top = (pos.y+10)+"px";
    d.style.display = d.style.display=='none'?'block':'none';

}
