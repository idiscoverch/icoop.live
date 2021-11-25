/*
@license

dhtmlxGantt v.5.2.0 Professional
This software is covered by DHTMLX Commercial License. Usage without proper license is prohibited.

(c) Dinamenta, UAB.

*/!function(t){var e={};function n(a){if(e[a])return e[a].exports;var r=e[a]={i:a,l:!1,exports:{}};return t[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(a,r,function(e){return t[e]}.bind(null,r));return a},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/codebase/",n(n.s=208)}({17:function(t,e){t.exports=function(t){t._get_linked_task=function(e,n){var a=null,r=n?e.target:e.source;return t.isTaskExists(r)&&(a=t.getTask(r)),a},t._get_link_target=function(e){return t._get_linked_task(e,!0)},t._get_link_source=function(e){return t._get_linked_task(e,!1)};var e=!1,n={},a={},r={},i={};t._isLinksCacheEnabled=function(){return e},t._startLinksCache=function(){n={},a={},r={},i={},e=!0},t._endLinksCache=function(){n={},a={},r={},i={},e=!1},t._formatLink=function(a){if(e&&n[a.id])return n[a.id];var r=[],i=this._get_link_target(a),s=this._get_link_source(a);if(!s||!i)return r;if(t.isSummaryTask(i)&&t.isChildOf(s.id,i.id)||t.isSummaryTask(s)&&t.isChildOf(i.id,s.id))return r;for(var o=this._getImplicitLinks(a,s,function(t){return 0},!0),u=t.config.auto_scheduling_move_projects,g=this.isSummaryTask(i)?this.getSubtaskDates(i.id):{start_date:i.start_date,end_date:i.end_date},c=this._getImplicitLinks(a,i,function(e){return u?e.$target.length||t.getState().drag_id==e.id?0:t.calculateDuration({start_date:g.start_date,end_date:e.start_date,task:s}):0}),l=0,d=o.length;l<d;l++)for(var h=o[l],f=0,_=c.length;f<_;f++){var k=c[f],v=1*h.lag+1*k.lag,p={id:a.id,type:a.type,source:h.task,target:k.task,lag:(1*a.lag||0)+v};r.push(t._convertToFinishToStartLink(k.task,p,s,i,h.taskParent,k.taskParent))}return e&&(n[a.id]=r),r},t._isAutoSchedulable=function(t){return!1!==t.auto_scheduling},t._getImplicitLinks=function(e,n,a,r){var i=[];if(this.isSummaryTask(n)){var s,o={};for(var u in this.eachTask(function(t){this.isSummaryTask(t)||(o[t.id]=t)},n.id),o){var g=o[u],c=r?g.$source:g.$target;s=!1;for(var l=0;l<c.length;l++){var d=t.getLink(c[l]),h=r?d.target:d.source,f=o[h];if(f&&!1!==g.auto_scheduling&&!1!==f.auto_scheduling&&(d.target==f.id&&Math.abs(d.lag)<=f.duration||d.target==g.id&&Math.abs(d.lag)<=g.duration)){s=!0;break}}s||i.push({task:g.id,taskParent:g.parent,lag:a(g)})}}else i.push({task:n.id,taskParent:n.parent,lag:0});return i},t._getDirectDependencies=function(t,e){for(var n=[],a=[],r=e?t.$source:t.$target,i=0;i<r.length;i++){var s=this.getLink(r[i]);if(this.isTaskExists(s.source)&&this.isTaskExists(s.target)){var o=this.getTask(s.target);this._isAutoSchedulable(o)&&n.push(this.getLink(r[i]))}}for(i=0;i<n.length;i++)a=a.concat(this._formatLink(n[i]));return a},t._getInheritedDependencies=function(t,n){var i,s=!1,o=[];if(this.isTaskExists(t.id)){this.getParent(t.id);this.eachParent(function(t){var u;s||(e&&(i=n?a:r)[t.id]?o.push.apply(o,i[t.id]):this.isSummaryTask(t)&&(this._isAutoSchedulable(t)?(u=this._getDirectDependencies(t,n),e&&(i[t.id]=u),o.push.apply(o,u)):s=!0))},t.id,this)}return o},t._getDirectSuccessors=function(t){return this._getDirectDependencies(t,!0)},t._getInheritedSuccessors=function(t){return this._getInheritedDependencies(t,!0)},t._getDirectPredecessors=function(t){return this._getDirectDependencies(t,!1)},t._getInheritedPredecessors=function(t){return this._getInheritedDependencies(t,!1)},t._getSuccessors=function(t,e){var n=this._getDirectSuccessors(t);return e?n:n.concat(this._getInheritedSuccessors(t))},t._getPredecessors=function(t,n){var a,r=t.id+n;if(e&&i[r])return i[r];var s=this._getDirectPredecessors(t);return a=n?s:s.concat(this._getInheritedPredecessors(t)),e&&(i[r]=a),a},t._convertToFinishToStartLink=function(e,n,a,r,i,s){var o={target:e,link:t.config.links.finish_to_start,id:n.id,lag:n.lag||0,source:n.source,preferredStart:null,sourceParent:i,targetParent:s},u=0;switch(n.type){case t.config.links.start_to_start:u=-a.duration;break;case t.config.links.finish_to_finish:u=-r.duration;break;case t.config.links.start_to_finish:u=-a.duration-r.duration;break;default:u=0}return o.lag+=u,o}}},208:function(t,e,n){n(17)(gantt),gantt.config.auto_scheduling=!1,gantt.config.auto_scheduling_descendant_links=!1,gantt.config.auto_scheduling_initial=!0,gantt.config.auto_scheduling_strict=!1,gantt.config.auto_scheduling_move_projects=!0,function(){var t=n(5);function e(t,e,n){for(var a,r=[t],i=[],s={};r.length>0;)if(!n[a=r.shift()]){n[a]=!0,i.push(a);for(var o=0;o<e.length;o++){var u=e[o];u.source!=a||n[u.target]?u.target!=a||n[u.source]||(r.push(u.source),s[u.id]=!0,e.splice(o,1),o--):(r.push(u.target),s[u.id]=!0,e.splice(o,1),o--)}}var g=[];for(var o in s)g.push(o);return{tasks:i,links:g}}gantt._autoSchedulingGraph={getVertices:function(t){for(var e,n={},a=0,r=t.length;a<r;a++)n[(e=t[a]).target]=e.target,n[e.source]=e.source;var i,s=[];for(var a in n)i=n[a],s.push(i);return s},topologicalSort:function(t){for(var e=this.getVertices(t),n={},a=0,r=e.length;a<r;a++)n[e[a]]={id:e[a],$source:[],$target:[],$incoming:0};for(a=0,r=t.length;a<r;a++){var i=n[t[a].target];i.$target.push(a),i.$incoming=i.$target.length,n[t[a].source].$source.push(a)}for(var s=e.filter(function(t){return!n[t].$incoming}),o=[];s.length;){var u=s.pop();o.push(u);var g=n[u];for(a=0;a<g.$source.length;a++){var c=n[t[g.$source[a]].target];c.$incoming--,c.$incoming||s.push(c.id)}}return o},_groupEdgesBySource:function(t){for(var e,n={},a=0,r=t.length;a<r;a++)n[(e=t[a]).source]||(n[e.source]=[]),n[e.source].push(e);return n},tarjanStronglyConnectedComponents:function(t,e){for(var n,a={},r=0,i=[],s=[],o=[],u=this._groupEdgesBySource(e),g=0,c=t.length;g<c;g++){void 0===d(n=t[g]).index&&l(n)}function l(t,e){var n;(c=d(t)).index=r,c.lowLink=r,r++,e&&s.push(e),i.push(c),c.onStack=!0;for(var a=u[t],g=0;a&&g<a.length;g++)if((n=a[g]).source==t){var c=d(n.source);void 0===(h=d(n.target)).index?(l(n.target,n),c.lowLink=Math.min(c.lowLink,h.lowLink)):h.onStack&&(c.lowLink=Math.min(c.lowLink,h.index),s.push(n))}if(c.lowLink==c.index){var h,f={tasks:[],links:[]};do{var _=s.pop();(h=i.pop()).onStack=!1,f.tasks.push(h.id),_&&f.links.push(_.id)}while(h.id!=c.id);o.push(f)}}return o;function d(t){return a[t]||(a[t]={id:t,onStack:!1,index:void 0,lowLink:void 0}),a[t]}}},gantt._autoSchedulingPath={getKey:function(t){return t.lag+"_"+t.link+"_"+t.source+"_"+t.target},getVirtualRoot:function(){return gantt.mixin(gantt.getSubtaskDates(),{id:gantt.config.root_id,type:gantt.config.types.project,$source:[],$target:[],$virtual:!0})},filterDuplicates:function(t){for(var e={},n=0;n<t.length;n++){var a=this.getKey(t[n]);e[a]?(t.splice(n,1),n--):e[a]=!0}return t},getLinkedTasks:function(t,e){var n=[t],a=!1;gantt._isLinksCacheEnabled()||(gantt._startLinksCache(),a=!0);for(var r=[],i={},s=0;s<n.length;s++)this._getLinkedTasks(n[s],i,e);for(var s in i)r.push(i[s]);return a&&gantt._endLinksCache(),r},_getLinkedTasks:function(t,e,n,a){var r=void 0===t?gantt.config.root_id:t,i=e||{},s=gantt.isTaskExists(r)?gantt.getTask(r):this.getVirtualRoot(),o=gantt._getSuccessors(s,a),u=[];n&&(u=gantt._getPredecessors(s,a));for(var g,c=[],l=0;l<o.length;l++)i[g=this.getKey(o[l])]||(i[g]=o[l],c.push(o[l]));for(l=0;l<u.length;l++)i[g=this.getKey(u[l])]||(i[g]=u[l],c.push(u[l]));for(l=0;l<c.length;l++){var d=c[l].sourceParent==c[l].targetParent;this._getLinkedTasks(c[l].target,i,!0,d)}if(gantt.hasChild(s.id)){var h=gantt.getChildren(s.id);for(l=0;l<h.length;l++)this._getLinkedTasks(h[l],i,!0,!0)}return c},findLoops:function(e){var n=[];t.forEach(e,function(t){t.target==t.source&&n.push([t.target,t.source])});var a=gantt._autoSchedulingGraph,r=a.getVertices(e),i=a.tarjanStronglyConnectedComponents(r,e);return t.forEach(i,function(t){t.tasks.length>1&&n.push(t)}),n}},gantt._autoSchedulingDateResolver={isFirstSmaller:function(t,e,n){return!!(t.valueOf()<e.valueOf()&&gantt._hasDuration(t,e,n))},isSmallerOrDefault:function(t,e,n){return!(t&&!this.isFirstSmaller(t,e,n))},resolveRelationDate:function(t,e,n){for(var a,r=null,i=null,s=null,o=0;o<e.length;o++){var u=e[o];t=u.target,s=u.preferredStart,a=gantt.getTask(t);var g=this.getConstraintDate(u,n,a);this.isSmallerOrDefault(s,g,a)&&this.isSmallerOrDefault(r,g,a)&&(r=g,i=u.id)}return r&&(r=gantt.getClosestWorkTime({date:r,dir:"future",task:gantt.getTask(t)})),{link:i,task:t,start_date:r}},getConstraintDate:function(t,e,n){var a=e(t.source),r=n,i=gantt.getClosestWorkTime({date:a,dir:"future",task:r});return a&&t.lag&&1*t.lag==t.lag&&(i=gantt.calculateEndDate({start_date:a,duration:1*t.lag,task:r})),i}},gantt._autoSchedulingPlanner={generatePlan:function(t){for(var e,n,a=gantt._autoSchedulingGraph.topologicalSort(t),r={},i={},s=0,o=a.length;s<o;s++){e=a[s],!1!==(f=gantt.getTask(e)).auto_scheduling&&(r[e]=[],i[e]=null)}function u(t){var e=i[t],n=gantt.getTask(t);return e&&(e.start_date||e.end_date)?e.end_date?e.end_date:gantt.calculateEndDate({start_date:e.start_date,duration:n.duration,task:n}):n.end_date}for(s=0,o=t.length;s<o;s++)r[(n=t[s]).target]&&r[n.target].push(n);var g=gantt._autoSchedulingDateResolver,c=[];for(s=0;s<a.length;s++){var l=a[s],d=g.resolveRelationDate(l,r[l]||[],u);if(d.start_date&&gantt.isLinkExists(d.link)){var h=gantt.getLink(d.link),f=gantt.getTask(l),_=gantt.getTask(h.source);if(f.start_date.valueOf()!==d.start_date.valueOf()&&!1===gantt.callEvent("onBeforeTaskAutoSchedule",[f,d.start_date,h,_]))continue}i[l]=d,d.start_date&&c.push(d)}return c},applyProjectPlan:function(t){for(var e,n,a,r,i=[],s=0;s<t.length;s++)if(a=null,r=null,(e=t[s]).task){n=gantt.getTask(e.task),e.link&&(a=gantt.getLink(e.link),r=gantt.getTask(a.source));var o=null;e.start_date&&n.start_date.valueOf()!=e.start_date.valueOf()&&(o=e.start_date),o&&(n.start_date=o,n.end_date=gantt.calculateEndDate(n),i.push(n.id),gantt.callEvent("onAfterTaskAutoSchedule",[n,o,a,r]))}return i}},gantt._autoSchedulingPreferredDates=function(t,e){for(var n=0;n<e.length;n++){var a=e[n],r=gantt.getTask(a.target);gantt.config.auto_scheduling_strict&&a.target!=t||(a.preferredStart=new Date(r.start_date))}},gantt._autoSchedule=function(t,e,n){if(!1!==gantt.callEvent("onBeforeAutoSchedule",[t])){gantt._autoscheduling_in_progress=!0;var a=[],r=gantt._autoSchedulingPath.findLoops(e);if(r.length)gantt.callEvent("onAutoScheduleCircularLink",[r]);else{var i=gantt._autoSchedulingPlanner;gantt._autoSchedulingPreferredDates(t,e);var s=i.generatePlan(e);a=i.applyProjectPlan(s),n&&n(a)}return gantt._autoscheduling_in_progress=!1,gantt.callEvent("onAfterAutoSchedule",[t,a]),a}},gantt.autoSchedule=function(t,e){e=void 0===e||!!e;var n=gantt._autoSchedulingPath.getLinkedTasks(t,e),a=(n.length,Date.now());gantt._autoSchedule(t,n,gantt._finalizeAutoSchedulingChanges);Date.now()},gantt._finalizeAutoSchedulingChanges=function(t){var e=!1;function n(){for(var e=0;e<t.length;e++)gantt.updateTask(t[e])}1==t.length?gantt.eachParent(function t(n){if(!e){var a=n.start_date.valueOf(),r=n.end_date.valueOf();if(gantt.resetProjectDates(n),n.start_date.valueOf()==a&&n.end_date.valueOf()==r)for(var i=gantt.getChildren(n.id),s=0;!e&&s<i.length;s++)t(gantt.getTask(i[s]));else e=!0}},t[0]):t.length&&(e=!0),e?gantt.batchUpdate(n):n()},gantt.isCircularLink=function(t){return!!gantt._getConnectedGroup(t)},gantt._getConnectedGroup=function(t){var e=gantt._autoSchedulingPath,n=e.getLinkedTasks();gantt.isLinkExists(t.id)||(n=n.concat(gantt._formatLink(t)));for(var a=e.findLoops(n),r=0;r<a.length;r++)for(var i=a[r].links,s=0;s<i.length;s++)if(i[s]==t.id)return a[r];return null},gantt.findCycles=function(){var t=gantt._autoSchedulingPath,e=t.getLinkedTasks();return t.findLoops(e)},gantt._attachAutoSchedulingHandlers=function(){var t,e;gantt._autoScheduleAfterLinkChange=function(t,e){gantt.config.auto_scheduling&&!this._autoscheduling_in_progress&&gantt.autoSchedule(e.source)},gantt.attachEvent("onAfterLinkUpdate",gantt._autoScheduleAfterLinkChange),gantt.attachEvent("onAfterLinkAdd",gantt._autoScheduleAfterLinkChange),gantt.attachEvent("onAfterLinkDelete",function(t,e){if(this.config.auto_scheduling&&!this._autoscheduling_in_progress&&this.isTaskExists(e.target)){var n=this.getTask(e.target),a=this._getPredecessors(n);a.length&&this.autoSchedule(a[0].source,!1)}}),gantt.attachEvent("onParse",function(){gantt.config.auto_scheduling&&gantt.config.auto_scheduling_initial&&gantt.autoSchedule()}),gantt._preventCircularLink=function(t,e){return!gantt.isCircularLink(e)||(gantt.callEvent("onCircularLinkError",[e,gantt._getConnectedGroup(e)]),!1)},gantt._preventDescendantLink=function(t,e){var n=gantt.getTask(e.source),a=gantt.getTask(e.target);return!(!gantt.config.auto_scheduling_descendant_links&&(gantt.isChildOf(n.id,a.id)&&gantt.isSummaryTask(a)||gantt.isChildOf(a.id,n.id)&&gantt.isSummaryTask(n)))},gantt.attachEvent("onBeforeLinkAdd",gantt._preventCircularLink),gantt.attachEvent("onBeforeLinkAdd",gantt._preventDescendantLink),gantt.attachEvent("onBeforeLinkUpdate",gantt._preventCircularLink),gantt.attachEvent("onBeforeLinkUpdate",gantt._preventDescendantLink),gantt._datesNotEqual=function(t,e,n,a){return t.valueOf()>e.valueOf()?this._hasDuration({start_date:e,end_date:t,task:a}):this._hasDuration({start_date:t,end_date:e,task:n})},gantt._notEqualTaskDates=function(t,e){if(this._datesNotEqual(t.start_date,e.start_date,t,e)||(this._datesNotEqual(t.end_date,e.end_date,t,e)||t.duration!=e.duration)&&t.type!=gantt.config.types.milestone)return!0},gantt.attachEvent("onBeforeTaskDrag",function(n,a,r){return gantt.config.auto_scheduling&&gantt.config.auto_scheduling_move_projects&&(t=gantt._autoSchedulingPath.getLinkedTasks(n,!0),e=n),!0}),gantt._autoScheduleAfterDND=function(n,a){if(gantt.config.auto_scheduling&&!this._autoscheduling_in_progress){var r=this.getTask(n);gantt._notEqualTaskDates(a,r)&&(gantt.config.auto_scheduling_move_projects&&e==n?(gantt.calculateDuration(a)!=gantt.calculateDuration(r)&&function(t,e){for(var n=!1,a=0;a<e.length;a++){var r=gantt.getLink(e[a].id);r.type!=gantt.config.links.start_to_start&&r.type!=gantt.config.links.start_to_finish||(e.splice(a,1),a--,n=!0)}if(n){var i={};for(a=0;a<e.length;a++)i[e[a].id]=!0;var s=gantt._autoSchedulingPath.getLinkedTasks(t,!0);for(a=0;a<s.length;a++)i[s[a].id]||e.push(s[a])}}(n,t),gantt._autoSchedule(n,t,gantt._finalizeAutoSchedulingChanges)):gantt.autoSchedule(r.id))}return t=null,e=null,!0},gantt._lightBoxChangesHandler=function(t,e){if(gantt.config.auto_scheduling&&!this._autoscheduling_in_progress){var n=this.getTask(t);gantt._notEqualTaskDates(e,n)&&(gantt._autoschedule_lightbox_id=t)}return!0},gantt._lightBoxSaveHandler=function(t,e){return gantt.config.auto_scheduling&&!this._autoscheduling_in_progress&&gantt._autoschedule_lightbox_id&&gantt._autoschedule_lightbox_id==t&&(gantt._autoschedule_lightbox_id=null,gantt.autoSchedule(e.id)),!0},gantt.attachEvent("onBeforeTaskChanged",function(t,e,n){return gantt._autoScheduleAfterDND(t,n)}),gantt.attachEvent("onLightboxSave",gantt._lightBoxChangesHandler),gantt.attachEvent("onAfterTaskUpdate",gantt._lightBoxSaveHandler)},gantt.attachEvent("onGanttReady",function(){gantt._attachAutoSchedulingHandlers(),gantt._attachAutoSchedulingHandlers=function(){}}),gantt.getConnectedGroup=function(t){var n=gantt._autoSchedulingPath.getLinkedTasks();return void 0!==t?gantt.getTask(t).type==gantt.config.types.project?{tasks:[],links:[]}:e(t,n,{}):function(t){for(var n,a,r,i={},s=[],o=0;o<t.length;o++)n=t[o].source,a=t[o].target,r=null,i[n]?i[a]||(r=a):r=n,r&&s.push(e(r,t,i));return s}(n)}}()},5:function(t,e){var n={second:1,minute:60,hour:3600,day:86400,week:604800,month:2592e3,quarter:7776e3,year:31536e3};t.exports={getSecondsInUnit:function(t){return n[t]||n.hour},forEach:function(t,e){if(t.forEach)t.forEach(e);else for(var n=t.slice(),a=0;a<n.length;a++)e(n[a],a)},arrayMap:function(t,e){if(t.map)return t.map(e);for(var n=t.slice(),a=[],r=0;r<n.length;r++)a.push(e(n[r],r));return a}}}});
//# sourceMappingURL=dhtmlxgantt_auto_scheduling.js.map