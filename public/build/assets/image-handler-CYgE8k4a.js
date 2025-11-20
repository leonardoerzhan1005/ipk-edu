function h(){document.querySelectorAll(".editor").forEach(n=>{const i=n,r=document.createElement("div");r.className="image-drop-zone",r.style.cssText=`
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 123, 255, 0.1);
            border: 2px dashed #007bff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #007bff;
            z-index: 1000;
            pointer-events: none;
        `,r.textContent="Перетащите изображения сюда";const s=i.parentElement;s.style.position="relative",s.appendChild(r),i.addEventListener("dragover",function(e){e.preventDefault(),r.style.display="flex"}),i.addEventListener("dragleave",function(e){i.contains(e.relatedTarget)||(r.style.display="none")}),i.addEventListener("drop",function(e){e.preventDefault(),r.style.display="none";const t=e.dataTransfer.files;c(t,i)}),i.addEventListener("click",function(e){if(e.target===i){const t=document.createElement("input");t.type="file",t.accept="image/*",t.multiple=!0,t.onchange=function(){c(t.files,i)},t.click()}})})}function c(o,n){Array.from(o).forEach(i=>{if(i.type.startsWith("image/")){const r=new FileReader;r.onload=function(s){const e=document.createElement("img");e.src=s.target.result,e.style.cssText=`
                    max-width: 100%;
                    height: auto;
                    border-radius: 4px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    margin: 10px 0;
                    cursor: move;
                `,e.setAttribute("contenteditable","false"),e.setAttribute("data-mce-object","img"),e.addEventListener("mousedown",function(t){t.target===e&&z(e,t)}),g(e,n)},r.readAsDataURL(i)}})}function g(o,n){const i=o.outerHTML,r=n.value||"",s=n.selectionStart||r.length,e=r.slice(0,s)+i+r.slice(s);if(n.value=e,window.tinymce){const t=window.tinymce.get(n.id);t&&t.setContent(e)}}function z(o,n){n.preventDefault();const i=n.clientX,r=n.clientY,s=o.offsetWidth,e=o.offsetHeight,t=b(o);function a(l){const u=l.clientX-i,p=l.clientY-r,f=Math.max(50,s+u),m=Math.max(50,e+p);o.style.width=f+"px",o.style.height=m+"px"}function d(){document.removeEventListener("mousemove",a),document.removeEventListener("mouseup",d),x(t)}document.addEventListener("mousemove",a),document.addEventListener("mouseup",d)}function b(o){const n=[];return[{top:-4,left:-4,cursor:"nw-resize"},{top:-4,left:"50%",marginLeft:-4,cursor:"n-resize"},{top:-4,right:-4,cursor:"ne-resize"},{top:"50%",right:-4,marginTop:-4,cursor:"e-resize"},{bottom:-4,right:-4,cursor:"se-resize"},{bottom:-4,left:"50%",marginLeft:-4,cursor:"s-resize"},{bottom:-4,left:-4,cursor:"sw-resize"},{top:"50%",left:-4,marginTop:-4,cursor:"w-resize"}].forEach((s,e)=>{const t=document.createElement("div");t.className="resize-handle",t.style.cssText=`
            position: absolute;
            width: 8px;
            height: 8px;
            background: #007bff;
            border: 1px solid #fff;
            cursor: ${s.cursor};
            z-index: 1001;
        `,Object.keys(s).forEach(a=>{a!=="cursor"&&(t.style[a]=s[a])}),o.parentElement.style.position="relative",o.parentElement.appendChild(t),n.push(t)}),n}function x(o){o.forEach(n=>{n.parentElement&&n.parentElement.removeChild(n)})}document.addEventListener("DOMContentLoaded",function(){h();const o=document.createElement("style");o.textContent=`
        .resize-handle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #007bff;
            border: 1px solid #fff;
            z-index: 1001;
        }
        
        .resize-handle:hover {
            background: #0056b3;
        }
        
        .image-drop-zone {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 123, 255, 0.1);
            border: 2px dashed #007bff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #007bff;
            z-index: 1000;
            pointer-events: none;
        }
        
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            background: #f9f9f9;
        }
        
        .image-gallery img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .image-gallery img:hover {
            transform: scale(1.05);
        }
    `,document.head.appendChild(o)});
