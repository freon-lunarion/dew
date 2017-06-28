$('body').on('click', '.panel-group>.panel>.panel-heading>.panel-title>a', function(event) {

  var closeIcon = 'fa fa-chevron-right';
  var openIcon = 'fa fa-chevron-down';

  var elm = $(this);
  var elmGroup = elm.parent('.panel-title').parent('.panel-heading').parent('.panel').parent('.panel-group');

  var elmPanel = elm.parent('.panel-title').parent('.panel-heading').parent('.panel').children('div');

  var elmIcon  = elm.children('i');

  elmGroup.children('.panel').children('.panel-heading').children('.panel-title').children('a').children('i').attr('class', closeIcon);
  event.preventDefault();

  if (elmPanel.hasClass('in')) {
    elmIcon.attr('class', closeIcon);
  } else {
    elmIcon.attr('class', openIcon);
  }

});

$('body').on('click', '.box-group>.box>.box-heading>.box-title>a', function(event) {

  var closeIcon = 'fa fa-chevron-right';
  var openIcon = 'fa fa-chevron-down';

  var elm = $(this);
  var elmGroup = elm.parent('.box-title').parent('.box-heading').parent('.box').parent('.box-group');

  var elmPanel = elm.parent('.box-title').parent('.box-heading').parent('.box').children('div');

  var elmIcon  = elm.children('i');

  elmGroup.children('.box').children('.box-heading').children('.box-title').children('a').children('i').attr('class', closeIcon);
  event.preventDefault();

  if (elmPanel.hasClass('in')) {
    elmIcon.attr('class', closeIcon);
  } else {
    elmIcon.attr('class', openIcon);
  }

});
