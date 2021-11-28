$(function(){
    $.post("/json/code.json",function(da){
        code_arr = da;
    })

    $.post("/json/product.json",function(da2){
        p_arr = da2;
        $(".cir > div").each(function(ind,ele){
             txt = p_arr[ind].split(" ").join("<br>");
            $(this).html(txt);
        })
    })

    stamp_img = new Image();
    stamp_img.src = "/img/stamp.png";

    red_img = new Image();
    red_img.src = "/img/red.png";

    green_img = new Image();
    green_img.src = "/img/green.png";

    st_ok = false;
    st_max = 8;
    st_count = 0;

    st_start = 20;
    st_gap = 103;
    st_top = [77,173];
    st_size = 83;
    minus = 0;

    spot_start = 160;
    spot_gap = 15;
    spot_y = 268;
    spot_size = 6;
    rol_count = 0;

    $(document).on("click", '#st_check',function(){ ch_code();})
    $(document).on("click", '#in_stamp',function(e){ cl_stamp(e,"stamp"); })
    $(document).on("click", '#in_rol',function(e){ cl_stamp(e,"rol"); })
});

function g_file(){
    return new Promise(async tmp => {
        const [fileHandle] = await window.showOpenFilePicker();
        const file_data = await fileHandle.getFile();
        const img_data = await file_read(file_data);

        tmp({img_data, fileHandle});
    })
}

function mk_img(img_data){
    return new Promise(async tmp => {
        img = new Image();
        img.src = img_data;
        img.onload = function(){
            tmp(img);
        }
    })
}

function file_read(file_data){
    return new Promise(async tmp => {
        reader = new FileReader();
        reader.readAsDataURL(file_data);
        reader.onload = () => {
            tmp(reader.result);
        }   
    })
}

async function cl_stamp(e,kind){
    e.preventDefault();
    tmp_st_count = st_count;
    tmp_rol_count = rol_count;

    if(kind == "stamp"){ tmp_st_count++;}
    if(kind == "rol"){ 
        tmp_rol_count++;
        if(rol_count == st_count){ alert("이벤트에 참여하실 수 없습니다."); return; }
    }

    $("#sel_modal").modal("hide");
    const {img_data, fileHandle} = await g_file();
    img = await mk_img(img_data);
    const {can, ctx} = mk_canvas(img);

    for(i=1; i<=tmp_st_count; i++){
        
        if(i>4){minus = st_gap*4;}
        st_x = (i-1) * st_gap + st_start - minus;

        st_y = i > 4 ? st_top[1] : st_top[0];
        ctx.drawImage(stamp_img,st_x,st_y,st_size,st_size);

        spot_x = (i-1) * spot_gap + spot_start;
        ctx.drawImage(green_img,spot_x,spot_y,spot_size,spot_size);

        if(tmp_rol_count >= i){
            ctx.drawImage(red_img,spot_x,spot_y,spot_size,spot_size);
        }
    }   

    can.toBlob(async blob => {
        const sel_file = await fileHandle.createWritable();
        sel_file.write(blob);
        sel_file.close();

        st_count = tmp_st_count;
        rol_count = tmp_rol_count;
        if(kind == "rol"){ st_rol();}
    })
}

function ch_code(){
    if(!st_ok){ alert("카드가 발급되지 않았습니다."); return;}
    val = $("#st_code").val();
    
    if($.inArray(val,code_arr) == -1){
        alert("일치하는 코드가 없습니다.");
    }else{
        if(st_max == st_count){ alert("스탬프를 모두 찍었습니다."); return;}
        $("#sel_modal").modal("show");
        $("#in_stamp").val("");
    }
}

function card_down() {
    name = $("#c_name").val();
    if(!name){alert("이름을 등록해 주세요"); return;}
    img = new Image();
    img.src = "/img/card.png";
    img.onload = function(){
        const {can,ctx} = mk_canvas(img);
        ctx.font = '8px arial';
        ctx.fillStyle = '#fff';
        ctx.fillText(name, 365, 20);
        data = can.toDataURL();

        $(`<a download="stamp_card.png" href="${data}"></a>`)[0].click();
        $("#card_modal").modal("hide");
        st_ok = true;
    }
}

function mk_canvas(img){
    can = $("<canvas>")[0];
    ctx = can.getContext('2d');
    can.width = img.width;
    can.height = img.height;
    ctx.drawImage(img, 0, 0);
    return {can,ctx};
}

function st_rol() {
    idx = Math.floor(Math.random() * 10);
    mk_deg = 36 * idx;
    total_deg = 1800 + mk_deg;
    ani = [
        {transform: `rotate(0deg)`},
        {transform: `rotate(${total_deg}deg)`},
    ];
    opt = {
        fill: 'forwards',
        duration: 6000,
        easing: 'cubic-bezier(.6,0,0,1)',
    };
    $('.cir')[0].animate(ani, opt);
    setTimeout(function(){
        idx = 10 - idx;
        if(idx == 10){ idx = 0;}
        msg = `축하합니다. ${p_arr[idx]}에 당첨되었습니다.`;
        alert(msg);
    }, 6000);
}