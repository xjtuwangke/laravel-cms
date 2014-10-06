/**
 * Created by kevin on 14-8-15.
 */

var artDialog = {};
artDialog.confirm = function( options  ){
    var defaultOptions = {
            title : '请确认' ,
            content : '确认要执行此操作吗',
            yes : '确定',
            no  : '取消',
            confirmed  : function(){} ,
            canceled : function(){}
        },
        options = $.extend( defaultOptions , options||{}),
        d = dialog({
            title: options.title,
            content: options.content,
            okValue: options.yes,
            ok: options.confirmed ,
            cancelValue: options.no,
            cancel: options.canceled
        });
    d.show();
}
;/**
 * Created by kevin on 14-8-6.
 */
$(function(){
    $("select[form-role='multi-select']").multiselect({
        selectedList : 6
    }).multiselectfilter();
    $("select[form-role='single-select']").multiselect({
        multiple : false ,
        selectedList : 1
    }).multiselectfilter();
});;/**
 * Created by kevin on 14-8-16.
 */

;(function($)
{
    $.extend(
        {
            getUrl: function(){
                var aQuery = window.location.href.split("?"),
                    aGET = {},
                    result = {
                        url : '',
                        get : {}
                    };
                if(aQuery.length > 1){
                    var aBuf = aQuery[1].split("&"),
                        iLoop = aBuf.length;
                    for( var i=0 ; i<iLoop ; i++){
                        var aTmp = aBuf[i].split("=");//分离key与Value
                        if( ! aTmp[0] ){
                            continue;
                        }
                        aGET[aTmp[0]] = aTmp[1];
                    }
                    result.url = aQuery[0];
                    result.get = aGET;
                }
                else{
                    result.url = window.location.href;
                }
                return result;
            } ,
            createUrl: function( options ){
                var result = options.url,
                    first = true;
                $.each( options.get , function( i , j ){
                    if( first ){
                        first = false;
                        result = result + "?" + i + "=" + j;
                    }
                    else{
                        result = result + "&" + i + "=" + j;
                    }

                });
                return result;
            }
        });
})(jQuery);
;/**
 * Created by kevin on 14-8-10.
 */

/*
 * for admin tables
 * 排序功能
 * 查找功能
 */

$( function(){

    $("table[data-role='table'] div.table-sort-btn").each( function( index , one ){
        var $one = $(one),
            field = $one.attr('attr-field'),
            $asc = $one.find('button[attr-btn-sort="asc"]'),
            $desc = $one.find('button[attr-btn-sort="desc"]');
        $asc.click( function(){
            cms_table_query( {
                sort : field ,
                order : 'asc'
            });
        });
        $desc.click( function(){
            cms_table_query( {
                sort : field ,
                order : 'desc'
            });
        });
    });

    $("table[data-role='table'] .input-table-search").each( function( index , one ){
        var $one= $(one),
            field = $one.attr('attr-field'),
            $parent = $one.parents("div.input-group"),
            $search = $parent.find("div.table-search-btn"),
            $reset  = $parent.find("div.table-reset-search-btn");
        console.log( $search );
        $search.click( function(){
            var pattern = $(this).parents("div.input-group").find(".input-table-search").val();
            cms_table_query( {
                search : pattern,
                field  : field
            });
        });
        $reset.click( function(){
            cms_table_query( {
                search : null,
                field  : null
            });
        });
    });

    $("table[data-role='table'] .btn-table-groupby").each( function( index , one ){
        var $one = $(one),
            field = $one.attr('attr-field'),
            value = $one.attr('attr-groupby');
        $one.click( function(){
            cms_table_query({
                groupby     : field ,
                group_value : value
            });
        });
    });

    $("table[data-role='table'] .table-role-btn-switch").each( function( index , one ){
        var $one = $(one),
            token = $one.attr( 'data-attr-token' ),
            url   = $one.attr('data-attr-url'),
            id   = $one.attr('data-attr-id'),
            status = $one.html();
        $one.click( function(){
            artDialog.confirm({
                content : '确定要修改状态为' + status + '吗',
                confirmed  : function(){
                    $.ajax({
                        url : url,
                        type : 'POST',
                        data :{
                            'status' : status ,
                            'id' : id ,
                            '_token' : token
                        },
                        success : function( content ){
                            console.log( content );
                            $one.parents('.btn-group').find('.btn-current-status').html( status );
                        }
                    })
                }
            });
        });
    });

})

function cms_table_query( options ){
    /*
     var url = $.getUrl();
     $.each( options , function( i , j ){
     url.get[i] = j;
     });
     $.each( url.get , function( i , j ){
     var $input = $("<input>").attr( "name" , i ).attr( 'value' , j );
     $("#_table-queries").append( $input );
     });
     */
    $.each( options , function( index , element ){
        var selector = '#_table-queries input[name="' + index + '"]';
        $input = $( selector );
        if( $input.length ){
            $input.val( element );
        }
    });

    $("#_table-queries").submit();
}

function remove_parent_tr( one ){
    $(one).parents('tr').remove();
}
;/**
 * Created by kevin on 14-8-21.
 */

$(function(){

    //tags中移除tag条

    $('a[form-role="tag-remove"]').click(function(){
        $(this).parents('span.label').remove();
    });

    $('.btn[form-role="add-tag"]').click(function(){
        var tag = $.trim( $(this).parents('.input-group').find("input").val()),
            field = $.trim( $(this).attr('form-attr-field')),
            new_span = '<span class="label label-info" style="display:inline-block;margin-right:5px;">' +
                tag + '<a href="javascript:;" form-role="tag-remove" style="margin-left:10px;">x</a>' +
                '<input style="display:none;" name="' + field + '" value="' + tag + '">' +
                '</span>';
        $(this).parents('.form-group').find('.tags-span').append( new_span );
        $('a[form-role="tag-remove"]').unbind( 'click' );
        $('a[form-role="tag-remove"]').click(function(){
            $(this).parents('span.label').remove();
        });
    });

    $("input[data-form-date-role='date']").each( function( index , one ){
        $(one).datepicker();
    });

    $("input[data-form-date-role='daterange']").each( function( index , one ){
        $(one).daterangepicker({
            format: 'YYYY-MM-DD hh:mm:ss',
            timePicker : true ,
            separator : '到'
        });
    });

    //appendable formfiled
    $('.btn[form-role="appendable"]').click(function(){
        var $this = $(this),
            url   = $this.attr('data-attr-append-url'),
            $target = $this.parents('.form-group').find('.appendable-inputs');
        $.ajax({
            url : url ,
            success : function( data ){
                $target.append( data );
            }
        });
    });

    //表格批量操作
    $('a[data-role="table-selecte-all"]').click(function(){
            $('input[data-role="form-selection"]').iCheck( 'check' );
        }
    );

    $('a[data-role="table-selecte-none"]').click(function(){
            $('input[data-role="form-selection"]').iCheck( 'uncheck' );
        }
    );

    $('a[data-role="table-group-action"]').click(function(){
        var $this = $(this),
            token = $this.attr( 'data-attr-token'),
            url   = $this.attr( 'data-attr-url'),
            $form = $("<form>"),
            $token = $( "<input>" ).attr( "name" , '_token' ).val( token );
        $form.attr('method' , 'POST');
        $form.attr( 'action' , url );
        $form.attr( 'target' , '_blank' );
        $form.css('display','none');
        $form.append( $token );
        $('input[data-role="form-selection"]').each( function( index , one ){
            var $one = $(one),
                checked = $one.is(':checked'),
                id = $one.attr( 'data-attr-id' );
            if( checked ){
                $form.append( $( "<input>" ).attr( "name" , 'id[]' ).val( id ) );
            }
        })
         console.log( $form );
         $('body').append( $form );
         $form.submit();
    });

})

function appendable_form_remove_btn( btn ){
    var $btn = $(btn);
    $btn.parents('.appendable').remove();
}
;/**
 * Created by kevin on 14-8-21.
 */

$( function(){

    //$('textarea[form-data-role="wysiwyg"]').wysihtml5();
    $('textarea[form-data-role="wysiwyg"]').each( function( index , one ){
        var name = $(one).attr( 'name' );
        CKEDITOR.replace( name );
    });

    //editorInstance.composer.commands.exec("insertImage", { src: "http://url.com/foo.jpg", alt: "this is an image" });

    $('input[form-role="image-upload-richtext"]').each( function( index , one ){
        var $one = $(one),
            id   = $one.attr( 'id'),
            $target = $one.parents('div.form-group').find('.uploadify-result'),
            options = {
                'formData': {
                    '_token': $one.parents('form').find("input[name='_token']").val(),
                    'type': 'default'
                },
                'swf'      : $one.attr('uploadify-swf-path'),
                'uploader' : $one.attr('uploadify-upload-url'),
                'buttonImage' : 'http://labor.qiniudn.com/lib/uploadify/browse-btn.png',
                'cancleImage' : 'http://labor.qiniudn.com/lib/uploadify/uploadify-cancel.png',
                'buttonClass': 'uploadify',
                'onUploadSuccess': function( file , data , response ){
                    console.log('data is:');
                    console.log(data);
                    eval('data=' + data);
                    $target.val( data.file_url );
                }
            };
        $( '#' + id ).uploadify( options );
    });

});
;/**
 * Created by kevin on 14-8-6.
 */
$(function(){

    /**
     * 单张图片uploadify上传
     */
    $("div[form-role='image-upload']").each( function( index , div ){
        var $div  = $(div);
        var options = {
            'formData'     : {
                '_token' : $div.parents('form').find("input[name='_token']").val(),
                'type'   : $div.attr('uploadify-image-type')
            },
            'swf'      : $div.attr('uploadify-swf-path'),
            'uploader' : $div.attr('uploadify-upload-url'),
            'buttonImage' : 'http://labor.qiniudn.com/lib/uploadify/browse-btn.png',
            'cancleImage' : 'http://labor.qiniudn.com/lib/uploadify/uploadify-cancel.png',
            'buttonClass': 'uploadify',
            'onUploadSuccess': function( file , data , response ){
                console.log('data is:');
                console.log(data);
                eval('data=' + data);
                var $img = $div.find('img.uploadify-image');
                var $input = $div.find('input.uploadify-input');
                if(data.file_url !== undefined){
                    $img.attr( 'src' , data.file_url );
                    $input.attr( 'value' , data.file_url);
                }
            }
        };
        var id = $div.find("input[type='file']").attr('id'); //必须用id初始化uploadify
        $( '#' + id ).uploadify( options );
    });

    /**
     * 多张图片uploadify上传
     */
    $("div[form-role='multi-image-upload']").each( function( index , div ) {
        var $div = $(div);
        var $container = $div.find( 'div.uploadify-multi-image-container' );
        var name = $div.attr('uploadify-field-name');
        var options = {
            'formData'     : {
                '_token' : $div.parents('form').find("input[name='_token']").val(),
                'type'   : $div.attr('uploadify-image-type')
            },
            'swf'      : $div.attr('uploadify-swf-path'),
            'uploader' : $div.attr('uploadify-upload-url'),
            'buttonImage' : 'http://labor.qiniudn.com/lib/uploadify/browse-btn.png',
            'cancleImage' : 'http://labor.qiniudn.com/lib/uploadify/uploadify-cancel.png',
            'buttonClass': 'uploadify',
            'onUploadSuccess': function( file , data , response ){
                console.log('data is:');
                console.log(data);
                eval('data=' + data);
                if(data.file_url !== undefined){
                    var html = '<div class="uploadify-image">\
<img src="{$image}" class="img-responsive uploadify-image">\
<input value="{$image}" name="{$name}" style="display:none">\
<a href="javascript:;" class="uploadify-remove-image" onclick="$(this).parents(\'div.uploadify-image\').remove();">x</a>\
</div>';
                    html = html.replace( /\{\$image\}/g , data.file_url );
                    html = html.replace( /\{\$name\}/g , name );
                    console.log( html );
                    $container.append( html );
                }
            }
        };
        var id = $div.find("input[type='file']").attr('id'); //必须用id初始化uploadify
        $( '#' + id ).uploadify( options );
    });

});
;/**
 * Created by kevin on 14-8-15.
 */

$(function(){
    $('.btn-submit-form-inside').click( function(){
        var $this = $(this),
            $form = $($this).find('form'),
            confirm = $(this).attr('data-attr-confirm');
        if( $form.length ){
            if( confirm ){
                artDialog.confirm({
                    content : confirm,
                    confirmed  : function(){
                        $form.submit();
                    }
                });
            }
            else{
                $form.submit();
            }
        }
    })
});
;/**
 * Created by kevin on 14-8-16.
 */

$(function(){
    $('input[type="checkbox"].gofarms-admin-switch').each( function( i , one ){
        var $one = $(one) ,
            checked = $one.checked;
        $one.bootstrapSwitch({
            state : checked
        });
        $one.on('switchChange.bootstrapSwitch', function(event, state) {
            console.log(this); // DOM element
            console.log(event); // jQuery event
            console.log(state); // true | false
            var actionON = $(this).attr('data-action-on'),
                actionOFF = $(this).attr('data-action-off'),
                id = $(this).attr('data-item-id'),
                token = $(this).attr('data-token');
            if( state ){ //启用
                console.log({
                    url : actionON ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
                $.ajax({
                    url : actionON ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
            }
            else{
                console.log({
                    url : actionOFF ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
                $.ajax({
                    url : actionOFF ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
            }
        });
    });
})
;/**
 * Created by kevin on 14-8-28.
 */

$(function(){
    $(".btn-search-modal-search").click(function(){
        var $this = $(this),
            $results = $this.parents( '.modal-content' ).find('.modal-body-results'),
            $search = $this.parents( '.modal-content' ).find('.modal-body-search'),
            $reset_btn = $this.parents( '.modal-content').find('.btn-search-modal-reset'),
            pattern = $this.parents('.modal-content').find(".modal-search-pattern").val(),
            url = $this.attr('data-attr-ajax-url'),
            options ={
                'pattern' : pattern ,
                'field' :$this.parents('.modal').attr('field') ,
                '_token' : $this.attr('data-csrf-token')
            };
        console.log( url );
        console.log( options );
        console.log( $results );
        $results.show();
        $search.hide();
        $this.hide();
        $results.html( 'loading...' );
        $results.load( url , options , function( response , status , jqXHR ){
            console.log( response );
            console.log( status );
            $reset_btn.show();
        });
    })

    $(".btn-search-modal-reset").click(function(){
        var $this = $(this),
            $results = $this.parents( '.modal-content' ).find('.modal-body-results'),
            $search = $this.parents( '.modal-content' ).find('.modal-body-search'),
            $search_btn = $this.parents( '.modal-content').find('.btn-search-modal-search');
        $results.hide();
        $search.show();
        $search_btn.show();
        $(this).hide();
    })
})

function modal_select_this_one_btn( one ){
    var $one = $(one),
        $modal = $one.parents('.modal'),
        $target = $one.parents('.form-group').find('.picked-item-span'),
        url = $one.attr('data-attr-url'),
        options = {
            'field' :$one.parents('.modal').attr('data-attr-field') ,
            'id'  : $one.attr('data-attr-id'),
            '_token' : $one.attr('data-csrf-token')
        }
    console.log( options );
    $modal.modal( 'hide' );
    $target.load( url , options );
}
