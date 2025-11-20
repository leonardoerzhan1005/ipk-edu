document.addEventListener("DOMContentLoaded",function(){tinymce.init({selector:".editor",height:600,menubar:!0,plugins:["advlist","autolink","lists","link","image","charmap","preview","anchor","searchreplace","visualblocks","code","fullscreen","insertdatetime","media","table","help","wordcount","emoticons","template","codesample","hr","pagebreak","nonbreaking","toc","imagetools","textpattern","noneditable","quickbars","accordion","autosave","save","directionality","code","codesample","media","powerpaste","rtc","tinymcespellchecker","a11ychecker","linkchecker"],toolbar:["undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat","alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist checklist | pagebreak | charmap emoticons","searchreplace | insertfile image media table | link anchor | ltr rtl | code codesample | fullscreen preview | save print | help"],quickbars_selection_toolbar:"bold italic | quicklink h2 h3 blockquote quickimage quicktable",quickbars_insert_toolbar:"quickimage quicktable",image_advtab:!0,image_caption:!0,image_title:!0,image_description:!0,image_dimensions:!0,image_class_list:[{title:"Responsive",value:"img-responsive"},{title:"Rounded",value:"img-rounded"},{title:"Circle",value:"img-circle"},{title:"Thumbnail",value:"img-thumbnail"},{title:"No border",value:"img-no-border"},{title:"Shadow",value:"img-shadow"},{title:"Border",value:"img-border"}],media_live_embeds:!0,media_url_resolver:function(e,t){if(e.url.indexOf("youtube.com")!==-1||e.url.indexOf("youtu.be")!==-1){var i='<iframe src="'+e.url+'" width="560" height="315" frameborder="0" allowfullscreen></iframe>';t({html:i})}else t({html:""})},table_default_attributes:{border:"1"},table_default_styles:{"border-collapse":"collapse",width:"100%"},table_class_list:[{title:"None",value:""},{title:"Table",value:"table"},{title:"Striped",value:"table table-striped"},{title:"Bordered",value:"table table-bordered"},{title:"Hover",value:"table table-hover"},{title:"Condensed",value:"table table-condensed"}],link_default_protocol:"https",link_context_toolbar:!0,link_title:!0,link_target_list:[{title:"None",value:""},{title:"Same window",value:"_self"},{title:"New window",value:"_blank"}],lists_indent_on_tab:!0,font_family_formats:"Arial=arial,helvetica,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats",font_size_formats:"8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt",autosave_ask_before_unload:!0,autosave_interval:"30s",autosave_prefix:"{path}{query}-{id}-",autosave_retention:"2m",browser_spellcheck:!0,contextmenu:"link image imagetools table spellchecker configurepermanentpen",codesample_languages:[{text:"HTML/XML",value:"markup"},{text:"JavaScript",value:"javascript"},{text:"CSS",value:"css"},{text:"PHP",value:"php"},{text:"Ruby",value:"ruby"},{text:"Python",value:"python"},{text:"Java",value:"java"},{text:"C",value:"c"},{text:"C#",value:"csharp"},{text:"C++",value:"cpp"}],file_picker_types:"image",file_picker_callback:function(e,t,i){if(i.filetype==="image"){var a=document.createElement("input");a.setAttribute("type","file"),a.setAttribute("accept","image/*"),a.onchange=function(){var l=this.files[0],o=new FileReader;o.onload=function(){e(o.result,{alt:l.name})},o.readAsDataURL(l)},a.click()}},paste_data_images:!0,paste_enable_default_filters:!1,paste_auto_cleanup_on_paste:!0,paste_remove_styles_if_webkit:!1,paste_merge_formats:!0,resize:!0,resize_img_proportional:!0,fullscreen_native:!0,preview_styles:"font-family font-size font-weight font-style text-decoration text-transform color background-color",searchreplace_replace_dialog:!0,insertdatetime_formats:["%Y-%m-%d","%H:%M:%S","%Y-%m-%d %H:%M:%S"],insertdatetime_element:!0,directionality:"ltr",a11y_advanced_options:!0,linkchecker_check_on_startup:!0,linkchecker_status_codes:"200,301,302",powerpaste_word_import:"prompt",powerpaste_html_import:"prompt",powerpaste_allow_local_images:!0,save_onsavecallback:function(){console.log("Content saved")},print:{prepend:"<h1>Document Title</h1>",header:"Document Header",footer:"Document Footer"},content_style:`
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                font-size: 16px; 
                line-height: 1.6; 
                color: #333;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            img { 
                max-width: 100%; 
                height: auto; 
                border-radius: 4px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            table { 
                border-collapse: collapse; 
                width: 100%; 
                margin: 20px 0;
            }
            table th, table td { 
                border: 1px solid #ddd; 
                padding: 12px; 
                text-align: left;
            }
            table th { 
                background-color: #f5f5f5; 
                font-weight: bold;
            }
            blockquote { 
                border-left: 4px solid #007cba; 
                margin: 20px 0; 
                padding: 10px 20px; 
                background-color: #f9f9f9;
                font-style: italic;
            }
            code { 
                background-color: #f4f4f4; 
                padding: 2px 4px; 
                border-radius: 3px; 
                font-family: 'Courier New', monospace;
            }
            pre { 
                background-color: #f4f4f4; 
                padding: 15px; 
                border-radius: 5px; 
                overflow-x: auto;
                border: 1px solid #ddd;
            }
            h1, h2, h3, h4, h5, h6 { 
                color: #2c3e50; 
                margin-top: 30px; 
                margin-bottom: 15px;
            }
            .img-responsive { 
                max-width: 100%; 
                height: auto; 
            }
            .img-rounded { 
                border-radius: 6px; 
            }
            .img-circle { 
                border-radius: 50%; 
            }
            .img-thumbnail { 
                padding: 4px; 
                border: 1px solid #ddd; 
                border-radius: 4px; 
            }
            .img-no-border { 
                border: none; 
            }
            .img-shadow { 
                box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
            }
            .img-border { 
                border: 2px solid #333; 
            }
        `,mobile:{theme:"mobile",plugins:["autosave","lists","autolink"],toolbar:["undo","bold","italic","styleselect"]},skin:"oxide",content_css:"default",debug:!1,cache_suffix:"?v=1.0.0",language:"ru",setup:function(e){e.on("ObjectResized",function(t){console.log("Image resized:",t.target)}),e.on("BeforeSetContent",function(t){t.content.includes("<img")&&console.log("Image content detected")}),e.on("SaveContent",function(t){console.log("Content saved")}),e.ui.registry.addButton("dragimage",{text:"Drag Image",onAction:function(){e.windowManager.open({title:"Insert Image by Drag & Drop",body:{type:"panel",items:[{type:"htmlpanel",html:'<div id="dropzone" style="border: 2px dashed #ccc; padding: 20px; text-align: center; background: #f9f9f9;">Drop images here or click to select</div>'}]},onAction:function(t){t.close()}})}})}})});
