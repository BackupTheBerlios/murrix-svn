<input class="form" id="<?=$args['varname']?>" name="<?=$args['varname']?>" type="text" value="<?=$args['value']?>"/> <a name="button<?=$args['id']?>" id="button<?=$args['id']?>" href="javascript:void(null);" onclick="var calendar<?=$args['id']?>=new CalendarPopup('popupCalendarDiv');calendar<?=$args['id']?>.setWeekStartDay(1);calendar<?=$args['id']?>.select(document.getElementById('<?=$args['formname']?>').<?=$args['varname']?>,'button<?=$args['id']?>','yyyy-MM-dd');"><?=img(imgpath("calendar.jpg"), ucf(i18n("calendar")))?></a>