import{D as d,E as n,c as t,F as c,e as i,i as l,n as m,b as u}from"./app-ea7ca92b.js";const p={class:"grid grid-cols-3 gap-6"},_=["onClick"],b=["src"],h={__name:"TemplateSelector",props:d(["profileTemplates"],{modelValue:{},modelModifiers:{}}),emits:["update:modelValue"],setup(r){const a=n(r,"modelValue"),o=s=>{a.value=s};return(s,v)=>(l(),t("div",p,[(l(!0),t(c,null,i(r.profileTemplates,e=>(l(),t("button",{key:e.id,type:"button",onClick:g=>o(e.template),class:m(["overflow-hidden rounded-lg border-2 border-transparent",{"border-primary-500 dark:border-primary-500":a.value==e.template}])},[u("img",{src:e.preview,alt:"preview",class:"h-full w-full object-cover"},null,8,b)],10,_))),128))]))}};export{h as default};
