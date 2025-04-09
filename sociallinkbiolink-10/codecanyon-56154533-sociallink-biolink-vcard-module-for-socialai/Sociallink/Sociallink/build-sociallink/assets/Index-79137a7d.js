import{s as $,o as V,T as f,a as M,c as h,b as s,d as i,u as t,t as o,F as g,e as E,f as k,k as j,w,g as u,v as p,h as B,i as m,j as n}from"./app-ea7ca92b.js";import{_ as I,a as x}from"./SpinnerBtn-5f942d18.js";import{_ as O}from"./PageHeader-5a9828b9.js";import{_ as q,h as F}from"./moment-33745971.js";import{d as v}from"./drawer-fe4c5e1a.js";import{_ as N}from"./NoDataFound-b1b3c01b.js";import{_ as T}from"./FilterForm-ae716a11.js";import{_}from"./InputFieldError-88a28532.js";const Y={class:"container flex-grow p-4 sm:p-6"},A={class:"space-y-4"},P={class:"table-responsive whitespace-nowrap rounded-primary"},R={class:"table"},z={class:"!text-right"},G={key:0},H={class:"flex items-center gap-1"},J=["href"],K=["href"],Q={class:"flex justify-end"},W={class:"dropdown","data-placement":"bottom-start"},X={class:"dropdown-toggle"},Z={class:"dropdown-content w-40"},ss={class:"dropdown-list"},ts={class:"dropdown-list-item"},es=["onClick"],as={class:"dropdown-list-item"},os=["onClick"],ns={id:"socialLinkSettingDrawer",class:"drawer drawer-right"},ls={class:"drawer-header"},is={type:"button",class:"btn btn-plain-secondary dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:bg-slate-700","data-dismiss":"drawer"},rs={class:"drawer-body"},ds={class:"mb-2"},cs={class:"label mb-1"},us={class:"drawer-footer"},ms={class:"flex justify-end gap-2"},ps={type:"button",class:"btn btn-secondary","data-dismiss":"drawer"},_s={id:"socialLinkEditDrawer",class:"drawer drawer-right"},bs={class:"drawer-header"},hs={type:"button",class:"btn btn-plain-secondary dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:bg-slate-700","data-dismiss":"drawer"},vs={class:"drawer-body space-y-1"},fs={class:"label mb-1"},gs={class:"label mb-1"},ks={class:"label mb-1"},ws={class:"label mb-1"},xs={class:"label mb-1"},ys={value:"active"},Ls={value:"inactive"},Ss={class:"drawer-footer"},Cs={class:"flex justify-end gap-2"},Ds={type:"button",class:"btn btn-secondary","data-dismiss":"drawer"},qs=Object.assign({layout:I},{__name:"Index",props:["socialLinks","socialLinkSettings","buttons"],setup(d){const{deleteRow:y}=$();V(()=>{v.init()});const b=f({credit:d.socialLinkSettings.credit}),e=f({id:"",uuid:"",name:"",url:"",username:"",bio:"",status:"active"}),L=()=>{e.patch(route("admin.sociallink.linktree.update",e.uuid),{onSuccess:()=>{e.value={},v.of("#socialLinkEditDrawer").hide()}})},S=r=>{e.id=r.id,e.uuid=r.social_profile.uuid,e.username=r.social_profile.username,e.bio=r.social_profile.bio,e.name=r.name,e.url=r.url,e.status=r.status,v.of("#socialLinkEditDrawer").show()};function C(r,l,c){r.put(route("admin.option.update",l),{onSuccess:()=>{v.of(c).hide(),e.reset()}})}const D=[{value:"url",label:"URL"},{label:"Status",value:"status",options:[{label:"Active",value:"active"},{label:"Inactive",value:"inactive"}]}];return(r,l)=>{const c=M("Icon");return m(),h(g,null,[s("main",Y,[i(O,{title:t(n)("Social Links"),buttons:d.buttons},null,8,["title","buttons"]),s("div",A,[i(T,{options:D}),s("div",P,[s("table",R,[s("thead",null,[s("tr",null,[s("th",null,o(t(n)("Platform")),1),s("th",null,o(t(n)("Username")),1),s("th",null,o(t(n)("URL")),1),s("th",null,o(t(n)("Date")),1),s("th",z,o(t(n)("Actions")),1)])]),d.socialLinks.data&&d.socialLinks.data.length?(m(),h("tbody",G,[(m(!0),h(g,null,E(d.socialLinks.data,a=>(m(),h("tr",{key:a.id},[s("td",null,[s("p",H,[i(c,{icon:a.icon,class:"text-xl"},null,8,["icon"]),s("span",null,o(a.name),1)])]),s("td",null,[s("a",{class:"hover:underline",href:`http://stori-ai.test/bio/${a.social_profile.username}`,target:"_blank"},o(a.social_profile.username),9,J)]),s("td",null,[s("a",{href:a.url,target:"_blank",class:"text-primary-500 hover:underline"},o(a.url),9,K)]),s("td",null,o(t(F)(a.created_at).format("DD MMM, YYYY.  h:mm a")),1),s("td",null,[s("div",Q,[s("div",W,[s("div",X,[i(c,{class:"text-2xl",icon:"bx:dots-horizontal-rounded"})]),s("div",Z,[s("ul",ss,[s("li",ts,[s("button",{onClick:U=>S(a),class:"dropdown-link text-red-500"},[i(c,{icon:"fe:pencil"}),s("span",null,o(t(n)("Edit")),1)],8,es)]),s("li",as,[s("button",{onClick:U=>t(y)(r.route("admin.sociallink.linktree.destroy",a.id)),class:"dropdown-link text-red-500"},[i(c,{icon:"fe:trash"}),s("span",null,o(t(n)("Delete")),1)],8,os)])])])])])])]))),128))])):(m(),k(N,{key:1,"for-table":"true"}))])]),d.socialLinks.data&&d.socialLinks.data.length?(m(),k(q,{key:0,links:d.socialLinks.links},null,8,["links"])):j("",!0)])]),s("div",ns,[s("form",{method:"POST",onSubmit:l[1]||(l[1]=w(a=>C(t(b),"social_link","#socialLinkSettingDrawer"),["prevent"]))},[s("div",ls,[s("h5",null,o(t(n)("Social Link Settings")),1),s("button",is,[i(c,{class:"text-xl",icon:"mdi:close"})])]),s("div",rs,[s("div",ds,[s("label",cs,o(t(n)("Credit charge per social profile")),1),u(s("input",{type:"number","onUpdate:modelValue":l[0]||(l[0]=a=>t(b).credit=a),class:"input",required:""},null,512),[[p,t(b).credit]])])]),s("div",us,[s("div",ms,[s("button",ps,[s("span",null,o(t(n)("Close")),1)]),i(x,{classes:"btn btn-primary",processing:t(b).processing,"btn-text":t(n)("Save Changes")},null,8,["processing","btn-text"])])])],32)]),s("div",_s,[s("form",{method:"POST",onSubmit:w(L,["prevent"])},[s("div",bs,[s("h5",null,o(t(n)("Social Link Editing")),1),s("button",hs,[i(c,{class:"text-xl",icon:"mdi:close"})])]),s("div",vs,[s("div",null,[s("label",fs,o(t(n)("Username")),1),u(s("input",{type:"text","onUpdate:modelValue":l[2]||(l[2]=a=>t(e).username=a),class:"input",required:""},null,512),[[p,t(e).username]]),i(_,{message:t(e).errors.username},null,8,["message"])]),s("div",null,[s("label",gs,o(t(n)("Bio")),1),u(s("textarea",{type:"text","onUpdate:modelValue":l[3]||(l[3]=a=>t(e).bio=a),class:"textarea"},null,512),[[p,t(e).bio]]),i(_,{message:t(e).errors.bio},null,8,["message"])]),s("div",null,[s("label",ks,o(t(n)("Name")),1),u(s("input",{type:"text","onUpdate:modelValue":l[4]||(l[4]=a=>t(e).name=a),class:"input",required:""},null,512),[[p,t(e).name]]),i(_,{message:t(e).errors.name},null,8,["message"])]),s("div",null,[s("label",ws,o(t(n)("Url")),1),u(s("input",{type:"text","onUpdate:modelValue":l[5]||(l[5]=a=>t(e).url=a),class:"input",required:""},null,512),[[p,t(e).url]]),i(_,{message:t(e).errors.url},null,8,["message"])]),s("div",null,[s("label",xs,o(t(n)("Status")),1),u(s("select",{"onUpdate:modelValue":l[6]||(l[6]=a=>t(e).status=a),class:"select"},[s("option",ys,o(t(n)("Active")),1),s("option",Ls,o(t(n)("Inactive")),1)],512),[[B,t(e).status]]),i(_,{message:t(e).errors.status},null,8,["message"])])]),s("div",Ss,[s("div",Cs,[s("button",Ds,[s("span",null,o(t(n)("Close")),1)]),i(x,{classes:"btn btn-primary",processing:t(e).processing,"btn-text":t(n)("Save Changes")},null,8,["processing","btn-text"])])])],32)])],64)}}});export{qs as default};
