/*
@license

dhtmlxGantt v.5.2.0 Professional
This software is covered by DHTMLX Commercial License. Usage without proper license is prohibited.

(c) Dinamenta, UAB.

*/!function(t){var e={};function r(n){if(e[n])return e[n].exports;var i=e[n]={i:n,l:!1,exports:{}};return t[n].call(i.exports,i,i.exports,r),i.l=!0,i.exports}r.m=t,r.c=e,r.d=function(t,e,n){r.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},r.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)r.d(n,i,function(e){return t[e]}.bind(null,i));return n},r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,"a",e),e},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.p="/codebase/",r(r.s=204)}({204:function(t,e){gantt._groups={relation_property:null,relation_id_property:"$group_id",group_id:null,group_text:null,loading:!1,loaded:0,init:function(t){var e=this;t.attachEvent("onClear",function(){e.clear()}),e.clear();var r=t.$data.tasksStore.getParent;t.$data.tasksStore.getParent=function(n){return e.is_active()?e.get_parent(t,n):r.apply(this,arguments)};var n=t.$data.tasksStore.setParent;t.$data.tasksStore.setParent=function(r,i){if(!e.is_active())return n.apply(this,arguments);if(t.isTaskExists(i)){var a=t.getTask(i);r[e.relation_property]=a[e.relation_id_property],this._setParentInner.apply(this,arguments)}},t.attachEvent("onBeforeTaskDisplay",function(r,n){return!(e.is_active()&&n.type==t.config.types.project&&!n.$virtual)}),t.attachEvent("onBeforeParse",function(){e.loading=!0}),t.attachEvent("onTaskLoading",function(){return e.is_active()&&(e.loaded--,e.loaded<=0&&(e.loading=!1,t.eachTask(t.bind(function(e){this.get_parent(t,e)},e)))),!0}),t.attachEvent("onParse",function(){e.loading=!1,e.loaded=0})},get_parent:function(t,e,r){void 0===e.id&&(e=t.getTask(e));var n=e[this.relation_property];if(void 0!==this._groups_pull[n])return this._groups_pull[n];var i=t.config.root_id;return this.loading||(i=this.find_parent(r||t.getTaskByTime(),n,this.relation_id_property,t.config.root_id),this._groups_pull[n]=i),i},find_parent:function(t,e,r,n){for(var i=0;i<t.length;i++){var a=t[i];if(void 0!==a[r]&&a[r]==e)return a.id}return n},clear:function(){this._groups_pull={},this.relation_property=null,this.group_id=null,this.group_text=null},is_active:function(){return!!this.relation_property},generate_sections:function(t,e){for(var r=[],n=0;n<t.length;n++){var i=gantt.copy(t[n]);i.type=e,i.open=!0,i.$virtual=!0,i.readonly=!0,i[this.relation_id_property]=i[this.group_id],i.text=i[this.group_text],r.push(i)}return r},clear_temp_tasks:function(t){for(var e=0;e<t.length;e++)t[e].$virtual&&(t.splice(e,1),e--)},generate_data:function(t,e){var r=t.getLinks(),n=t.getTaskByTime();this.clear_temp_tasks(n);var i=[];this.is_active()&&e&&e.length&&(i=this.generate_sections(e,t.config.types.project));var a={links:r};return a.data=i.concat(n),a},update_settings:function(t,e,r){this.clear(),this.relation_property=t,this.group_id=e,this.group_text=r},group_tasks:function(t,e,r,n,i){this.update_settings(r,n,i);var a=this.generate_data(t,e);this.loaded=a.data.length,t._clear_data(),t.parse(a)}},gantt._groups.init(gantt),gantt.groupBy=function(t){var e=(t=t||{}).groups||null,r=t.relation_property||null,n=t.group_id||"key",i=t.group_text||"label";this._groups.group_tasks(this,e,r,n,i)}}});
//# sourceMappingURL=dhtmlxgantt_grouping.js.map