/*  protocalendar.js
 *  (c) 2008 Spookies
 *  License : MIT-style license.
 *  Web site: http://labs.spookies.jp
 *  protocalendar.js - depends on prototype.js 1.5 or later
 *  http://www.prototypejs.org/
 */
var ProtoCalendar=Class.create();ProtoCalendar.Version="1.1.4";ProtoCalendar.LangFile=new Object();ProtoCalendar.LangFile["en"]={HOUR_MINUTE_ERROR:"The time is not valid.",NO_DATE_ERROR:"No day has been selected.",OK_LABEL:"OK",DEFAULT_FORMAT:"mm/dd/yyyy",LABEL_FORMAT:"ddd mm/dd/yyyy",MONTH_ABBRS:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],MONTH_NAMES:["January","February","March","April","May","June","July","August","September","October","November","December"],YEAR_LABEL:" ",MONTH_LABEL:" ",WEEKDAY_ABBRS:["Sun","Mon","Tue","Wed","Thr","Fri","Sat"],WEEKDAY_NAMES:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],YEAR_AND_MONTH:false};ProtoCalendar.LangFile.defaultLang="en";ProtoCalendar.LangFile.defaultLangFile=function(){return ProtoCalendar.LangFile[defaultLang]};Object.extend(ProtoCalendar,{JAN:0,FEB:1,MAR:2,APR:3,MAY:4,JUNE:5,JULY:6,AUG:7,SEPT:8,OCT:9,NOV:10,DEC:11,SUNDAY:0,MONDAY:1,TUESDAY:2,WEDNESDAY:3,THURSDAY:4,FRIDAY:5,SATURDAY:6,getNumDayOfMonth:function(A,B){return 32-new Date(A,B,32).getDate()},getDayOfWeek:function(B,C,A){return new Date(B,C,A).getDay()}});ProtoCalendar.prototype={initialize:function(C){var B=new Date();this.options=Object.extend({month:B.getMonth(),year:B.getFullYear(),lang:ProtoCalendar.LangFile.defaultLang},C||{});var A=ProtoCalendar.LangFile[this.options.lang]["getHolidays"];if(A){this.initializeHolidays=A.bind(top,this)}else{this.initializeHolidays=function(){this.holidays=[]}}this.date=new Date(this.options.year,this.options.month,1)},getMonth:function(){return this.date.getMonth()},getYear:function(){return this.date.getFullYear()},invalidate:function(){this.holidays=undefined},setMonth:function(A){if(A!=this.getMonth()){this.invalidate()}return this.date.setMonth(A)},setYear:function(A){if(A!=this.getYear()){this.invalidate()}return this.date.setFullYear(A)},getDate:function(){return this.date},setDate:function(A){this.invalidate();this.date=A},setYearByOffset:function(A){if(A!=0){this.invalidate()}this.date.setFullYear(this.date.getFullYear()+A)},setMonthByOffset:function(A){if(A!=0){this.invalidate()}this.date.setMonth(this.date.getMonth()+A)},getNumDayOfMonth:function(){return ProtoCalendar.getNumDayOfMonth(this.getYear(),this.getMonth())},getDayOfWeek:function(A){return ProtoCalendar.getDayOfWeek(this.getYear(),this.getMonth(),A)},clone:function(){return new ProtoCalendar({year:this.getYear(),month:this.getMonth()})},getHoliday:function(A){if(!this.holidays){this.initializeHolidays()}var B=this.holidays[A];return B?B:false},initializeHolidays:function(){}};var AbstractProtoCalendarRender=Class.create();Object.extend(AbstractProtoCalendarRender,{id:1,WEEK_DAYS_SUNDAY:[0,1,2,3,4,5,6],WEEK_DAYS_MONDAY:[1,2,3,4,5,6,0],WEEK_DAYS_INDEX_SUNDAY:[0,1,2,3,4,5,6],WEEK_DAYS_INDEX_MONDAY:[6,0,1,2,3,4,5],getId:function(){var A=AbstractProtoCalendarRender.id;AbstractProtoCalendarRender.id+=1;return A}});AbstractProtoCalendarRender.prototype={initialize:function(A){this.id=AbstractProtoCalendarRender.getId();this.options=Object.extend({weekFirstDay:ProtoCalendar.MONDAY,containerClass:"cal-container",tableClass:"cal-table",headerTopClass:"cal-header-top",headerClass:"cal-header",headerBottomClass:"cal-header-bottom",bodyTopClass:"cal-body-top",bodyClass:"cal-body",bodyBottomClass:"cal-body-bottom",bodyId:this.getIdPrefix()+"-body",footerTopClass:"cal-footer-top",footerClass:"cal-footer",footerBottomClass:"cal-footer-bottom",footerId:this.getIdPrefix()+"-footer",yearSelectClass:"cal-select-year",yearSelectId:this.getIdPrefix()+"-select-year",monthSelectClass:"cal-select-month",monthSelectId:this.getIdPrefix()+"-select-month",borderClass:"cal-border",hourMinuteInputClass:"cal-input-hour-minute",hourMinuteInputId:this.getIdPrefix()+"-input-hour-minute",hourInputClass:"cal-input-hour",hourInputId:this.getIdPrefix()+"-input-hour",minuteInputClass:"cal-input-minute",minuteInputId:this.getIdPrefix()+"-input-minute",secondInputClass:"cal-input-second",secondInputId:this.getIdPrefix()+"-input-second",okButtonClass:"cal-ok-button",okButtonId:this.getIdPrefix()+"-ok-button",errorDivClass:"cal-error-list",errorDivId:this.getIdPrefix()+"-error-list",labelRowClass:"cal-label-row",labelCellClass:"cal-label-cell",nextButtonClass:"cal-next-btn",prevButtonClass:"cal-prev-btn",dayCellClass:"cal-day-cell",dayClass:"cal-day",weekdayClass:"cal-weekday",sundayClass:"cal-sunday",saturdayClass:"cal-saturday",holidayClass:"cal-holiday",otherdayClass:"cal-otherday",selectedDayClass:"cal-selected",nextBtnId:this.getIdPrefix()+"-next-btn",prevBtnId:this.getIdPrefix()+"-prev-btn",lang:ProtoCalendar.LangFile.defaultLang,showEffect:"Appear",hideEffect:"Fade"},A||{});this.langFile=ProtoCalendar.LangFile[this.options.lang];this.weekFirstDay=this.options.weekFirstDay;this.initWeekData();this.container=this.createContainer();this.alignTo=$(this.options.alignTo);if(navigator.appVersion.match(/\bMSIE\b/)){this.iframe=this.createIframe()}},createContainer:function(){var A=$(document.createElement("div"));A.addClassName(this.options.containerClass);A.setStyle({position:"absolute",top:"0px",left:"0px",zindex:1,display:"none"});A.hide();document.body.appendChild(A);return A},createIframe:function(){var A=document.createElement("iframe");A.setAttribute("src","javascript:false;");A.setAttribute("frameBorder","0");A.setAttribute("scrolling","no");Element.setStyle(A,{position:"absolute",top:"0px",left:"0px",zindex:10,display:"none",overflow:"hidden",filter:"progid:DXImageTransform.Microsoft.Alpha(opacity=0)"});document.body.appendChild(A);return $(A)},getWeekdayLabel:function(A){return this.langFile.WEEKDAY_ABBRS[A]},getWeekdays:function(){return this.weekdays},initWeekData:function(){if(this.weekFirstDay==ProtoCalendar.SUNDAY){this.weekLastDay=ProtoCalendar.SATURDAY;this.weekdays=AbstractProtoCalendarRender.WEEK_DAYS_SUNDAY;this.weekdaysIndex=AbstractProtoCalendarRender.WEEK_DAYS_INDEX_SUNDAY}else{this.weekFirstDay==ProtoCalendar.MONDAY;this.weekLastDay=ProtoCalendar.SUNDAY;this.weekdays=AbstractProtoCalendarRender.WEEK_DAYS_MONDAY;this.weekdaysIndex=AbstractProtoCalendarRender.WEEK_DAYS_INDEX_MONDAY}},getCalendarBeginDay:function(B){var C=this.getDayIndexOfWeek(B,1);var A=new Date(B.getYear(),B.getMonth(),1-C);return A},getCalendarEndDay:function(C){var A=C.getNumDayOfMonth();var D=6-this.getDayIndexOfWeek(C,A);var B=new Date(C.getYear(),C.getMonth(),A+D+1);return B},getDayIndexOfWeek:function(B,A){return this.weekdaysIndex[B.getDayOfWeek(A)]},getIdPrefix:function(){return"cal"+this.id},getDayDivId:function(A){return this.getIdPrefix()+"-year"+A.getFullYear()+"-month"+A.getMonth()+"-day"+A.getDate()},setPosition:function(){if(!this.alignTo){return }setAlignment(this.alignTo,this.container);if(this.iframe){var A=Element.getDimensions(this.container);this.iframe.setAttribute("width",A.width);this.iframe.setAttribute("height",A.height);setAlignment(this.alignTo,this.iframe)}},show:function(B){this.setPosition();if(typeof Effect!="undefined"){var A=this.options["showEffect"]||"Appear";if(!this._effect||this._effect.state=="finished"){this._effect=new Effect[A](this.container,{duration:0.5})}}else{this.container.show()}if(this.iframe){this.iframe.show()}},hide:function(B){if(!this.container.visible()){return }if(typeof Effect!="undefined"){var A=this.options["hideEffect"]||"Fade";if(!this._effect||this._effect.state=="finished"){this._effect=new Effect[A](this.container,{duration:0.3})}}else{this.container.hide()}if(this.iframe){this.iframe.hide()}},toggle:function(A){this.container.visible()?this.hide():this.show()},render:function(A){},rerender:function(A){},getContainer:function(){return this.container},getPrevButton:function(){return $(this.options.prevBtnId)},getNextButton:function(){return $(this.options.nextBtnId)},getYearSelect:function(){return $(this.options.yearSelectId)},getMonthSelect:function(){return $(this.options.monthSelectId)},getHourInput:function(){return $(this.options.hourInputId)},getMinuteInput:function(){return $(this.options.minuteInputId)},getSecondInput:function(){return $(this.options.secondInputId)},getOkButton:function(){return $(this.options.okButtonId)},getBody:function(){return $(this.options.bodyId)},getDayDivs:function(){var B=[];var A=this.dayDivs;for(var C=0;C<A.length;C++){B.push(document.getElementById(A[C]))}return B},getDateFromEl:function(B){var A=$(B);return new Date(A.readAttribute("year"),A.readAttribute("month"),A.readAttribute("day"))},injectHourMinute:function(A){if(!A||isNaN(A.getTime())){return undefined}var B=new Date(A.getFullYear(),A.getMonth(),A.getDate());B.setHours(parseInt(this.getHourInput().value,10));B.setMinutes(this.getMinuteInput().value);if(this.options.enableSecond){B.setSeconds(this.getSecondInput().value)}return isNaN(B.getTime())?undefined:B},selectDate:function(A){var B=$(this.getDayDivId(A));if(B){B.addClassName(this.options.selectedDayClass)}},selectTime:function(A){if(!A){return }this.getHourInput().value=A.getHours();this.getMinuteInput().value=A.getMinutes();if(this.options.enableSecond){this.getSecondInput().value=A.getSeconds()}},deselectDate:function(B){if(B){var A=$(this.getDayDivId(B));if(A){A.removeClassName(this.options.selectedDayClass)}}},evaluateWithOptions:function(A){var B=new Template(A);return B.evaluate(this.options)},defaultOnError:function(A){this.ensureErrorDiv();this.errorDiv.show();this.errorDiv.innerHTML+="<li>"+this.langFile[A]+"</li>"},hideError:function(){this.ensureErrorDiv();this.errorDiv.innerHTML="";this.errorDiv.hide()},ensureErrorDiv:function(){if(!this.errorDiv){var A='<div class="#{errorDivClass}" id="#{errorDivId}"><ul></ul></div>';new Insertion.Before($(this.options.footerId),this.evaluateWithOptions(A));this.errorDiv=$(this.options.errorDivId)}}};var ProtoCalendarRender=Class.create();Object.extend(ProtoCalendarRender.prototype,AbstractProtoCalendarRender.prototype);Object.extend(ProtoCalendarRender.prototype,{render:function(B){var A="";A+=this.renderHeader(B);A+='<div class="#{bodyTopClass}"></div><div class="#{bodyClass}" id="#{bodyId}">';A+='</div><div class="#{bodyBottomClass}"></div>';if(this.options.enableHourMinute){A+=this.renderHourMinute()}A+=this.renderFooter(B);this.container.innerHTML=this.evaluateWithOptions(A);this.rerender(B)},rerender:function(A){this.getBody().innerHTML=this.evaluateWithOptions(this.renderBody(A));selectOption(this.getMonthSelect(),A.getMonth());selectOption(this.getYearSelect(),A.getYear());if(this.container.visible()){this.setPosition()}},renderHeader:function(B){var A="";A+='<div class="#{headerTopClass}"></div><div class="#{headerClass}"><a href="javascript:void(0);" id="#{prevBtnId}" class="#{prevButtonClass}">&lt;&lt;</a>'+this.createSelect(B.getYear(),B.getMonth())+'<a href="javascript:void(0);" id="#{nextBtnId}" class="#{nextButtonClass}">&gt;&gt;</a></div><div class="#{headerBottomClass}"></div>';return A},renderFooter:function(A){return'<div class="#{footerTopClass}"></div><div class="#{footerClass}" id="#{footerId}"></div><div class="#{footerBottomClass}"></div>'},renderHourMinute:function(){if(!this.hourMinuteHtml){var A='<div class="#{borderClass}"></div><div id="#{hourMinuteInputId}" class="#{hourMinuteInputClass}">';A+='<input type="text" name="hour" size="2" maxlength="2" class="#{hourInputClass}" id="#{hourInputId}" />:<input type="text" name="minute" size="2" maxlength="2" class="#{minuteInputClass}" id="#{minuteInputId}" />';if(this.options.enableSecond){A+=':<input type="text" name="second" size="2" maxlength="2" class="#{secondInputClass}" id="#{secondInputId}"/>'}A+='<input type="button" value="'+this.langFile["OK_LABEL"]+'" class="#{okButtonClass}" name="ok_button" id="#{okButtonId}"/>';A+="</div>";this.hourMinuteHtml=A}return this.hourMinuteHtml},createSelect:function(C,D){var B=this.createYearSelect(C)+this.langFile.YEAR_LABEL;var A=this.createMonthSelect(D)+this.langFile.MONTH_LABEL;if(this.langFile.YEAR_AND_MONTH){return B+A}else{return A+B}},createYearSelect:function(B){var A="";A+='<select id="#{yearSelectId}" class="#{yearSelectClass}">';for(var D=this.options.startYear,C=this.options.endYear;D<=C;D+=1){A+='<option value="'+D+'"'+(D==B?" selected":"")+">"+D+"</option>"}A+="</select>";return A},createMonthSelect:function(C){if(!this.monthSelectHtml){var B="";B+='<select id="#{monthSelectId}" class="#{monthSelectClass}">';for(var A=ProtoCalendar.JAN;A<=ProtoCalendar.DEC;A+=1){B+='<option value="'+A+'"'+(A==C?" selected":"")+">"+this.langFile["MONTH_ABBRS"][A]+"</option>"}B+="</select>";this.monthSelectHtml=B}return this.monthSelectHtml},renderBody:function(F){this.dayDivs=[];var H='<table class="#{tableClass}" cellspacing="0">';H+='<tr class="#{labelRowClass}">';var B=this;if(!this.headHtml){this.headHtml="";$A(this.getWeekdays()).each(function(L){var K="";if(L==ProtoCalendar.SUNDAY){K=" #{sundayClass}"}if(L==ProtoCalendar.SATURDAY){K=" #{saturdayClass}"}B.headHtml+='<th class="#{labelCellClass}'+K+'">'+B.getWeekdayLabel(L)+"</th>"})}H+=this.headHtml;var D=this.getCalendarBeginDay(F);var A=this.getCalendarEndDay(F);H+="<tbody>";var C=Math.round((A-D)/1000/60/60/24);for(var G=0;G<C;G+=1,D.setDate(D.getDate()+1)){var I;var E=F.getHoliday(D.getDate());if(D.getMonth()!=F.getMonth()){I=this.options.otherdayClass}else{if(E){I=this.options.holidayClass}else{if(D.getDay()==ProtoCalendar.SUNDAY){I=this.options.sundayClass}else{if(D.getDay()==ProtoCalendar.SATURDAY){I=this.options.saturdayClass}else{I=this.options.weekdayClass}}}}if(D.getDay()==this.weekFirstDay){H+="<tr>"}var J=this.getDayDivId(D);H+='<td class="'+I+' #{dayCellClass}"><a class="#{dayClass}" href="javascript:void(0)" id="'+J+(E?'" title="'+E:"")+'" year="'+D.getFullYear()+'" month="'+D.getMonth()+'" day="'+D.getDate()+'">'+D.getDate()+"</a>";if(D.getDay()==this.weekLastDay){H+="</tr>"}this.dayDivs.push(J)}H+="</tbody></table>";return H}});var ProtoCalendarController=Class.create();ProtoCalendarController.prototype={initialize:function(C,B){this.options=Object.extend({onHourMinuteError:this.defaultOnHourMinuteError.bind(this),onNoDateError:this.defaultOnNoDateError.bind(this)},B);this.calendarRender=C;this.initializeDate();this.calendar=new ProtoCalendar(this.options);this.calendarRender.render(this.calendar);if(B.year&&B.month&&B.day){var A=new Date(this.options.year,this.options.month,this.options.day);if(B.hour&&B.minute&&B.second){A.setHours(B.hour,B.minute&&B.second)}this.selectDate(A,true)}else{this.selectDate(null)}this.observeEventsOnce();this.observeEvents();this.onChangeHandlers=[]},initializeDate:function(){var A=new Date();if(!this.options.year){if(A.getFullYear()>=this.options.startYear&&A.getFullYear()<=this.options.endYear){this.options.year=A.getFullYear()}else{this.options.year=this.options.startYear}}if(!this.options.month){this.options.month=A.getMonth()}if(!this.options.day){this.options.day=A.getDate()}},observeEventsOnce:function(){var G=this.calendarRender;G.getPrevButton().observe("click",this.showPrevMonth.bindAsEventListener(this));G.getNextButton().observe("click",this.showNextMonth.bindAsEventListener(this));var C=this;var I=G.getYearSelect();var D=G.getMonthSelect();var J=this.calendar.getYear();var H=this.calendar.getMonth();I.observe("change",function(){C.setMonth(parseInt(I[I.selectedIndex].value,10),parseInt(D[D.selectedIndex].value,10))});D.observe("change",function(){C.setMonth(parseInt(I[I.selectedIndex].value,10),parseInt(D[D.selectedIndex].value,10))});if(this.options.enableHourMinute){var F=G.getHourInput();var E=G.getMinuteInput();F.observe("keyup",this._autoFocus.bindAsEventListener(F,E));F.observe("keydown",this._disablePaste.bindAsEventListener(F));F.observe("contextmenu",this._disableContextMenu.bindAsEventListener(F));var B=this.options.enableSecond?G.getSecondInput():G.getOkButton();E.observe("keyup",this._autoFocus.bindAsEventListener(E,B));E.observe("keydown",this._disablePaste.bindAsEventListener(E));E.observe("contextmenu",this._disableContextMenu.bindAsEventListener(E));if(navigator.appVersion.match(/\bMSIE\b/)){F.setStyle({"imeMode":"disabled"});E.setStyle({"imeMode":"disabled"})}}if(this.options.enableSecond){var A=G.getSecondInput();A.observe("keyup",this._autoFocus.bindAsEventListener(A,G.getOkButton()));A.observe("keydown",this._disablePaste.bindAsEventListener(A));A.observe("contextmenu",this._disableContextMenu.bindAsEventListener(A));if(navigator.appVersion.match(/\bMSIE\b/)){A.setStyle({"imeMode":"disabled"})}}if(this.options.enableHourMinute){G.getOkButton().observe("click",this.onSubmit.bind(this))}},_disableContextMenu:function(A){Event.stop(A);return false},_disablePaste:function(A){if((A.keyCode==86&&A.ctrlKey)||(A.keyCode==45&&A.shiftKey)){Event.stop(A);return false}},_autoFocus:function(C,A){if(C.keyCode==16||C.keyCode==9||(C.keyCode==9&&C.shiftKey)){Event.stop(C);return false}var B=this.value;if(B.length&&B.length==2){A.focus();A.select()}return true},observeEvents:function(){var A=this;this.calendarRender.getDayDivs().each(function(B){Event.observe(B,"click",A.onClickHandler.bindAsEventListener(A))})},onClickHandler:function(B){var A=this.calendarRender.getDateFromEl(Event.element(B));if(A){this.selectDate(A);if(!this.options.enableHourMinute){this.onChangeHandler();setTimeout(this.hideCalendar.bind(this),150)}}},onSubmit:function(){this.hideError();var A=this.selectedDate;if(!A){return this.options.onNoDateError()}A=this.calendarRender.injectHourMinute(A);if(!A){this.options.onHourMinuteError()}else{this.selectDate(A,true);if(this.options.enableHourMinute){this.calendarRender.selectTime(A)}this.onChangeHandler();this.hideCalendar()}},selectDate:function(A,B){this.calendarRender.deselectDate(this.selectedDate);this.selectedDate=A;if(!A){return }if(B&&(A.getFullYear()!=this.calendar.getYear()||A.getMonth()!=this.calendar.getMonth())){this.setMonth(A.getFullYear(),A.getMonth())}this.calendarRender.selectDate(this.selectedDate)},getSelectedDate:function(){return this.selectedDate},addChangeHandler:function(A){this.onChangeHandlers.push(A)},onChangeHandler:function(){this.onChangeHandlers.each(function(A){A()})},showCalendar:function(){this.calendarRender.show()},hideCalendar:function(){this.calendarRender.hide()},toggleCalendar:function(){this.calendarRender.toggle()},showPrevMonth:function(A){this.shiftMonthByOffset(-1);if(A){Event.stop(A)}},showNextMonth:function(A){this.shiftMonthByOffset(1);if(A){Event.stop(A)}},shiftMonthByOffset:function(A){if(A==0){return }this.calendar.setMonthByOffset(A);this.afterSet()},setMonth:function(A,B){if(this.calendar.getYear()==A&&this.calendar.getMonth()==B){return }this.calendar.setYear(A);this.calendar.setMonth(B);this.afterSet()},afterSet:function(){this.calendarRender.rerender(this.calendar);this.selectDate(this.selectedDate);this.observeEvents()},getContainer:function(){return this.calendarRender.getContainer()},defaultOnHourMinuteError:function(){this.calendarRender.defaultOnError("HOUR_MINUTE_ERROR")},defaultOnNoDateError:function(){this.calendarRender.defaultOnError("NO_DATE_ERROR")},hideError:function(){this.calendarRender.hideError()}};var BaseCalendar=Class.create();BaseCalendar.bindOnLoad=function(A){if(document.observe){document.observe("dom:loaded",A)}else{Event.observe(window,"load",A)}};BaseCalendar.prototype={initialize:function(A){throw"Cannot instantiate BaseCalendar."},initializeOptions:function(A){if(!A){A={}}this.options=Object.extend({startYear:new Date().getFullYear()-10,endYear:new Date().getFullYear()+10,format:ProtoCalendar.LangFile[A.lang||ProtoCalendar.LangFile.defaultLang]["DEFAULT_FORMAT"],enableHourMinute:false,enableSecond:false,lang:ProtoCalendar.LangFile.defaultLang,triggers:[]},A)},initializeBase:function(){this.calendarController=new ProtoCalendarController(new ProtoCalendarRender(this.options),this.options);this.langFile=ProtoCalendar.LangFile[this.options.lang]||ProtoCalendar.LangFile.defaultLangFile();this.triggers=[];this.options.triggers.each(this.addTrigger.bind(this));this.changeHandlers=[];this.observeEvents()},addTrigger:function(A){this.triggers.push($(A));$(A).setStyle({"cursor":"pointer"})},observeEvents:function(){Event.observe(document,"click",this.windowClickHandler.bindAsEventListener(this));this.calendarController.addChangeHandler(this.onCalendarChange.bind(this));this.doObserveEvents()},doObserveEvents:function(){},windowClickHandler:function(A){var B=$(Event.element(A));if(this.triggers.include(B)){this.calendarController.toggleCalendar()}else{if(B!=this.input&&!Element.descendantOf(B,this.calendarController.getContainer())){this.calendarController.hideCalendar()}}},addChangeHandler:function(A){this.changeHandlers.push(A)},onCalendarChange:function(){this.changeHandlers.each(function(A){A()})}};var InputCalendar=Class.create();InputCalendar.createOnLoaded=function(A,B){BaseCalendar.bindOnLoad(function(){new InputCalendar(A,B)})};InputCalendar.initCalendars=function(A,B){if(document.observe){document.observe("dom:loaded",function(){$$(A).each(function(C){new InputCalendar(C,B)})})}else{Event.observe(window,"load",function(){$$(A).each(function(C){new InputCalendar(C,B)})})}};Object.extend(InputCalendar.prototype,BaseCalendar.prototype);Object.extend(InputCalendar.prototype,{initialize:function(A,B){this.input=$(A);this.initializeOptions(B);this.options=Object.extend({alignTo:A,inputReadOnly:false,labelFormat:undefined,labelEl:undefined},this.options);this.initializeBase();this.initializeInput();this.initializeLabel()},initializeInput:function(){this.dateFormat=new ProtoCalendar.DateFormat(this.options.format);if(this.input.value&&this.dateFormat.parse(this.input.value)){this.onInputChange()}else{this.onCalendarChange()}if(this.options.enableHourMinute){this.calendarController.calendarRender.selectTime(this.calendarController.selectedDate)}if(this.options.inputReadOnly){this.input.setAttribute("readOnly",this.options.inputReadOnly)}},initializeLabel:function(){this.labelFormat=new ProtoCalendar.DateFormat(this.options.labelFormat||this.langFile["LABEL_FORMAT"]);var B=$(this.options.labelEl);if((!B)&&this.options.labelFormat){var A=this.input.id+"_label";new Insertion.After(this.input,"<div id='"+A+"'></div>");B=$(A)}this.labelEl=B;this.changeLabel()},changeLabel:function(){if(!this.labelEl){return }if(this.calendarController.getSelectedDate()){this.labelEl.innerHTML=this.labelFormat.format(this.calendarController.getSelectedDate(),this.options.lang)}},doObserveEvents:function(){this.input.observe("change",this.onInputChange.bind(this));this.input.observe("focus",this.calendarController.showCalendar.bind(this.calendarController));this.addChangeHandler(this.changeInputValue.bind(this));this.addChangeHandler(this.changeLabel.bind(this))},onInputChange:function(){var B=this.dateFormat.parse(this.input.value);if(B){this.calendarController.selectDate(B,true)}else{var A=this.input.value.toLowerCase();var B;if(this.langFile["today"]&&this.langFile["today"]==A||A=="today"){B=new Date()}else{if(this.langFile["tomorrow"]&&this.langFile["tomorrow"]==A||A=="tomorrow"){B=new Date();B.setDate(B.getDate()+1)}else{if(this.langFile["yesterday"]&&this.langFile["yesterday"]==A||A=="yesterday"){B=new Date();B.setDate(B.getDate()-1)}else{if(this.langFile.parseDate&&(B=this.langFile.parseDate(A))){}else{B=undefined}}}}this.calendarController.selectDate(B,true);this.onCalendarChange()}this.changeLabel()},changeInputValue:function(){this.input.value=this.dateFormat.format(this.calendarController.getSelectedDate(),this.options.lang)}});function setAlignment(C,A){var B=Position.cumulativeOffset(C);A.setStyle({left:B[0]+"px",top:(B[1]+C.offsetHeight)+"px"})}ProtoCalendar.DateFormat=Class.create();Object.extend(ProtoCalendar.DateFormat,{MONTH_ABBRS:ProtoCalendar.LangFile.en.MONTH_ABBRS,MONTH_NAMES:ProtoCalendar.LangFile.en.MONTH_NAMES,WEEKDAY_ABBRS:ProtoCalendar.LangFile.en.WEEKDAY_ABBRS,WEEKDAY_NAMES:ProtoCalendar.LangFile.en.WEEKDAY_NAMES,formatRegexp:/(?:d{3,4}i|d{1,4}|m{1,4}|yy(?:yy)?|([hHMs])\1?|TT|tt|[lL])|.+?/g,zeroize:function(D,C){if(!C){C=2}D=String(D);for(var B=0,A="";B<(C-D.length);B++){A+="0"}return A+D}});ProtoCalendar.DateFormat.prototype={initialize:function(A){this.dateFormat=A;this.parserInited=false;this.formatterInited=false},format:function(A,D){if(!this.formatterInited){this.initFormatter()}if(!A){return""}var B=ProtoCalendar.LangFile[D||ProtoCalendar.LangFile.defaultLang];var C="";this.formatHandlers.each(function(E){C+=E(A,B)});return C},initFormatter:function(){var A=[];var C=this.dateFormat.match(ProtoCalendar.DateFormat.formatRegexp);for(var B=0,D=C.length;B<D;B++){switch(C[B]){case"d":A.push(function(F,E){return F.getDate()});break;case"dd":A.push(function(F,E){return ProtoCalendar.DateFormat.zeroize(F.getDate())});break;case"ddd":A.push(function(F,E){return ProtoCalendar.DateFormat.WEEKDAY_ABBRS[F.getDay()]});break;case"dddd":A.push(function(F,E){return ProtoCalendar.DateFormat.WEEKDAY_NAMES[F.getDay()]});break;case"dddi":A.push(function(F,E){return E.WEEKDAY_ABBRS[F.getDay()]});break;case"ddddi":A.push(function(F,E){return E.WEEKDAY_NAMES[F.getDay()]});break;case"m":A.push(function(F,E){return F.getMonth()+1});break;case"mm":A.push(function(F,E){return ProtoCalendar.DateFormat.zeroize(F.getMonth()+1)});break;case"mmm":A.push(function(F,E){return E.MONTH_ABBRS[F.getMonth()]});break;case"mmmm":A.push(function(F,E){return(E.MONTH_NAMES||ProtoCalendar.DateFormat)[F.getMonth()]});break;case"yy":A.push(function(F,E){return String(F.getFullYear()).substr(2)});break;case"yyyy":A.push(function(F,E){return F.getFullYear()});break;case"h":A.push(function(F,E){return F.getHours()%12||12});break;case"hh":A.push(function(F,E){return ProtoCalendar.DateFormat.zeroize(F.getHours()%12||12)});break;case"H":A.push(function(F,E){return F.getHours()});break;case"HH":A.push(function(F,E){return ProtoCalendar.DateFormat.zeroize(F.getHours())});break;case"M":A.push(function(F,E){return F.getMinutes()});break;case"MM":A.push(function(F,E){return ProtoCalendar.DateFormat.zeroize(F.getMinutes())});break;case"s":A.push(function(F,E){return F.getSeconds()});break;case"ss":A.push(function(F,E){return ProtoCalendar.DateFormat.zeroize(F.getSeconds())});break;case"l":A.push(function(F,E){return ProtoCalendar.DateFormat.zeroize(F.getMilliseconds(),3)});break;case"tt":A.push(function(F,E){return F.getHours()<12?"am":"pm"});break;case"TT":A.push(function(F,E){return F.getHours()<12?"AM":"PM"});break;default:A.push(createIdentity(C[B]))}}this.formatHandlers=A;this.formatterInited=true},parse:function(E){if(!this.parserInited){this.initParser()}if(!E){return undefined}var C=E.match(this.parserRegexp);if(!C){return undefined}var A=new Date();var D;for(var B=0,F=this.parseHandlers.length;B<F;B++){if(this.parseHandlers[B]!=undefined){(this.parseHandlers[B])(A,C[B+1])}}this.parseCallback(A);return A},initParser:function(){var B=[];var F="";var E=this.dateFormat.match(ProtoCalendar.DateFormat.formatRegexp);var A,C;for(var D=0,G=E.length;D<G;D++){F+="(";switch(E[D]){case"d":case"dd":F+="\\d{1,2}";B.push(function(H,I){H.setDate(I)});break;case"m":case"mm":F+="\\d{1,2}";B.push(function(H,I){H.setMonth(parseInt(I,10)-1)});break;case"yy":F+="\\d{2}";B.push(function(H,J){var I=parseInt(J,10);I=I<70?2000+I:1900+I;H.setFullYear(I)});break;case"yyyy":F+="\\d{4}";B.push(function(H,I){H.setFullYear(I)});break;case"h":case"hh":A=true;F+="\\d{1,2}";B.push(function(H,I){I=I%12||0;H.setHours(I)});break;case"H":case"HH":F+="\\d{1,2}";B.push(function(H,I){H.setHours(I)});break;case"M":case"MM":F+="\\d{1,2}";B.push(function(H,I){H.setMinutes(I)});break;case"s":case"ss":F+="\\d{1,2}";B.push(function(H,I){H.setSeconds(I)});break;case"l":F+="\\d{1,3}";B.push(function(H,I){H.setMilliSeconds(I)});break;case"tt":F+="am|pm";B.push(function(H,I){C=I});break;case"TT":F+="AM|PM";B.push(function(H,I){C=I.toLowerCase()});break;case"mmm":case"mmmm":case"ddd":case"dddd":case"dddi":case"ddddi":F+=".+?";B.push(undefined);break;default:F+=E[D];B.push(undefined)}F+=")"}this.parserRegexp=new RegExp(F);this.parseHandlers=B;if(C=="pm"&&A){this.parseCallback=this.normalizeHour.bind(this)}else{this.parseCallback=function(){}}this.parserInited=true},normalizeHour:function(B){var A=B.getHours();A=A==12?0:A+12;B.setHours(A)}};function createIdentity(A){return function(){return A}}function selectTimeOption(A,B){var C=B-0;C=C<10?"0"+C:C;selectOption(A,C)}function selectOption(A,D){var E=$(A);var B=E.options;for(var C=0;C<B.length;C++){if(B[C].value===D.toString()){B[C].selected=true;return }}}var SelectCalendar=Class.create();SelectCalendar.createOnLoaded=function(A,B){BaseCalendar.bindOnLoad(function(){new SelectCalendar(A,B)})};Object.extend(SelectCalendar.prototype,BaseCalendar.prototype);Object.extend(SelectCalendar.prototype,{initialize:function(A,B){this.yearSelect=$(A.yearSelect);this.monthSelect=$(A.monthSelect);this.daySelect=$(A.daySelect);this.initializeOptions(B);if(this.options.enableHourMinute){this.hourSelect=$(A.hourSelect);this.minuteSelect=$(A.minuteSelect);if(this.options.enableSecond){this.secondSelect=$(A.secondSelect)}}this.options=Object.extend({alignTo:A.yearSelect},this.options);this.initializeBase();this.initializeSelect()},initializeSelect:function(){if(this.getSelectedDate()){this.onSelectChange()}else{this.onCalendarChange()}},doObserveEvents:function(){this.yearSelect.observe("change",this.onSelectChange.bind(this));this.monthSelect.observe("change",this.onSelectChange.bind(this));this.daySelect.observe("change",this.onSelectChange.bind(this));if(this.options.enableHourMinute){this.hourSelect.observe("change",this.onSelectChange.bind(this));this.minuteSelect.observe("change",this.onSelectChange.bind(this));if(this.options.enableSecond){this.secondSelect.observe("change",this.onSelectChange.bind(this))}}this.addChangeHandler(this.changeSelectValue.bind(this))},onSelectChange:function(){var A=this.getSelectedDate();if(!A){return }this.calendarController.selectDate(A,true);if(this.options.enableHourMinute){this.calendarController.calendarRender.selectTime(A)}this.onCalendarChange()},changeSelectValue:function(){var A=this.calendarController.getSelectedDate();if(A){selectOption(this.yearSelect,A.getFullYear());selectOption(this.monthSelect,A.getMonth()+1);selectOption(this.daySelect,A.getDate());if(this.options.enableHourMinute){selectTimeOption(this.hourSelect,A.getHours());selectTimeOption(this.minuteSelect,A.getMinutes());if(this.options.enableSecond){selectTimeOption(this.secondSelect,A.getSeconds())}}}},getSelectedDate:function(){var A=new Date();A.setFullYear(this.yearSelect.value);A.setMonth(this.monthSelect.value-1);A.setDate(this.daySelect.value);if(this.options.enableHourMinute){A.setHours(this.hourSelect.value-0);A.setMinutes(this.minuteSelect.value-0);if(this.options.enableSecond){A.setSeconds(this.secondSelect.value-0)}}if(isNaN(A.getTime())||A.getTime()<0){return undefined}else{return A}}})