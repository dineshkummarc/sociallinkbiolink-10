import{i as c,f as r,l as i,u as t,S as e,c as l,w as d,b as s,t as o,k as m,$ as _}from"./app-ea7ca92b.js";const p={class:"c-modal-content rounded bg-white p-5 dark:bg-dark-800"},b=s("div",{class:"exclamation"},[s("span",null,"!")],-1),u={class:"mt-4 text-center"},f={class:"mb-4 mt-4 text-center"},k={class:"c-model-btn-container"},h={__name:"ActionModal",setup(x){return(g,a)=>(c(),r(_,{name:"fade",appear:"",mode:"out-in"},{default:i(()=>[t(e).state?(c(),l("div",{key:0,onClick:a[2]||(a[2]=d((...n)=>t(e).reset&&t(e).reset(...n),["self"])),class:"c-modal-container"},[s("div",p,[b,s("h6",u,o(t(e).confirm_text),1),s("p",f,o(t(e).message),1),s("div",k,[s("button",{onClick:a[0]||(a[0]=(...n)=>t(e).accept&&t(e).accept(...n)),class:"btn btn-primary"},o(t(e).accept_btn_text),1),s("button",{onClick:a[1]||(a[1]=(...n)=>t(e).reset&&t(e).reset(...n)),class:"btn btn-danger"},o(t(e).reject_btn_text),1)])])])):m("",!0)]),_:1}))}};export{h as default};
