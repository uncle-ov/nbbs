!function(t,e){"object"==typeof exports&&"object"==typeof module?module.exports=e():"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?exports.CKEditor5=e():(t.CKEditor5=t.CKEditor5||{},t.CKEditor5.drupalentity=e())}(self,(()=>(()=>{var t={"ckeditor5/src/core.js":(t,e,i)=>{t.exports=i("dll-reference CKEditor5.dll")("./src/core.js")},"ckeditor5/src/engine.js":(t,e,i)=>{t.exports=i("dll-reference CKEditor5.dll")("./src/engine.js")},"ckeditor5/src/ui.js":(t,e,i)=>{t.exports=i("dll-reference CKEditor5.dll")("./src/ui.js")},"ckeditor5/src/utils.js":(t,e,i)=>{t.exports=i("dll-reference CKEditor5.dll")("./src/utils.js")},"ckeditor5/src/widget.js":(t,e,i)=>{t.exports=i("dll-reference CKEditor5.dll")("./src/widget.js")},"dll-reference CKEditor5.dll":t=>{"use strict";t.exports=CKEditor5.dll}},e={};function i(r){var n=e[r];if(void 0!==n)return n.exports;var o=e[r]={exports:{}};return t[r](o,o.exports,i),o.exports}i.d=(t,e)=>{for(var r in e)i.o(e,r)&&!i.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})},i.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e);var r={};return(()=>{"use strict";i.d(r,{default:()=>A});var t=i("ckeditor5/src/core.js"),e=i("ckeditor5/src/widget.js");class n extends t.Command{execute(t){const{model:e}=this.editor,i=this.editor.plugins.get("EntityEmbedEditing"),r=Object.fromEntries(Object.entries(i.attrs).map((([t,e])=>[e,t]))),n=Object.fromEntries(Object.keys(r).filter((e=>t[e])).map((e=>[r[e],t[e]])));e.change((t=>{e.insertContent(function(t,e){return t.createElement("drupalEntity",e)}(t,n))}))}refresh(){const t=this.editor.model,e=t.document.selection,i=t.schema.findAllowedParent(e.getFirstPosition(),"drupalEntity");this.isEnabled=null!==i}}class o extends t.Plugin{static get requires(){return[e.Widget]}init(){this.attrs={alt:"alt",title:"title",dataCaption:"data-caption",dataAlign:"data-align",drupalEntityLangCode:"data-langcode",drupalEntityEntityType:"data-entity-type",drupalEntityEntityUuid:"data-entity-uuid",drupalEntityViewMode:"data-view-mode",drupalEntityEmbedButton:"data-embed-button",drupalEntityEmbedDisplay:"data-entity-embed-display",drupalEntityEmbedDisplaySettings:"data-entity-embed-display-settings"};const t=this.editor.config.get("entityEmbed");if(!t)throw new Error("Error on initializing entityEmbed plugin: entityEmbed config is required.");this.options=t,this.labelError=Drupal.t("Preview failed"),this.previewError=`\n      <p>${Drupal.t("An error occurred while trying to preview the embedded content. Please save your work and reload this page.")}<p>\n    `,this._defineSchema(),this._defineConverters(),this.editor.commands.add("insertEntityEmbed",new n(this.editor))}_defineSchema(){this.editor.model.schema.register("drupalEntity",{isObject:!0,isContent:!0,isBlock:!0,allowWhere:"$block",allowAttributes:Object.keys(this.attrs)}),this.editor.editing.view.domConverter.blockElements.push("drupal-entity")}_defineConverters(){const{conversion:t}=this.editor,i={model:"drupalEntity",view:{name:"drupal-entity"}};t.for("upcast").elementToElement(i),t.for("dataDowncast").elementToElement(i),t.for("editingDowncast").elementToElement({...i,view:(t,{writer:i})=>{const r=t.hasAttribute("dataAlign")?` align-${t.getAttribute("dataAlign")}`:"",n=i.createContainerElement("figure",{class:`drupal-entity${r}`});return i.setCustomProperty("drupalEntity",!0,n),(0,e.toWidget)(n,i,{label:Drupal.t("Entity Embed widget")})}}).add((t=>(t.on("attribute:drupalEntityEntityUuid:drupalEntity",((t,e,i)=>{const r=i.writer,n=e.item,o=i.mapper.toViewElement(e.item);let a=this._getPreviewContainer(o.getChildren());if(a){if("ready"!==a.getAttribute("data-drupal-entity-preview"))return;r.setAttribute("data-drupal-entity-preview","loading",a)}else a=r.createRawElement("div",{"data-drupal-entity-preview":"loading"}),r.insert(r.createPositionAt(o,0),a);this._loadPreview(n).then((({label:t,preview:e})=>{a&&this.editor.editing.view.change((i=>{const r=i.createRawElement("div",{"data-drupal-entity-preview":"ready","aria-label":t},(t=>{t.innerHTML=e}));i.insert(i.createPositionBefore(a),r),i.remove(a)}))}))})),t))),Object.keys(this.attrs).forEach((e=>{const i={model:{key:e,name:"drupalEntity"},view:{name:"drupal-entity",key:this.attrs[e]}};t.for("dataDowncast").attributeToAttribute(i),t.for("upcast").attributeToAttribute(i)}))}async _loadPreview(t){const e={text:this._renderElement(t)},i=await fetch(Drupal.url("embed/preview/"+this.options.format+"?"+new URLSearchParams(e)),{headers:{"X-Drupal-EmbedPreview-CSRF-Token":this.options.previewCsrfToken}});if(i.ok){return{label:Drupal.t("Entity Embed widget"),preview:await i.text()}}return{label:this.labelError,preview:this.previewError}}_renderElement(t){const e=this.editor.model.change((e=>{const i=e.createDocumentFragment(),r=e.cloneElement(t,!1);return["linkHref","dataAlign"].forEach((t=>{e.removeAttribute(t,r)})),e.append(r,i),i}));return this.editor.data.stringify(e)}_getPreviewContainer(t){for(const e of t){if(e.hasAttribute("data-drupal-entity-preview"))return e;if(e.childCount){const t=this._getPreviewContainer(e.getChildren());if(t)return t}}return null}static get pluginName(){return"EntityEmbedEditing"}}var a=i("ckeditor5/src/ui.js");class s extends t.Plugin{static get requires(){return[e.WidgetToolbarRepository]}init(){const e=this.editor,i=e.plugins.get("EntityEmbedEditing"),r=e.config.get("entityEmbed"),{dialogSettings:n={}}=r;e.ui.componentFactory.add("entityEmbedEdit",(o=>{let s=new a.ButtonView(o);return s.set({label:e.t("Edit"),icon:t.icons.pencil,tooltip:!0}),this.listenTo(s,"execute",(t=>{const o=e.model.document.selection.getSelectedElement(),a=Drupal.url("entity-embed/dialog/"+r.format+"/"+o.getAttribute("drupalEntityEmbedButton"));let s={};for(let[t,e]of o.getAttributes()){let r=i.attrs[t];r&&(s[r]=e)}this._openDialog(a,s,(({attributes:t})=>{e.execute("insertEntityEmbed",t),e.editing.view.focus()}),n)})),s})),e.ui.componentFactory.add("editEmbeddedEntity",(i=>{let r=new a.ButtonView(i);return r.set({isEnabled:!0,label:Drupal.t("Edit the embedded entity (opens in new tab)"),icon:t.icons.cog,tooltip:!0}),this.listenTo(r,"execute",(t=>{const i=e.model.document.selection.getSelectedElement();if(!i)return null;if(!i.hasAttribute("drupalEntityEntityUuid"))return console.warn(Drupal.t("Unable to create edit link. There must be a value for data-entity-uuid.")),null;if(!i.hasAttribute("drupalEntityEntityType"))return console.warn(Drupal.t("Unable to create edit link. There must be a value for data-entity-type.")),null;const n=i.getAttribute("drupalEntityEntityUuid"),o=i.getAttribute("drupalEntityEntityType"),a=Drupal.url(`entity-embed/edit-embedded/${o}/${n}`);fetch(a).then((t=>{t.ok||r.set({label:Drupal.t(`You do not have the permissions needed to edit this ${o}.`),isEnabled:!1})})),window.open(a,"_blank")})),r}))}afterInit(){const{editor:t}=this;if(!t.plugins.has("WidgetToolbarRepository"))return;t.plugins.get(e.WidgetToolbarRepository).register("entityEmbed",{ariaLabel:Drupal.t("Entity Embed toolbar"),items:["entityEmbedEdit","entityEmbedLink","editEmbeddedEntity"],getRelatedElement(t){const i=t.getSelectedElement();return i&&(0,e.isWidget)(i)&&i.getCustomProperty("drupalEntity")?i:null}})}_openDialog(t,e,i,r){const n=r.dialogClass?r.dialogClass.split(" "):[];n.push("ui-dialog--narrow"),r.dialogClass=n.join(" "),r.autoResize=window.matchMedia("(min-width: 600px)").matches,r.width="auto";Drupal.ajax({dialog:r,dialogType:"modal",selector:".ckeditor5-dialog-loading-link",url:t,progress:{type:"fullscreen"},submit:{editor_object:e}}).execute(),Drupal.ckeditor5.saveCallback=i}static get pluginName(){return"EntityEmbedToolbar"}}class l extends t.Plugin{static get requires(){return["Widget"]}init(){const t=this.editor,e=t.commands.get("insertEntityEmbed"),i=t.config.get("entityEmbed");if(!i)return;const{dialogSettings:r={}}=i,n=i.buttons;Object.keys(n).forEach((o=>{const s=Drupal.url("entity-embed/dialog/"+i.format+"/"+o);t.ui.componentFactory.add(o,(i=>{const l=n[o],d=new a.ButtonView(i);let u=null;if(l.icon.endsWith("svg")){let t=new XMLHttpRequest;t.open("GET",l.icon,!1),t.send(null),u=t.response}else console.warn(`CKEditor 5 only supports enity embed icons in SVG format. The icon provided is ${l.icon}`);return d.set({label:l.label,icon:u??'<?xml version="1.0" encoding="UTF-8" standalone="no"?>\n<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">\n<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve">  <image id="image0" width="16" height="16" x="0" y="0"\n    href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAALGPC/xhBQAAAAJiS0dE\nAP+Hj8y/AAAAB3RJTUUH5gkNAywZrK+VpAAAAElJREFUKM9jYKAeuMrwHwUuhwizoCj6xfALzv6J\nzYRKTIOZCNmMaoUpQzKcvZnhFX5HWmIz4SrDDTj7LbUcSVABbkfeJ9kEcgEApvsllE2X4VkAAAAl\ndEVYdGRhdGU6Y3JlYXRlADIwMjItMDktMTNUMDE6NDQ6MjUrMDI6MDCMUacyAAAAJXRFWHRkYXRl\nOm1vZGlmeQAyMDIyLTA5LTEzVDAxOjQ0OjI1KzAyOjAw/QwfjgAAAABJRU5ErkJggg==" />\n</svg>\n',tooltip:!0}),d.bind("isOn","isEnabled").to(e,"value","isEnabled"),this.listenTo(d,"execute",(()=>Drupal.ckeditor5.openDialog(s,(({attributes:e})=>{t.execute("insertEntityEmbed",e)}),r))),d}))}))}static get pluginName(){return"EntityEmbedUI"}}function d(t,e,i){if(e.attributes)for(const[r,n]of Object.entries(e.attributes))t.setAttribute(r,n,i);e.styles&&t.setStyle(e.styles,i),e.classes&&t.addClass(e.classes,i)}function u(t,e,i){if(!i.consumable.consume(e.item,t.name))return;const r=i.mapper.toViewElement(e.item);d(i.writer,e.attributeNewValue,r)}class c extends t.Plugin{constructor(t){if(super(t),!t.plugins.has("GeneralHtmlSupport"))return;t.plugins.has("DataFilter")&&t.plugins.has("DataSchema")||console.error("DataFilter and DataSchema plugins are required for Entity Embed to integrate with General HTML Support plugin.");const{schema:e}=t.model,{conversion:i}=t,r=this.editor.plugins.get("DataFilter");this.editor.plugins.get("DataSchema").registerBlockElement({model:"drupalEntity",view:"drupal-entity"}),r.on("register:drupal-entity",((t,n)=>{"drupalEntity"===n.model&&(e.extend("drupalEntity",{allowAttributes:["htmlLinkAttributes","htmlAttributes"]}),i.for("upcast").add(function(t){return e=>{e.on("element:drupal-entity",((e,i,r)=>{function n(e,n){const o=t.processViewAttributes(e,r);o&&r.writer.setAttribute(n,o,i.modelRange)}const o=i.viewItem,a=o.parent;n(o,"htmlAttributes"),a.is("element","a")&&n(a,"htmlLinkAttributes")}),{priority:"low"})}}(r)),i.for("editingDowncast").add((t=>{t.on("attribute:linkHref:drupalEntity",((t,e,i)=>{if(!i.consumable.consume(e.item,"attribute:htmlLinkAttributes:drupalEntity"))return;const r=i.mapper.toViewElement(e.item),n=function(t,e,i){const r=t.createRangeOn(e);for(const{item:t}of r.getWalker())if(t.is("element",i))return t}(i.writer,r,"a");d(i.writer,e.item.getAttribute("htmlLinkAttributes"),n)}),{priority:"low"})})),i.for("dataDowncast").add((t=>{t.on("attribute:linkHref:drupalEntity",((t,e,i)=>{if(!i.consumable.consume(e.item,"attribute:htmlLinkAttributes:drupalEntity"))return;const r=i.mapper.toViewElement(e.item).parent;d(i.writer,e.item.getAttribute("htmlLinkAttributes"),r)}),{priority:"low"}),t.on("attribute:htmlAttributes:drupalEntity",u,{priority:"low"})})),t.stop())}))}static get pluginName(){return"EntityEmbedGeneralHtmlSupport"}}var m=i("ckeditor5/src/engine.js"),p=i("ckeditor5/src/utils.js");function g(t){return Array.from(t.getChildren()).find((t=>"drupal-entity"===t.name))}function E(t){return e=>{e.on(`attribute:${t.id}:drupalEntity`,((e,i,r)=>{const n=r.mapper.toViewElement(i.item);let o=Array.from(n.getChildren()).find((t=>"a"===t.name));if(o=!o&&n.is("element","a")?n:Array.from(n.getAncestors()).find((t=>"a"===t.name)),o){for(const[e,i]of(0,p.toMap)(t.attributes))r.writer.setAttribute(e,i,o);t.classes&&r.writer.addClass(t.classes,o);for(const e in t.styles)Object.prototype.hasOwnProperty.call(t.styles,e)&&r.writer.setStyle(e,t.styles[e],o)}}))}}function b(t,e){return t=>{t.on("element:a",((t,i,r)=>{const n=i.viewItem;if(!g(n))return;const o=new m.Matcher(e._createPattern()).match(n);if(!o)return;if(!r.consumable.consume(n,o.match))return;const a=i.modelCursor.nodeBefore;r.writer.setAttribute(e.id,!0,a)}),{priority:"high"})}}class y extends t.Plugin{static get requires(){return["EntityEmbedEditing"]}static get pluginName(){return"EntityEmbedLinkEditing"}init(){const{editor:t}=this;t.model.schema.extend("drupalEntity",{allowAttributes:["linkHref"]}),t.conversion.for("upcast").add((t=>{t.on("element:a",((t,e,i)=>{const r=e.viewItem,n=g(r);if(!n)return;if(!i.consumable.consume(r,{attributes:["href"],name:!0}))return;const o=r.getAttribute("href");if(!o)return;const a=i.convertItem(n,e.modelCursor);e.modelRange=a.modelRange,e.modelCursor=a.modelCursor;const s=e.modelCursor.nodeBefore;s&&s.is("element","drupalEntity")&&i.writer.setAttribute("linkHref",o,s)}),{priority:"high"})})),t.conversion.for("editingDowncast").add((t=>{t.on("attribute:linkHref:drupalEntity",((t,e,i)=>{const{writer:r}=i;if(!i.consumable.consume(e.item,t.name))return;const n=i.mapper.toViewElement(e.item),o=Array.from(n.getChildren()).find((t=>"a"===t.name));if(o)e.attributeNewValue?r.setAttribute("href",e.attributeNewValue,o):(r.move(r.createRangeIn(o),r.createPositionAt(n,0)),r.remove(o));else{const t=Array.from(n.getChildren()).find((t=>t.getAttribute("data-drupal-entity-preview"))),i=r.createContainerElement("a",{href:e.attributeNewValue});r.insert(r.createPositionAt(n,0),i),r.move(r.createRangeOn(t),r.createPositionAt(i,0))}}),{priority:"high"})})),t.conversion.for("dataDowncast").add((t=>{t.on("attribute:linkHref:drupalEntity",((t,e,i)=>{const{writer:r}=i;if(!i.consumable.consume(e.item,t.name))return;const n=i.mapper.toViewElement(e.item),o=r.createContainerElement("a",{href:e.attributeNewValue});r.insert(r.createPositionBefore(n),o),r.move(r.createRangeOn(n),r.createPositionAt(o,0))}),{priority:"high"})})),this._enableManualDecorators();if(t.commands.get("link").automaticDecorators.length>0)throw new Error("The Drupal Entity plugin is not compatible with automatic link decorators. To use Drupal Entity, disable any plugins providing automatic link decorators.")}_enableManualDecorators(){const t=this.editor,e=t.commands.get("link");for(const i of e.manualDecorators)t.model.schema.extend("drupalEntity",{allowAttributes:i.id}),t.conversion.for("downcast").add(E(i)),t.conversion.for("upcast").add(b(0,i))}}class h extends t.Plugin{static get requires(){return["LinkEditing","LinkUI","EntityEmbedEditing","EntityEmbedUI"]}static get pluginName(){return"EntityEmbedLinkUi"}init(){const{editor:t}=this,e=t.editing.view.document;this.listenTo(e,"click",((e,i)=>{this._isSelectedLinkedEntityEmbed(t.model.document.selection)&&(i.preventDefault(),e.stop())}),{priority:"high"}),this._createToolbarLinkEntityEmbedButton()}_createToolbarLinkEntityEmbedButton(){const{editor:t}=this;t.ui.componentFactory.add("entityEmbedLink",(e=>{const i=new a.ButtonView(e),r=t.plugins.get("LinkUI"),n=t.commands.get("link");return i.set({isEnabled:!0,label:Drupal.t("Link entity embed"),icon:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="m11.077 15 .991-1.416a.75.75 0 1 1 1.229.86l-1.148 1.64a.748.748 0 0 1-.217.206 5.251 5.251 0 0 1-8.503-5.955.741.741 0 0 1 .12-.274l1.147-1.639a.75.75 0 1 1 1.228.86L4.933 10.7l.006.003a3.75 3.75 0 0 0 6.132 4.294l.006.004zm5.494-5.335a.748.748 0 0 1-.12.274l-1.147 1.639a.75.75 0 1 1-1.228-.86l.86-1.23a3.75 3.75 0 0 0-6.144-4.301l-.86 1.229a.75.75 0 0 1-1.229-.86l1.148-1.64a.748.748 0 0 1 .217-.206 5.251 5.251 0 0 1 8.503 5.955zm-4.563-2.532a.75.75 0 0 1 .184 1.045l-3.155 4.505a.75.75 0 1 1-1.229-.86l3.155-4.506a.75.75 0 0 1 1.045-.184z"/></svg>',keystroke:"Ctrl+K",tooltip:!0,isToggleable:!0}),i.bind("isEnabled").to(n,"isEnabled"),i.bind("isOn").to(n,"value",(t=>!!t)),this.listenTo(i,"execute",(()=>{this._isSelectedLinkedEntityEmbed(t.model.document.selection)?r._addActionsView():r._showUI(!0)})),i}))}_isSelectedLinkedEntityEmbed(t){const e=t.getSelectedElement();return!!e&&e.is("element","drupalEntity")&&e.hasAttribute("linkHref")}}class f extends t.Plugin{static get requires(){return[y,h]}static get pluginName(){return"EntityEmbedLink"}}class w extends t.Plugin{static get requires(){return[o,l,s,c,f]}static get pluginName(){return"EntityEmbed"}}const A={EntityEmbed:w}})(),r=r.default})()));