( function( $ ) {
    // on load single page
    const params = new URLSearchParams(window.location.search);
    if($('#cpt-ajax-gallery').length && params.has("ajax") && params.get("ajax") != ""){
        let post_id = params.get("ajax");
        $.ajax({
            method: "GET",
            url: api.base_endpoint + 'portfolios/' + post_id,
            dataType: 'json',
            beforeSend: function() {
                $("body").addClass("blur-on-load");
            }
        })
        .done(function( data ) {
            if(data.item.length > 0){
                let item = data.item;
                if(item[0].gallery.length > 0){
                    let gallery = item[0].gallery;
                    let content = '';
                    for (let i = 0; i < gallery.length; i++) {
                        content += '<div class="portfolio-wrapper-item">';
                        content += '<img src="'+gallery[i].photo+'" alt="'+gallery[i].title+'">';
                        content += '</div>';
                    }
                    $("#cpt-ajax-gallery").html("");
                    $("#cpt-ajax-gallery").html(content);
                }

                if(item[0].team.length > 0){
                    let team = item[0].team;
                    let content = '';
                    for (let i = 0; i < team.length; i++) {
                        content +='<div class="portfolio-wrapper-item">';
                        content +='<img src="'+team[i].photo+'" alt="'+team[i].name+'">';
                        content +='<p>'+team[i].description+'</p>';
                        content +='<a href="'+team[i].social_link+'">Social link</a>';
                        content +='</div>';
                        
                    }
                    $("#cpt-ajax-team").html("");
                    $("#cpt-ajax-team").html(content);
                }
            }

            $("body").removeClass("blur-on-load");
        });
    }

    // on load page
    if($('#cpt-list-api').length){
        $.ajax({
            method: "GET",
            url: api.base_endpoint + 'portfolios/?cpage=0',
            dataType: 'json',
            beforeSend: function() {
                $("body").addClass("blur-on-load");
            }
        })
        .done(function( data ) {
            let content = '';
            if(data.items.length > 0 && data.total_pages > 1){
                let items = data.items;

                for (let i = 0; i < items.length; i++) {
                    content += '<div class="portfolio-wrapper-item">';
                    content += '<a href="'+items[i].permalink+'?ajax='+items[i].ID+'" title="'+items[i].title+'">';
                    content += '<img src="'+items[i].thumbnail+'" alt="'+items[i].title+'">';
					content += '<p>'+items[i].excerpt+'</p>';
                    content += '</a>';
                    content += '</div>';
                    
                }
                $("#cpt-list-api").html("");
                $("#cpt-list-api").html(content);
                $(".cpt-pagination").addClass('show');
                $(".cpt-current").attr('cpage',data.cpage).text(1);
                $(".cpt-total").text(data.total_pages);
            }

            if(data.items.length > 0 && data.total_pages == 1){
                let items = data.items;
                for (let i = 0; i < items.length; i++) {
                    content += '<div class="portfolio-wrapper-item">';
                    content += '<a href="'+items[i].permalink+'?ajax='+items[i].ID+'" title="'+items[i].title+'">';
                    content += '<img src="'+items[i].thumbnail+'" alt="'+items[i].title+'">';
					content += '<p>'+items[i].excerpt+'</p>';
                    content += '</a>';
                    content += '</div>';
                    
                }
            }
            $("body").removeClass("blur-on-load");
        });
    }

    $('body').on('click','.nav', function(){
        let cpage = $(".cpt-current").attr('cpage');
            cpage = parseInt(cpage);
        var cpage_label = parseInt($(".cpt-current").text());
        let id = $(this).data("id");
        if(id == "prev" && cpage_label > 1){
            --cpage;
            --cpage_label;
        }else{
            ++cpage;
            ++cpage_label;
        }

        $.ajax({
            method: "GET",
            url: api.base_endpoint + 'portfolios/?cpage='+cpage,
            dataType: 'json',
            beforeSend: function() {
                $("body").addClass("blur-on-load");
            }
        })
        .done(function( data ) {
            let content = '';
            if(data.items.length > 0 && data.total_pages > cpage_label){
                let items = data.items;
                for (let i = 0; i < items.length; i++) {
                    content += '<div class="portfolio-wrapper-item">';
                    content += '<a href="'+items[i].permalink+'?ajax='+items[i].ID+'" title="'+items[i].title+'">';
                    content += '<img src="'+items[i].thumbnail+'" alt="'+items[i].title+'">';
					content += '<p>'+items[i].excerpt+'</p>';
                    content += '</a>';
                    content += '</div>';
                    
                }
                $("#cpt-list-api").html("");
                $("#cpt-list-api").html(content);
                $(".cpt-current").attr('cpage',data.cpage).text(cpage_label);
                $(".cpt-total").text(data.total_pages);
                $(".cpt-prev").addClass("nav");
                if(cpage_label == 1){
                    $(".cpt-prev").removeClass("nav");
                    $(".cpt-next").addClass("nav");
                }
            }

            if(data.items.length > 0 && data.total_pages == cpage_label){
                let items = data.items;
                for (let i = 0; i < items.length; i++) {
                    content += '<div class="portfolio-wrapper-item">';
                    content += '<a href="'+items[i].permalink+'?ajax='+items[i].ID+'" title="'+items[i].title+'">';
                    content += '<img src="'+items[i].thumbnail+'" alt="'+items[i].title+'">';
					content += '<p>'+items[i].excerpt+'</p>';
                    content += '</a>';
                    content += '</div>';
                    
                }
                $("#cpt-list-api").html("");
                $("#cpt-list-api").html(content);
                $(".cpt-current").attr('cpage',data.cpage).text(cpage_label);
                $(".cpt-total").text(data.total_pages);
                $(".cpt-next").removeClass("nav");
                $(".cpt-prev").addClass("nav");

            }
            $("body").removeClass("blur-on-load");
        });
    });
}( jQuery ) );